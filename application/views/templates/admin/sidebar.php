<style>
    .side_bar {
        position: fixed;
        left: 0;
        top: 63px;
        bottom: 0;
        overflow-y: auto;
    }

    .btn-add-project {
        width: 100%;
        justify-content: space-around;
        padding: 0 12px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .dropdown-menu li {
        padding: 8px 10px;
        cursor: pointer;
    }

    .dropdown-menu li:hover {
        background: #ddd !important;
    }

    .dropdown-toggle::after {
        display: none !important;
    }

    .list-admin-manager {
        padding: 12px;
    }

    .list-admin-item {
        padding: 8px 10px;
    }

    .list-admin-item a {
        color: #333 !important;
    }

    .list-admin-item:hover {
        background: #f0f0f1;
    }

    .list-admin-manager li a {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .main-content {
        height: calc(100vh - var(--header-height)) !important;
        overflow-y: auto !important;
    }

    .side-bar {
        position: relative;
        height: calc(100vh - var(--header-height)) !important;
        overflow-y: auto;
        transition: .3s;
    }

    .side-bar.collapsed {
        transition: width 0.3s;
        width: 70px;
    }

    .side-bar.collapsed .home_menu_list span {
        transition: width 0.3s;
        display: none;
    }

    .side-bar.collapsed .home_menu_list a {
        justify-content: center;
    }

    .main-content.expanded {
        margin-left: 0;
        width: calc(100% - 50px);
        transition: margin-left 0.2s ease, width 0.4s ease;
    }

    @media (max-width: 768px) {
        .main-content.expanded {
            width: 100%;
        }
    }

    .collapse-button {
        width: 50px;
        height: 50px;
        padding: 8px 10px;
        border: none;
        border-radius: 50%;
        background-color: transparent;
    }

    .collapse-button:hover {
        background-color: #ddd;
    }

    .popover-admin {
        position: absolute;
        width: 250px !important;
        bottom: 30px;
        left: 65px;
        z-index: 999;
        width: 200px;
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        padding: 10px;
        display: none;
    }

    .popover-list-add-item {
        position: absolute;
        top: 250px;
        left: 50px;
        z-index: 999;
        width: 200px;
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        padding: 10px;
        display: none;
    }

    .type-select-collapse {
        padding: 6px;
    }

    .btn-add-project-collapse {
        width: 100%;
        justify-content: space-around;
        padding: 0 12px;
        display: none;
        align-items: center;
        gap: 5px;
    }

    .item-manager-icon {
        width: 20px;
        height: 20px;
    }

    .sidebar_right {
        position: relative;
        height: 100%;
    }

    .list_project_main {
        padding-top: 10px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .list_project_main_item,
    .btn_add_project {
        background: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        width: 45px;
        height: 45px;
        overflow: hidden;
        border-radius: 50%;
        margin: 0 auto;
    }

    .group_list_project_general,
    .group_list_project_personal {
        padding: 5px;
        /* margin-top: 10px; */
    }

    .btn_add_project {
        background: #0763EE;
        color: white !important;
    }

    .group_list_project_general_heading,
    .group_list_project_personal_heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .group_list_project_general_item,
    .group_list_project_personal_item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 5px;
        padding: 3px 0 3px 10px;
        position: relative;
    }

    .project_personal_actions {
        text-align: center;
        width: 20px;
        height: 20px;
        position: absolute;
        right: 5px;
        cursor: pointer;
        display: none;
    }

    .group_list_project_personal_item:hover>.project_personal_actions {
        display: block;
    }

    .group_list_project_general_item:hover,
    .group_list_project_personal_item:hover {
        background: #eee;
    }


    .group_list_project_general_item.active,
    .group_list_project_personal_item.active {
        background: #eee;
    }

    .group_list_project_general_item_image,
    .group_list_project_personal_item_image {
        width: 20px;
        height: 20px;
        overflow: hidden;
    }



    .group_list_project_general_item_image img,
    .group_list_project_personal_item_image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        vertical-align: super !important;
    }

    .group_list_project_general_item_title,
    .group_list_project_personal_item_title {
        width: 85%;
        font-size: 14px;
    }

    .user_setting {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 10px;
        border-top: 1px solid #ddd;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
    }

    .user_setting_info {
        display: flex;
        align-items: center;
        gap: 10px;

    }

    .user_setting_info_avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #0d6efd;
        position: relative;
    }

    .user_setting_info_avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .user_setting_info_status {
        width: 10px;
        height: 10px;
        position: absolute;
        bottom: 0;
        right: 0;
        background: green;
        border-radius: 50%;
    }

    .user_setting_icon {
        cursor: pointer;
    }
