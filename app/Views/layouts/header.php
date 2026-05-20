<?php
$uri = service('uri');
$segment1 = $uri->getSegment(1);
$segment2 = $uri->getSegment(2);
?>

<section class="content-header">
    <div class="content-title">
        <h1><?= $title ?></h1>
    </div>
    <div class="breadcrumb">
        <i class="fa-solid fa-gauge-high"></i>
        <a class="breadcrumb-item" href="<?= base_url('dashboard') ?>">Home</a>
        <i class="fa-solid fa-angle-right"></i>
        <?php if ($segment2): ?>
            <a class="breadcrumb-item" href="<?= base_url('dashboard/' . $segment2) ?>">
                <?= ucfirst(str_replace(['_', '-'], ' ', $segment2)) ?>
            </a>
        <?php else: ?>
            <span class="breadcrumb-item">
                <?= ucfirst($segment1) ?>
            </span>
        <?php endif; ?>
    </div>
</section>