<?php

namespace BestProject;

use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;
use Joomla\CMS\Version;
use Joomla\Registry\Registry;

/**
 * Template helper.
 */
abstract class TemplateHelper
{
    /**
     * Template params.
     *
     * @var Registry
     */
    private static $params;

    /**
     * Current templae name.
     *
     * @var string
     */
    private static $template;

    /**
     * Scripts that should be placed in the bottom of body tag.
     *
     * @var array
     */
    private static $scripts = [];

    /**
     * Render scripts that should be in the top of head section.
     *
     * @return string
     */
    public static function renderCodeHeadTop(): string
    {
        return self::getParams()->get('code_head_top', '');
    }

    /**
     * Render scripts that should be in the bottom of head section.
     *
     * @return string
     */
    public static function renderCodeHeadBottom(): string
    {
        return self::getParams()->get('code_head_bottom', '');
    }

    /**
     * Render scripts that should be in the top of body section.
     *
     * @return string
     */
    public static function renderCodeBodyTop(): string
    {
        return self::getParams()->get('code_body_top', '');
    }

    /**
     * Render scripts that should be in the bottom of body section.
     *
     * @return string
     */
    public static function renderCodeBodyBottom(): string
    {
        return self::getParams()->get('code_body_bottom', '');
    }

    /**
     * Get template parameters.
     *
     * @return Registry
     */
    private static function getParams(): Registry
    {
        if (is_null(self::$params)) {
            self::$params = Factory::getApplication()->getTemplate(true)->params;
        }

        return self::$params;
    }

    /**
     * Get current template name.
     *
     * @return string
     */
    private static function getTemplate(): string
    {
        if (is_null(self::$template)) {
            self::$template = Factory::getApplication()->getTemplate();
        }

        return self::$template;
    }

    /**
     * Add asynchronous (Firefox/Chrome) scripts.
     *
     * @param string    $url        URL for a script file.
     * @param array     $attributes String for inline script or array of tag attributes
     */
    public static function addAsyncScripts(string $url, array $attributes = [])
    {
        self::$scripts = array_merge(self::$scripts, [$url => $attributes]);
    }

    /**
     * Render asynchronous scripts.
     */
    public static function renderAsyncScripts()
    {
        $buffer       = '';
        /* @var $doc HtmlDocument */
        $doc          = Factory::getDocument();
        $mediaVersion = $doc->getMediaVersion();

        foreach (self::$scripts AS $url => $attributes) {

            if (stripos($url, "\n") === false) {
                $attributes_string = '';
                foreach ($attributes AS $attribute => $value) {
                    $attributes_string .= ' '.$attribute.(!empty($value) ? '="'.$value.'"' : '');
                }

                $script_url = (substr_compare($url, 'http', 0, 4) === 0 ? $url : $url.'?'.$mediaVersion);

                $buffer .= '<script src="'.$script_url.'" type="text/javascript" '.trim($attributes_string).'></script>'."\n";
            } else {
                $buffer .= '<script type="text/javascript">'.$url.'</script>'."\n";
            }
        }

        return $buffer;
    }

    /**
     * Convert fields array mapped by ID to NAME mapped array.
     *
     * @param array $fields Fields array to convert.
     *
     * @return array
     */
    public static function &getFieldsMap(&$fields): array
    {
        $map = [];
        foreach ($fields AS $id => &$field) {
            $map[$field->name] = &$fields[$id];
        }

        return $map;
    }

    /**
     * Get asset url using manifest.json build by webpack in /templates/THEME_NAME/assets/build.
     *
     * @param string $url Internal URL (eg. templates/test/assets/build/theme.css)
     *
     * @return string
     */
    public static function getAssetUrl(string $url): string
    {
        $public_url  = $url;
        $manifestUrl = JPATH_THEMES.'/'.self::getTemplate().'/assets/build/manifest.json';

        if (file_exists($manifestUrl)) {
            $manifest = file_get_contents($manifestUrl);
            $manifest = json_decode($manifest, true);
            if (key_exists(ltrim($url, '/'), $manifest)) {
                $public_url = $manifest[ltrim($url, '/')];
            }
        }

        return $public_url;
    }

    /**
     * Combining system scripts from /media directory into chunks per page.
     * @note Use after </html> tag.
     *
     * @param array $scripts Reference to document scripts array.
     */
    public static function combineSystemScripts(&$scripts)
    {
        $origin = $scripts;

        $media = [];
        $files = [];

        // Look for scripts included from media directory
        $entry_details = [];
        foreach( $scripts AS $path=>$details ) {

            if( substr_compare($path, '/media/', 0, 7)===0 ) {
                $media[] = $path;
                $files [] = pathinfo($path, PATHINFO_FILENAME);
                $entry_details = $details;
            }

        }

        // If there is anything to combine
        if( !empty($media) ) {

        }

        // Create a build sign
        $sign = Version::MAJOR_VERSION.'-'.Version::MINOR_VERSION.'-'.Version::PATCH_VERSION.'-';
        $sign.= md5(implode('|', $files));

        $cache_path = '/cache/system/'.$sign.'.js';

        // Combine files and store them in a cache directory
        if( !file_exists(JPATH_ROOT.$cache_path) ) {

            $buffer = '';
            foreach( $media AS $media_path ) {
                $buffer.= ';'.file_get_contents(JPATH_ROOT.'/'.$media_path);
            }

            // Create cache directory
            if ( !file_exists(JPATH_CACHE.'/system') ) {
                mkdir(JPATH_CACHE.'/system', 0755);
            }

            file_put_contents(JPATH_ROOT.$cache_path, trim($buffer, ';'));
        }

        // Create combined scripts entry
        $entry = [
            $cache_path => $entry_details
        ];

        // Remove references to old scripts
        foreach( $media as $path ) {
            unset($scripts[$path]);
        }

        // Swap scripts definitions for a new one
        $scripts = array_merge($entry, $scripts);

    }
}