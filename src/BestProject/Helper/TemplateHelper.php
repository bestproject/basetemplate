<?php

namespace BestProject\Helper;

use Exception;
use JEventDispatcher;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Version;
use Joomla\Registry\Registry;
use RuntimeException;

/**
 * Template helper.
 *
 * @since 1.5.0
 */
abstract class TemplateHelper
{
	/**
	 * Conversion settings for lonelyLetter method.
	 *
	 * @since 1.5.0
	 */
	protected const  LONELY = [
		[' i ', ' z ', ' o ', ' a ', ' u '],
		[' i&nbsp;', ' z&nbsp;', ' o&nbsp;', ' a&nbsp;', ' u&nbsp;']
	];
	/**
	 * Template params.
	 *
	 * @var Registry
	 *
	 * @since 1.0.0
	 */
	private static $params;
	/**
	 * Current template name.
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	private static $template;
	/**
	 * Scripts that should be placed in the bottom of body tag.
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	private static $scripts = [];
	/**
	 * Manifest cache avoiding multiple disc reads.
	 *
	 * @var array
	 *
	 * @since 1.5.0
	 */
	private static $manifestCache;

	/**
	 * Entry points cache.
	 *
	 * @var array
	 *
	 * @since 1.5.0
	 */
	private static $entrypointsCache;

	/**
	 * Render scripts that should be in the top of head section.
	 *
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public static function renderCodeHeadTop(): string
	{
		return static::getParams()->get('code_head_top', '');
	}

	/**
	 * Get template parameters.
	 *
	 * @return Registry
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	private static function getParams(): Registry
	{
		if (is_null(static::$params))
		{
			static::$params = Factory::getApplication()->getTemplate(true)->params;
		}

		return static::$params;
	}

	/**
	 * Render scripts that should be in the bottom of head section.
	 *
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public static function renderCodeHeadBottom(): string
	{
		return static::getParams()->get('code_head_bottom', '');
	}

	/**
	 * Render scripts that should be in the top of body section.
	 *
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public static function renderCodeBodyTop(): string
	{
		return static::getParams()->get('code_body_top', '');
	}

	/**
	 * Render scripts that should be in the bottom of body section.
	 *
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public static function renderCodeBodyBottom(): string
	{
		return static::getParams()->get('code_body_bottom', '');
	}

	/**
	 * Convert fields array mapped by ID to NAME mapped array.
	 *
	 * @param   array|object  $item     Fields array or an object with jcfields property.
	 * @param   string        $context  Plugin context eg. com_content.article
	 *
	 * @return ObjectFields
	 *
	 * @since 1.0.0
	 */
	public static function getFieldsMap($item, string $context = 'com_content.article'): ObjectFields
	{

		// Find fields list
		$fields = $item;

		// If this is object, try to look for its fields list
		if (is_object($item))
		{

			// Fields property doesn't exists so load them using fields plugin
			if (!property_exists($item, 'jcfields'))
			{
				static::getObjectFields($item, $context);
			}

			$fields = $item->jcfields;
		}

		// Map fields
		$map = [];
		foreach ($fields as $id => $field)
		{
			$map[$field->name] = &$fields[$id];
		}

		// Return item fields object
		return new ObjectFields($map, $context);
	}

	/**
	 * Load object custom fields using Content plugin onContentPrepare event.
	 *
	 * @param   object  $item     Object holding the fields.
	 * @param   string  $context  Plugin context. eg. com_content.article
	 *
	 * @since 1.5
	 */
	public static function getObjectFields(object $item, string $context = 'com_content.article'): void
	{
		$dispatcher = JEventDispatcher::getInstance();
		PluginHelper::importPlugin('content');

		// Make sure event has something to work on
		// If this is an article, join its text into one property
		if (!property_exists($item, 'text') && property_exists($item, 'introtext') && property_exists($item, 'fulltext'))
		{
			$item->text = $item->introtext . $item->fulltext;
		}

		$dispatcher->trigger('onContentPrepare', [$context, &$item, &$item->params, 0]);
	}

