<?php
define('_JEXEC',1);

// Enable Error reporting
ini_set('display_errors', -1);
error_reporting(E_ALL);

// Disable caching
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() - 3600));

/**
 * TemplateBuilder class takes care of customizing template name and
 */
class TemplateBuilder {

	public $override_extensions = array();
	protected $name;
	protected $base;


	/**
	 * Run all required methods to create new theme.
	 */
	public function __construct() {

		// Get required directories
		$this->base = dirname(dirname(__DIR__));
		$this->name = basename(__DIR__);

	}

	/**
	 * Create template overrides for currently installed extensions
	 */
	public function createTemplateOverrides() {

		$path = __DIR__.'/html';

		// Create overrides for components
		foreach( glob($this->base.'/components/*') AS $directory ) {

			// If this extension views should not be overriden, skip it
			if(  !in_array(basename($directory), $this->override_extensions) ) {
				continue;
			}

			// Prepare component data an directories
			$component = basename($directory);
			$path_overrides = $path.'/'.$component;

			// There are views
			$path_views = $directory.'/views';
			if( file_exists($path_views) ) {

				// Make sure component views oferride exists
				if( !file_exists($path_overrides) ) {
					mkdir($path_overrides);
				}

				// Crawl over views
				foreach( glob($path_views.'/*') AS $path_view)  {
					
					// Ommit placeholders
					if( basename($directory)==='index.html' ) {
						continue;
					}

					$view = basename($path_view);

					// if view have layouts
					if( is_dir($path_view) AND file_exists($path_view.'/tmpl') ) {

						// Create view override directory
						$path_override_view = $path_overrides.'/'.$view;
						if( !file_exists($path_override_view) ) {
							mkdir($path_override_view);
						}

						// Copy layouts
						foreach( glob($path_view.'/tmpl/*.php') AS $path_layout ) {

							// Copy only layotus that don't exists in template
							$path_override_layout = $path_override_view.'/'.basename($path_layout);
							if( !file_exists($path_override_layout) ) {
								copy($path_layout, $path_override_layout);
							}
						}
					}
				}

			}

		}

		// Create overrides for modules
		foreach( glob($this->base.'/modules/*') AS $directory ) {

			// If this extension views should not be overriden, skip it
			if(  !in_array(basename($directory), $this->override_extensions) ) {
				continue;
			}

			// Prepare component data an directories
			$module = basename($directory);
			$path_overrides = $path.'/'.$module;

			// If module have layouts
			if( file_exists($directory.'/tmpl') ) {

				// Create view override directory
				if( !file_exists($path_overrides) ) {
					mkdir($path_overrides);
				}

				// Copy layouts
				foreach( glob($directory.'/tmpl/*.php') AS $path_layout ) {

					// Copy only layotus that don't exists in template
					$path_override_layout = $path_overrides.'/'.basename($path_layout);
					if( !file_exists($path_override_layout) ) {
						copy($path_layout, $path_override_layout);
					}
				}
			}

		}

	}

	/**
	 * Prepare language files. Rename files and constants in it.
	 */
	public function prepareLanguageFiles(){
		$path = __DIR__.'/language/';

		// Foreach  directory/language
		foreach ( glob($path.'*') AS $language_dir ) {

			// if this is not top directory
			if( $language_dir!=='..' AND $language_dir!=='.' ) {

				// Walk trouth language files
				foreach( glob($language_dir.'/*.ini') AS $language_file ) {

					// Prepare new language file name and rename the file
					$path = str_ireplace('basetheme', strtolower($this->name), $language_file);
					rename($language_file, $path);

					// Rename the constants
					if( file_exists($path) ) {
						$buff = file_get_contents($path);
						$buff = str_replace('BASETHEME', strtoupper($this->name), $buff);
						file_put_contents($path, $buff);
					}
				}

			}
		}

	}

	/**
	 * Prepare a template declaration (details) XML file.
	 */
	public function prepareDetailsFile(){
		$path = __DIR__.'/templateDetails.xml';
		$buff = file_get_contents($path);
		$buff = str_replace('BASETHEME', strtoupper($this->name), $buff);
		$buff = str_replace('basetheme', strtolower($this->name), $buff);
		$buff = str_ireplace('01/01/2000', date('Y/m/d'), $buff);
		file_put_contents($path, $buff);
	}

	/**
	 * Execute the builder.
	 */
	public function build() {

		// Run build task
		$this->createTemplateOverrides();
		$this->prepareLanguageFiles();
		$this->prepareDetailsFile();
	}

}

$template = new TemplateBuilder();
$template->build();

die('DONE.');