</style>

<?php
$page_url = $this->uri->segment(1);
$folder_id_url = $this->uri->segment(3);
$project_id_url = $this->uri->segment(4);
$userId = $this->session->userdata('user_id');
$userInfo = $this->User_model->get_user_by_id($userId);
?>

<style>
    .sidebar_home {
        width: 48px;
    }

    .project_contain {
        width: calc(100% - 48px);
    }

    @media (max-width: 767px) {
        .project_contain {
            width: 100%;
        }
    }
</style>

<div class="side-bar d-none d-md-block <?= isset($folder_id_url) ? 'col-md-4 col-lg-3 col-xxl-2' : 'sidebar_home' ?> bg-light p-0 overflow-y-auto border-end" id="side-bar">
    <div class="row border h-100 m-0">
        <div class="<?= isset($folder_id_url) ? 'col-2' : 'col-12' ?> p-0" style="background: #E4E6E9;" id="sidebar_left">
            <!-- List project main -->
            <div class="list_project_main">

                <?php $folders = $this->Items_model->get_folders($this->session->userdata('user_id')); ?>

                <?php foreach ($folders as $folder) : ?>
                    <a href="<?= base_url("folder/view/" . $folder->id) ?>">
                        <div style="<?= $folder_id_url == $folder->id ? 'border: 2px solid #0763EE;'  :  '' ?>" class="list_project_main_item shadow" data-bs-toggle="tooltip" data-bs-title="<?= $folder->title ?>" data-folder-id="<?= $folder->id ?>">
                            <?php

                            $thumbnail = $folder->thumbnail;

                            if (empty($thumbnail)) {
                                $thumbnail = "assets/images/folder.svg";
                            }

                            ?>

                            <img style="width: 70%; height: 70%; object-fit: cover; border-radius: 50%;" src="<?= base_url($thumbnail) ?>" alt="">
                        </div>
                    </a>
                <?php endforeach; ?>

                <div class="btn_add_project" data-bs-toggle="modal" data-parent-id="0" data-bs-target="#modalCreateProject" data-type-id="<?= $this->config->item('FOLDER_ID') ?>">
                    <img style="width: 40%; height: 40%;" src="<?= base_url("assets/images/plus-white.svg") ?>" alt="">
                </div>
            </div>
            <!-- End List project main -->
        </div>
        <div class="col-10 p-0" id="sidebar_right" <?= isset($folder_id_url) ? '' : 'hidden' ?>>
            <div class="sidebar_right">
                <!-- Main project title -->
                <?php

                $title = "";
                $link = $this->uri->segment(1);

                if ($link == 'mywork') {
                    $title = "My Work";
                } else if ($link == 'schedule-user') {
                    $title = "Lịch giảng dạy";
                } else if (isset($folder_id_url) && $folder_id_url != 0) {

                    $folder = $this->Items_model->find_by_id($folder_id_url);
                    $title = $folder->title;
                } else {
                    $title = $project->title;
                }

                ?>

                <?php if (isset($folder)) : ?>
                    <?php if (isset($folder_id_url)) : ?>
                        <div class="folder-content d-flex justify-content-between align-items-center p-2" style="background: #eee;">
                            <div class="folder-title" style="width: 90%;">
                                <input type="text" data-folder-id="<?= $folder->id ?>" class="folder-title-input" value="<?= $title ?>" style="display: none;">
                                <h6 class="m-0 text-truncate text-primary folder-title-text"><?= $title ?></h6>
                            </div>
                            <span class="text-primary" style="cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                            </span>
                            <ul class="dropdown-menu">
                                <li class="btn-rename-folder">Đổi tên</li>
                                <li class="btn-change-thumbnail-folder">
                                    <label for="input-change-thumnail-folder" class="m-0 fw-normal w-100">Đổi ảnh</label>
                                    <input id="input-change-thumnail-folder" type="file" data-folder-id="<?= $folder->id ?>" hidden class="input-change-thumnail-folder">
                                </li>
                                <li class="btn-delete-folder" data-folder-id="<?= $folder->id ?>" onclick="confirm('Xác nhận xóa?')">Xóa</li>
                            </ul>

                        </div>
                    <?php endif; ?>
                    <!-- End Main project title -->

                    <?php $folders = $this->Items_model->get_child_folder_by_user($folder_id_url, $this->session->userdata('user_id')); ?>

                    <?php foreach ($folders as $folder) : ?>
                        <div class="group_list_project_general">
                            <div class="group_list_project_general_heading">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-primary btn-collapse" style="font-size: 13px;" data-bs-toggle="collapse" href="#collapseListProject_<?= $folder->id ?>" role="button" aria-expanded="false" aria-controls="collapseListProject_<?= $folder->id ?>">
                                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    </span>
                                    <h6 class="m-0 text-primary"><?= $folder->title ?></h6>
                                </div>
                                <span data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                                    <img data-bs-toggle="tooltip" data-bs-title="Thêm dự án" width="20" src="<?= base_url("assets/images/plus.svg") ?>" alt="">
                                </span>

                                <ul class="dropdown-menu" style="z-index: 10;">
                                    <?php foreach ($this->Types_model->get_where(['is_project' => 1]) as $type) : ?>
                                        <li class="type-select-id" style="font-size: 14px;" data-parent-id="<?= $folder->id ?>" data-bs-toggle="modal" data-bs-target="#modalCreateProject" data-type-id="<?= $type->id ?>"><span type="button"><span>
                                                    <img src="<?= base_url("assets/images/plus-circle.svg") ?>" width="20" alt="">
                                                </span> Tạo <?= $type->title ?> </span></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="collapse show" id="collapseListProject_<?= $folder->id ?>">
                                <?php $projects = $this->Items_model->get_project_by_user($folder->id, $this->session->userdata('user_id')) ?>

                                <?php foreach ($projects as $project) : ?>
                                    <?php
                                    $type_url = "";
                                    $project_icon = "";

                                    switch ($project->type_id) {
                                        case $this->config->item("TIMETALBE_ID"):
                                            $type_url = base_url("timetable/view/$folder_id_url/$project->id");
                                            $project_icon = "calendar";
                                            break;
                                        case $this->config->item("TALBE_ID"):
                                            $type_url = base_url("customtable/view/$folder_id_url/$project->id");
                                            $project_icon = "table";
                                            break;
                                        case $this->config->item("DEPARTMENT_ID"):
                                            $type_url = base_url("table/view/$folder_id_url/$project->id");
                                            $project_icon = "user";
                                            break;
                                        case $this->config->item("FOLDER_ID"):
                                            $type_url = "#";
                                            $project_icon = "folder";
                                            break;
                                        default:
                                            $type_url = base_url("table/view/$folder_id_url/$project->id");
                                            $project_icon = "task";
                                            break;
                                    }
                                    ?>

                                    <div class="group_list_project_personal_item <?= $project->id == $project_id_url ? "active" : "" ?>" data-project-id="<?= $project->id ?>">
                                        <div class="" style="width: 85%;">
                                            <a href="<?= $type_url ?>" class="text-secondary d-flex align-items-center gap-2" data-item-id="<?= $project->id ?>">
                                                <div class="group_list_project_personal_item_image">
                                                    <img src="<?= base_url("assets/images/" . $project_icon . ".svg") ?>" alt="">
                                                </div>
                                                <div class="group_list_project_personal_item_title text-truncate project-title-sidebar <?= $project->is_done ? "text-success fw-bold" : "" ?>" data-project-id="<?= $project->id ?>">
                                                    <?= $project->title ?>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="project_personal_actions" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                        </div>
                                        <ul class="dropdown-menu p-0">
                                            <li class="btn-delete-project" data-project-id="<?= $project->id ?>">Xoá</li>
                                        </ul>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="text-center p-2">Không tìm thấy thư mục hoặc thư mục đã xóa!</p>
                <?php endif; ?>


                <!-- User setting -->
                <div class="user_setting">
                    <?php if (isset($userInfo)) : ?>

                        <div class="user_setting_info">
                            <div class="user_setting_info_avatar">
                                <img src="<?= base_url() . $userInfo->avatar; ?>" alt="">
                                <span class="user_setting_info_status"></span>
                            </div>
                            <div class="user_setting_name">
                                <span class="d-block"><strong><?= $userInfo->firstname . " " . $userInfo->lastname; ?></strong></span>
                                <span class="d-block" style="font-size: 13px;"><?= $userInfo->department_name ?></span>
                            </div>
                        </div>

                    <?php endif; ?>

                    <?php
                    $role_id = $this->session->userdata('role_id');
                    $role_permission = $this->Permission_model->get_permissions_by_role($role_id);
                    $menu_all = $this->Permission_model->get_all();
                    ?>

                    <?php $hasAdminManagement = false; ?>

                    <?php foreach ($menu_all as $menu) : ?>
                        <?php if ($menu->parent == 0 && in_array($menu->id, array_column($role_permission, 'permission_id'))) : ?>
                            <?php $hasAdminManagement = true; ?>
                            <?php break; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($hasAdminManagement) : ?>
                        <div>
                            <button type="button" class="collapse-button" data-bs-toggle="popover">
                                <img src="<?= base_url("assets/images/setting.svg") ?>" width="20" alt="">
                            </button>
                        </div>
                    <?php endif; ?>

                </div>
                <!-- End User setting -->
            </div>
        </div>
    </div>
