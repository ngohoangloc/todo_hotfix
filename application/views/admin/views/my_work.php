<div class="row pt-3" style="padding-left: 37px !important;">
    <?php if (isset($items) && count($items) > 0) : ?>
        <?php foreach ($items as $project) : ?>
            <div class="col-md-8">
                <input class="project-title fs-4" data-project-id="<?= $project->id; ?>" value="<?= $project->title; ?>" />
            </div>

            <?php foreach ($project->groups as $group) : ?>
                <?php if ($group->type_id == 5) : ?>
                    <!-- Group -->
                    <div class="group-list-item mb-3" data-group-id="<?= $group->id ?>">
                        <div class="group-item-title mb-3">
                            <div class="menu_hover" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
                                <div class="group-item-menu">
                                    <i class="fa fa-ellipsis-h"></i>
                                </div>
                            </div>
                            <ul class="dropdown-menu menu_hover_list">
                                <li class="btn-rename px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> Đổi tên nhóm</li>
                                <li data-id="<?= $group->id ?>" class="btn-delete-group px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
                            </ul>
                            <span class="btn-collapse" style="font-size: 14px;" data-group-id="<?= $group->id ?>" data-bs-toggle="collapse" href="#collapse-group-<?= $group->id ?>" aria-controls="collapse-group-<?= $group->id ?>">
                                <i class="fa fa-chevron-down text-primary" aria-hidden="true"></i>
                            </span>

                            <input type="text" name="group-name" data-id="<?= $group->id ?>" value="<?= $group->title; ?>" class="text-primary group-item-title-input" />

                            <div style="display: inline; text-align: end;">
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-add-member" data-bs-toggle="modal" data-bs-target="#add_member_to_group_modal" data-group-id="<?= $group->id ?>">
                                    + Thành viên
                                </button>
                            </div>
                        </div>
                        <div id="collapse-group-<?= $group->id ?>" class="collapse show group-item">
                            <!-- Task header -->
                            <div class="d-flex task-item-header" style="margin: 0 25px;">
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
                                <?php foreach ($project->fields as $field) : ?>
                                    <div class="field-title" style="width: <?= $field->width ?>;" data-field-id="<?= $field->id; ?>">
                                        <?php
                                        $data['key'] = $field->key;
                                        $data['value'] = $field->title;
                                        $data['data_id'] = $field->id;
                                        $data['disabled'] = true;
                                        $data['showMenu'] = true;
                                        $data['showBtnClear'] = false;
                                        $data['placeholder'] = "";
                                        $data['width'] = $field->width;
                                        $this->load->view("admin/views/input/text", $data);
                                        ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- task of group -->
                            <div class="sortable">
                                <?php foreach ($group->tasks as $task) : ?>
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
                                                <div class="header-title" style="width: 380px;">
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
                                                <?php foreach ($project->fields as $field) : ?>
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
                                            </div>
                                            <!-- task of group -->
                                            <?php foreach ($this->Items_model->get_child_items($task->id) as $subitem) : ?>
                                                <div class="task-item d-flex">
                                                    <div class="menu_hover" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
                                                        <div class="task-item-menu">
                                                            <i class="fa fa-ellipsis-h"></i>
                                                        </div>
                                                    </div>
                                                    <ul class="dropdown-menu menu_hover_list">
                                                        <li data-id="<?= $subitem->id ?>" class="btn-delete-task px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
                                                    </ul>

                                                    <div class="stt" data-id="<?= $subitem->id; ?>">
                                                        <input type="checkbox" value="" />
                                                    </div>
                                                    <!-- Task title -->
                                                    <div class="task-title border-end" style="width: 380px;">
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
                                                        <div class="task-meta border-end" style="width: <?= $field->width ? $field->width : '' ?>" data-field-id="<?= $field->id ?>">
                                                            <?php
                                                            $data['key'] = $meta->key;
                                                            $data['value'] = $meta->value;
                                                            $data['data_id'] = $subitem->id;
                                                            $data['meta_id'] = $meta->id;
                                                            $data['showBtnClear'] = true;
                                                            $data['showMenu'] = false;
                                                            $data['placeholder'] = "";
                                                            $data['width'] = $field->width;
                                                            $this->load->view("admin/views/input/" . $field->type_html, $data);
                                                            ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                    <!-- End Item meta -->
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- End add task btn -->
                            <div class="d-flex">
                                <div style="width: calc(380px + 60px);"></div>
                                <?php
                                $status = [
                                    ['hoanthanh', 'success'],
                                    ['danglam', 'warning'],
                                    ['chuabatdau', 'secondary'],
                                    ['chuahoanthanh', 'danger']
                                ];
                                ?>

                                <?php if (isset($tasks[0])) : ?>
                                    <?php foreach ($this->Items_model->get_all_meta($tasks[0]->id) as $key => $meta) : ?>
                                        <?php $field = $this->Items_model->get_field_by_key($meta->key); ?>
                                        <div class="task-meta" style="width: <?= $field->width ? $field->width : '' ?>;" data-field-id="<?= $field->id ?>">
                                            <?php if ($field->type_html == "status") : ?>
                                                <div class="progress-stacked">
                                                    <?php foreach ($status as $st) : ?>
                                                        <?php $percent = $this->Items_model->get_percent($group->id, $field->key, $st[0]); ?>
                                                        <?php if ($percent[0] > 0) : ?>
                                                            <div class="progress" data-bs-toggle="tooltip" data-placement="top" data-bs-title="<?= $percent[1]  ?>/<?= count($tasks) ?>" role="progressbar" aria-label="Tien do" aria-valuenow="<?= ceil($percent[0]); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= ceil($percent[0]); ?>%;">
                                                                <div class="progress-bar bg-<?= $st[1]; ?>"></div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else : ?>
                                                <div style="width: <?= $field->width; ?>"></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="text-center">
            <img width="200" src="https://static.vecteezy.com/system/resources/previews/005/006/031/non_2x/no-result-data-document-or-file-not-found-concept-illustration-flat-design-eps10-modern-graphic-element-for-landing-page-empty-state-ui-infographic-icon-etc-vector.jpg" alt="">
            <div>Bạn chưa có công việc nào!</div>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title file-name"></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="image-preview-modal">
                    <img src="" class="image-preview" alt="image-preview">
                </div>

                <div class="image-info">
                    <li class="btn-download-file-modal dropdown-item">
                        <i class="fa fa-download" aria-hidden="true"></i> Tải xuống
                    </li>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".task-title").children(".input-group").each(function() {
            $(this).children("input").change(function() {
                const payload = {
                    title: $(this).val(),
                }
                console.log(payload);
                $.ajax({
                    url: "<?= base_url() ?>admin/items/update/" + $(this).attr('data-id'),
                    method: "post",
                    data: payload,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    }
                })
            })
        })

        // Handle meta change
        $(".task-meta").children(".input-group").each(function() {
            if ($(this).children("input")) {
                $(this).children("input").change(function() {
                    switch ($(this).attr("type")) {
                        case "file":
                            const files = $(this)[0].files;
                            const key = $(this).attr("name");
                            const item_id = <?= $project->id ?>;
                            const meta_id = $(this).attr("data-meta-id");

                            const formData = new FormData();
                            formData.append("key", key);
                            formData.append("item_id", item_id);
                            formData.append("meta_id", meta_id);

                            for (let i = 0; i < files.length; i++) {
                                formData.append('files[]', files[i]);
                            }

                            $.ajax({
                                method: "post",
                                processData: false,
                                contentType: false,
                                cache: false,
                                enctype: 'multipart/form-data',
                                url: "<?= base_url("file/upload") ?>",
                                data: formData,
                                dataType: "json",
                                success: function(response) {
                                    if (response.success) {
                                        location.reload();
                                    }
                                },
                                complete: function() {
                                    toastr.success("Thêm file thành công!");
                                }
                            })

                            break;

                        case "text":
                        case "date":
                            $.ajax({
                                url: "<?= base_url() ?>admin/items/update_meta",
                                method: "post",
                                data: {
                                    meta_id: $(this).attr("data-meta-id"),
                                    value: $(this).val(),
                                },
                                dataType: "json",
                                success: function(response) {
                                    if (response.success) {
                                        toastr.success("Cập nhật dữ liệu thành công!");
                                    }
                                }
                            });

                            break;

                        default:
                            break;
                    }
                })
            }
            if ($(this).children("select")) {

                const status_select = $(this).children("select");

                $(this).children("select").change(function() {
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
                                const bg_old = status_select.attr("data-bg-color");

                                const bg_color = status_select.val().split("|")[1];

                                status_select.removeClass(bg_old);
                                status_select.addClass("bg-" + bg_color);

                                status_select.attr("data-bg-color", "bg-" + bg_color);

                                toastr.success("Cập nhật trạng thái thành công!");

                                location.reload();
                            }
                        }
                    })
                })
            }
            if ($(this).children("textarea")) {
                $(this).children("textarea").change(function() {
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
                                // location.reload();
                            }
                        }
                    })
                })
            }
        })

        // Handle checkall task
        $("body").on("click", ".checkall_btn input", function() {
            if ($(this).is(":checked")) {

                const parent = $(this).parents(".group-list-item");
                const input_children = parent.find(".stt input");

                input_children.each(function() {
                    $(this).prop("checked", true);
                })

                const checked_length = $(".stt input:checked").length;

                $(".batch-actions").css("visibility", "visible")

                $(".batch-actions-number span").text(checked_length + " đã chọn");

            } else {
                $(".stt input").each(function() {
                    $(this).prop("checked", false);
                })
                $(".batch-actions").css("visibility", "hidden")
            }

        })
    });
</script>