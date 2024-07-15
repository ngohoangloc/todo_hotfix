<style>
  .account {
    display: flex;
    align-items: center;
    padding: 0;
    cursor: pointer;
    border-radius: 50px;
  }

  .account .user_name {
    margin-left: 5px;
  }

  .account:hover,
  .menu-account-item:hover {
    background: #ddd;
  }

  .monday_logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .user_avatar {
    width: 32px;
    height: 32px;
    overflow: hidden;
    border-radius: 50%;
  }

  .user_avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .main_content {
    margin-left: 285px;
    overflow: auto;
    height: calc(100vh - 66px);
    padding: 30px 0 0 38px;
    width: calc(100% - 275px);
  }


  .dropdown-menu[data-bs-popper] {
    left: -37px !important;
  }

  .avatar-wrapper {
    position: relative;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
  }

  .avatar-container {
    position: relative;
    display: inline-block;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
  }

  .avatar {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
  }

  .overlay-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 50%;
  }

  .overlay-image i {
    color: #fff;
    font-size: 24px;
  }

  .avatar-container:hover .overlay-image {
    opacity: 1;
  }

  .menu-account {
    display: flex;
    gap: 10px;
  }

  .menu-account-item {
    width: 30px;
    height: 30px;
    text-align: center;
    line-height: 30px;
    cursor: pointer;
    border-radius: 3px;
  }
  
  .noti-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .noti-list-item {
    display: flex;
    gap: 5px;
    align-items: center;
    transition: .3s;
    border-radius: 3px;
    cursor: pointer;
  }

  .noti-list-item:hover {
    background: #eee;
  }

  .noti-list-item-avatar {
    width: 40px;
    height: 40px;
    overflow: hidden;
  }

  .noti-list-item-avatar img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .search_main {
    display: flex;
    align-items: center;
    height: 100%;
    border: 1px solid #ddd;
    position: relative;
    background: #fff;
  }

  .search_main_input {
    width: 100%;
    height: 100%;
    outline: none;
    background: none;
    padding-left: 30px;
    caret-color: blue;
    font-size: 14px;
    border: 1px solid transparent;
  }

  .search_main_input:focus {
    border: 1px solid blue;
  }

  .search_main .search_main_icon {
    width: 30px;
    height: 100%;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
  }

  .search_main_result {
    position: absolute;
    height: fit-content;
    top: 35px;
    left: 0;
    right: 0;
    background: #FFFFFF;
    padding: 10px 15px 0 30px;
    z-index: 10;
    border: 1px solid #ddd;
    transition: .3s;
    display: none;
  }

  .search_main_result.show {
    display: block;
  }

  .search_main_list_item {
    cursor: pointer;
  }

  .search_main_list_item a {
    padding-bottom: 10px;
  }

  @media (max-width: 767px) {
    .search_main {
      display: none !important;
    }
  }

  .btn-collapse-sidebar {
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    border-radius: 50%;
    border: none;
    background-color: transparent;
  }

  .btn-collapse-sidebar:hover {
    background-color: #ddd;
  }

  .user_permission_info {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
  }

  .user_permission_info:hover {
    background: #eee;
  }

  .user_permission_info_avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #ffffff;
    border: 2px solid #0d6efd;
    position: relative;
  }

  .user_permission_info_avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
  }

  .user_permission_info_status {
    width: 10px;
    height: 10px;
    position: absolute;
    bottom: 0;
    right: 0;
    background: green;
    border-radius: 50%;
  }

  .show_notification_btn_dot {
    display: none;
    content: "";
    position: absolute;
    top: 4px;
    right: 3px;
    width: 8px;
    height: 8px;
    background: red;
    border-radius: 50%;
  }
</style>

<?php
$userId = $this->session->userdata('user_id');
$userInfo = $this->User_model->get_user_by_id($userId);
$project_id_url = $this->uri->segment(4);
?>

