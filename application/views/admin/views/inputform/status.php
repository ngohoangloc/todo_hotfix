<?php

$key = isset($key) ? $key : time();
$title = isset($title) ? $title : "";
$required = isset($required) ? $required : false;

$options = [
    'chuabatdau|secondary' => ['Chưa bắt đầu', 'secondary'],
    'danglam|warning' => ['Đang làm', 'warning'],
    'chuahoanthanh|danger' => ['Chưa hoàn thành', 'danger'],
    'hoanthanh|success' => ['Hoàn thành', 'success'],
];
?>
<div class="input-group" <?= $required ? "required" : "" ?> data-title="<?= $title ?>" data-key="<?= $key ?>">
    <select name="<?= $key ?>" class="form-select">
        <?php foreach ($options as $key => $option) : ?>
            <option <?= isset($value) && explode("|", $key)[0]  == explode("|", $value)[0]  ? "selected = 'selected'" : "" ?> value="<?= $key  ?>"><?= $option[0] ?></option>
        <?php endforeach; ?>
    </select>
</div>