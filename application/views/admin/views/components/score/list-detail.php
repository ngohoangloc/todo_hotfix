<?php
    $i = 1;
    $total_pages = ceil($total_rows / $limit);
?>

<style>
    .custom-tooltip .tooltip-inner {
        background-color: #333;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 14px;
    }
</style>


<div class="content">
    <table class="table table-striped" style="font-size: 90%;">
        <thead class="table-primary">
            <tr>
                <th scope="col">STT</th>
                <th scope="col" style="width: 15%;">Tên nhân sự</th>
                <th scope="col" style="width: 15%;">Đơn vị</th>
                <th scope="col">Công việc</th>
                <th scope="col">Nhóm công việc</th>
                <th scope="col">Dự án</th>
                <th scope="col" class="text-center">Điểm</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($scores as $score) : ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td class="text-bold"><?= $score->firstname . ' ' . $score->lastname ?></td>
                    <td class="text-secondary"><?= $score->department_name ?></td>
                    <td><?= $score->item->title ?></td>
                    <td><?= $score->group->title ?></td>
                    <td><?= $score->project->title ?></td>
                    <td class="text-center fw-bold text-primary"><?= $score->score ?></td>
                    <td >
                        <span data-bs-placement="left" data-bs-toggle="tooltip" data-bs-title="<?= $score->desc ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#2196f3" fill="none">
                                <path d="M2.5 12C2.5 7.52166 2.5 5.28249 3.89124 3.89124C5.28249 2.5 7.52166 2.5 12 2.5C16.4783 2.5 18.7175 2.5 20.1088 3.89124C21.5 5.28249 21.5 7.52166 21.5 12C21.5 16.4783 21.5 18.7175 20.1088 20.1088C18.7175 21.5 16.4783 21.5 12 21.5C7.52166 21.5 5.28249 21.5 3.89124 20.1088C2.5 18.7175 2.5 16.4783 2.5 12Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M12.2422 17V12C12.2422 11.5286 12.2422 11.2929 12.0957 11.1464C11.9493 11 11.7136 11 11.2422 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M11.992 8H12.001" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </td>
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