	/**
	 * Include entry point assets from manifest file.
	 *
	 * @param   string  $name  Name of the entry point.
	 *
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	public static function addEntryPointAssets(string $name): void
	{
		$entrypoints = self::getEntryPoints()['entrypoints'];

		// If there is anything from this entrypoint
		if (array_key_exists($name, $entrypoints))
		{

			$attribs    = ['data-entrypoint' => $name];
			$entrypoint = $entrypoints[$name];

			// If there are css styles in this entry point
			if (array_key_exists('css', $entrypoint))
			{
				foreach ($entrypoint['css'] as $path)
				{
					Factory::getDocument()->addStyleSheet(self::getAssetUrl($path), ['version' => 'auto'], $attribs);
				}
			}

			// If there are js scripts in this entry point
			if (array_key_exists('js', $entrypoint))
			{
				foreach ($entrypoint['js'] as $path)
				{
					self::addScript(self::getAssetUrl($path), $attribs);
				}
			}
		}
	}

	/**
	 * Get entry points array.
	 *
	 * @return array
	 * @throws Exception
	 *
	 * @since 1.5
	 */
	public static function getEntryPoints(): array
	{
		if (is_null(static::$entrypointsCache))
		{
			$entrypoints_path = JPATH_THEMES . '/' . static::getTemplate() . '/assets/build/entrypoints.json';

			static::$entrypointsCache = [];
			if (file_exists($entrypoints_path))
			{
				static::$entrypointsCache = json_decode(file_get_contents($entrypoints_path), true, 512, JSON_THROW_ON_ERROR);
			}
		}

		return static::$entrypointsCache;
	}

	/**
	 * Get current template name.
	 *
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	private static function getTemplate(): string
	{
		if (is_null(static::$template))
		{
			static::$template = Factory::getApplication()->getTemplate();
		}

		return static::$template;
	}

	/**
	 * Get asset url using manifest.json build by webpack in /templates/THEME_NAME/assets/build.
	 *
	 * @param   string  $url  Internal URL (eg. templates/test/assets/build/theme.css)
	 *
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public static function getAssetUrl(string $url): string
	{
		$public_url  = $url;
		$manifest    = static::getManifest();
		$relativeUrl = ltrim($url, '/');
		if (array_key_exists($relativeUrl, $manifest))
		{
			$public_url = $manifest[$relativeUrl];
		}

		return $public_url;
	}

	/**
	 * Return manifest array.
	 *
	 * @return array
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public static function getManifest(): array
	{
		if (is_null(static::$manifestCache))
		{
			$manifest_path = JPATH_THEMES . '/' . static::getTemplate() . '/assets/build/manifest.json';

			static::$manifestCache = [];
			if (file_exists($manifest_path))
			{
				static::$manifestCache = json_decode(file_get_contents($manifest_path), true, 512, JSON_THROW_ON_ERROR);
			}
		}

		return static::$manifestCache;
	}

	/**
	 * Add asynchronous script.
	 *
	 * @param   string  $url         URL for a script file.
	 * @param   array   $attributes  Tag attributes
	 *
	 * @since 1.0.0
	 */
	public static function addScript(string $url, array $attributes = []): void
	{
		if (!array_key_exists($url, self::$scripts))
		{
			self::$scripts += [$url => ''];
			self::addToScriptsBuffer(self::renderScriptUrl($url, $attributes));
		}
	}

	/**
	 * Update scripts buffer.
	 *
	 * @param   string  $content  String to add to the buffer.
	 *
	 * @since  1.5
	 */
	public static function addToScriptsBuffer(string $content): void
	{
		// Get current buffer
		/**
		 * @var HtmlDocument $doc
		 */
		$doc    = Factory::getDocument();
		$buffer = $doc->getBuffer('scripts', 'scripts');

		// Update buffer
		$doc->setBuffer($buffer . $content . "\n", ['type' => 'scripts', 'name' => 'scripts', 'title' => '']);
	}

