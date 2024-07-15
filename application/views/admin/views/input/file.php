<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$data_id = isset($data_id) ? $data_id : "";
$width = isset($width) ? $width : "";
$showBtnClear = isset($showBtnClear) ? $showBtnClear : false;
$project = isset($project) ? $project : (object) array();
$group = isset($group) ? $group : (object) array();

$projectIdEnc = isset($project->id) ? $this->stringencryption->encryptString($project->id, $this->config->item("image_key")) : "";
$groupIdEnc = isset($group->id) ? $this->stringencryption->encryptString($group->id, $this->config->item("image_key")) : "";

$filePath = "items/file/" . $projectIdEnc . "/" . $groupIdEnc . "/";

?>

<div class="input-group input-group-table" style="width: 100%;">
    <div class="d-flex justify-content-center align-items-center w-100 file_meta_input" data-meta-id="<?= $meta_id ?>">
        <?php if ($value) : ?>
            <?php $files = $this->File_model->find_in_set($value); ?>
            <?php $fileTypes = explode("|", "pdf|doc|docx|xls|xlsx|ppt|pptx|rar|zip|svg"); ?>

            <span style="width: 20px;" class="btn-add-file" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-plus"></i></span>

            <div class="file_meta_list">
                <?php foreach ($files as $key => $file) : ?>

                    <?php

                    $fileUrl = "";
                    $fileUrlDownload = "";

                    $filePathExplode = explode("/", str_replace("uploads", "file", $file->path));


                    if (count($filePathExplode) >= 3) {
                        $fileUrl = $filePath . $filePathExplode[2];
                    } else {
                        $fileUrl = $file->path;
                    }

                    if (!in_array($this->session->userdata("user_id"), explode(",", $group->owners))) {
                        $fileUrl = "assets/images/data-encryption.png";
                    }

                    ?>

                    <div class="file_image_field file-image">

                        <?php if (!in_array($this->session->userdata("user_id"), explode(",", $group->owners))) : ?>
                            <img src="<?= base_url($fileUrl) ?>" alt="file_icon">
                        <?php else : ?>
                            <?php if ($file->type == 'jpeg' || $file->type ==  'png' || $file->type == 'jpg') : ?>
                                <img src="<?= base_url($fileUrl) ?>" alt="file_icon">
                            <?php elseif (in_array($file->type, $fileTypes)) : ?>
                                <img src="<?= base_url("assets/images/" . $file->type . ".svg") ?>" alt="file_icon">
                            <?php else : ?>
                                <img src="<?= base_url("assets/images/file-icon.svg") ?>" alt="file_icon">
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="file-info" hidden data-file-desc="<?= $file->description ?>" data-file-id="<?= $file->id ?>" data-file-type="<?= $file->type ?>" data-file-upload-date="<?= $file->created_at ?>" data-file-title="<?= $file->title ?>" data-file-path="<?= base_url($fileUrl) ?>"></div>

                    </div>
                    <?php if ($key == 2) break; ?>

                <?php endforeach; ?>

                <?php if (count($files) > 3) : ?>
                    <div class="extra-files-count" data-bs-toggle="dropdown" aria-expanded="false">
                        <span> +<?= count($files) - 3 ?> </span>
                    </div>
                <?php endif; ?>
                <ul class="dropdown-menu shadow" style="width: 290px; padding: 16px;">
                    <div class="row list_meta_item_dropdown">
                        <?php foreach ($files as $file) : ?>
                            <div class="col-12 mb-2">
                                <div class="row">
                                    <div class="col-2">
                                        <div class="meta_item_file_image">
                                            <?php

                                            $fileUrl = "";
                                            $filePathExplode = explode("/", str_replace("uploads", "file", $file->path));

                                            if (count($filePathExplode) >= 3) {
                                                $fileUrl = $filePath . $filePathExplode[2];
                                            } else {
                                                $fileUrl = $file->path;
                                            }

                                            ?>

                                            <?php if (!in_array($this->session->userdata("user_id"), explode(",", $group->owners))) : ?>
                                                <img src="<?= base_url("assets/images/data-encryption.png") ?>" alt="file_icon">
                                            <?php else : ?>
                                                <?php if ($file->type == 'jpeg' || $file->type ==  'png' || $file->type == 'jpg') : ?>
                                                    <img src="<?= base_url($fileUrl) ?>" alt="file_icon">
                                                <?php elseif (in_array($file->type, $fileTypes)) : ?>
                                                    <img src="<?= base_url("assets/images/" . $file->type . ".svg") ?>" alt="file_icon">
                                                <?php else : ?>
                                                    <img src="<?= base_url("assets/images/file-icon.svg") ?>" alt="file_icon">
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-10 d-flex align-items-center justify-content-between">
                                        <div class="text-truncate" style="font-size: 13px; width: 85%;"><?= $file->title ?></div>
                                        <div data-group-id="<?= $group->id ?>" data-project-id="<?= $project->id ?>" onclick="confirm('Xác nhận xóa?')" class="btn-clear-file text-danger" data-file-id="<?= $file->id ?>" data-meta-id="<?= $meta_id ?>"><i class="fa fa-times"></i></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <div>
                        <label class="py-2 d-flex justify-content-center align-items-center gap-2" style="font-size: 14px; cursor: pointer; width: 100%;" for="input_file_<?= $meta_id ?>">
                            <img src="<?= base_url("assets/images/upload.svg") ?>" width="20" alt="">
                            Tải lên
                        </label>
                    </div>
                </ul>
            </div>
        <?php else : ?>
            <label for="input_file_<?= $meta_id ?>" class="w-100 d-flex justify-content-center align-items-center gap-2" style="cursor: pointer; font-size: 14px;">
                <img src="<?= base_url("assets/images/upload.svg") ?>" width="20" alt="">
                Tải lên
            </label>
        <?php endif; ?>

        <input type="file" id="input_file_<?= $meta_id ?>" data-id="<?= $data_id ?>" data-group-id="<?= $group->id ?>" data-group-key-code="<?= isset($group->key_code) ? $group->key_code : "" ?>" data-meta-id="<?= $meta_id ?>" name="<?= $key ?>" multiple class="input_file_upload form-control input-table" hidden />
    </div>
</div>