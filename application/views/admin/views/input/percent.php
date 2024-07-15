<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : 0;
$data_id = isset($data_id) ? $data_id : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$hover = isset($hover) ? $hover : false;
$width = isset($width) ? $width : "";
$placeholder = isset($placeholder) ? $placeholder : "";
$showBtnClear = isset($showBtnClear) ? $showBtnClear : false;
$disabled = isset($disabled) ? $disabled : null;
$showMenu = isset($showMenu) ? $showMenu : false;
?>
<style>
.progress
{
  width: 100%;
  border-radius: 10px !important;
}
.progress-input:hover .progress,.progress-input .input-table
{
  display: none;
}
.progress-input:hover .input-table
{
  display: block !important;
}
.progress
{
  display: flex;
  justify-content: left;
  align-items: center;
}
.input-group.input-group-table {
    align-items: center;
}
</style>
<div class="input-group input-group-table progress-input">
  <div class="progress">
    <div class="progress-bar" role="progressbar" style="width: <?= $value; ?>%" aria-valuenow="<?= $value; ?>" aria-valuemin="0" aria-valuemax="100"><?= $value; ?>%</div>
  </div>
  <input min="0" max="100" type="number" placeholder="<?= $placeholder ?>" data-meta-id="<?= $meta_id ?>" data-id="<?= $data_id ?>" name="<?= $key ?>" value="<?= $value; ?>" class="form-control input-table text-truncate" />
</div>
<script>
$( document ).ready(function() {
    $("body").on("change",".progress-input .input-table",function(){
            var el = $(this).prev().find(".progress-bar");
            var val = $(this).val();
            el.css("width",val+"%");
            el.attr("aria-valuenow",val);
            el.html(val+"%");
    });
});
</script>