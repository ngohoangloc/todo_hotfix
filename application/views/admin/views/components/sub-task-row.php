<div class="task-item d-flex" data-item-id="<?= $subitem->id ?>" data-parent-id="<?= $subitem->parent_id ?>">
    <div class="menu_hover" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
        <div class="task-item-menu">
            <i class="fa fa-ellipsis-h"></i>
        </div>
    </div>
    <ul class="dropdown-menu menu_hover_list">
        <li data-id="<?= $subitem->id ?>" class="btn-delete-task px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
    </ul>

    <div class="stt_subitem" data-id="<?= $subitem->id; ?>">
        <input type="checkbox" value="" />
    </div>
    <!-- Task title -->
    <div class="task-title border-end" style="width: calc(380px - 28px);">
        <?php
        $data['value'] = $subitem->title;
        $data['data_id'] = $subitem->id;
        $data['disabled'] = false;
        $data['showMenu'] = false;
        $this->load->view("admin/views/input/text", $data);
        ?>
    </div>
    <!-- Item metas -->
    <?php foreach ($this->Items_model->get_all_meta($subitem->id) as $meta) : ?>
        <?php $field = $this->Items_model->get_field_by_key($meta->key); ?>
        <div <?= $field->display == 0 ? "hidden" : "" ?> class="task-meta border-end" style="width: <?= $field->width ? $field->width : '' ?>" data-field-id="<?= $field->id ?>" data-meta-id="<?= $meta->id ?>">
            <?php
            $data['key'] = $meta->key;
            $data['value'] = $meta->value;
            $data['data_id'] = $subitem->id;
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
    <?php endforeach; ?>
    <!-- End Item meta -->
</div>