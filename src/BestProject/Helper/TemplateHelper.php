<?php

namespace BestProject\Helper;

use BestProject\ObjectFields;
use Exception;
use JEventDispatcher;
use Joomla\CMS\Event\Content\ContentPrepareEvent;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\CMS\WebAsset\WebAssetManagerInterface;
use Joomla\Event\Event;
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
        if (is_null(static::$params)) {
            static::$params = Factory::getApplication()->getTemplate(true)->params;
        }

        return static::$params;
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
    public static function getTemplate(): string
    {
        if (is_null(static::$template)) {
            static::$template = Factory::getApplication()->getTemplate();
        }

        return static::$template;
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
     * @throws Exception
     * @since 1.0.0
     */
    public static function getFieldsMap($item, string $context = 'com_content.article'): ObjectFields
    {

        // Find fields list
        $fields = $item;

        // If this is object, try to look for its fields list
        if (is_object($item)) {

            // Fields property doesn't exist so load them using fields plugin
            if (!property_exists($item, 'jcfields')) {
                static::getObjectFields($item, $context);
            }

            $fields = $item->jcfields;
        }

        // Map fields
        $map = [];
        foreach ($fields as $id => $field) {
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
     * @throws Exception
     * @since 1.5
     */
    public static function getObjectFields(object $item, string $context = 'com_content.article'): void
    {
        $dispatcher = Factory::getApplication()->getDispatcher();
        $event = new ContentPrepareEvent('onContentPrepare', [$context, &$item, &$item->params, 0]);

        PluginHelper::importPlugin('content');

        // Make sure event has something to work on
        // If this is an article, join its text into one property
        if (!property_exists($item, 'text') && property_exists($item, 'introtext') && property_exists($item, 'fulltext')) {
            $item->text = $item->introtext . $item->fulltext;
        }

        // Check if requirements are met
        if( !property_exists($item, 'text') ) {
            throw new RuntimeException('This object has no [text] property. Unable to build objects map');
        }

        // Dispatch event
        $dispatcher->dispatch($event->getName(), $event);
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
     * Remove styles and script that contain a certain text in its name.
     *
     * @param   WebAssetManagerInterface  $webAssetManager  Website assets manager.
     * @param   array                     $scripts          Array of script names parts.
     * @param   array                     $styles           Array of style names parts.
     *
     * @return void
     * @throws Exception
     */
    public static function removeAssets(WebAssetManagerInterface $webAssetManager, array $scripts = [], array $styles = []): void
    {
        Factory::getApplication()->registerEvent('onBeforeRender', function() use ($webAssetManager, $scripts, $styles){
            /**
             * @var WebAssetManager $wa
             */

            $registered_scripts = $webAssetManager->getAssets('script');
            $registered_styles = $webAssetManager->getAssets('style');

            foreach( $registered_scripts as $key=>$script ) {
                foreach( $scripts as $remove ) {
                    if( stripos($key, $remove)!==false ) {
                        $webAssetManager->disableAsset('script', $key);
                    }
                }
            }

            foreach( $registered_styles as $key=>$style ) {
                foreach( $styles as $remove ) {
                    if( stripos($key, $remove)!==false ) {
                        $webAssetManager->disableAsset('style', $key);
                    }
                }
            }

        });

    }

}