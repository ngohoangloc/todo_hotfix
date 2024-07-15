<div class="row py-3 px-4">
    <div class="row">
        <h1>Quản lý Log</h1>
    </div>
    <div class="row">
        <table id="logs-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>Thời gian</th>
                    <th>Tiêu đề</th>
                    <th>Hành động</th>
                    <th>Người dùng</th>
                    <th>Giá trị cũ</th>
                    <th>Giá trị mới</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    const fetchData = () => {
        $(document).ready(function() {
            $('#logs-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?php echo base_url(); ?>logs/get_all_logs",
                    "type": "GET",
                    "dataType": "json",
                    "data": function(d) {
                        d.start = d.start;
                        d.length = d.length;
                    }
                },
                columns: [{
                        "data": "created_at",
                        "render": function(data) {
                            return '<i class="fa fa-history" aria-hidden="true"></i> ' + data;
                        }
                    },
                    {
                        "data": "title",
                    },
                    {
                        "data": "type",
                    },
                    {
                        "data": "user",
                        "render": function(data) {
                            return data.firstname + ' ' + data.lastname;
                        }
                    },
                    {
                        "data": "value_old",
                    },
                    {
                        "data": "value_new",
                    },
                    {
                        "data": "type",
                        "render": function(data, type, row) {
                            if (data == "DELETE") {
                                return '<button class="btn-restore btn" data-log-id="' + row.id + '" ><i class="fa fa-undo" aria-hidden="true"></i></button>';
                            } else {
                                return '';
                            }
                        }
                    }
                ]
            });
        });
    }

    fetchData();

    $('body').on('click', '.btn-restore', function() {
        let url = '<?= base_url('items/logs/restore') ?>';
        let log_id = $(this).data('log-id');
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                log_id: log_id,
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success('Dữ liệu đã được khôi phục!');
                } else {
                    toastr.error('Dữ liệu không thể khôi phục do không còn tồn tại trong hệ thống!');
                }
            },
        });
    });
</script>