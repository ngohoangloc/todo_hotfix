<?php
    $key = isset($key) ? $key : "";
    $value = isset($value) ? $value : "";
    $data_id = isset($data_id) ? $data_id : "";
    $meta_id = isset($meta_id) ? $meta_id : "";
    $width = isset($width) ? $width : "";
    $group = isset($group) ? $group : (object)[];

    $options = [
        'chuabatdau|secondary' => ['Chưa bắt đầu', 'secondary'],
        'chuahoanthanh|danger' => ['Chưa hoàn thành', 'danger'],
        'hoanthanh|success' => ['Hoàn thành', 'success'],
    ];
?>

<div class="input-group" data-title="<?= $title ?>" data-key="<?= $key ?>">
    <select data-item-id="<?= $data_id ?>" disabled data-id="<?= $meta_id ?>" data-type="confirm" data-group-id="<?= $group->id ?>" name="<?= $key ?>" style="width: <?= $width ?>" class="status_select form-select form-select-sm bg-gradient <?= $value != '' ? "text-light" : "" ?> bg-<?= explode("|", $value)[1] ?>" data-bg-color="bg-<?= explode("|", $value)[1] ?>">
        <?php foreach ($options as $key => $option) : ?>
            <option <?= explode("|", $key)[0]  == explode("|", $value)[0]  ? "selected = 'selected'" : "" ?> value="<?= $key  ?>|<?= $option[0]  ?>" class="bg-<?= $option[1] ?> p-1 text-white"><?= $option[0] ?></option>
        <?php endforeach; ?>
    </select>
</div>


<script>

</script>