<li class="user_list_item user_list_item_<?= $meta_id ?>" data-meta-id="<?= $meta_id ?>" data-project-id="<?= $project_id ?>" data-user-id="<?= $user->id ?>" data-group-id="<?= $group_id ?>">
    <img src="<?= base_url($user->avatar) ?>" alt="">
    <span><?= $user->firstname . " " . $user->lastname ?></span>
</li>