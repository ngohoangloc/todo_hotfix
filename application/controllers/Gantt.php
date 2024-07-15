<?php

class Gantt extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Items_model');
        $this->load->model('Fields_model');
    }

    public function view($folder_id, $id)
    {
        $data['project'] = $this->Items_model->find_by_id($id);

        $this->load->admin('admin/views/gantt', $data);
    }

    public function fetch_gantt_data($id)
    {
        $i = 0;
        $groups = $this->Items_model->get_groups_by_owner($id);     // Lấy danh sách nhóm công việc của user
        $fields = $this->Items_model->get_fields($id);              // Lấy danh sách fields của dự án

        $timeline_fields        = [];                               // Khởi tạo mảng để chứa key của các field có type là timeline
        $percent_fields         = [];                               // Khởi tạo mảng để chứa key của các field có type là percent
        $dependent_task_fields   = [];                               // Khởi tạo mảng để chứa key của các field có type là dependent_task
        $priority_fields        = [];                               // Khởi tạo mảng để chứa key của các field có type là priority

        foreach ($fields as $field) {                               // Lọc qua danh sách fields và lấy key của field là timeline
            if ($field->type_html == 'timeline') {
                $timeline_fields[] = $field->key;
            }

            if ($field->type_html == 'percent') {
                $percent_fields[] = $field->key;
            }

            if ($field->type_html == 'dependenttask') {
                $dependent_task_fields[] = $field->key;
            }

            if ($field->type_html == 'priority') {
                $priority_fields[] = $field->key;
            }
        }

        $data = [                                                   // Khởi tạo biến data để lưu dữ liệu trả về client
            'data' => [],
            'links' => []
        ];

        foreach ($groups as $group) {

            $start_date_of_group = null;
            $end_date_of_group = null;

            $tasks = $this->Items_model->get_child_items($group->id);

            foreach ($tasks as $task) {

                $start_date_of_task = null;
                $end_date_of_task = null;

                // Lấy start date và end date cho từng công việc
                foreach ($timeline_fields as $timeline_field) {

                    $timeline_meta_item = $this->Items_model->get_meta($task->id, $timeline_field);

                    if ($timeline_meta_item) {

                        list($start_date_str, $end_date_str) = explode(' - ', $timeline_meta_item->value);

                        $start_date = DateTime::createFromFormat('d/m/Y', $start_date_str);
                        $end_date = DateTime::createFromFormat('d/m/Y', $end_date_str);

                        if ($start_date_of_task == null || $start_date_of_task > $start_date) {
                            $start_date_of_task = $start_date;
                        }

                        if ($end_date_of_task == null || $end_date_of_task < $end_date) {
                            $end_date_of_task = $end_date;
                        }
                    }

                    if ($start_date_of_group == null || $start_date_of_group > $start_date_of_task) {
                        $start_date_of_group = $start_date_of_task;
                    }

                    if ($end_date_of_group == null || $end_date_of_group < $end_date_of_task) {
                        $end_date_of_group = $end_date_of_task;
                    }
                }

                // Lấy tiến độ cho từng công việc
                foreach ($percent_fields as $percent_field)
                {
                    $percent_meta_item = $this->Items_model->get_meta($task->id, $percent_field);
                }

                // Lấy phụ thuộc của từng công việc
                foreach ($dependent_task_fields as $dependent_task_field)
                {
                    $dependent_task_meta_item = $this->Items_model->get_meta($task->id, $dependent_task_field);

                    if($dependent_task_meta_item->value)
                    {
                        foreach(explode(',', $dependent_task_meta_item->value) as $value)
                        {
                            $data['links'][] = [
                                'id' => $i++,
                                'source' => $value,
                                'target' => $task->id,
                                'type' => '0'
                            ];
                        }
                    }
                }

                // Lấy độ ưu tiên cho từng công việc
                foreach ($priority_fields as $priority_field)
                {
                    $priority_meta_item = $this->Items_model->get_meta($task->id, $priority_field);
                }

                // Kiểm tra nếu tồn tại ngày bắt đầu và ngày kết thúc
                if ($start_date_of_task != null && $end_date_of_task != null) {
                    $duration = $start_date_of_task->diff($end_date_of_task)->days;

                    $data['data'][] = [
                        'id' => $task->id,
                        'text' => $task->title,
                        'start_date' => $start_date_of_task->format('d-m-Y'),
                        'duration' => $duration,
                        'parent' => $group->id,
                        'progress' => $percent_meta_item->value ? ($percent_meta_item->value / 100) : 1,
                        'open' => true,
                        'priority' => $priority_meta_item->value ? $priority_meta_item->value : null,
                    ];
                } else {
                    $data['data'][] = [
                        'id' => $task->id,
                        'text' => $task->title,
                        'start_date' => null,
                        'duration' => null,
                        'parent' => $group->id,
                        'progress' => $percent_meta_item->value ? ($percent_meta_item->value / 100) : 1,
                        'open' => true,
                        'priority' => $priority_meta_item->value ? $priority_meta_item->value : null,
                    ];
                }
            }

            if ($start_date_of_group != null && $end_date_of_group != null) {

                $duration = $start_date_of_group->diff($end_date_of_group)->days;

                $data['data'][] = [
                    'id' => $group->id,
                    'text' => $group->title,
                    'start_date' => $start_date_of_group->format('d-m-Y'),
                    'duration' => $duration,
                    'parent' => 0,
                    'progress' => null,
                    'open' => true,
                ];

            } else {

                $data['data'][] = [
                    'id' => $group->id,
                    'text' => $group->title,
                    'start_date' => null,
                    'duration' => null,
                    'parent' => 0,
                    'progress' => null,
                    'open' => true,
                ];

            }
        }

        echo json_encode(['success' => true, 'gantt' => $data]);
        die();
    }
}
