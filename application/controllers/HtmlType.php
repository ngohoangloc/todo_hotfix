<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HtmlType extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->model("Html_types_model");

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }
    public function index()
    {
        $data['html_types'] = $this->Html_types_model->get_all();

        $this->load->admin("admin/html_type/index", $data);
    }
    public function view($id)
    {
        $result = $this->Html_types_model->view($id);
        echo json_encode(array("success" => $result ? true : false, 'data' => $result));
    }
    public function create()
    {

        $result = $this->Html_types_model->add($this->input->post());

        echo json_encode(array("success" => $result));
    }
    public function edit($id)
    {
        $result = $this->Html_types_model->update($this->input->post(), $id);

        echo json_encode(array("success" => $result));
    }
    public function delete($id)
    {
        $result = $this->Html_types_model->delete($id);

        echo json_encode(array("success" => $result));
    }
}
