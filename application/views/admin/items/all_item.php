<style>
    .gray-out {
        filter: grayscale(100%);
    }

    .input-search-task .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
    }
</style>

<nav>
    <div class="nav nav-tabs mt-2" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-item-tab" data-bs-toggle="tab" data-bs-target="#nav-all-item" type="button" role="tab" aria-controls="nav-all-item" aria-selected="true">Dự án</button>
        <button class="nav-link" id="nav-table-tab" data-bs-toggle="tab" data-bs-target="#nav-table-content" type="button" role="tab" aria-controls="nav-table-content" aria-selected="false">Bảng</button>
    </div>
</nav>

<div class="tab-content" id="nav-tabContent">

    <div class="input-search-task">
        <input type="text" id="search" class="form-control mt-3" placeholder="Tìm kiếm...">
    </div>
    <div class="tab-pane fade show active" id="nav-all-item" role="tabpanel" aria-labelledby="nav-item-tab">
        <br>
        <div class="row" id="view-mode-cards-all">
            <?php foreach ($items as $item) : ?>
                <?php if ($item->type_id == 6) : ?>
                    <div class="col-6 col-md-4 col-lg-3 mb-3 item" data-item="<?= $item->id ?>" data-type="task" data-title="<?= strtolower($item->title) ?>">
                        <div class="card-view">
                            <div class="card">
                                <a class="text-decoration-none" href="<?= base_url(); ?>table/view/<?= $item->id ?>" target="_blank">
                                    <img src="https://cdn.monday.com/images/quick_search_recent_board.svg" class="card-img-top <?= $item->deleted_at ? 'gray-out' : '' ?>" alt="...">
                                </a>
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <h5 class="card-title text-sm"><?= $item->title; ?></h5>
                                    <?php if ($item->deleted_at) : ?>
                                        <button class="btn btn-primary btn-restore" data-id="<?= $item->id ?>"><i class="fa fa-undo"></i></button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-table-content" role="tabpanel" aria-labelledby="nav-table-tab">
        <br>
        <div class="row" id="view-mode-cards-table">
            <?php foreach ($items as $item) : ?>
                <?php if ($item->type_id == 31) : ?>
                    <div class="col-6 col-md-4 col-lg-3 mb-3 item" data-item="<?= $item->id ?>" data-type="table" data-title="<?= strtolower($item->title) ?>">
                        <div class="card-view">
                            <div class="card">
                                <a class="text-decoration-none" href="<?= base_url(); ?>customtable/view/<?= $item->id ?>" target="_blank">
                                    <img src="https://cdn.monday.com/images/quick_search_recent_board.svg" class="card-img-top <?= $item->deleted_at ? 'gray-out' : '' ?>" alt="...">
                                </a>
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <h5 class="card-title text-sm"><?= $item->title; ?></h5>
                                    <?php if ($item->deleted_at) : ?>
                                        <button class="btn btn-primary btn-restore" data-id="<?= $item->id ?>"><i class="fa fa-undo"></i></button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.btn-restore').each(function() {
            $(this).on('click', function() {
                const itemId = $(this).data('id');
                const listItem = $(`li[data-project-id='${itemId}']`).closest(".list-project-item");
                console.log(listItem);

                $.ajax({
                    url: `<?= base_url() ?>admin/items/restore/${itemId}`,
                    method: "POST",
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            const item = $(`[data-item="${itemId}"]`);
                            item.find('.btn-restore').remove();
                            const img = item.find('.card-img-top');
                            if (img.length) {
                                img.removeClass('gray-out');
                            }
                            listItem.attr("hidden", false);
                            toastr.success("Khôi phục thành công!");
                        } else {
                            toastr.error("Khôi phục thất bại!");
                        }
                    },
                    error: function() {
                        toastr.error("Đã xảy ra lỗi khi khôi phục!");
                    }
                });
            });
        });

        $('#search').on('input', function() {
            const searchValue = $(this).val().toLowerCase();
            $('.item').each(function() {
                const title = $(this).data('title');
                if (title.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>