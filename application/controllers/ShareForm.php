<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ShareForm extends CI_Controller
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
    }

    function share_form($id)
    {

        $key = $this->config->item("image_key");
        $id_decrypt = $this->stringencryption->decryptString($id, $key);

        $project = $this->Items_model->find_by_id($id_decrypt);

        $data['project'] = $project;

        $data['fields'] = $this->Items_model->get_fields($id_decrypt);

        $this->load->au("share_form", $data);
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
}
