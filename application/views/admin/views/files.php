<style>
    .file-image {
        height: 180px;
        overflow: hidden;
    }

    .file-image img {
        width: 100%;

        height: 100%;
        /* object-fit: cover; */
    }

    .file-item {
        position: relative;
        cursor: pointer;
        transition: .3s;
        display: flex;
        align-items: center;
        flex-direction: column;
    }

    .file-item .file-image {
        transition: .4s;
    }

    .file-item:hover .file-image {
        transform: scale(1.05);
    }

    .file-heading {
        margin-top: 5px;
        font-size: 14px;
        padding-left: 30px;
    }

    .file-info {
        width: 100%;
    }

    .menu-bar {
        width: 25px;
        height: 25px;
        line-height: 25px;
        background: #eee;
        position: absolute;
        top: 0;
        right: 0;
        text-align: center;
        cursor: pointer;
        border-radius: 2px;
    }

    .menu_file_hover_list li {
        font-size: 14px;
        cursor: pointer;
        z-index: 1000;
    }

    .layout_buttons {
        display: flex;
        align-items: center;
    }

    .layout_buttons .layout_button_item {
        border: 1px solid #ddd;
        padding: 4px 10px;
        cursor: pointer;
    }

    .layout_buttons .layout_button_item:hover {
        background: #ddd;
    }

    .input-search-image {
        border: 1px solid var(--primary-color);
        outline: none;
        font-size: 15px;
        padding: 5px 30px;
        position: relative;
        width: fit-content;

    }
