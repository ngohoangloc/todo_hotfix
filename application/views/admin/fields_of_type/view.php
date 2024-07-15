<?php
$types_html = $this->Html_types_model->get_all();

?>

<div class="row">
    <form action="<?= base_url('fields_of_type/update/' . $field_of_type->id) ?>" method="post">
        <p class="text-center">Update Field Of Type</p>
        <div class="col-6 m-auto">
            <div class="form-group mt-3">
                <label for="" class="form-label">Type html</label>
                <select name="type_html" id="" class="form-select">
                    <?php foreach ($types_html as $type) : ?>

                        <option <?= $field_of_type->type_html == $type->title ? "selected = 'selected'" : "" ?> value="<?= $type->title ?>"><?= $type->title ?></option>

                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="" class="form-label">Title</label>
                <input type="text" name="title" value="<?= $field_of_type->title ?>" class="form-control">
            </div>
            <div class="form-group mt-3">
                <label for="" class="form-label">Type</label>
                <select name="type_id" id="" class="form-select">
                    <?php foreach ($types as $type) : ?>

                        <option <?= $field_of_type->type_id == $type->id ? "selected = 'selected'" : "" ?> value="<?= $type->id ?>"><?= $type->title ?></option>

                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </div>
    </form>
</div>