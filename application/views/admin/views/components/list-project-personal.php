<div class="group_list_project_personal_heading">
    <div class="d-flex align-items-center gap-2">
        <span class="text-primary btn-collapse" style="font-size: 13px;" data-bs-toggle="collapse" href="#collapseListProjectPersonal" role="button" aria-expanded="false" aria-controls="collapseListProjectPersonal">
            <i class="fa fa-chevron-down" aria-hidden="true"></i>
        </span>
        <h6 class="m-0 text-primary">Dự án cá nhân</h6>
    </div>
    <span data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
        <img data-bs-toggle="tooltip" data-bs-title="Thêm dự án" width="20" src="<?= base_url("assets/images/plus.svg") ?>" alt="">
    </span>

    <ul class="dropdown-menu" style="z-index: 10;">
        <?php foreach ($project_type as $type) : ?>
            <li class="type-select-id" style="font-size: 14px;" data-parent-id="<?= $folder->id ?>" data-bs-toggle="modal" data-bs-target="#modalCreateProject" data-type-id="<?= $type->id ?>"><span type="button"><span>
                        <img src="<?= base_url("assets/images/plus-circle.svg") ?>" width="20" alt="">
                    </span> Tạo <?= $type->title ?> </span></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="collapse show" id="collapseListProjectPersonal">
    <?php if (count($personalProjects) > 0) : ?>

        <?php foreach ($personalProjects as $key => $personalProject) : ?>
            <?php
            $type_url = "";
            $project_icon = "";

            switch ($personalProject->type_id) {
                case $this->config->item("TIMETALBE_ID"):
                    $type_url = base_url("timetable/view/$personalProject->id");
                    $project_icon = "calendar";
                    break;
                case $this->config->item("TALBE_ID"):
                    $type_url = base_url("customtable/view/$personalProject->id");
                    $project_icon = "table";
                    break;
                case $this->config->item("DEPARTMENT_ID"):
                    $type_url = base_url("table/view/$personalProject->id");
                    $project_icon = "user";
                    break;
                case $this->config->item("FOLDER_ID"):
                    $type_url = "#";
                    $project_icon = "folder";
                    break;
                default:
                    $type_url = base_url("table/view/$personalProject->id");
                    $project_icon = "task";
                    break;
            }

            ?>

            <a href="<?= $type_url ?>" class="text-secondary" data-project-id="<?= $personalProject->id ?>">
                <div class="group_list_project_personal_item <?= $personalProject->id == $project_id_url ? "active" : "" ?>">
                    <div class="group_list_project_personal_item_image">
                        <img src="<?= base_url("assets/images/" . $project_icon . ".svg") ?>" alt="">
                    </div>
                    <div class="group_list_project_personal_item_title text-truncate">
                        <?= $personalProject->title ?>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="text-center">Không tìm thấy dự án!</p>
    <?php endif; ?>
</div>