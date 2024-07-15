<a href="<?= base_url("folder/view/" . $folder->id) ?>">
    <div class="list_project_main_item" data-bs-toggle="tooltip" data-bs-title="<?= $folder->title ?>" data-folder-id="<?= $folder->id ?>">
        <?php

        $thumbnail = $folder->thumbnail;

        if (empty($thumbnail)) {
            $thumbnail = "assets/images/folder.svg";
        }

        ?>

        <img style="width: 70%; height: 70%; object-fit: cover; border-radius: 50%;" src="<?= base_url($thumbnail) ?>" alt="">
    </div>
</a>