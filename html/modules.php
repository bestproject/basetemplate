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
		$h_class = ' class="module-title ' . ($header_class ?? '') . '"';
		$html    .= '<' . $h . $h_class . '>' . TemplateHelper::splitTitle(TemplateHelper::lonelyLetter($module->title)) . '</' . $h . '>';
	}
	$html .= TemplateHelper::lonelyLetter($module->content);

	echo $html;
}
