<?php

class Items extends CI_Controller
{
    const GROUP_ID            = 5;
    const PROJECT_ID          = 6;
    const TASK_ID             = 8;
    const SUB_TASK            = 28;
    const FOLDER_ID           = 7;
    const BOARD_ID            = 27;
    const DEPARTMENT_ID       = 30;
    const TIMETALBE_ID        = 32;
    const TIMETALBE_ITEM_ID   = 33;
    const TABLEITEM_ID   = 35;
    const TABLE_ID   = 31;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library("pxl");
        $this->load->model("Items_model");
        $this->load->model("fields_model");
        $this->load->model("types_model");
        $this->load->model("File_model");
        $this->load->model("User_model");
        $this->load->model("Notifications_model");

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }

    public function get_item($id)
    {
        $result = $this->items_model->get_all_meta($id);

        echo json_encode(array('success' => true, 'data' => $result));
    }

    function view($id)
    {
        $data['project_item']   = $this->Items_model->find_by_id($id);
        $data['groups']         = $this->Items_model->get_child_items($id);

        $this->load->admin("admin/items/view", $data);
    }

    function index()
    {


        $this->load->admin("admin/items");
    }

    function add()
    {

        $type_id = $this->input->post("type_id");

        $data_add = $this->input->post();

        if ($type_id == 7) {
            // Handle add thumbnail for type folder
            if (!empty($_FILES['thumbnail']['name'])) {

                $_FILES['file']['name'] = $_FILES['thumbnail']['name'];
                $_FILES['file']['type'] = $_FILES['thumbnail']['type'];
                $_FILES['file']['tmp_name'] = $_FILES['thumbnail']['tmp_name'];
                $_FILES['file']['error'] = $_FILES['thumbnail']['error'];
                $_FILES['file']['size'] = $_FILES['thumbnail']['size'];

                $originalFileName = $_FILES['thumbnail']['name'];
                $hashedFileName = hash('sha256', $originalFileName . time());

                $config['file_name'] = $hashedFileName;
                $config['upload_path'] = "uploads/thumbnail_folder/";

                // Check folder exists
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path']);
                }

                $config['allowed_types'] = "jpg|jpeg|png";
                $config['max_size'] = 2048;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('thumbnail')) {
                    $uploadData = $this->upload->data();
                    $data_add['thumbnail'] = $config['upload_path'] . $uploadData['file_name'];
                    // Process further with $data_add
                } else {
                    $uploadError = $this->upload->display_errors();
                    echo $uploadError; // Display any upload errors
                }
            } else {
                $data_add['thumbnail'] = "assets/images/folder.svg";
            }
        }


        $result = $this->Items_model->add($data_add);

        if ($result) {

            $item       = $this->Items_model->find_by_id($result);
            $group      = $this->Items_model->find_by_id($item->parent_id);
            $project    = isset($group->parent_id) ? $this->Items_model->find_by_id($group->parent_id) :  (object) array();

            switch ($item->type_id) {
                case self::PROJECT_ID:
                case self::BOARD_ID:
                case self::DEPARTMENT_ID:
                case self::TIMETALBE_ID:

                    $data['personalProject'] = $item;

                    $html = $this->load->view("admin/views/components/project-personal-item", $data, true);
                    echo json_encode(array('success' => true, 'data' => $html));
                    break;
                case self::GROUP_ID:
                    $data['group'] = $item;
                    $data['project'] = $project;
                    $html = $this->load->view("admin/views/components/group", $data, true);
                    echo json_encode(array('success' => true, 'data' => $html, 'parent_id' => (int)$item->parent_id));
                    break;
                case self::FOLDER_ID:
                    $data['folder']  = $item;
                    $html          = $this->load->view("admin/views/components/folder-sidebar", $data, true);
                    // $html          = $this->load->view("admin/views/components/folder-item", $data, true);
                    echo json_encode(array('success' => true, 'data' => $html, 'parent_id' => (int)$item->parent_id));
                    break;
                case 29:
                    $data['board_subset']   = $item;
                    $data['folder_id'] =  $data_add['folder_id'];
                    $data['project_id']     = $item->parent_id;
                    $html = $this->load->view("admin/views/components/board-subset", $data, true);
                    echo json_encode(array('success' => true, 'data' => $html));
                    break;
                case self::TASK_ID:
                    $data['task']      = $item;
                    $data['group']     = $group;
                    $data['project']   = $project;

                    $html = $this->load->view("admin/views/components/task-row", $data, true);
                    echo json_encode(array('success' => true, 'data' => $html));
                    break;
                case self::SUB_TASK:
                    $data['group']     = $this->Items_model->find_by_id($group->parent_id);
                    $data['project']   = $project;
                    $data['subitem'] = $item;

                    $html = $this->load->view("admin/views/components/sub-task-row", $data, true);
                    echo json_encode(array('success' => true, 'data' => $html, $group->owners));
                    break;
                case self::TABLEITEM_ID:
                    $data['task']      = $item;
                    $data['group']     = $group;
                    $data['project']   = $project;
                    $html = $this->load->view("admin/views/components/table-row", $data, true);
                    echo json_encode(array('success' => true, 'data' => $html));
                    break;
                case self::TABLE_ID:
                    $data['personalProject'] = $item;
                    $html = $this->load->view("admin/views/components/project-personal-item", $data, true);
                    echo json_encode(array('success' => true, 'data' => $html, 'parent_id' => (int)$item->parent_id));
                    break;
                default:
                    break;
            }
        }
    }

    function add_group()
    {

        $data_add = [
            'parent_id' => $this->input->post('parent_id'),
            'title' => $this->input->post('title'),
            'type_id' => $this->input->post('type_id'),
        ];

        $position = $this->input->post("position");

        $group = $this->Items_model->add_group($data_add, $position);
        $project = $this->Items_model->find_by_id($group->parent_id);
        $tasks = $this->Items_model->get_child_items($group->id);

        $data = [
            'group' => $group,
            'project' => $project,
            'tasks' => $tasks
        ];

        $data['is_owner'] = false;

        if (in_array($this->session->userdata('user_id'), explode(',', $project->owners))) {
            $data['is_owner'] = true;
        }

        switch ($project->type_id) {
            case self::PROJECT_ID:
            case self::DEPARTMENT_ID:
                $html = $this->load->view("admin/views/components/group", $data, true);
                echo json_encode(array('success' => true, 'data' => $html, 'group' => $group->id));
                die();

            case self::TIMETALBE_ID:

                $html = $this->load->view("admin/views/components/group-timetable", $data, true);
                echo json_encode(array('success' => true, 'data' => $html, 'group' => $group->id, 'project_id' => $project->type_id));
                die();
            default:
                break;
        }
    }

    function update($id)
    {
        $data_update = $this->input->post();

        $old_folder = $this->Items_model->find_by_id($id);

        $file_url_updated  = "";

        // Handle add thumbnail for type folder
        if (!empty($_FILES['thumbnail']['name'])) {

            $_FILES['file']['name'] = $_FILES['thumbnail']['name'];
            $_FILES['file']['type'] = $_FILES['thumbnail']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['thumbnail']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['thumbnail']['error'];
            $_FILES['file']['size'] = $_FILES['thumbnail']['size'];

            $originalFileName = $_FILES['thumbnail']['name'];
            $hashedFileName = hash('sha256', $originalFileName . time());

            $config['file_name'] = $hashedFileName;
            $config['upload_path'] = "uploads/thumbnail_folder/";

            // Check folder exists
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path']);
            }

            $config['allowed_types'] = "jpg|jpeg|png";
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('thumbnail')) {
                $uploadData = $this->upload->data();
                $data_update['thumbnail'] = $config['upload_path'] . $uploadData['file_name'];
                unlink($old_folder->thumbnail);
                $file_url_updated = base_url($data_update['thumbnail']);
            } else {
                $uploadError = $this->upload->display_errors();
                echo $uploadError; // Display any upload errors
            }
        }

        $result = $this->Items_model->update($data_update, $id);

        echo json_encode(array('success' => $result, "file_url_updated" => !empty($file_url_updated) ? $file_url_updated : ""));
    }



    function update_meta()
    {
        $view_type                      = $this->input->post('view_type');
        $type                           = $this->input->post('type');
        $flag                           = true;
        $dependentItems                 = [];

        if ($type == 'status') {
            //lay key dependenttask
            $project_id                 = $this->input->post('project_id');
            $item_id                    = $this->input->post('item_id');

            $fields                     = $this->Items_model->get_fields($project_id);
            $dependentTaskKey           = null;

            foreach ($fields as $field) {
                if ($field->type_html === 'dependenttask') {
                    $dependentTaskKey   = $field->key;
                    break;
                }
            }

            //get meta của dependenttask la id cua cac item phu thuoc

            $items_dependent            = $this->Items_model->get_meta_by_field($item_id, $dependentTaskKey);

            if ($items_dependent && isset($items_dependent->value)) {
                $items_id = explode(",", $items_dependent->value);
            }

            $is_done                    = [];

            if (isset($items_id)) {
                foreach ($items_id as $id) {
                    $item                   = $this->db->select('*')
                        ->from('items')
                        ->where('id', $id)
                        ->get()
                        ->row_object();

                    if ($item) {
                        $is_done[]          = $item->is_done;
                        $dependentItems[]   = $item;
                    }
                }
            }

            $flag                       = !in_array(0, $is_done);
        }


        if ($flag) {

            $data_update  = [
                'meta_id' => $this->input->post('meta_id'),
                'value' => $this->input->post('value'),
                'type' => $this->input->post('type')
            ];

            $result                     = $this->Items_model->update_meta($data_update);

            if ($result) {

                $item = $this->db->get_where('items', ['id' => $this->input->post('item_id'), 'deleted_at' => NULL])->row_object();

                $group = $this->db->get_where('items', ['id' => $this->input->post('group_id'), 'deleted_at' => NULL])->row_object();

                $meta = $this->db->select('items_meta.*')->from('items_meta')
                    ->join('fields', 'fields.key = items_meta.key')
                    ->where('fields.deleted_at', NULL)
                    ->where('fields.type_html', 'people')
                    ->where('items_meta.items_id', $this->input->post('item_id'))->get()->row_object();

                $task_owners = explode(',', $meta->value);
                $group_owners = explode(',', $group->owners);

                // Kết hợp các mảng và loại bỏ các phần tử null hoặc rỗng
                $receivers = array_filter(array_merge($task_owners, $group_owners), function ($value) {
                    return !is_null($value) && $value !== '';
                });

                foreach ($receivers as $receiver) {
                    if ($receiver != $this->session->userdata('user_id')) {
                        $notification = [
                            'title' => $item->title,
                            'message' => 'Có cập nhật mới!',
                            'user_id' => $receiver,
                        ];

                        $this->Notifications_model->create($notification);
                    }
                }
            }

            switch ($type) {
                case 'people_add':
                case 'people_remove':
                    $project            = $this->Items_model->find_by_id($this->input->post('project_id'));
                    $group              = $this->Items_model->find_by_id($this->input->post('group_id'));

                    $data['value']      = $result->value;
                    $data['meta_id']    = $result->id;
                    $data['project']    = $project;
                    $data['group']      = $group;

                    $html               = $this->load->view('admin/views/input/people', $data, true);

                    echo json_encode(array('success' => true, 'data' => $html));
                    die();
                case 'status':
                    if ($view_type == 'table') {
                        $data['group_id']   = $this->input->post('group_id');
                        $data['key']        = $result->key;
                        $data['tasks']      = $this->Items_model->get_child_items_order_by_created_at($this->input->post('group_id'));

                        $progress_html      = $this->load->view('admin/views/components/progress-stacked', $data, true);

                        echo json_encode(array('success' => $result ? true : false, $result->key, 'progress_html' => $progress_html));
                        die();
                    } elseif ($view_type == 'customtable') {
                        echo json_encode(array('success' => $result ? true : false, $result->key));
                        die();
                    }

                default:
                    echo json_encode(array('success' => $result ? true : false));
                    break;
            }
        } else {
            echo json_encode(['success' => false, 'dependent_items' => $dependentItems]);
        }
    }

    function delete($id)
    {
        $result = $this->Items_model->delete($id);
        echo json_encode(array('success' => $result));
    }

    public function change_key_permission()
    {
        $user_id = $this->input->post();

        
    }

    function sort()
    {
        $array_id = $this->input->post("array_id");

        $result = $this->Items_model->sort($array_id);

        echo json_encode(array('success' => $result));
    }
    function sort_task()
    {
        $array_id = $this->input->post("array_id");

        $result = $this->Items_model->sort_task($array_id);

        echo json_encode(array('success' => $result));
    }
    function delete_multiple()
    {
        $array_id = $this->input->post("array_id");
        $result = $this->Items_model->delete_multiple($array_id);
        echo json_encode(array('success' => $result));
    }

    function export()
    {

        //Create a new Object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);


        $project_id = $this->input->post("project_id");
        $dataExport = $this->input->post("payload");

        $fields = $this->Items_model->get_fields($project_id);

        $colsGroup = "A";
        $row = 1;


        for ($i = 0; $i < count($dataExport); $i++) {
            $group_id = $dataExport[$i]['group_id'];
            $tasks_id = $dataExport[$i]['tasks_id'];

            $group = $this->Items_model->find_by_id($group_id);

            // Create task for group
            if (isset($tasks_id)) {

                $items = $this->Items_model->find_in_set(implode(",", $tasks_id));

                // Create group
                $objPHPExcel->getActiveSheet()->setCellValue($colsGroup . $row, $group->title);

                $objPHPExcel->getActiveSheet()
                    ->getStyle($colsGroup . $row)
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);

                $row++;

                if (count($items) > 0) {

                    // Set task title
                    $objPHPExcel->getActiveSheet()->setCellValue("A" . $row, "STT");
                    $objPHPExcel->getActiveSheet()->getStyle("A" . $row)->getFont()->setBold(true)->setSize(14);

                    $objPHPExcel->getActiveSheet()->getColumnDimension("A" . $row)->setWidth(10);

                    $objPHPExcel->getActiveSheet()->setCellValue("B" . $row, "Công việc");
                    $objPHPExcel->getActiveSheet()->getStyle("B" . $row)->getFont()->setBold(true)->setSize(14);

                    $colsTask = "C";

                    // Create field
                    for ($j = 0; $j < count($fields); $j++) {
                        $objPHPExcel->getActiveSheet()->setCellValue($colsTask . $row, $fields[$j]->title);
                        $objPHPExcel->getActiveSheet()
                            ->getStyle($colsTask . $row)
                            ->getFont()
                            ->setSize(14)
                            ->setBold(true);

                        $colsTask++;
                    }

                    $row++;

                    // Create task
                    $BStyle = array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_DOTTED,
                                'color' => array('rgb' => 'A9A9A9')
                            )
                        )
                    );

                    foreach ($items as $key => $task) {

                        $objPHPExcel->getActiveSheet()->setCellValue("A" . $row, $key + 1);
                        $objPHPExcel->getActiveSheet()
                            ->getStyle("A" . $row)
                            ->getFont()
                            ->setSize(14);

                        $objPHPExcel->getActiveSheet()->getStyle("A" . $row)->applyFromArray($BStyle);

                        $objPHPExcel->getActiveSheet()->setCellValue("B" . $row, $task->title);
                        $objPHPExcel->getActiveSheet()
                            ->getStyle("B" . $row)
                            ->getFont()
                            ->setSize(14);

                        $objPHPExcel->getActiveSheet()->getStyle("B" . $row)->applyFromArray($BStyle);

                        $objPHPExcel->getActiveSheet()->getStyle("A" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


                        $metaCols = "C";
                        // Create meta for task
                        foreach ($this->Items_model->get_all_meta($task->id) as $meta) {

                            $field = $this->Items_model->get_field_by_key($meta->key);

                            if (empty($value) || trim($value) == '') {
                                $value = "";
                            }

                            switch ($field->type_html) {
                                case "status":
                                    $meta->value = explode("|", $meta->value)[2];

                                    $objPHPExcel->getActiveSheet()->setCellValue($metaCols . $row, $meta->value);
                                    break;
                                case "timeline":
                                    $meta->value = implode(" đến ", explode("|", $meta->value));
                                    $objPHPExcel->getActiveSheet()->setCellValue($metaCols . $row, $meta->value);
                                    break;
                                case "file":

                                    $files = $this->File_model->find_in_set($meta->value);

                                    $baseUrl = base_url();

                                    foreach ($files as $file) {
                                        // Create the hyperlink
                                        $hyperlink = new PHPExcel_Cell_Hyperlink();
                                        $hyperlink->setUrl($baseUrl . $file->path); // Set the URL
                                        $hyperlink->setTooltip($file->title); // Set the tooltip

                                        // Set the hyperlink for the cell
                                        $objPHPExcel->getActiveSheet()->setCellValue($metaCols . $row, $file->title);
                                        $objPHPExcel->getActiveSheet()->getCell($metaCols . $row)->setHyperlink($hyperlink);
                                    }

                                    break;
                                case "people":
                                    $users_name = [];

                                    $users = $this->User_model->find_in_set($meta->value);

                                    foreach ($users as $user) {
                                        $users_name[] = $user->firstname . " " . $user->lastname;
                                    }

                                    $meta->value = implode(", ", $users_name);
                                    $objPHPExcel->getActiveSheet()->setCellValue($metaCols . $row, $meta->value);

                                    break;
                                case "date":
                                    $meta->value = implode("-", array_reverse(explode("-", $meta->value)));
                                    $objPHPExcel->getActiveSheet()->setCellValue($metaCols . $row, $meta->value);
                                    break;
                                default:
                                    $objPHPExcel->getActiveSheet()->setCellValue($metaCols . $row, $meta->value);
                                    break;
                            }

                            $objPHPExcel->getActiveSheet()->getStyle($metaCols . $row)->getFont()->setSize(14);
                            $objPHPExcel->getActiveSheet()->getStyle($metaCols . $row)->applyFromArray($BStyle);

                            $objPHPExcel->getActiveSheet()->getStyle($metaCols . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setWrapText(true);

                            $metaCols++;
                        }
                        $row++;
                    }
                }
            }
        }

        foreach (range('B', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }

        ob_start();
        $write = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $write->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response =  array(
            'status' => TRUE,
            'total_group' => $dataExport,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

        die(json_encode($response));
    }
    public function export_sample_file()
    {
        $project_id = $this->input->post("project_id");

        $fields = $this->Items_model->get_fields($project_id);

        //Create a new Object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        $cols = "A";
        $row = 1;

        $objPHPExcel->getActiveSheet()->setCellValue($cols . $row, "Tên công việc");

        $objPHPExcel->getActiveSheet()
            ->getStyle($cols . $row)
            ->getFont()
            ->setSize(14)
            ->setBold(true);

        foreach ($fields as $key => $field) {
            $cols++;
            $objPHPExcel->getActiveSheet()->setCellValue($cols . $row, $field->title);

            $objPHPExcel->getActiveSheet()
                ->getStyle($cols . $row)
                ->getFont()
                ->setSize(14)
                ->setBold(true);
        }

        $rowsDataSample = 2;

        for ($i = 1; $i <= 3; $i++) {
            $colsDataSample = "A";

            for ($j = 0; $j <= count($fields); $j++) {

                if ($j == 0) {
                    $objPHPExcel->getActiveSheet()->setCellValue($colsDataSample . $rowsDataSample, "Công việc " . $i);
                } else {
                    $field = $this->Items_model->get_field_by_key($fields[$j - 1]->key);

                    $value = "";

                    switch ($field->type_html) {
                        case 'file':
                        case 'people':
                        case 'connecttable':
                        case 'department':
                            $value = "Để trống";
                            break;
                        case 'date':
                            $value = "01-01-2024";
                            break;
                        case 'email':
                            $value = "abc@gmail.com";
                            break;
                        case 'gender':
                            $value = "Nam";
                            break;
                        case 'timeline':
                            $value = "10/06/2024 - 13/06/2024";
                            break;
                        case 'text':
                        case 'longtext':
                            $value = "Text";
                            break;
                        case 'percent':
                            $value = "100";
                            break;
                        case 'status':
                            $value = "Hoàn thành|Đang làm|Chưa hoàn thành|Chưa bắt đầu";
                            break;
                        case 'number':
                            $value = "1";
                            break;
                        default:
                            break;
                    }

                    $objPHPExcel->getActiveSheet()->setCellValue($colsDataSample . $rowsDataSample, $value);
                }

                $colsDataSample++;
            }
            $rowsDataSample++;
        }

        foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }

        ob_start();
        $write = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $write->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response =  array(
            'status' => TRUE,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

        die(json_encode($response));
    }

    public function import_timetable()
    {

        $project_id                   = $this->input->post("project_id");
        $group_id                     = $this->input->post("group_id");
        $fields                       = $this->Items_model->get_fields($project_id);

        if (isset($_FILES["file"]["name"])) {
            $path                     = $_FILES["file"]["tmp_name"];
            $object                   = PHPExcel_IOFactory::load($path);

            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow           = $worksheet->getHighestRow();
                $highestColumn        = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $magv             = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    // Add items
                    $data_item_add = [
                        'title' => $magv,
                        'type_id' => 33,
                        'user_id' => $this->session->userdata('user_id'),
                        'parent_id' => $group_id
                    ];

                    $item_id          = $this->Items_model->add($data_item_add);

                    // Add meta for item
                    foreach ($fields as $key => $field) {
                        $value        = $worksheet->getCellByColumnAndRow($key, $row)->getValue();

                        if (empty($value) || trim($value) == '') {
                            $value    = "";
                        }

                        $this->Items_model->add_meta($item_id, $field->key, $value);
                    }
                }
            }
        }

        $project                      = $this->Items_model->find_by_id($project_id);
        $group                        = $this->Items_model->find_by_id($group_id);
        $tasks                        = $this->Items_model->get_child_items($group_id);

        $data = [
            'group_id' => $group,
            'project' => $project,
            'tasks' => $tasks
        ];

        $html                         = $this->load->view("admin/views/components/group-item-timetable", $data, true);
        echo json_encode(array('success' => true, 'group_html' => $html, 'group' => $group->id));

        die();
    }
    public function import_task()
    {

        $project_id                   = $this->input->post("project_id");
        $type_id                   = $this->input->post("type_id");
        $group_id                     = $this->input->post("group_id");
        $fields                       = $this->Items_model->get_fields($project_id);
        $isSuccess                    = false;

        if (isset($_FILES["file"]["name"])) {
            $path                     = $_FILES["file"]["tmp_name"];
            $object                   = PHPExcel_IOFactory::load($path);

            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow           = $worksheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $title            = $worksheet->getCellByColumnAndRow(0, $row)->getValue();

                    // Add items
                    $data_item_add = [
                        'title' => $title,
                        'type_id' => $type_id,
                        'user_id' => $this->session->userdata('user_id'),
                        'parent_id' => $group_id
                    ];

                    $item_id          = $this->Items_model->add($data_item_add);

                    if (!empty($item_id)) {
                        $isSuccess    = true;
                    }

                    // Add meta for item
                    for ($i = 0; $i < count($fields); $i++) {

                        $field_type = $fields[$i]->type_html;

                        $value =  $worksheet->getCellByColumnAndRow($i + 1, $row)->getValue();

                        switch ($field_type) {
                            case 'file':
                            case 'people':
                                $value = "";
                                break;
                            case 'date':
                                $value = implode("-", array_reverse(explode("-", $value)));
                                break;
                            case 'status':
                                switch ($value) {
                                    case 'Hoàn thành':
                                        $value = "hoanthanh|success|Hoàn thành";
                                        break;
                                    case 'Đang làm':
                                        $value = "danglam|warning|Đang làm";
                                        break;
                                    case 'Chưa hoàn thành':
                                        $value = "chuahoanthanh|danger|Chưa hoàn thành";
                                        break;
                                    case 'Chưa bắt đầu':
                                        $value = "chuabatdau|secondary|Chưa bắt đầu";
                                        break;
                                    default:
                                        $value = "chuabatdau|secondary|Chưa bắt đầu";
                                        break;
                                }
                                break;
                            default:
                                break;
                        }

                        if (empty($value) || trim($value) == '') {
                            $value = "";
                        }

                        $meta_id = $this->Items_model->add_meta($item_id, $fields[$i]->key, $value);

                        if (isset($meta_id)) {
                            $isSuccess    = true;
                        }
                    }
                }
            }
        }

        $data_html = [];

        if ($isSuccess) {

            $tasks = $this->Items_model->get_where($group_id, []);

            if (count($tasks) > 0) {

                foreach ($tasks as $key => $task) {
                    $sort_item_html = $this->load->view("admin/views/components/task-row", ['task' => $task, 'type_id' => $type_id], true);

                    $data_html[] = $sort_item_html;
                }
            }
        }

        echo json_encode(array('success' => $isSuccess, 'data_html' => count($data_html) > 0 ? $data_html : ""));

        die();
    }

    public function add_owner_to_project()
    {
        $item_id    = $this->input->post('item_id');
        $user_id    = $this->input->post('user_id');
        $result     = $this->Items_model->add_owner_to_project($item_id, $user_id);
        $item       = $this->Items_model->find_by_id($item_id);

        if ($result) {
            $notification = [
                'title' => $item->title,
                'message' => 'Bạn đã được mời vào ' . $item->title,
                'user_id' => $user_id,
            ];

            $this->Notifications_model->create($notification);
        }

        echo json_encode(array('success' => $result));
    }

    public function add_department_to_project()
    {
        $item_id          = $this->input->post('item_id');
        $department_id    = $this->input->post('department_id');

        $item             = $this->Items_model->add_department_to_project($item_id, $department_id);

        echo json_encode(array('success' => $item));
    }

    public function get_owners()
    {
        $item_id    = $this->input->get('item_id');
        $owners     = $this->Items_model->get_owners($item_id);

        echo json_encode(array('success' => true, 'data' => $owners));
    }

    public function get_all_meta($id)
    {

        $data['item']   = $this->Items_model->find_by_id($id);
        $data['meta']   = $this->Items_model->get_all_meta($id);

        echo json_encode(array('success' => true, 'data' => $data));
    }
    public function delete_user_from_group()
    {
        $user_id    = $this->input->post('user_id');
        $item_id    = $this->input->post('item_id');

        $owners     = $this->Items_model->delete_user_from_item($user_id, $item_id);

        if ($owners == false) {
            echo json_encode(array('success' => false, 'message' => 'Bạn không có quyền xóa thành viên!'));
        } else {
            $item   = $this->Items_model->find_by_id($item_id);

            $notification = [
                'title' => $item->title,
                'message' => 'Bạn đã được mời ra khỏi ' . $item->title,
                'user_id' => $user_id,
            ];

            $this->Notifications_model->create($notification);

            echo json_encode(array('success' => true, 'data' => $owners));
        }
    }

    public function get_fields()
    {
        $item_id = strip_tags($this->input->post('item_id'));
        $result  = $this->Items_model->get_fields($item_id);

        echo json_encode(array('success' => $result ? true : false, 'data' => $result));
    }

    public function get_item_is_archived()
    {
        $items_all      = $this->Items_model->get_by_owner($this->session->userdata('user_id'));
        $archived_items = [];

        foreach ($items_all as $item) {
            if ($item->is_archived == 1) {
                $archived_items[] = $item;
            }
        }

        echo json_encode(['archived_items' => $archived_items]);
    }

    public function get_all_items()
    {
        $userId        = $this->session->userdata('user_id');
        $data['items'] = $this->Items_model->get_all_items_by_user_id($userId);

        $this->load->admin("admin/items/all_item", $data);
    }

    public function restore($id)
    {
        $result = $this->Items_model->restore($id);
        echo json_encode(array('success' => $result));
    }

    public function save_filter()
    {

        $items_id          = $this->input->post("items_id");
        $json_filter_value = $this->input->post("json_filter_value");
        // Handle check meta exists

        // Get data filtered
        $meta_filter_exists  = $this->Items_model->get_meta($items_id, "filter");

        // Update if exists
        if (isset($meta_filter_exists)) {

            // Create data update filter meta
            $data_update_filter  = [
                "meta_id" => $meta_filter_exists->id, "value" => $json_filter_value
            ];

            // Update meta filter
            $update_filter       = $this->Items_model->update_meta($data_update_filter);
            // Update meta display
            echo json_encode(array("success" => $update_filter ? true : false, "update success!"));
        } else {
            // Create if not exists
            $meta_filter_id      = $this->Items_model->add_meta($items_id, "filter", $json_filter_value);
            echo json_encode(array("success" => $meta_filter_id ? true : false, "create success!"));
        }
    }

    public function add_link_zalo_for_group()
    {
        $items_id   = $this->input->post("items_id");
        $value      = $this->input->post("value");
        $meta       = $this->Items_model->get_meta($items_id, "linkzalo");

        if ($meta) {
            $data   = [
                'meta_id' => $meta->id,
                'value' => $value
            ];
            $result = $this->Items_model->update_meta($data);
            echo json_encode(array('success' => $result));
        } else {
            $result = $this->Items_model->add_meta($items_id, "linkzalo", $value);
            echo json_encode(array('success' => $result));
        }
    }

    public function get_meta()
    {
        $items_id = $this->input->post("items_id");
        $key      = $this->input->post("key");

        $items_arr = $this->Items_model->get_child_items($items_id);
        $options   = [];

        foreach ($items_arr as $ite) {

            $meta = $this->Items_model->get_meta_by_field($ite->id, $key);

            if (isset($meta) || count($meta)) {
                $options[] = $this->Items_model->get_meta_by_field($ite->id, $key);
            }
        }

        echo json_encode(array("success" => count($options) > 0 ? true : false, "data" => $options));
    }

    public function update_all()
    {
        $project_id = $this->input->post("project_id");

        $data_update = [
            'display' => $this->input->post("display")
        ];

        $result = $this->Items_model->update_all($data_update, $project_id);

        echo json_encode(array('success' => $result));
    }
    public function search_item()
    {

        $search_key = $this->input->post("search_key");

        $result = $this->Items_model->search_items($search_key);

        echo json_encode(array("success" => true, "data" => count($result) > 0 ? $result : []));
    }

    public function search_department()
    {
        if ($this->input->get('search') != null) {
            $department = $this->User_model->get_department($this->input->get('search'));
        } else {
            $department = [];
        }
        echo json_encode(array('success' => true, 'data' => $department));
    }

    public function search_users_input()
    {
        if ($this->input->get('search') == '') {
            // $user_id = $this->session->userdata('user_id');

            // $users = $this->User_model->get_board_members_of_the_same_department($user_id);
        } else {
            $search = $this->input->get('search');

            $users = $this->User_model->get_users_by_search($search);
        }

        $html = '';

        foreach ($users as $user) {
            $data = [
                'user' => $user,
                'project_id' => $this->input->get('project_id'),
                'group_id' => $this->input->get('group_id'),
                'meta_id' => $this->input->get('meta_id'),
            ];

            $html .= $this->load->view('admin/views/components/input/people_search', $data, true);
        }

        echo json_encode(['success' => true, 'data' => $html]);
        die();
    }

    public function get_childs()
    {

        $folder_id = $this->input->post("folder_id");

        $folder = $this->Items_model->find_by_id($folder_id);
        $project_type = $this->Types_model->get_where(['is_project' => 1]);

        $personalProjects = $this->Items_model->get_where($folder_id, ['user_id' => $this->session->userdata("user_id")]);

        $data['personalProjects'] = $personalProjects;
        $data['folder'] = $folder;
        $data['project_type'] = $project_type;

        $data_html = $this->load->view("admin/views/components/list-project-personal", $data, true);

        echo json_encode(array('success' => true, 'data_html' => $data_html, "group" => $folder));
    }

    public function file_download($projectIdEcn, $groupIdEnc, $filename)
    {
        try {
            $decoded_file_name = urldecode($filename);
            $projectIdDec = $this->stringencryption->decryptString($projectIdEcn, $this->config->item("image_key"));
            $groupIdDec = $this->stringencryption->decryptString($groupIdEnc, $this->config->item("image_key"));

            $group = $this->Items_model->find_by_id($groupIdDec);

            $user_id = $this->session->userdata("user_id");
            $user_array_id = explode(",", $group->owners);

            $filePath = "uploads/" . $projectIdDec . "/" . $decoded_file_name;
            $file = $this->File_model->get_file(['path' => $filePath]);

            // Check user exists in group
            if (in_array($user_id, $user_array_id)) {
                $this->load->helper('string');
                $this->load->library('imageencryption');
                $keycode = $group->key_code;

                if (str_ends_with($decoded_file_name, '.enc')) {

                    $content = $this->imageencryption->decryptImage(
                        base_url($filePath),
                        $this->config->item("image_key") . $keycode
                    );


                    header("Content-Type: $file->content_type");
                    echo $content;
                } else {
                    $content = file_get_contents(base_url($filePath));

                    header("Content-Type: $file->content_type");
                    echo $content;
                }
            } else {

                $arrContextOptions = array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ),
                );

                $content = file_get_contents(base_url("assets/images/data-encryption.png"), false, stream_context_create($arrContextOptions));

                header("Content-Type: image/png");
                echo $content;

                // $this->load->view("errors/html/file_not_found");
            }
        } catch (\Throwable $th) {
            show_error($th->getMessage());
        }
    }
}
