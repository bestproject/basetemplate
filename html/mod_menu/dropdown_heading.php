<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Utilities\ArrayHelper;

$attributes = [];

if ($item->anchor_title) {
    $attributes['title'] = $item->anchor_title;
}

$attributes['class'] = 'mod-menu__heading nav-header';
$attributes['class'] .= $item->anchor_css ? ' ' . $item->anchor_css : null;

if( $showAll && $item->deeper ) {
    $attributes['class'].= ' dropdown-toggle';
    $attributes['data-bs-toggle'] = 'dropdown';
    $attributes['data-bs-offset'] = '0,0';
    $attributes['aria-haspopup'] = 'true';
    $attributes['aria-expanded'] = 'false';
}

$linktype = $item->title;

if ($item->menu_icon) {
    // The link is an icon
    if ($itemParams->get('menu_text', 1)) {
        // If the link text is to be displayed, the icon is added with aria-hidden
        $linktype = '<i class=" ' . $item->menu_icon . '" aria-hidden="true"></i>' . $item->title;
    } else {
        // If the icon itself is the link, it needs a visually hidden text
        $linktype = '<i class=" ' . $item->menu_icon . '" aria-hidden="true"></i><span class="visually-hidden">' . $item->title . '</span>';
    }
} elseif ($item->menu_image) {
    // The link is an image, maybe with an own class
    $image_attributes = [];

    if ($item->menu_image_css) {
        $image_attributes['class'] = $item->menu_image_css;
    }

    $linktype = HTMLHelper::_('image', $item->menu_image, $item->title, $image_attributes);

    if ($itemParams->get('menu_text', 1)) {
        $linktype .= '<span class="image-title">' . $item->title . '</span>';
    }
}

echo '<span ' . ArrayHelper::toString($attributes) . '>' . $linktype . '</span>';