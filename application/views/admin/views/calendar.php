<?php
$folder_id_url = $this->uri->segment(3);
$project_id_url = $this->uri->segment(4);
?>
<div class="row">
    <div class="col-md-10">
        <input class="project-title fs-4" data-project-id="<?= $project->id; ?>" value="<?= $project->title; ?>" />
    </div>
    <div class="col-md-2">
        <?php $this->load->view("templates/admin/logs", ['project' => $project]) ?>
    </div>
    <div class="mb-3">
        <?php $this->load->view("templates/admin/board-toolbar", ['project_id' => $project->id, 'parent_id' => $project->parent_id, 'folder_id_url' => $folder_id_url]) ?>
    </div>

    <div class="container-fluid">

        <div id='calendar'></div>

    </div>

    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="<?= base_url(); ?>calendar/add" method="post" class="mt-5" id="form-add-item">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= $project->title ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php if ($fields) : ?>
                            <input type="text" name="type_id" value="8" hidden>
                            <input type="text" name="user_id" value="<?= $this->session->userdata('user_id') ?>" hidden>

                            <div class="row">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">Tên công việc</label>
                                    <div class="input-group" data-title="Tên công việc" required data-key="title">
                                        <input name="title" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <?php $groups = $this->Items_model->get_groups_by_owner($project->id) ?>

                                    <label class="form-label fw-bold">Chọn nhóm</label>
                                    <select name="parent_id" class="form-control">
                                        <?php foreach ($groups as $group) : ?>
                                            <option value="<?= $group->id ?>"><?= $group->title ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
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
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group d-flex justify-content-center">
                            <button class="btn btn-primary btn-submit-form">Thêm mới</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="<?= base_url(); ?>calendar/update" method="post" class="mt-5">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= $project->title ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php if ($fields) : ?>
                            <input type="text" name="id" hidden>
                            <input type="text" name="user_id" value="<?= $this->session->userdata('user_id') ?>" hidden>

                            <div class="row">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">Tên công việc</label>
                                    <div class="input-group" data-title="Tên công việc" required data-key="title">
                                        <input name="title" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <?php $groups = $this->Items_model->get_groups_by_owner($project->id) ?>

                                    <label class="form-label fw-bold">Chọn nhóm</label>
                                    <select name="parent_id" class="form-control">
                                        <?php foreach ($groups as $group) : ?>
                                            <option value="<?= $group->id ?>"><?= $group->title ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
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
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group d-flex justify-content-center">
                            <button class="btn btn-primary">Cập nhật</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var screenHeight = window.innerHeight;
        var desiredHeight = screenHeight * 0.7;
        var events = [];
        var item_id = <?= $project->id ?>;

        $.ajax({
            url: `<?= base_url("calendar/events/") ?>${item_id}`,
            type: 'GET',
            dataType: "json",
            success: function(response) {
                response.data.forEach(item => {
                    if (item.value != '') {
                        var event = {
                            title: item.title,
                            start: item.value,
                            end: item.value,
                            id: item.id
                        };
                        events.push(event);
                    }
                });

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: desiredHeight,
                    firstDay: 1,
                    events: events,
                    locale: 'vi',

                    dateClick: function(info) {
                        $('input[type="date"]').val(info.dateStr);
                        $('#createModal').modal('show');
                    },

                    eventClick: function(info) {

                        var eventObj = info.event;

                        $.ajax({
                            url: `<?= base_url("items/") ?>${eventObj.id}`,
                            type: 'GET',
                            dataType: "json",
                            success: function(response) {

                                console.log(response.data);

                                $('#updateModal').find('input[name="title"]').val(eventObj.title);
                                $('#updateModal').find('input[name="id"]').val(eventObj.id);

                                response.data.forEach(meta => {
                                    if (meta.value != null)
                                        $('#updateModal').find('input[name="' + meta.key + '"]').val(meta.value);
                                });

                                $('#updateModal').modal('show');
                            }
                        });
                    }
                });
                calendar.render();
            }
        });

        $('#updateModal form').submit(function(event) {
            event.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                dataType: 'json',
                data: formData,
                success: function(response) {
                    console.log('Update successful:', response);

                    $('#updateModal').modal('hide');
                    location.reload();

                },
                error: function(xhr, status, error) {
                    console.error('Update failed:', error);
                }
            });
        });


        $(".btn-submit-form").click(function(event) {
            event.preventDefault();

            const btn_submit_form = $(this);

            const data = $('#form-add-item').serializeArray();
            let formData = new FormData();

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
                    url: "<?= base_url(); ?>calendar/add",
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
                            };
                            location.reload();

                        } else {
                            toastr.error(response.errors);
                            btn_submit_form.html("<span>Thêm mới</span>");
                        }
                    },
                });
            }
        })
    });
</script>