<?php

namespace BestProject;

/**
 * Build to return Bootstrap3 classes in modules and templates.
 */
abstract class Bootstrap3
{

    /**
     * Returns a Bootstrap 3 column classes build from desktop column size.
     *
     * @param   Integer $size   Size of a column (from 0 to 12)
     * @return  string
     */
    public static function getColumnClass($size)
    {
        switch ($size) {
            case 11: return 'col-md-11 col-sm-10 col-xs-8';
            case 10: return 'col-md-10 col-sm-8 col-xs-8';
            case 9: return 'col-md-9 col-sm-8 col-xs-12';
            case 8: return 'col-md-8 col-sm-8 col-xs-12';
            case 7: return 'col-md-7 col-sm-6 col-xs-12';
            case 6: return 'col-md-6 col-sm-6 col-xs-12';
            case 5: return 'col-md-5 col-sm-6 col-xs-12';
            case 4: return 'col-md-4 col-sm-12 col-xs-12';
            case 3: return 'col-md-3 col-sm-6 col-xs-12';
            case 2: return 'col-md-2 col-sm-4 col-xs-4';
            case 1: return 'col-md-1 col-sm-2 col-xs-4';
            default : return 'col-xs-12';
        }
    }

    /**
     * Returns a Bootstral 3 column classes build from items count.
     *
     * @param   Integer $count  Number if items in a list.
     * @return  string
     */
    public static function getColumnClassFromCount($count)
    {
        if ($count % 4 == 0) {
            return 'col-md-3 col-sm-6 col-xs-12';
        } elseif ($count % 3 == 0) {
            return 'col-md-4 col-sm-6 col-xs-12';
        } elseif ($count % 3 > $count % 4) {
            return 'col-md-4 col-sm-6 col-xs-12';
        } elseif ($count % 3 < $count % 4) {
            return 'col-md-3 col-sm-6 col-xs-12';
        } else {
            return $count == 2 ? 'col-md-6 col-xs-12' : 'col-xs-12';
        }
    }

	/**
	 * Render a selected modules position.
	 *
	 * @param	string	$position				Name of the position to render
	 * @param	bool	$columns				Should this position be rendered as a columns row.
	 * @param	string	$classSfx				A prefix for each module/column.
	 * @param	bool	$responsive_classes		If set to TRUE each module/column will also recieve classes for various resolutions (xs,sm,md,lg)
	 *
	 * @return	string
	 */
	public static function renderModulesPosition($position, $columns = true, $classSfx = null, $responsive_classes = true){
		$modules = \JModuleHelper::getModules($position);

		$html = '';

		// Wrap around modules if columns are enabled
		if( $columns ) {
			$html.= '<div class="row">';
		}

		foreach( $modules AS $module ){
			$module_params	= new \Joomla\Registry\Registry($module->params);

			// Use columns
			$column_class = 'module';
			if( $columns AND $responsive_classes ) {
				$column_class = self::getColumnClass($module_params->get('bootstrap_size','0'));
			}

			// Add each module a class
			if( !is_null($classSfx) ){
				$column_class.=' '.$classSfx;
			}

			$html.= '<div class="'.$column_class.'">';
			if( $module->showtitle ) {
				$h			= $module_params->get('header_tag','h3');
				$h_class	= $module_params->get('header_class','')!=='' ? ' class="'.$module_params->get('header_class','').'"':'';
				$html		.='<'.$h.$h_class.'>'.$module->title.'</'.$h.'>';
			}
			$html.= \JModuleHelper::renderModule($module);
			$html.= '</div>';
		}

		// Wrap around modules if columns are enabled
		if( $columns ) {
			$html.= '</div>';
		}

		return $html;
	}

}