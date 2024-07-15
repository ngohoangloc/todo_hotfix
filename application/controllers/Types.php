<?php

class Types extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model("types_model");

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }

    public function index($id = null)
    {
        $data['types'] = $this->types_model->get_all();

        $this->load->admin('/admin/types', $data);
    }

    public function fetchTypes()
    {
        $result = $this->types_model->get_all();

        if ($result) {
            echo json_encode(array('success' => true, 'data' => $result));
        }
    }

    public function add()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('title', 'Title', 'required');

            $this->form_validation->set_rules('slug', 'Slug', 'required');

            if ($this->form_validation->run() === FALSE) {
                $data = array('success' => false, 'errors' => validation_errors());
            } else {
                $this->types_model->add($this->input->post());
                $data = array('success' => true);
            }
            echo json_encode($data);
        }
    }

    public function update($id = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('title', 'Title', 'required');

            $this->form_validation->set_rules('slug', 'Slug', 'required');

            if ($this->form_validation->run() === FALSE) {
                $data = array('success' => false, 'errors' => validation_errors());
            } else {
                $result = $this->types_model->update($id);
                $data = array('success' => true);
            }
            echo json_encode($data);
        }
    }
    public function delete($id = null)
    {
        if ($id) {
            $result = $this->types_model->delete($id);
            echo json_encode(array('success' => $result));
        }
    }

    public function fetch_by_id($id = null)
    {
        if ($id) {
            $result = $this->types_model->get_type($id);
            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }
}
