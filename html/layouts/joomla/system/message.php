<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$msgList = $displayData['msgList'];

if( !empty($msgList) ): ?>
<ul id="system-message" class="container">
    <?php foreach( $msgList AS $type=>$messages ):
    switch($type) {
        case 'error': $type_class='danger';break;
        case 'notice': $type_class='info';break;
        case 'warning': $type_class='warning';break;
        case 'message': $type_class='success';break;
        default: $type_class = $type;
    }
    ?>
        <?php foreach( $messages AS $message ): ?>
            <li class="alert alert-dismissible alert-<?php echo $type_class ?>" role="alert">
               <button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-close"></i></button>
               <strong><?php echo JText::_($type) ?></strong><?php echo $message ?>
            </li>
        <?php endforeach ?>
    <?php endforeach ?>
</ul>
<?php endif;