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
    .field-link .form-control {
        width: calc(100% - 30px) !important;

    }

    .field-link .btn-addon {
        padding: 5px;
        color: #fff !important;
        background-color: #0d6efd;
    }

    .field-link .btn-addon a {
        color: #fff !important;
    }

    .error {
        font-size: 0.8em;
        color: red;
    }
</style>
<div class="input-group input-group-table field-link">
    <?php if ($showBtnClear) : ?>
        <span class="btn-clear"><i class="fa fa-times" aria-hidden="true"></i></span>

    <?php endif; ?>

    <input type="text" placeholder="<?= $placeholder ?>" data-meta-id="<?= $meta_id ?>" data-id="<?= $data_id ?>" <?php echo $disabled ? "disabled" : "" ?> name="<?= $key ?>" value="<?= $value; ?>" class="form-control input-table text-truncate" />
    <span class="btn-addon bg-blue">
        <a href="<?= $value; ?>" target="_blank"><i class="fa fa-link"></i></a>
    </span>
    <span class="error danger"></span>
    <?php if (!$value) : ?>

        <div class="hover">
            <span>
                <i class="fa fa-text-width" aria-hidden="true"></i>
            </span>

        </div>

    <?php endif; ?>

    <?php if ($showMenu) : ?>
        <div class="menu_hover" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
            <div class="btn-group">
                <i class="fa fa-ellipsis-h"></i>
            </div>
        </div>
        <ul class="dropdown-menu menu_hover_list">
            <li class="btn-rename px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> Đổi tên</li>
            <li data-id="<?= $data_id ?>" class="btn-delete-field px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
        </ul>
    <?php endif; ?>

</div>
<script>
    $(document).ready(function() {
        const validateUrl = (email) => {
            return String(email)
                .toLowerCase()
                .match(
                    /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i
                );
        };
        $(".field-link .form-control").change(function(e) {
            e.preventDefault();
            var url = $(this).val();
            if (url != '') {
                if (!validateUrl(url)) {
                    $(this).parent().addClass('border border-danger');
                    $(this).next().next().html("Link chưa đúng định dạng");
                    e.stopImmediatePropagation();
                } else {
                    $(this).parent().removeClass('border border-danger');
                    $(this).next().next().html("");

                }
                $(this).next().find('a').attr("href", url);
            } else {
                $(this).parent().removeClass('border border-danger');
                $(this).next().next().html("");
            }
        });
    });
</script>