<?php $item_id_url = $this->uri->segment(3) ?>
<?php $childs = $this->Items_model->get_child_items($item->id); ?>

<li class="list-project-item" data-project-id="<?= $item->id ?>">
    <div style="padding-left: 25px;">
        <div class="list-project-link">
            <a href="<?= base_url(); ?>table/view/<?= $item->parent_id ?>/<?= $item->id ?>" data-id="<?= $item->id; ?>" style="gap: 10px;" class="text-decoration-none d-flex text-center align-items-center">
                <div class="project-icon">
                    <?php if ($item->type_id == 6 || $item->type_id == 30) : ?>
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="icon_90dcd5fe7a home-list-icon" data-testid="icon">
                            <path d="M4 4H16C16.2761 4 16.5 4.22386 16.5 4.5V15.5C16.5 15.7761 16.2761 16 16 16H4C3.72386 16 3.5 15.7761 3.5 15.5V4.5C3.5 4.22386 3.72386 4 4 4ZM2 4.5C2 3.39543 2.89543 2.5 4 2.5H16C17.1046 2.5 18 3.39543 18 4.5V15.5C18 16.6046 17.1046 17.5 16 17.5H4C2.89543 17.5 2 16.6046 2 15.5V4.5ZM5.75 6C5.33579 6 5 6.33579 5 6.75C5 7.16421 5.33579 7.5 5.75 7.5H10.25C10.6642 7.5 11 7.16421 11 6.75C11 6.33579 10.6642 6 10.25 6H5.75ZM7 9.75C7 9.33579 7.33579 9 7.75 9H12.25C12.6642 9 13 9.33579 13 9.75C13 10.1642 12.6642 10.5 12.25 10.5H7.75C7.33579 10.5 7 10.1642 7 9.75ZM9.75 12C9.33579 12 9 12.3358 9 12.75C9 13.1642 9.33579 13.5 9.75 13.5H14.25C14.6642 13.5 15 13.1642 15 12.75C15 12.3358 14.6642 12 14.25 12H9.75Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    <?php elseif ($item->type_id == 27) : ?>
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" aria-label="Public board" class="icon_90dcd5fe7a" data-testid="icon">
                            <path d="M7.5 4.5H16C16.2761 4.5 16.5 4.72386 16.5 5V15C16.5 15.2761 16.2761 15.5 16 15.5H7.5L7.5 4.5ZM6 4.5H4C3.72386 4.5 3.5 4.72386 3.5 5V15C3.5 15.2761 3.72386 15.5 4 15.5H6L6 4.5ZM2 5C2 3.89543 2.89543 3 4 3H16C17.1046 3 18 3.89543 18 5V15C18 16.1046 17.1046 17 16 17H4C2.89543 17 2 16.1046 2 15V5Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="project_title" style="width: 74% !important;">
                    <span class="text-truncate"><?= $item->title; ?></span>
                    <input class="input_project_title" type="text" value="<?= $item->title; ?>" data-project-id="<?= $item->id; ?>">
                </div>
                <div class="menu_item_hover dropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
                    <i class="fa fa-ellipsis-h"></i>
                </div>
                <ul class="dropdown-menu">
                    <li class="btn-rename-project px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> Đổi tên</li>
                    <li data-id="<?= $item->id ?>" class="btn-delete-project px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
                </ul>
            </a>
        </div>
    </div>
</li>