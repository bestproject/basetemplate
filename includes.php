<?php defined('_JEXEC') or die;

/**
 * == TEMPLATE CLASSES =========================================================
 */
require_once __DIR__.'/vendor/bestproject/bootstrap3/bootstrap3.php';


/**
 * == BASIC VARIABLES ==========================================================
 */
/* @var $menu JMenu */
/* @var $active JMenuItem */
$app			= JFactory::getApplication();
$debug			= (bool)$app->get('debug',0);
$doc			= JFactory::getDocument();
$language		= JFactory::getLanguage()->getTag();
$direction		= $doc->direction;
$menu			= $app->getMenu();
$active			= $menu->getActive();
$default		= $menu->getDefault($language);
$sitename		= $app->get('sitename');
$sitetitle		= $this->params->get('sitetitle');
$logoFile		= JUri::root().$this->params->get('logoFile');
$slogan			= $this->params->get('sitedescription');
$copyrights		= $this->params->get('copyrights', $sitename);
$is_frontpage	= ( ($active AND $active->id==$default->id) ? true : false );
$view			= $app->input->get('view');
$layout			= $app->input->get('layout');


/**
 * == JAVA SCRIPT ==============================================================
 */
JHTML::_('jquery.framework');
$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.'.($debug?'':'min.').'js');
if( $this->params->get('vendors_colorbox') ) {
	$doc->addScript($this->baseurl.'/templates/'.$this->template.'/vendor/jackmoore/colorbox/jquery.colorbox'.($debug?'':'-min.').'js');
	$doc->addScriptDeclaration('
		// Setup lightbox for anchors
		jQuery(document).ready(function($){
			$("a[href$=\'.jpg\'],a[href$=\'.jpeg\'],a[href$=\'.png\'],a[href$=\'.gif\']").colorbox({maxWidth:"95%", maxHeight:"95%"});
		});

	');
}
if( $this->params->get('vendors_animated') ) {
	$doc->addScript($this->baseurl.'/templates/'.$this->template.'/vendor/bestproject/animated/animated-1.0.0.'.($debug?'':'min.').'js');
	$doc->addScriptDeclaration('
		// Run animations when element enters the screen
		jQuery(document).ready(function($){
			$(document).Animated();
		});

	');
}
if( $this->params->get('back_to_top') ) {
	$doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/template.'.($debug?'':'min.').'js');
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
$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.'.($debug?'':'min.').'css');

// Colorbox lightbox for all links to images
if( $this->params->get('vendors_colorbox') ) {
	$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/vendor/jackmoore/colorbox/example2/colorbox.css');
}

// CSS3 Animations from animate.css library when element enters the screen
if( $this->params->get('vendors_animated') ) {
	$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/vendor/daneden/animate.css/animate.'.($debug?'':'min.').'css');
}

// Icons from Font Awesome library
if( $this->params->get('vendors_fontawesome') ) {
	$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.'.($debug?'':'min.').'css');
}

// Icons from Material Design library
if( $this->params->get('vendors_googleicons') ) {
	$doc->addStyleSheet('https://fonts.googleapis.com/icon?family=Material+Icons');
}

// Project styles
$doc->addStyleSheetVersion($this->baseurl.'/templates/'.$this->template.'/css/theme.css', filemtime(__DIR__.'/css/theme.css')); // MAIN THEME
$doc->addStyleSheetVersion($this->baseurl.'/templates/'.$this->template.'/css/responsive.css', filemtime(__DIR__.'/css/responsive.css')); // RESPONSIVE


/**
 * == MODULES POSITIONS ========================================================
 */
$has_menu			= $this->countModules('menu');
$has_slider			= $this->countModules('slider');
$has_content		= trim(current(current(current($doc->getBuffer()))))!=='';
$has_left			= $this->countModules('left');
$has_right			= $this->countModules('right');
$has_footer			= $this->countModules('footer');
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


/**
 * == LAYOUT OPTIONS ===========================================================
 */
$has_menu_fixed = $this->params->get('menu_fixed');


/**
 * == DEBUG ====================================================================
 */
if( $debug ) {
	$app->enqueueMessage('This is a sample error message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'error');
	$app->enqueueMessage('This is a sample warning message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'warning');
	$app->enqueueMessage('This is a sample notice message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'notice');
	$app->enqueueMessage('This is a simple message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'message');
}