<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FieldsOfType extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Fields_of_type');

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
        if (!$this->authenticate->has_permission()) {
            redirect('/access_denied');
        }
    }

    public function index()
    {


        $data['types'] = $this->Fields_of_type->get_types();

        $this->load->admin("/admin/fields_of_type/index", $data);
    }
    public function getall()
    {
        $fields_of_types = $this->Fields_of_type->get_all();
        echo json_encode(array('success' => count($fields_of_types) > 0 ? true : false, 'data' => $fields_of_types));
    }
    public function add()
    {
        $this->Fields_of_type->add($this->input->post());
        redirect("fields_of_type");
    }
    public function view($id)
    {
        $result = $this->Fields_of_type->get_by_id($id);

        $data['types'] = $this->Fields_of_type->get_types();

        echo json_encode(array("success" => $result, 'data' => $result));
    }
    public function update($id)
    {
        $result = $this->Fields_of_type->update($this->input->post(), $id);
        echo json_encode(array("success" => $result));
    }
    public function delete($id)
    {
        $result = $this->Fields_of_type->delete($id);

        echo json_encode(array("success" => $result));
    }
}
