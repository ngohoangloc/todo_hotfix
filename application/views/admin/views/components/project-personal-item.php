<?php
$type_url = "";
$project_icon = "";
$parent = $this->Items_model->find_by_id($personalProject->parent_id);
$project_id_url = isset($project_id_url) ? $project_id_url : "";

switch ($personalProject->type_id) {
    case $this->config->item("TIMETALBE_ID"):
        $type_url = base_url("timetable/view/$parent->parent_id/$personalProject->id");
        $project_icon = "calendar";
        break;
    case $this->config->item("TALBE_ID"):
        $type_url = base_url("customtable/view/$parent->parent_id/$personalProject->id");
        $project_icon = "table";
        break;
    case $this->config->item("DEPARTMENT_ID"):
        $type_url = base_url("table/view/$parent->parent_id/$personalProject->id");
        $project_icon = "user";
        break;
    case $this->config->item("FOLDER_ID"):
        $type_url = "#";
        $project_icon = "folder";
        break;
    default:
        $type_url = base_url("table/view/$parent->parent_id/$personalProject->id");
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