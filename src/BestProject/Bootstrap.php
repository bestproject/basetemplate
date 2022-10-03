<?php
/**
 * A Boostrap class for processing Joomla 4 modules position.
 *
 * @copyright Grupa Best Sp. z o.o.
 * @license   MIT
 */
namespace BestProject;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Registry\Registry;

/**
 * Build to return Bootstrap3 classes in modules and templates.
 *
 * @since 1.5
 */
abstract class Bootstrap
{

	/**
	 * Returns a Bootstrap 4 column classes build from desktop column size.
	 *
	 * @param   int  $size  Size of a column (from 0 to 12)
	 *
	 * @return  string
	 *
	 * @since 1.5
	 */
	public static function getColumnClass(int $size): string
	{
		switch ($size)
		{
			case 11:
				return 'col-xl-11 col-12';
			case 10:
				return 'col-xl-10 col-12';
			case 9:
				return 'col-xl-9 col-12';
			case 8:
				return 'col-xl-8 col-12';
			case 7:
				return 'col-xl-7 col-12';
			case 6:
				return 'col-xl-6 col-12';
			case 5:
				return 'col-xl-5 col-md-6 col-12';
			case 4:
				return 'col-xl-4 col-md-6 col-12';
			case 3:
				return 'col-xl-3 col-md-6 col-12';
			case 2:
				return 'col-xl-2 col-md-6 col-12';
			case 1:
				return 'col-xl-1 col-md-6 col-12';
			default :
				return 'col-12';
		}
	}

	/**
	 * Render a selected modules position.
	 *
	 * @param   string  $position      Name of the position to render
	 * @param   string  $itemClassSfx  A prefix for each module/column.
	 * @param   string  $rowClass      A prefix for items row.
	 * @param   bool    $columns       Should this position be rendered as a columns row.
	 *
	 * @return    string
	 *
	 * @since 1.5
	 */
	public static function position(string $position, string $itemClassSfx = '', string $rowClass = 'row', bool $columns = true): string
	{
		$modules = \JModuleHelper::getModules($position);

		// There are no modules in this position, do not create any HTML
		if( empty($modules) ) {
			return '';
		}

		$html = '';

		// Wrap around modules if columns are enabled
		if ($columns || !empty($rowClass))
		{
			$html .= '<div class="' . $rowClass . '">';
		}

		foreach ($modules as $module)
		{
			$module_params = new Registry($module->params);
			$module_tag    = $module_params->get('module_tag', 'h3');

			// Use columns
			$column_class = 'module ';
			if ($columns)
			{
				$column_class .= self::getColumnClass($module_params->get('bootstrap_size', '0'));
			}

			// Add each module a class
			if (!empty($itemClassSfx))
			{
				$column_class .= ' ' . $itemClassSfx;
			}

			$attribs = ' class="' . trim($column_class) . '"';
			if( $module->showtitle ) {
				$attribs.= ' aria-labeledby="module-'.$module->id.'-title"';
			}

			$html .= '<' . $module_tag . $attribs .'>';

			$style = $module_params->get('style', 'default');
			if ($style === '0')
			{
				$style = 'default';
			}

			$html .= ModuleHelper::renderModule($module, ['style' => $style]);
			$html .= '</' . $module_tag . '>';
		}

		// Wrap around modules if columns are enabled
		if ($columns || !empty($rowClass))
		{
			$html .= '</div>';
		}

		return $html;
	}
}