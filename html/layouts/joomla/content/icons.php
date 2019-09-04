<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JHtml::_('bootstrap.framework');

$canEdit = $displayData['params']->get('access-edit');

?>

<div class="icons">
	<?php if (empty($displayData['print'])) : ?>

		<?php if ($canEdit || $displayData['params']->get('show_print_icon') || $displayData['params']->get('show_email_icon')) : ?>
            <div class="btn-group float-right">
                <a class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-cog" aria-hidden="true"></i>
                </a>
				<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
                <ul class="dropdown-menu">
					<?php if ($displayData['params']->get('show_print_icon')) : ?>
                        <li class="dropdown-item print-icon"><?php echo str_ireplace('icon-print', 'fa fa-print', JHtml::_('icon.print_popup', $displayData['item'], $displayData['params'])); ?> </li>
					<?php endif; ?>
					<?php if ($displayData['params']->get('show_email_icon')) : ?>
                        <li class="dropdown-item email-icon"><?php echo str_ireplace('icon-envelope', 'fa fa-envelope', JHtml::_('icon.email', $displayData['item'], $displayData['params'])); ?> </li>
					<?php endif; ?>
					<?php if ($canEdit) : ?>
                        <li class="dropdown-item edit-icon"><?php echo str_ireplace('icon-edit', 'fa fa-edit', JHtml::_('icon.edit', $displayData['item'], $displayData['params'])); ?> </li>
					<?php endif; ?>
                </ul>
            </div>
		<?php endif; ?>

	<?php else : ?>

        <div class="float-right">
			<?php echo JHtml::_('icon.print_screen', $displayData['item'], $displayData['params']); ?>
        </div>

	<?php endif; ?>
</div>
