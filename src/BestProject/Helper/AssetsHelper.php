<?php

namespace BestProject\Helper;

use Exception;
use Joomla\Application\WebApplication;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\Component\Config\Administrator\Controller\ApplicationController;
use JUri;
use RuntimeException;

class AssetsHelper
{

    /**
     * Assets base path.
     *
     * @var string
     *
     * @since 2.0.0
     */
    protected static $assets_root_path = '';

    /**
     * Plugin entry points.
     *
     * @var array[]
     *
     * @since 2.0.0
     */
    protected static $entryPoints = [];

    /**
     * @var WebAssetManager
     */
    protected static $assetsManager;

    /**
     * Get entry points from a specific config.
     *
     * @return array
     *
     * @throws Exception
     * @since 2.0.0
     */
    public static function getEntryPoints(): array
    {
        if (self::$entryPoints === []) {
            $path              = self::getAssetsRootPath() . '/entrypoints.json';

            // Make sure manifest file exists
            if( !file_exists($path) ) {
                throw new RuntimeException("Unable to find manifest file in: $path");
            }

            $manifest          = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY);
            self::$entryPoints = $manifest['entrypoints'];
        }

        return self::$entryPoints;
    }

    /**
     * Get Assets Manager instance.
     *
     * @return WebAssetManager
     * @throws \Exception
     */
    public static function getAssetsManager(): WebAssetManager
    {
        if( !isset(self::$assetsManager) ) {
            /**
             * @var CMSApplication $app
             */
            $app = Factory::getApplication();
            self::$assetsManager = $app->getDocument()->getWebAssetManager();
        }

        return self::$assetsManager;
    }

    /**
     * Get plugin assets root path.
     *
     * @return string
     *
     * @throws Exception
     * @since 2.0.0
     */
    public static function getAssetsRootPath(): string
    {
        if (self::$assets_root_path === '') {
            /**
             * @var CMSApplication $app
             */
            $app = Factory::getApplication();
            self::$assets_root_path = JPATH_SITE . '/media/templates/site/'.$app->getTemplate();
        }

        return self::$assets_root_path;
    }

    /**
     * Include entry point assets into the document.
     *
     * @param   string           $entryPoint
     * @param   WebAssetManager|null  $webAssetManager
     *
     * @return void
     *
     * @throws Exception
     * @since 1.1
     */
    public static function addEntryPointAssets(string $entryPoint, WebAssetManager $webAssetManager = null): void
    {
        $entryPoints = self::getEntryPoints();

        // If no custom assets manager is provided, use default one
        if( is_null($webAssetManager) ) {
            $webAssetManager = self::getAssetsManager();
        }

        // Check if this entry point exists
        if (array_key_exists($entryPoint, $entryPoints)) {

            // Add Styles
            if (array_key_exists('css', $entryPoints[$entryPoint])) {
                $idx = 1;
                foreach ($entryPoints[$entryPoint]['css'] as $stylesheet) {
                    $webAssetManager->registerAndUseStyle($entryPoint.'-style-'.$idx, JUri::root(true) . trim($stylesheet, '/'));
                    $idx++;
                }
            }

            // Add scripts
            if (array_key_exists('js', $entryPoints[$entryPoint])) {
                $idx = 1;
                foreach ($entryPoints[$entryPoint]['js'] as $script) {
                    $webAssetManager->registerAndUseScript($entryPoint.'-script'.$idx, JUri::root(true) . trim($script, '/'));
                    $idx++;
                }
            }
        }
    }

    /**
     * Add script declaration.
     *
     * @param   string  $code Code to add.
     * @param   array   $dependencies   Array of dependencies keys.
     *
     * @return void
     * @throws Exception
     */
    public static function addScriptDeclaration(string $code, array $dependencies = []): void
    {
        $assetsManager = self::getAssetsManager();
        $assetsManager->addInlineScript($code, [], [], $dependencies);
    }

}