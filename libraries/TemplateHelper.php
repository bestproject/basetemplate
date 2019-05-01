<?php

namespace BestProject;

use Exception;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;
use Joomla\CMS\Version;
use Joomla\Registry\Registry;

/**
 * Template helper.
 *
 * @since 1.5.0
 */
abstract class TemplateHelper
{
	/**
	 * Template params.
	 *
	 * @var Registry
	 * @since 1.0.0
	 */
	private static $params;

	/**
	 * Current templae name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private static $template;

	/**
	 * Scripts that should be placed in the bottom of body tag.
	 *
	 * @var array
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
	 * Render scripts that should be in the top of head section.
	 *
	 * @return string
	 * @throws Exception
	 * @since 1.0.0
	 */
	public static function renderCodeHeadTop(): string
	{
		return self::getParams()->get('code_head_top', '');
	}

	/**
	 * Get template parameters.
	 *
	 * @return Registry
	 * @throws Exception
	 * @since 1.0.0
	 */
	private static function getParams(): Registry
	{
		if (is_null(self::$params))
		{
			self::$params = Factory::getApplication()->getTemplate(true)->params;
		}

		return self::$params;
	}

	/**
	 * Render scripts that should be in the bottom of head section.
	 *
	 * @return string
	 * @throws Exception
	 * @since 1.0.0
	 */
	public static function renderCodeHeadBottom(): string
	{
		return self::getParams()->get('code_head_bottom', '');
	}

	/**
	 * Render scripts that should be in the top of body section.
	 *
	 * @return string
	 * @throws Exception
	 * @since 1.0.0
	 */
	public static function renderCodeBodyTop(): string
	{
		return self::getParams()->get('code_body_top', '');
	}

	/**
	 * Render scripts that should be in the bottom of body section.
	 *
	 * @return string
	 * @throws Exception
	 * @since 1.0.0
	 */
	public static function renderCodeBodyBottom(): string
	{
		return self::getParams()->get('code_body_bottom', '');
	}

	/**
	 * Render asynchronous scripts.
	 *
	 * @since 1.0.0
	 */
	public static function renderScripts()
	{
		$buffer = '';

		/* @var $doc HtmlDocument */
		$doc          = Factory::getDocument();
		$mediaVersion = $doc->getMediaVersion();

		foreach (self::$scripts AS $url => $attributes)
		{

			$attributes_string = '';
			foreach ($attributes AS $attribute => $value)
			{
				$attributes_string .= ' ' . $attribute . (!empty($value) ? '="' . $value . '"' : '');
			}
			$attributes_string = (!empty($attributes_string) ? ' '.trim($attributes_string):'');

			if (stripos($url, "\n") === false)
			{
				$script_url = (substr_compare($url, 'http', 0, 4) === 0 ? $url : $url . '?' . $mediaVersion);
				$buffer .= '<script src="' . $script_url . '"' . $attributes_string . '></script>' . "\n";
			}
			else
			{
				$buffer .= '<script' . $attributes_string . '>' . $url . '</script>' . "\n";
			}
		}

		return $buffer;
	}

	/**
	 * Convert fields array mapped by ID to NAME mapped array.
	 *
	 * @param   array|object  $item  Fields array or an object with jcfields property.
	 *
	 * @return ObjectFields
	 * @since 1.0.0
	 */
	public static function getFieldsMap($item): ObjectFields
	{

		// Find fields list
		$fields = $item;
		if (is_object($item))
		{
			$fields = $item->jcfields;
		}

		// Map fields
		$map = [];
		foreach ($fields AS $id => &$field)
		{
			$map[$field->name] = &$fields[$id];
		}

		// Return item fields object
		return new ObjectFields($map);
	}

	/**
	 * Get asset url using manifest.json build by webpack in /templates/THEME_NAME/assets/build.
	 *
	 * @param   string  $url  Internal URL (eg. templates/test/assets/build/theme.css)
	 *
	 * @return string
	 * @throws Exception
	 * @since 1.0.0
	 */
	public static function getAssetUrl(string $url): string
	{
		$public_url  = $url;
		$manifest    = static::getManifest();
		$relativeUrl = ltrim($url, '/');
		if (key_exists($relativeUrl, $manifest))
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
	 * @since 1.0.0
	 */
	public static function getManifest(): array
	{
		if (is_null(static::$manifestCache))
		{
			$manifest_path = JPATH_THEMES . '/' . self::getTemplate() . '/assets/build/manifest.json';

			static::$manifestCache = [];
			if (file_exists($manifest_path))
			{
				static::$manifestCache = json_decode(file_get_contents($manifest_path), true);
			}
		}

		return static::$manifestCache;
	}

	/**
	 * Get current template name.
	 *
	 * @return string
	 * @throws Exception
	 * @since 1.0.0
	 */
	private static function getTemplate(): string
	{
		if (is_null(self::$template))
		{
			self::$template = Factory::getApplication()->getTemplate();
		}

		return self::$template;
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
	public static function addEntryPointAssets(string $name)
	{
		$manifest = static::getManifest();

		// Assets files
		$cssFilePath = 'templates/' . static::getTemplate() . '/assets/build/' . $name . '.css';
		$jsFilePath  = 'templates/' . static::getTemplate() . '/assets/build/' . $name . '.js';

		// If css asset exists
		if (key_exists($cssFilePath, $manifest))
		{
			Factory::getDocument()->addStyleSheet($manifest[$cssFilePath], ['version' => 'auto']);
		}

		// If js asset exists
		if (key_exists($jsFilePath, $manifest))
		{
			static::addScript($manifest[$jsFilePath]);
		}
	}

	/**
	 * Add asynchronous script.
	 *
	 * @param   string  $url         URL for a script file.
	 * @param   array   $attributes  Tag attributes
	 *
	 * @since 1.0.0
	 */
	public static function addScript(string $url, array $attributes = [])
	{
		self::$scripts = array_merge(self::$scripts, [$url => $attributes]);
	}

	/**
	 * Add asynchronous script declaration
	 *
	 * @param   string  $code        Script code.
	 * @param   array   $attributes  Tag attributes
	 *
	 * @since 1.0.0
	 */
	public static function addScriptDeclaration(string $code, array $attributes = [])
	{
		static::addScript($code, $attributes);
	}

	/**
	 * Combining system scripts from /media directory into chunks per page.
	 * @note  Use after </html> tag.
	 *
	 * @param   array  $scripts  Reference to document scripts array.
	 *
	 * @since 1.0.0
	 */
	public static function combineSystemScripts(&$scripts)
	{
		$media = [];
		$files = [];

		// Look for scripts included from media directory
		$entry_details = [];
		foreach ($scripts AS $path => $details)
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
				foreach ($media AS $media_path)
				{
					$buffer .= ';' . file_get_contents(JPATH_ROOT . '/' . $media_path);
				}

				// Create cache directory
				if (!file_exists(JPATH_CACHE . '/system'))
				{
					mkdir(JPATH_CACHE . '/system', 0755);
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
}