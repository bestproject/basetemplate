<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use BestProject\Helper\AssetsHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * @var array $list List of items
 * @var Registry $params Module params.
 */

AssetsHelper::addEntryPointAssets('slider');

$id = 'mod_articles_category_slider' . $module->id;
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
    'spaceBetween' => 20,

    // Center slides
    'centeredSlides' => true,

    // Responsive settings
    'breakpoints' => [
        1300 => [
            'slidesPerView' => min($count, 6),
        ],
        1170 => [
            'slidesPerView' => min($count, 3),
        ],
        768 => [
            'slidesPerView' => min($count, 2),
        ],
        0 => [
            'slidesPerView' => 1,
        ],
    ]
];


$options = json_encode((object)$options, JSON_THROW_ON_ERROR);

/**
 * @var WebAssetManager $wa
 */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->addInlineScript("
    jQuery(function($){
        var ModArticlesCategoryLogos{$module->id} = new Swiper('#$id', $options);
    });
");

?>
<div class="articles-category logos d-flex w-100 justify-content-center">
    <div id="<?php echo $id ?>" class="swiper flex-shrink-1 w-100">
        <div class="swiper-wrapper">
            <?php foreach( $list AS $item ):
                $images = json_decode($item->images);
                ?>
                <div class="swiper-slide">
                    <?php if ((int)$params->get('link_titles') === 1) : ?>
                        <a class="mod-articles-category-title border flex-grow-1 d-flex bg-white align-items-center justify-content-center h-100 p-2 rounded-lg <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
                            <img src="<?php echo $images->image_intro ?>" alt="<?php echo $item->title ?>" class="img-fluid m-1" />
                        </a>
                    <?php else: ?>
                        <div class="mod-articles-category-title border flex-grow-1 d-flex bg-white align-items-center justify-content-center h-100 p-2 rounded-lg <?php echo $item->active; ?>">
                            <img src="<?php echo $images->image_intro ?>" alt="<?php echo $item->title ?>" class="img-fluid m-1" />
                        </div>
                    <?php endif ?>
                </div>
            <?php endforeach ?>

        </div>
    </div>
</div>
