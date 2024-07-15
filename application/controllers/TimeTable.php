<?php
class TimeTable extends CI_Controller
{
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

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }
    function view($folder_id, $id)
    {
        $data['project'] = $this->Items_model->find_by_id($id);
        $data['groups'] = $this->Items_model->get_groups_by_owner($id, "id", "asc");

        $data['is_owner'] = false;
        if (in_array($this->session->userdata('user_id'), explode(',', $data['project']->owners))) {
            $data['is_owner'] = true;
        }

        $data['types'] = $this->types_model->get_all();

        $this->load->admin("admin/views/timetable", $data);
    }
}
