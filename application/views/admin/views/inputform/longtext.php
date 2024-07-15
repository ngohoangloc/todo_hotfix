<?php

$key = isset($key) ? $key : "";
$title = isset($title) ? $title : "";
$required = isset($required) ? $required : false;

?>


<div class="input-group" <?= $required ? "required" : "" ?> data-title="<?= $title ?>" data-key="<?= $key ?>">
    <textarea name="<?= $key ?>" class="form-control" rows="5"></textarea>
</div>