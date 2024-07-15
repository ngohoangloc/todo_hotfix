<?php

$folder_id_url = $this->uri->segment(3);
$project_id_url = $this->uri->segment(4);
?>

<div class="row pt-3" style="padding-left: 37px !important;">
    <?php if (isset($project)) : ?>

        <div class="col-12 col-lg-8">
            <span class="project-id fs-4"> ID: <?= $project->id;  ?></span>
            <input class="ms-2 project-title fs-4" data-project-id="<?= $project->id; ?>" value="<?= $project->title; ?>" />
        </div>

        <div class="col-12 col-lg-4 d-flex justify-content-end align-items-center gap-3 mt-2">
            <div class="form-switch d-flex align-items-center gap-2">
                <input id="checkbox" class="form-check-input project-scope" data-project-id="<?= $project->id; ?>" type="checkbox" value="" <?= $project->is_private == 1 ? 'checked' : '';  ?> />
                <span>Ẩn</span>
            </div>
            <?php $this->load->view("templates/admin/invite", ['item_id' => $project->id, 'is_owner' => $is_owner]) ?>
            <?php $this->load->view("templates/admin/logs", ['project_id' => $project->id]) ?>
        </div>


        <div class="col-12 my-3">
            <!-- Board Toolbar -->
            <?php $this->load->view("templates/admin/board-toolbar", ['project_id' => $project->id, 'folder_id_url' => $folder_id_url]) ?>
            <!-- End Board Toolbar -->

            <div class="form-actions">
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle rounded-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Thêm mới
                        <i class="fa fa-caret-down"></i>
                    </button>

                    <ul class="dropdown-menu" style="width: 230px;">
                        <li class="btn-export-sample-file" style="display: flex; align-items: center; gap: 5px;">
                            <img width="20" src="<?= base_url("assets/images/download.svg") ?>" alt="">
                            <span>Xuất file mẫu</span>
                        </li>
                        <li style="display: flex; align-items: center; gap: 5px;" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                            <img width="20" src="<?= base_url("assets/images/excel.svg") ?>" alt="">
                            <span>Import Excel</span>
                        </li>
                        <li style="display: flex; align-items: center; gap: 5px;">
                            <img width="20" src="<?= base_url("assets/images/group.svg") ?>" alt="">
                            <span>Thêm nhóm mới</span>
                        </li>
                    </ul>
                </div>

                <!-- Search task form -->
                <div class="add-task-actions">
                    <div class="input-search-task">
                        <div class="search-icon"><i class="fa fa-search" aria-hidden="true"></i></div>
                        <input type="text" placeholder="Tìm kiếm...">
                    </div>
                </div>
                <!-- End Search task form -->

                <div>
                    <!-- Filter columns -->
                    <div class="form-actions-item btn-filter-column">
                        <i class="fa fa-filter" aria-hidden="true"></i>
                        <span>Lọc</span>
                    </div>
                    <!-- End Filter columns -->

                    <div hidden>
                        <div class="filter-column-body">
                            <div class="row py-2">
                                <div class="col-12 text-end">
                                    <span class="btn-cancel-filter btn btn-sm btn-secondary">Xóa tất cả</span>
                                    <span class="btn-save-filter btn btn-sm btn-success">Lưu bộ lọc</span>
                                </div>

                                <form id="form-filter">
                                    <div class="group-filter-column">

                                        <?php
                                        $meta_filters = json_decode($this->Items_model->get_meta($project->id, "filter")->value);

                                        $condition_arr = [
                                            [
                                                "value" => "1",
                                                "icon" => "=",
                                            ],
                                            [
                                                "value" => "2",
                                                "icon" => ">=",
                                            ],
                                            [
                                                "value" => "3",
                                                "icon" => "<=",
                                            ],
                                            [
                                                "value" => "4",
                                                "icon" => "!=",
                                            ],
                                            [
                                                "value" => "5",
                                                "icon" => ">",
                                            ],
                                            [
                                                "value" => "6",
                                                "icon" => "<",
                                            ],
                                        ]

                                        ?>

                                        <?php if (isset($meta_filters)) : ?>
                                            <?php foreach ($meta_filters as $key => $meta_filter) : ?>
                                                <div class="group-filter-column-item row mb-2">
                                                    <div class="col-5">
                                                        <label class="form-label">Cột</label>

                                                        <div class="row">
                                                            <div class="col-4">
                                                                <select name="select-filter-logic" class="form-select">
                                                                    <option <?= $meta_filter->logic == "AND" ? "selected" : "" ?> value="AND">And</option>
                                                                    <option <?= $meta_filter->logic == "OR" ? "selected" : "" ?> value="OR">Or</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-8">
                                                                <select name="select-filter-fields" class="form-select">
                                                                    <!-- Load fields -->
                                                                    <option value="" disabled selected> --- Chọn cột --- </option>
                                                                    <?php foreach ($this->Items_model->get_fields($project->id) as $field) : ?>
                                                                        <option <?= $meta_filter->key == $field->key ? "selected" : "" ?> value="<?= $field->key; ?>" data-field-type="<?= $field->type_html; ?>"><?= $field->title ?></option>
                                                                    <?php endforeach; ?>
                                                                    <!-- End Load fields -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-2">
                                                        <label class="form-label">Điều kiện</label>
                                                        <select name="select-filter-condition" class="form-select">
                                                            <?php foreach ($condition_arr as $condition_item) : ?>
                                                                <option <?= $meta_filter->condition == $condition_item['icon'] ? "selected" : "" ?> value="1"> <?= "=" ?> </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-5">
                                                        <label class="form-label">Giá trị</label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <input type="text" class="input-filter-value input-text-filter-values form-control" hidden>

                                                            <select name="select-filter-values" class="input-filter-value form-select">
                                                                <option value="" disabled selected> --- Chọn giá trị --- </option>

                                                                <?php foreach ($this->Fields_model->get_meta_by_key_distint($meta_filter->key, $meta_filter->type) as $meta) : ?>

                                                                    <?php if ($meta_filter->type == 'status') : ?>
                                                                        <?php if (explode("|", $meta->value)[2]) : ?>
                                                                            <option <?= $meta_filter->value == $meta->value ? "selected" : "" ?> value="<?= $meta->value ?>"> <?= explode("|", $meta->value)[2]; ?> </option>
                                                                        <?php endif; ?>
                                                                    <?php elseif ($meta_filter->type == 'people') : ?>
                                                                        <option <?= $meta_filter->value == $meta->value ? "selected" : "" ?> value="<?= $meta->user_id ?>"> <?= $meta->value; ?> </option>
                                                                    <?php else : ?>
                                                                        <option <?= $meta_filter->value == $meta->value ? "selected" : "" ?> value="<?= $meta->value ?>"> <?= $meta->value; ?> </option>
                                                                    <?php endif; ?>

                                                                <?php endforeach; ?>
                                                            </select>

                                                            <?php if ($key != 0) : ?>
                                                                <span class="btn-remove-filter-column" style="cursor: pointer;">
                                                                    <i class="fa fa-times"></i>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </form>

                                <div class="col-12 mt-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="btn-add-filter-column text-secondary" style="cursor: pointer;"><i class="fa fa-plus"></i> Thêm</span>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex justify-content-end gap-2 align-items-center">
                                                <span class="total_filter">0 kết quả</span>
                                                <span class="btn-filter btn btn-sm btn-primary">Lọc</span>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div>
                    <!-- Hidden columns -->
                    <div class="form-actions-item btn-hidden-column">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                        <span>Ẩn cột</span>
                    </div>
                    <!-- End Hidden columns -->

                    <div hidden>
                        <div class="hidden-column-body">
                            <div class="hidden-column-list">
                                <input class="hidden-column-list-checkall" type="checkbox">
                                <span>Chọn tất cả</span>

                                <span>-</span>
                                <span class="hidden-column-number-selected text-secondary">0 đã chọn</span>
                                <hr>
                                <?php foreach ($this->Items_model->get_fields($project->id) as $field) : ?>
                                    <div class="hidden-column-list-item">
                                        <input <?= $field->display == 0 ? "checked" : "";  ?> class="hidden-column-list-checkbox" type="checkbox" data-field-id="<?= $field->id ?>">
                                        <span class="ms-2"><?= $field->title ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="group-list p-0">

            <!-- Group -->
            <div class="group-list-item mb-3">
                <div class="group-item">
                    <!-- Task header -->
                    <div class="d-flex task-item-header" style="">
                        <div class="checkall_btn">
                            <input type="checkbox" value="" />
                        </div>
                        <div class="header-id" style="width: 80px;">
                            <div class="input-group input-group-table">
                                <input type="text" placeholder="" value="ID" disabled class="form-control input-table text-truncate" />
                            </div>
                        </div>
                        <!-- Header title -->
                        <div class="header-title" style="width: 380px;">
                            <?php
                            $data['key'] = "title";
                            $data['value'] = "Tên";
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
                                                    <li class="fiels_list_item dropdown-item" data-group-id="<?= $project->id; ?>" data-value="<?= $html_type->value ?>" data-type-html="<?= $html_type->title ?>">
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
                    <?php $tasks = $this->Items_model->get_child_items($project->id); ?>


                    <div class="sortable">
                        <?php foreach ($tasks as $task) : ?>
                            <?php if ($task->type_id == 35) : ?>
                                <div class="sort-item" data-item-id="<?= $task->id ?>" data-position="<?= $task->position ?>">
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

                                        <!-- Task title -->
                                        <div class="task-title border-end" style="width: 380px;" data-bs-title="<?= $task->title ?>" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="top">
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
                                                $data['group'] = $project;
                                                $this->load->view("admin/views/input/" . $field->type_html, $data);
                                                ?>
                                            </div>
                                        <?php endforeach; ?>
                                        <!-- End Item meta -->
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </div>
                    <!-- Add task btn -->
                    <div class="d-flex border-start border-end border-bottom group-item-footer">
                        <div class="p-1" style="width: calc(380px + 35px);">
                            <input type="text" data-type-id="35" class="w-100 p-1 input-add-item" placeholder="+ Thêm hàng mới" style="font-size: 13px;">
                        </div>
                    </div>
                    <!-- End add task btn -->
                </div>
            </div>

        </div>
        <!-- Batch-actions -->
        <div class="batch-actions shadow">
            <div class="batch-actions-number">
                <span>1</span>
            </div>
            <ul class="batch-actions-list">
                <li class="batch-actions-item btn-actions-export">
                    <span class="batch-actions-item-icon"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span>
                    <span>Xuất</span>
                </li>
                <li class="batch-actions-item btn-actions-delete">
                    <span class="batch-actions-item-icon"><i class="fa fa-trash" aria-hidden="true"></i></span>
                    <span>Xóa</span>
                </li>
            </ul>
            <div class="batch-actions-close-btn">
                <span class="batch-actions-item-icon"><i class="fa fa-times" aria-hidden="true"></i></span>
            </div>
        </div>

    <?php else : ?>
        <div class="text-center">
            <img width="200" src="https://cdn.monday.com/images/recycle_bin/empty_state_deleted_3.svg" alt="">
            <div>Dự án đã xóa</div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Import excel-->