</div>

<div class="popover-admin" id="adminPopover">
    <h6 class="text-center">Danh mục quản trị</h6>
    <ul class="list-admin-manager mb-0">
        <?php foreach ($menu_all as $menu) : ?>
            <?php if ($menu->parent == 0 && in_array($menu->id, array_column($role_permission, 'permission_id'))) : ?>
                <li class="list-admin-item">
                    <a href="<?php echo base_url() . $menu->permission_code; ?>" class="text-decoration-none">
                        <div class="item-manager-icon">
                            <i class="<?= $menu->icon ?>"></i>
                        </div>
                        <div class="">
                            <span><?= $menu->permission_name;  ?></span>
                        </div>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Modal create project -->
<div class="modal fade" id="modalCreateProject" tabindex="-1" aria-labelledby="modalCreateProjectLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-add-project" action="<?= base_url("admin/items/add") ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateProjectLabel">Thêm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input class="form-control" hidden type="text" name="type_id" />
                    <input class="form-control" hidden type="text" value="0" name="parent_id" />

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Tên dự án</label>
                        <input class="form-control" type="text" name="input_project_title_create" />
                    </div>
                    <div class="form-group mb-3 form-group-file-thumbnail">
                        <label for="" class="form-label">Ảnh</label>
                        <input class="form-control" type="file" name="thumbnail" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalItemArchive" tabindex="-1" aria-labelledby="modalItemArchiveLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body" style="height: 500px">

            </div>
        </div>
    </div>
