<style>
    .kanban-board {
        display: flex;
        justify-content: space-around;
        padding: 20px;
    }

    .kanban-column {
        flex: 1;
        margin: 0 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        margin-bottom: 20px;
    }

    .kanban-header {
        position: relative;
        width: 100%;
        background-color: inherit;
        text-align: center;
        padding: 5px;
        margin-bottom: 10px;
    }

    .kanban-header span {
        color: #fff;
        margin-bottom: 0;
        font-size: 25px;
    }

    .kanban-item {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 5px;
        margin: 10px;
        margin-bottom: 10px;
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .kanban-item:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .secondary {
        background-color: #6c757d;
    }

    .warning {
        background-color: #ffc107;
    }

    .danger {
        background-color: #dc3545;
    }

    .success {
        background-color: #28a745;
    }

    .task-status {
        width: 155px;
        background: #efeff1;
    }

    .task-status .badge {
        width: 0;
        transition: .3s;
        color: #333;
    }

    .task-status:hover .badge {
        width: 100%;
        color: #fff;
    }

    .kanban-header i.fa.fa-plus {
        display: none;
        position: absolute;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
        cursor: pointer;
    }

    .kanban-column:hover i.fa.fa-plus {
        display: inline-block;
        color: #fff;
    }

    .task-action {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        display: none;
    }

    .kanban-item:hover .task-action {
        display: block;
    }

    .dropdown-menu {
        z-index: 1000;
    }

    .input-group-table {
        width: 90% !important;
    }
</style>
<?php
$folder_id_url = $this->uri->segment(3);
$project_id_url = $this->uri->segment(4);
?>
<div class="row pt-3">
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
        <div class="kanban-board mt-5">
            <?php
            $columns = [
                'hoanthanh|success' => ['Hoàn thành', 'success'],
                'danglam|warning' => ['Đang làm', 'warning'],
                'chuahoanthanh|danger' => ['Chưa hoàn thành', 'danger'],
                'chuabatdau|secondary' => ['Chưa bắt đầu', 'secondary'],
            ]; ?>

            <?php foreach ($columns as $key => $option) : ?>

                <div class="kanban-column" ondrop="drop(event)" ondragover="allowDrop(event)" data-column="<?= $key ?>" data-status="<?= $option[0] ?>" data-color="<?= $option[1] ?>">
                    <div class="kanban-header <?= $option[1] ?>">
                        <span><?= $option[0] ?></span>
                        <i class="fa fa-plus createTask" data-column="<?= $key ?>"></i>
                    </div>
                    <?php foreach ($groups as $group) : ?>
                        <?php $tasks = $this->Items_model->get_child_items($group->id); ?>
                        <?php foreach ($tasks as $task) : ?>

                            <?php $status = ''; ?>
                            <?php foreach ($this->Items_model->get_all_meta($task->id) as $meta) : ?>
                                <?php $field = $this->Items_model->get_field_by_key($meta->key) ?>
                                <?php if ($field->type_html == 'status') : ?>
                                    <?php $status_parts = explode('|', $meta->value); ?>

                                    <?php $columns_parts = explode('|', $key); ?>

                                    <?php $status = $status_parts[1]; ?>
                                    <?php if ($status == $columns_parts[1]) : ?>
                                        <div class="kanban-item" data-id="<?= $task->id ?>" data-task-id="<?= $task->id ?>" draggable="true" ondragstart="drag(event)" id="<?= $meta->id ?>">
                                            <div class="task-title">
                                                <?php
                                                $data['value'] = $task->title;
                                                $data['data_id'] = $task->id;
                                                $data['disabled'] = false;
                                                $data['showMenu'] = false;
                                                $data['width'] = "150px;";
                                                $this->load->view("admin/views/input/text", $data);
                                                ?>
                                            </div>
                                            <div class="task-status">
                                                <span class="badge rounded-1 bg-<?= $columns_parts[1] ?>"> <?= $option[0] ?></span>
                                            </div>

                                            <div class="task-action">
                                                <div class="menu" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
                                                    <div class="item-menu">
                                                        <i class="fa fa-ellipsis-h"></i>
                                                    </div>
                                                </div>
                                                <ul class="dropdown-menu menu_list">
                                                    <li data-task-id="<?= $task->id ?>" class="btn-delete-task px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
                                                </ul>
                                            </div>

                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

</div>

<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="createForm" action="<?= base_url(); ?>items/add" method="post" class="mt-5">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ($fields) : ?>
                        <input type="text" name="parent_id" value="<?= $first_group_id->id;  ?>" hidden>
                        <input type="text" name="type_id" value="8" hidden>
                        <input type="text" name="user_id" value="<?= $this->session->userdata('user_id') ?>" hidden>

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">Title</label>
                            <input name="title" type="text" class="form-control">
                        </div>

                        <div class="form-group mb-4">

                            <label class="form-label fw-bold">Chọn nhóm</label>
                            <select name="parent_id" class="form-control">
                                <?php foreach ($groups as $group) : ?>
                                    <option value="<?= $group->id ?>"><?= $group->title ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php foreach ($fields as $key => $field) : ?>
                            <?php if ($field->type_html == 'people' || $field->type_html == 'dependenttask' || $field->type_html == 'connecttable') :  ?>
                                <input type="text" name="<?= isset($field->key) ? $field->key : time(); ?>" value="" hidden>
                            <?php else : ?>
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold"><?= $field->title ?></label>
                                    <?php
                                    $data['key'] = $field->key;
                                    $data['title'] = $field->title;
                                    $data['required'] = $field->required == 1 ? true : false;
                                    $this->load->view("/admin/views/inputform/" . $field->type_html, $data);
                                    ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <div class="form-group d-flex justify-content-center">
                        <button id="submitBtn" class="btn btn-primary">Thêm mới</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>



<script>
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("id", ev.target.id);
    }

    function drop(ev) {
        ev.preventDefault();
        var meta_id = ev.dataTransfer.getData("id");
        var value = ev.target.dataset.column;

        var draggedElement = document.getElementById(meta_id);
        if (ev.target.classList.contains('kanban-column')) {

            var status = ev.target.dataset.status;
            var color = ev.target.dataset.color;


            // Cập nhật nội dung của task-status
            var kanbanItem = document.getElementById(meta_id);
            if (kanbanItem) {
                var taskStatus = kanbanItem.querySelector('.task-status');
                if (taskStatus) {
                    taskStatus.innerHTML = '<span class="badge bg-' + color + '">' + status + '</span>';
                }
            }

            ev.target.appendChild(draggedElement);
            $.ajax({
                url: "<?= base_url() ?>admin/items/update_meta",
                method: "post",
                data: {
                    meta_id: meta_id,
                    value: value,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success("Cập nhật dữ liệu thành công!");
                    }
                }
            });
        }
    }
    $(document).ready(function() {

        $(document).on('change', '.task-title .input-group input', function() {
            const title = $(this).val().trim();

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
            });
        });

        $(document).on('click', '.createTask', function(ev) {
            ev.preventDefault();

            $('#createModal').modal('show');
            $("#createForm")[0].reset();

            var value = ev.target.dataset.column;

            let select_input = $('#createModal').find('.form-select');

            select_input.children('option').each(function() {
                var option_value = $(this).val();

                if (option_value === value) {
                    $(this).attr('selected', 'selected');
                } else {
                    $(this).removeAttr('selected');
                }
            });


        });

        $('#submitBtn').click(function(e) {

            e.preventDefault();

            const btn_submit_form = $(this);

            const data = $('#createForm').serializeArray();
            const formData = new FormData();

            const timeline = $(".timeline-form");


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
                    url: "<?= base_url(); ?>kanban/add",
                    method: "post",
                    processData: false,
                    contentType: false,
                    cache: false,
                    enctype: 'multipart/form-data',
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
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

                            let select_input = $('#createModal').find('select');

                            select_input.children('option:selected').each(function() {

                                var option_value = $(this).val();
                                var option_text = $(this).text();

                                console.log(option_value);
                                console.log(option_text);

                                var parts = option_value.split("|");
                                var color = parts[1];

                                var columnId = option_value;

                                var firstMeta = response.meta[0];
                                console.log(firstMeta);

                                var newTaskHtml = `
                                <div class="kanban-item" data-id="${response.item_id}" data-task-id="${response.item_id}" draggable="true" ondragstart="drag(event)" id="${firstMeta.id}">
                                    <div class="task-title">
                                        <div class="input-group input-group-table">
                                            <input type="text" placeholder="" data-meta-id="" data-id="${response.item_id}"  name="" value="${response.data.title}" class="form-control input-table text-truncate" />                                              
                                        </div>
                                    </div>
                                    <div class="task-status">
                                        <span class="badge rounded-1 bg-${color}"> ${option_text} </span>
                                    </div>
                                    <div class="task-action">
                                            <div class="menu" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
                                            <div class="item-menu">
                                                <i class="fa fa-ellipsis-h"></i>
                                            </div>
                                        </div>
                                        <ul class="dropdown-menu menu_list">
                                            <!-- <li data-task-id="${response.item_id}" class="btn-update px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>Sửa</li> -->
                                            <li data-task-id="${response.item_id}" class="btn-delete-task px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
                                        </ul>
                                    </div>
                                </div>
                            `;
                                $(`.kanban-column[data-column="${columnId}"]`).append(newTaskHtml);

                            });

                            $('#createModal').modal('hide');
                            btn_submit_form.html("<span>Thêm mới</span>");
                            toastr.success("Thêm công việc thành công!");
                        } else {
                            btn_submit_form.html("<span>Thêm mới</span>");
                            toastr.error(response.errors);
                        }
                    },
                });
            }
        });


        $(document).on('click', '.btn-delete-task', function() {
            var taskId = $(this).data('task-id');
            console.log(taskId);
            if (confirm("Bạn có chắc chắn muốn xóa công việc này không?")) {
                $.ajax({
                    url: "<?= base_url("admin/items/delete/") ?>" + taskId,
                    method: "post",
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            var taskItem = $(".kanban-item[data-id='" + taskId + "']");
                            taskItem.remove();
                            toastr.success("Xóa thành công");
                        }
                    }
                })
            } else {}
        });
    });
</script>