<?php

namespace BestProject\Command;

use Composer\Script\Event;
use Exception;

/**
 * Base template build composer command.
 *
 * @package     BestProject\Command
 *
 * @since       2.0
 */
class Build
{

	/**
	 * Template name. eg. basetemplate
	 *
	 * @var string
	 *
	 * @since 1.0
	 */
	protected $name;

	/**
	 * Template base directory. eg. /var/www/example.com/templates/basetemplate
	 *
	 * @var string
	 *
	 * @since 1.0
	 */
	protected $base;

	/**
	 * System root directory. eg. /var/www/example.com
	 *
	 * @var string
	 *
	 * @since 1.0
	 */
	protected $root;

	/**
	 * Run all required methods to create new theme.
	 *
	 * @since  1.0
	 */
	public function __construct()
	{

		// Get required directories
		$this->base = dirname(__DIR__, 3);
		$this->root = dirname($this->base, 2);
		$this->name = basename($this->base);

		// If template can't be build
		if (!$this->canBuild())
		{
			$this->write("You are trying to build without changing template name. Change /templates/basetemplate to /templates/YOUR_THEME_NAME and try again.");
		}
		else
		{

			$this->buildTemplate();
		}
	}

	/**
	 * Check if template can be build.
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */
	public function canBuild(): bool
	{
		return (strtolower($this->name) !== 'basetemplate');
	}

	/**
	 * Write line to console.
	 *
	 * @param   string  $text  Text.
	 *
	 * @since 2.0
	 */
	public static function write(string $text): void
    {
		echo $text . PHP_EOL;
	}

	/**
	 * Build template.
	 *
	 * @since 2.0
	 */
	protected function buildTemplate(): void
    {
		$this->write("Building template ...");

		// Run build task
		$success = true;
		try
		{
			$this->prepareLanguageFiles();
			$this->prepareDetailsFile();
			$this->prepareIndexFile();
			$this->prepareIncludesFile();
			$this->createTemplateDirectories();
			$this->prepareTemplateNamespace();
		}
		catch (Exception $e)
		{
			$success = false;
			$this->write("Error while building template: " . $e->getMessage());
		}

		// Template build is successful
		if ($success)
		{
			$this->write("Finish.");
		}

	}

	/**
	 * Prepare language files. Rename files and constants in it.
	 *
	 * @since 1.0
	 */
	public function prepareLanguageFiles(): void
    {
		$this->write("Preparing language files ...");

		$path = $this->base . '/language/';

		// Foreach  directory/language
		foreach (glob($path . '*') AS $language_dir)
		{

			// If this is not top directory
			if (!in_array($language_dir, ['.', '..']))
			{

				// Walk through language files
				foreach (glob($language_dir . '/*.ini') AS $language_file)
				{

					// Prepare new language file name and rename the file
					$path = str_ireplace('basetemplate', strtolower($this->name), $language_file);
					rename($language_file, $path);

					// Rename the constants
					if (file_exists($path))
					{
						$buff = file_get_contents($path);
						$buff = str_replace('BASETEMPLATE', strtoupper($this->name), $buff);
						file_put_contents($path, $buff);
					}
				}
			}
		}
	}

	/**
	 * Prepare a template declaration (details) XML file.
	 *
	 * @since 1.0
	 */
	public function prepareDetailsFile(): void
    {
		$this->write("Preparing template definition XML file ...");

		$path = $this->base . '/templateDetails.xml';
		$buff = file_get_contents($path);
        $buff = str_replace(['BASETEMPLATE', 'basetemplate', 'BaseTemplate'], [strtoupper($this->name), strtolower($this->name), ucfirst(strtolower($this->name))], $buff);
		$buff = str_ireplace('01/01/2000', date('Y/m/d'), $buff);
		file_put_contents($path, $buff);
	}

	/**
	 * Override index.php translations
	 *
	 * @since 1.0
	 */
	public function prepareIndexFile(): void
    {
		$this->write("Preparing template index file ...");

		$path = $this->base . '/index.php';
		$buff = file_get_contents($path);
		$buff = str_replace(['BASETEMPLATE','BaseTemplate'], [strtoupper($this->name), ucfirst(strtolower($this->name))], $buff);
		file_put_contents($path, $buff);
	}

	/**
	 * Prepare a template namespace class.
	 *
	 * @since 2.0
	 */
	public function prepareTemplateNamespace(): void
    {
		$this->write("Preparing template namespaces ...");

		$path = $this->base . '/src/BaseTemplate/Helper/BaseTemplateHelper.php';
        if( file_exists($path) ) {
            $buff = file_get_contents($path);
            $buff = str_replace(['BASETEMPLATE','BaseTemplate'], [strtoupper($this->name), ucfirst(strtolower($this->name))], $buff);
            file_put_contents($path, $buff);
        }

        // Rename Helper class
        if( file_exists($path) ) {
            rename($path, $this->base . '/src/BaseTemplate/Helper/'.ucfirst(strtolower($this->name)).'Helper.php');
        }

        // Rename namespace directory
        $namespace_directory = dirname($path, 2);
        if( is_dir($namespace_directory) ) {
            rename($namespace_directory, $this->base . '/src/'.ucfirst(strtolower($this->name)));
        }
	}

	/**
	 * Override index.php translations
	 *
	 * @since 1.0
	 */
	public function prepareIncludesFile(): void
    {
		$this->write("Preparing template includes file ...");

		$path = $this->base . '/includes.php';
		$buff = file_get_contents($path);
        $buff = str_replace(['BASETEMPLATE','BaseTemplate'], [strtoupper($this->name), ucfirst(strtolower($this->name))], $buff);
		file_put_contents($path, $buff);
	}

	/**
	 * Create all the directories template needs o work as expected.
	 *
	 * @since 2.0
	 */
	protected function createTemplateDirectories(): void
	{
		$this->write("Preparing template directories ...");

		$logo_path   = $this->root . '/images/logo';
		$icons_path  = $this->root . '/images/icons';
		$assets_path = $this->root . '/media/templates/site/'.$this->name;
        $fonts_path = $this->base . '/.dev/fonts';

		// Create logo directory
		if (!file_exists($logo_path) && !mkdir($logo_path, 0755, true) && !is_dir($logo_path)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $logo_path));
        }

		// Create icons directory
		if (!file_exists($icons_path) && !mkdir($icons_path, 0755, true) && !is_dir($icons_path)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $icons_path));
        }

		// Create assets build directory
		if (!file_exists($assets_path) && !mkdir($assets_path, 0755, true) && !is_dir($assets_path)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $assets_path));
        }

		// Create source fonts directory
		if (!file_exists($fonts_path) && !mkdir($fonts_path, 0755, true) && !is_dir($fonts_path)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $fonts_path));
        }
	}

	/**
	 * Execute the builder.
	 *
	 * @since 1.0
	 */
	public static function execute(Event $e): void
    {
		$instance = new self();
	}

}
