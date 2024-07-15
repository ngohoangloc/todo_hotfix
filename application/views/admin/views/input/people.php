<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$project = isset($project) ? $project : (object) array();
$group = isset($group) ? $group : (object) array();

$user_id_list = isset($group->owners) ? $group->owners : "";

$users = $this->User_model->find_in_set($user_id_list);
$users_of_meta = $this->User_model->find_in_set($value);
?>

<div class="input-group input-group-table d-flex justify-content-center align-items-center dropdown-center">
    <!-- User meta list -->
    <div class="user_meta_list" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="btn-add-user btn-add-user-<?= $meta_id ?>"><i class="fa fa-plus"></i></span>
        <?php if ($value) : ?>
            <?php foreach ($users_of_meta as $key => $user_meta) : ?>
                <div class="user_avatar_field">
                    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="<?= $user_meta->firstname . " " . $user_meta->lastname ?>">
                        <?php if (!empty($user_meta->avatar)) : ?>
                            <img src="<?= base_url($user_meta->avatar) ?>">
                        <?php else :  ?>
                            <?= $user_meta->firstname[0] . $user_meta->lastname[0] ?>
                        <?php endif;  ?>
                    </span>
                </div>
                <?php if ($key == 2) break; ?>
            <?php endforeach ?>

            <?php if (count($users_of_meta) > 3) : ?>
                <div class="extra-files-count" data-bs-toggle="dropdown" aria-expanded="false">
                    <span> <?= "+" . (count($users_of_meta) - 3) ?> </span>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <div class="user_avatar_field">
                <img src="https://cdn.monday.com/icons/dapulse-person-column.svg" alt="">
            </div>
        <?php endif; ?>
    </div>
    <!-- End User meta list -->

    <div hidden>
        <div class="list-user-meta list-user-meta-body-<?= $meta_id ?>">
            <div class="row">
                <?php $user_meta_id = array() ?>
                <?php foreach ($users_of_meta as $user_meta) : ?>
                    <?php $user_meta_id[] = $user_meta->id ?>
                    <div class="col-12 mb-2">
                        <div class="meta_item_user">
                            <img src="<?= base_url($user_meta->avatar) ?>" alt="" style="border-radius: 50%;">
                            <span><?= $user_meta->firstname . " " . $user_meta->lastname ?></span>
                            <div class="btn-clear-user" data-meta-id="<?= $meta_id ?>" data-project-id="<?= $project->id ?>" data-user-id="<?= $user_meta->id ?>" data-group-id="<?= $group->id ?>"><i class="fa fa-times"></i></div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>

            <div class="col-12 mb-2">
                <input type="text" id="search_users_item_<?= $meta_id ?>" class="form-control" placeholder="Tìm kiếm người dùng">
            </div>

            <div class="row mt-3">
                <span style="margin-bottom: 5px; font-size: 13px;">
                    Gợi ý
                </span>

                <ul class="user_list">
                    <?php $users_of_department = $this->User_model->get_board_members_of_the_same_department($this->session->userdata('user_id')) ?>

                    <?php foreach ($users_of_department as $user) : ?>
                        <?php if (!in_array($user->id, $user_meta_id)) : ?>
                            <li class="user_list_item user_list_item_<?= $meta_id ?>" data-meta-id="<?= $meta_id ?>" data-project-id="<?= isset($project->id) ? $project->id : "" ?>" data-user-id="<?= isset($user->id) ? $user->id : "" ?>" data-group-id="<?= isset($group->id) ? $group->id : "" ?>">
                                <img src="<?= base_url($user->avatar) ?>" alt="" style="border-radius: 50%;">
                                <span><?= $user->firstname . " " . $user->lastname ?></span>
                            </li>
                        <?php endif; ?>
                    <?php endforeach ?>

                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const options = {
            html: true,
            title: "",
            content: $('.list-user-meta-body-<?= $meta_id ?>')
        };
        const addUserEl = $('.btn-add-user-<?= $meta_id ?>');

        const popover = new bootstrap.Popover(addUserEl, options);

        $('body').on('keyup', '#search_users_item_<?= $meta_id ?>', function() {

            let search = $(this).val();

            $.ajax({
                url: '<?= base_url('items/search_users_input') ?>',
                method: 'get',
                data: {
                    search: search,
                    project_id: <?= $group->parent_id ?>,
                    group_id: <?= $group->id ?>,
                    meta_id: <?= $meta_id ?>,
                },
                dataType: 'json',
                success: function(res) {
                    const list = $('.list-user-meta-body-<?= $meta_id ?>').find('.user_list');
                    list.html(res.data);
                }
            });
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.popover-body').length) {
                $('.btn-add-user-<?= $meta_id ?>').popover('hide');
                // hide_popover($('.btn-add-user-<?= $meta_id ?>'));
            }
        });

        $(popover).on('click', function(event) {
            event.stopPropagation();
        });

        // $("body").on('click', '.user_list_item_<?= $meta_id ?>', function() {
        //     const meta_id = $(this).attr("data-meta-id");
        //     const project_id = $(this).attr("data-project-id");
        //     const group_id = $(this).attr("data-group-id");
        //     const value = $(this).attr("data-user-id");

        //     const task_meta = $(this).parents('.task-meta');

        //     const payload = {
        //         meta_id,
        //         group_id,
        //         project_id,
        //         value,
        //         type: "people_add"
        //     }

        //     $.ajax({
        //         url: "<?= base_url() ?>admin/items/update_meta",
        //         method: "post",
        //         data: payload,
        //         dataType: "json",
        //         success: function(response) {
        //             console.log(response.data);
        //             if (response.success) {

        //                 task_meta.empty();
        //                 task_meta.append(response.data);

        //                 toastr.success("Thay đổi đã được cập nhật!");

        //                 hide_popover();
        //             }
        //         }
        //     })
        // });

        $(".list-user-meta-body-<?= $meta_id ?>").on("click", ".btn-clear-user", function(e) {
            const meta_id = $(this).attr("data-meta-id");
            const project_id = $(this).attr("data-project-id");
            const group_id = $(this).attr("data-group-id");
            const value = $(this).attr("data-user-id");

            const task_meta = $(`.task-meta[data-meta-id='${meta_id}']`);

            const payload = {
                meta_id,
                project_id,
                group_id,
                value,
                type: "people_remove"
            }

            $.ajax({
                url: "<?= base_url() ?>admin/items/update_meta",
                method: "post",
                data: payload,
                dataType: "json",
                success: function(response) {
                    if (response.success) {

                        task_meta.empty();
                        task_meta.append(response.data);

                        toastr.success("Thay đổi đã được cập nhật!");
                        hide_popover();
                    }
                }
            })
        });

        function hide_popover() {
            popover.dispose();
        }

    });
</script>