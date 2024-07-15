<?php $item_id_url = $this->uri->segment(3) ?>
<?php $childs = $this->Items_model->get_child_items($item->id); ?>

<li class="list-project-item" data-project-id="<?= $item->id ?>">
    <div class="list-project-link d-flex text-center align-items-center" style="gap: 10px; padding: 8px 0px;">
        <div class="project-icon btn-collapse-folder" data-bs-toggle="collapse" href="#collapseFolder-<?= $item->id ?>" role="button" aria-expanded="true" aria-controls="collapseFolder-<?= $item->id ?>">
            <?= in_array($item_id_url, array_column($childs, "id")) ? '<i class="fa fa-caret-down" aria-hidden="true"></i>' : '<i class="fa fa-caret-right" aria-hidden="true"></i>' ?>
        </div>
        <div class="project_title">
            <span class="text-truncate"><?= $item->title; ?></span>
            <input class="input_project_title" type="text" value="<?= $item->title; ?>" data-project-id="<?= $item->id; ?>">
        </div>
        <div class="menu_item_hover dropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
            <i class="fa fa-ellipsis-h"></i>
        </div>
        <ul class="dropdown-menu">
            <li class="btn-rename-project px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> Đổi tên</li>
            <li class="type-select-id" style="font-size: 14px;" data-bs-toggle="modal" data-bs-target="#modalCreateProject" data-type-id="6" data-parent-id="<?= $item->id ?>"><span type="button">Tạo dự án</span></li>
            <li class="type-select-id" style="font-size: 14px;" data-bs-toggle="modal" data-bs-target="#modalCreateProject" data-type-id="27" data-parent-id="<?= $item->id ?>"><span type="button">Tạo board</span></li>
            <li data-id="<?= $item->id ?>" class="btn-delete-project px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
        </ul>
    </div>
</li>