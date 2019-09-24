<?php

use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$class_sfx = 'nav '.$class_sfx;

// Note. It is important to remove spaces between elements.

$default = JFactory::getApplication()->getMenu()->getDefault(JFactory::getLanguage()->getTag());

$tag_id = $params->get('tag_id');
$moduleclass_sfx = $params->get('moduleclass_sfx');

/* @var $doc HtmlDocument */
$doc = Factory::getDocument();
$doc->addScriptDeclaration('
    jQuery(function($){
        $(".dropdown-menu a.dropdown-toggle").on("click", function(e) {
            if (!$(this).next().hasClass("show")) {
                $(this).parents(".dropdown-menu").first().find(".show").removeClass("show");
            }

            var $subMenu = $(this).next(".dropdown-menu");
            $subMenu.toggleClass("show");

            $(this).parents("li.nav-item.dropdown.show").on("hidden.bs.dropdown", function(e) {
                $(".dropdown-submenu .show").removeClass("show");
            });

            return false;
        });
    });
');
?>
<?php // The menu class is deprecated. Use nav instead. ?>
<?php if (!empty($moduleclass_sfx) or !empty($tag_id)): ?>
<div class="modmenu<?php echo $moduleclass_sfx ?>" <?php echo($tag_id ? 'id="' . $tag_id . '"' : '') ?>>
	<?php endif ?>
    <ul class="<?php echo trim($class_sfx) ?>">
		<?php
		foreach ($list as $i => &$item)
		{

			// No images allowed in this menu type
			$item->menu_image = null;

			$class = 'nav-item item-' . $item->id;

			if (($item->id == $active_id) OR ($item->type == 'alias' AND $item->params->get('aliasoptions') == $active_id))
			{
				$class .= ' current';
			}

			if ($item->id == $default->id)
			{
				$class .= ' default';
			}

			if (in_array($item->id, $path))
			{
				$class .= ' active';
			}
            elseif ($item->type == 'alias')
			{
				$aliasToId = $item->params->get('aliasoptions');

				if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
				{
					$class .= ' active';
				}
                elseif (in_array($aliasToId, $path))
				{
					$class .= ' alias-parent-active';
				}
			}

			if ($item->type == 'separator')
			{
				$class .= ' dropdown-divider';
			}

			if ($item->deeper)
			{
				$class .= ' dropdown';
			}

			if ($item->parent)
			{
				$class .= ' parent';
			}

			if (!empty($class))
			{
				$class = ' class="' . trim($class) . '"';
			}

			echo '<li' . $class . '>';

			// Render the menu item.
			switch ($item->type) :
				case 'separator':
				case 'url':
				case 'component':
				case 'heading':
					require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
					break;

				default:
					require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
					break;
			endswitch;

			// The next item is deeper.
			if ($item->deeper)
			{
				echo '<ul class="dropdown-menu">';
			}
            elseif ($item->shallower)
			{
				// The next item is shallower.
				echo '</li>';
				echo str_repeat('</ul></li>', $item->level_diff);
			}
			else
			{
				// The next item is on the same level.
				echo '</li>';
			}
		}
		?></ul>
	<?php if (!empty($moduleclass_sfx) or !empty($tag_id)): ?>
</div>
<?php endif ?>