	/**
	 * Render script src tag.
	 *
	 * @param   string  $url
	 * @param   array   $attributes
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public static function renderScriptUrl(string $url, array $attributes = []): string
	{
		$mediaVersion = Factory::getDocument()->getMediaVersion();

		$script_url = (substr_compare($url, 'http', 0, 4) === 0 ? $url : $url . '?' . $mediaVersion);

		return '<script src="' . $script_url . '"' . static::renderAttributes($attributes) . '></script>';
	}

	/**
	 * Render HTML attributes to a string.
	 *
	 * @param   array  $attributes  Attributes key=>valur table.
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	public static function renderAttributes(array $attributes = []): string
	{
		$attributes_string = '';
		foreach ($attributes as $attribute => $value)
		{
			$attributes_string .= ' ' . $attribute . (!empty($value) ? '="' . $value . '"' : '');
		}

		return (!empty($attributes_string) ? ' ' . trim($attributes_string) : '');
	}

	/**
	 * Combining system scripts from /media directory into chunks per page.
	 * @note  Use after </html> tag.
	 *
	 * @param   array  $scripts  Reference to document scripts array.
	 *
	 * @since 1.0.0
	 */
	public static function combineSystemScripts(array &$scripts): void
	{
		$media = [];
		$files = [];

		// Look for scripts included from media directory
		$entry_details = [];
		foreach ($scripts as $path => $details)
		{

			if (substr_compare($path, '/media/', 0, 7) === 0)
			{
				$media[]  = $path;
				$files [] = pathinfo($path, PATHINFO_FILENAME);
				if (empty($entry_details))
				{
					$entry_details = $details;
				}
			}

		}

		// If there is anything to combine
		if (!empty($media))
		{

			// Create a build sign
			$sign = Version::MAJOR_VERSION . '-' . Version::MINOR_VERSION . '-' . Version::PATCH_VERSION . '-';
			$sign .= md5(implode('|', $files));

			$cache_path = '/cache/system/' . $sign . '.js';

			// Combine files and store them in a cache directory
			if (!file_exists(JPATH_ROOT . $cache_path))
			{

				$buffer = '';
				foreach ($media as $media_path)
				{
					$buffer .= ';' . file_get_contents(JPATH_ROOT . '/' . $media_path);
				}

				// Create cache directory
				if (!file_exists(JPATH_CACHE . '/system') && !mkdir($concurrentDirectory = JPATH_CACHE . '/system', 0755) && !is_dir($concurrentDirectory))
				{
					throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
				}

				file_put_contents(JPATH_ROOT . $cache_path, trim($buffer, ';'));
			}

			// Create combined scripts entry
			$entry = [
				$cache_path => $entry_details
			];

			// Remove references to old scripts
			foreach ($media as $path)
			{
				unset($scripts[$path]);
			}

			// Swap scripts definitions for a new one
			$scripts = array_merge($entry, $scripts);
		}
	}

	/**
	 * Return title divided on |
	 *
	 * @param   string  $title  Title to split.
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public static function splitTitle(string $title): string
	{
		$parts = explode('|', $title);

		return '<div>' . implode('</div><div>', $parts) . '</div>';
	}

	/**
	 * Convert lonely letters in text to always join next word.
	 *
	 * @param   string  $text  Text to convert.
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public static function lonelyLetter(string $text): string
	{
		return str_replace(static::LONELY[0], static::LONELY[1], $text);
	}

	/**
	 * Add asynchronous script declaration
	 *
	 * @param   string  $code        Script code.
	 * @param   array   $attributes  Tag attributes
	 *
	 * @since 1.0.0
	 */
	public static function addScriptDeclaration(string $code, array $attributes = []): void
	{
		if (!array_key_exists($code, self::$scripts))
		{
			self::$scripts += [$code => ''];
			self::addToScriptsBuffer(self::renderScriptDeclaration($code, $attributes));
		}
	}

	/**
	 * Render script declaration tag.
	 *
	 * @param   string  $code
	 * @param   array   $attributes
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public static function renderScriptDeclaration(string $code, array $attributes = []): string
	{
		return '<script' . static::renderAttributes($attributes) . '>' . $code . '</script>';
	}

}