</div>

<script>
    // Set type id for modal
    $('body').on("click", ".type-select-id", function() {
        const type_id = $(this).attr("data-type-id");
        const parent_id = $(this).attr("data-parent-id");

        $(".form-group-file-thumbnail").attr("hidden", true);

        if (parent_id) {
            $("input[name='parent_id']").val(parent_id);
        }

        $("input[name='type_id']").val(type_id);
    })

    // Set parent id for modal (when create project in folder)
    $('body').on("click", ".btn_add_project", function() {
        const parent_id = $(this).attr("data-parent-id");

        $(".form-group-file-thumbnail").removeAttr("hidden");

        $("input[name='parent_id']").val(parent_id);
        $("input[name='type_id']").val("7");
    })

    // Create new project
    $("#form-add-project").on("submit", function(event) {
        event.preventDefault();

        const formData = new FormData();

        const title = $("input[name='input_project_title_create']").val();
        const type_id = $("input[name='type_id']").val();
        const parent_id = $("input[name='parent_id']").val();
        const user_id = "<?= $this->session->userdata('user_id') ?>";

        formData.append("title", title);
        formData.append("type_id", type_id);
        formData.append("parent_id", parent_id);
        formData.append("user_id", user_id);

        console.log("type_id >>>", type_id)
        console.log("parent_id >>>", parent_id)

        if (type_id == 7) {
            const thumbnail = $("input[name='thumbnail']")[0].files;
            formData.append("thumbnail", thumbnail[0]);
        }

        console.log($(this).serializeArray())

        $.ajax({
            url: $(this).attr('action'),
            method: "post",
            processData: false,
            contentType: false,
            cache: false,
            enctype: 'multipart/form-data',
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.success) {

                    if (parent_id == 0) {

                        // Giả sử response.data chứa nội dung bạn muốn thêm
                        var responseData = response.data;

                        // Tìm tất cả các phần tử con của .list_project_main
                        var children = $('.list_project_main').children();

                        if (children.length > 0) {
                            // Nếu có phần tử con, chèn vào trước phần tử cuối cùng
                            children.last().before(responseData);
                        } else {
                            // Nếu không có phần tử con, chèn vào bên trong .list_project_main
                            $('.list_project_main').append(responseData);
                        }

                    } else {

                        const group_list_project_personal = $(".group_list_project_personal");

                        const collapseListProjectPersonal = $(`#collapseListProjectPersonal`);

                        // if (group_list_project_personal.find(collapseListProjectPersonal).length == 0) {
                        //     group_list_project_personal.append(`<div class="collapse show" id="collapseListProjectPersonal"></div>`);
                        // }

                        $(`#collapseListProject_` + parent_id).append(response.data);

                    }

                    $("#modalCreateProject").modal("hide");
                    $("input[name='input_project_title_create']").val("");

                }
            },
        });
    })

    // Delete project
    $("body").on('click', '.btn-delete-folder', function(event) {
        event.preventDefault();

        const id = $(this).attr("data-folder-id");

        $.ajax({
            url: `<?= base_url("admin/items/delete/") ?>${id}`,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    const list_project_main_item = $(`.list_project_main_item[data-folder-id='${id}']`).parent();
                    list_project_main_item.remove();
                }
            }
        })
    })

    // Handle rename folder click
    $("body").on('click', '.btn-rename-folder', function(event) {
        event.preventDefault();

        const folder_title = $(this).parents(".folder-content").find(".folder-title");
        const text_title = folder_title.find("h6");
        const input_project_title = folder_title.find(".folder-title-input");

        text_title.css("display", "none");

        input_project_title.css({
            "display": "block",
            "border": "1px solid blue"
        });

        const input_value = input_project_title.val();

        input_project_title.focus().val('').val(input_value);

        input_project_title.click(function(event) {
            event.preventDefault();
        })

        input_project_title.blur(function() {
            text_title.css("display", "block");
            input_project_title.css("display", "none");
        })

    })
    // Handle input change (Folder title)
    $(".folder-title-input").change(function() {
        const id = $(this).attr("data-folder-id");
        const title = $(this).val().trim("");

        const text_title = $(this).next();

        $.ajax({
            url: `<?= base_url() ?>admin/items/update/${id}`,
            method: "post",
            dataType: "json",
            data: {
                title
            },
            success: function(response) {
                if (response.success) {
                    toastr.success("Cập nhật tên dự án thành công!");
                    text_title.text(title);
                }
            }
        })
    })

    // Handle change thumbnail folder
    $("body").on("change", ".input-change-thumnail-folder", function() {
        const id = $(this).attr("data-folder-id");
        const file = $(this)[0].files;

        if (file.length > 0) {
            const formData = new FormData();

            formData.append("thumbnail", file[0]);

            $.ajax({
                url: `<?= base_url() ?>admin/items/update/${id}`,
                method: "post",
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                data: formData,
                dataType: "json",
                success: function(response) {

                    if (response.success) {
                        const list_project_main_item = $(`.list_project_main_item[data-folder-id='${id}']`);
                        if (list_project_main_item.length != 0) {
                            list_project_main_item.find("img").attr("src", response.file_url_updated);
                        }
                    }

                }
            })

        }

    })

    $(function() {
        $(".list-project").sortable({
                update: function(event, ui) {

                    let array_id = [];

                    $(this).children().each(function() {
                        const id = $(this).attr("data-project-id");
                        array_id.push(id);
                    })

                    $.ajax({
                        url: "<?= base_url("items/sort") ?>",
                        method: "post",
                        data: {
                            array_id
                        },
                        dataType: "json",
                    })
                }
            }

        );
    });

    $("body").on('click', '.itemArchive', function(event) {
        $('#modalItemArchive').modal('show');
        $.ajax({
            url: `<?= base_url("admin/items/get_item_archive") ?>`,
            dataType: "json",
            success: function(response) {
                $('#modalItemArchive .modal-body').empty();
                let items = response.archived_items;
                if (items.length > 0) {
                    let tableHtml = `
                    <h5 class="text-center mb-4">Các dự án đã lưu trữ</h5>
                    <div class="table-responsive">
                        <table id="archive-table" class="table table-hover table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Tên</th>
                                    <th scope="col">
                                    
                                    
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                    items.forEach(function(item) {
                        tableHtml += `
                        <tr data-id="${item.id}">
                            <th scope="row">${item.id}</th>
                            <td>${item.title}</td>
                            <td class="text-center">
                                <a class="btn btn-primary btn-sm" href="<?= base_url() ?>table/view/${item.id}" target="_blank" title="Xem">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <button class="btn btn-secondary btn-sm restore-btn" data-id="${item.id}" title="Khôi phục">
                                    <i class="fa fa-undo"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    });

                    tableHtml += `
                            </tbody>
                        </table>
                    </div>
                `;

                    $('#modalItemArchive .modal-body').append(tableHtml);
                } else {
                    $('#modalItemArchive .modal-body').append(`
                    <div class="text-center">
                        <img width="200" src="<?= base_url() ?>/assets/images/general_not_found_state_new.svg" alt="No archived projects found">
                        <p>Chưa có dự án nào được lưu trữ.</p>
                    </div>
                `);
                }

                $('#modalItemArchive').modal('show');
            }
        });
    });


    $("body").on('click', '.restore-btn', function(event) {
        const id = $(this).attr("data-id");
        const listItem = $(`li[data-project-id='${id}']`).closest(".list-project-item");

        $.ajax({
            url: `<?= base_url() ?>admin/items/update/${id}`,
            method: "post",
            dataType: "json",
            data: {
                is_archived: 0
            },
            success: function(response) {
                if (response.success) {
                    toastr.success("Cập nhật thành công!");
                    $('.archive').remove();
                    $(`tr[data-id="${id}"]`).remove();
                    $(`.project-archive[data-project-id="${id}"]`).attr("data-value", 1).html('<i class="fa fa-archive" aria-hidden="true"></i> Lưu trữ');
                    listItem.attr("hidden", false);

                    var table = document.getElementById("archive-table");
                    var tbodyRowCount = table.tBodies[0].rows.length;

                    if (tbodyRowCount == 0) {
                        $('#modalItemArchive .modal-body').empty();
                        $('#modalItemArchive .modal-body').append(`
                                                                <div class="text-center">
                                                                    <img width="200" src="<?= base_url() ?>/assets/images/general_not_found_state_new.svg" alt="">
                                                                    <p>Chưa có dự án nào được lưu trữ.</p>
                                                                </div>
                                                            `);
                    }
                }
            },
        });

    })

    $(document).ready(function() {
        $('.collapse-button').popover({
            placement: 'top',
            container: 'body',
            html: true,
            content: function() {
                return $('#adminPopover').html();
            }
        });
    });

     // Delete project
     $("body").on('click', '.btn-delete-project', function(event) {
            event.preventDefault();

            const id = $(this).data('project-id');

            $.ajax({
                url: `<?= base_url("admin/items/delete/") ?>${id}`,
                method: "get",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        const group_list_project_personal_item = $(`.group_list_project_personal_item[data-project-id=${id}]`);
                        if (group_list_project_personal_item.length > 0) {
                            group_list_project_personal_item.remove();
                        }
                        toastr.success("Xóa dự án thành công!");
                    }
                }
            })

        })
</script>

<div class="main-content bg-light <?= isset($folder_id_url) ? 'col-md-8 col-lg-9 col-xxl-10' : 'project_contain' ?> rounded" id="main-content">