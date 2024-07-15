<div class="card shadow mb-4">
    <div class="card-header py-3">
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <a class="btn btn-warning" href="<?php echo base_url('role'); ?>"> <i class="fa fa-angle-left"></i> Trở về</a>

        <!-- <a href="<?= base_url('role/permission_create') ?>" class="btn btn-success"> <i class="fa fa-plus mr-2"></i>Add New Permission</a> -->

        <a href="#" id="addNewPermissionBtn" class="btn btn-success"> <i class="fa fa-plus mr-2"></i> Thêm quyền mới</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tên quyền</th>
                        <th>Mã quyền</th>
                        <th>Mã quyền phụ</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($permission as $item) : ?>
                        <?php if ($item->parent == 0) : ?>
                            <tr>
                                <td class="permission-row" data-id="<?php echo $item->id; ?>" data-name="<?php echo $item->permission_name; ?>" data-code="<?php echo $item->permission_code; ?>"><?php echo $item->permission_name; ?></td>
                                <td><?php echo $item->permission_code; ?></td>
                                <td>
                                    <?php foreach ($permission as $subItem) : ?>
                                        <?php if ($subItem->parent == $item->id) : ?>
                                            <div><?php echo $subItem->permission_code; ?></div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-success addSubPermission" data-id="<?php echo $item->id; ?>" data-code="<?php echo $item->permission_code; ?>"> <i class="fa fa-plus mr-2"></i> Thêm quyền phụ</a>
                                    <a href="#" class="btn btn-warning editSubPermission" data-id="<?php echo $item->id; ?>"> <i class="fa fa-edit mr-2"></i> Sửa quyền phụ</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-labelledby="addPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addpermissionModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPermissionForm">
                    <div class="form-group">
                        <label for="permissionName">Tên quyền</label>
                        <input type="text" class="form-control" id="permissionName" name="permissionName">
                        <span id="Name_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="permissionCode">Mã quyền</label>
                        <input type="text" class="form-control" id="permissionCode" name="permissionCode">
                        <span id="Code_error" class="text-danger"></span>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Lưu</button>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editpermissionModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPermissionForm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="editpermissionId" name="permissionId" hidden>
                        <label for="permissionName">Tên quyền</label>
                        <input type="text" class="form-control" id="editpermissionName" name="permissionName">
                        <span id="Name_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="permissionCode">Mã quyền</label>
                        <input type="text" class="form-control" id="editpermissionCode" name="permissionCode">
                        <span id="Code_error" class="text-danger"></span>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger" id="deletePermissionBtn">Delete</button>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addSubPermissionModal" tabindex="-1" aria-labelledby="addSubPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Sub Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSubPermissionForm">
                    <input type="hidden" class="form-control" id="permissionId" name="permissionId">
                    <div class="form-group">
                        <label for="permissionName">Permission Name</label>
                        <input type="text" class="form-control" id="subPermissionName" name="subPermissionName">
                        <span id="subName_error" class="text-danger"></span>

                    </div>
                    <div class="form-group">
                        <label for="permissionCode">Permission Code</label>
                        <input type="text" class="form-control" id="subPermissionCode" name="subPermissionCode">
                        <span id="subCode_error" class="text-danger"></span>
                    </div>


                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="editSubPermissionModal" tabindex="-1" aria-labelledby="editSubPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Sub Permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSubPermissionForm">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="subPermissionList">
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('#addNewPermissionBtn').click(function() {
            $('#addPermissionModal').modal('show');
            $('#addpermissionModalLabel').text('Thêm quyền');
            $('#addPermissionForm')[0].reset();
            $('#Name_error').html('').hide();
            $('#Code_error').html('').hide();
        });

        $('#addPermissionForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: "<?php echo base_url('permission/create'); ?>",
                type: "POST",
                data: formData,
                success: function(response) {

                    if (response.error) {
                        $('#Name_error').html(response.error.Name).show();
                        $('#Code_error').html(response.error.Code).show();
                    } else {
                        $('#addPermissionModal').modal('hide');
                        location.reload();
                    }

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('.permission-row').click(function() {
            var permissionId = $(this).data('id');
            var permissionName = $(this).data('name');
            var permissionCode = $(this).data('code');

            $('#editPermissionModal').modal('show');

            $('#editpermissionModalLabel').text('Chỉnh sửa quyền');
            $('#editpermissionId').val(permissionId);
            $('#editpermissionName').val(permissionName);
            $('#editpermissionCode').val(permissionCode);

            $('#deletePermissionBtn').data('id', permissionId);

        });

        $('#editPermissionForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log(formData)
            $.ajax({
                url: "<?php echo base_url('permission/edit'); ?>",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        $('#editPermissionModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Failed to edit sub permissions');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while editing sub permissions');
                }
            });
        });

        $('#deletePermissionBtn').click(function() {
            var Id = $(this).data('id');
            console.log(Id);

            var deleteConfirmation = confirm("Are you sure you want to delete this permission?");
            if (deleteConfirmation) {
                $.ajax({
                url: "<?php echo base_url('permission/delete'); ?>",
                type: "POST",
                data: {
                    id: Id
                },
                success: function(response) {
                    $('#editPermissionModal').modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            }
           
        });

        $(document).on('click', '.addSubPermission', function(e) {
            e.preventDefault();
            $('#addSubPermissionForm')[0].reset();
            var permissionId = $(this).data('id');
            var permissionCode = $(this).data('code');
            $('#permissionId').val(permissionId);
            $('#subPermissionCode').val(permissionCode + '.');
            $('#subName_error').html('').hide();
            $('#subCode_error').html('').hide();

            $('#addSubPermissionModal').modal('show');
        });

        $('#addSubPermissionForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log(formData);
            $.ajax({
                url: "<?php echo base_url('permission/add_subpermission'); ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.error) {
                        $('#subName_error').html(response.error.Name).show();
                        $('#subCode_error').html(response.error.Code).show();
                    } else {
                        $('#addSubPermissionModal').modal('hide');
                        location.reload();
                    }

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.editSubPermission', function(e) {
            e.preventDefault();
            var permissionId = $(this).data('id');

            $.ajax({
                url: "<?php echo base_url('permission/get_subpermission'); ?>",
                type: "POST",
                data: {
                    permissionId: permissionId
                },
                dataType: "json",
                success: function(response) {
                    $('#editPermissionId').val(permissionId);

                    var subPermissionsHtml = '';

                    $.each(response, function(index, subPermission) {
                        subPermissionsHtml += '<tr>';
                        subPermissionsHtml += '<td><input type="text" class="form-control" name="subPermissions[' + subPermission.id + '][permission_name]" value="' + subPermission.permission_name + '"></td>';
                        subPermissionsHtml += '<td><input type="text" class="form-control" name="subPermissions[' + subPermission.id + '][permission_code]" value="' + subPermission.permission_code + '"></td>';
                        subPermissionsHtml += '<td><button type="button" class="btn btn-danger btn-sm deleteSubPermission" data-id="' + subPermission.id + '">Delete</button></td>';
                        subPermissionsHtml += '</tr>';
                    });

                    $('#subPermissionList').html(subPermissionsHtml);

                    $('#editSubPermissionModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('#editSubPermissionForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: "<?php echo base_url('permission/edit_subpermission'); ?>",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        $('#editSubPermissionModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Failed to edit sub permissions');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while editing sub permissions');
                }
            });
        });


        $(document).on('click', '.deleteSubPermission', function() {
            var subPermissionId = $(this).data('id');
            var deleteConfirmation = confirm("Are you sure you want to delete this sub permission?");

            if (deleteConfirmation) {
                $.ajax({
                    url: "<?php echo base_url('permission/delete_subpermission'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        subPermissionId: subPermissionId
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            $('#editSubPermissionModal').modal('hide');
                            location.reload();
                        } else {
                            alert('Failed to delete sub permissions');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('An error occurred while deleting sub permission');
                    }
                });
            }
        });
    });
</script>