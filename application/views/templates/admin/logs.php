<style>
    .log-name span {
        display: inline-block;
        width: 40px;
        height: 40px;
        text-align: center;
        line-height: 40px;
        background: var(--primary-color);
        border-radius: 50%;
        color: #fff;
    }

    @media (max-width: 767px) {
        .title_logs {
            display: none;
        }
    }
</style>
<?php
$project_id = isset($project_id) ? $project_id : "";
?>
<button class="btn btn-outline-primary btn-sm px-2" id="btn-show-active" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fa fa-history"></i> <span class="title_logs">Hoạt động</span></button>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" style="width: 40%;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Hoạt động</h5>
        <button type="button" class="btn-close btn-close-active" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <div class="row py-3">
                        <div class="col-2">
                            <button class="btn btn-ms btn-primary text-center w-100" id="reportrange">
                                Lọc <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="col-4">
                            <input type="text" class="form-control" id="search-logs-input" placeholder="Tìm kiếm hoạt động...">
                        </div>
                        <div class="col-6 text-end">
                            <button class="btn btn-sm btn-outline-success" id="export-logs-to-excel-btn" data-project-id="<?= $project_id ?>">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel
                                <i id="excel-export-loading" style="display: none;" class="fa fa-spinner fa-spin"></i>
                            </button>
                        </div>
                    </div>
                </tr>
            </thead>
            <tbody class="list-logs border">

            </tbody>
        </table>

        <div id="loading-icon">
            <div class="d-flex justify-content-center align-items-center">
                <div class="spinner-border text-primary d-flex justify-content-center align-items-center" role="status">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        let page = 0;
        let isLoading;
        let reachedMax;
        let logs_data;
        let start_date;
        let end_date;

        $("#search-logs-input").on("keyup", function() {
            let value = $(this).val().toLowerCase();

            $(".list-logs tr").filter(function() {

                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)

            });
        });

        $('.offcanvas-body').scroll(function() {

            let height = Math.floor($('.list-logs').height()) - Math.floor($(this).height());

            let top = Math.floor($(this).scrollTop());

            if (height <= top && reachedMax == false && isLoading == false) {

                page++;

                console.log(height, top, page)

                loadLogs(page, start_date, end_date);
            }
        });

        $('#btn-show-active').click(function() {
            $('.list-logs').empty();
            page = 1;
            isLoading = false;
            reachedMax = false;
            start_date = null;
            end_date = null;
            loadLogs(page, start_date, end_date);
        });

        $('#export-logs-to-excel-btn').click(function() {
            $('#excel-export-loading').show();
            $.ajax({
                url: "<?= base_url() ?>items/logs/export",
                method: "post",
                data: {
                    project_id: $(this).data('project-id'),
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
                            var endIndex = contentDisposition.indexOf('.xls');
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
                        loadLogs(page);
                    } else {
                        console.error('Không có dữ liệu nhận được từ server.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi khi gửi yêu cầu Ajax: ', error);
                },
                complete: function() {
                    $('#excel-export-loading').hide();
                }
            });
        });

        function loadLogs(page, start_date, end_date) {
            $('#loading-icon').show();

            if (page == 1) {
                $('.list-logs').empty();
            }

            isLoading = true;

            $.ajax({
                url: "<?= base_url('items/logs/') . $project->id ?>",
                type: 'GET',
                dataType: 'json',
                data: {
                    page: page,
                    limit: 20,
                    start_date: start_date,
                    end_date: end_date
                },
                success: function(response) {
                    if (response.success) {
                        if (response.data.length > 0) {

                            logs_data = response.data;

                            $.each(logs_data, function(index, log) {
                                if (log.value_old !== '' || log.value_new !== '') {
                                    var value_old = log.value_old !== null ? '<span class="log-value-old">' + log.value_old + '</span>' + '<i class="fa fa-angle-right px-3 text-body-tertiary" aria-hidden="true"></i>' : '';
                                    var value_new = log.value_new !== null ? '<span class="log-value-new">' + log.value_new + '</span>' : '';
                                    var row = '<tr>' +
                                        '<td class="w-5">' +
                                        '<i class="fa fa-history" aria-hidden="true"></i>' +
                                        ' <span class="log-time">' + log.created + '</span>' +
                                        '</td>' +
                                        '<td class="log-name text-center">' + log.user.firstname + ' ' + log.user.lastname + '</td>' +
                                        // '<td class="w-25 log-title">' + log.title + '</td>' +
                                        '<td class="log-description">' + value_old + value_new + '</td>' +
                                        '</tr>';
                                    $('.list-logs').append(row);
                                }
                            });

                            $('#loading-icon').hide();

                        } else {
                            $('.list-logs').append("<td colspan='4' class='text-center'>Không tìm thấy dữ liệu!</td>");
                            $('#loading-icon').hide();

                            reachedMax = true;
                        }
                    } else {
                        console.error('Error:', response.message);
                    }
                    isLoading = false;
                },
                error: function(xhr, status, error) {
                    $('#loading-icon').hide();
                    console.error('Error:', error);
                    isLoading = false;
                }
            });
        }



        $(function() {
            var start = moment().subtract(29, 'days');
            var end = moment();
            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'right',
                locale: {
                    format: 'DD/MM/YYYY',
                    applyLabel: 'Áp dụng',
                    cancelLabel: 'Hủy',
                    fromLabel: 'Từ',
                    toLabel: 'Đến',
                    customRangeLabel: 'Tùy chỉnh',
                    daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                    monthNames: [
                        'Tháng 1',
                        'Tháng 2',
                        'Tháng 3',
                        'Tháng 4',
                        'Tháng 5',
                        'Tháng 6',
                        'Tháng 7',
                        'Tháng 8',
                        'Tháng 9',
                        'Tháng 10',
                        'Tháng 11',
                        'Tháng 12'
                    ],
                    firstDay: 1
                },
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Tuần này': [moment().subtract(6, 'days'), moment()],
                    'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end) {
                $('.list-logs').empty();
                start_date = start.format('YYYY-MM-DD');
                end_date = end.format('YYYY-MM-DD');
                page = 1;
                reachedMax = false;
                loadLogs(page, start_date, end_date);
            });
        });
    });
</script>