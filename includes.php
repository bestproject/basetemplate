<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * == TEMPLATE CLASSES =========================================================
 */
require_once __DIR__.'/vendor/autoload.php';


/**
 * == BASIC VARIABLES ==========================================================
 */
$app          = Factory::getApplication();
$params       = $this->params;
$debug        = (bool) $app->get('debug', 0);
$doc          = Factory::getDocument();
$language     = Factory::getLanguage()->getTag();
$direction    = $doc->direction;
$menu         = $app->getMenu();
$active       = $menu->getActive();
$default      = $menu->getDefault($language);
$sitename     = $app->get('sitename');
$sitetitle    = $params->get('sitetitle');
$logoFile     = JUri::root().$params->get('logoFile');
$slogan       = $params->get('sitedescription');
$copyrights   = $params->get('copyrights', $sitename);
$is_frontpage = ( ($active AND $active->id == $default->id) ? true : false );
$view         = $app->input->get('view');
$layout       = $app->input->get('layout');


/**
 * == JAVA SCRIPT ==============================================================
 */
JHTML::_('jquery.framework');
$doc->addScript('templates/'.$this->template.'/js/theme'.($debug ? '':'.min').'.js', ['version'=>'auto']); // Main Theme JS
$doc->addScript('templates/'.$this->template.'/js/vendor'.($debug ? '':'.min').'.js', ['version'=>'auto']); // Theme Vendor JS

if( $params->get('vendors_animated') ) {
	$doc->addScriptDeclaration('

		// Run animations when element enters the screen
		jQuery(document).ready(function($){
			$(document).Animated();
		});

	');
}
if( $params->get('back_to_top') ) {
	$doc->addScriptDeclaration('

		// Change menu position on scroll
		jQuery(document).ready(function($){
			$(document).backToTopButton("'.JText::_('TPL_BASETHEME_BACK_TO_TOP').'");
		});

	');
}

/**
 * == STYLE SHEETS =============================================================
 */

// Project styles
$doc->addStyleSheet('templates/'.$this->template.'/css/index.css', ['version'=>'auto']); // MAIN THEME


/**
 * == MODULES POSITIONS ========================================================
 */
$has_menu			= $this->countModules('menu');
$has_slider			= $this->countModules('slider');
$has_slider_after   = $this->countModules('slider-after');
$has_left			= $this->countModules('left');
$has_content_before = $this->countModules('content-before');
$has_content		= trim(current(current(current($doc->getBuffer()))))!=='';
$has_content_after  = $this->countModules('content-after');
$has_right			= $this->countModules('right');
$has_footer_before  = $this->countModules('footer-before');
$has_footer			= $this->countModules('footer');
$has_footer_menu    = $this->countModules('footer-menu');
$has_debug			= $this->countModules('debug');


/**
 * == PAGE CLASSES =============================================================
 */
$class = 'page-'.($menu->getActive() ? $menu->getActive()->id : 'unknown');
$class.= ' '.$app->input->get('option');
if( !empty($view) ) {
	$class.= ' view-'.$view;
}
if( !empty($layout) ) {
	$class.= ' layout-'.$layout;
}
if( $active ) {
	$class.= ' '.$active->params->get('pageclass_sfx');
}
if( !empty($app->input->get('layout')) ) {
	$class.=' layout-'.$app->input->get('layout');
}
if( $is_frontpage ) {
    $class.=' frontpage';
} else {
    $class.=' subpage';
}


/**
 * == LAYOUT OPTIONS ===========================================================
 */
$has_menu_fixed = $params->get('menu_fixed');


/**
 * == DEBUG ====================================================================
 */
if( $debug ) {
	$app->enqueueMessage('This is a sample error message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'error');
	$app->enqueueMessage('This is a sample warning message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'warning');
	$app->enqueueMessage('This is a sample notice message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'notice');
	$app->enqueueMessage('This is a simple message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'message');
}