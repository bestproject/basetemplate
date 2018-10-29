<?php
/**
 * A Boostrap 4 class for processing Joomla 3 modules position.
 */

namespace BestProject;

use Joomla\Registry\Registry;

/**
 * Build to return Bootstrap3 classes in modules and templates.
 */
abstract class Bootstrap4
{

    /**
     * Returns a Bootstrap 4 column classes build from desktop column size.
     *
     * @param   Integer $size   Size of a column (from 0 to 12)
     * @return  string
     */
    public static function getColumnClass($size)
    {
        switch ($size) {
            case 11: return 'col-lg-11 col-12';
            case 10: return 'col-lg-10 col-12';
            case 9: return 'col-lg-9 col-12';
            case 8: return 'col-lg-8 col-12';
            case 7: return 'col-lg-7 col-12';
            case 6: return 'col-lg-6 col-12';
            case 5: return 'col-lg-5 col-md-6 col-12';
            case 4: return 'col-lg-4 col-md-6 col-12';
            case 3: return 'col-lg-3 col-md-6 col-12';
            case 2: return 'col-lg-2 col-md-6 col-12';
            case 1: return 'col-lg-1 col-md-6 col-12';
            default : return 'col-12';
        }
    }

    /**
     * Render a selected modules position.
     *
     * @param	string	$position				Name of the position to render
     * @param	string	$itemClassSfx				A prefix for each module/column.
     * @param	string	$rowClass				A prefix for items row.
     * @param	bool	$columns				Should this position be rendered as a columns row.
     *
     * @return	string
     */
    public static function position($position, $itemClassSfx = '', $rowClass = 'row', $columns = true)
    {
        $modules = \JModuleHelper::getModules($position);
        $html = '';

        // Wrap around modules if columns are enabled
        if ($columns OR !empty($rowClass)) {
            $html .= '<div class="'.$rowClass.'">';
        }

        foreach ($modules AS $module) {
            $module_params = new Registry($module->params);
            $module_tag = $module_params->get('module_tag', 'h3');
            $header_class = $module_params->get('header_class');
            $header_class = $header_class ?? null;

            // Use columns
            $column_class = 'module ';
            if ($columns) {
                $column_class.= self::getColumnClass($module_params->get('bootstrap_size', '0'));
            }

            // Add each module a class
            if (!empty($itemClassSfx)) {
                $column_class .= ' '.$itemClassSfx;
            }

            $html .= '<'.$module_tag.' class="'.trim($column_class).'">';
            if ($module->showtitle) {
                $h       = $module_params->get('header_tag', 'h3');
                $h_class = ' class="'.($header_class ?? 'module-title').'"';
                $html    .= '<'.$h.$h_class.'>'.$module->title.'</'.$h.'>';
            }
            $html .= \JModuleHelper::renderModule($module);
            $html .= '</'.$module_tag.'>';
        }

        // Wrap around modules if columns are enabled
        if ($columns OR !empty($rowClass)) {
            $html .= '</div>';
        }

        return $html;
    }
}
