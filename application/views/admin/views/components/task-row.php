<div class="sort-item" data-item-id="<?= $task->id ?>" data-position="<?= $task->position ?>" data-group-id="<?= $group->id ?>">
    <div class="task-item d-flex" data-item-id="<?= $task->id ?>">
        <div class="menu_hover" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
            <div class="task-item-menu">
                <i class="fa fa-ellipsis-h"></i>
            </div>
        </div>
        <ul class="dropdown-menu menu_hover_list">
            <li data-id="<?= $task->id ?>" class="btn-delete-task px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
        </ul>
        <div class="stt" data-id="<?= $task->id; ?>">
            <input type="checkbox" value="" />
        </div>

        <?php if (isset($type_id) && $type_id == 35) : ?>
            <div class="task-id border-end" style="width: 80px;" data-bs-title="<?= $task->title ?>" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="top">
                <?php
                $data['value'] = $task->id;
                $data['data_id'] = $task->id;
                $data['disabled'] = false;
                $data['showMenu'] = false;
                $data['width'] = "50px;";
                $this->load->view("admin/views/input/text", $data);
                ?>
            </div>
        <?php endif; ?>

        <!-- Task title -->
        <div class="task-title border-end" style="width: 380px;" data-bs-title="<?= $task->title ?>" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="top">
            <span class="btn-collapse" style="font-size: 14px;" data-task-id="<?= $task->id ?>" data-bs-toggle="collapse" href="#collapse-task-<?= $task->id ?>" aria-controls="collapse-task-<?= $task->id ?>">
                <i class="fa fa-chevron-right text-primary" aria-hidden="true"></i>
            </span>
            <?php
            $data['value'] = $task->title;
            $data['data_id'] = $task->id;
            $data['disabled'] = false;
            $data['showMenu'] = false;
            $data['width'] = "150px;";
            $this->load->view("admin/views/input/text", $data);
            ?>
            <?php
            $chat = [
                'task' => $task,
            ];
            $this->load->view('admin/views/components/chat', $chat);
            ?>
        </div>

        <!-- Item metas -->
        <?php foreach ($this->Items_model->get_all_meta($task->id) as $meta) : ?>
            <?php $field = $this->Items_model->get_field_by_key($meta->key); ?>
            <div <?= $field->display == 0 ? "hidden" : "" ?> class="task-meta border-end" style="width: <?= $field->width ? $field->width : '' ?>" data-field-id="<?= $field->id ?>" data-meta-id="<?= $meta->id ?>">
                <?php
                $data['key'] = $meta->key;
                $data['value'] = $meta->value;
                $data['data_id'] = $task->id;
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

    <!-- subitem of task -->
    <div class="collapse subitem pb-3 ps-4 pt-3" id="collapse-task-<?= $task->id ?>">
        <!-- Task header -->
        <div class="d-flex task-item-header">
            <div class="checkall_subitem" data-id="<?= $group->id; ?>">
                <input type="checkbox" value="" />
            </div>
            <!-- Header title -->
            <div class="header-title" style="width: calc(380px - 28px);">
                <?php
                $data['key'] = "title";
                $data['value'] = "Công việc phụ";
                $data['disabled'] = true;
                $data['width'] = "150px;";
                $data['showBtnClear'] = false;
                $data['showMenu'] = false;
                $this->load->view("admin/views/input/text", $data);
                ?>
            </div>
            <!-- Load fields of project -->
            <?php foreach ($this->Items_model->get_fields($project->id) as $field) : ?>
                <div <?= $field->display == 0 ? "hidden" : "" ?> class="field-title" style="width: <?= $field->width ?>;" data-field-id="<?= $field->id; ?>">
                    <?php
                    $data['key'] = $field->key;
                    $data['value'] = $field->title;
                    $data['data_id'] = $field->id;
                    $data['disabled'] = false;
                    $data['showMenu'] = true;
                    $data['showBtnClear'] = false;
                    $data['placeholder'] = "";
                    $data['width'] = $field->width;
                    $this->load->view("admin/views/input/text", $data);
                    ?>
                </div>
            <?php endforeach; ?>
            <!-- Btn add field -->
            <div class="btn-add-fields" data-id="<?= $project->id; ?>">
                <div class="dropdown">
                    <span class="dropdown-toggle" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-plus"></i>
                    </span>
                    <ul class="dropdown-menu" style="z-index: 1000; width: 400px;" aria-labelledby="dropdownMenuLink">
                        <?php $html_types = $this->Html_types_model->get_all(); ?>
                        <div class="row">
                            <?php foreach ($html_types as $html_type) : ?>
                                <div class="col-6">
                                    <li class="fiels_list_item dropdown-item" data-value="<?= $html_type->value ?>" data-type-html="<?= $html_type->title ?>">
                                        <div class="fiels_list_item_image rounded">
                                            <img src="<?= base_url($html_type->icon); ?>" class="fiels_list_item_icon bg-<?= $html_type->color ?>" />
                                        </div>
                                        <span><?= $html_type->value; ?></span>
                                    </li>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <!-- task of group -->
        <?php foreach ($this->Items_model->get_child_items($task->id) as $subitem) : ?>
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
        <?php endforeach; ?>
        <!-- Add task btn -->
        <div class="d-flex">
            <div class="p-1">
                <input type="text" data-group-id="<?= $task->id ?>" data-type-id="28" class="p-1 input-add-item" placeholder="+ Thêm công việc phụ" style="font-size: 13px;">
            </div>
        </div>
        <!-- End add task btn -->
    </div>
</div>