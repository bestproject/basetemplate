<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Router\Route;
use Joomla\CMS\WebAsset\WebAssetManager;

defined('_JEXEC') or die;
?>

<ol itemscope itemtype="https://schema.org/BreadcrumbList" class="breadcrumb">
	<?php if ($params->get('showHere', 1)) : ?>
		<li>
			<?php echo JText::_('MOD_BREADCRUMBS_HERE'); ?>&#160;
		</li>
	<?php else : ?>
		<li class="active">
			<span class="divider icon-location"></span>
		</li>
	<?php endif; ?>

	<?php
	// Get rid of duplicated entries on trail including home page when using multilanguage
	for ($i = 0; $i < $count; $i++)
	{
		if ($i === 1 && !empty($list[$i]->link) && !empty($list[$i - 1]->link) && $list[$i]->link === $list[$i - 1]->link)
		{
			unset($list[$i]);
		}
	}

	// Find last and penultimate items in breadcrumbs list
	end($list);
	$last_item_key   = key($list);
	prev($list);
	$penult_item_key = key($list);

	// Make a link if not the last item in the breadcrumbs
	$show_last = $params->get('showLast', 1);

	// Generate the trail
	foreach ($list as $key => $item) :
		if ($key !== $last_item_key) :
			// Render all but last item - along with separator ?>
			<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<?php if (!empty($item->link)) : ?>
					<a itemprop="item" href="<?php echo $item->link; ?>" class="pathway">
                        <span itemprop="name"><?php echo $item->name; ?></span>
                    </a>
				<?php else : ?>
					<span itemprop="name">
						<?php echo $item->name; ?>
					</span>
				<?php endif; ?>

				<?php if (($key !== $penult_item_key) || $show_last) : ?>
					<i class="fas fa-angle-right" aria-hidden="true"></i>
				<?php endif; ?>
				<meta itemprop="position" content="<?php echo $key + 1; ?>">
			</li>
		<?php elseif ($show_last) :
			// Render last item if reqd. ?>
			<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="breadcrumb-item active" aria-current="page">
				<span itemprop="name">
					<?php echo $item->name; ?>
				</span>
				<meta itemprop="position" content="<?php echo $key + 1; ?>">
			</li>
		<?php endif;
	endforeach; ?>
</ol>
<?php

// Structured data as JSON
$data = [
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => []
];

// Use an independent counter for positions. E.g. if Heading items in pathway.
$itemsCounter = 0;

// If showHome is disabled use the fallback $homeCrumb for startpage at first position.
if (isset($homeCrumb)) {
    $data['itemListElement'][] = [
        '@type'    => 'ListItem',
        'position' => ++$itemsCounter,
        'item'     => [
            '@id'  => Route::_($homeCrumb->link, true, Route::TLS_IGNORE, true),
            'name' => $homeCrumb->name,
        ],
    ];
}

foreach ($list as $key => $item) {
    // Only add item to JSON if it has a valid link, otherwise skip it.
    if (!empty($item->link)) {
        $data['itemListElement'][] = [
            '@type'    => 'ListItem',
            'position' => ++$itemsCounter,
            'item'     => [
                '@id'  => Route::_($item->link, true, Route::TLS_IGNORE, true),
                'name' => $item->name,
            ],
        ];
    } elseif ($key === $last_item_key) {
        // Add the last item (current page) to JSON, but without a link.
        // Google accepts items without a URL only as the current page.
        $data['itemListElement'][] = [
            '@type'    => 'ListItem',
            'position' => ++$itemsCounter,
            'item'     => [
                'name' => $item->name,
            ],
        ];
    }
}

if ($itemsCounter) {
    /** @var WebAssetManager $wa */
    $wa = $app->getDocument()->getWebAssetManager();
    $wa->addInline('script', json_encode($data, JSON_UNESCAPED_UNICODE), [], ['type' => 'application/ld+json']);
}
?>
