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
$type = isset($type) ? $type : "";

?>

<div class="input-group input-group-table">
    <?php if ($showBtnClear) : ?>
        <span class="btn-clear"><i class="fa fa-times" aria-hidden="true"></i></span>
    <?php endif; ?>

    <input type="text" placeholder="<?= $placeholder ?>" data-meta-id="<?= $meta_id ?>" data-id="<?= $data_id ?>" <?php echo $disabled ? "disabled" : "" ?> name="<?= $key ?>" value="<?= $value; ?>" class="form-control input-table text-truncate" />

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
            <?php if ($type == 'confirm') : ?>
                <li class="px-2 dropdown-item btn_confirm_all" data-group-id="<?= $group_id ?>" style="font-size: 14px;"><span><i class="fa fa-check-square-o" aria-hidden="true"></i></span> Xác nhận tất cả</li>
            <?php endif; ?>
            <li class="btn-rename px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> Đổi tên</li>
            <li data-id="<?= $data_id ?>" class="btn-delete-field px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa </li>
        </ul>

    <?php endif; ?>

</div>