<nav class="border-bottom" style="background: var(--bg-main-color) !important;">
  <div class="row p-3 m-0 ps-0">
    <div class="col-4 d-flex gap-2 align-items-center">
      <button id="toggle-sidebar" class="btn-collapse-sidebar"><i class="fa fa-bars"></i></button>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex align-items-center gap-2 flex-row justify-content-between">
        <li class="nav-item">
          <a class="navbar-brand d-flex align-items-center" href="<?= base_url() ?>items">
            <img src="<?= base_url("assets/images/dnc_logo.png"); ?>" style="width: 35px; height: 35px;" alt="">
            <span class="d-none d-md-block ms-2">Work Management</span>
          </a>
        </li>
        <!-- <li class="btn-show-navbar-mobile nav-item d-md-none px-2" data-bs-toggle="offcanvas" href="#offcanvasSideBarMobile" role="button" aria-controls="offcanvasSideBarMobile">
          <i class="fa fa-bars" aria-hidden="true"></i>
        </li> -->
      </ul>
    </div>
    <div class="col-4">
      <div class="search_main">
        <input class="search_main_input" type="text" placeholder="Tìm kiếm...">

        <span class="search_main_icon">
          <img width="20" src="<?= base_url("assets/images/search.svg") ?>" alt="">
        </span>

        <div class="search_main_result">
          <ul class="p-0 search_main_result_list">
            <li class="search_main_list_item text-center">
              Không tìm thấy kết quả
            </li>
          </ul>
        </div>

      </div>
    </div>
    <div class="col-4">
      <div class="d-flex justify-content-end">
        <div class="d-flex align-items-center" style="gap: 20px;">
          <div class="menu-account-item position-relative show_notification_btn" data-user-id="<?= $this->session->userdata('user_id') ?>" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNotification" aria-controls="offcanvasNotification" data-bs-toggle="tooltip" data-bs-placement="bottom">
            <img src="<?= base_url("assets/images/bell.svg") ?>" width="20" alt="" data-bs-toggle="tooltip" data-bs-title="Thông báo">
            <div class="show_notification_btn_dot"></div>
          </div>

          <div class="menu-account-item position-relative">
            <img src="<?= base_url("assets/images/sidebar-left.svg") ?>" width="20" alt="" id="popoverElement" data-bs-toggle="tooltip" data-bs-title="Menu">
          </div>

          <div class="account" data-bs-toggle="dropdown" aria-expanded="false">
            <?php if (isset($userInfo)) : ?>
              <div class="user_avatar">
                <img id="avatarImg" src="<?= base_url() . $userInfo->avatar; ?>" alt="">
              </div>
            <?php endif; ?>
            <span class="user_name" data-bs-placement="bottom" data-bs-toggle="tooltip" data-bs-title="Tài khoản"><?= $this->session->userdata("username") ?></span>
          </div>

          <ul class="dropdown-menu">
            <li class="d-flex align-items-center gap-2" style="font-size: 14px;" data-bs-toggle="modal" data-bs-target="#profileModal">
              <img width="20" src="<?= base_url("assets/images/user.svg") ?>" alt=""> Hồ sơ
            </li>
            <li style="font-size: 14px;" class="">
              <a class="d-flex align-items-center gap-2 text-dark" href="<?= base_url("logout") ?>">
                <img width="20" src="<?= base_url("assets/images/logout.svg") ?>" alt=""> Đăng xuất
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- Menu sidebar mobile -->

<!-- End Menu sidebar mobile -->

<div id="menu" style="display: none;">
  <ul class="p-0">
    <li class="py-2">
      <a class="d-flex align-items-center gap-2" href="<?= base_url("mywork") ?>">
        <img width="20" src="<?= base_url("assets/images/calendar.svg") ?>" alt="">
        <span>My Work</span>
      </a>
    </li>
    <li class="py-2">
      <a class="d-flex align-items-center gap-2" href="<?= base_url("schedule-user") ?>">
        <img width="20" src="<?= base_url("assets/images/calendar.svg") ?>" alt="">
        <span>
          Lịch giảng dạy
        </span>
      </a>
    </li>
  </ul>
</div>

<!-- Menu notification -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNotification" aria-labelledby="offcanvasNotificationLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasNotificationLabel">Thông báo</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="notification-ui_dd-content">

    </div>
  </div>
  <div class="offcanvas-footer notification-footer">
    <button class="btn text-center fw-bold text-primary notification-button">
      <img width="24" height="24" src="<?= base_url('assets/icons/double-check.svg') ?>" alt="">
      Đánh dấu đã đọc
    </button>
  </div>
</div>
<!--End Menu notification -->

