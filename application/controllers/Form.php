<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Form extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model("Items_model");
        $this->load->model("fields_model");
        $this->load->model("types_model");

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }
    public function view($folder_id,$id)
    {
        if ($this->check_is_owner($id)) {
            $data['project'] = $this->Items_model->find_by_id($id);
            $data['first_group_id'] = $this->Items_model->get_first_group_id($data['project']->id);
            $data['fields'] = $this->Items_model->get_fields($data['project']->id);
            $data['items_all'] = $this->Items_model->get_all();
            $data['types'] = $this->types_model->get_all();
            $this->load->admin("admin/views/form", $data);
        } else {
            return redirect('/items');
        }
    }
    public function add()
    {

        $data = $this->input->post();

        $this->form_validation->set_rules('title', 'Tên công việc', 'required');
        // Kiểm tra độ dài title

        $this->form_validation->set_message('required', 'Vui lòng nhập tên công việc!');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('success' => false, 'errors' => validation_errors()));
            die();
        }

        $result = $this->Items_model->add($data);

        echo json_encode(array('success' => $result ? true : false, "item_id" => $result));
    }

    function check_is_owner($item_id)
    {
        if ($this->session->userdata('user_id')) {
            $item = $this->Items_model->find_by_id($item_id);
            if (!$item) {
                return false;
            }

            $groups = $this->Items_model->get_groups($item->id);

            foreach ($groups as $group) {
                $owners = explode(',', $group->owners);

                if (in_array($this->session->userdata('user_id'), $owners)) {
                    return true;
                }
            }

            $owners = explode(',', $item->owners);

            return in_array($this->session->userdata('user_id'), $owners) ? true : false;
        } else {
            return false;
        }
    }

    function share_form($id)
    {
        $project = $this->Items_model->find_by_id($id);

        $data['project'] = $project;
        $data['fields'] = $this->Items_model->get_fields($id);

        $this->load->au("share_form", $data);
    }

    function decrypt($data, $key)
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }
}
