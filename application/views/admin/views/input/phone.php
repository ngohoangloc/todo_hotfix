<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
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
    .gender-input {
        display: block !important;
    }

    .error {
        font-size: 0.8em;
        color: red;
    }

    .input-group .form-control,
    .phone-input {
        max-height: 40px;
    }
</style>
<div class="input-group input-group-table phone-input">
    <input minlength="5" maxlength="30" type="text" placeholder="<?= $placeholder ?>" data-meta-id="<?= $meta_id ?>" data-id="<?= $data_id ?>" name="<?= $key ?>" value="<?= $value; ?>" class="form-control input-table text-truncate" />
    <span class="error danger"></span>
</div>

<script>
    $(document).ready(function() {
        const validatePhone = (phone) => {
            return String(phone)
                .toLowerCase()
                .match(
                    /^\+?([0-9]{1,10})$/
                );
        };

        $("body").on("change", ".phone-input .form-control", function(e) {
            e.preventDefault();
            var phone = $(this).val();
            if (phone != '') {
                if (!validatePhone(phone)) {
                    $(this).parent().addClass('border border-danger');
                    $(this).next().html("Điện thoại chưa đúng định dạng");
                    e.stopImmediatePropagation();
                } else {
                    $(this).parent().removeClass('border border-danger');
                    $(this).next().html("");

                }
            } else {
                $(this).parent().removeClass('border border-danger');
                $(this).next().html("");

            }
        });

    });
</script>