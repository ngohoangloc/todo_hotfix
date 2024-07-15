<div class="row pt-3" style="padding-left: 37px !important;">

    <?php if (isset($project)) : ?>
        <div class="col-12 col-lg-9">
            <input class="project-title fs-4" data-project-id="<?= $project->id; ?>" value="<?= $project->title; ?>" />
        </div>
        <div class="col-12 col-lg-3 d-flex justify-content-end align-items-center gap-2 mt-2">
            <?php $this->load->view("templates/admin/invite", ['item_id' => $project->id, 'is_owner' => $is_owner]) ?>
            <?php $this->load->view("templates/admin/logs", ['project_id' => $project->id]) ?>
        </div>

        <div class="col-12 my-3">
            <div class="form-actions">
                <div class="add-task-actions">
                    <!-- Search task form -->
                    <div class="input-search-task">
                        <div class="search-icon"><i class="fa fa-search" aria-hidden="true"></i></div>
                        <input type="text" placeholder="Tìm kiếm...">
                    </div>
                </div>

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
            </div>
        </div>

        <div class="group-list p-0">
            <?php foreach ($groups as $group) : ?>
                <?php if ($group->type_id == 5) : ?>
                    <!-- Group -->
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
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="col-12">
            <!-- Btn add group -->
            <?php if ($is_owner) : ?>
                <button data-project-id="<?= $project->id ?>" class="btn-add-group btn btn-sm btn-outline-secondary my-2"><i class="fa fa-plus"></i> <span>Thêm nhóm mới</span></button>
            <?php endif; ?>
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

    $(document).ready(function() {
        $("body").on("click", ".fiels_list_item", function() {
            const type_html = $(this).attr('data-type-html');
            const value = $(this).attr('data-value');
            const project_id = $(this).closest(".btn-add-fields").attr("data-id");

            $.ajax({
                url: "<?= base_url() ?>fields/add",
                method: "post",
                data: {
                    title: value,
                    items_id: project_id,
                    type_html
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        const {
                            field_html,
                            meta_html
                        } = response;

                        $(".task-item-header").find(".field-title:last").after(field_html);
                        $(".task-item").find(".task-meta:last").after(meta_html);

                        $(".task-item-header").css("width", "fit-content");
                        $(".group-item").css("width", Math.floor($(".task-item-header").width() + 52) + "px");
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

        // Handle meta change
        $('body').on('change', '.task-meta .input-group input', function() {

            const meta_id = $(this).attr("data-meta-id");

            switch ($(this).attr("type")) {
                case "file":
                    const files = $(this)[0].files;
                    const key = $(this).attr("name");
                    const item_id = <?= $project->id ?>;
                    const file_meta_input = $(this).parent().find(".file_meta_input");

                    let html_file = '';
                    const url = '<?= base_url() ?>';

                    const formData = new FormData();
                    formData.append("key", key);
                    formData.append("item_id", item_id);
                    formData.append("meta_id", meta_id);

                    for (let i = 0; i < files.length; i++) {
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
                                if (file_meta_input.find('label').length > 0) {
                                    file_meta_input.empty();

                                    file_meta_input.html('<span class="btn-add-file" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-plus"></i></span><div class = "file_meta_list"></div>');

                                }

                                const file_meta_list = file_meta_input.find(".file_meta_list");

                                file_meta_list.empty();

                                response.data.map(function(e, key) {

                                    if (key < 3) {
                                        html_file += '<div class="file_image_field file-image" data-file-type="' + e.type + '" data-file-title="' + e.title + '" data-file-path="' + url + e.path + '">';

                                        if ("pdf|doc|docx|xls|xlsx|ppt|pptx".split("|").includes(e.type)) {

                                            html_file += '<img src="' + url + '/assets/images/' + e.type + '.svg' + '" alt="file_icon">';

                                        } else {

                                            html_file += '<img src="' + url + e.path + '" alt="file_icon">';
                                        }

                                        html_file += '</div>';

                                    } else if (key == (response.data.length - 1)) {
                                        html_file += '<div class="extra-files-count" data-bs-toggle="dropdown" aria-expanded="false"> <span >+' +
                                            (response.data.length - 3) +
                                            ' < /span> </div>';
                                    }
                                });

                                html_file += '<ul class="dropdown-menu shadow" style="max-width: 290px; padding: 16px;"><div class="row">';

                                response.data.map(function(e, key) {
                                    html_file += '<div class="col-12 mb-2"><div class="meta_item_file">';

                                    if ("pdf|doc|docx|xls|xlsx|ppt|pptx".split("|").includes(e.type)) {

                                        html_file += '<img src="' + url + '/assets/images/' + e.type + '.svg' + '" alt="file_icon">';

                                    } else {

                                        html_file += '<img src="' + url + e.path + '" alt="file_icon">';
                                    }

                                    html_file += '<span class="text-truncate">' + e.title + '</span>';
                                    html_file += '<div class="btn-clear-file" data-file-id="' + e.id + '" data-meta-id="' + meta_id + '"><i class="fa fa-times"></i></div></div></div>';
                                });

                                html_file += '</div><div class = "mt-3"><label style = "font-size: 14px; cursor: pointer;" for="input_file_' + meta_id + '"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Tải lên</label></div></ul>';

                                file_meta_list.append(html_file);
                                toastr.success("Thêm file thành công!");
                            } else {
                                toastr.error("Có lỗi xảy ra, vui lòng thử lại sau!");
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

            const status_select = $(this);

            const meta_id = $(this).attr("data-id");
            const group_id = $(this).attr("data-group-id");

            const payload = {
                meta_id,
                group_id,
                type: "status",
                value: $(this).val(),
            }

            $.ajax({
                url: "<?= base_url() ?>admin/items/update_meta",
                method: "post",
                data: payload,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        const bg_old = status_select.attr("data-bg-color");

                        const bg_color = status_select.val().split("|")[1];

                        status_select.removeClass(bg_old);

                        status_select.addClass("bg-" + bg_color);

                        status_select.attr("data-bg-color", "bg-" + bg_color);

                        toastr.success("Cập nhật trạng thái thành công!");

                        $(`.progress-stacked[data-group-id='${group_id}']`).html(response.progress_html)
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    }
                }
            })
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
        $(".btn-add-group").click(function() {
            const group_list = $('.group-list');
            const items_id = $(this).attr("data-project-id");
            const title = "Nhóm mới";
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
                        $(".group-item").css("width", Math.floor($(".task-item-header").width() + 52) + "px");
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
        $("body").on("click", ".btn-clear-user", function(e) {
            const task_meta = $(this).parents('.task-meta');

            const payload = {
                meta_id: $(this).attr("data-meta-id"),
                project_id: $(this).attr("data-project-id"),
                value: $(this).attr("data-user-id"),
                type: "people_remove"
            }

            $.ajax({
                url: "<?= base_url() ?>admin/items/update_meta",
                method: "post",
                data: payload,
                dataType: "json",
                success: function(response) {
                    if (response.success) {

                        task_meta.empty();
                        task_meta.append(response.data);

                        toastr.success("Thay đổi đã được cập nhật!");
                    }
                }
            })
        });

        // Handle add user in meta item
        $("body").on('click', '.user_list_item', function() {
            const task_meta = $(this).parents('.task-meta');

            const payload = {
                meta_id: $(this).attr("data-meta-id"),
                project_id: $(this).attr("data-project-id"),
                value: $(this).attr("data-user-id"),
                type: "people_add"
            }

            $.ajax({
                url: "<?= base_url() ?>admin/items/update_meta",
                method: "post",
                data: payload,
                dataType: "json",
                success: function(response) {
                    if (response.success) {

                        task_meta.empty();
                        task_meta.append(response.data);

                        toastr.success("Thay đổi đã được cập nhật!");
                    }
                }
            })
        });
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
                url: "<?= base_url("items/sort") ?>",
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
                                sort_item.remove();
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

    // Handle import file
    $("body").on("change", ".input-file", function() {

        const loading_html = `<span>Đang tải lên...</span>`;

        const fileToUpload = $(this).prop('files')[0];

        const input_file = $(this);

        input_file.prev().html(loading_html);

        if (fileToUpload) {
            const group_id = $(this).attr("data-group-id");
            const group_list_item = $(".group-list").find(`.group-list-item[data-group-id='${group_id}']`);
            const sortable = group_list_item.find(`.group-item .sortable`);

            const project_id = $(this).attr("data-project-id");

            const formData = new FormData();
            formData.append("file", fileToUpload);
            formData.append("group_id", group_id);
            formData.append("project_id", project_id);

            $.ajax({
                url: "<?= base_url("items/import_timetable") ?>",
                method: "post",
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                data: formData,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        input_file.prev().html(`<i class="fa fa-download" aria-hidden="true"></i> Nhập dữ liệu`);

                        const group_id = response.group;
                        const group_html = response.group_html;

                        sortable.html(group_html);
                        toastr.success("Nhập dữ liệu thành công!");
                    }
                }
            })
        }
    });

    $(document).ready(function() {

        var options = {
            html: true,
            title: `<div class='d-flex justify-content-between'>
        <span>Bộ lọc nhanh</span>
    </div>`,
            placement: "bottom",
            content: $('.filter-column-body'),
        };

        var filterColumnEl = $('.btn-filter-column');

        var popover = new bootstrap.Popover(filterColumnEl, options);

    });

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
                    type_filter: "table",
                    "type_filter": "timetable"
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
</script>