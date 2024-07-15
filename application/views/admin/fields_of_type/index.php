<?php
$types_html = $this->Html_types_model->get_all();
?>

<div class="row py-3 px-4">
    <div class="col-4">
        <form action="<?= base_url('fields_of_type/add') ?>" method="post">
            <div class="form-group mt-3">
                <label for="" class="form-label">Type html</label>
                <select name="type_html" id="" class="form-select">
                    <?php foreach ($types_html as $type) : ?>

                        <option value="<?= $type->title ?>"><?= $type->title ?></option>

                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="" class="form-label">Title</label>
                <input type="text" name="title" class="form-control">
            </div>
            <div class="form-group mt-3">
                <label for="" class="form-label">Type</label>
                <select name="type_id" id="" class="form-select">
                    <?php foreach ($types as $type) : ?>

                        <option value="<?= $type->id ?>"><?= $type->title ?></option>

                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>
    <div class="col-8">
        <table id="field-of-type-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Type_id</th>
                    <th>Title</th>
                    <th>Type_html</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-data">
                <!-- <?php foreach ($fields_of_type as $field_of_type) : ?>
                    <tr>
                        <td><?= $field_of_type->id ?></td>
                        <td><?= $field_of_type->type_id ?></td>
                        <td><?= $field_of_type->title ?></td>
                        <td><?= $field_of_type->type_html ?></td>
                        <td>
                            <a href="<?= base_url() ?>fields_of_type/view/<?= $field_of_type->id ?>" class="btn  btn-sm btn-warning">Edit</a>
                            <a href="<?= base_url() ?>fields_of_type/delete/<?= $field_of_type->id ?>" onclick="return confirm('Delete?')" class="btn  btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?> -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" id="form">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal_title"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="text" name="id" hidden class="form-control">

                    <div class="form-group mt-3">
                        <label for="" class="form-label">Type html</label>
                        <select name="type_html_edit" id="" class="form-select">
                            <?php foreach ($types_html as $type) : ?>
                                <option value="<?= $type->title ?>"><?= $type->title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="" class="form-label">Title</label>
                        <input type="text" name="title_edit" class="form-control">
                    </div>
                    <div class="form-group mt-3">
                        <label for="" class="form-label">Type</label>
                        <select name="type_id_edit" id="" class="form-select">
                            <?php foreach ($types as $type) : ?>

                                <option value="<?= $type->id ?>"><?= $type->title ?></option>

                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btn_submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function fetchData() {
        $.ajax({
            url: "<?= base_url("fields_of_type/getall") ?>",
            dataType: "json",
            success: function(response) {
                if (response.data.length > 0) {
                    $("#field-of-type-table").DataTable({
                        data: response.data,
                        columns: [{
                                data: "id",
                            }, {
                                data: "type_id",
                            },
                            {
                                data: "title",
                            },
                            {
                                data: "type_html",
                            },
                            {
                                "render": function(data, type, row, meta) {
                                    return `<button class="edit_btn btn" data-id="${row.id}"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                <button class="delete_btn btn" data-id="${row.id}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        `;
                                }
                            },
                        ]
                    });
                }
            }
        })
    }

    // Get data to modal
    $(document).on('click', '.edit_btn', function() {
        const id = $(this).attr("data-id");

        $("input[name=id]").val(id);

        $.ajax({
            url: `<?php echo base_url(); ?>fields_of_type/view/${id}`,
            dataType: "json",
            method: "get",
            success: function(response) {
                if (response.success) {

                    $("select[name='type_html_edit']").val(response.data.type_html)
                    $("input[name=title_edit]").val(response.data.title);
                    $("select[name='type_id_edit']").val(response.data.type_id)

                    $("#formModal").modal('show');
                }
            },
            error: function(err) {
                console.log(err)
            }
        })
    })

    // Handle submit
    $("#btn_submit").click(function() {
        let payload = {};

        const type_html_edit = $("select[name='type_html_edit']").val()
        const title_edit = $("input[name=title_edit]").val();
        const type_id_edit = $("select[name='type_id_edit']").val()

        payload.type_html = type_html_edit;
        payload.title = title_edit;
        payload.type_id = type_id_edit;

        const id = $("input[name=id]").val();

        $.ajax({
            url: `<?php echo base_url(); ?>fields_of_type/update/${id}`,
            method: "post",
            dataType: "json",
            data: payload,
            success: function(response) {
                if (response.success) {
                    toastr.success("Cập nhật dữ liệu thành công!");
                    $("#formModal").modal('hide');
                    location.reload();
                }
            },
        });

    })

    // Handle delete
    $(document).on('click', '.delete_btn', function() {
        const id = $(this).attr('data-id');
        const deleteConfirm = confirm("Are you sure?");
        if (deleteConfirm) {
            $.ajax({
                url: `<?php echo base_url(); ?>fields_of_type/delete/${id}`,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success("Xóa dữ liệu thành công!");
                        location.reload();
                    }
                }
            })
        }
    })

    fetchData();
</script>