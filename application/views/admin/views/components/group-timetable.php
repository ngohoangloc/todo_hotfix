<div class="group-list-item my-4" data-group-id="<?= $group->id ?>">
    <div class="group-item-title mb-3">
        <div class="row">
            <div class="menu_hover" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
                <div class="group-item-menu">
                    <i class="fa fa-ellipsis-h"></i>
                </div>
            </div>
            <ul class="dropdown-menu menu_hover_list">
                <li class="btn-rename px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> Đổi tên nhóm</li>
                <li data-id="<?= $group->id ?>" class="btn-delete-group px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
            </ul>
            <div class="col-8 col-md-10 d-flex align-items-center">
                <span class="btn-collapse" style="font-size: 14px;" data-group-id="<?= $group->id ?>" data-bs-toggle="collapse" href="#collapse-group-<?= $group->id ?>" aria-controls="collapse-group-<?= $group->id ?>">
                    <i class="fa fa-chevron-down text-primary" aria-hidden="true"></i>
                </span>
                <input type="text" name="group-name" data-id="<?= $group->id ?>" value="<?= $group->title ?>" class="text-primary group-item-title-input" />
            </div>

            <div class="col-4 col-md-2 d-flex justify-content-end" style="display: inline; text-align: end;">
                <label for="input-file-<?= $group->id ?>" type="button" class="btn btn-sm btn-success btn-add-member">
                    <i class="fa fa-download" aria-hidden="true"></i> Nhập dữ liệu
                </label>
                <input class="input-file" id="input-file-<?= $group->id ?>" data-group-id="<?= $group->id ?>" data-project-id="<?= $project->id ?>" type="file" hidden>
            </div>
        </div>
    </div>
    <div id="collapse-group-<?= $group->id ?>" class="collapse show group-item" data-group-id="<?= $group->id ?>">
        <!-- Task header -->
        <div class="d-flex task-item-header" style="margin: 0 25px;">
            <div class="checkall_btn">
                <input type="checkbox" value="" />
            </div>
            <!-- Header title -->
            <div class="header-title" style="width: 200px;">
                <?php
                $data['key'] = "title";
                $data['value'] = "MaGV";
                $data['disabled'] = true;
                $data['width'] = "150px;";
                $data['showBtnClear'] = false;
                $data['showMenu'] = false;
                $this->load->view("admin/views/input/text", $data);
                ?>
            </div>
            <!-- Load fields of project -->
            <?php foreach ($this->Items_model->get_fields($project->id) as $field) : ?>
                <div class="field-title" style="width: <?= $field->width ?>;" data-field-id="<?= $field->id; ?>">
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
            <?php if ($is_owner) : ?>
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
            <?php endif; ?>
        </div>
        <!-- task of group -->
        <?php $tasks = $this->Items_model->get_child_items($group->id); ?>
        <div class="sortable" style="overflow-y: auto; max-height: 490px;">
            <?php foreach ($tasks as $task) : ?>
                <div class="sort-item" data-item-id="<?= $task->id ?>" data-position="<?= $task->position ?>" data-group-id="<?= $group->id ?>">
                    <div class="task-item d-flex">
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

                        <!-- Task title -->
                        <div class="task-title border-end" style="width: 200px;" data-bs-title="<?= $task->title ?>" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="top">
                            <?php
                            $data['value'] = $task->title;
                            $data['data_id'] = $task->id;
                            $data['disabled'] = false;
                            $data['showMenu'] = false;
                            $data['width'] = "150px;";
                            $this->load->view("admin/views/input/text", $data);
                            ?>
                        </div>

                        <!-- Item metas -->
                        <?php foreach ($this->Items_model->get_all_meta($task->id) as $meta) : ?>
                            <?php $field = $this->Items_model->get_field_by_key($meta->key); ?>
                            <div class="task-meta border-end" style="width: <?= $field->width ? $field->width : '' ?>" data-field-id="<?= $field->id ?>">
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
                </div>
            <?php endforeach; ?>
        </div>
        <!-- End add timetable btn -->
    </div>
</div>