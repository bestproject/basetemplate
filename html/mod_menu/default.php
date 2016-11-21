<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.

$default = JFactory::getApplication()->getMenu()->getDefault(JFactory::getLanguage()->getTag());

?>
<ul class="nav nav-pills nav-stacked<?php echo $class_sfx ?> <?php echo $params->get('moduleclass_sfx') ?>"<?php
		$tag = '';

		if ($params->get('tag_id') != null)
		{
			$tag = $params->get('tag_id') . '';
			echo ' id="' . $tag . '"';
		}
	?>>
	<?php
	foreach ($list as $i => &$item)
	{

		// No images allowed in this menu type
		$item->menu_image = null;

		$class = 'item-' . $item->id;

		if (($item->id == $active_id) OR ($item->type == 'alias' AND $item->params->get('aliasoptions') == $active_id))
		{
			$class .= ' current';
		}

		if( $item->id==$default->id ) {
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
			$class .= ' divider';
		}

//		if ($item->deeper)
//		{
//			$class .= ' dropdown';
//		}

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
				require JModuleHelper::getLayoutPath('mod_menu', 'pills_' . $item->type);
				break;

			default:
				require JModuleHelper::getLayoutPath('mod_menu', 'pills_url');
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
