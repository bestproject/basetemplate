<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use BestProject\Helper\TemplateHelper;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

TemplateHelper::addEntryPointAssets('slider');

$id = 'mod_articles_category_slider' . $module->id;

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

    // Number of slides per view
    'slidesPerView' => 6,

    // Space between slides
    'spaceBetween' => 20,

    // Responsive settings
    'breakpoints' => [
        1170 => [
	        'slidesPerView' => 3,
        ],
        768 => [
	        'slidesPerView' => 1,
        ],
    ]
];


$options = json_encode((object)$options);
TemplateHelper::addScriptDeclaration("
    jQuery(function($){
        var ModArticlesCategoryLogos{$module->id} = new Swiper('#$id', $options);
    });
");

/**
 * @var array $list List of items
 * @var Registry $params Module params.
 */
?>
<div class="articles-category logos d-flex w-100 justify-content-center">
    <div id="<?php echo $id ?>"  class="swiper-container flex-shrink-1">
        <div class="swiper-wrapper">
			<?php foreach( $list AS $item ):
				$images = json_decode($item->images);
				?>
                <div class="swiper-slide">
                    <?php if ((int)$params->get('link_titles') === 1) : ?>
                        <a class="mod-articles-category-title border flex-grow-1 d-flex bg-white align-items-center justify-content-center p-2 rounded-lg <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
                            <img src="<?php echo $images->image_intro ?>" alt="<?php echo $item->title ?>" />
                        </a>
                    <?php else: ?>
                        <div class="mod-articles-category-title border flex-grow-1 d-flex bg-white align-items-center justify-content-center p-2 rounded-lg <?php echo $item->active; ?>">
                            <img src="<?php echo $images->image_intro ?>" alt="<?php echo $item->title ?>" />
                        </div>
                    <?php endif ?>
                </div>
			<?php endforeach ?>

        </div>
    </div>
</div>
