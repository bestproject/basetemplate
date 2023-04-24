<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * @var array $list List of items.
 * @var Registry $params Module params.
 */
?>
<ul class="articles-logos row list-unstyled align-items-stretch">
	<?php foreach( $list AS $item ):
		$images = json_decode($item->images, JSON_FORCE_OBJECT, 512, JSON_THROW_ON_ERROR);
		?>
        <li class="col-12 col-lg-6 col-xl-3 d-flex align-items-stretch py-15">
			<?php if ((int)$params->get('link_titles') === 1) : ?>
                <a class="mod-articles-category-title border flex-grow-1 d-flex bg-white align-items-center justify-content-center p-2 rounded-lg <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
                    <img src="<?php echo $images->image_intro ?>" alt="<?php echo $item->title ?>" />
                </a>
            <?php else: ?>
                <div class="mod-articles-category-title border flex-grow-1 d-flex bg-white align-items-center justify-content-center p-2 rounded-lg <?php echo $item->active; ?>">
                    <img src="<?php echo $images->image_intro ?>" alt="<?php echo $item->title ?>" />
                </div>
            <?php endif ?>
        </li>
	<?php endforeach ?>
</ul>