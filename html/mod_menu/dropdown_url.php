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

if( $item->deeper ) {
	$class = ' dropdown-toggle';
	$attrib = ' data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" ';
} else {
	$class ='';
	$attrib ='';
}

$class = $item->anchor_css ? 'class="' . $item->anchor_css . $class . '" ' : ' class="'.trim($class).'" ';
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';

if ($item->menu_image)
{
	$item->params->get('menu_text', 1) ?
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="title">' . $item->title . '</span> ' :
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
}
else
{
	$linktype = '<span>'.$item->title.'</span>';
}

if( $item->deeper ) {
	$linktype.= '<i class="fa fa-angle-down"></i>';
}

$flink = $item->flink;
$flink = JFilterOutput::ampReplace(htmlspecialchars($flink));

switch ($item->browserNav) :
	default:
	case 0:
?><a <?php echo $class,$attrib ?>href="<?php echo $flink; ?>" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
		break;
	case 1:
		// _blank
?><a <?php echo $class,$attrib ?>href="<?php echo $flink; ?>" target="_blank" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
		break;
	case 2:
		// Use JavaScript "window.open"
		$options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $params->get('window_open');
			?><a <?php echo $class,$attrib ?>href="<?php echo $flink; ?>" onclick="window.open(this.href,'targetWindow','<?php echo $options;?>');return false;" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
		break;
endswitch;
