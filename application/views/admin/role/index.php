<div class="card shadow mb-4">
    <div class="card-header py-3">
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        <a class="btn btn-warning" href="<?php echo base_url('/items'); ?>"> <i class="fa fa-angle-left"></i> Trở về</a>

        <!-- <a href="<?= base_url('role/create') ?>" class="btn btn-success"> <i class="fa fa-plus mr-2"></i>Add New role</a> -->
        <a href="#" id="addNewRoleBtn" class="btn btn-success"> <i class="fa fa-plus mr-2"></i> Thêm vai trò</a>
        <!-- <a href="<?= base_url('permission/view') ?>" class="btn btn-info"><i class="fa fa-eye mr-2"></i> View Permission</a> -->

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tên vai trò</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($role as $item) : ?>
                        <tr>
                            <td><?php echo $item->role_name; ?></td>
                            <td>
                                <a href="<?= base_url('permission/addpermission/' . $item->id) ?>" class="btn btn-primary"> <i class="fa fa-edit mr-2"></i> Cập nhật quyền</a>
                                <!-- <a href="<?= base_url('role/edit/' . $item->id) ?>" class="btn btn-warning"> <i class="fa fa-pen mr-2"></i> Edit </a> -->
                                <a href="#" class="btn btn-warning editRoleBtn" data-id="<?= $item->id ?>" data-role-name="<?= $item->role_name ?>"> <i class="fa fa-pencil mr-2"></i> Sửa </a>
                                <!-- <a href="<?= base_url('role/delete/' . $item->id) ?>" class="btn btn-danger"> <i class="fa fa-trash mr-2"></i>Delete </a> -->

                                <a href="#" class="btn btn-danger deleteRoleBtn" data-id="<?= $item->id ?>" data-role-name="<?= $item->role_name ?>"> <i class="fa fa-trash mr-2"></i> Xóa </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRoleForm">
                    <div class="form-group">
                        <label for="Rolename">Role Name</label>
                        <input type="text" class="form-control" id="Rolename" name="Rolename">
                        <span id="rolename_error" class="text-danger"></span>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ediRoleForm">
                    <div class="form-group">
                        <label for="Rolename">Role Name</label>
                        <input type="text" class="form-control" id="Role_id" hidden name="Role_id">
                        <input type="text" class="form-control" id="Role_name" name="Role_name">
                        <span id="Role_name_error" class="text-danger"></span>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="deleteRoleModal" tabindex="-1" role="dialog" aria-labelledby="deleteRoleModalLabel" aria-hidden="true">
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

<script>
    $(document).ready(function() {

        $('#addNewRoleBtn').click(function() {
            $('#addRoleModal').modal('show');
            $('#addRoleForm')[0].reset();
            $('#rolename_error').html('').hide();
        });

        $('#addRoleForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: "<?php echo base_url('role/create'); ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    console.log(response)
                    if (response.error) {
                        $('#rolename_error').html(response.error.Rolename).show();
                    } else {
                        location.reload();
                        $('#addRoleModal').modal('hide');
                    }

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
        $(document).on('click', '.editRoleBtn', function(e) {
  
            e.preventDefault();
            var roleId = $(this).data('id');
            var roleName = $(this).data('role-name');

            $('#Role_id').val(roleId);
            $('#Role_name').val(roleName);

            $('#editRoleModal').modal('show');
            $('#Role_name_error').html('').hide();
        });

        $('#ediRoleForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: "<?= base_url('role/edit') ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.error) {
                        $('#Role_name_error').html(response.error.Role_name).show();
                    } else {
                        location.reload()
                        $('#editRoleModal').modal('hide');
                    }

                    
                },
                error: function(xhr, status, error) {

                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.deleteRoleBtn', function(e) {
            e.preventDefault();

            var itemId = $(this).data('id');
            var itemName = $(this).data('role-name');
            $('#deleteModalLabel').text('Confirm Delete ' + itemName);
            $('#confirmDeleteBtn').data('id', itemId);

            $('#deleteRoleModal').modal('show');
        });


        $('#confirmDeleteBtn').click(function() {
            var roleId = $(this).data('id');
            $.ajax({
                url: "<?php echo base_url('role/delete/'); ?>" + roleId,
                type: "POST",
                data: {
                    id: roleId
                },
                success: function(response) {
                    $('#deleteRoleModal').modal('hide');
                    location.reload();

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });


        });


    });
</script>