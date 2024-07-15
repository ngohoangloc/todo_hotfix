<?php

$key = isset($key) ? $key : time();
$title = isset($title) ? $title : "";
$required = isset($required) ? $required : false;

?>
<div class="input-group" <?= $required ? "required" : "" ?> data-title="<?= $title ?>" data-key="<?= $key ?>">
    <select name="<?= $key ?>" class="form-select">
        <option value="Nam" >Nam</option>
        <option value="Nữ" >Nữ</option>
        <option value="Khác">Khác</option>
    </select>
</div>