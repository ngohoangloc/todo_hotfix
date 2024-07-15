<?php
$types = [
    "text",
    "number",
    "file",
    "select",
    "date",
    "status",
    "checkbox",
    "items",
    "textarea",
    "user",
    "timeline",
]
?>
<div class="row py-5">
    <div class="col-10 m-auto">
        <div class="row">
            <div class="col-5">
                <button id="add_btn" class="btn btn-success">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
            <div class="col-12">
                <table id="fields-table" class="table mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Key</th>
                            <th>Title</th>
                            <th>Class</th>
                            <th>Width</th>
                            <th>Type html</th>
                            <th>Required</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" id="form">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal_title"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="errors" class="text-danger"></div>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" name="id" hidden class="form-control">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Key</label>
                                <input type="text" name="key" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Class</label>
                                <input type="text" name="class" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Width</label>
                                <input type="text" name="width" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Item id</label>
                                <input type="text" name="items_id" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Type html</label>
                                <select name="type_html" id="select_type_html" class="form-select">
                                    <option value="" selected disable>---- Choose type html ----</option>
                                    <?php foreach ($types as $item) : ?>
                                        <option value="<?= $item ?>"><?= $item ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Require</label>
                                <input type="checkbox" name="required">
                            </div>
                        </div>
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

<!-- Script -->
<script>
    let action = "";
    // Show modal
    $('#add_btn').click(function() {
        clear();
        action = "Add";
        $(".modal-title").text(action);
        $("#formModal").modal('show');
    })
    // Edit button
    $(document).on('click', ".edit_btn", function() {
        clear();
        action = "Edit";
        const id = $(this).attr("data-id");
        $(".modal-title").text(action);
        $("#formModal").modal('show');
        $.ajax({
            url: `<?php echo base_url() ?>fields/fetch_by_id/${id}`,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("input[name='id']").val(response.data.id);
                    $("input[name='key']").val(response.data.key);
                    $("input[name='title']").val(response.data.title);
                    $("input[name='class']").val(response.data.class);
                    $("input[name='width']").val(response.data.width);
                    $("input[name='items_id']").val(response.data.items_id);
                    // handle check
                    response.data.required == 0 && $("input[name='required']").prop("checked", true);
                    // handle select option
                    $("#select_type_html > option").each(function() {
                        this.value == response.data.type_html && $(this).prop("selected", true);
                    })

                    $("#formModal").modal('show');
                }
            }
        })
    })
    // Delete button
    $(document).on('click', ".delete_btn", function() {
        const id = $(this).attr("data-id");
        const deleteField = confirm("Delete?");

        if (deleteField) {
            $.ajax({
                url: `<?php echo base_url() ?>fields/delete/${id}`,
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    fetchData();
                }
            })
        }
    })

    // Submit btn
    $("#btn_submit").click(function() {
        const payload = {};

        const id = $("input[name='id']").val();
        const key = $("input[name='key']").val();
        const title = $("input[name='title']").val();
        const classInput = $("input[name='class']").val();
        const width = $("input[name='width']").val();
        const items_id = $("input[name='items_id']").val();
        const type_html = $("#select_type_html").find(":selected").text();
        const required = $("input[name='required']").is(":checked") ? 0 : 1;

        payload.key = key;
        payload.title = title;
        payload.class = classInput;
        payload.width = width;
        payload.type_html = type_html;
        payload.items_id = items_id;
        payload.required = required;

        switch (action) {
            case "Add":
                $.ajax({
                    url: `<?php echo base_url(); ?>fields/add`,
                    method: "post",
                    data: payload,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            fetchData();
                            clear();
                            $("#formModal").modal('hide');
                        }
                    }
                })
                break;
            case "Edit":
                $.ajax({
                    url: `<?php echo base_url(); ?>fields/update/${id}`,
                    method: "post",
                    data: payload,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            fetchData();
                            clear();
                            $("#formModal").modal('hide');
                        }
                    }
                })
                break;
            default:
                break;
        }
    })

    //  Fetch Data
    function fetchData() {
        $.ajax({
            url: "<?php echo base_url(); ?>fields/fetchFields",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#fields-table").DataTable({
                        "bDestroy": true,
                        data: response.data,
                        columns: [{
                                data: "null",
                                "render": function(data, type, row, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                data: "key"
                            },
                            {
                                data: "title"
                            },
                            {
                                data: "class"
                            },
                            {
                                data: "width"
                            },
                            {
                                data: "type_html"
                            },
                            {
                                "render": function(data, type, row, meta) {
                                    return row.required == 0 ? '<i class="fa fa-check text-success" aria-hidden="true"></i>' : '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                                }
                            },
                            {
                                data: "created_at"
                            },
                            {
                                "render": function(data, type, row, meta) {
                                    return `<button class="edit_btn btn" data-id="${row.id}"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                <button class="delete_btn btn" data-id="${row.id}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        `;
                                }
                            },
                        ]
                    })
                }
            }
        })
    }
    fetchData();

    function clear() {
        $("#form")[0].reset();
    }
</script>