<div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportExcelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalImportExcelLabel">Nhập dữ liệu</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên dự án</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $project->id ?></td>
                            <td><?= $project->title ?></td>
                            <td class="text-center">
                                <label for="input-file-import-<?= $project->id ?>" class="btn-import-data-project btn btn-sm btn-success rounded-0 text-light">
                                    <img width="15" src="<?= base_url("assets/images/excel.svg") ?>" alt="">
                                    Nhập</label>
                                <input class="input-file-import" hidden id="input-file-import-<?= $project->id ?>" data-project-id="<?= $project->id ?>" type="file">
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal create project -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url("admin/items/add") ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input class="form-control" hidden type="text" name="type_id" />
                    <input class="form-control" type="text" hidden name="user_id" value="<?= $this->session->userdata('user_id') ?>" />
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Title</label>
                        <input class="form-control" type="text" name="title" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal show image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title file-name"></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-8 border-end">
                        <div class="image-preview-modal">
                            <img src="" class="image-preview" alt="image-preview">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="image-info">
                            <h6>Thông tin</h6>
                            <div class="mt-2 form-group mb-3">
                                <input type="text" class="file_info_description form-control" placeholder="Thêm mô tả...">
                            </div>
                            <div class="form-group mb-3">
                                <ul class="m-0 p-0" class="file_info_list">
                                    <li class="py-2 file_info_item">
                                        <span class="fw-bold">File name: </span>
                                        <input class="file_info_name form-control" />
                                    </li>
                                    <li class="py-2 file_info_item">
                                        <span class="fw-bold">File type: </span>
                                        <span class="file_info_type"></span>
                                    </li>
                                    <li class="py-2 file_info_item">
                                        <span class="fw-bold">Upload date: </span>
                                        <span class="file_info_upload_date"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="btn-download-file-modal form-group">
                                <i class="fa fa-download" aria-hidden="true"></i> Tải xuống
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal add member to group -->
<div class="modal fade" id="add_member_to_group_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height: 500px;">

                <input id="search-department" type="text" class="form-control mb-2" placeholder="Tìm kiếm phòng ban">

                <input id="search-users-to-invite-group" type="text" class="form-control" placeholder="Tìm kiếm username hoặc email">

                <div class="menu-result">
                    <div class="group-members-list-search p-0 mt-3">

                    </div>
                </div>

                <div class="owners-list">
                    <ul class="group-members-list">

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$type_html = base_url() . "input/gettypehtml";
?>

