<!-- Css -->
<style>
    #gantt {
        overflow: auto;
    }

    .high {
        border: 2px solid #db2536;
        color: #d96c49;
        background: #861014;
    }

    .high .gantt_task_progress {
        background: #db2536;
    }

    .medium {
        border: 2px solid #0D6EFD;
        color: #34c461;
        background: #073f91;
    }

    .medium .gantt_task_progress {
        background: #0D6EFD;
    }

    .low {
        border: 2px solid #0dcaf0;
        color: #6ba8e3;
        background: #4d84a1;
    }

    .low .gantt_task_progress {
        background: #0dcaf0;
    }

    .default-task {
        border: 2px solid #bbbbbb;
        color: #cccccc;
        background: #aaaaaa;
    }

    .default-task .gantt_task_progress {
        background: #bbbbbb;
    }
</style>
<?php
$folder_id_url = $this->uri->segment(3);
$project_id_url = $this->uri->segment(4);
?>
<!-- Html -->
<div class="row pt-3" style="padding-left: 37px !important;">
    <div class="col-md-10">
        <input class="project-title fs-4" data-project-id="<?= $project->id; ?>" value="<?= $project->title; ?>" />
    </div>
    <div class="col-md-2">
        <?php $this->load->view("templates/admin/logs", ['project' => $project]) ?>
    </div>
    <div class="mb-3">
        <?php $this->load->view("templates/admin/board-toolbar", ['project_id' => $project->id, 'parent_id' => $project->parent_id, 'folder_id_url' => $folder_id_url]) ?>
    </div>

    <div class="row" id="gantt" style='width:100%; height:80vh'></div>

</div>


<script>
    $(document).ready(function() {

        gantt.config.readonly = true;

        gantt.i18n.setLocale({
            date: {
                month_full: ["Tháng Một", "Tháng Hai", "Tháng Ba", "Tháng Tư", "Tháng Năm", "Tháng Sáu",
                    "Tháng Bảy", "Tháng Tám", "Tháng Chín", "Tháng Mười", "Tháng Mười Một", "Tháng Mười Hai"
                ],
                month_short: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7",
                    "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
                ],
                day_full: ["Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"],
                day_short: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"]
            },
            labels: {
                new_task: "Công việc mới",
                icon_save: "Lưu",
                icon_cancel: "Hủy",
                icon_details: "Chi tiết",
                icon_edit: "Chỉnh sửa",
                icon_delete: "Xóa",
                gantt_save_btn: "Lưu",
                gantt_cancel_btn: "Hủy",
                gantt_delete_btn: "Xóa",
                confirm_closing: "", // Các thay đổi của bạn sẽ không được lưu, bạn có chắc chắn không?
                confirm_deleting: "Công việc sẽ bị xóa vĩnh viễn, bạn có chắc chắn không?",
                section_description: "Mô tả",
                section_time: "Khoảng thời gian",
                section_type: "Loại",

                /* grid columns */
                column_wbs: "WBS",
                column_text: "Tên công việc",
                column_start_date: "Ngày bắt đầu",
                column_duration: "Thời hạn",
                column_add: "",

                /* link confirmation */
                link: "Liên kết",
                confirm_link_deleting: "sẽ bị xóa",
                link_start: " (bắt đầu)",
                link_end: " (kết thúc)",

                type_task: "Công việc",
                type_project: "Dự án",
                type_milestone: "Mốc thời gian",

                minutes: "Phút",
                hours: "Giờ",
                days: "Ngày",
                weeks: "Tuần",
                months: "Tháng",
                years: "Năm",

                /* message popup */
                message_ok: "OK",
                message_cancel: "Hủy",

                /* constraints */
                section_constraint: "Ràng buộc",
                constraint_type: "Loại ràng buộc",
                constraint_date: "Ngày ràng buộc",
                asap: "Càng sớm càng tốt",
                alap: "Càng muộn càng tốt",
                snet: "Bắt đầu không sớm hơn",
                snlt: "Bắt đầu không muộn hơn",
                fnet: "Kết thúc không sớm hơn",
                fnlt: "Kết thúc không muộn hơn",
                mso: "Phải bắt đầu vào",
                mfo: "Phải kết thúc vào",

                /* resource control */
                resources_filter_placeholder: "gõ để lọc",
                resources_filter_label: "ẩn trống"
            }
        });


        gantt.locale.labels.section_name = "Tên công việc";
        gantt.locale.labels.section_start_date = "Ngày bắt đầu";
        gantt.locale.labels.section_duration = "Thời lượng";

        gantt.templates.date_scale = function(date) {
            var weekDay = date.getDay();
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            return "<b>" + day + "-" + month + "-" + year + "</b>";
        };

        gantt.config.columns = [{
                name: "text",
                label: "Tên công việc",
                tree: true,
                width: 200,
                resize: true
            },
            {
                name: "start_date",
                label: "Ngày bắt đầu",
                align: "center",
                width: 100
            },
            {
                name: "duration",
                label: "Thời hạn (ngày)",
                align: "center",
                width: 100
            },
        ];

        gantt.config.min_column_width = 100;

        gantt.config.date_format = "%d-%m-%Y %H:%i";

        gantt.templates.task_class = function(start, end, task) {

            if (task.priority) {
                switch (task.priority) {
                    case 'Low':
                        return "low";
                    case 'Medium':
                        return "medium";
                    case 'High':
                        return "high";
                    default:
                        return "default-task";
                }
            }

            return "default-task";
        };

        gantt.init("gantt");

        $.ajax({
            url: "<?= base_url('gantt/fetch_gantt_data/'); ?>" + <?= $project->id ?>,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                gantt.parse({
                    'data': response.gantt.data,
                    'links': response.gantt.links
                });
            },
            error: function() {
                alert('Failed to load data');
            }
        });
    });
</script>