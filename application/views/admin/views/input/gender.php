<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : 0;
$data_id = isset($data_id) ? $data_id : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$hover = isset($hover) ? $hover : false;
$width = isset($width) ? $width : "";
$placeholder = isset($placeholder) ? $placeholder : "";
$showBtnClear = isset($showBtnClear) ? $showBtnClear : false;
$disabled = isset($disabled) ? $disabled : null;
$showMenu = isset($showMenu) ? $showMenu : false;
?>
<style>
    .gender-input {
        display: block !important;
    }
</style>
<div class="input-group input-group-table gender-input">
    <?php

    ?>
    <select data-meta-id="<?= $meta_id ?>" data-id="<?= $meta_id ?>" data-type="gender" name="<?= $key ?>" value="<?= $value; ?>" class="form-control input-table text-truncate">
        <option value="Nam" <?php if ($value == 'Nam') echo "selected='selected'"; ?>>Nam</option>
        <option value="Nữ" <?php if ($value == 'Nữ') echo "selected='selected'"; ?>>Nữ</option>
        <option value="Khác" <?php if ($value == 'Khác') echo "selected='selected'"; ?>>Khác</option>
    </select>
</div>