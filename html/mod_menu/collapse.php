<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Menu\MenuItem;
use Joomla\Utilities\ArrayHelper;

$attributes          = [];
$nav_class_suffix = $nav_class_suffix ?? 'flex-column';
$attributes['class'] = 'mod-menu nav '. $nav_class_suffix .' flex-column mod-menu-collapse' . $class_sfx;

if ($tagId = $params->get('tag_id', '')) {
    $attributes['id'] = $tagId;
}

/**
 * @var MenuItem $active
 * @var MenuItem $default
 * @var object $module
 */

$start = (int) $params->get('startLevel', 1);

?>
<ul <?php echo ArrayHelper::toString($attributes); ?>>
    <?php foreach ($list as $i => &$item) {
        // Skip sub-menu items if they are set to be hidden in the module's options
        if (!$showAll && $item->level > $start) {
            continue;
        }

        $itemParams = $item->getParams();
        $class      = [];
        $class[]    = 'nav-item item-' . $item->id . ' level-' . ($item->level - $start + 1);

        if ($item->id == $default_id) {
            $class[] = 'default';
        }

        if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id)) {
            $class[] = 'current';
        }

        if (in_array($item->id, $path)) {
            $class[] = 'active';
        } elseif ($item->type === 'alias') {
            $aliasToId = $itemParams->get('aliasoptions');

            if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
                $class[] = 'active';
            } elseif (in_array($aliasToId, $path)) {
                $class[] = 'alias-parent-active';
            }
        }

        if ($item->type === 'separator') {
            $class[] = 'divider';
        }

        if ($showAll) {
            if ($item->deeper) {
                $class[] = 'deeper dropdown';
            }

            if ($item->parent) {
                $class[] = 'parent';
            }
        }

        echo '<li class="' . implode(' ', $class) . '">';

        switch ($item->type) :
            case 'separator':
            case 'component':
            case 'heading':
            case 'url':
                require ModuleHelper::getLayoutPath('mod_menu', 'collapse_' . $item->type);
                break;

            default:
                require ModuleHelper::getLayoutPath('mod_menu', 'collapse_url');
        endswitch;

        switch (true) :
            // The next item is deeper.
            case $showAll && $item->deeper:
                $submenuAttributes = [
                    'class' => implode(' ', ['']),
                    'id' => 'mod-menu-'.$module->id.'-submenu-'.$item->id,
                ];

                if( in_array($item->id, $active->tree) ) {
                    $submenuAttributes['class'].= ' collapse show';
                } else {
                    $submenuAttributes['class'].= ' collapse';
                }

                echo '<ul '.ArrayHelper::toString($submenuAttributes).'>';
                break;

            // The next item is shallower.
            case $item->shallower:
                echo '</li>';
                echo str_repeat('</ul></li>', $item->level_diff);
                break;

            // The next item is on the same level.
            default:
                echo '</li>';
                break;
        endswitch;
    }
    ?></ul>
