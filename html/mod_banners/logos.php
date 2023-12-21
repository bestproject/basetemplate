<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use BestProject\Helper\AssetsHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\Component\Banners\Site\Helper\BannerHelper;

/**
 * @var object $module
 * @var array $list
 * @var string $headerText
 * @var string $footerText
 */

AssetsHelper::addEntryPointAssets('slider');

$id = 'mod_blocks_logos_slider_' . $module->id;
$count = count($list);

$options = [
    'autoplay' => [

        // Slide change delay
        'delay' => 2000,

        // No automatic change disabled after interaction
        'disableOnInteraction' => 'false',
    ],

    // Animation speed
    'speed' => 500,

    // Loop the slides
    'loop' => 'true',

    // Space between slides
    'spaceBetween' => 30,

    'navigation' => [
        'prevEl' => "#{$id}_prev",
        'nextEl' => "#{$id}_next",
    ],

    'slidesPerView' => min($count, 5),

    // Responsive settings
    'breakpoints' => [
        1300 => [
            'slidesPerView' => min($count, 5),
        ],
        1170 => [
            'slidesPerView' => min($count, 4),
        ],
        768 => [
            'slidesPerView' => min($count, 3),
        ],
        576 => [
            'slidesPerView' => min($count, 2),
        ],
        0 => [
            'slidesPerView' => min($count, 1),
        ],
    ]
];


$options = json_encode((object)$options, JSON_THROW_ON_ERROR);

/**
 * @var WebAssetManager $wa
 */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->addInlineScript("
    window.ModBlocksLogos{$module->id} = new Swiper('#$id', $options);
");

?>

<div class="mod-blocks mod-blocks-logos">

    <?php if ($headerText) : ?>
        <div class="bannerheader">
            <?php echo $headerText; ?>
        </div>
    <?php endif; ?>

    <div class="d-flex align-items-center justify-content-center justify-content-md-between flex-wrap flex-md-nowrap">

        <button id="<?php echo "{$id}_prev" ?>" class="d-flex align-items-center order-1 mod-blocks-prev">
            <span class="visually-hidden"><?php echo Text::_('JPREV') ?></span>
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
        </button>

        <button id="<?php echo "{$id}_next" ?>" class="d-flex align-items-center order-2 order-md-3 mod-blocks-prev">
            <span class="visually-hidden"><?php echo Text::_('JNEXT') ?></span>
            <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </button>

        <div id="<?php echo $id ?>" class="swiper order-3 order-md-2 mx-0 mx-md-5">
            <div class="swiper-wrapper align-items-center">

                <?php foreach ($list as $item) : ?>
                    <div class="swiper-slide d-flex align-items-center justify-content-center">
                        <?php $link = Route::_('index.php?option=com_banners&task=click&id=' . $item->id); ?>
                        <?php if ($item->type == 1) : ?>
                            <?php // Text based banners ?>
                            <?php echo str_replace(['{CLICKURL}', '{NAME}'], [$link, $item->name], $item->custombannercode); ?>
                        <?php else : ?>
                            <?php $imageurl = $item->params->get('imageurl'); ?>
                            <?php $width = $item->params->get('width'); ?>
                            <?php $height = $item->params->get('height'); ?>
                            <?php if (BannerHelper::isImage($imageurl)) : ?>
                                <?php // Image based banner ?>
                                <?php $baseurl = strpos($imageurl, 'http') === 0 ? '' : Uri::base(); ?>
                                <?php $alt = $item->params->get('alt'); ?>
                                <?php $alt = $alt ?: $item->name; ?>
                                <?php $alt = $alt ?: Text::_('MOD_BANNERS_BANNER'); ?>
                                <?php if ($item->clickurl) : ?>
                                    <?php // Wrap the banner in a link ?>
                                    <?php $target = $params->get('target', 1); ?>
                                    <?php if ($target == 1) : ?>
                                        <?php // Open in a new window ?>
                                        <a
                                                href="<?php echo $link; ?>" target="_blank" rel="noopener noreferrer"
                                                title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>">
                                            <img
                                                    src="<?php echo $baseurl . $imageurl; ?>"
                                                    alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
                                                <?php if (!empty($width)) {
                                                    echo 'width="' . $width . '"';
                                                } ?>
                                                <?php if (!empty($height)) {
                                                    echo 'height="' . $height . '"';
                                                } ?>
                                            >
                                        </a>
                                    <?php elseif ($target == 2) : ?>
                                        <?php // Open in a popup window ?>
                                        <a
                                                href="<?php echo $link; ?>" onclick="window.open(this.href, '',
                                        'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');
                                        return false"
                                                title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>">
                                            <img
                                                    src="<?php echo $baseurl . $imageurl; ?>"
                                                    alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
                                                <?php if (!empty($width)) {
                                                    echo 'width="' . $width . '"';
                                                } ?>
                                                <?php if (!empty($height)) {
                                                    echo 'height="' . $height . '"';
                                                } ?>
                                            >
                                        </a>
                                    <?php else : ?>
                                        <?php // Open in parent window ?>
                                        <a
                                                href="<?php echo $link; ?>"
                                                title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>">
                                            <img
                                                    src="<?php echo $baseurl . $imageurl; ?>"
                                                    alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
                                                <?php if (!empty($width)) {
                                                    echo 'width="' . $width . '"';
                                                } ?>
                                                <?php if (!empty($height)) {
                                                    echo 'height="' . $height . '"';
                                                } ?>
                                            >
                                        </a>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <?php // Just display the image if no link specified ?>
                                    <img
                                            src="<?php echo $baseurl . $imageurl; ?>"
                                            alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
                                        <?php if (!empty($width)) {
                                            echo 'width="' . $width . '"';
                                        } ?>
                                        <?php if (!empty($height)) {
                                            echo 'height="' . $height . '"';
                                        } ?>
                                    >
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>


    <?php if ($footerText) : ?>
        <div class="mod-blocks__footer">
            <?php echo $footerText; ?>
        </div>
    <?php endif; ?>

</div>
