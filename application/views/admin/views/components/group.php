<?php
$meta = isset($meta) ? $meta : "";
?>
<div class="group-list-item mb-3" <?= $group->display ? "" : "hidden" ?> data-group-id="<?= $group->id ?>">
    <div class="group-item-title mb-3">
        <div class="row me-0">
            <div class="menu_hover" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
                <div class="group-item-menu">
                    <i class="fa fa-ellipsis-h"></i>
                </div>
            </div>
            <?php $meta = $this->Items_model->get_meta($group->id, "linkzalo"); ?>

            <ul class="dropdown-menu menu_hover_list">
                <li class="btn-rename px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> Đổi tên nhóm</li>
                <li data-id="<?= $group->id ?>" class="btn-delete-group px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
                <li data-group="<?= $group->id ?>" class="btn-add-link-zalo px-2 dropdown-item" data-old-link="<?= $meta ? $meta->value : "" ?>" style="font-size: 14px;"><span><i class="fa fa-link" aria-hidden="true"></i></span>Thêm nhóm chat</li>
            </ul>

            <div class="col-8 col-md-7 d-flex align-items-center">
                <span class="btn-collapse" style="font-size: 14px;" data-group-id="<?= $group->id ?>" data-bs-toggle="collapse" href="#collapse-group-<?= $group->id ?>" aria-controls="collapse-group-<?= $group->id ?>">
                    <i class="fa fa-chevron-down text-primary" aria-hidden="true"></i>
                </span>

                <input type="text" name="group-name" data-id="<?= $group->id ?>" value="<?= $group->title; ?>" class="text-primary group-item-title-input" />
            </div>

            <div class="col-4 col-md-5 d-flex justify-content-end gap-2">

                <?php $group_owners = $this->Items_model->get_owners($group->id); ?>

                <div class="d-flex align-items-center gap-2">
                    <?php foreach ($group_owners as $key => $group_owner) {
                        if ($key == 3) {
                            break;
                        }; ?>
                        <div class="border" style="width: 35px; height: 35px; border-radius:50%; background-color: #B9B9B9; overflow: hidden;">
                            <img style="width: 100%; height: 100%; object-fit: cover;" src="<?= base_url($group_owner->avatar) ?>" alt="">
                        </div>
                    <?php }; ?>

                    <?php if (count($group_owners) > 3) : ?>
                        <div class="d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border-radius:50%; background-color: #B9B9B9;">
                            <strong>
                                <?= "+" . (count($group_owners) - 3) ?>
                            </strong>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="button" class="btn btn-sm btn-outline-secondary btn-add-member" data-bs-toggle="modal" data-bs-target="#add_member_to_group_modal" data-group-id="<?= $group->id ?>">
                    + Thành viên
                </button>

                <!-- <button type="button" class="btn btn-sm btn-outline-secondary btn-add-member" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddMember<?= $group->id ?>" aria-controls="offcanvasAddMember<?= $group->id ?>">
                    + Thiết lập
                </button> -->

                <!-- link zalo -->
                <a style="line-height: 26px;" class="btn btn-sm btn-outline-secondary" href="<?= isset($meta->value) ? $meta->value :  "" ?>" data-group-id="<?= $group->id ?>" data-meta-id="<?=  isset($meta->id) ? $meta->id :  "" ?>" target="_blank" <?= !empty($meta->value) ? "" : "hidden" ?>>
                    <span class="d-flex align-items-center gap-2"><i class="fa fa-link"></i>
                        <span>Nhóm zalo</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div id="collapse-group-<?= $group->id ?>" class="collapse show group-item">
        <!-- Task header -->
        <div class="d-flex task-item-header" style="">
            <div class="checkall_btn">
                <input type="checkbox" value="" />
            </div>
            <!-- Header title -->
            <div class="header-title" style="width: 380px;">
                <?php
                $data['key'] = "title";
                $data['value'] = "Công việc";
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
            <?php if ($is_owner) : ?>
                <div class="btn-add-fields" data-id="<?= $project->id; ?>">
                    <div class="dropdown">
                        <span class="dropdown-toggle" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus"></i>
                        </span>
                        <ul class="dropdown-menu" style="z-index: 1000; width: 500px;" aria-labelledby="dropdownMenuLink">
                            <?php $html_types = $this->Html_types_model->get_all(); ?>
                            <div class="row">
                                <?php foreach ($html_types as $html_type) : ?>
                                    <div class="col-6">
                                        <li class="fiels_list_item dropdown-item" data-group-id="<?= $group->id ?>" data-value="<?= $html_type->value ?>" data-type-html="<?= $html_type->title ?>">
                                            <div class="fiels_list_item_image rounded">
                                                <img src="<?= base_url($html_type->icon); ?>" class="fiels_list_item_icon bg-<?= $html_type->color ?>" width="35px" height="35px" />
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
        <div class="sortable">
            <?php foreach ($tasks as $task) : ?>
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
                            $data = [
                                'task' => $task,
                                'is_owner' => $is_owner,
                            ];
                            $this->load->view('admin/views/components/chat', $data);
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
                                                    <li class="fiels_list_item dropdown-item" data-group-id="<?= $group->id ?>" data-value="<?= $html_type->value ?>" data-type-html="<?= $html_type->title ?>">
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
            <?php endforeach; ?>
        </div>
        <!-- Add task btn -->
        <div class="d-flex border-start border-end border-bottom group-item-footer">
            <div class="p-1" style="width: calc(380px + 35px);">
                <input type="text" data-group-id="<?= $group->id ?>" data-type-id="8" class="w-100 p-1 input-add-item" placeholder="+ Thêm công việc" style="font-size: 13px;">
            </div>
        </div>
        <!-- End add task btn -->
        <div class="d-flex">
            <div style="width: calc(380px + 35px);"></div>
            <?php

            $status = [
                ['hoanthanh', 'success'],
                ['danglam', 'warning'],
                ['chuabatdau', 'secondary'],
                ['chuahoanthanh', 'danger']
            ];

            $fields = $this->Items_model->get_fields($project->id);

            ?>

            <?php foreach ($fields as $field) : ?>
                <div class="task-meta" style="width: <?= $field->width ? $field->width : '' ?>;" data-field-id="<?= $field->id ?>">
                    <?php if ($field->type_html == "status") : ?>
                        <div class="progress-group rounded-2 overflow-hidden d-flex" data-group-id="<?= $group->id ?>">

                        </div>
                    <?php else : ?>
                        <div style="width: <?= $field->width; ?>"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>