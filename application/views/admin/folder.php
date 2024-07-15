<?php
$page_url = $this->uri->segment(1);
$folder_id_url = $this->uri->segment(3);
$project_id_url = $this->uri->segment(4);
$userId = $this->session->userdata('user_id');
?>
<style>
    .input-text.form-control {
        border: none;
    }
</style>

<?php $folders = $this->Items_model->get_child_folder_by_user($folder_id_url, $userId); ?>

<?php foreach ($folders as $folder) : ?>
    <div class="col-12 col-md-8 col-lg-7 d-flex align-items-center pt-2">
        <span class="btn-collapse" style="font-size: 14px;" data-bs-toggle="collapse" href="#collapseItemsGeneral-<?= $folder->id ?>" aria-controls="collapseItemsGeneral-<?= $folder->id ?>">
            <i class="fa fa-chevron-down text-primary" aria-hidden="true"></i>
        </span>
        <h4 class="m-2 text-primary"><?= $folder->title ?></h4>
    </div>

    <?php $projects = $this->Items_model->get_project_by_user($folder->id, $userId); ?>
    <div class="row m-0 p-0 pt-3 collapse show" id="collapseItemsGeneral-<?= $folder->id ?>">
        <?php foreach ($projects as $item) : ?>
            <?php if (!$item->is_private && !$item->is_archived) : ?>
                <?php if ($item->type_id == 7) : ?>
                    <?php foreach ($this->Items_model->get_child_items($item->id) as $child) : ?>
                        <div class="col-6 col-md-3 col-lg-2 mb-3">
                            <a class="text-decoration-none" href="<?= base_url(); ?>table/view/<?= $folder_id_url ?>/<?= $child->id ?>">
                                <div class="card">
                                    <img src="https://cdn.monday.com/images/quick_search_recent_board.svg" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title text-truncate text-sm"><?= $child->title; ?></h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($item->type_id == 32) : ?>
                    <div class="col-6 col-md-3 col-lg-2 mb-3">
                        <a class="text-decoration-none" href="<?= base_url(); ?>timetable/view/<?= $folder_id_url ?>/<?= $item->id ?>">
                            <div class="card">
                                <img src="https://cdn.monday.com/images/quick_search_recent_board.svg" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate text-sm"><?= $item->title; ?></h5>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php elseif ($item->type_id == 31) : ?>
                    <div class="col-6 col-md-3 col-lg-2 mb-3">
                        <a class="text-decoration-none" href="<?= base_url(); ?>customtable/view/<?= $folder_id_url ?>/<?= $item->id ?>">
                            <div class="card">
                                <img src="https://cdn.monday.com/images/quick_search_recent_board.svg" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate text-sm"><?= $item->title; ?></h5>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="col-6 col-md-3 col-lg-2 mb-3">
                        <a class="text-decoration-none" href="<?= base_url(); ?>table/view/<?= $folder_id_url ?>/<?= $item->id ?>">
                            <div class="card">
                                <img src="https://cdn.monday.com/images/quick_search_recent_board.svg" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate text-sm"><?= $item->title; ?></h5>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>