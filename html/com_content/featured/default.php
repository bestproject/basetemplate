<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

JHtml::_('behavior.caption');

// If the page class is defined, add to class as suffix.
// It will be a separate class if the user starts it with a space
?>
<section class="blog-featured<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
	<?php if ($this->params->get('show_page_heading') != 0) : ?>
        <div class="page-header">
            <h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
            </h1>
        </div>
	<?php endif; ?>
	<?php if ($this->params->get('page_subheading')) : ?>
        <h2>
			<?php echo $this->escape($this->params->get('page_subheading')); ?>
        </h2>
	<?php endif; ?>
	<?php $leadingcount = 0; ?>

	<?php if (!empty($this->lead_items)) : ?>
        <div class="items-leading clearfix">
			<?php foreach ($this->lead_items as &$item) : ?>
                <article
                        class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?> pb-3"
                        itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
					<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
					?>
                </article>
				<?php
				$leadingcount++;
				?>
			<?php endforeach; ?>
        </div>
	<?php endif; ?>

	<?php
	$introcount = count($this->intro_items);
	$counter    = 0;
	?>
	<?php if (!empty($this->intro_items)) : ?>
        <div class="items row">
			<?php foreach ($this->intro_items as $key => &$item) : ?>

				<?php
				$key      = ($key - $leadingcount) + 1;
				$rowcount = (((int) $key - 1) % (int) $this->columns) + 1;
				$row      = $counter / $this->columns;
				?>
                <article
                        class="item col-12 col-lg-<?php echo round(12 / $this->columns); ?> pb-3 <?php echo $item->state == 0 ? ' system-unpublished' : null; ?> span<?php echo round(12 / $this->columns); ?>"
                        itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
					<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
					?>
                </article>
				<?php $counter++; ?>

			<?php endforeach; ?>
        </div>
	<?php endif; ?>

	<?php if (!empty($this->link_items)) : ?>
        <div class="items-more">
			<?php echo $this->loadTemplate('links'); ?>
        </div>
	<?php endif; ?>

	<?php if ($this->params->def('show_pagination', 2) == 1 || ($this->params->get('show_pagination') == 2 && $this->pagination->pagesTotal > 1)) : ?>
        <div class="pagination-wrapper text-center pt-3">

			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
                <p class="counter py-2 text-center">
					<?php echo $this->pagination->getPagesCounter(); ?>
                </p>
			<?php endif; ?>

			<?php echo $this->pagination->getPagesLinks(); ?>
        </div>
	<?php endif; ?>

</section>
