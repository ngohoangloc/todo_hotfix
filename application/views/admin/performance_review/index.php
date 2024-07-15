<style>
    .group-row {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .group-row td {
        border-top: 2px solid #dee2e6;
    }
</style>


<ul class="nav nav-underline mt-2 nav_items_reports">
    <li class="nav-item statistic_nav_item" data-view="statistic">
        <a class="nav-link text-dark px-2 active" href="#statistic"><img src="<?= base_url('assets/icons/chart-waterfall-svgrepo-com.svg') ?>" alt="" width="20px"> Thống kê</a>
    </li>

    <li class="nav-item performance_review_nav_item" data-view="performance_review">
        <a class="nav-link text-dark px-2" aria-current="page" href="#performance_review"><img src="<?= base_url('assets/icons/report-text-svgrepo-com.svg') ?>" alt="" width="20px"> Báo cáo đánh giá</a>
    </li>

    <li class="nav-item list_detail_nav_item" data-view="list_detail">
        <a class="nav-link text-dark px-2" href="#list_detail"><img src="<?= base_url('assets/icons/detail-2-svgrepo-com.svg') ?>" alt="" width="20px"> Đánh giá chi tiết</a>
    </li>
</ul>

<div class="report_content">
    <div class="row p-2 performance_review_content" style="display: none">
        <div class="row text-center">
            <h2>BÁO CÁO ĐÁNH GIÁ NHÂN SỰ</h2>
        </div>

        <div class="row g-3 align-items-center performance_review_filters">
            <!-- Department Filter -->
            <div class="col-auto">
                <label for="select_department_filter" class="col-form-label">Đơn vị: </label>
            </div>

            <div class="col-auto">
                <select class="form-control select_department_filter" style="min-width: 150px;" data-type="department">
                    <option class="select_department_filter_item" value="">Tất cả</option>
                    <?php foreach ($departments as $department) : ?>
                        <option class="select_department_filter_item" value="<?= $department->id ?>"><?= $department->title ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Year Filter -->
            <div class="col-auto">
                <label for="select_year_filter" class="col-form-label">Năm: </label>
            </div>
            <div class="col-auto">
                <select class="form-control select_year_filter" style="min-width: 150px;" data-type="year">
                    <option class="select_year_filter_item" value="">Tất cả</option>
                    <?php
                    $minYear = PHP_INT_MAX;
                    $maxYear = PHP_INT_MIN;
                    foreach ($scores as $score) {

                        $year = date("Y", strtotime($score->created_at));

                        if ($year < $minYear) {
                            $minYear = $year;
                        }

                        if ($year > $maxYear) {
                            $maxYear = $year;
                        }
                    }

                    for ($year = $maxYear; $year >= $minYear; $year--) { ?>
                        <option class="select_year_filter_item" value="<?= $year ?>"><?= $year ?></option>
                    <?php } ?>
                </select>
            </div>
            <!-- Month Filter -->
            <div class="col-auto">
                <label for="select_month_filter" class="col-form-label">Tháng: </label>
            </div>
            <div class="col-auto">
                <select class="form-control select_month_filter" style="min-width: 150px;" data-type="month">
                    <option class="select_month_filter_item" value="">Tất cả</option>
                    <?php for ($month = 1; $month <= 12; $month++) { ?>
                        <option class="select_month_filter_item" value="<?= $month ?>">Tháng <?= $month ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-4">
                <input type="text" class="form-control" placeholder="Tìm kiếm...">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn_export_performance_review" style="min-width: 100px;"><i class="fa fa-file-excel-o icon_export"></i><i id="excel-export-loading" style="display: none;" class="fa fa-spinner fa-spin"></i> Xuất Excel</button>
            </div>

            <div class="col-auto">
                <button class="btn btn-primary btn_print_performance_review" style="min-width: 100px;"><i class="fa fa-print"></i> In</button>
            </div>
        </div>

        <div class="mt-3" id="performance_review_container">
        </div>
        <div class="col-12 text-center mt-5 loading_pane" style="display: none;">
            <div id="loading-spinner" class="spinner-border text-primary" role="status">
            </div>
        </div>
    </div>

    <div class="row p-2 list_detail_content" style="display: none">
        <div class="row text-center">
            <h2>ĐÁNH GIÁ CHI TIẾT NHÂN SỰ</h2>
        </div>

        <div class="row g-3 align-items-center performance_review_filters">
            <!-- Department Filter -->
            <div class="col-auto">
                <label for="select_department_filter" class="col-form-label">Đơn vị: </label>
            </div>

            <div class="col-auto">
                <select class="form-control select_department_filter" style="min-width: 150px;" data-type="department">
                    <option class="select_department_filter_item" value="">Tất cả</option>
                    <?php foreach ($departments as $department) : ?>
                        <option class="select_department_filter_item" value="<?= $department->id ?>"><?= $department->title ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Year Filter -->
            <div class="col-auto">
                <label for="select_year_filter" class="col-form-label">Năm: </label>
            </div>
            <div class="col-auto">
                <select class="form-control select_year_filter" style="min-width: 150px;" data-type="year">
                    <option class="select_year_filter_item" value="">Tất cả</option>
                    <?php
                    $minYear = PHP_INT_MAX;
                    $maxYear = PHP_INT_MIN;
                    foreach ($scores as $score) {

                        $year = date("Y", strtotime($score->created_at));

                        if ($year < $minYear) {
                            $minYear = $year;
                        }

                        if ($year > $maxYear) {
                            $maxYear = $year;
                        }
                    }

                    for ($year = $maxYear; $year >= $minYear; $year--) { ?>
                        <option class="select_year_filter_item" value="<?= $year ?>"><?= $year ?></option>
                    <?php } ?>
                </select>
            </div>
            <!-- Month Filter -->
            <div class="col-auto">
                <label for="select_month_filter" class="col-form-label">Tháng: </label>
            </div>
            <div class="col-auto">
                <select class="form-control select_month_filter" style="min-width: 150px;" data-type="month">
                    <option class="select_month_filter_item" value="">Tất cả</option>
                    <?php for ($month = 1; $month <= 12; $month++) { ?>
                        <option class="select_month_filter_item" value="<?= $month ?>">Tháng <?= $month ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-4">
                <input type="text" class="form-control" placeholder="Tìm kiếm...">
            </div>

            <div class="col-auto">
                <button class="btn btn-primary btn_export_performance_review" style="min-width: 100px;"><i class="fa fa-file-excel-o icon_export"></i><i id="excel-export-loading" style="display: none;" class="fa fa-spinner fa-spin"></i> Xuất Excel</button>
            </div>

            <!-- <div class="col-auto">
                <button class="btn btn-primary btn_print_performance_review" style="min-width: 100px;"><i class="fa fa-print"></i> In</button>
            </div> -->
        </div>

        <div class="mt-3" id="list_detail_container">
        </div>
        <div class="col-12 text-center mt-5 loading_pane" style="display: none;">
            <div id="loading-spinner" class="spinner-border text-primary" role="status">
            </div>
        </div>
    </div>

    <div class="row p-2 statistic_content" style="display: none">
        <div class="row text-center">
            <h2>THỐNG KÊ ĐÁNH GIÁ NHÂN SỰ</h2>
        </div>

        <div class="mt-3" id="statistic_container">
        </div>
        <div class="col-12 text-center mt-5 loading_pane" style="display: none;">
            <div id="loading-spinner" class="spinner-border text-primary" role="status">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        var filters = {
            search: '',
            year: '',
            department: '',
            month: '',
            limit: 10,
            offset: 1,
        };

        // init
        // $('[data-bs-toggle="tooltip"]').tooltip();
        $('.statistic_content').css('display', 'block');
        load_statistic_report(filters);

        // Handle click nav bar
        $('body').on('click', '.nav_items_reports .nav-item', function() {
            const view = $(this).data('view');
            const nav_item = $(this).children('.nav-link');

            filters = {
                year: '',
                department: '',
                month: '',
                limit: 10,
                offset: 1,
            };

            $('.performance_review_content, .statistic_content, .list_detail_content').css('display', 'none');
            $('.nav_items_reports .nav-link').removeClass('active');

            switch (view) {
                case 'performance_review':

                    $('.performance_review_content').css('display', 'block');
                    nav_item.addClass('active');

                    load_performance_review_report(filters);
                    break;

                case 'statistic':

                    $('.statistic_content').css('display', 'block');
                    nav_item.addClass('active');

                    load_statistic_report(filters);
                    break;

                default:

                    $('.list_detail_content').css('display', 'block');
                    nav_item.addClass('active');

                    load_list_detail_report(filters);
                    break;
            }
        });

        //Handle click filters
        $('.report_content').on('change', '.performance_review_filters select', function() {

            const selected_value = $(this).val();
            const selected_filter_type = $(this).data('type');

            if (selected_filter_type == 'department') {
                filters.department = selected_value;
            } else if (selected_filter_type == 'year') {
                filters.year = selected_value;
            } else {
                filters.month = selected_value;
            }

            console.log(filters);
            const activeLink = $('.nav_items_reports .nav-link.active');
            switch (activeLink.parent().data('view')) {
                case 'performance_review':
                    load_performance_review_report(filters)
                    break;

                default:
                    load_list_detail_report(filters);
                    break;
            }

        });

        $('.report_content').on('keyup', '.performance_review_filters input', function() {

            const value = $(this).val();

            filters.search = value;

            const activeLink = $('.nav_items_reports .nav-link.active');

            console.log(activeLink.parent().data('view'), filters)

            switch (activeLink.parent().data('view')) {
                case 'performance_review':
                    load_performance_review_report(filters)
                    break;

                default:
                    load_list_detail_report(filters);
                    break;
            }

        });

        $('.performance_review_content').on('click', '.btn_export_performance_review', function() {

            $('#excel-export-loading').show();
            $('.icon_export').hide();

            $.ajax({
                url: "<?= base_url() ?>score/export",
                method: "post",
                data: {
                    filters: filters
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response, status, xhr) {

                    if (response) {
                        var fileName = "";

                        var contentDisposition = xhr.getResponseHeader('Content-Disposition');

                        if (contentDisposition && contentDisposition.indexOf('attachment') !== -1) {
                            var startIndex = contentDisposition.indexOf('filename=') + 10;
                            var endIndex = contentDisposition.indexOf('.xlsx');
                            fileName = contentDisposition.substring(startIndex, endIndex + 4);
                        } else {
                            console.error('Không thể trích xuất tên file từ header Content-Disposition.');
                        }

                        var url = window.URL.createObjectURL(response);

                        var link = document.createElement('a');

                        link.href = url;
                        link.download = fileName;

                        document.body.appendChild(link);

                        link.click();

                        window.URL.revokeObjectURL(url);

                        $('#excel-export-loading').hide();
                        $('.icon_export').show();

                    } else {
                        console.error('Không có dữ liệu nhận được từ server.');
                    }
                },
            });
        });

        $('.performance_review_content').on('click', '.btn_print_performance_review', function() {

            $.ajax({
                url: "<?= base_url() ?>score/print",
                method: 'post',
                data: {
                    filters: filters
                },
                success: function(response) {
                    var printWindow = window.open('', '_blank');

                    printWindow.document.write(response);
                    printWindow.document.close();
                    printWindow.print();
                },
                error: function() {
                    alert('Failed to export data. Please try again.');
                }
            });
        });

        function load_performance_review_report(filters) {
            $('#performance_review_container').empty();
            $('.loading_pane').show();
            $.ajax({
                url: "<?= base_url() ?>score/show_performance_review_report",
                data: {
                    filters: filters,
                },
                method: 'get',
                dataType: 'json',
                success: function(res) {
                    $('#performance_review_container').html(res.data);
                },
                complete: function() {
                    $('.loading_pane').hide();
                },

            });
        }

        function load_list_detail_report(filters) {
            $('#list_detail_container').empty();
            $('.loading_pane').show();
            $.ajax({
                url: "<?= base_url() ?>score/show_list_detail_report",
                data: {
                    filters: filters,
                },
                method: 'get',
                dataType: 'json',
                success: function(res) {
                    $('#list_detail_container').html(res.data);
                },
                complete: function() {
                    $('.loading_pane').hide();
                },

            });
        }

        function load_statistic_report(filters) {

            $('#statistic_container').empty();

            $('.loading_pane').show();
            $.ajax({
                url: "<?= base_url() ?>score/show_statistic_report",
                data: {
                    filters: filters,
                },
                method: 'get',
                dataType: 'json',
                success: function(res) {
                    $('#statistic_container').html(res.data);
                },
                complete: function() {
                    $('.loading_pane').hide();
                },

            });
        }
    });
</script>