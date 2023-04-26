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

$collapseLayout = true;
?>

<nav aria-label="<?php echo htmlspecialchars($module->title, ENT_QUOTES, 'UTF-8'); ?>">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $module->id; ?>" aria-controls="collapse-<?php echo $module->id; ?>" aria-expanded="false" aria-label="<?php echo Text::_('MOD_MENU_TOGGLE'); ?>">
        <i class="fas fa-hamburger" aria-hidden="true"></i>
    </button>
    <div class="collapse navbar-collapse" id="collapse-<?php echo $module->id; ?>">
        <?php
        $nav_class_suffix = 'navbar-nav flex-column flex-lg-row';
        require __DIR__ . '/horizontal.php'; ?>
    </div>
</nav>