<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Chỉnh sửa hồ sơ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- Sidebar -->
          <div class="col-12 col-lg-3">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" style="text-decoration: none;" id="editProfileTab" data-bs-toggle="tab" href="#editProfile"> <i class="fa fa-user"></i> Chỉnh sửa hồ sơ</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" style="text-decoration: none;" id="changePasswordTab" data-bs-toggle="tab" href="#changePassword"> <i class="fa fa-lock"></i> Thay đổi mật khẩu</a>
              </li>
            </ul>
          </div>
          <!-- Content -->
          <div class="col-12 col-lg-9">
            <div class="tab-content">
              <!-- Tab Chỉnh sửa hồ sơ -->
              <div class="tab-pane fade show active" id="editProfile">

                <form id="editProfileForm">
                  <div id="error-message" class="alert alert-danger" style="display: none;"></div>
                  <div id="success-message" class="alert alert-success" style="display: none;"></div>

                  <div class="row mt-3">
                    <!-- Avatar -->
                    <div class="col-4 col-lg-3 mb-3 m-auto m-lg-0">
                      <div class="avatar-wrapper">
                        <label for="avatar" class="avatar-container">
                          <img id="avatarPreview" src="<?= base_url() . $userInfo->avatar; ?>" alt="Avatar" class="avatar img-fluid">
                          <div class="overlay-image">
                            <i class="fa fa-camera"></i>
                          </div>
                        </label>
                      </div>
                      <input type="file" id="avatar" name="avatar" style="display: none;">
                    </div>

                    <div class="col-12 col-lg-9">
                      <div class="mb-3">
                        <label for="firstName" class="form-label">Họ</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?= $userInfo->firstname; ?>" disabled>
                        <span id="firstNameError" class="text-danger"></span>

                      </div>
                      <div class="mb-3">
                        <label for="lastName" class="form-label">Tên</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?= $userInfo->lastname; ?>" disabled>
                        <span id="lastNameError" class="text-danger"></span>

                      </div>
                      <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $userInfo->email; ?>" required>
                        <small style="color: red;">(*) Email này sẽ sử dụng để đăng nhập google</small>
                        <span id="emailError" class="text-danger"></span>

                      </div>
                      <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?= $userInfo->phone; ?>">
                        <span id="phoneError" class="text-danger"></span>

                      </div>
                      <div class="mb-3" style="text-align: left;">
                        <button type="button" class="btn btn-primary" id="btnUpdateProfile">Lưu thay đổi</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!-- Tab Thay đổi mật khẩu -->
              <div class="tab-pane fade" id="changePassword">
                <form id="changePasswordForm">
                  <div id="error-message" class="alert alert-danger" style="display: none;"></div>
                  <div id="success-message" class="alert alert-success" style="display: none;"></div>
                  <div class="mb-3">
                    <label for="currentPassword" class="form-label">Mật khẩu hiện tại</label>
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" autocomplete="on" required>
                    <span id="currentPasswordError" class="text-danger"></span>
                  </div>
                  <div class="mb-3">
                    <label for="newPassword" class="form-label">Mật khẩu mới</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" autocomplete="on" required>
                    <span id="newPasswordError" class="text-danger"></span>

                  </div>
                  <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" autocomplete="on" required>
                    <span id="confirmPasswordError" class="text-danger"></span>

                  </div>
                  <button type="button" class="btn btn-primary" id="btnChangePassword">Lưu thay đổi</button>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#btnChangePassword').click(function() {
      var formData = $('#changePasswordForm').serialize();
      $.ajax({
        url: '<?= base_url('profile/changePassword') ?>',
        type: 'post',
        dataType: 'json',
        data: formData,
        success: function(response) {
          console.log(response);
          if (response.success) {
            toastr.success('Thay đổi mật khẩu thành công', 'Thành công');
            $('#changePasswordForm')[0].reset();
            $('#currentPasswordError').html('');
            $('#newPasswordError').html('');
            $('#confirmPasswordError').html('');
          } else {
            $('#currentPasswordError').html(response.error.currentPassword);
            $('#newPasswordError').html(response.error.newPassword);
            $('#confirmPasswordError').html(response.error.confirmPassword);
          }
        }
      });
    });

    $('#btnUpdateProfile').click(function() {
      var formData = new FormData($('#editProfileForm')[0]);
      console.log(formData);
      $.ajax({
        url: '<?= base_url('profile/updateProfile') ?>',
        type: 'post',
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          console.log(response);
          if (response.success) {

            toastr.success('Cập nhật hồ sơ', 'Thành công');
            $('#firstNameError').html('');
            $('#lastNameError').html('');
            $('#emailError').html('');
            $('#phoneError').html('');
            var newAvatarSrc = '<?= base_url() ?>' + response.user.avatar;

            document.getElementById('avatarImg').src = newAvatarSrc;

          } else {
            if (response.error) {
              $('#firstNameError').html(response.error.firstName);
              $('#lastNameError').html(response.error.lastName);
              $('#emailError').html(response.error.email);
              $('#phoneError').html(response.error.phone);
            } else {
              $('#firstNameError').html('');
              $('#lastNameError').html('');
              $('#emailError').html('');
              $('#phoneError').html('');

            }


            toastr.error(response.message, 'Lỗi');

          }
        }
      });
    });


    $('#avatar').change(function() {
      var input = this;
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#avatarPreview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    });

    $('#profileModal').on('show.bs.modal', function(e) {
      $('.text-danger').empty();
      $('.alert').hide();
    });
  });


  $(document).ready(function() {

    $(".search_main_input").focus(function() {
      $(".search_main_result").addClass("show");
    })

    $(".search_main_input").click(function(e) {
      e.stopPropagation();
    })

    $(document).click(function(event) {
      if (!$(event.target).closest('.search_main_result').length) {
        if ($('.search_main_result').hasClass('show')) {
          $('.search_main_result').removeClass('show');
        }
      }
    });

  })


  $("body").on("keyup", ".search_main_input", function() {

    const search_key = $(this).val();

    if (search_key.trim() && search_key.length >= 3) {
      $.ajax({
        url: "<?= base_url("items/search_item") ?>",
        method: "post",
        data: {
          search_key
        },
        dataType: "json",
        success: function(response) {
          if (response.success) {

            const search_main_result_list = $(".search_main_result_list");

            if (response.data.length > 0) {

              search_main_result_list.empty();

              response.data.map(item => {

                const type = item.type_id;

                const type_url = "table";

                switch (type) {
                  case 6:
                    type_url = "table"
                    break;
                  case 31:
                    type_url = "customtable"
                    break;
                  case 32:
                    type_url = "timetable"
                    break;
                  default:
                    break;
                }

                const url = "<?= base_url() ?>" + `${type_url}/view/${item.folder_id}/${item.id}`;

                const html = `<li class="search_main_list_item">
                                  <a class="d-block text-dark d-flex align-items-center gap-2" href="${url}">
                                  <img width="20" src="<?= base_url("assets/images/project.svg") ?>" alt=""><span>${item.title}</span>
                                  </a>
                                </li>`;

                search_main_result_list.append(html);
              })
            } else {
              search_main_result_list.html(`<li class="search_main_list_item text-center">Không tìm thấy kết quả</li>`);
            }

          }
        }
      })
    }
  })

  //Collapse button
  // $(document).ready(function() {

  //   // const sidebar = $('#side-bar');
  //   const mainContent = $('#main-content');
  //   const toggleButton = $('#toggle-sidebar');
  //   const sidebarRight = $('#sidebar_right');
  //   // const collapseConfig = $('#collapse-config');
  //   // const collapseButtonAdditem = $('#btn-add-project-collapse');
  //   const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

  //   applySidebarState(isCollapsed);

  //   $(toggleButton).on("click", function() {
  //     const isCurrentlyCollapsed = sidebarRight.hasClass('collapsed');
  //     applySidebarState(!isCurrentlyCollapsed);
  //     localStorage.setItem('sidebarCollapsed', !isCurrentlyCollapsed);
  //   });

  //   function applySidebarState(isCollapsed) {
  //     if (isCollapsed) {
  //       // sidebar.addClass('collapsed');
  //       mainContent.addClass('expanded');
  //       sidebarRight.css('display', 'none');
  //       // sidebar.find('.rounded').removeClass('border-end');
  //       // sidebar.addClass('border-end');
  //       // sidebar.hide();
  //       // collapseConfig.css('display', 'flex');
  //       // collapseButtonAdditem.css('display', 'flex');
  //     } else {
  //       // sidebar.removeClass('collapsed');
  //       mainContent.removeClass('expanded');
  //       // sidebar.css('display', 'block');
  //       // sideContent.css('display', 'block');
  //       // sidebar.find('.rounded').addClass('border-end');
  //       // sidebar.removeClass('border-end');
  //       // collapseConfig.hide();
  //       // collapseButtonAdditem.hide();
  //     }
  //   }

  // })

  $(document).ready(function() {
    $('#popoverElement').popover({
      placement: 'bottom',
      container: 'body',
      html: true,
      content: function() {
        return $('#menu').html();
      }
    });
  });
</script>

<!-- Content -->
<div class="bg-body-secondary">
  <div class="row p-0 m-0">