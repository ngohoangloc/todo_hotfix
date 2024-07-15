<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$data_id = isset($data_id) ? $data_id : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$width = isset($width) ? $width : "";
$group = isset($group) ? $group : (object)[];
?>

<?php

$options = [
    'hoanthanh|success' => ['Hoàn thành', 'success'],
    'danglam|warning' => ['Đang làm', 'warning'],
    'chuahoanthanh|danger' => ['Chưa hoàn thành', 'danger'],
    'chuabatdau|secondary' => ['Chưa bắt đầu', 'secondary'],
];

?>

<div class="input-group input-group-table">
    <select data-item-id="<?= $data_id ?>" data-id="<?= $meta_id ?>" data-type="status" data-group-id="<?= $group->id ?>" name="<?= $key ?>" style="width: <?= $width ?>" class="status_select form-select form-select-sm bg-gradient <?= $value != '' ? "text-light" : "" ?> bg-<?= explode("|", $value)[1] ?>" data-bg-color="bg-<?= explode("|", $value)[1] ?>">
        <?php foreach ($options as $key => $option) : ?>
            <option <?= explode("|", $key)[0]  == explode("|", $value)[0]  ? "selected = 'selected'" : "" ?> value="<?= $key  ?>|<?= $option[0]  ?>" class="bg-<?= $option[1] ?> p-1 text-white"><?= $option[0] ?></option>
        <?php endforeach; ?>
    </select>
</div>

<script>
    $(document).ready(function() {
        $('.status_select[data-item-id="<?= $data_id ?>"]').click(function(e) {
            var date = $(this).parents(".task-item").find('input[type="date"]').val();
            
            if (date === '') {

                toastr.warning("Vui lòng cập nhật ngày trước khi hoàn thành công việc !");

                $(this).parents(".task-item").find('input[type="date"]').focus();
                e.stopImmediatePropagation();
                
            }
        });
    });
</script>