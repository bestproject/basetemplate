<?php
defined('_JEXEC') or die;

/**
 * == TEMPLATE CLASSES =====================
 */
require_once __DIR__.'/vendor/bestproject/bootstrap3/bootstrap3.php';
use BestProject\Bootstrap3;


/**
 * == BASIC VARIABLES =====================
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
$is_frontpage	= ( ($active AND $active->id==$default->id) ? true : false );
$view			= $app->input->get('view');
$layout			= $app->input->get('layout');

/**
 * == JAVA SCRIPT =====================
 */
JHTML::_('jquery.framework');
$doc->addScript('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.'.($debug?'':'min.').'js');
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
if( $this->params->get('menu_fixed') AND $this->params->get('menu_fixed_on_scroll') ) {
	$doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/template.'.($debug?'':'min.').'js');
	$doc->addScriptDeclaration('
		// Change menu position on scroll
		jQuery(document).ready(function($){
			$(document).menuClassOnScroll();
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
 * == STYLE SHEETS =====================
 */
$doc->addStyleSheet('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.'.($debug?'':'min.').'css');
if( $this->params->get('vendors_colorbox') ) {
	$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/vendor/jackmoore/colorbox/example2/colorbox.css'); // Chosen default theme
}
if( $this->params->get('vendors_animated') ) {
	$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/vendor/daneden/animate.css/animate.'.($debug?'':'min.').'css'); // Animate.CSS
}
if( $this->params->get('vendors_fontawesome') ) {
	$doc->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.'.($debug?'':'min.').'css');// Font Awesome Icons
}
if( $this->params->get('vendors_googleicons') ) {
	$doc->addStyleSheet('https://fonts.googleapis.com/icon?family=Material+Icons'); // Material Design Icons
}
$font_languages = $this->params->get('font_languages');
if( !empty($font_languages) ) {
	$font_languages = !empty($font_languages) ? '&amp;subset='.implode(',', $font_languages):'';
}
$font_main = $this->params->get('font_main');
if( !empty($font_main) ) {
	$font_main_name = str_replace(' ', '+', $font_main);
	$doc->addStyleSheet('https://fonts.googleapis.com/css?family='.$font_main_name.':100,200,300,400,500,600,700,800,900'.$font_languages);
	$doc->addStyleDeclaration('html>body{font-family:\''.$font_main.'\'}');
}
$font_second = $this->params->get('font_second');
if( !empty($font_second) ) {
	$font_second_name = str_replace(' ', '+', $font_second);
	$doc->addStyleSheet('https://fonts.googleapis.com/css?family='.$font_second_name.':100,200,300,400,500,600,700,800,900'.$font_languages);
	$doc->addStyleDeclaration('h1,h2,h3,h4,h5,.btn,button,input[type="submit"],input[type="button"],.navbar-header>.navbar-nav,figure.logo{font-family:\''.$font_second.'\'}');
}
$doc->addStyleSheetVersion($this->baseurl.'/templates/'.$this->template.'/css/theme.css', filemtime(__DIR__.'/css/theme.css')); // MAIN THEME
$doc->addStyleSheetVersion($this->baseurl.'/templates/'.$this->template.'/css/responsive.css', filemtime(__DIR__.'/css/responsive.css')); // RESPONSIVE


/**
 * == MODULES POSITIONS =====================
 */
$has_toolbar		= $this->countModules('toolbar');
$has_menu			= $this->countModules('menu');
$has_slider			= $this->countModules('slider');
$has_content_before	= $this->countModules('content-before');
$has_content		= trim(current(current(current($doc->getBuffer()))))!=='';
$has_left			= $this->countModules('left');
$has_right			= $this->countModules('right');
$has_content_after	= $this->countModules('content-after');
$has_footer			= $this->countModules('footer');
$has_footer_menu	= $this->countModules('footer-menu');
$has_debug			= $this->countModules('debug');


/**
 * == PAGE CLASSES =====================
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
 * == LAYOUT OPTIONS =====================
 */
$has_navbar_full_width		= $this->params->get('navbar_full_width');
$has_menu_container			= $this->params->get('menu_container');
$has_menu_fixed				= $this->params->get('menu_fixed');
$has_menu_fixed_on_scroll	= $this->params->get('menu_fixed_on_scroll');
$menu_position				= $this->params->get('menu_position');
$has_footer_full_width		= $this->params->get('footer_full_width');
$has_footer_container		= $this->params->get('footer_container');
$toolbar_position			= $this->params->get('toolbar_position');

//$app->enqueueMessage('This is a sample error message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'error');
//$app->enqueueMessage('This is a sample warning message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'warning');
//$app->enqueueMessage('This is a sample notice message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'notice');
//$app->enqueueMessage('This is a simple message. Suspendisse pretium sodales mauris, quis semper lacus fermentum eu.', 'message');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $language; ?>" lang="<?php echo $language; ?>" dir="<?php echo $direction; ?>">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <jdoc:include type="head" />
        <!--[if lt IE 9]>
            <script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
        <![endif]-->
    </head>

    <body class="site <?php echo $class ?>">
        <!--Page header-->
        <header>
			
			<?php if( !$has_navbar_full_width ): ?>
			<div class="container">
			<?php endif ?>

            <!--Page navigation-->
			<div class="navbar-container <?php echo (!$has_navbar_full_width?'centered':'') ?>">
            <nav id="nav" class="navbar navbar-default <?php echo (($has_menu_fixed AND !$has_menu_fixed_on_scroll) ? 'navbar-fixed-top':'') ?>">
				
				<?php if( $has_toolbar AND $toolbar_position=='top' ): ?>
				<!--Toolbar-->
				<div id="toolbar">
					<?php if( $has_navbar_full_width AND !$has_menu_container ): ?>
					<div class="container">
					<?php endif ?>
					<div class="wrapper  animated-slideInDown">
						<?php echo Bootstrap3::renderModulesPosition('toolbar',true, 'module') ?>
					</div>
					<?php if( $has_navbar_full_width AND !$has_menu_container ): ?>
					</div>
					<?php endif ?>
				</div>
				<?php endif ?>

				<?php if( $has_navbar_full_width AND !$has_menu_container ): ?>
				<div class="container">
				<?php endif ?>
					
					<!--Navigation header-->
					<div class="navbar-header navbar-left sm-block">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navigation" aria-expanded="false">
							<span class="sr-only"><?php echo JText::_('TPL_STOMED_TOGGLE_NAVIGATION') ?></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<?php if( $is_frontpage ): ?>
							<h1 class="logo animated-slideInLeft">
							   <a class="navbar-brand" href="<?php echo JURI::Base() ?>" title="<?php echo $sitename ?>">
									<?php if ($this->params->get('logoFile')): ?>
										<img src="<?php echo $logoFile ?>" alt="<?php echo $sitename ?>" />
									<?php else: ?>
										<?php echo $sitename ?>
									<?php endif ?>
									<?php if( !empty($slogan) ): ?>
										<small class="xs-block"><?php echo $slogan ?></small>
									<?php endif ?>
								</a>
							</h1>
						<?php else: ?>
							<figure class="logo animated-slideInLeft">
								<a class="navbar-brand" href="<?php echo JURI::Base() ?>" title="<?php echo $sitename ?>">
									<?php if ($this->params->get('logoFile')): ?>
										<img src="<?php echo $logoFile ?>" alt="<?php echo $sitename ?>" />
									<?php else: ?>
										<?php echo $sitename ?>
									<?php endif ?>
									<?php if( !empty($slogan) ): ?>
										<small class="xs-block"><?php echo $slogan ?></small>
									<?php endif ?>
								</a>
							</figure>
						<?php endif ?>
					</div>

					<?php if( $has_menu AND in_array($menu_position, array('left','right'))): ?>
						<!--Main navigation-->
						<div id="main-navigation" class="navbar-collapse collapse navbar-<?php echo $menu_position ?> sm-block animated-slideInRight">
							<?php echo Bootstrap3::renderModulesPosition('menu',false) ?>
						</div>
					<?php else: ?>
						<?php if( $has_toolbar AND $toolbar_position=='logo' ): ?>
						<!--Toolbar-->
						<div id="toolbar" class="navbar-right">
							<div class="wrapper animated-slideInRight">
								<?php echo Bootstrap3::renderModulesPosition('toolbar',true, 'module') ?>
							</div>
						</div>
						<?php endif ?>
					<?php endif ?>

				<?php if( $has_navbar_full_width AND !$has_menu_container ): ?>
				</div>
				<?php endif ?>

				<?php if( $has_menu AND $menu_position==='bottom' ): ?>
					<!--Main navigation-->
					<div id="main-navigation" class="navbar-collapse collapse navbar-<?php echo $menu_position ?> sm-block animated-slideInRight">
						<?php if( $has_navbar_full_width AND !$has_menu_container  ): ?>
						<div class="container">
						<?php endif ?>
								<?php echo Bootstrap3::renderModulesPosition('menu',false) ?>
						<?php if( $has_navbar_full_width AND !$has_menu_container ): ?>
						</div>
						<?php endif ?>
					</div>
				<?php endif ?>
            </nav>
			</div>
			
			<?php if( !$has_navbar_full_width ): ?>
			</div>
			<?php endif ?>
        </header>

        <!--System messages-->
        <jdoc:include type="message" />

		<?php if( $has_content_before ): ?>
		<!--Content before-->
		<div id="content_before" class="animated-flipInX">
			<div class="container">
				<?php echo Bootstrap3::renderModulesPosition('content-before') ?>
			</div>
		</div>
		<?php endif ?>

		<?php if( $has_content ):
		$content_class = ($has_left AND $has_right) ? 'col-sm-6' : ''; // Both columns has content
		$content_class = (empty($content_class) AND ($has_left OR $has_right)) ? 'col-sm-8': $content_class; // Only one column has content
		?>
		<div class="container">
			<div id="content" class="row">

				<?php if( $has_left ): ?>
				<!--Left column-->
				<aside class="col-xs-12 <?php echo ($has_right?'col-sm-3':'col-sm-4') ?> animated-bounceInLeft">
					<?php echo Bootstrap3::renderModulesPosition('left') ?>
				</aside>
				<?php endif ?>

				<!--Website main content-->
				<main class="col-xs-12 <?php echo $content_class ?> animated-bounceInDown">

					<jdoc:include type="component" />

				</main>

				<?php if( $has_right ): ?>
				<!--Right column-->
				<aside class="col-xs-12 <?php echo ($has_left?'col-sm-3':'col-sm-4') ?> animated-bounceInRight">
					<?php echo Bootstrap3::renderModulesPosition('right') ?>
				</aside>
				<?php endif ?>
			</div>
		</div>
		<?php endif ?>

		<?php if( $has_content_after ): ?>
		<div id="content_after" class="animated-flipInX">
			<div class="container">
				<?php echo Bootstrap3::renderModulesPosition('content-after') ?>
			</div>
		</div>
		<?php endif; ?>

        <?php if( $has_debug ): ?>
        <!--System debug data-->
        <div class="container">
            <jdoc:include type="modules" name="debug" style="none" />
        </div>
        <?php endif ?>
		
        <!--Footer-->
        <footer<?php echo !$has_footer_full_width?' class="container"':'' ?>>
			<?php if( $has_footer_full_width AND $has_footer_container ): ?>
			<div class="container">
			<?php endif ?>

				<?php if( $has_footer ): ?>
				<!--Footer modules-->
				<div id="footer">
					<?php echo Bootstrap3::renderModulesPosition('footer', true, 'animated-flipInY') ?>
				</div>
				<?php endif ?>

				<div class="subfooter">
					<p class="copyrights pull-left">© <?php echo date('Y') ?> Smart-Up Sp. z o.o.</p>

					<?php if( $has_footer_menu ): ?>
					<!--Footer menu-->
					<div class="footer-menu pull-right">
						<?php echo Bootstrap3::renderModulesPosition('footer-menu') ?>
					</div>
					<?php endif ?>
				</div>

			<?php if( $has_footer_full_width AND $has_footer_container ): ?>
			</div>
			<?php endif ?>

        </footer>

    </body>
</html>
<?php

// Remove Bootstrap 2
$document = JFactory::getDocument();
unset($document->_scripts['/media/jui/js/bootstrap.min.js']);
unset($document->_scripts['/media/jui/js/bootstrap.js']);