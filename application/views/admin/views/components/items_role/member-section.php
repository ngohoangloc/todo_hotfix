<?php
$members =  $this->Items_role_model->get_users_by_role($items_role->id);

?>
<div class="member-section">
    <div class="member-header">
        <div class="config">
            <span><?= $items_role->items_role_name ?></span>
            <button class="btn btn-sm btn-permission-config" type="button" data-role-id="<?= $items_role->id ?>" data-group-id="<?= $group->id ?>" data-bs-toggle="modal" data-bs-target="#permissionModal"><i class="fa fa-cog"></i></button>
        </div>
        <div class="add-member">
            <button class="btn btn-sm btn-add-member-to-role" type="button" data-role-id="<?= $items_role->id ?>" data-group-id="<?= $group->id ?>" data-bs-toggle="modal" data-bs-target="#add_user_to_role_modal"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="member-list item-role-<?= $items_role->id ?>">
        <!-- load thành viên dự án theo role -->
        <?php foreach ($members as $member) : ?>
            <div class="member">
                <img src="<?= base_url() . $member->avatar ?>" alt="">
                <div>
                    <div class="name"><?= $member->firstname . " " . $member->lastname ?></div>
                    <div class="position"><?= $member->deparment_name?></div>
                </div>
            </div>
        <?php endforeach ; ?>

    </div>
</div>