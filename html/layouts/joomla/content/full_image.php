<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
$params = $displayData->params;
?>
<?php $images = json_decode($displayData->images); ?>
<?php if (!empty($images->image_fulltext)) : ?>
	<?php $imgfloat = empty($images->float_fulltext) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
    <?php
    $imgfloat.= ' mb-2';
    switch( $imgfloat ) {
        case 'left':
            $imgfloat.= ' mr-md-3 mb-md-3';
            break;
        case 'right':
            $imgfloat.= ' ml-md-3 mb-md-3';
            break;
        default:
            $imgfloat.= ' mb-md-3';
            break;
    }
    ?>
	<div class="float-md-<?php echo htmlspecialchars($imgfloat); ?> item-image"> <img
		<?php if ($images->image_fulltext_caption) :
			echo 'class="caption"' . ' title="' . htmlspecialchars($images->image_fulltext_caption) . '"';
		endif; ?>
	src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>" itemprop="image"/> </div>
<?php endif; ?>
