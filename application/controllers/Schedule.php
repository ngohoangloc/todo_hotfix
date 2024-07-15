<?php
class Schedule extends CI_Controller
{
    const SERECT_KEY = "6LcHQAolAAAAAGmDYWRGLjM8wdVPQFNvNPCB2-ss";

    const GROUP_ID = 5;
    const PROJECT_ID = 6;
    const TASK_ID = 8;
    const SUB_TASK = 28;
    const FOLDER_ID = 7;
    const BOARD_ID = 27;
    const DEPARTMENT_ID = 30;
    const TABLE_ID = 31;
    const TIMETALBE_ID = 32;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model("Items_model");
        $this->load->model("types_model");
        $this->load->model("fields_model");
        $this->load->library('parser');
    }
    function index()
    {
        $this->load->au("schedule");
    }
    function view()
    {
        $this->load->admin("admin/views/schedule-user");
    }
    public function verify_recaptcha()
    {
        $recaptchaSecret = self::SERECT_KEY;
        $recaptchaResponse = $this->input->post('g-recaptcha-response');

        // Verify the reCAPTCHA response
        $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
        $responseData = json_decode($verifyResponse);

        if ($responseData->success) {
            echo json_encode(array("success" => true));
            die();
        } else {
            echo json_encode(array("success" => false, 'message' => "Xác thực không thành công. Vui lòng thử lại!"));
            die();
        }
    }
    public function search_schedule()
    {
        $search_key = strip_tags($this->input->post("search_key"));

        $user = $this->User_model->get_user_by_teacher_code($search_key);

        $tuan = $this->input->post("tuan");

        $timetables = $this->Items_model->find_by_type(self::TIMETALBE_ID);

        $data_show = [];

        $data_group_select = [];
        $data_date_select = [];

        foreach ($timetables as $key => $timetable) {

            if (!empty($tuan)) {
                $groups = $this->Items_model->get_groups($timetable->id, ['title' => $tuan]);
            } else {
                $groups = $this->Items_model->get_groups($timetable->id);
            }

            foreach ($groups as $key => $group) {

                $timetable_items = $this->Items_model->get_where($group->id, ['title' => $search_key]);

                if (count($timetable_items) > 0) {
                    $data_group_select[] = $group;
                }

                foreach ($timetable_items as $key => $timetable_item) {

                    $ngay_meta = $this->Items_model->get_meta($timetable_item->id, 'ngay')->value;

                    if (!in_array($ngay_meta, $data_date_select)) {
                        $data_date_select[] = $ngay_meta;
                    }

                    $data_show[] = [
                        'tiet' => $this->Items_model->get_meta($timetable_item->id, 'tiet')->value,
                        'tenhocphan' => $this->Items_model->get_meta($timetable_item->id, 'tenhocphan')->value,
                        'thu' => $this->Items_model->get_meta($timetable_item->id, 'thu')->value,
                        'phonghoc' => $this->Items_model->get_meta($timetable_item->id, 'phonghoc')->value,
                        'lop' => $this->Items_model->get_meta($timetable_item->id, 'lop')->value,
                        'ngay' => $this->Items_model->get_meta($timetable_item->id, 'ngay')->value,
                    ];
                }
            }
        }


        $user_info = [];

        if (isset($user)) {
            $user_info = [
                'user_name' => $user->firstname . " " . $user->lastname,
                'magv' => $user->magv,
            ];
        }

        $html = null;

        if (count($data_show) > 0) {
            $data['data'] = $data_show;
            $html = $this->load->view("admin/views/components/schedule-table-item", $data, true);
        }

        echo json_encode(array('success' => count($data_show) > 0 ? true : false, 'group_select' => $data_group_select, 'date_select' => $data_date_select, "user_info" => $user_info ? $user_info : "", "data_show" => $data_show ? $data_show : "", 'html' => $html));
    }
}
