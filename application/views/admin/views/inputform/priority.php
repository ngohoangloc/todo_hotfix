<?php

$key = isset($key) ? $key : time();
$title = isset($title) ? $title : "";
$required = isset($required) ? $required : false;

?>
<div class="input-group" <?= $required ? "required" : "" ?> data-title="<?= $title ?>" data-key="<?= $key ?>">
    <select name="<?= $key ?>" id="" class="form-select">
        <option value="critical">Critical</option>
        <option value="high">High</option>
        <option value="medium">Medium</option>
        <option value="low">Low</option>
    </select>
</div>