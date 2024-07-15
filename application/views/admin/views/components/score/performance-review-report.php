<?php
    $last_department = null;
    $i = 1;
    $total_pages = ceil($total_rows / $limit);
?>
<div class="content">
    <table class="table table-striped"  style="font-size: 90%;">
        <thead class="table-primary">
            <tr>
                <th scope="col">STT</th>
                <th scope="col">Tên nhân sự</th>
                <th scope="col">Chức vụ</th>
                <th scope="col">Đơn vị</th>
                <th scope="col" class="text-center">Điểm</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($avg_scores_group_by_user as $score) : ?>
                <?php if ($last_department !== $score->department_name) : // Kiểm tra nếu phòng ban thay đổi 
                ?>
                    <tr class="group-row">
                        <td colspan="5"><?= $score->department_name ?></td>
                    </tr>
                    <?php $last_department = $score->department_name; // Cập nhật phòng ban cuối cùng 
                    ?>
                    <?php $i = 1; // Reset số thứ tự cho phòng ban mới 
                    ?>
                <?php endif; ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $score->firstname . ' ' . $score->lastname ?></td>
                    <td>Nhân Viên</td>
                    <td><?= $score->department_name ?></td>
                    <td class="text-center"><?= ($score->average_score - floor($score->average_score) == 0) ? floor($score->average_score) : number_format($score->average_score, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Pagination -->
    <div class="row" style="position: absolute; right: 20px;">
        <nav>
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" id="pagination_previous" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
                <li class="page-item">
                    <a class="page-link" id="pagination_next" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('.pagination .page-link').eq(1).addClass('active');

        // Xử lý sự kiện click vào phân trang
        $('.pagination').on('click', '.page-link', function(event) {

            event.preventDefault();

            // Kiểm tra nếu nhấn vào button previous hoặc next
            if ($(this).attr('id') === 'pagination_previous') {
                let activePage = $('.pagination .page-link.active').parent().prev().find('.page-link');
                if (activePage.length && !activePage.attr('aria-label')) {
                    $('.pagination .page-link').removeClass('active');
                    activePage.addClass('active');
                }
            } else if ($(this).attr('id') === 'pagination_next') {
                let activePage = $('.pagination .page-link.active').parent().next().find('.page-link');
                if (activePage.length && !activePage.attr('aria-label')) {
                    $('.pagination .page-link').removeClass('active');
                    activePage.addClass('active');
                }
            } else {
                $('.pagination .page-link').removeClass('active');
                $(this).addClass('active');
            }
        });
    });
</script>