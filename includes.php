<?php

use BestProject\TemplateHelper;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * == TEMPLATE CLASSES =========================================================
 */
require_once __DIR__.'/vendor/autoload.php';


/**
 * == BASIC VARIABLES ==========================================================
 */
/* @var $doc HtmlDocument */
/* @var $app CMSApplication */

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
TemplateHelper::addAsyncScripts(TemplateHelper::getAssetUrl('templates/'.$this->template.'/assets/build/theme.js'));
$doc->addScript('media/jui/js/html5.js', ['conditional'=>'lt IE 9']);

if( $params->get('vendors_animated') ) {
	TemplateHelper::addAsyncScripts('

		// Run animations when element enters the screen
		jQuery(function($){
			$(document).Animated();
		});

	');
}
if( $params->get('back_to_top') ) {
	TemplateHelper::addAsyncScripts('

		// Change menu position on scroll
		jQuery(function($){
			$(document).backToTopButton("'.JText::_('TPL_POLISA_BACK_TO_TOP').'");
		});

	');
}

/**
 * == STYLE SHEETS =============================================================
 */

// Project styles
$doc->addStyleSheet(TemplateHelper::getAssetUrl('templates/'.$this->template.'/assets/build/theme.css'), ['version'=>'auto']); // MAIN THEME


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