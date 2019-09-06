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
	 * Template name. eg. basetheme
	 *
	 * @var string
	 *
	 * @since 1.0
	 */
	protected $name;

	/**
	 * Template base directory. eg. /var/www/example.com/templates/basetheme
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
		$this->base = dirname(dirname(__DIR__));
		$this->root = dirname(dirname($this->base));
		$this->name = basename($this->base);

		// If template can't be build
		if (!$this->canBuild())
		{
			$this->write("You are trying to build without changing template name. Change /templates/basetheme to /templates/YOUR_THEME_NAME and try again.");
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
		return (strtolower($this->name) !== 'basetheme');
	}

	/**
	 * Write line to console.
	 *
	 * @param   string  $text  Text.
	 *
	 * @since 2.0
	 */
	public static function write(string $text)
	{
		echo $text . PHP_EOL;
	}

	/**
	 * Build template.
	 *
	 * @since 2.0
	 */
	protected function buildTemplate()
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
	public function prepareLanguageFiles()
	{
		$this->write("Preparing language files ...");

		$path = $this->base . '/language/';

		// Foreach  directory/language
		foreach (glob($path . '*') AS $language_dir)
		{

			// If this is not top directory
			if (!in_array($language_dir, ['.', '..']))
			{

				// Walk trough language files
				foreach (glob($language_dir . '/*.ini') AS $language_file)
				{

					// Prepare new language file name and rename the file
					$path = str_ireplace('basetheme', strtolower($this->name),
						$language_file);
					rename($language_file, $path);

					// Rename the constants
					if (file_exists($path))
					{
						$buff = file_get_contents($path);
						$buff = str_replace('BASETHEME',
							strtoupper($this->name), $buff);
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
	public function prepareDetailsFile()
	{
		$this->write("Preparing template definition XML file ...");

		$path = $this->base . '/templateDetails.xml';
		$buff = file_get_contents($path);
		$buff = str_replace('BASETHEME', strtoupper($this->name), $buff);
		$buff = str_replace('basetheme', strtolower($this->name), $buff);
		$buff = str_ireplace('01/01/2000', date('Y/m/d'), $buff);
		file_put_contents($path, $buff);
	}

	/**
	 * Override index.php translations
	 *
	 * @since 1.0
	 */
	public function prepareIndexFile()
	{
		$this->write("Preparing template index file ...");

		$path = $this->base . '/index.php';
		$buff = file_get_contents($path);
		$buff = str_replace('BASETHEME', strtoupper($this->name), $buff);
		file_put_contents($path, $buff);
	}

	/**
	 * Override index.php translations
	 *
	 * @since 1.0
	 */
	public function prepareIncludesFile()
	{
		$this->write("Preparing template includes file ...");

		$path = $this->base . '/includes.php';
		$buff = file_get_contents($path);
		$buff = str_replace('BASETHEME', strtoupper($this->name), $buff);
		file_put_contents($path, $buff);
	}

	/**
	 * Create all the directories template needs o work as expected.
	 *
	 * @since 2.0
	 */
	protected function createTemplateDirectories()
	{
		$this->write("Preparing template directories ...");

		$logo_path   = $this->root . '/images/logo';
		$icons_path  = $this->root . '/images/icons';
		$assets_path = $this->base . '/assets/build';

		// Create logo directory
		if (!file_exists($logo_path))
		{
			mkdir($logo_path, 0755);
		}

		// Create icons directory
		if (!file_exists($icons_path))
		{
			mkdir($icons_path, 0755);
		}

		// Create assets build directory
		if (!file_exists($assets_path))
		{
			mkdir($assets_path, 0755, true);
		}
	}

	/**
	 * Execute the builder.
	 *
	 * @since 1.0
	 */
	public static function execute(Event $e)
	{
		$instance = new self();
	}

}