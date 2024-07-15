<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$data_id = isset($data_id) ? $data_id : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$hover = isset($hover) ? $hover : false;
$style = isset($style) ? $style : "";
$showBtnClear = isset($showBtnClear) ? $showBtnClear : false;
?>

<div class="input-group input-group-table">
    <input type="number" style="<?= $style ?>" placeholder="<?= $placeholder ?>" data-meta-id="<?= $meta_id ?>" <?php echo $key == 'title' ? "disabled" : "" ?> name="<?= $key ?>" value="<?= $value; ?>" class="form-control input-table" />
    <div class="hover">
        <span>
            <i class="fa fa-plus"></i>
        </span>
    </div>
</div>