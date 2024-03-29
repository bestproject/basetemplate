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
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * @var array $list
 * @var bool $showAll
 * @var int $active_id
 * @var array $path
 * @var int $default_id
 * @var object $module
 * @var object $active
 * @var string $class_sfx
 * @var Registry $params
 */
$attributes          = [];
$nav_class_suffix = $nav_class_suffix ?? 'flex-column';
$attributes['class'] = 'mod-menu nav '. $nav_class_suffix .' ' . $class_sfx .' mod-menu-default';

$tagId = $params->get('tag_id', 'mod-menu-default-'.$module->id);
if ($tagId) {
    $attributes['id'] = $tagId;
}

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
        $submenuClass = ['nav','flex-column','flex-nowrap','collapse','ps-3'];

        if ($item->id == $default_id) {
            $class[] = 'default';
        }

        if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id)) {
            $class[] = 'active';
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

            if( in_array($item->id, $active->tree) ) {
                $submenuClass[] = ' show';
            }
        }

        echo '<li class="' . implode(' ', $class) . '">';

        switch ($item->type) :
            case 'separator':
            case 'component':
            case 'heading':
            case 'url':
                require ModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
                break;

            default:
                require ModuleHelper::getLayoutPath('mod_menu', 'default_url');
        endswitch;

        switch (true) :
            // The next item is deeper.
            case $showAll && $item->deeper:

                $submenuAttributes = [
                    'class' => implode(' ', $submenuClass),
                    'id' => 'mod-menu-'.$module->id.'-submenu-'.$item->id,
                    'data-bs-parent' => '#'.$tagId,
                ];

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
