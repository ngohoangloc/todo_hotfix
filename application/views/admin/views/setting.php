<style>
    .config-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .input-config {
        width: 500px;
        height: 40px;
        border: none;
        padding: 10px 6px;
    }
</style>
<nav class="mt-2">
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-all-tab" data-bs-toggle="tab" data-bs-target="#nav-all" type="button" role="tab" aria-controls="nav-all" aria-selected="true">Cài đặt chung</button>
        <button class="nav-link" id="nav-ip-tab" data-bs-toggle="tab" data-bs-target="#nav-ip" type="button" role="tab" aria-controls="nav-ip" aria-selected="true">Cài đặt IP</button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <!-- Tab setting all -->
    <div class="tab-pane fade  show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
        <button class="btn btn-sm btn-primary btn-add-setting mt-2" data-bs-toggle="modal" data-bs-target="#modalCreateSetting"><i class="fa fa-plus"></i> Thêm cài đặt</button>
        <?php foreach ($config as $conf) : ?>
            <div class="config-item d-flex p-3" style="width: 700px">
                <div>
                    <span><?= htmlspecialchars($conf->name) ?></span>
                </div>

                <?php
                $data['id'] = $conf->id;
                $data['key'] = $conf->key;
                $data['value'] = $conf->value;
                $this->load->view("admin/views/components/setting/" . $conf->type, $data);
                ?>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Tab setting ip -->
    <div class="tab-pane fade" id="nav-ip" role="tabpanel" aria-labelledby="nav-ip-tab">
        <br>
        <div class="row">
            <div class="col-6">
                <form action="<?= base_url('setting/create') ?>" method="post">
                    <div class="mb-3">
                        <label for="ip_address" class="form-label">Địa chỉ IP</label>
                        <input type="text" class="form-control" id="ip_address" name="ip_address" placeholder="xxx.xxx.xxx.xxx">
                        <?php if (!empty($error)) : ?> <span class="text-danger"><?php echo $error; ?></span>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-success">Thêm IP</button>
                </form>
            </div>
            <div class="col-6">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ip as $ip) : ?>
                            <tr>

                                <td><?= $ip->ip ?></td>
                                <td>
                                    <a href="#" class="btn btn-warning editIpBtn" data-ip="<?= $ip->ip ?>"> <i class="fa fa-pencil mr-2"></i> Sửa </a>
                                    <a href="#" class="btn btn-danger deleteIpBtn" data-ip="<?= $ip->ip ?>"> <i class="fa fa-trash mr-2"></i> Xóa </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>


</div>
<div class="modal fade" id="editIpModal" tabindex="-1" aria-labelledby="editIpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Ip</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ediIpForm">
                    <div class="form-group">
                        <label for="Ip">Ip address</label>
                        <input type="text" class="form-control" id="oldIp" name="oldIp" hidden>
                        <input type="text" class="form-control" id="newIp" name="newIp">
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="deleteIpModal" tabindex="-1" role="dialog" aria-labelledby="deleteIpModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCreateSetting" tabindex="-1" aria-labelledby="modalCreateSettingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-add-setting" action="<?= base_url("setting/add") ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateSettingLabel">Thêm cài đặt mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input class="form-control" hidden type="text" name="type_id" />

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Tên cài đặt</label>
                        <input class="form-control" type="text" name="input_name" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Key</label>
                        <input class="form-control" type="text" name="input_key" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Value</label>
                        <input class="form-control" type="text" name="input_value" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Type</label>
                        <select class="form-control" name="input_type">
                            <option value="input">Input</option>
                            <option value="toggle">Toggle</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('body').on('click', '.editIpBtn', function(e) {
            e.preventDefault();
            const oldIp = $(this).data('ip');

            $('#oldIp').val(oldIp);
            $('#newIp').val(oldIp);
            $('#editIpModal').modal('show');

        });

        $('body').on('submit', '#ediIpForm', function(e) {

            e.preventDefault();
            var formData = $(this).serialize();
            console.log(formData);
            $.ajax({
                url: "<?php echo base_url('setting/edit'); ?>",
                data: formData,
                type: "POST",
                success: function(response) {
                    toastr.success('Cập nhật thành công', 'Thành công');
                    $('#editIpModal').modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    toastr.error(xhr.responseText, 'Lỗi');
                }
            });
        });

        $('body').on('click', '.deleteIpBtn', function(e) {

            e.preventDefault();

            const ip = $(this).data('ip');

            $('#confirmDeleteBtn').data('ip', ip);

            $('#deleteIpModal').modal('show');
        });

        $('body').on('click', '#confirmDeleteBtn', function(e) {

            const ip = $(this).data('ip');

            $.ajax({
                url: "<?php echo base_url('setting/delete'); ?>",
                data: {
                    ip
                },
                type: "POST",
                success: function(response) {
                    $('#deleteIpModal').modal('hide');
                    toastr.success('Xóa thành công', 'Thành công');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    toastr.error(xhr.responseText, 'Lỗi');
                }
            });
        });

        $('body').on('change', '.form-check-input', function() {

            const id = $(this).data('id');
            const isChecked = $(this).is(':checked');

            $.ajax({
                url: `<?= base_url() ?>setting/update/${id}`,
                method: "post",
                dataType: "json",
                data: {
                    value: isChecked ? 1 : 0
                },
                success: function(response) {
                    toastr.success('Cập nhật thành công');
                },
                error: function(xhr, status, error) {
                    toastr.error(xhr.responseText, 'Lỗi');
                }
            });
        });

        $('body').on('change', '.input-config', function() {

            const id = $(this).data('id');
            const value = $(this).val();

            $.ajax({
                url: `<?= base_url() ?>setting/update/${id}`,
                method: "post",
                dataType: "json",
                data: {
                    value: value
                },
                success: function(response) {
                    toastr.success('Cập nhật thành công');
                },
                error: function(xhr, status, error) {
                    toastr.error(xhr.responseText, 'Lỗi');
                }
            });
        });

        $("#form-add-setting").on("submit", function(event) {
            event.preventDefault();

            const name = $("input[name='input_name']").val();
            const key = $("input[name='input_key']").val();
            const value = $("input[name='input_value']").val();
            const type = $("select[name='input_type']").val();

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: {
                    name,
                    key,
                    value,
                    type
                },
                dataType: "json",

                success: function(response) {
                    console.log(response);
                    if (response.success) {

                        $("#modalCreateSetting").modal("hide");
                        toastr.success("Thành công");

                        $("input[name='input_name']").val("");
                        $("input[name='input_key']").val("");
                        $("input[name='input_value']").val("");
                        $("input[name='input_type']").val("");

                        $('#nav-all').append(response.html);
                    }
                },
            });
        })
    });
</script>