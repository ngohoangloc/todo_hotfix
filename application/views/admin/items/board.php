<style>
    .input-text.form-control {
        border: none;
    }
</style>
<div class="row gray-200">
    <div class="col-md-2">
        <div class="col-12 btn-group mb-2">
            <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Thêm mới
            </button>
            <ul class="dropdown-menu">
                <?php foreach ($items_types as $type) : ?>
                    <li>
                        <a class="dropdown-item item-type" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-type-id="<?= $type->id ?>" data-type-title="<?= $type->title ?>">
                            <?= $type->title ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="card">
            <!-- Example single danger button -->
            <nav id="navbar-example3" class="h-100 flex-column align-items-stretch pe-4 border-end">
                <nav class="nav nav-pills flex-column">
                    <?php foreach ($items_all as $item) : ?>
                        <?php if ($item->parent_id ==  0) : ?>
                            <a class="nav-link items-list <?= $item->type_id == 7 ? 'disabled' : '' ?>" data-id="<?= $item->id ?>" data-id="<?= $item->id ?>" href="<?= base_url('items/' . $item->id) ?>"><?= $item->title ?></a>
                            <?php if ($item->type_id == 7) : ?>
                                <?php foreach ($this->Items_model->get_children($item->id) as $child) : ?>
                                    <nav class="nav nav-pills flex-column">
                                        <a class="nav-link ms-3 my-1 items-list" data-id="<?= $child->id ?>" href="<?= base_url('items/' . $child->id) ?>"><?= $child->title ?></a>
                                    </nav>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </nav>
            </nav>
        </div>
    </div>
    <div class="col-md-10">
        <div class="card">
            <div class="col-md-12">
                <div class="container-fluid">
                    <?php if (isset($project_item)) : ?>
                        <h2><?= $project_item[0]->title; ?></h2>
                        <nav class="navbar navbar-expand-lg bg-light">
                            <div class="container-fluid">
                                <div class="collapse navbar-collapse" id="navbarNav">
                                    <ul class="navbar-nav">
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="#">Main</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Features</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Pricing</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                        <div class="col-md-12">
                            <iframe id="show-view-iframe" src="<?= base_url('items/view/') . $project_item[0]->id ?>" width="100%" height="700"></iframe>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('items/add') ?>" method="post" id="addItemForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input hidden id="type_id" name="type_id" />
                    <div class="form-group mb-3">
                        <label for="title" class="form-label title_form"></label>
                        <input class="form-control" type="text" name="title" />
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">User Id</label>
                        <input class="form-control" type="text" name="user_id" value="1" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.item-type').click(function(e) {
            e.preventDefault();

            var type_id = $(this).data('type-id');
            var type_title = $(this).data('type-title').toLowerCase();

            console.log(type_id);

            $('#type_id').val(type_id);

            $('.title_form').text('Tên ' + type_title);

            $('#exampleModal').modal('show');
        });

        $('#addItemForm').submit(function (event) {
            event.preventDefault();

            var url = $(this).attr('action');

            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function (data) {
                    console.log(data);
                    $('#exampleModal').modal('hide');
                    $('#addItemForm')[0].reset();
                    location.reload();
                }
            });
        });
    });
</script>