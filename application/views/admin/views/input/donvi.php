<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$data_id = isset($data_id) ? $data_id : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$width = isset($width) ? $width : "";
?>

<div class="input-group input-group-table">
    <select data-id="<?= $meta_id ?>" name="<?= $key ?>" style="width: <?= $width ?>" class="donvi_select form-select form-select-sm bg-gradient <?= $value != '' ? "text-light" : "" ?> bg-<?= explode("|", $value)[1] ?>">
        <option value="dv1">Don vi 1</option>
        <option value="dv2">Don vi 2</option>
        <option value="dv3">Don vi 3</option>
        <option value="dv4">Don vi 4</option>
    </select>
</div>
<script>
    $(document).ready(function() {
        $('.donvi_select').select2();
    });
</script>