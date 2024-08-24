<?php

defined('_JEXEC') or die;

use BestProject\Bootstrap;
use BestProject\Helper\ImageHelper;
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
/* @var $homepage_url string */
/* @var $class string */
/* @var $logoFile string */
/* @var $sitename string */
/* @var $direction string */
/* @var $language string */

// Prepare document head
require_once __DIR__.'/includes.php';

/** == FONTS ========================================================= */
$fonts_uri = 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap';

$wa->registerAndUseStyle('fontscheme.current', $fonts_uri, [],['rel' => 'lazy-stylesheet', 'crossorigin' => 'anonymous']);

$this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', ['crossorigin' => 'anonymous']);
$this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', ['crossorigin' => 'anonymous']);
$this->getPreloadManager()->preload($wa->getAsset('style', 'fontscheme.current')->getUri(), ['as' => 'style', 'crossorigin' => 'anonymous']);



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

<header id="navigation" class="shadow-sm pb-2 navbar navbar-expand-lg navbar-light">

    <div class="container">

        <a class="navbar-brand" href="<?php echo $homepage_url ?>" title="<?php echo $sitename ?>">
            <?php if ($logoFile): ?>
                <img src="<?php echo $logoFile ?>" <?php echo ImageHelper::getSizeAttributes($logoFile) ?> alt="<?php echo $sitename ?>" />
            <?php else: ?>
                <?php echo $sitename ?>
            <?php endif ?>

            <?php if( !empty($slogan) ): ?>
                <small class="navbar-text visually-hidden"><?php echo $slogan ?></small>
            <?php endif ?>
        </a>

        <?php if( $has_menu ): ?>
            <jdoc:include type="modules" name="menu" style="none" />
        <?php endif ?>
    </div>

</header>

<?php if( $has_slider ): ?>
    <div class="slider">
        <jdoc:include type="modules" name="slider" />
    </div>
<?php endif ?>

<?php if( $has_slider_after ): ?>
    <div id="slider-after">
        <div class="container">
            <div class="wrapper">
                <jdoc:include type="modules" name="slider-after" />
            </div>
        </div>
    </div>
<?php endif ?>

<jdoc:include type="message" />

<?php if( $has_content ):
    $content_class = ($has_left && $has_right) ? 'col-xl-6' : ''; // Both columns has content
    $content_class = (empty($content_class) && ($has_left || $has_right)) ? 'col-xl-8': $content_class; // Only one column has content
    ?>

    <div class="container">
        <div id="content" class="row">

            <?php if( $has_left ): ?>
                <div class="col-12 <?php echo ($has_right ? 'col-xl-3' : 'col-xl-4') ?>">
                    <jdoc:include type="modules" name="left" />
                </div>
            <?php endif ?>

            <div class="columns-container col-12 <?php echo $content_class ?>">

                <?php if( $has_content_before ): ?>
                    <div id="content-before">
                        <jdoc:include type="modules" name="content-before" />
                    </div>
                <?php endif ?>

                <main>

                    <jdoc:include type="component" />

                </main>

                <?php if( $has_content_after ): ?>
                    <div id="content-after">
                        <jdoc:include type="modules" name="content-after" />
                    </div>
                <?php endif ?>

            </div>

            <?php if( $has_right ): ?>
                <div class="col-12 <?php echo ($has_left ? 'col-xl-3' : 'col-xl-4') ?>">
                    <jdoc:include type="modules" name="right" />
                </div>
            <?php endif ?>

        </div>
    </div>
<?php endif ?>

<?php if( $has_footer_before ): ?>
    <div id="footer-before">
        <div class="container">
            <div class="wrapper">
                <jdoc:include type="modules" name="footer-before" />
            </div>
        </div>
    </div>
<?php endif ?>

<footer>

    <?php if( $has_footer ): ?>
        <div class="container">
            <jdoc:include type="modules" name="footer" />
        </div>
    <?php endif ?>

    <div class="container">
        <div class="wrapper d-flex flex-wrap justify-content-center justify-content-xl-between align-items-center py-3">
            <span class="copyrights text-muted">© <?php echo date('Y') ?> <?php echo $copyrights ?></span>

            <?php if( $has_footer_menu ): ?>
                <jdoc:include type="modules" name="footer-menu" style="none" />
            <?php endif ?>
        </div>
    </div>

</footer>

<?php if( $has_debug ): ?>
    <jdoc:include type="modules" name="debug" style="none" />
<?php endif ?>

<jdoc:include type="scripts" />

<?php echo TemplateHelper::renderCodeBodyBottom() ?>
</body>
</html>
<?php

// Remove build-in bootstrap scripts and styles to avoid duplication
TemplateHelper::removeAssets($wa, ['bootstrap'],['bootstrap']);