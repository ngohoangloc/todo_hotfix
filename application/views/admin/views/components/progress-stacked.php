<?php
$status = [
    ['hoanthanh', 'success'],
    ['danglam', 'warning'],
    ['chuabatdau', 'secondary'],
    ['chuahoanthanh', 'danger']
];
?>

<?php foreach ($status as $st) : ?>
    <?php $percent = $this->Items_model->get_percent($group_id, $key, $st[0]); ?>
    <?php if ($percent[0] != "0") : ?>
        <div class="progress bg-<?= $st[1]; ?>" data-bs-toggle="tooltip" data-placement="top" data-bs-title="<?= $percent[1]  ?>/<?= count($tasks) ?>" role="progressbar" aria-label="Tien do" aria-valuenow="<?= ceil($percent[0]); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= ceil($percent[0]); ?>%; border-radius: 0 !important;">
            <div class="progress-bar"></div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>