<?php

/**
 * @var object $item
 * @var int $list_count
 * @var int $idx
 */
?>
<div class="alert alert-danger d-flex align-items-center justify-content-between mb-0" role="alert">
    <div class="d-flex align-items-center <?php echo (( $list_count>1 ) ? 'pe-5':'') ?>">
        <i class="fas fa-warning flex-shrink-0 me-3" aria-hidden="true"></i>

        <?php if( $item->introtext==='' ): ?>
            <div>
                <?php echo $item->title ?>
            </div>
        <?php else: ?>
            <a href="<?php echo $item->link ?>">
                <?php echo $item->title ?>
            </a>
        <?php endif ?>

        <?php if( $list_count>1 ): ?>
            <span class="ms-2" aria-hidden="true">(<?php echo ($idx+1)."/$list_count" ?>)</span>
        <?php endif ?>
    </div>
</div>
