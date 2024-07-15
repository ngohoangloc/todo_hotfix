<div class="col-12 mb-2">
    <div class="row">
        <div class="col-2">
            <div class="meta_item_file_image" style="background: #ddd;">
                <?php if ($file->type == 'jpeg' || $file->type ==  'png' || $file->type == 'jpg') : ?>
                    <img src="<?= base_url($file->path) ?>" alt="file_icon">
                <?php elseif (in_array($file->type, $fileTypes)) : ?>
                    <img src="<?= base_url("/assets/images/$file->type.svg") ?>" alt="file_icon">
                <?php else : ?>
                    <img src="<?= base_url("/assets/images/file-icon.svg") ?>" alt="file_icon">
                <?php endif; ?>
            </div>
        </div>
        <div class="col-10 d-flex align-items-center justify-content-between">
            <span class="text-truncate" style="font-size: 13px;"><?= $file->title ?></span>
            <div class="btn-clear-file text-danger" data-file-id="<?= $file->id ?>" data-meta-id="<?= $meta_id ?>"><i class="fa fa-times"></i></div>
        </div>
    </div>
</div>