<script>
    $(document).on("click", function(event) {
        if (!$(event.target).closest('.menu-result').length) {
            $(".menu-result").hide();
        }
    });

    var group_id;
    var user_id_login = <?= $this->session->userdata('user_id') ?>;

    $('body').on("click", ".add-user-to-group", function() {
        // if (is_owner) {
        $.ajax({
            url: "<?= base_url('items/add_owner') ?>",
            method: "post",
            data: {
                user_id: $(this).data('user_id'),
                item_id: $(this).data('item_id'),
            },
            dataType: "json",
            success: function(res) {
                $.ajax({
                    url: "<?= base_url('items/get_owners') ?>",
                    method: "get",
                    data: {
                        item_id: group_id,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            updateGroupOwner(response.data);
                        }
                    }
                });
                $(".menu-result").hide();
                $('#search-users-to-invite-group').val('');
            }
        });
        // } else {
        //     toastr.warning('Thành viên không thể thêm thành viên khác vào nhóm!');
        // }
    });

    $('body').on('click', '.btn-add-member', function() {
        group_id = $(this).data('group-id');
        $.ajax({
            url: "<?= base_url('items/get_owners') ?>",
            method: "get",
            data: {
                item_id: group_id,
            },
            dataType: "json",
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    updateGroupOwner(response.data);
                }
            }
        });
    });

    $('body').on('click', '.btn-clear-member', function() {
        let user_id = $(this).data('user-id');
        let group_id = $(this).data('group-id');

        if ($(this).find('svg').length > 0 && user_id_login == user_id) {
            toastr.warning('Không thể xóa chủ nhóm!');
        } else {
            $.ajax({
                url: "<?= base_url('items/delete_user_from_group') ?>",
                method: "post",
                data: {
                    user_id: user_id,
                    item_id: group_id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        updateGroupOwner(response.data);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        }
    });


    function updateGroupOwner(owners) {
        $('.group-members-list').empty();

        let isOwnerOfGroup = true;

        $.each(owners, function(key, owner) {
            var row = '<div class="group-member-item-info">' +
                '<div data-item_id="' + group_id + '" data-user_id="' + owner.id + '" class="p-2">' +
                '<img src="' + '<?= base_url() ?>' + owner.avatar + '" alt="">' +
                '<span class="px-2">' + owner.firstname + ' ' + owner.lastname + '</span>' +
                '</div>' +
                '<button style="border: none;background-color: transparent;" type="button" class="btn-clear-member" data-user-id="' + owner.id + '" data-group-id="' + group_id + '">';
            if (isOwnerOfGroup === true) {
                row += '<svg fill="#FFFF00" height="16px" width="16px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 220 220" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M220,98.865c0-12.728-10.355-23.083-23.083-23.083s-23.083,10.355-23.083,23.083c0,5.79,2.148,11.084,5.681,15.14 l-23.862,21.89L125.22,73.002l17.787-20.892l-32.882-38.623L77.244,52.111l16.995,19.962l-30.216,63.464l-23.527-21.544 c3.528-4.055,5.671-9.344,5.671-15.128c0-12.728-10.355-23.083-23.083-23.083C10.355,75.782,0,86.137,0,98.865 c0,11.794,8.895,21.545,20.328,22.913l7.073,84.735H192.6l7.073-84.735C211.105,120.41,220,110.659,220,98.865z"></path> </g></svg>';
                isOwnerOfGroup = false;
            } else if (user_id_login == owners[0].id) {
                row += '<i class="fa fa-times-circle" aria-hidden="true"></i>';
            }

            row += '</button></div>';

            $('.group-members-list').append(row);
        });
    }


    $('#search-users-to-invite-group').keyup(function() {
        let typingTimer;
        let doneTypingInterval = 500;

        clearTimeout(typingTimer);
        const inputValue = $(this).val().trim();
        if (inputValue) {
            typingTimer = setTimeout(function() {
                searchUsersAddGroup(inputValue);
            }, doneTypingInterval);
        } else {
            $('.group-members-list-search').empty();
            $(".menu-result").hide();
        }

    });

    function searchUsersAddGroup(searchQuery) {
        $.ajax({
            url: "<?= base_url('user/search_users') ?>",
            method: "get",
            data: {
                search: searchQuery,
            },
            dataType: "json",
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    $(".menu-result").css("display", "block");

                    $('.group-members-list-search').empty();

                    var users = response.data;
                    $.each(users, function(key, user) {
                        var row = '<button type="button" data-item_id="' + group_id + '" data-user_id="' + user.id + '" class="add-user-to-group list-group-item list-group-item-action p-2 col-10">' +
                            '<div class="group-member-item-info">' +
                            '<img src="<?= base_url() ?>' + user.avatar + '" alt="">' +
                            '<span>' + user.firstname + ' ' + user.lastname + '</span>';
                        if (user.department_name !== null && user.department_name !== undefined) {
                            row += '<span> <small><i>(' + user.department_name + ')</i></small> </span>';
                        }

                        row += '</div></button>';

                        $('.group-members-list-search').append(row);
                    });
                } else {
                    $(".menu-result").hide();
                }
            }
        });
    }

    $('#search-department').keyup(function() {
        let typingTimer;
        let doneTypingInterval = 500;

        clearTimeout(typingTimer);
        const inputValue = $(this).val().trim();
        if (inputValue) {
            typingTimer = setTimeout(function() {
                searchDepartment(inputValue);
            }, doneTypingInterval);
        } else {
            $('.group-members-list-search').empty();
            $(".menu-result").hide();
        }

    });

    function searchDepartment(searchQuery) {
        $.ajax({
            url: "<?= base_url('user/search_department') ?>",
            method: "get",
            data: {
                search: searchQuery,
            },
            dataType: "json",
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    $(".menu-result").css("display", "block");

                    $('.group-members-list-search').empty();

                    var department = response.data;
                    $.each(department, function(key, department) {
                        var row = '<button type="button" data-department-id="' + department.id + '"  class="add-department-to-project list-group-item list-group-item-action p-2 col-10">' +
                            '<div class="group-member-item-info">' +
                            '<span>' + department.title + '</span>' +
                            '</div>' +
                            '</button>';
                        $('.group-members-list-search').append(row);
                    });
                } else {
                    $(".menu-result").hide();
                }
            }
        });
    }

    $(document).ready(function() {
        $("body").on("click", ".fiels_list_item", function() {
            const type_html = $(this).attr('data-type-html');
            const value = $(this).attr('data-value');
            const group_id = $(this).attr('data-group-id');
            const project_id = $(this).closest(".btn-add-fields").attr("data-id");

            $.ajax({
                url: "<?= base_url() ?>fields/add",
                method: "post",
                data: {
                    title: value,
                    items_id: project_id,
                    type_html,
                    group_id,
                    type: "customtable"
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        const {
                            field_html,
                            data_html
                        } = response;

                        const field_title_last = $(".task-item-header").find(".field-title:last");

                        if (field_title_last.length > 0) {
                            $(field_html).insertAfter(field_title_last);
                        } else {
                            $(field_html).insertBefore(".btn-add-fields");
                        }


                        console.log(data_html);

                        data_html.map((meta, index) => {

                            const task_item = $(`.task-item[data-item-id='${meta.items_id}']`);
                            const meta_last = task_item.find(".task-meta:last");

                            console.log(meta_last);

                            if (meta_last.length > 0) {
                                $(meta.meta_html).insertAfter(meta_last);

                            } else {
                                task_item.append(meta.meta_html);
                            }
                        })

                        $(".task-item-header").css("width", "fit-content");
                    }
                }

            })

        });

        // Handle group name change
        $("body").on("change", ".group-item-title-input", function() {
            const title = $(this).val().trim("");

            $.ajax({
                url: "<?= base_url() ?>admin/items/update/" + $(this).attr('data-id'),
                method: "post",
                data: {
                    title
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success("Cập nhật tên nhóm thành công!");
                    }
                }
            });
        })

        // Handle task title change
        $("body").on("change", ".task-title .input-group input", function() {
            const title = $(this).val().trim("");

            $(this).parents(".task-title").attr("data-bs-title", title);
            $('[data-bs-toggle="tooltip"]').tooltip();

            $.ajax({
                url: "<?= base_url() ?>admin/items/update/" + $(this).attr('data-id'),
                method: "post",
                data: {
                    title
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success("Cập nhật tên công việc thành công!");
                    }
                }
            })
        })

        // Handle field title change
        $("body").on("change", ".field-title .input-group input", function() {
            const title = $(this).val().trim("");

            $.ajax({
                url: "<?= base_url() ?>fields/update/" + $(this).attr('data-id'),
                method: "post",
                data: {
                    title
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success("Cập nhật dữ liệu thành công!");
                    }
                }
            })
        })


        // Handle add new task
        $("body").on("click", ".btn-add-task", function() {
            const group_id = $("input[name='group-name']").first().attr("data-id");
            const payload = {
                type_id: 8,
                title: "New item",
                user_id: "<?= $this->session->userdata('user_id') ?>",
                project_id: <?= $project->id ?>,
                parent_id: group_id,
            }

            $.ajax({
                url: "<?= base_url("admin/items/add") ?>",
                method: "post",
                dataType: "json",
                data: payload,
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
            });
        });

        // Handle ajax add task
        $('body').on("change", ".input-add-item", function() {
            const input_add_item = $(this);

            const max_position = $(".group-list-item").first().find(".sort-item").last().attr("data-position");
            const payload = {
                type_id: $(this).attr("data-type-id"),
                title: $(this).val(),
                user_id: "<?= $this->session->userdata('user_id') ?>",
                project_id: <?= $project->id ?>,
                parent_id: <?= $project->id ?>,
                position: +max_position + 1,
            }
            $.ajax({
                url: "<?= base_url("admin/items/add") ?>",
                method: "post",
                dataType: "json",
                data: payload,
                success: function(response) {
                    if (response.success) {
                        if (input_add_item.parents('.subitem').length > 0) {
                            const sub_task_list = input_add_item.parents('.subitem');

                            if (sub_task_list.children('.task-item').length > 0) {
                                sub_task_list.children('.task-item:last').after(response.data);
                            } else {
                                sub_task_list.children('.task-item-header').after(response.data);
                            }

                            $('[data-bs-toggle="tooltip"]').tooltip();

                            toastr.success("Đã thêm công việc phụ!");
                        } else {
                            const task_list = input_add_item.parents('.group-item').find('.sortable');

                            if (task_list.children('.sort-item').length > 0) {
                                task_list.children('.sort-item:last').after(response.data);
                            } else {
                                task_list.append(response.data);
                            }

                            $('[data-bs-toggle="tooltip"]').tooltip();

                            toastr.success("Đã thêm công việc!");
                        }

                        input_add_item.val('');

                    }
                },
            });
        });

        // Handle meta change
        $('body').on('change', '.task-meta .input-group input', function() {

            const meta_id = $(this).attr("data-meta-id");

            switch ($(this).attr("type")) {
                case "file":
                    const files = $(this)[0].files;
                    const key = $(this).attr("name");
                    const key_code = $(this).attr("data-group-key-code");
                    const group_id = $(this).attr("data-group-id");
                    const item_id = <?= $project->id ?>;
                    const file_meta_input = $(this).parent().find(".file_meta_input");

                    let html_file = '';
                    const url = '<?= base_url() ?>';

                    const formData = new FormData();
                    formData.append("key", key);
                    formData.append("item_id", item_id);
                    formData.append("meta_id", meta_id);
                    formData.append("project_id", "<?= $project->id ?>");
                    formData.append("key_code", key_code);
                    formData.append("group_id", group_id);

                    for (let i = 0; i < files.length; i++) {

                        if (files[i].size / 1024 > 10240) {
                            toastr.warning("File không được lớn hơn 10MB");
                            return;
                        }

                        formData.append('files[]', files[i]);
                    }

                    $.ajax({
                        url: "<?= base_url("file/upload") ?>",
                        method: "post",
                        processData: false,
                        contentType: false,
                        cache: false,
                        enctype: 'multipart/form-data',
                        data: formData,
                        dataType: "json",
                        success: function(response) {

                            if (response.success) {

                                $(`.file_meta_input[data-meta-id='${meta_id}']`).html(response?.file_html);

                                toastr.success("Thêm file thành công!");
                            } else {
                                toastr.error(response.data);
                            }
                        },
                    })

                    break;
                case "text":
                case "number":
                case "date":
                    if ($(this).attr("name") !== 'daterange') {

                        const value = $(this).val();

                        $.ajax({
                            url: "<?= base_url() ?>admin/items/update_meta",
                            method: "post",
                            data: {
                                meta_id,
                                value
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    toastr.success("Cập nhật dữ liệu thành công!");
                                }
                            }
                        });
                    }
                    break;
                default:
                    break;
            }

        });


        $('body').on('change', '.task-meta .input-group select', function() {

            const type = $(this).attr("data-type");
            const status_select = $(this);

            const meta_id = $(this).attr("data-id");
            const group_id = $(this).attr("data-group-id");
            const item_id = $(this).attr("data-item-id");

            const payload = {
                meta_id,
                value: status_select.val(),
                view_type: "customtable",

            }

            switch (type) {
                case 'status':
                    payload.type = "status";
                    payload.project_id = <?= $project->id ?>;
                    payload.item_id = item_id;
                    payload.meta_id = meta_id;

                    $.ajax({
                        url: "<?= base_url() ?>admin/items/update_meta",
                        method: "post",
                        data: payload,
                        dataType: "json",
                        success: function(response) {
                            console.log(response)
                            if (response.success) {
                                switch (type) {
                                    case "status":
                                        const bg_old = status_select.attr("data-bg-color");
                                        const bg_color = status_select.val().split("|")[1];

                                        status_select.removeClass(bg_old);
                                        status_select.addClass("bg-" + bg_color);
                                        status_select.attr("data-bg-color", "bg-" + bg_color);
                                        break;
                                    default:
                                        break;
                                }

                                toastr.success("Cập nhật dữ liệu thành công!");
                            } else {
                                for (let i = 0; i < response.dependent_items.length; i++) {
                                    const {
                                        title,
                                        id
                                    } = response.dependent_items[i];

                                    toastr.warning(title + " phải hoàn thành trước", '', {
                                        timeOut: 10000
                                    }).click(function() {
                                        let item_focus = $('.sort-item .task-item[data-item-id="' + id + '"]');
                                        item_focus.css('border', '1px solid red');
                                        item_focus.addClass('shake');
                                        setTimeout(function() {
                                            item_focus.css('border', '');
                                        }, 5000);
                                    });
                                }
                                switch (type) {
                                    case "status":
                                        status_select.val(previousValue);
                                        break;
                                    default:
                                        break;
                                }
                            }

                        }
                    });

                    break;

                    // Xử lý select là xác nhận của lãnh đạo
                case 'confirm':
                    if (status_select.val() !== 'chuabatdau|secondary|Chưa bắt đầu') {

                        if ($('#performance-review-modal').length > 0) {
                            $('#performance-review-modal').remove();
                        }

                        $.ajax({
                            url: "<?= base_url() ?>confirm/show_review_modal",
                            method: "get",
                            data: {
                                item_id: item_id,
                            },
                            dataType: "json",
                            success: function(res) {
                                if (res.success) {
                                    $('.main-content').append(res.data);

                                    old_value_confirmed = status_select.val();

                                    const performance_review_modal = new bootstrap.Modal('#performance-review-modal');
                                    performance_review_modal.show();

                                } else {
                                    toastr.error(res.message);
                                }
                            }

                        });
                    }
                    break;
                case 'connettable':
                    $.ajax({
                        url: "<?= base_url() ?>admin/items/update_meta",
                        method: "post",
                        data: payload,
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                toastr.success("Cập nhật dữ liệu thành công!");
                            }

                        }
                    });
                    break;
                default:
                    $.ajax({
                        url: "<?= base_url() ?>admin/items/update_meta",
                        method: "post",
                        data: payload,
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                toastr.success("Cập nhật dữ liệu thành công!");
                            }

                        }
                    });
                    break;
            }
        });

        //Hanlde review button
        $('body').on('click', '.score', function() {

            $(this).attr('class', 'btn btn-success score mx-2');

            $(this).closest('.number-scale').find('.score').not(this).each(function() {

                $(this).attr('class', 'btn btn-outline-success score mx-2');

            });

        });

        //Handle click submit review button
        $('body').on('click', '.btn-submit-review', function() {

            let data = [];

            const scoresList = $(this).closest('.modal-content').find('.modal-body .container .row');

            //Duyet qua mang de lay data danh gia truoc khi submit
            scoresList.each(function() {

                const userId = $(this).find('.number-scale').data('user-id');
                const itemId = $(this).find('.number-scale').data('item-id');
                const desc = $(this).find('.desc').val();

                let selectedScore = 10;

                $(this).find('.score').each(function() {
                    if ($(this).hasClass('btn-success')) {
                        selectedScore = $(this).data('score');
                    }
                });

                data.push({
                    user_id: userId,
                    item_id: itemId,
                    score: selectedScore,
                    desc: desc,
                });
            });

            $.ajax({
                url: '<?= base_url() ?>confirm/mark',
                data: {
                    scores: data
                },

                method: 'post',
                dataType: 'json',
                success: function(res) {
                    $('#performance-review-modal').modal('hide');

                    toastr.success("Thêm đánh giá thành công!")
                }
            });

        });

        // Handle the close event of the modal
        $('body').on('hidden.bs.modal', '#performance-review-modal', function() {
            // Add any additional cleanup or actions here
        });

        $('body').on('change', '.task-meta .input-group textarea', function() {
            const payload = {
                meta_id: $(this).attr("data-id"),
                value: $(this).val(),
            }

            $.ajax({
                url: "<?= base_url() ?>admin/items/update_meta",
                method: "post",
                data: payload,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success("Cập nhật dữ liệu thành công!");
                    }
                }
            })
        });


        // Handle add group
        $('body').on('click', '.btn-add-group', function() {
            const group_list = $('.group-list');
            const items_id = $(this).attr("data-project-id");
            const title = "New Group";
            const type_id = 5;

            $.ajax({
                url: "<?= base_url() ?>admin/items/group/add",
                method: "post",
                dataType: "json",
                data: {
                    parent_id: items_id,
                    title,
                    type_id
                },
                success: function(response) {
                    if (response.success) {
                        group_list.append(response.data);

                        toastr.success('Thêm nhóm thành công!');
                    }
                }
            })
        })

        $('body').on('click', '#btn-add-group', function() {
            const group_list = $('.group-list');
            const items_id = $(this).attr("data-project-id");
            const title = "New Group";
            const type_id = 5;

            $.ajax({
                url: "<?= base_url() ?>admin/items/group/add",
                method: "post",
                dataType: "json",
                data: {
                    parent_id: items_id,
                    title,
                    type_id
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        group_list.append(response.data);

                        toastr.success('Thêm nhóm thành công!');

                    }
                }
            })
        })

        // Handle delete field
        $("body").on('click', '.btn-delete-field', function() {
            const id = $(this).attr("data-id")
            $.ajax({
                url: `<?= base_url("fields/delete/") ?>${id}`,
                dataType: "json",
                success: function(response) {
                    if (response.success) {

                        const field_title = $(`.field-title[data-field-id='${id}']`);
                        field_title.remove();

                        const task_meta = $(`.task-meta[data-field-id='${id}']`);

                        task_meta.remove();

                        $(".task-item-header").css("width", "fit-content");
                    }
                }
            });
        })

        // Handle delete task
        $("body").on('click', '.btn-delete-task', function() {
            const id = $(this).attr("data-id")

            $.ajax({
                url: `<?= base_url("admin/items/delete/") ?>${id}`,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        const sort_item = $(`.sort-item[data-item-id='${id}']`);
                        const task_item = $(`.task-item[data-item-id='${id}']`);

                        if (sort_item.length > 0) {
                            sort_item.remove();
                        }

                        if (task_item.length > 0) {
                            task_item.remove();
                        }

                        $('.task-title').tooltip({
                            disabled: true
                        })
                    }
                }
            })
        })

        // Handle delete group
        $("body").on('click', '.btn-delete-group', function() {
            const id = $(this).attr("data-id");

            $.ajax({
                url: `<?= base_url("admin/items/delete/") ?>${id}`,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        const group_list_item = $(`.group-list-item[data-group-id='${id}']`);
                        group_list_item.remove();
                    }
                }
            })
        })

        // Set type_id for modal
        $('body').on('click', '.type-select-id', function() {
            const type_id = $(this).attr("data-type-id");
            $("input[name='type_id']").val(type_id);
        })

        // Btn rename group, task
        $("body").on('click', '.btn-rename', function() {
            let input = $(this).closest(".input-group, .group-item-title").find("input");

            const input_value = input.val();

            input.focus().val('').val(input_value);
        })

        // Handle collapse group
        $("body").on('click', '.btn-collapse', function() {
            const isCollapsed = $(this).hasClass("collapsed");

            if (isCollapsed) {
                $(this).children().attr("class", "fa fa-chevron-right text-primary")
            } else {
                $(this).children().attr("class", "fa fa-chevron-down text-primary")
            }
        })


        // Handle clear user
        // $("body").on("click", ".btn-clear-user", function(e) {
        //     const meta_id = $(this).attr("data-meta-id");
        //     const project_id = $(this).attr("data-project-id");
        //     const group_id = $(this).attr("data-group-id");
        //     const value = $(this).attr("data-user-id");

        //     const task_meta = $(`.task-meta[data-meta-id='${meta_id}']`);

        //     const payload = {
        //         meta_id,
        //         project_id,
        //         group_id,
        //         value,
        //         type: "people_remove"
        //     }

        //     $.ajax({
        //         url: "<?= base_url() ?>admin/items/update_meta",
        //         method: "post",
        //         data: payload,
        //         dataType: "json",
        //         success: function(response) {
        //             if (response.success) {

        //                 task_meta.empty();
        //                 task_meta.append(response.data);

        //                 toastr.success("Thay đổi đã được cập nhật!");
        //             }
        //         }
        //     })
        // });
    });

    // Handle sort task
    $(".sortable").sortable({
        update: function(event, ui) {

            let array_id = [];

            $(this).children().each(function() {
                const id = $(this).attr("data-item-id");
                array_id.push(id);
            })

            $.ajax({
                url: "<?= base_url("items/sort_task") ?>",
                method: "post",
                data: {
                    array_id
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // location.reload();
                    }
                }
            })
        }
    });

    // Handle search task
    $(".input-search-task input").keyup(function() {
        const key = $(this).val().toLowerCase();
        $(".sortable .sort-item").each(function() {
            $(this).toggle($(this).find('.task-title .input-group-table input').val().toLowerCase().indexOf(key) > -1);
        });
    });

    // Handle btn-actions-delete click
    $(".btn-actions-delete").click(function() {
        let array_id = [];
        const item_checked = $(".stt input:checked");
        const subitem_checked = $(".stt_subitem input:checked");

        if (item_checked.length > 0) {
            item_checked.each(function() {
                const parent = $(this).parent();

                const task_id = parent.attr("data-id");
                array_id.push(task_id);
            })
        }

        if (subitem_checked.length > 0) {
            subitem_checked.each(function() {
                const parent = $(this).parent();
                const task_id = parent.attr("data-id");
                array_id.push(task_id);
            })
        }

        if (array_id.length > 0) {
            // Ajax delete 
            $.ajax({
                url: "<?= base_url("items/delete_multiple") ?>",
                method: "post",
                dataType: "json",
                data: {
                    array_id
                },
                success: function(respsonse) {
                    if (respsonse.success) {

                        array_id.map(task_id => {
                            const sort_item = $(`.sort-item[data-item-id='${task_id}']`);
                            const subitem = $(`.task-item[data-item-id='${task_id}']`);

                            if (sort_item.length > 0) {
                                sort_item.remove(


                                );
                            }

                            if (subitem.length > 0) {
                                subitem.remove();
                            }

                        })

                        toastr.success("Công việc đã được xóa!");
                        $(".batch-actions").css("visibility", "hidden");
                    }
                }
            })
        }
    })

    // Handle close batch-actions
    $(".batch-actions-close-btn").click(function() {
        const parent = $(this).parent();

        parent.css("visibility", "hidden");

        const input_checkbox = $(".stt input");
        const input_checkball = $(".checkall_btn input");

        input_checkbox.each(function() {
            $(this).prop("checked", false);
        })

        input_checkball.each(function() {
            $(this).prop("checked", false);
        })

    })

    // Handle export file
    $(".btn-actions-export").click(function() {
        let payload = [];

        const btn_actions_export = $(this);

        const group_list = $(".group-list-item");

        const loading_html = `<img width='100' src="https://img.pikbest.com/png-images/20190918/cartoon-snail-loading-loading-gif-animation_2734139.png!bw700" alt="loading">`;
        const normal_html = `
            <span class="batch-actions-item-icon"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span>
            <span>Xuất</span>
        `;

        btn_actions_export.html(loading_html);

        group_list.each(function() {
            let obj = {};
            const group = $(this);
            const group_id = group.attr("data-group-id")

            obj.group_id = group_id;

            const item_checked = group.find(".stt input:checked");

            const subitem_checked = group.find(".stt_subitem input:checked");

            let array_id = [];

            if (item_checked.length > 0) {
                item_checked.each(function() {
                    const parent = $(this).parent();
                    const task_id = parent.attr("data-id");
                    array_id.push(task_id);
                })
                obj.tasks_id = array_id;
            }

            if (subitem_checked.length > 0) {
                subitem_checked.each(function() {
                    const parent = $(this).parent();
                    const task_id = parent.attr("data-id");
                    array_id.push(task_id);
                })
                obj.tasks_id = array_id;
            }

            payload.push(obj);
        })

        $.ajax({
            "url": `<?= base_url("items/export") ?>`,
            dataType: "json",
            method: "post",
            data: {
                payload,
                project_id: `<?= $project->id ?>`
            },
            success: function(data) {
                const $a = $("<a>");
                $a.attr("href", data.file);
                $("body").append($a);
                $a.attr("download", `board-${Date.now()}.xlsx`);
                $a[0].click();
                $a.remove();
                btn_actions_export.html(normal_html);

            },
            error: function() {
                btn_actions_export.html(normal_html);
            }
        })
    });

    // Handle export sample file
    $("body").on("click", ".btn-export-sample-file", function() {

        $.ajax({
            url: "<?= base_url("items/export-sample-file") ?>",
            method: "post",
            data: {
                project_id: "<?= $project->id ?>"
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    const $a = $("<a>");
                    $a.attr("href", response.file);
                    $("body").append($a);
                    $a.attr("download", `file-mau-${Date.now()}.xlsx`);
                    $a[0].click();
                    $a.remove();
                }
            }
        })
    })

    // Handle import data for group
    $("body").on("change", ".input-file-import", function() {
        const label_import = $(this).parent().find(".btn-import-data-group");

        const group_id = $(this).data("project-id");
        const project_id = "<?= $project->id ?>";

        const file = $(this)[0].files;

        const formData = new FormData();
        formData.append("group_id", group_id);
        formData.append("project_id", project_id);
        formData.append("type_id", 35);
        formData.append("file", file[0]);

        if (file.length > 0) {

            label_import.html("<span>Đang tải lên...</span>");

            $.ajax({
                url: "<?= base_url("items/import_task") ?>",
                method: "post",
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                data: formData,
                dataType: "json",
                success: function(response) {

                    console.log(response)

                    if (response.success) {
                        label_import.html(`<img width="15" src="<?= base_url("assets/images/excel.svg") ?>" alt="">
                                        Nhập`);

                        const sortable = $(`.sortable`);

                        if (sortable.length > 0) {
                            sortable.html(response.data_html);
                        }

                        toastr.success("Nhập dữ liệu thành công!");
                    }

                },
                error: function(error) {
                    label_import.html(`<img width="15" src="<?= base_url("assets/images/excel.svg") ?>" alt="">
                                        Nhập`);
                    toastr.error("Nhập dữ liệu không thành công!");
                }
            })

        }

    })

    // Popover hidden columns
    $(document).ready(function() {
        const options = {
            html: true,
            title: `<div class='d-flex justify-content-between'>
                <span>Hiển thị - Ẩn cột</span>
            </div>`,
            placement: "bottom",
            content: $('.hidden-column-body'),
        };
        const hidenColumnEl = $('.btn-hidden-column');

        const popover = new bootstrap.Popover(hidenColumnEl, options);

    });

    // Popover filter

    $(document).ready(function() {

        const options = {
            html: true,
            title: `<div class='d-flex justify-content-between'>
                <span>Bộ lọc nhanh</span>
            </div>`,
            placement: "bottom",
            content: $('.filter-column-body'),
        };

        const filterColumnEl = $('.btn-filter-column');

        const popover = new bootstrap.Popover(filterColumnEl, options);

    });

    // Popover hidden groups
    $(document).ready(function() {
        const options = {
            html: true,
            title: `<div class='d-flex justify-content-between'>
                <span>Hiển thị - Ẩn nhóm</span>
            </div>`,
            placement: "bottom",
            content: $('.hidden-group-body'),
        };
        const hidenGroupEl = $('.btn-hidden-group');

        const popover = new bootstrap.Popover(hidenGroupEl, options);

    });

    // Handle check hidden column
    $("body").on("change", ".hidden-column-list-checkbox", function() {
        const isChecked = $(this).is(":checked");
        const id = $(this).attr("data-field-id");

        const field_title = $(`.field-title[data-field-id='${id}']`);
        const task_meta = $(`.task-meta[data-field-id='${id}']`);

        const hiddenCheckedColumnLength = $(".hidden-column-list-checkbox:checked").length;
        const hiddenColumnLength = $(".hidden-column-list-checkbox").length;

        $(".hidden-column-number-selected").text(`${hiddenCheckedColumnLength} đã chọn`);

        if (hiddenCheckedColumnLength == hiddenColumnLength) {
            $(".hidden-column-list-checkall").prop("checked", true);
        } else {
            $(".hidden-column-list-checkall").prop("checked", false);
        }

        $.ajax({
            url: `<?= base_url() ?>fields/update/${id}`,
            method: "post",
            data: {
                display: isChecked ? 0 : 1
            },
            dataType: "json",
            success: function(response) {

                if (response.success) {

                    switch (isChecked) {
                        case true:
                            field_title.prop("hidden", true);
                            task_meta.prop("hidden", true);
                            $(".task-item-header").css("width", "fit-content");
                            break;
                        case false:
                            field_title.prop("hidden", false);
                            task_meta.prop("hidden", false);
                            $(".task-item-header").css("width", "fit-content");
                            break;
                        default:
                            break;
                    }
                }

            }
        })


    })

    // Handle check hidden column
    $("body").on("change", ".hidden-group-list-checkbox", function() {
        const isChecked = $(this).is(":checked");
        const id = $(this).attr("data-group-id");

        const group = $(`.group-list-item[data-group-id='${id}']`);

        const hiddenCheckedGroupLength = $(".hidden-group-list-checkbox:checked").length;
        const hiddenGroupLength = $(".hidden-group-list-checkbox").length;

        $(".hidden-group-number-selected").text(`${hiddenCheckedGroupLength} đã chọn`);

        if (hiddenCheckedGroupLength == hiddenGroupLength) {
            $(".hidden-group-list-checkall").prop("checked", true);
        } else {
            $(".hidden-group-list-checkall").prop("checked", false);
        }

        $.ajax({
            url: `<?= base_url() ?>items/update/${id}`,
            method: "post",
            data: {
                display: isChecked ? 0 : 1
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    switch (isChecked) {
                        case true:
                            group.prop("hidden", true);
                            break;
                        case false:
                            group.prop("hidden", false);
                            break;
                        default:
                            break;
                    }
                }
            }
        })

    })

    $(document).ready(function() {
        const inputHiddenColumnLength = $(".hidden-column-list-checkbox").length;
        const hiddenCheckedColumnLength = $(".hidden-column-list-checkbox:checked").length;

        if (inputHiddenColumnLength == hiddenCheckedColumnLength) {
            $(".hidden-column-list-checkall").prop("checked", true);
        } else {
            $(".hidden-column-list-checkall").prop("checked", false);
        }

        const inputHiddenGroupLength = $(".hidden-group-list-checkbox").length;
        const hiddenCheckedGroupLength = $(".hidden-group-list-checkbox:checked").length;

        if (inputHiddenGroupLength == hiddenCheckedGroupLength) {
            $(".hidden-group-list-checkall").prop("checked", true);
        } else {
            $(".hidden-group-list-checkall").prop("checked", false);
        }

    });

    // Handle check all hidden column list
    $("body").on("change", ".hidden-column-list-checkall", function() {
        const isChecked = $(this).is(":checked");

        if (isChecked) {
            $(".hidden-column-list-checkbox").prop("checked", true);

            const hiddenCheckedLength = $(".hidden-column-list-checkbox:checked").length;
            $(".hidden-column-number-selected").text(`${hiddenCheckedLength} đã chọn`);

            $(".field-title").prop("hidden", true);
            $(".task-meta").prop("hidden", true);

            $(".task-item-header").css("width", "fit-content");

        } else {
            $(".hidden-column-list-checkbox").prop("checked", false);

            const hiddenCheckedLength = $(".hidden-column-list-checkbox:checked").length;
            $(".hidden-column-number-selected").text(`${hiddenCheckedLength} đã chọn`);


            $(".field-title").prop("hidden", false);
            $(".task-meta").prop("hidden", false);

            $(".task-item-header").css("width", "fit-content");

        }

        $.ajax({
            url: `<?= base_url() ?>fields/update_all`,
            method: "post",
            data: {
                project_id: "<?= $project->id ?>",
                display: isChecked ? 0 : 1,
            },
            dataType: "json",
            success: function(response) {

                if (response.success) {

                    switch (isChecked) {
                        case true:
                            field_title.prop("hidden", true);
                            task_meta.prop("hidden", true);
                            break;
                        case false:
                            field_title.prop("hidden", false);
                            task_meta.prop("hidden", false);
                            break;
                        default:
                            break;
                    }

                    $(".task-item-header").css("width", "fit-content");
                    $(".group-item").css("width", Math.floor($(".task-item-header").width() + 52) + "px");
                }

            }
        })

    })
    // End Handle check hidden column

    // Handle check all hidden group list
    $("body").on("change", ".hidden-group-list-checkall", function() {
        const isChecked = $(this).is(":checked");

        if (isChecked) {
            $(".hidden-group-list-checkbox").prop("checked", true);

            const hiddenCheckedGroupLength = $(".hidden-group-list-checkbox:checked").length;
            $(".hidden-group-number-selected").text(`${hiddenCheckedGroupLength} đã chọn`);

            $(".group-list-item").prop("hidden", true);
            $(".hidden-group-list-checkbox").prop("checked", true);

        } else {
            $(".hidden-column-list-checkbox").prop("checked", false);

            const hiddenCheckedGroupLength = $(".hidden-column-list-checkbox:checked").length;
            $(".hidden-column-number-selected").text(`${hiddenCheckedGroupLength} đã chọn`);

            $(".group-list-item").prop("hidden", false);
            $(".hidden-group-list-checkbox").prop("checked", false);

        }

        $.ajax({
            url: `<?= base_url() ?>items/update_all`,
            method: "post",
            data: {
                project_id: "<?= $project->id ?>",
                display: isChecked ? 0 : 1,
            },
            dataType: "json",
            success: function(response) {

                if (response.success) {

                    switch (isChecked) {
                        case true:
                            $(".group-list-item").prop("hidden", true);
                            break;
                        case false:
                            $(".group-list-item").prop("hidden", false);
                            break;
                        default:
                            break;
                    }
                }

            }
        })

    })
    // End Handle check all hidden group

    // Handle filter column

    $("body").on("change", "select[name='select-filter-fields']", function() {
        const key = $(this).val();
        const type_html = $(this).find(":selected").data("field-type");
        const parent = $(this).parents(".group-filter-column-item");

        const input_text_filter_value = parent.find(".input-text-filter-values");
        const select_filter_values = parent.find("select[name='select-filter-values']");

        const html = [];

        input_text_filter_value.attr('data-key', key);
        select_filter_values.attr('data-key', key);
        select_filter_values.attr('data-type', type_html);

        switch (type_html) {
            case "file":
                input_text_filter_value.prop("hidden", false);
                input_text_filter_value.prop("disabled", true);
                select_filter_values.prop("hidden", true);
                break;
            case "text":
                input_text_filter_value.prop("hidden", false);
                select_filter_values.attr("hidden", true);
                break;
            default:
                input_text_filter_value.prop("hidden", true);
                select_filter_values.prop("hidden", false);

                $.ajax({
                    url: "<?= base_url("fields/get_meta") ?>",
                    data: {
                        key,
                        type_html
                    },
                    method: "post",
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            if (response.data.length > 0) {
                                response?.data.map(item => {

                                    switch (type_html) {
                                        case "people":
                                            html.push(`<option data-user-id='${item.user_id}'>${item.value}</option>`);
                                            break;
                                        case "status":

                                            if (item.value.split('|')[2]) {
                                                html.push(`<option value='${item.value}' data-user-id='${item.user_id}'>${item.value.split('|')[2]}</option>`);
                                            }

                                            break;
                                        default:
                                            html.push(`<option value='${item.value}'>${item.value}</option>`);

                                            break;
                                    }

                                });

                                html.unshift(`<option value="" disabled selected> --- Chọn giá trị --- </option>`);

                                select_filter_values.html(html);
                                select_filter_values.prop("disabled", false);
                            } else {
                                select_filter_values.prop("disabled", true);
                            }
                        } else {
                            toastr.warning("Không tìm thấy dữ liệu!");
                        }
                    }
                })
                break;
        }

    })

    // Handle filter (Text & Select)
    $("body").on("click", ".btn-filter", function() {
        const payload = [];

        const group_filter_column_item = $(".group-filter-column-item");

        group_filter_column_item.each(function() {
            const item = {};

            const select_filter_logic = $(this).find("select[name='select-filter-logic']").find(":selected");
            const select_filter_fields = $(this).find("select[name='select-filter-fields']").find(":selected");
            const select_filter_condition = $(this).find("select[name='select-filter-condition']").find(":selected");
            const input_filter_value = $(this).find(".input-filter-value").not(":hidden");


            if (select_filter_logic.val() != null || undefined) {
                item.logic = select_filter_logic.val();
            }
            if (select_filter_fields.val() != null || undefined) {
                item.key = select_filter_fields.val();
            }
            if (select_filter_condition.val() != null || undefined) {
                item.condition = select_filter_condition.val();
            }
            if (input_filter_value.val() != null || undefined) {

                let value = input_filter_value.val();

                const type = input_filter_value.attr("data-type");

                switch (type) {
                    case "people":
                        value = input_filter_value.find(":selected").attr("data-user-id");
                        break;
                    case "date":
                        value = value.split("-").reverse().join("-");
                        break;
                    case "percent":
                        value = value.replace("%", "");
                        break;
                    default:
                        break;
                }

                item.value = value;
                item.type = type;
            }

            if (Object.keys(item).length > 3) {
                payload.push(item);
            }

        })

        // Ajax filter
        if (payload.length > 0) {
            $.ajax({
                url: "<?= base_url("table/filter") ?>",
                data: {
                    payload,
                    type_filter: "customtable"
                },
                method: "post",
                dataType: "json",
                success: function(response) {

                    if (response.success) {
                        const sort_items = $(`.sort-item`);

                        if (response?.data.length > 0) {

                            $(".total_filter").text(`${response.data.length} kết quả`);

                            sort_items.each(function() {
                                if (response.data.includes($(this).attr("data-item-id"))) {
                                    $(this).prop("hidden", false);
                                } else {
                                    $(this).prop("hidden", true);
                                }
                            })
                        } else {
                            toastr.warning("Không tìm thấy kết quả!");
                        }
                    }

                }
            })
        }

    });

    // End Handle filter column

    // Handle add column filter

    $("body").on("click", ".btn-add-filter-column", function() {

        $.ajax({
            url: "<?= base_url("table/get_filter_item_html") ?>",
            data: {
                project_id: "<?= $project->id ?>"
            },
            method: "post",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $(".group-filter-column").append(response?.data);
                }
            }
        })

    })

    // Handle cancel filter
    $("body").on("click", ".btn-cancel-filter", function() {
        $(`.sort-item`).prop("hidden", false);
        $(".total_filter").text(`0 kết quả`);

        $("#form-filter")[0].reset();

    })

    // Handle remove filter column item

    $("body").on("click", ".btn-remove-filter-column", function() {
        const parent = $(this).parents(".group-filter-column-item");
        parent.remove();
    })

    // Handle save filter
    $("body").on("click", ".btn-save-filter", function() {

        const payload = [];

        const group_filter_column_item = $(".group-filter-column-item");

        group_filter_column_item.each(function() {
            const item = {};

            const select_filter_logic = $(this).find("select[name='select-filter-logic']").find(":selected");
            const select_filter_fields = $(this).find("select[name='select-filter-fields']").find(":selected");
            const select_filter_condition = $(this).find("select[name='select-filter-condition']").find(":selected");
            const input_filter_value = $(this).find(".input-filter-value").not(":hidden");

            if (select_filter_logic.val() != null || undefined) {
                item.logic = select_filter_logic.val();
            }
            if (select_filter_fields.val() != null || undefined) {
                item.key = select_filter_fields.val();
            }
            if (select_filter_condition.val() != null || undefined) {
                item.condition = select_filter_condition.val();
            }
            if (input_filter_value.val() != null || undefined) {

                let value = input_filter_value.val();

                const type = select_filter_fields.attr("data-field-type");

                switch (type) {
                    case "people":
                        value = input_filter_value.find(":selected").attr("data-user-id");
                        break;
                    case "date":
                        value = value.split("-").reverse().join("-");
                        break;
                    case "percent":
                        value = value.replace("%", "");
                        break;
                    default:
                        break;
                }

                item.value = value;
                item.type = type;
            }

            if (Object.keys(item).length > 3) {
                payload.push(item);
            }

        })

        const payload_string = JSON.stringify(payload);

        if (payload.length > 0) {
            $.ajax({
                url: "<?= base_url("items/save_filter") ?>",
                method: "post",
                data: {
                    items_id: "<?= $project->id ?>",
                    json_filter_value: payload_string,
                    filter_value: payload
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success("Cập nhật dữ liệu thành công!");
                    }
                }
            })
        }

    })

    // Handle search task
    $(".input-search-task input").keyup(function() {
        const key = $(this).val().toLowerCase();
        $(".sortable .sort-item").each(function() {
            const titleMatch = $(this).find('.task-title .input-group-table input').val().toLowerCase().indexOf(key) > -1;
            const idMatch = $(this).find('.task-id .input-group-table input').val().toLowerCase().indexOf(key) > -1;
            $(this).toggle(titleMatch || idMatch);
        });
    });
</script>