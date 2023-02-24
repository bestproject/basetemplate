<?php

defined('_JEXEC') or die;

use BestProject\Bootstrap;
use BestProject\Helper\TemplateHelper;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Menu\MenuItem;
use Joomla\CMS\Menu\SiteMenu;
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\Registry\Registry;

/* @var $doc HtmlDocument */
/* @var $this HtmlDocument */
/* @var $menu SiteMenu */
/* @var $active MenuItem */
/* @var $default MenuItem */
/* @var $params Registry */
/* @var $wa WebAssetManager */

// Prepare document head
require_once __DIR__.'/includes.php';

/** == FONTS ========================================================= */
$fonts_uri = 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap';

$this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', ['crossorigin' => 'anonymous']);
$this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', ['crossorigin' => 'anonymous']);
$this->getPreloadManager()->preload($fonts_uri, ['as' => 'style', 'crossorigin' => 'anonymous']);

$wa->registerAndUseStyle('fontscheme.current', $fonts_uri, [],['media' => 'print', 'rel' => 'lazy-stylesheet', 'onload' => 'this.media=\'all\'', 'crossorigin' => 'anonymous'])


/** == CUSTOM VARIABLES ============================================== */
// Enter your custom variables here

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $language; ?>" lang="<?php echo $language; ?>" dir="<?php echo $direction; ?>">
<head>
    <?php echo TemplateHelper::renderCodeHeadTop() ?>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <?php echo TemplateHelper::renderCodeHeadBottom() ?>
</head>

<body class="site <?php echo $class ?>">
<?php echo TemplateHelper::renderCodeBodyTop() ?>

<header id="navigation" class="shadow-sm pb-2 navbar navbar-expand-md navbar-light">

    <!--Navigation header-->
    <div class="container">

        <!--Logo-->
        <a class="navbar-brand" href="<?php echo JURI::Base() ?>" title="<?php echo $sitename ?>">
            <?php if ($logoFile): ?>
                <img src="<?php echo $logoFile ?>" alt="<?php echo $sitename ?>" />
            <?php else: ?>
                <?php echo $sitename ?>
            <?php endif ?>

            <?php if( !empty($slogan) ): ?>
                <small class="navbar-text visually-hidden"><?php echo $slogan ?></small>
            <?php endif ?>
        </a>

        <!--Menu button-->
        <?php if( $has_menu ): ?>
            <?php echo Bootstrap::position('menu', '', '', false) ?>
        <?php endif ?>
    </div>

</header>

<?php if( $has_slider ): ?>
    <div class="slider">
        <?php echo Bootstrap::position('slider', 'jumbotron text-center', 'wrapper', false) ?>
    </div>
<?php endif ?>

<?php if( $has_slider_after ): ?>
    <!--After slider-->
    <div id="slider-after">
        <div class="container">
            <div class="wrapper">
                <?php echo Bootstrap::position('slider-after') ?>
            </div>
        </div>
    </div>
<?php endif ?>

<!--System messages-->
<jdoc:include type="message" />

<?php if( $has_content ):
    $content_class = ($has_left && $has_right) ? 'col-xl-6' : ''; // Both columns has content
    $content_class = (empty($content_class) && ($has_left || $has_right)) ? 'col-xl-8': $content_class; // Only one column has content
    ?>

    <!--Content blocks-->
    <div class="container">
        <div id="content" class="row">

            <?php if( $has_left ): ?>
                <!--Left column-->
                <div class="col-12 <?php echo ($has_right ? 'col-xl-3' : 'col-xl-4') ?>">
                    <?php echo Bootstrap::position('left') ?>
                </div>
            <?php endif ?>

            <!--Main content-->
            <div class="columns-container col-12 <?php echo $content_class ?>">

                <?php if( $has_content_before ): ?>
                    <!--Before content-->
                    <div id="content-before">
                        <?php echo Bootstrap::position('content-before') ?>
                    </div>
                <?php endif ?>

                <main>

                    <jdoc:include type="component" />

                </main>

                <?php if( $has_content_after ): ?>
                    <!--After content-->
                    <div id="content-after">
                        <?php echo Bootstrap::position('content-after') ?>
                    </div>
                <?php endif ?>

            </div>

            <?php if( $has_right ): ?>
                <!--Right column-->
                <div class="col-12 <?php echo ($has_left ? 'col-xl-3' : 'col-xl-4') ?>">
                    <?php echo Bootstrap::position('right') ?>
                </div>
            <?php endif ?>

        </div>
    </div>
<?php endif ?>

<?php if( $has_footer_before ): ?>
    <!--Before footer-->
    <div id="footer-before">
        <div class="container">
            <div class="wrapper">
                <?php echo Bootstrap::position('footer-before') ?>
            </div>
        </div>
    </div>
<?php endif ?>

<!--Footer-->
<footer>

    <?php if( $has_footer ): ?>
        <!--Footer modules-->
        <div class="container">
            <?php echo Bootstrap::position('footer') ?>
        </div>
    <?php endif ?>

    <div class="container">
        <div class="wrapper row justify-content-center justify-content-xl-between align-items-center py-3">
            <!--Copyrights info-->
            <span class="copyrights text-muted">© <?php echo date('Y') ?> <?php echo $copyrights ?></span>

            <?php if( $has_footer_menu ): ?>
                <!--Footer menu-->
                <?php echo Bootstrap::position('footer-menu', '', 'footer-menu') ?>
            <?php endif ?>
        </div>
    </div>

</footer>

<?php if( $has_debug ): ?>
    <!--System debug data-->
    <jdoc:include type="modules" name="debug" style="none" />
<?php endif ?>

<!--Script rendered-->
<jdoc:include type="scripts" />
<!--End of script rendered-->

<?php echo TemplateHelper::renderCodeBodyBottom() ?>
</body>
</html>