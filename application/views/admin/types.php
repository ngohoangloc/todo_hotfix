<div class="row py-5">
    <div class="col-12">
        <div class="row">
            <div class="col-5">
                <button id="add_btn" class="btn btn-success">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
            <div class="col-12">
                <table id="types-table" class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <!-- <tbody>
                        <?php
                        $index = 1;
                        foreach ($types as $type) : ?>
                            <tr>
                                <td><?= $index++ ?></td>
                                <td><?= $type->title ?></td>
                                <td><?= $type->slug ?></td>
                                <td><?= $type->created_at ?></td>
                                <td>
                                    <button class="edit_btn btn" data-id="<?php echo $type->id ?>">Edit</button>
                                    <a href="admin/types/delete/<?= $type->id ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody> -->
                </table>
            </div>
        </div>
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
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Title</label>
                        <input type="text" name="type_title" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control">
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
        action = "Add";
        $("#modal_title").text(action);
        $("#formModal").modal('show');
    })

    // Get data to modal
    $(document).on('click', '.edit_btn', function() {
        action = "Update";

        $("#modal_title").text(action);
        const id = $(this).attr("data-id");

        $.ajax({
            url: `<?php echo base_url(); ?>/types/fetch_by_id/${id}`,
            dataType: "json",
            method: "get",
            success: function(response) {
                $("input[name=id]").val(response.id);
                $("input[name=title]").val(response.title);
                $("input[name=slug]").val(response.slug);
                $("#formModal").modal('show');
            },
            error: function(err) {
                console.log(err)
            }
        })
    })

    // Handle submit
    $("#btn_submit").click(function() {
        let payload = {};

        const title = $("input[name=type_title]").val();
        const slug = $("input[name=slug]").val();

        payload.title = title;
        payload.slug = slug;

        switch (action) {
            case "Add":

                if (Object.keys(payload).length > 0) {
                    $.ajax({
                        url: `<?php echo base_url(); ?>admin/types/add`,
                        method: "post",
                        dataType: "json",
                        data: payload,
                        success: function(response) {
                            if (!response.success) {
                                console.log(response.errors.split("\n"))
                            }
                            $("#form")[0].reset();
                            location.reload();
                        },
                    })
                }
                break;
            case "Update":
                const id = $("input[name=id]").val();
                if (Object.keys(payload).length > 0) {
                    $.ajax({
                        url: `<?php echo base_url(); ?>admin/types/update/${id}`,
                        method: "post",
                        dataType: "json",
                        data: payload,
                        success: function(response) {
                            if (!response.success) {
                                console.log(response.errors.split("\n"))
                            }
                            $("#form")[0].reset();
                            location.reload();
                        },
                    });
                }
                break;
            default:
                break;
        }
    })

    // Handle delete
    $(document).on('click', '.delete_btn', function() {
        const id = $(this).attr('data-id');
        const deleteConfirm = confirm("Are you sure?");
        if (deleteConfirm) {
            $.ajax({
                url: `<?php echo base_url(); ?>admin/types/delete/${id}`,
                success: function(response) {
                    console.log(response)
                }
            })
        }
    })

    // Fetch data
    const fetchData = () => {
        $(document).ready(function() {
            $.ajax({
                url: "<?php echo base_url(); ?>types/fetchTypes",
                method: "get",
                dataType: "json",
                success: function(response) {
                    if (response.success) {

                        $("#types-table").DataTable({
                            data: response.data,
                            columns: [{
                                    data: "id",
                                }, {
                                    data: "title",
                                },
                                {
                                    data: "slug",
                                },
                                {
                                    data: "created_at",
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
                },
            })
        });

    }
    fetchData();
</script>