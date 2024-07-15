<div class="row">
    <div class="col-12">
        <h4>Quản lý Html Type</h4>

        <button class="add_btn btn btn-sm btn-outline-success">
            <span>Thêm mới</span>
            <i class="fa fa-plus" aria-hidden="true"></i>
        </button>

        <table class="table table-bordered table-striped table-hover mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Value</th>
                    <th>Color</th>
                    <th>Created at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($html_types as $html_type) : ?>
                    <tr>
                        <td><?= isset($html_type->id) ? $html_type->id : "" ?></td>
                        <td><?= isset($html_type->title) ? $html_type->title : "" ?></td>
                        <td><?= isset($html_type->value) ? $html_type->value : "" ?></td>
                        <td><?= isset($html_type->color) ? $html_type->color : "" ?></td>
                        <td><?= isset($html_type->created_at) ? $html_type->created_at : "" ?></td>
                        <td>
                            <button class="edit_btn btn btn-sm btn-outline-warning" data-id="<?= $html_type->id ?>"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                            <button onclick="return confirm('Xóa?')" class="delete_btn btn btn-sm btn-outline-danger" data-id="<?= $html_type->id ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="HtmlTypeModal" tabindex="-1" aria-labelledby="HtmlTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="HtmlTypeModalLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="id" class="form-control" hidden>
                <div class="form-group mb-2">
                    <label class="form-label">Kiểu html</label>
                    <input type="text" name="name" class="form-control">
                </div>
                <div class="form-group mb-2">
                    <label class="form-label">Tên cột</label>
                    <input type="text" name="value" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Màu sắc</label>
                    <select name="color" class="form-select">
                        <option value="danger">Danger</option>
                        <option value="primary">Primary</option>
                        <option value="warning">Warning</option>
                        <option value="info">Info</option>
                        <option value="success">Success</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn_submit btn btn-primary">Lưu</button>
            </div>
        </div>
    </div>
</div>


<script>
    const title = $("input[name='name']");
    const value = $("input[name='value']");
    const color = $("select[name='color']");
    const input_id = $("input[name='id']");

    let action = "add";

    const clearText = () => {
        title.val("");
        value.val("");
    }

    $(".add_btn").click(function() {
        action = "add";
        clearText();
        $("#HtmlTypeModal").modal("show");
    });

    $(".edit_btn").click(function() {
        action = "edit";

        const id = $(this).attr("data-id");

        $.ajax({
            url: "<?= base_url("html_type/view/") ?>" + id,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    title.val(response.data.title);
                    color.val(response.data.color);
                    value.val(response.data.value);
                    input_id.val(id);
                    $("#HtmlTypeModal").modal("show");
                }
            }
        })


    });

    $(".delete_btn").click(function() {

        const id = $(this).attr("data-id");

        $.ajax({
            url: "<?= base_url("html_type/delete/") ?>" + id,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    toastr.success("Xóa dữ liệu thành công!");
                    location.reload();
                }
            }
        })


    });

    $(".btn_submit").click(function() {

        const payload = {
            title: title.val(),
            value: value.val(),
            color: color.val(),
        }

        let url = "<?= base_url("html_type/") ?>" + action;

        if (action) {
            url += "/" + input_id.val();
        }

        $.ajax({
            url: url,
            method: "post",
            data: payload,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#HtmlTypeModal").modal("hide");
                    title.val("");
                    color.val("");
                    location.reload();
                }
            }
        })

    });
</script>