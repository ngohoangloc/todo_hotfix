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
    .form-switch .form-check-input {
        background-repeat: no-repeat !important;
        background-color: #ddd;
    }

    .form-switch .form-check-input:checked {
        background-color: #0d6efd !important;
    }
    .input-group-table.check-input:hover .form-switch,.input-group-table.check-input .form-control
    {
        display: none;
    }
    .input-group-table.check-input:hover .form-control
    {
        display: block;
    }
    
</style>
<div class="input-group input-group-table check-input">
    <?php $selected = $value > 0 ? "checked='checked'" : '';?>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?= $selected;?>>
        <label class="form-check-label" for="flexSwitchCheckDefault"><?= $value; ?> Ngày</label>
    </div>
    <input min="0" max="100" type="number" <?php if ($disabled) echo "disabled='$disabled'"; ?> placeholder="<?= $placeholder ?>" data-meta-id="<?= $meta_id ?>" data-id="<?= $data_id ?>" name="<?= $key ?>" value="<?= $value; ?>" class="form-control input-table text-truncate" />
</div>
<script>
    $(document).ready(function() {
        $("body").on("change", ".form-check-input", function() {
           
        });
        $("body").on("change", ".check-input .input-table", function() {
             
                var val = $(this).val();
                if(val > 0)
                {
                    $(this).parent().find('.form-check-input').attr("checked","checked");
                    $(this).parent().find('.form-check-label').html(val+" ngày");
                }
        });
    });
</script>