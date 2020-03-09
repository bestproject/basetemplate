<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.beez3
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use BestProject\TemplateHelper;

defined('_JEXEC') or die;

/**
 * Default module style.
 *
 * @since   1.0
 */
function modChrome_default($module, &$params, &$attribs)
{
	$module_params = $params;
	$header_class  = trim($params->get('header_class'));
	$header_class  = $header_class ?? null;

	$html = '';
	if ($module->showtitle)
	{
		$h       = $module_params->get('header_tag', 'h3');
		$attribs = ' class="module-title ' . ($header_class ?? '') . '"';
		$attribs.= ' id="module-'.$module->id.'-title"';
		$html    .= '<' . $h . $attribs . '>' . TemplateHelper::splitTitle(TemplateHelper::lonelyLetter($module->title)) . '</' . $h . '>';
	}
	$html .= TemplateHelper::lonelyLetter($module->content);

	echo $html;
}

/**
 *
 * Screen readers style.
 *
 * @since   1.0
 */
function modChrome_sr_only($module, &$params, &$attribs)
{
	$module_params = $params;
	$header_class  = trim($params->get('header_class'));
	$header_class  = $header_class ?? null;

	$html = '';
	if ($module->showtitle)
	{
		$h       = $module_params->get('header_tag', 'h3');
		$attribs = ' class="module-title ' . ($header_class ?? '') . ' sr-only"';
		$attribs.= ' id="module-'.$module->id.'-title"';
		$html    .= '<' . $h . $attribs . '>' . TemplateHelper::splitTitle(TemplateHelper::lonelyLetter($module->title)) . '</' . $h . '>';
	}
	$html .= TemplateHelper::lonelyLetter($module->content);

	echo $html;
}
