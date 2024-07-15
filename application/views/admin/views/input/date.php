<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$data_id = isset($data_id) ? $data_id : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$width = isset($width) ? $width : "";
$showBtnClear = isset($showBtnClear) ? $showBtnClear : false;
$group = isset($group) ? $group : (object)[];

?>

<?php

if (str_contains($value, "/")) {
    $value = implode("-", array_reverse(explode("/", $value)));
}

?>

<div class="input-group input-group-table" data-group-id="<?= isset($group->id) ? $group->id : "" ?>">
    <input type="date" style="width: <?= $width ?>" data-meta-id="<?= $meta_id ?>" name="<?= $key ?>" value="<?= $value ?>" class="form-control input-table" />
</div>