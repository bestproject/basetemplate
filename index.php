<?php defined('_JEXEC') or die;

// Preload resources
//$this->addHeadLink('templates/'.$this->template.'/assets/webfonts/fa-brands-400.woff2', 'preload', 'rel', ['as'=>'font']);
//$this->addHeadLink('templates/'.$this->template.'/css/style.css', 'preload', 'rel', ['as'=>'style']);
//$this->addHeadLink('templates/'.$this->template.'/js/script.js', 'preload', 'rel', ['as'=>'script']);
//$this->addHeadLink('templates/'.$this->template.'/images/image.jpg', 'preload', 'rel', ['as'=>'image']);


use BestProject\Bootstrap4;
use BestProject\TemplateHelper;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Menu\MenuItem;
use Joomla\CMS\Menu\SiteMenu;
use Joomla\Registry\Registry;

/* @var $doc HtmlDocument */
/* @var $this HtmlDocument */
/* @var $menu SiteMenu */
/* @var $active MenuItem */
/* @var $default MenuItem */
/* @var $params Registry */

// Prepare document head
require_once 'includes.php';

/** == FONTS ========================================================= */
$this->addHeadLink('https://fonts.gstatic.com', 'preconnect', 'rel');
$this->addHeadLink('https://fonts.googleapis.com/css?family=Roboto:100,200,300,400,500,600,700,800,900&amp;subset=latin-ext', 'preload', 'rel', ['as'=>'style']);

/** == CUSTOM VARIABLES ============================================== */
// Enter your custom variables here

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $language; ?>" lang="<?php echo $language; ?>" dir="<?php echo $direction; ?>">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <?php echo TemplateHelper::renderCodeHeadTop() ?>
        <jdoc:include type="head" />
        <?php echo TemplateHelper::renderCodeHeadBottom() ?>
    </head>

    <body class="site <?php echo $class ?>">
		<?php echo TemplateHelper::renderCodeBodyTop() ?>
        <!--Page header-->
        <header>

            <!--Page navigation-->
            <nav id="nav" class="navbar navbar-expand-xl navbar-light">

				<!--Navigation header-->
                <div class="container">

                       <!--Logo-->
                       <a class="navbar-brand" href="<?php echo JURI::Base() ?>" title="<?php echo $sitename ?>">
                            <?php if ($this->params->get('logoFile')): ?>
                                <img src="<?php echo $logoFile ?>" alt="<?php echo $sitename ?>" />
                            <?php else: ?>
                                <?php echo $sitename ?>
                            <?php endif ?>

                            <?php if( !empty($slogan) ): ?>
                                <small class="navbar-text"><?php echo $slogan ?></small>
                            <?php endif ?>
                        </a>

                        <!--Menu button-->
                        <?php if( $has_menu ): ?>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navigation" aria-controls="main-navigation" aria-expanded="false" aria-label="<?php echo JText::_('TPL_BASETHEME_TOGGLE_NAVIGATION') ?>">
                            <span class="d-flex align-items-center">
                                <i class="fas fa-bars" aria-hidden="true"></i>
                                <span class="toggler-text"><?php echo JText::_('TPL_BASETHEME_TOGGLE_NAVIGATION') ?></span>
                            </span>
                        </button>
                        <?php endif ?>

                    <?php if( $has_menu ): ?>
                        <!--Main navigation-->
                        <div id="main-navigation" class="collapse navbar-collapse">
                            <?php echo Bootstrap4::position('menu', '', '', false) ?>
                        </div>
                    <?php endif ?>
                </div>

            </nav>

			<?php if( $has_slider ): ?>
			<div class="slider">
                <?php echo Bootstrap4::position('slider', 'jumbotron text-center', 'wrapper') ?>
			</div>
			<?php endif ?>

        </header>

        <?php if( $has_slider_after ): ?>
        <!--After slider-->
        <aside id="slider-after">
            <div class="container">
                <div class="wrapper">
                    <?php echo Bootstrap4::position('slider-after') ?>
                </div>
            </div>
        </aside>
        <?php endif ?>

        <!--System messages-->
        <jdoc:include type="message" />

        <?php if( $has_content ):
        $content_class = ($has_left AND $has_right) ? 'col-xl-6' : ''; // Both columns has content
        $content_class = (empty($content_class) AND ($has_left OR $has_right)) ? 'col-xl-8': $content_class; // Only one column has content
        ?>

            <!--Content blocks-->
            <div class="container">
                <div id="content" class="row">

                    <?php if( $has_left ): ?>
                    <!--Left column-->
                    <aside class="col-12 <?php echo ($has_right?'col-xl-3':'col-xl-4') ?>">
                        <?php echo Bootstrap4::position('left') ?>
                    </aside>
                    <?php endif ?>

                    <!--Main content-->
                    <div class="columns-container col-12 <?php echo $content_class ?>">

                        <?php if( $has_content_before ): ?>
                        <!--Before content-->
                        <aside id="content-before">
                            <?php echo Bootstrap4::position('content-before') ?>
                        </aside>
                        <?php endif ?>

                        <main>

                            <jdoc:include type="component" />

                        </main>

                        <?php if( $has_content_after ): ?>
                        <!--After content-->
                        <aside id="content-after">
                            <?php echo Bootstrap4::position('content-after') ?>
                        </aside>
                        <?php endif ?>

                    </div>

                    <?php if( $has_right ): ?>
                    <!--Right column-->
                    <aside class="col-12 <?php echo ($has_left?'col-xl-3':'col-xl-4') ?>">
                        <?php echo Bootstrap4::position('right') ?>
                    </aside>
                    <?php endif ?>

                </div>
            </div>
        <?php endif ?>

        <?php if( $has_footer_before ): ?>
        <!--Before footer-->
        <aside id="footer-before">
            <div class="container">
                <div class="wrapper">
                    <?php echo Bootstrap4::position('footer-before') ?>
                </div>
            </div>
        </aside>
        <?php endif ?>

        <!--Footer-->
        <footer>

            <?php if( $has_footer ): ?>
            <!--Footer modules-->
            <div class="container">
                <?php echo Bootstrap4::position('footer') ?>
            </div>
            <?php endif ?>

            <div class="container">
                <div class="wrapper row justify-content-center justify-content-xl-between align-items-center">
                    <!--Copyrights info-->
                    <span class="copyrights muted">© <?php echo date('Y') ?> <?php echo $copyrights ?></span>

                    <?php if( $has_footer_menu ): ?>
                        <!--Footer menu-->
                        <?php echo Bootstrap4::position('footer-menu', '', 'footer-menu') ?>
                    <?php endif ?>
                </div>
            </div>

        </footer>

        <?php if( $has_debug ): ?>
        <!--System debug data-->
        <div class="container">
            <jdoc:include type="modules" name="debug" style="none" />
        </div>
        <?php endif ?>
        <?php echo TemplateHelper::renderScripts() ?>
        <?php echo TemplateHelper::renderCodeBodyBottom() ?>
    </body>
</html>
<?php

// Remove Bootstrap 2
unset($this->_scripts['/media/jui/js/bootstrap.min.js']);
unset($this->_scripts['/media/jui/js/bootstrap.js']);

// Combine system scripts
TemplateHelper::combineSystemScripts($this->_scripts);