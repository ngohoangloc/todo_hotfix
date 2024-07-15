<?php $field = $this->Items_model->get_field_by_key($meta->key); ?>

<div <?= $field->display == 0 ? "hidden" : "" ?> class="task-meta border-end" style="width: <?= $field->width ? $field->width : '' ?>" data-field-id="<?= $field->id ?>" data-meta-id="<?= $meta->id ?>">
    <?php
    $data['key'] = $meta->key;
    $data['value'] = $meta->value;
    $data['data_id'] = $meta->items_id;
    $data['meta_id'] = $meta->id;
    $data['showBtnClear'] = true;
    $data['showMenu'] = false;
    $data['placeholder'] = "";
    $data['width'] = $field->width;
    $data['project'] = $project;
    $data['group'] = $group;
    $this->load->view("admin/views/input/" . $field->type_html, $data);
    ?>
</div>