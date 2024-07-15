<style>
    /* .modal-body {
        position: relative;
    } */

    .menu-result {
        position: absolute;
        top: 102px;
        left: 16px;
        right: 16px;
        border: 1px solid #ddd;
        height: fit-content;
        border-radius: 5px;
        overflow-y: auto;
        display: none;
        box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.2);
        background-color: #fff;
        z-index: 9999;
    }

    .owners-list {
        padding-top: 1rem;
    }

    .owners-list .project-members-list {
        padding: 0;
    }

    .project-member-item-info {
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
        padding: 10px 0 0 0;
    }

    .project-member-item-info button {
        display: flex;
        align-items: center;
        gap: 10px;
        position: absolute;
        right: 0;
    }

    .project-member-item-info img {
        object-fit: cover;
    }

    .project-member-item-info button img {
        object-fit: cover;
    }

    @media (max-width: 767px) {
        .title_invite {
            display: none;
        }
    }
</style>

<div class="invite-project-members dropstart">
    <button type="button" class="btn btn-sm btn-outline-secondary btn-search-owners-modal" data-bs-toggle="modal" data-bs-target="#inviteModal">
        <i class="fa fa-user-plus" aria-hidden="true"></i>
        <span class="title_invite">Mời</span>
    </button>

    <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="min-height: 500px;">
                    <input id="search-department-invite-to-project" type="text" class="form-control mb-2" placeholder="Tìm kiếm phòng ban">

                    <input id="search-users-to-invite-project" type="text" class="form-control" placeholder="Tìm kiếm username hoặc email">

                    <div class="menu-result">
                        <div class="project-members-list-search p-0 mt-3">

                        </div>
                    </div>

                    <div class="owners-list">
                        <ul class="project-members-list">

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var is_owner = <?= $is_owner ? $is_owner : 0  ?>;

        //Add user to project
        $(document).on("click", ".add-user-to-project", function() {

            if (is_owner) {
                $.ajax({
                    url: "<?= base_url('items/add_owner') ?>",
                    method: "post",
                    data: {
                        user_id: $(this).data('user_id'),
                        item_id: $(this).data('item_id'),
                    },
                    dataType: "json",
                    success: function(res) {
                        loadOwners();
                        $(".menu-result").hide();
                        $('#search-users-to-invite-project').val('');
                    }
                });
            } else {
                toastr.warning('Bạn không thể mời thành viên vào dự án!');
            }
        });

        $('.btn-search-owners-modal').click(function() {

            loadOwners();
        });

        $('#search-users-to-invite-project').keyup(function() {
            let typingTimer;
            let doneTypingInterval = 500;

            clearTimeout(typingTimer);
            const inputValue = $(this).val().trim();
            if (inputValue) {
                typingTimer = setTimeout(function() {
                    searchUsers(inputValue);
                }, doneTypingInterval);
            } else {
                $('.project-members-list-search').empty();
                $(".menu-result").hide();
            }
        });

        function searchUsers(searchQuery) {
            $.ajax({
                url: "<?= base_url('user/search_users') ?>",
                method: "get",
                data: {
                    search: searchQuery,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success && response.data.length > 0) {
                        $(".menu-result").css("display", "block");
                        $('.project-members-list-search').empty();
                        var users = response.data;
                        $.each(users, function(key, user) {
                            // var row = '<div class="project-member-item-info">' +
                            //     '<button type="button" data-item_id="<?= $item_id ?>" data-user_id="' + user.id + '" class="add-user-to-project list-group-item list-group-item-action p-2">' +
                            //     '<img src="<?= base_url() ?>' + user.avatar + '" alt="">' +
                            //     '<span>' + user.firstname + ' ' + user.lastname + '</span>' +
                            //     '</button>'
                            // '</div>';

                            var row = '<button type="button" data-item_id="<?= $item_id ?>" data-user_id="' + user.id + '" class="add-user-to-project list-group-item list-group-item-action p-2 col-10">' +
                                '<div class="project-member-item-info">' +
                                '<img src="<?= base_url() ?>' + user.avatar + '" alt="">' +
                                '<div class="text-container">' +
                                '<div class="name-department">' +
                                '<span>' + user.firstname + ' ' + user.lastname + '</span>';
                            if (user.department_name !== null && user.department_name !== undefined) {
                                row += '<span> <small><i>(' + user.department_name + ')</i></small> </span>';
                            }
                            row += '</div>';

                            row += '<span class="email">' + user.email + '</span>';

                            row += '</div></div></button>';

                            $('.project-members-list-search').append(row);
                        });
                    } else {
                        $(".menu-result").hide();
                    }
                }
            });
        }
        //End user to project

        //Add department to project
        $('#search-department-invite-to-project').keyup(function() {
            let typingTimer;
            let doneTypingInterval = 500;

            clearTimeout(typingTimer);
            const inputValue = $(this).val().trim();
            if (inputValue) {
                typingTimer = setTimeout(function() {
                    searchDepartment(inputValue);
                }, doneTypingInterval);
            } else {
                $('.project-members-list-search').empty();
                $(".menu-result").hide();
            }

        });

        function searchDepartment(searchQuery) {
            $.ajax({
                url: "<?= base_url('items/search_department') ?>",
                method: "get",
                data: {
                    search: searchQuery,
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);

                    if (response.success && response.data.length > 0) {
                        $(".menu-result").css("display", "block");

                        $('.project-members-list-search').empty();

                        var department = response.data;
                        $.each(department, function(key, department) {
                            var row = '<button type="button" data-item-id="<?= $item_id ?>" data-department-id="' + department.id + '" class="add-department-to-project list-group-item list-group-item-action p-2 col-10">' +
                                '<div class="group-member-item-info">' +
                                '<span>' + department.title + '</span>' +
                                '</div>' +
                                '</button>';
                            $('.project-members-list-search').append(row);
                        });
                    } else {
                        $(".menu-result").hide();
                    }
                }
            });
        }

        $('body').on("click", ".add-department-to-project", function() {
            console.log(is_owner);

            department_id = $(this).data('department-id');
            console.log("Mã đơn vị " + department_id);
            item_id = $(this).data('item-id');
            console.log("Mã bảng " + item_id);
            if (is_owner) {
                $.ajax({
                    url: "<?= base_url('items/add_department') ?>",
                    method: "post",
                    data: {
                        item_id,
                        department_id
                    },
                    dataType: "json",
                    success: function(res) {
                        console.log(res);
                        toastr.success("Thêm đơn vị vào dự án thành công");
                        loadOwners();
                        $(".menu-result").hide();
                        $('#search-department-invite-to-project').val('');
                    }
                });
            } else {
                toastr.warning('Bạn không thể mời thành viên vào dự án!');
            }
        });
        //End add department to project

        $('body').on('click', '.btn-clear-member-of-project', function() {
            if (is_owner) {
                let user_id = $(this).data('user-id');
                let item_id = $(this).data('item-id');

                if ($(this).find('svg').length > 0 && user_id_login == user_id) {
                    toastr.warning('Không thể xóa chủ dự án!');
                } else {
                    $.ajax({
                        url: "<?= base_url('items/delete_user_from_group') ?>",
                        method: "post",
                        data: {
                            user_id: user_id,
                            item_id: item_id,
                        },
                        dataType: "json",
                        success: function(response) {
                            loadOwners(item_id);
                        }
                    });

                }
            }
        });

    });

    function loadOwners(item_id) {
        $.ajax({
            url: "<?= base_url('items/get_owners') ?>",
            method: "get",
            data: {
                item_id: <?= $item_id ?>,
            },
            dataType: "json",
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    $('.project-members-list').empty();
                    var owners = response.data;
                    let isOwnerOfGroup = true;

                    $.each(owners, function(key, owner) {
                        var row = '<div class="project-member-item-info" data-item_id="' + <?= $item_id ?> + '"   data-user_id="' + owner.id + '" class="list-group-item list-group-item-action p-2">' +
                            '<img src="' + '<?= base_url() ?>' + owner.avatar + '" alt="">' +
                            '<span>' + owner.firstname + ' ' + owner.lastname + '</span>' +
                            '<button style="border: none;background-color: transparent;padding-right: 25px;" type="button" class="btn-clear-member-of-project" data-user-id="' + owner.id + '" data-item-id="' + <?= $item_id ?> + '">';
                        if (isOwnerOfGroup === true) {
                            row += '<svg fill="#FFFF00" height="16px" width="16px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 220 220" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M220,98.865c0-12.728-10.355-23.083-23.083-23.083s-23.083,10.355-23.083,23.083c0,5.79,2.148,11.084,5.681,15.14 l-23.862,21.89L125.22,73.002l17.787-20.892l-32.882-38.623L77.244,52.111l16.995,19.962l-30.216,63.464l-23.527-21.544 c3.528-4.055,5.671-9.344,5.671-15.128c0-12.728-10.355-23.083-23.083-23.083C10.355,75.782,0,86.137,0,98.865 c0,11.794,8.895,21.545,20.328,22.913l7.073,84.735H192.6l7.073-84.735C211.105,120.41,220,110.659,220,98.865z"></path> </g></svg>';
                            isOwnerOfGroup = false;
                        } else if (user_id_login == owners[0].id) {
                            row += '<i class="fa fa-times-circle" aria-hidden="true"></i>';
                        }

                        row += '</button></div>';
                        $('.project-members-list').append(row);
                    });
                }
            }
        });
    }
</script>