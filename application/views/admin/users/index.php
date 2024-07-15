<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a class="btn btn-warning" href="<?php echo base_url(); ?>"> <i class="fa fa-angle-left"></i> Trở về</a>
        <a href="#" id="addNewUserBtn" class="btn btn-success"> <i class="fa fa-plus mr-2"></i> Thêm người dùng</a>
        <div class="col-3 mt-2">
            <label>Lọc theo đơn vị</label>
            <select id="departmentFilter" class="form-select">
                <option value="0">Tất cả</option>
                <?php foreach ($departments as $department) : ?>
                    <option value="<?php echo $department->id; ?>"><?php echo $department->title; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="20">Username</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo $user->username; ?></td>
                            <td><?php echo $user->firstname; ?></td>
                            <td><?php echo $user->lastname; ?></td>
                            <td><?php echo $user->email; ?></td>
                            <td>
                                <?php
                                if ($user->status == true) {
                                    echo '<span class="badge bg-success">Kích hoạt</span>';
                                } else {
                                    echo '<span class="badge bg-danger">Đã khóa</span>';
                                }
                                ?>
                            </td>

                            <td>
                                <a href="#" class="btn btn-primary updatePasswordBtn" data-id="<?= $user->id ?>"> <i class="fa fa-lock mr-2"></i> Thay đổi mật khẩu </a>
                                <a href="#" class="btn btn-warning editUserBtn" data-id="<?= $user->id ?>" data-username="<?= $user->username ?>" data-firstname="<?= $user->firstname ?>" data-lastname="<?= $user->lastname ?>" data-email="<?= $user->email ?>" data-roleid="<?= $user->roles_id ?>" data-departmentid="<?= $user->department_id ?>"> <i class="fa fa-pencil mr-2"></i> Sửa </a>
                                <a href="#" class="btn deleteUserBtn <?= $user->status == true ? 'btn-secondary' : 'btn-success' ?>" data-id="<?= $user->id ?>" data-email="<?= $user->email ?>"><i class="fa fa-lock mr-2"></i> <?= $user->status == true ? 'Khóa' : 'Kích hoạt' ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="form-group">
                        <label for="Firstname">First name</label>
                        <input type="text" class="form-control" id="Firstname" name="Firstname">
                        <span id="Fistname_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="Lastname">Last name</label>
                        <input type="text" class="form-control" id="Lastname" name="Lastname">
                        <span id="Lastname_error" class="text-danger"></span>

                    </div>
                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="email" class="form-control" id="Email" name="Email">
                        <span id="Email_error" class="text-danger"></span>

                    </div>
                    <div class="form-group">
                        <label for="Username">Username</label>
                        <input type="text" class="form-control" id="Username" name="Username">
                        <span id="Username_error" class="text-danger"></span>

                    </div>

                    <div class="form-group">
                        <label for="Password">Password</label>
                        <input type="password" class="form-control" id="Password" name="Password" autocomplete="off">
                        <span id="Password_error" class="text-danger"></span>

                    </div>

                    <div class="form-group">
                        <label for="ConfirmPassword">Confirm password</label>
                        <input type="password" class="form-control" id="ConfirmPassword" name="ConfirmPassword" autocomplete="off">
                        <span id="ConfirmPass_error" class="text-danger"></span>

                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="Role" required>
                            <option value="">Select Role</option>
                            <?php foreach ($roles as $role) : ?>
                                <option value="<?php echo $role->id; ?>"><?php echo $role->role_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span id="Role_error" class="text-danger"></span>

                    </div>

                    <div class="form-group">
                        <label>Phòng ban</label>
                        <select class="form-control" name="Department" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department) : ?>
                                <option value="<?php echo $department->id; ?>"><?php echo $department->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span id="Role_error" class="text-danger"></span>

                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="text" class="form-control" id="id1" hidden name="id1">

                    <div class="form-group">
                        <label for="Firstname">First name</label>
                        <input type="text" class="form-control" id="Firstname1" name="Firstname1">
                        <span id="Firstname1_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="Lastname">Last name</label>
                        <input type="text" class="form-control" id="Lastname1" name="Lastname1">
                        <span id="Lastname1_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="email" class="form-control" id="Email1" name="Email1">
                        <input type="email" class="form-control" hidden id="Old_Email" name="Old_Email">
                        <span id="Email1_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="Username">Username</label>
                        <input type="text" class="form-control" id="Username1" name="Username1" readonly>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="Role1" required>
                            <option value="">Select Role</option>
                            <?php foreach ($roles as $role) : ?>
                                <option value="<?php echo $role->id; ?>"><?php echo $role->role_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span id="Role1_error" class="text-danger"></span>
                    </div>

                    <div class="form-group">
                        <label>Phòng ban</label>
                        <select class="form-control" name="Department1" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department) : ?>
                                <option value="<?php echo $department->id; ?>"><?php echo $department->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span id="Department_error" class="text-danger"></span>

                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="updatePasswordModal" tabindex="-1" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatePasswordModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updatePasswordForm">
                    <input type="hidden" id="id" name="id">

                    <div class="form-group">
                        <label for="Password">New Password</label>
                        <input type="password" class="form-control" id="NewPassword" name="NewPassword" autocomplete="off">
                        <span id="Newpassword_error" class="text-danger"></span>

                    </div>

                    <div class="form-group">
                        <label for="ConfirmPassword">Confirm password</label>
                        <input type="password" class="form-control" id="ConfirmPassword1" name="ConfirmPassword1" autocomplete="off">
                        <span id="Confirmpassword_error" class="text-danger"></span>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to disable this user ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#addNewUserBtn').click(function() {
            $('#addUserModal').modal('show');
            $("#addUserForm").trigger("reset");
            $('#Fistname_error').html('').hide();
            $('#Lastname_error').html('').hide();
            $('#Email_error').html('').hide();
            $('#Username_error').html('').hide();
            $('#Password_error').html('').hide();
            $('#ConfirmPass_error').html('').hide();
            $('#Role_error').html('').hide();
        });

        $('#addUserForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: "<?php echo base_url('user/create'); ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.error) {
                        $('#Fistname_error').html(response.error.Firstname).show();
                        $('#Lastname_error').html(response.error.Lastname).show();
                        $('#Email_error').html(response.error.Email).show();
                        $('#Username_error').html(response.error.Username).show();
                        $('#Password_error').html(response.error.Password).show();
                        $('#ConfirmPass_error').html(response.error.ConfirmPassword).show();
                        $('#Role_error').html(response.error.Role).show();
                    } else {
                        $('#addUserModal').modal('hide');
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.editUserBtn', function(e) {

            e.preventDefault();
            $('#editUserModal').modal('show');
            $("#editUserForm").trigger("reset");
            var id = $(this).data('id');
            var username = $(this).data('username');
            var firstname = $(this).data('firstname');
            var lastname = $(this).data('lastname');
            var email = $(this).data('email');
            var oldemail = $(this).data('email');
            var role_id = $(this).data('roleid');
            var department_id = $(this).data('departmentid');

            console.log(department_id);

            $('#id1').val(id);
            $('#Firstname1').val(firstname);
            $('#Lastname1').val(lastname);
            $('#Username1').val(username);
            $('#Email1').val(email);
            $('#Old_Email').val(oldemail);

            $('#Firstname1_error').html('').hide();
            $('#Lastname1_error').html('').hide();
            $('#Email1_error').html('').hide();
            $('#Role1_error').html('').hide();

            $('#editUserModal select[name="Role1"]').val(role_id);
            $('#editUserModal select[name="Department1"]').val(department_id);


        });

        $('#editUserForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log(formData)
            $.ajax({
                url: "<?= base_url('user/edit') ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    console.log(response);
                    if (response.error) {
                        $('#Firstname1_error').html(response.error.Firstname).show();
                        $('#Lastname1_error').html(response.error.Lastname).show();
                        $('#Email1_error').html(response.error.Email).show();
                        $('#Role1_error').html(response.error.Role).show();
                    } else {
                        $('#editUserModal').modal('hide');

                        location.reload();
                    }
                },
                error: function(xhr, status, error) {

                    console.error(xhr.responseText);
                }
            });
        });
        $(document).on('click', '.updatePasswordBtn', function(e) {

            var UserId = $(this).data('id');
            $('#id').val(UserId);

            $('#updatePasswordModal').modal('show');
            $("#updatePasswordForm").trigger("reset");

            $('#Newpassword_error').html('').hide();
            $('#Confirmpassword_error').html('').hide();
        });

        $('#updatePasswordForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: "<?= base_url('user/updatePassword') ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.error) {
                        $('#Newpassword_error').html(response.error.NewPassword).show();
                        $('#Confirmpassword_error').html(response.error.ConfirmPassword).show();
                    } else {
                        $('#updatePasswordModal').modal('hide');
                        alert("Cập nhật mật khẩu thành công");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.deleteUserBtn', function(e) {

            var userId = $(this).data('id');

            $('#deleteModalLabel').text('Confirm disable user' + userId);
            $('#confirmDeleteBtn').data('id', userId);
            $('#deleteUserModal').modal('show');
        });


        $('#confirmDeleteBtn').click(function() {
            var userId = $(this).data('id');
            console.log(userId);
            $.ajax({
                url: "<?php echo base_url('user/delete/'); ?>" + userId,
                type: "POST",
                data: {
                    id: userId
                },
                success: function(response) {
                    console.log(response)
                    $('#deleteUserModal').modal('hide');
                    location.reload();

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });


        });

        $('#departmentFilter').change(function() {
            var departmentId = $(this).val();
            console.log(departmentId);
            console.log("hehehehehehe");
            // filterUsersByDepartment(departmentId);
        });

        function filterUsersByDepartment(departmentId) {
            $.ajax({
                url: "<?php echo base_url('user/filterByDepartment'); ?>",
                type: "GET",
                data: {
                    department_id: departmentId
                },
                success: function(response) {
                    var tbody = $('#dataTable tbody');
                    tbody.empty();

                    response.users.forEach(function(user) {
                        var statusBadge = user.status ? '<span class="badge bg-success">Kích hoạt</span>' : '<span class="badge bg-danger">Đã khóa</span>';
                        var actionButtons = `
                        <a href="#" class="btn btn-primary updatePasswordBtn" data-id="${user.id}"> <i class="fa fa-lock mr-2"></i> Thay đổi mật khẩu </a>
                        <a href="#" class="btn btn-warning editUserBtn" data-id="${user.id}" data-username="${user.username}" data-firstname="${user.firstname}" data-lastname="${user.lastname}" data-email="${user.email}" data-roleid="${user.roles_id}" data-departmentid="${user.department_id}"> <i class="fa fa-pencil mr-2"></i> Sửa </a>
                        <a href="#" class="btn deleteUserBtn ${user.status ? 'btn-secondary' : 'btn-success'}" data-id="${user.id}" data-email="${user.email}"><i class="fa fa-lock mr-2"></i> ${user.status ? 'Khóa' : 'Kích hoạt'}</a>
                    `;

                        var row = `
                        <tr>
                            <td>${user.username}</td>
                            <td>${user.firstname}</td>
                            <td>${user.lastname}</td>
                            <td>${user.email}</td>
                            <td>${statusBadge}</td>
                            <td>${actionButtons}</td>
                        </tr>
                    `;
                        tbody.append(row);
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

    });

    $(document).ready(function() {
        var departmentFilter = $('#departmentFilter');
        var selectedDepartmentId = "<?php echo $selected_department_id; ?>";

        departmentFilter.find('option').each(function() {
            if ($(this).val() == selectedDepartmentId) {
                $(this).prop('selected', true);
                return false; 
            }
        });

        departmentFilter.change(function() {
            var department_id = $(this).val();
            if (department_id == 0) {
                window.location.href = "<?php echo base_url('user'); ?>";
            } else {
                window.location.href = "<?php echo base_url('user'); ?>?department_id=" + department_id;
            }
        });
    });
</script>