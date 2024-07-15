<?php

class Score extends CI_Controller
{
    const GROUP_ID = 5;
    const PROJECT_ID = 6;
    const TASK_ID = 8;
    const SUB_TASK = 28;
    const FOLDER_ID = 7;
    const BOARD_ID = 27;
    const DEPARTMENT_ID = 30;
    const TABLE_ID = 31;
    const TIMETALBE_ID = 32;
    const TABLEITEM_ID = 35;


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Scores_model');
        $this->load->model('Items_model');
        $this->load->model('Fields_model');

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }

    public function index()
    {
        $data['scores']      = $this->Scores_model->get_all();
        $data['departments'] = $this->Items_model->get_departments();

        $this->load->admin("admin/performance_review/index", $data);
    }

    public function show_review_modal()
    {
        $item_id        = $this->input->get('item_id');
        $data['item']   = $this->Items_model->find_by_id($item_id);
        $id_users_lists = $this->Items_model->get_meta_by_task_and_by_type_html($item_id, 'people');

        $users          = array();
        $data['users']  = array();

        foreach ($id_users_lists as $lists) {

            foreach ($lists as $list) {
                if (!empty($list->value)) {

                    $id_users = explode(',', $list->value);

                    foreach ($id_users as $id) {
                        if (!in_array($id, $users)) {

                            $users[]            = $id;
                            $data['users'][]    = $this->User_model->get_user_by_id($id);
                        }
                    }
                }
            }
        }

        if (empty($data['users'])) {

            echo json_encode(['success' => false, 'message' => 'Công việc chưa được phân công cho người dùng!']);
            die();
        } else {

            $html = $this->load->view('admin/views/components/performance-review', $data, true);

            echo json_encode(['success' => true, 'data' => $html]);
            die();
        }
    }

    public function mark()
    {
        $scores = $this->input->post('scores');

        foreach ($scores as $score) {
            $result = $this->Scores_model->create($score);
        }

        echo json_encode(['success' => $result]);
    }

    public function show_performance_review_report()
    {
        $filters = $this->input->get('filters');

        $data['avg_scores_group_by_user']   = $this->Scores_model->avg_scores_group_by_user($filters);

        $data['total_rows']                 = $this->Scores_model->count_total_scores($filters);

        $data['limit']                      = $filters['limit'];
        $data['offset']                     = $filters['offset'];

        $html                               = $this->load->view('admin/views/components/score/performance-review-report', $data, true);

        echo json_encode(['success' => true, 'data' => $html]);
        die();
    }

    public function show_list_detail_report()
    {
        $filters          = $this->input->get('filters');

        $data['scores']   = $this->Scores_model->get_all($filters);

        $data['total_rows']                 = $this->Scores_model->count_get_all($filters);

        foreach ($data['scores'] as $key => $score) {
            $data['scores'][$key]->item       = $this->Items_model->find_by_id($score->items_id);

            $data['scores'][$key]->group      = $this->Items_model->get_parent_by_type($score->items_id, self::GROUP_ID);
            $data['scores'][$key]->project    = $this->Items_model->get_parent_by_type($score->items_id, self::PROJECT_ID);
        }

        $data['limit']    = $filters['limit'];
        $data['offset']   = $filters['offset'];

        $html             = $this->load->view('admin/views/components/score/list-detail', $data, true);

        echo json_encode(['success' => true, 'data' => $html]);
        die();
    }

    public function show_statistic_report()
    {
        $data['filters'] = $this->input->get('filters');

        $html = $this->load->view('admin/views/components/score/statistic', $data, true);

        echo json_encode(['success' => true, 'data' => $html]);
        die();
    }

    public function fetch_pie_chart_data()
    {
        $filters = $this->input->post('filters');

        $chart_data = $this->Scores_model->pie_chart_data($filters);

        echo json_encode(['success' => true, 'data' => $chart_data]);
    }

    public function fetch_overview_data()
    {
        $filters = $this->input->post('filters');

        $data['count_departments'] = $this->Scores_model->count_departments($filters);
        $data['count_employees'] = $this->Scores_model->count_employees($filters);
        $data['top_department'] = $this->Scores_model->top_department($filters);
        $data['avg_score'] = $this->Scores_model->avg_score($filters);

        echo json_encode(['success' => true, 'data' => $data]);
        die();
    }

    public function fetch_bar_chart_data()
    {
        $filters = $this->input->post('filters');

        $chart_data = $this->Scores_model->bar_chart_data($filters);

        echo json_encode(['success' => true, 'data' => $chart_data]);
    }


    public function export()
    {
        $filters = $this->input->post('filters');
        $data = $this->Scores_model->avg_scores_group_by_user($filters);

        $this->load->library("pxl");

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);


        $heading = array(
            'STT',
            'TÊN NHÂN SỰ',
            'CHỨC VỤ',
            'ĐƠN VỊ',
            'ĐIỂM',
        );

        $rowNumberH = 1;
        $colH = 'A';

        // Setting header values
        foreach ($heading as $h) {
            $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $h);
            $colH++;
        }

        $rows = 2;
        $currentDepartment = '';

        if (!empty($data)) {
            // Styling header
            $headerStyleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D9D9D9'),
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($headerStyleArray);

            // Setting data values
            foreach ($data as $key => $score) {
                // Check if department changes
                if ($currentDepartment != $score->department_name) {
                    $currentDepartment = $score->department_name;

                    // Merge cells for department header
                    $objPHPExcel->getActiveSheet()->mergeCells("A{$rows}:E{$rows}");
                    $objPHPExcel->getActiveSheet()->setCellValue("A{$rows}", $currentDepartment);

                    // Styling department header
                    $objPHPExcel->getActiveSheet()->getStyle("A{$rows}:E{$rows}")->applyFromArray(array(
                        'font' => array(
                            'bold' => true,
                            'size' => 12,
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('rgb' => '000000'),
                            ),
                        ),
                    ));

                    $rows++;
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rows, $key + 1);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rows, $score->firstname . ' ' . $score->lastname);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $rows, 'Nhân Viên');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $rows, $score->department_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $rows, number_format($score->average_score, 1));

                // Add border to each data row
                $objPHPExcel->getActiveSheet()->getStyle("A{$rows}:E{$rows}")->applyFromArray(array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000'),
                        ),
                    ),
                ));

                $rows++;
            }

            // Auto-size columns
            foreach (range('A', 'E') as $col) {
                $objPHPExcel->getActiveSheet()
                    ->getColumnDimension($col)
                    ->setAutoSize(true);
            }
        }

        // Create Excel file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $fileName = 'scores-' . time() . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: inline; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit();
    }

    public function print()
    {
        $filters = $this->input->post('filters');

        $data = $this->Scores_model->avg_scores_group_by_user($filters);

        $user_create = $this->User_model->get_user_by_id($this->session->userdata('user_id'));
        $current_date = date('d');
        $current_month = date('m');
        $current_year = date('Y');

        $heading = array(
            'STT',
            'TÊN NHÂN SỰ',
            'CHỨC VỤ',
            'ĐƠN VỊ',
            'ĐIỂM',
        );

        $html = '<html><head><style>
            body { font-family: "Times New Roman", sans-serif; }
            h1, h3 { text-align: center; }
            h1 { margin-top: 20px; }
            h3 { margin: 0; }
            .report-info { margin: 20px 0; text-align: center; }
            .signature { margin-top: 50px; margin-right: 30px; text-align: right; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            table, th, td { border: 1px solid black; }
            th, td { padding: 8px 12px; text-align: left; }
            th { background-color: #D9D9D9; }
            .department-header { font-weight: bold; font-size: 12pt; background-color: #f0f0f0; }
            .creation-time { text-align: right; margin: 10px 0; }
        </style>
        <script>
            window.onload = function() {
                window.print();
                window.onafterprint = function() {
                    window.close();
                };
            };
        </script>
        </head><body>';

        // Quốc hiệu và tiêu ngữ
        $html .= '<h3>Cộng hòa xã hội chủ nghĩa Việt Nam</h3>';
        $html .= '<h3>Độc lập - Tự do - Hạnh phúc</h3>';
        $html .= '<div style="text-align: center; margin-top: 10px;"><hr style="width: 20%; margin: auto;"></div>';

        // Title and user information
        $html .= '<h1>BÁO CÁO ĐÁNH GIÁ NHÂN SỰ</h1>';

        // Table header
        $html .= '<table>';
        $html .= '<tr>';
        foreach ($heading as $h) {
            $html .= '<th>' . $h . '</th>';
        }
        $html .= '</tr>';

        $currentDepartment = '';

        if (!empty($data)) {
            foreach ($data as $key => $score) {
                if ($currentDepartment != $score->department_name) {
                    $currentDepartment = $score->department_name;

                    // Department header row
                    $html .= '<tr class="department-header"><td colspan="5">' . $currentDepartment . '</td></tr>';
                }

                $html .= '<tr>';
                $html .= '<td>' . ($key + 1) . '</td>';
                $html .= '<td>' . $score->firstname . ' ' . $score->lastname . '</td>';
                $html .= '<td>Nhân Viên</td>';
                $html .= '<td>' . $score->department_name . '</td>';
                $html .= '<td>' . number_format($score->average_score, 1) . '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</table>';

        // Signature section
        $html .= '<div class="signature">';
        $html .= '<span>.......<i>, ngày ' . $current_date . ' tháng ' . $current_month . ' năm ' . $current_year . '</i><br><p style="margin-right:20px;">Người lập</p></span>';
        $html .= '<br><br><br>';
        $html .= '<p>' . $user_create->firstname . ' ' . $user_create->lastname . '</p>';
        $html .= '</div>';

        $html .= '</body></html>';

        echo $html;
    }
}
