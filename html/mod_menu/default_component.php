<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$item->anchor_css.= ' nav-link';

if( $item->deeper AND count($item->tree)<=1 ) {
    if( stripos($params->get('layout', 'default'), ':dropdown')!=='false') {
        $attrib = ' ';
        $dropdown_class = ' dropdown-toggle';
        $dropdown_attrib = ' data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"';
    } else {
        $attrib = '';
    }
} else {
	$item->anchor_css.= ' ';
	$attrib ='';
}

if (in_array($item->id, $path))
{
    $item->anchor_css .= ' active';
}
elseif ($item->type == 'alias')
{
    $aliasToId = $item->params->get('aliasoptions');

    if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
    {
        $item->anchor_css .= ' active';
    }
    elseif (in_array($aliasToId, $path))
    {
        $item->anchor_css .= ' alias-parent-active';
    }
}

// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? 'class="' . trim($item->anchor_css) . '" ' : '';
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';

if ($item->menu_image)
{
	$item->params->get('menu_text', 1) ?
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="title">' . $item->title . '</span> ' :
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
}
else
{
	$menu_image_css = $item->params->get('menu_image_css');
	if (!empty($menu_image_css))
	{
		$linktype = '<i class="' . $menu_image_css . ' d-none d-xl-inline-flex" aria-hidden="true"></i>';
		$linktype .= '<span class="d-inline-flex d-xl-none">' . $item->title . '</span>';
	}
	else
	{
		$linktype = '<span>' . $item->title . '</span>';
	}
}

$dropdown = '';
if( stripos($params->get('layout', 'default'), ':dropdown')!=='false') {
    if( $item->deeper AND count($item->tree)<=1 ) {
        $dropdown = ' <button type="button"  class="btn '.$dropdown_class.'" '.$dropdown_attrib.'></button>';
    }
}

switch ($item->browserNav)
{
	default:
	case 0:
?><a <?php echo $class,$attrib ?>href="<?php echo $item->flink; ?>" <?php echo $title; ?>><?php echo $linktype; ?></a><?php echo $dropdown;
		break;
	case 1:
		// _blank
?><a <?php echo $class,$attrib ?>href="<?php echo $item->flink; ?>" target="_blank" <?php echo $title; ?>><?php echo $linktype; ?></a><?php echo $dropdown;
		break;
	case 2:
	// Use JavaScript "window.open"
?><a <?php echo $class,$attrib ?>href="<?php echo $item->flink; ?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;" <?php echo $title; ?>><?php echo $linktype; ?></a>
<?php echo $dropdown;
		break;
}
