<div class="modal" id="performance-review-modal" tabindex="-1" aria-labelledby="performance-review-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title  ">Đánh giá công việc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <?php foreach ($users as $user) : ?>
                        <div class="row py-2">
                            <div class="col-12">
                                <h6><?= $user->firstname . ' ' . $user->lastname ?></h6>
                            </div>

                            <ul class="number-scale py-2 text-center" data-user-id="<?= $user->id ?>" data-item-id="<?= $item->id ?>">
                                <li class="d-inline"><button class="btn btn-outline-success score mx-2" style="width: 50px; height: 50px;" data-score="1">1</button></li>
                                <li class="d-inline"><button class="btn btn-outline-success score mx-2" style="width: 50px; height: 50px;" data-score="2">2</button></li>
                                <li class="d-inline"><button class="btn btn-outline-success score mx-2" style="width: 50px; height: 50px;" data-score="3">3</button></li>
                                <li class="d-inline"><button class="btn btn-outline-success score mx-2" style="width: 50px; height: 50px;" data-score="4">4</button></li>
                                <li class="d-inline"><button class="btn btn-outline-success score mx-2" style="width: 50px; height: 50px;" data-score="5">5</button></li>
                                <li class="d-inline"><button class="btn btn-outline-success score mx-2" style="width: 50px; height: 50px;" data-score="6">6</button></li>
                                <li class="d-inline"><button class="btn btn-outline-success score mx-2" style="width: 50px; height: 50px;" data-score="7">7</button></li>
                                <li class="d-inline"><button class="btn btn-outline-success score mx-2" style="width: 50px; height: 50px;" data-score="8">8</button></li>
                                <li class="d-inline"><button class="btn btn-outline-success score mx-2" style="width: 50px; height: 50px;" data-score="9">9</button></li>
                                <li class="d-inline"><button class="btn btn-success score mx-2" style="width: 50px; height: 50px;" data-score="10">10</button></li>
                            </ul>
                            <div class="col-12">
                                <span class="p-2">Viết nhận xét cho <?= $user->lastname ?></span>
                                <textarea class="form-control desc" name="desc"></textarea>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-outline-success btn_submit_review_<?= $item->id ?>">Gửi</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('body').on('click', '.btn_submit_review_<?= $item->id ?>', function() {
        let data = [];

        const scoresList = $(this).closest('.modal-content').find('.modal-body .container .row');

        scoresList.each(function() {
            const userId = $(this).find('.number-scale').data('user-id');
            const itemId = $(this).find('.number-scale').data('item-id');
            const desc = $(this).find('.desc').val();

            let selectedScore = 10;

            $(this).find('.score').each(function() {
                if ($(this).hasClass('btn-success')) {
                    selectedScore = $(this).data('score');
                }
            });

            data.push({
                user_id: userId,
                item_id: itemId,
                score: selectedScore,
                desc: desc,
            });
        });

        $.ajax({
            url: '<?= base_url() ?>confirm/mark',
            data: {
                scores: data
            },

            method: 'post',
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $('#performance-review-modal').modal('hide');
                    toastr.success("Thêm đánh giá thành công!")
                } else {

                }
            }
        });
    });

    $('#performance-review-modal').on('hidden.bs.modal', function() {
        confirm_select_el.val(previousConfirmValue);
    });

</script>