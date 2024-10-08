<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * @var object $module
 */

$collapseLayout = true;
$module_id = 'offcanvas-menu-'.$module->id;
?>

<nav aria-label="<?php echo htmlspecialchars($module->title, ENT_QUOTES, 'UTF-8'); ?>" class="d-flex justify-content-end justify-content-lg-start">

    <button class="btn btn-outline-primary d-inline-flex align-items-center d-lg-none px-3 py-2 my-3" id="<?php echo $module_id ?>-activator" type="button" data-bs-toggle="offcanvas" data-bs-target="#<?php echo $module_id; ?>"  aria-controls="<?php echo $module_id; ?>" aria-expanded="false" aria-label="<?php echo Text::_('MOD_MENU_TOGGLE'); ?>">
        <i class="fas fa-bars" aria-hidden="true"></i>
        <span class="visually-hidden"><?php echo Text::_('TPL_BASETEMPLATE_MENU') ?></span>
    </button>

    <div class="d-none d-lg-block">
        <?php
        $nav_class_suffix = 'mod-menu-offcanvas flex-column flex-lg-row desktop-menu';
        require __DIR__ . '/horizontal.php'; ?>
    </div>

    <div class="offcanvas offcanvas-end d-flex d-lg-none" tabindex="-1" id="<?php echo $module_id ?>" aria-labelledby="<?php echo $module_id ?>-title">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title h6 text-primary" id="<?php echo $module_id ?>-title"><?php echo $module->title ?></h5>
            <button type="button" class="btn btn-outline-primary px-2 py-1 mt-1 me-1 d-flex align-items-center" data-bs-dismiss="offcanvas" aria-label="<?php echo Text::_('JCLOSE') ?>">
                <i class="fas fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
        <div class="offcanvas-body py-2">
            <?php
            $nav_class_suffix = 'mod-menu-offcanvas flex-column flex-lg-row mobile-menu';
            require __DIR__ . '/default.php'; ?>
        </div>
    </div>
</nav>
