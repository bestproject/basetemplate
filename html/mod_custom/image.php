<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$image = $params->get('backgroundimage');
if( empty($image) ) return;
?>
<img src="<?php echo $image ?>" alt="<?php echo htmlspecialchars($module->title) ?>" class="img-fluid" />
