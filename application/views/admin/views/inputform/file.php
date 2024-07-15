<?php

$key = isset($key) ? $key : time();
$title = isset($title) ? $title : "";
$required = isset($required) ? $required : false;

?>
<div class="input-group" <?= $required ? "required" : "" ?> data-title="<?= $title ?>" data-key="<?= $key ?>">
    <input type="text" name="<?= $key  ?>" data-key="files" value="" hidden>
    <input type="file" name="file_upload" class="form-control" multiple>
</div>