<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<ul class="articles-logos row list-unstyled align-items-stretch">
	<?php foreach( $list AS $item ):
		$images = json_decode($item->images);
		?>
        <li class="col-12 col-lg-6 col-xl-3 d-flex align-items-stretch py-15">
			<?php if ($params->get('link_titles') == 1) : ?>
            <a class="mod-articles-category-title border flex-grow-1 d-flex bg-white align-items-center justify-content-center p-2 rounded-lg <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
				<?php else: ?>
                <div class="mod-articles-category-title border flex-grow-1 d-flex bg-white align-items-center justify-content-center p-2 rounded-lg <?php echo $item->active; ?>">
					<?php endif ?>
                    <img src="<?php echo $images->image_intro ?>" alt="<?php echo $item->title ?>" />
					<?php if ($params->get('link_titles') == 1) : ?>
            </a>
		<?php else: ?>
            </div>
		<?php endif ?>
        </li>
	<?php endforeach ?>
</ul>