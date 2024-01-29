<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use BestProject\Helper\AssetsHelper;
use BestProject\Helper\TemplateHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * @var array $list List of items
 * @var Registry $params Module params.
 */

$id = 'mod_articles_category_alerts' . $module->id;
$list_count = count($list);

$options = [
    'autoplay' => [
        // Slide change delay
        'delay' => 4000,
        // No automatic change disabled after interaction
        'disableOnInteraction' => 'false',
    ],
    // Animation speed
    'speed' => 100,
    // Loop the slides
    'loop' => 'true',
    'effect' => 'fade',
    'crossFade' => 'true',
    'navigation' => [
        'prevEl' => "#{$id}__prev",
        'nextEl' => "#{$id}__next",
    ],
    'slidesPerView' => 1,
];


$options = json_encode((object)$options, JSON_THROW_ON_ERROR);

?>
<div class="articles-category articles-category-alerts d-flex w-100 justify-content-center position-relative">
    <?php if( $list_count===1 ): ?>
        <?php $item = $list[0]; ?>
        <?php $idx = 0 ?>
        <?php require ModuleHelper::getLayoutPath('mod_articles_category', 'alerts_item'); ?>
    <?php else: ?>
    <?php

        AssetsHelper::addEntryPointAssets('slider');

        /**
         * @var WebAssetManager $wa
         */
        $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
        $wa->addInlineScript("
            jQuery(function($){
                var ModArticlesCategoryAlerts{$module->id} = new Swiper('#$id', $options);
            });
        ");
    ?>
        <div id="<?php echo $id ?>"  class="swiper flex-shrink-1 w-100">
            <div class="swiper-wrapper">
                <?php foreach( $list AS $idx=>$item ): ?>
                    <div class="swiper-slide">
                        <?php require ModuleHelper::getLayoutPath('mod_articles_category', 'alerts_item'); ?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <button id="<?php echo $id ?>__prev" class="btn-previous d-flex align-items-center justify-content-center">
            <span class="visually-hidden"><?php echo Text::_('JPREV') ?></span>
            <i class="theme-icon theme-icon-arrow-left" aria-hidden="true"></i>
        </button>
        <button id="<?php echo $id ?>__next" class="btn-next d-flex align-items-center justify-content-center">
            <span class="visually-hidden"><?php echo Text::_('JNEXT') ?></span>
            <i class="theme-icon theme-icon-arrow-right" aria-hidden="true"></i>
        </button>
    <?php endif ?>

</div>
