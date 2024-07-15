<style>
    .board_toolbar {
        width: 100%;
        border-bottom: 1px solid #ddd;
    }

    .btn-add-board {
        cursor: pointer;
    }

    .list-board-subset {
        display: flex;
    }

    .list-board-subset li:not(.btn-add-board, .menu-board-subset-item, .btn-delete-subset) {
        min-width: 120px;
        position: relative;
        display: flex;
        align-items: center;
        border-radius: 4px;
        justify-content: center;
    }

    .btn-add-board {
        padding: 4px 8px;
    }

    .list-board-subset li a {
        text-align: center;
    }

    .list-board-subset li:hover {
        background: var(--surfce-color);
    }

    .list-board-subset>li::after {
        content: "";
        width: 1px;
        height: 20px;
        position: absolute;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
        background: #ddd;
    }

    .menu-add-board-subset li {
        cursor: pointer;
    }

    .menu-add-board-subset li span {
        margin-right: 3px;
    }

    .board-subset-icon {
        width: 20px;
    }

    .board-subset-menu-hover {
        position: absolute;
        right: 0;
        width: 25px;
        text-align: center;
        cursor: pointer;
        display: none;
    }

    .board-subset-menu-hover:hover {
        background-color: #fff;
    }

    .board-subset-item:hover .board-subset-menu-hover {
        display: block;
    }

</style>

<?php
$tab = $this->uri->segment(1);
$folder_id_url = isset($folder_id_url) ? $folder_id_url : "";
?>

<div class="board_toolbar">
    <ul class="m-0 p-0 list-board-subset mt-2">
        <li class="board-subset-item <?php if ($tab == 'table') echo 'active'; ?>"  data-subset-title="table">
            <?php $project = $this->Items_model->find_by_id($project_id)  ?>
            <a class="text-dark w-100" href="<?= base_url() ?><?= ($project->type_id == 31) ? 'customtable' : 'table' ?>/view/<?= $folder_id_url ?>/<?= $project_id ?>">
                <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16" aria-hidden="true" class="icon_1b49d21cea icon me-2" data-testid="icon">
                    <path d="M9.56992 2.1408C9.82591 1.95307 10.1741 1.95307 10.4301 2.1408L17.7028 7.47413C17.8896 7.61113 18 7.82894 18 8.06061V16.7879C18 17.1895 17.6744 17.5152 17.2727 17.5152H11.9394C11.5377 17.5152 11.2121 17.1895 11.2121 16.7879V13.1515H8.78788V16.7879C8.78788 17.1895 8.46227 17.5152 8.06061 17.5152H2.72727C2.32561 17.5152 2 17.1895 2 16.7879V8.06061C2 7.82894 2.11037 7.61113 2.29719 7.47413L9.56992 2.1408ZM3.45455 8.42914V16.0606H7.33333V12.4242C7.33333 12.0226 7.65894 11.697 8.06061 11.697H11.9394C12.3411 11.697 12.6667 12.0226 12.6667 12.4242V16.0606H16.5455V8.42914L10 3.62914L3.45455 8.42914Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
                Main table
            </a>
        </li>

        <?php $boards_subset = $this->Items_model->get_where($project_id, ['type_id' => 29])  ?>

        <?php foreach ($boards_subset as $board_subset) : ?>
            <li class="board-subset-item <?php if ($tab == $board_subset->title) echo 'active'; ?>" data-subset-id="<?= $board_subset->id ?>" data-subset-title="<?= $board_subset->title ?>">
                <a class="w-100 text-dark text-truncate" href="<?= base_url($board_subset->title . "/" . "view/" . $folder_id_url . "/" . $project_id) ?>">
                    <?= ucfirst($board_subset->title) ?>
                </a>
                <span class="board-subset-menu-hover" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </span>
                <ul class="dropdown-menu">
                    <li data-subset-id="<?= $board_subset->id ?>" class="btn-delete-subset px-2 dropdown-item" style="font-size: 14px;"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>
                </ul>

            </li>
        <?php endforeach; ?>

        <li class="btn-add-board" data-bs-toggle="dropdown" aria-expanded="false" aria-hidden="true">
            <span><i class="fa fa-plus"></i></span>
        </li>

        <ul class="dropdown-menu menu-add-board-subset">
            <?php if ($project->type_id == 31) : ?>
                <li data-project-id="<?= $project_id ?>" data-folder-id="<?= $folder_id_url ?>" data-value="form" class="menu-board-subset-item px-2 dropdown-item" style="font-size: 14px;"> <span class="board-subset-icon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> <span> Form </span></li>
            <?php else : ?>
                <li data-project-id="<?= $project_id ?>" data-folder-id="<?= $folder_id_url ?>" data-value="form" class="menu-board-subset-item px-2 dropdown-item" style="font-size: 14px;"> <span class="board-subset-icon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> <span> Form </span></li>
                <li data-project-id="<?= $project_id ?>" data-folder-id="<?= $folder_id_url ?>" data-value="file" class="menu-board-subset-item px-2 dropdown-item" style="font-size: 14px;"> <span class="board-subset-icon"><i class="fa fa-picture-o" aria-hidden="true"></i></span> <span> File gallery </span></li>
                <li data-project-id="<?= $project_id ?>" data-folder-id="<?= $folder_id_url ?>" data-value="calendar" class="menu-board-subset-item px-2 dropdown-item" style="font-size: 14px;"> <span class="board-subset-icon"><i class="fa fa-calendar" aria-hidden="true"></i></span> <span> Calendar </span></li>
                <li data-project-id="<?= $project_id ?>" data-folder-id="<?= $folder_id_url ?>" data-value="kanban" class="menu-board-subset-item px-2 dropdown-item" style="font-size: 14px;"> <span class="board-subset-icon"><i class="fa fa-list-alt" aria-hidden="true"></i> </span> <span> Kanban </span></li>
                <li data-project-id="<?= $project_id ?>" data-folder-id="<?= $folder_id_url ?>" data-value="gantt" class="menu-board-subset-item px-2 dropdown-item" style="font-size: 14px;"> <span class="board-subset-icon"><i class="fa fa-list-alt" aria-hidden="true"></i> </span> <span> Gantt </span></li>
            <?php endif; ?>

        </ul>
    </ul>
</div>

<script>
    // Handle add board subset item
    $("body").on("click", ".menu-board-subset-item", function() {
        const parent_id = $(this).attr("data-project-id");
        const title = $(this).attr("data-value");
        const type_id = 29;
        const user_id = <?= $this->session->userdata("user_id") ?>;
        const folder_id = $(this).attr("data-folder-id");

        const payload = {
            parent_id,
            title,
            type_id,
            user_id,
            folder_id
        }

        $.ajax({
            url: "<?= base_url("admin/items/add") ?>",
            method: "post",
            dataType: "json",
            data: payload,
            success: function(response) {
                if (response.success) {
                    $(".list-board-subset").find(".board-subset-item:last").after(response.data);
                }
            },
        });

    })

    // Handle delete board subset item
    $("body").on("click", ".btn-delete-subset", function() {
        const id = $(this).attr("data-subset-id");
        $.ajax({
            url: "<?= base_url("admin/items/delete/") ?>" + id,
            method: "post",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    const board_subset_item = $(`.board-subset-item[data-subset-id='${id}']`);
                    board_subset_item.remove();
                }
            },
        });
    })
</script>