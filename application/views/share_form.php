<div class="row py-5 m-0" style="background: #edebf2; height: 100vh; overflow-y: scroll;">
    <div class="col-5 bg-white shadow rounded px-5 py-3 m-auto border-top border-5 border-primary">
        <div class="mb-2">
            <img width="100%" src="<?= base_url("assets/images/form_image.png") ?>" alt="">
        </div>

        <div class="project_title w-100 mt-3" data-project-id="<?= $project->id ?>">
            <h4 class=""><?= $project->title ?></h4>
        </div>

        <form id="form-add-item" method="post" action="<?= base_url(); ?>form/add" class="mt-5" enctype="multipart/form-data">

            <?php if ($fields) : ?>
                <input type="text" name="type_id" value=<?= $project->type_id == 31 ? 35 : 8 ?> hidden>
                <input type="text" name="user_id" value="0" hidden>

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
                        <?php if ($field->type_html == 'people') :  ?>
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
                <div class="form-group d-flex justify-content-center">
                    <button class="btn btn-primary btn-submit-form">
                        <span>Thêm mới</span>
                    </button>
                </div>
            <?php else : ?>
                <div class="text-center text-primary">Không tìm thấy dữ liệu!</div>
            <?php endif; ?>
        </form>
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
                    url: "<?= base_url(); ?>share_form/add",
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
                            toastr.success("Thêm dữ liệu thành công!");
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
</script>