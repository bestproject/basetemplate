<?php

use BestProject\TemplateHelper;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

/**
 * == TEMPLATE CLASSES =========================================================
 */
require_once __DIR__ . '/vendor/autoload.php';


/**
 * == BASIC VARIABLES ==========================================================
 */
/* @var $doc HtmlDocument */
/* @var $app CMSApplication */
$base_uri     = Uri::base(true);
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
$logoFile     = $params->get('logoFile', '-1');
$logoFile     = $logoFile !== '-1' ? $base_uri . '/images/logo/' . $logoFile : '';
$slogan       = $params->get('sitedescription');
$faviconFile  = $params->get('faviconFile', '-1');
$faviconFile  = $faviconFile !== '-1' ? $base_uri . '/images/icons/' . $faviconFile : '';
$copyrights   = $params->get('copyrights', $sitename);
$is_frontpage = (($active AND $active->id == $default->id) ? true : false);
$is_subpage   = !$is_frontpage;
$view         = $app->input->get('view');
$layout       = $app->input->get('layout');

/** == JAVA SCRIPT ============================================================== */
JHTML::_('jquery.framework');
$doc->addScript('media/jui/js/html5.js', ['conditional' => 'lt IE 9']);

/** == ENTRY POINTS ============================================================== */

try
{

	$path_entrypoints = __DIR__ . '/assets/build/entrypoints.json';
	if (!file_exists($path_entrypoints))
	{
		throw new Exception('There is no entrypoints.json file in youre template assets directory. Did you run [npm run dev] ?', 500);
	}
	else
	{

		// Force media version update
		$doc->setMediaVersion(filemtime($path_entrypoints));
	}

	// Include Java Script entry point at the bottom
	TemplateHelper::addEntryPointAssets('runtime');
	TemplateHelper::addEntryPointAssets('theme');

}
catch (Exception $e)
{
	echo $e->getMessage();
	http_response_code($e->getCode());
	$app->close();
}

// Include animate.css and animated.js
if ($params->get('vendors_animated'))
{
	TemplateHelper::addEntryPointAssets('animated');
}

// Include magnific-popup with automatic image popup
if ($params->get('vendors_lightbox'))
{
	TemplateHelper::addEntryPointAssets('lightbox');
}

// Include back-to-top script
if ($params->get('back_to_top'))
{
	$button_text = JText::_('TPL_BASETHEME_BACK_TO_TOP');
	TemplateHelper::addEntryPointAssets('backtotop');
	TemplateHelper::addScriptDeclaration("

		// Add scroll to top button
		jQuery(function($){
			$(document).backToTopButton({
		        button_text: '$button_text',
		    });
		});

	");
}

// Add a class to navbar on scroll
$has_menu_fixed = $params->get('menu_fixed');
if ($has_menu_fixed)
{
	TemplateHelper::addEntryPointAssets('classonscroll');
	TemplateHelper::addScriptDeclaration("
		// Add 'scrolled' class to #nav after windows scroll
		jQuery(function($){
			$('#nav').classOnScroll();
		});
	");
}

/**
 * == TEMPLATE SETTINGS
 */
if (!empty($faviconFile))
{
	$this->addFavicon($faviconFile);
}

/**
 * == MODULES POSITIONS ========================================================
 */
$has_menu           = $this->countModules('menu');
$has_slider         = $this->countModules('slider');
$has_slider_after   = $this->countModules('slider-after');
$has_left           = $this->countModules('left');
$has_content_before = $this->countModules('content-before');
$has_content        = trim(current(current(current($doc->getBuffer())))) !== '';
$has_content_after  = $this->countModules('content-after');
$has_right          = $this->countModules('right');
$has_footer_before  = $this->countModules('footer-before');
$has_footer         = $this->countModules('footer');
$has_footer_menu    = $this->countModules('footer-menu');
$has_debug          = $this->countModules('debug');


/**
 * == PAGE CLASSES =============================================================
 */
$class = 'page-' . ($menu->getActive() ? $menu->getActive()->id : 'unknown');
$class .= ' ' . $app->input->get('option');
if (!empty($view))
{
	$class .= ' view-' . $view;
}
if (!empty($layout))
{
	$class .= ' layout-' . $layout;
}
if ($active)
{
	$class .= ' ' . $active->params->get('pageclass_sfx');
}
if (!empty($app->input->get('layout')))
{
	$class .= ' layout-' . $app->input->get('layout');
}
if ($is_frontpage)
{
	$class .= ' frontpage';
}
else
{
	$class .= ' subpage';
}


/**
 * == LAYOUT OPTIONS ===========================================================
 */


/**
 * == DEBUG ====================================================================
 */
if ($params->get('messages_debug', 0))
{
	$app->enqueueMessage('This is a sample error message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'error');
	$app->enqueueMessage('This is a sample warning message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'warning');
	$app->enqueueMessage('This is a sample notice message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'notice');
	$app->enqueueMessage('This is a simple message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'message');
}