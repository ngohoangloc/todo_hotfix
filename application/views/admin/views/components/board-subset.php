<?php 
$folder_id = isset($folder_id) ? $folder_id : ""
 ?>
<li class="board-subset-item" data-subset-id="<?= $board_subset->id ?>" data-subset-title="<?= $board_subset->title ?>">
    <a class="w-100 text-dark text-truncate" href="<?= base_url($board_subset->title . "/" . "view/"  . $folder_id . "/"  . $project_id) ?>">
        <?= ucfirst($board_subset->title) ?>
    </a>
    <span class="board-subset-menu-hover" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
    </span>
    <ul class="dropdown-menu">
        <li data-subset-id="<?= $board_subset->id ?>" class="btn-delete-subset px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
    </ul>
</li>