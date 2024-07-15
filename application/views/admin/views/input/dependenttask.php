<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$data_id = isset($data_id) ? $data_id : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$width = isset($width) ? $width : "";
$group = isset($group) ? $group : (object) array();

$query = $this->db->select("*")->from("items")->where("parent_id", $group->id)->where("id !=", $data_id)->get();

$tasks = $query->num_rows() > 0 ? $query->result_object() : [];

$items_of_meta = $this->Items_model->find_in_set($value);

$items_of_meta_ids = array_map(function ($item) {
    return $item->id;
}, $items_of_meta);

?>

<div class="input-group input-group-table d-flex justify-content-center align-items-center dropdown-center" data-item-id="<?= $data_id ?>">
    <!-- Item meta list -->
    <div class="item_meta_list" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="btn-add-item btn-add-item-<?= $data_id  ?> text-primary">
            <i class="fa fa-tasks"></i>
            <span class="item-count"><?= count($items_of_meta) ?></span>
        </span>
    </div>
    <!-- End Item meta list -->
    <div hidden>
        <div class="list-item-meta list-item-meta-body-<?= $data_id  ?>">
            <div class="row">
                <?php $item_meta_ids = array() ?>

                <?php foreach ($tasks as $task) : ?>
                    <div class="col-12 mb-2">
                        <div class="meta_item">
                            <input class="item-checkbox-<?= $meta_id ?>" type="checkbox" data-meta-id="<?= $meta_id ?>" data-project-id="<?= $project->id ?>" data-task-id="<?= $task->id ?>" data-key="<?= $key ?>" <?= in_array($task->id, $items_of_meta_ids) ? 'checked' : '' ?>>
                            <span><?= $task->title ?></span>
                        </div>
                    </div>
                <?php endforeach ?>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const options = {
            html: true,
            title: "",
            content: $('.list-item-meta-body-<?= $data_id  ?>')

        };

        const addItemEl = $('.btn-add-item-<?= $data_id  ?>');

       new bootstrap.Popover(addItemEl, options);

        $('html').on('click', function(e) {
            if (!$(e.target).closest('.popover').length && !$(e.target).is(addItemEl)) {
                addItemEl.popover('hide');
            }
        });

        $("body").on('click', '.item-checkbox-<?= $meta_id ?>', function() {
            const meta_id = $(this).attr("data-meta-id");
            const project_id = $(this).attr("data-project-id");
            const value = $(this).attr("data-task-id");
            const key = $(this).attr("data-key");
            const isChecked = $(this).is(':checked');
            console.log(key);

            const payload = {
                meta_id,
                project_id,
                value,
                type: isChecked ? "add_dependent_task" : "remove_dependent_task"
            }

            console.log(payload);

            $.ajax({
                url: "<?= base_url() ?>admin/items/update_meta",
                method: "post",
                data: payload,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success("Thay đổi đã được cập nhật!");
                        const checkedCount = $('.item-checkbox-<?= $meta_id ?>:checked').length;
                        $('.btn-add-item-<?= $data_id ?> .item-count').text(checkedCount);
                    }
                }
            })
        });
    });
</script>