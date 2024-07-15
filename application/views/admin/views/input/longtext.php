<?php

$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$data_id = isset($data_id) ? $data_id : "";
$meta_id = isset($meta_id) ? $meta_id : "";

?>


<div class="input-group input-group-table">
    <textarea name="<?= $key ?>" data-id="<?= $meta_id ?>" class="long-text-input form-control form-control-sm"><?= $value ?></textarea>
</div>