</style>
<?php
$folder_id_url = $this->uri->segment(3);
$project_id_url = $this->uri->segment(4);
?>
<div class="row px-3">
    <div class="col-9">
        <input class="project-title fs-4" data-project-id="<?= $project_item->id; ?>" value="<?= $project_item->title; ?>" />
    </div>
    <div class="col-3">
        <?php $this->load->view("templates/admin/logs", ['project' => $project_item]) ?>
    </div>
    <div class="mb-3">
        <?php $this->load->view("templates/admin/board-toolbar", ['project_id' => $project_item->id, 'parent_id' => $project_item->parent_id, 'folder_id_url' => $folder_id_url]) ?>
    </div>

    <div class="card border border-primary">
        <div class="card-header">
            File gallery
        </div>
        <div class="card-body">
            <?php if (count($files) > 0) : ?>
                <div class="row">
                    <div class="col-12 col-sm-9 mb-2">
                        <input class="input-search-image" type="text" placeholder="Tìm kiếm hình ảnh...">
                    </div>
                    <div class="col-12 col-sm-3 d-flex justify-content-end">
                        <span class="me-2" style="line-height: 34px;">Hiển thị: </span>

                        <ul class="layout_buttons p-0">
                            <li class="layout_button_item" value="grid"><svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20" aria-hidden="true" class="icon_1b49d21cea noFocusStyle_fea810e042" data-testid="icon">
                                    <path d="M3 2.25C2.58579 2.25 2.25 2.58579 2.25 3V8.38462C2.25 8.79883 2.58579 9.13462 3 9.13462H8.38462C8.79883 9.13462 9.13462 8.79883 9.13462 8.38462V3C9.13462 2.58579 8.79883 2.25 8.38462 2.25H3ZM3.75 7.63462V3.75H7.63462V7.63462H3.75ZM11.6154 2.25C11.2012 2.25 10.8654 2.58579 10.8654 3V8.38462C10.8654 8.79883 11.2012 9.13462 11.6154 9.13462H17C17.4142 9.13462 17.75 8.79883 17.75 8.38462V3C17.75 2.58579 17.4142 2.25 17 2.25H11.6154ZM12.3654 7.63462V3.75H16.25V7.63462H12.3654ZM2.25 11.6154C2.25 11.2012 2.58579 10.8654 3 10.8654H8.38462C8.79883 10.8654 9.13462 11.2012 9.13462 11.6154V17C9.13462 17.4142 8.79883 17.75 8.38462 17.75H3C2.58579 17.75 2.25 17.4142 2.25 17V11.6154ZM3.75 12.3654V16.25H7.63462V12.3654H3.75ZM11.6154 10.8654C11.2012 10.8654 10.8654 11.2012 10.8654 11.6154V17C10.8654 17.4142 11.2012 17.75 11.6154 17.75H17C17.4142 17.75 17.75 17.4142 17.75 17V11.6154C17.75 11.2012 17.4142 10.8654 17 10.8654H11.6154ZM12.3654 16.25V12.3654H16.25V16.25H12.3654Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                                </svg></li>
                            <li class="layout_button_item" value="list"><svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20" aria-hidden="true" class="icon_1b49d21cea noFocusStyle_fea810e042" data-testid="icon">
                                    <g clip-path="url(#clip0)">
                                        <path d="M1.99805 2.77233C1.99805 2.34471 2.34471 1.99805 2.77233 1.99805H17.2257C17.6533 1.99805 18 2.34471 18 2.77233V7.58989V12.4079V17.2257C18 17.6533 17.6533 18 17.2257 18H2.77233C2.34471 18 1.99805 17.6533 1.99805 17.2257V12.4079V7.58989V2.77233ZM3.54662 13.1822V16.4514H16.4514V13.1822H3.54662ZM16.4514 11.6336H3.54662V8.36417H16.4514V11.6336ZM16.4514 3.54662V6.8156H3.54662V3.54662H16.4514Z" fill-rule="evenodd" clip-rule="evenodd"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0">
                                            <path transform="translate(2 2)" d="M0 0H16V16H0z"></path>
                                        </clipPath>
                                    </defs>
                                </svg></li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row file_list">
                <?php if (count($files) > 0) : ?>

                    <?php

                    $fileTypes = explode("|", "pdf|doc|docx|xls|xlsx|ppt|pptx|rar|zip");
                    $projectIdEnc = $this->stringencryption->encryptString($project_item->id, $this->config->item("image_key"));

                    ?>

                    <?php foreach ($files as $key => $file) : ?>

                        <?php
                        $filePathExplode = explode("/", str_replace("uploads", "file", $file->path));

                        $item_meta = $this->Items_model->get_meta_by_id($file->item_meta_id);
                        $item = $this->Items_model->find_by_id($item_meta->items_id);
                        $group = $this->Items_model->find_by_id($item->parent_id);

                        $groupIdEnc = $this->stringencryption->encryptString($group->id, $this->config->item("image_key"));


                        $filePath = "items/file/" . $projectIdEnc . "/" . $groupIdEnc . "/";

                        $fileUrl = "";

                        if (count($filePathExplode) >= 3) {
                            $fileUrl = $filePath . $filePathExplode[2];
                        } else {
                            $fileUrl = $file->path;
                        }

                        ?>

                        <div class="file_list_item col-6 col-lg-3 mb-3" data-file-id="<?= $file->id ?>">
                            <div class="file-item border p-2 rounded-2">
                                <div class="file-image" data-file-desc="<?= $file->description ?>" data-file-id="<?= $file->id ?>" data-file-type="<?= $file->type ?>" data-file-upload-date="<?= $file->created_at ?>" data-file-title="<?= $file->title ?>" data-file-path="<?= base_url($filePath . $filePathExplode[2]) ?>">
                                    <?php if ($file->type == 'jpeg' || $file->type ==  'png' || $file->type == 'jpg') : ?>

                                        <img src="<?= base_url($filePath . $filePathExplode[2]) ?>" alt="file_icon">

                                    <?php elseif (in_array($file->type, $fileTypes)) : ?>
                                        <img src="<?= base_url("/assets/images/$file->type.svg") ?>" alt="file_icon">
                                    <?php else : ?>
                                        <img src="<?= base_url("/assets/images/file-icon.svg") ?>" alt="file_icon">
                                    <?php endif; ?>
                                    <div class="file-info" hidden data-file-url-download="<?= base_url($fileUrlDownload) ?>" data-file-desc="<?= $file->description ?>" data-file-id="<?= $file->id ?>" data-file-type="<?= $file->type ?>" data-file-upload-date="<?= $file->created_at ?>" data-file-title="<?= $file->title ?>" data-file-path="<?= base_url($fileUrl) ?>"></div>
                                </div>

                                <div class="file-info">
                                    <div class="file-heading text-truncate">
                                        <span>
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="file-title" data-file-id="<?= $file->id ?>">
                                                <?= $file->title ?>
                                            </span>
                                            <span class="file-desc" data-file-id="<?= $file->id ?>" hidden>
                                                <?= $file->description ?>
                                            </span>
                                        </span>
                                        <br>
                                        <span>
                                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                                            <?= $file->created_at ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="menu-bar" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                </div>
                                <ul class="menu_file_hover_list dropdown-menu shadow" style="max-width: 290px; padding: 10px;">
                                    <li class="btn-download-file dropdown-item">
                                        <a class="d-flex align-items-center gap-2 text-dark" href="<?= base_url($file->path) ?>" download>
                                            <img width="20" src="<?= base_url("assets/images/download.svg") ?>" alt="">
                                            Tải xuống
                                        </a>
                                    </li>
                                    <li onclick="confirm('Xác nhận xóa?')" class="btn-clear-file dropdown-item d-flex align-items-center gap-2 text-dark" data-meta-id="<?= $file->item_meta_id ?>" data-file-id="<?= $file->id ?>">
                                        <img width="20" src="<?= base_url("assets/images/delete.svg") ?>" alt=""> Xóa
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="text-center">
                        <img class="w-25" src="https://cdn.monday.com/images/files-gallery/empty-state.svg">
                        <p class="mt-3">Không tìm thấy file!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal show image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="me-2">Tên tệp: </strong> <span class="modal-title file-name"></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-8 border-end">
                        <div class="image-preview-modal">
                            <img src="" class="image-preview" alt="image-preview">
                        </div>  
                    </div>
                    <div class="col-4">
                        <div class="image-info">
                            <h6>Thông tin</h6>
                            <div class="mt-2 form-group mb-3">
                                <input type="text" class="file_info_description form-control" placeholder="Thêm mô tả...">
                            </div>
                            <div class="form-group mb-3">
                                <ul class="m-0 p-0" class="file_info_list">
                                    <li class="py-2 file_info_item">
                                        <span class="fw-bold">Tên tệp: </span>
                                        <input class="file_info_name form-control" />
                                    </li>
                                    <li class="py-2 file_info_item">
                                        <span class="fw-bold">Định dạng: </span>
                                        <span class="file_info_type"></span>
                                    </li>
                                    <li class="py-2 file_info_item">
                                        <span class="fw-bold">Ngày tải lên: </span>
                                        <span class="file_info_upload_date"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="btn-download-file-modal form-group border text-center p-2">
                                <i class="fa fa-download" aria-hidden="true"></i> Tải xuống
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(".layout_button_item").click(function() {
        const value = $(this).attr("value");

        switch (value) {
            case "grid":
                $(".file-item").parent().removeClass("col-12").addClass("col-6 col-lg-3");

                $(".file-item").css({
                    "flex-direction": "column",
                    "gap": "0px"
                });

                $(".file-image").css({
                    "width": "100%",
                    "height": "180px"
                });

                $(".file-image img").css({
                    "object-fit": "contain",
                });

                $(".file-heading").css("width", "100%");

                break;

            case "list":
                $(".file-item").parent().removeClass("col-6 col-lg-3").addClass("col-12");

                $(".file-item").css({
                    "flex-direction": "row",
                    "gap": "10px"
                });

                $(".file-image").css({
                    "width": "300px",
                });

                $(".file-info").css({
                    "width": "80% !important",
                });

                $(".file-image img").css({
                    "object-fit": "contain",
                });

                break;
            default:
                break;
        }
    })

    // Handle search image
    $(".input-search-image").keyup(function() {
        const value = $(this).val();

        $(".file_list .file_list_item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });

    })
</script>