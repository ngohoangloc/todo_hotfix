<div class="field-title" style="width: <?= $field->width ?>;" data-field-id="<?= $field->id; ?>">
    <?php
    $data['key'] = $field->key;
    $data['value'] = $field->title;
    $data['group_id'] = $group->id;
    $data['type'] = $field->type_html;
    $data['data_id'] = $field->id;
    $data['disabled'] = false;
    $data['showMenu'] = true;
    $data['showBtnClear'] = false;
    $data['placeholder'] = "";
    $data['width'] = $field->width;
    $this->load->view("admin/views/input/text", $data);
    ?>
</div>