<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("logs_model");
        
        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }

    public function index()
    {
        $this->load->admin("/admin/log/index");
    }

    public function get_all_logs()
    {
        $start = $this->input->get('start');
        $length = $this->input->get('length');

        $result = $this->logs_model->get_logs($start, $length);

        echo json_encode(array('success' => true, 'data' => $result));
    }

    public function get_logs($item_id)
    {
        $page = $this->input->get('page') ? $this->input->get('page') : 1;

        $limit = $this->input->get('limit') ? $this->input->get('limit') : 20;

        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : null;

        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : null;

        $result = $this->logs_model->get_logs_by_item($item_id, $page, $limit, $start_date, $end_date);

        echo json_encode(array('success' => true, 'data' => $result));
    }

    public function logs_export()
    {
        $logs = $this->logs_model->get_logs_by_item($this->input->post('project_id'));

        $this->load->library("pxl");

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);

        $i = 0;
        $heading = array(
            'STT',
            'THỜI GIAN',
            'NGƯỜI THAY ĐỔI',
            'TÊN DỰ ÁN',
            'TÊN',
            'GIÁ TRỊ CŨ',
            'GIÁ TRỊ MỚI',
            'ĐỊA CHỈ IP',
            'NHÓM',
            'LOẠI',
        );

        // for ($col = 'A'; $col <= 'Z'; $col++) {
        //     if ($i == 48) {
        //         break;
        //     }
        //     $objPHPExcel->getActiveSheet()
        //         ->getColumnDimension($col)
        //         ->setAutoSize(true);

        //     $i++;
        // }

        $rowNumberH = 1;
        $colH = 'A';
        foreach ($heading as $h) {
            $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $h);
            $colH++;
        }

        $rows = 2;
        foreach ($logs as $key => $log) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rows, $key + 1); // STT
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rows, $log->created_at); // Thời gian
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $rows, $log->user->firstname . ' ' . $log->user->lastname); // Người thay đổi
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $rows, $log->item_title); // Tên dự án
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $rows, $log->title); // Tên
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $rows, $log->value_old); // Giá trị cũ
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $rows, $log->value_new); // Giá trị mới
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $rows, $log->ip); // Địa chỉ IP
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $rows, ''); // Nhóm
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $rows, $log->type); // Loại

            $rows++;
        }

        $headerStyleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D9D9D9'),
            ),
        );

        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($headerStyleArray);

        // Set border for all cells
        $allBordersStyleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A1:J' . $objPHPExcel->getActiveSheet()->getHighestRow())
            ->applyFromArray($allBordersStyleArray);

        // Set auto size for columns A to J
        foreach (range('A', 'J') as $col) {
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $fileName = 'logs-' . time() . '.xls';;

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit();
    }

    public function restore()
    {
        $log_id = $this->input->post('log_id');

        $log = $this->logs_model->get_log_by_id($log_id);

        $result = $this->logs_model->restore_data($log);

        echo (json_encode(array('success' => $result)));
    }
}
