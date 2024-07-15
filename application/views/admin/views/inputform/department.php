<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$data_id = isset($data_id) ? $data_id : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$width = isset($width) ? $width : "";

$title = isset($title) ? $title : "";
$required = isset($required) ? $required : false;

$query = $this->db->select("*")->from("items")->where("parent_id", 1945)->get();
$donvis = $query->num_rows() > 0 ? $query->result_object() : [];

?>

<div class="input-group" <?= $required ? "required" : "" ?> data-title="<?= $title ?>" data-key="<?= $key ?>">
    <select data-id="<?= $meta_id ?>" name="<?= $key ?>" style="width: <?= $width ?>" class="form-select">
        <?php foreach ($donvis as $key => $dv) : ?>
            <option value="<?= $dv->id; ?>" <?php if ($dv->id == $value) echo "selected"; ?>><?= $dv->title; ?></option>
        <?php endforeach; ?>
    </select>
</div>