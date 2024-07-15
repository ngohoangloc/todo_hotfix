<style>
    .form-group-field {
        transition: .3s;
    }
</style>
<?php
$folder_id_url = $this->uri->segment(3);
$project_id_url = $this->uri->segment(4);
?>
<div class="row pb-4 px-3 pt-3">
    <div class="col-9">
        <input class="project-title fs-4" data-project-id="<?= $project->id; ?>" value="<?= $project->title; ?>" />
    </div>
    <div class="col-3">
        <?php $this->load->view("templates/admin/logs", ['project' => $project]) ?>
    </div>
    <div class="mb-3">
        <?php $this->load->view("templates/admin/board-toolbar", ['project_id' => $project->id, 'parent_id' => $project->parent_id, 'folder_id_url' => $folder_id_url]) ?>
    </div>

    <div class="mb-2">
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editFormModal">Cập nhật form</button>
    </div>

    <div class="col-12 col-xl-7 bg-white shadow rounded px-5 py-3 m-auto border-top border-5 border-primary">
        <div class="project_title w-100 text-truncate" data-project-id="<?= $project->id ?>">
            <span class="fs-3 text-center"><?= $project->title ?></span>
        </div>
        <form id="form-add-item" method="post" action="<?= base_url(); ?>form/add" class="mt-5" enctype="multipart/form-data">
            <?php $project = $this->Items_model->find_by_id($project->id)  ?>

            <?php if ($fields) : ?>
                <input type="text" name="type_id" value=<?= $project->type_id == 31 ? 35 : 8 ?> hidden>
                <input type="text" name="user_id" value="<?= $this->session->userdata('user_id') ?>" hidden>

                <div class="row">
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">Tên công việc</label>
                        <div class="input-group" data-title="Tên công việc" required data-key="title">
                            <input name="title" type="text" class="form-control">
                        </div>
                    </div>
                    <?php if ($project->type_id != 31) : ?>
                        <div class="form-group mb-4">
                            <?php $groups = $this->Items_model->get_groups_by_owner($project->id) ?>
                            <label class="form-label fw-bold">Chọn nhóm</label>
                            <select name="parent_id" class="form-control">
                                <?php foreach ($groups as $group) : ?>
                                    <option value="<?= $group->id ?>"><?= $group->title ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else : ?>
                        <input type="text" name="parent_id" value=<?= $project->id ?> hidden>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <?php foreach ($fields as $key => $field) : ?>
                        <?php if ($field->type_html == 'people' || $field->type_html == 'dependenttask' || $field->type_html == 'connecttable') :  ?>
                            <input type="text" name="<?= isset($field->key) ? $field->key : time(); ?>" value="" hidden>
                        <?php else : ?>
                            <div class="form-group form-group-field mb-4 <?= $field->class ?>" data-field-id="<?= $field->id ?>" data-class="<?= $field->class ?>">
                                <label class="form-label fw-bold" data-field-id="<?= $field->id ?>"><?= $field->title ?></label>
                                <?php
                                $data['key'] = $field->key;
                                $data['title'] = $field->title;
                                $data['required'] = $field->required == 1 ? true : false;
                                $this->load->view("/admin/views/inputform/" . $field->type_html, $data);
                                ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="form-group d-flex justify-content-center">
                <button class="btn btn-primary btn-submit-form">
                    <span>Thêm mới</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editFormModal" tabindex="-1" aria-labelledby="editFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row w-100">
                    <div class="col-8">
                        <input type="text" class="border-0 w-100" disabled value="<?= $project->title ?>">
                    </div>
                    <div class="col-4 text-center">
                        <span class="btn-share-form btn btn-sm btn-outline-primary" data-url="<?= base_url("share_form/" . $project->id) ?>">Chia sẻ link <i class="fa fa-link" aria-hidden="true"></i></span>


                        <?php

                        $key = $this->config->item("image_key");
                        $text = $project->id;

                        // Use library String encrypt
                        $id_encrypt = $this->stringencryption->encryptString($text, $key);

                        ?>
                        <input type="text" id="text-to-copy" value="<?= base_url("share_form/" . $id_encrypt) ?>" style="height:0; position:absolute; z-index: -1; opacity: 0;">
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <?php if ($fields) : ?>
                    <?php foreach ($fields as $key => $field) : ?>
                        <?php if ($field->type_html != 'people') :  ?>
                            <div class="card mb-4">
                                <div class="card-header bg-info text-light">
                                    Cập nhật cột <?= $field->title ?>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Tên cột</label>
                                        <input class="input-form-update form-control" type="text" value="<?= $field->title ?>" data-field-update="title" data-field-id="<?= $field->id ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Class</label>
                                        <input class="input-form-update form-control" type="text" value="<?= $field->class ?>" data-field-update="class" data-field-id="<?= $field->id ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Width</label>
                                        <input class="input-form-update form-control" type="text" value="<?= $field->width ?>" data-field-update="width" data-field-id="<?= $field->id ?>">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <input class="field-checkbox" type="checkbox" data-field-id="<?= $field->id ?>" data-key="<?= $field->key ?>"> required
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".btn-submit-form").click(function(event) {
            event.preventDefault();

            const btn_submit_form = $(this);

            const data = $('#form-add-item').serializeArray();
            const formData = new FormData();

            const inputFile = $('input[data-key=files]');
            const filesUpload = $('input[name=file_upload]');

            let validate = [];

            data.map((item) => {

                const input = $(`.input-group[data-key='${item.name}']`);

                if (input.length > 0) {
                    const isRequired = $(`.input-group[data-key='${item.name}']`).attr("required");
                    const title = $(`.input-group[data-key='${item.name}']`).attr("data-title");
                    const input = $(`.input-group[data-key='${item.name}']`).find("input");

                    if (isRequired && input.val().length == 0) {
                        validate.push(`Vui lòng nhập ${title}!`);
                    }
                }

                formData.append(item.name, item.value);

            })

            if (validate.length > 0) {
                toastr.error(validate[0]);
            } else {
                const loading_html = `<img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/YouTube_loading_symbol_3_%28transparent%29.gif" width="25" alt="">`;

                $(this).html(loading_html);

                $.ajax({
                    url: "<?= base_url(); ?>form/add",
                    method: "post",
                    processData: false,
                    contentType: false,
                    cache: false,
                    enctype: 'multipart/form-data',
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            // Handle add file
                            if (inputFile.length > 0 && filesUpload.length > 0) {
                                inputFile.each(function() {
                                    const inputFileUpload = $(this).next()[0].files;

                                    if (inputFileUpload.length > 0) {
                                        const formData = new FormData();

                                        formData.append("key", $(this).attr("name"));
                                        formData.append("item_id", response.item_id);

                                        for (let i = 0; i < inputFileUpload.length; i++) {
                                            formData.append(`files[]`, inputFileUpload[i]);
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
                                        });
                                    }
                                })
                            }

                            btn_submit_form.html("<span>Thêm mới</span>");
                            toastr.success("Thêm công việc thành công!");
                            $("#form-add-item").reset();

                        } else {
                            toastr.error(response.errors);
                            btn_submit_form.html("<span>Thêm mới</span>");
                        }
                    },
                });
            }
        })

    })
    // Handle rename field
    $("body").on("change", ".form-group .input-form-update", function() {
        const id = $(this).attr("data-field-id");
        const field_update = $(this).attr("data-field-update");
        const title = $(this).val();

        const payload = {
            [field_update]: title
        };

        $.ajax({
            url: "<?= base_url() ?>fields/update/" + id,
            method: "post",
            data: payload,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    toastr.success("Cập nhật dữ liệu thành công!");

                    switch (field_update) {
                        case "title":
                            $(`.form-label[data-field-id='${id}']`).text(title);
                            break;
                        case "class":
                            const old_class = $(`.form-group-field[data-field-id='${id}']`).attr("data-class");
                            $(`.form-group-field[data-field-id='${id}']`).removeClass(old_class);
                            $(`.form-group-field[data-field-id='${id}']`).addClass(title);
                            break;
                        default:
                            break;
                    }

                }
            }
        })

    })
    // Handle check required
    $("body").on("change", ".field-checkbox", function() {
        const id = $(this).attr("data-field-id");
        const key = $(this).attr("data-key");

        const checked = $(this).is(":checked");

        let required = 0;

        if (checked) {
            required = 1;
            $(`.input-group[data-key='${key}']`).attr("required", true);

        } else {
            required = 0;
            $(`.input-group[data-key='${key}']`).attr("required", false);
        }

        $.ajax({
            url: "<?= base_url() ?>fields/update/" + id,
            method: "post",
            data: {
                required
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    toastr.success("Cập nhật dữ liệu thành công!");
                }
            }
        })

    })

    $("body").on("click", ".btn-share-form", function() {

        $(this).parent().find("#text-to-copy").select();
        document.execCommand("copy");

        toastr.success("Đã copy vào bộ nhớ!");
    });
</script>