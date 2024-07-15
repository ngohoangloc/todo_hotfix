<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$width = isset($width) ? $width : "";
?>

<?php

if (!empty($value) && trim($value) != "") {

    $timeline = $value;
    $dateNow = new DateTime(date("d-m-Y"));
    $startTime = explode("-", $value)[0];
    $endTime = explode("-", $value)[1];

    $new_data_start = new DateTime(implode("-", explode("/", $startTime)));
    $new_data_end = new DateTime(implode("-", explode("/", $endTime)));

    $timeDiff = $new_data_end->diff($dateNow);
    $value = (int)$timeDiff->format("%d");
}
?>

<div class="input-group input-group-table">
    <div class="timeline_meta w-100 h-100 text-center" style="font-size: 12px;" data-meta-id="<?= $meta_id ?>" data-value="<?= isset($timeline) ? $timeline : "" ?>" data-test="<?= $value ?>">
        <?php if (!empty($value) || trim($value) != '') : ?>
            <div class="<?= $value <= 3 || $value == 0 ? "bg-danger" : "bg-success" ?>  text-light d-flex align-items-center justify-content-center rounded-2 w-100 h-100" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="<?= $value ?> ngày">
                <span style="position: absolute; left: 10px; transform: translateY(-50%); top: 50%;">
                    <?php if ($value <= 3 || $value == 0) : ?>
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                    <?php else : ?>
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <?php endif; ?>
                </span>
                <input type="text" name="daterange" class="text-center text-light" value="<?= $timeline ?>" style="flex: 1; height: 100%; caret-color: transparent; cursor: pointer; font-size: 12px; outline: transparent;" data-meta-id="<?= $meta_id ?>">
            </div>
        <?php else : ?>
            <div class="bg-secondary rounded-2 text-light px-2 h-100 d-flex align-items-center justify-content-center ">
                <input type="text" name="daterange" class="text-center text-light" value="Chọn ngày" style="flex: 1; height: 100%; caret-color: transparent; cursor: pointer; font-size: 12px; outline: none;" data-meta-id="<?= $meta_id ?>">
            </div>
        <?php endif; ?>
    </div>
</div>