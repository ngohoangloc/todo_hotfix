<div class="row">
    <?php if (isset($project)) : ?>
        <div class="col-md-8">
            <input class="project-title fs-4" data-project-id="<?= $project->id; ?>" value="<?= $project->title; ?>" />
        </div>

        <div class="col-md-2 text-end">
            <?php $this->load->view("templates/admin/invite", ['item_id' => $project->id, 'is_owner' => $is_owner]) ?>
        </div>

        <div class="col-md-2">
            <?php $this->load->view("templates/admin/logs", ['project_id' => $project->id]) ?>
        </div>

        <div class="my-3">
            <?php $this->load->view("templates/admin/board-toolbar", ['project_id' => $project->id]) ?>
            <!-- Btn add task -->
            <div class="form-actions">
                <div class="add-task-actions">
                    <!-- <button class="btn-add-task btn btn-sm btn-primary">Thêm công việc</button> -->
                    <!-- Search task form -->
                    <div class="input-search-task">
                        <div class="search-icon"><i class="fa fa-search" aria-hidden="true"></i></div>
                        <input type="text" placeholder="Tìm kiếm công việc...">
                    </div>
                </div>
            </div>
        </div>

        <div class="content">

        </div>

    <?php endif; ?>
</div>

<script>
    $('body').on('click', '.board-subset-item', function (e) {
        e.preventDefault();

        const subset_title = $(this).data('subset-title');

        $.ajax({
            url : <?= base_url() ?> + subset_title + "/" + <?= $project->id ?>,
            type : 'GET',
            data : [
                'item_id' : <?= $project->id ?>
            ],
            dataType : 'json',
            success : function(response) {

            },
        });
    });
</script>