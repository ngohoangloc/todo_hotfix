<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$data_id = isset($data_id) ? $data_id : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$width = isset($width) ? $width : "";
$query = $this->db->select("*")->from("items")->where("parent_id", 1945)->get();
$donvis = $query->num_rows() > 0 ? $query->result_object() : [];
?>

<div class="input-group input-group-table">
    <select data-id="<?= $meta_id ?>" data-type="department" name="<?= $key ?>" style="width: <?= $width ?>" class="donvi_select form-select form-select-sm bg-gradient <?= $value != '' ? "text-light" : "" ?> bg-<?= explode("|", $value)[1] ?>">
        <?php foreach ($donvis as $key => $dv) : ?>
            <option value="<?= $dv->id; ?>" <?php if ($dv->id == $value) echo "selected"; ?>><?= $dv->title; ?></option>
        <?php endforeach; ?>
    </select>
</div>
<style>
    .select2-container--default .select2-selection--single {
        background-color: rgba(0, 0, 0, 0) !important;
        border: none !important;
        border-radius: 4px;
    }

    .select2.select2-container {
        max-width: 100% !important;
    }
</style>

<script>
    $(document).ready(function() {
        $('.donvi_select').select2();
    });
</script>
