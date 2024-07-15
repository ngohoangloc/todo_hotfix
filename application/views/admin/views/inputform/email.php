<?php

$key = isset($key) ? $key : time();

$title = isset($title) ? $title : "";
$required = isset($required) ? $required : false;

?>

<div class="input-group" <?= $required ? "required" : "" ?> data-title="<?= $title ?>" data-key="<?= $key ?>">
    <input name="<?= $key ?>" type="email" class="form-control">
</div>