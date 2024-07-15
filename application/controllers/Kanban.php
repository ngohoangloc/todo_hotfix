<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kanban extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model("fields_model");
        $this->load->model("types_model");

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }

    public function view($folder_id, $id)
    {
        $data['project'] = $this->Items_model->find_by_id($id);
        $data['groups'] = $this->Items_model->get_groups_by_owner($id, "id", "asc");
        $data['first_group_id'] = $this->Items_model->get_first_group_id($id);
        $data['fields'] = $this->Items_model->get_fields($id);
        $data['items_all'] = $this->Items_model->get_all();
        $data['types'] = $this->types_model->get_all();

        $this->load->admin("admin/views/kanban", $data);
    }

    public function add()
    {
        $data = $this->input->post();

        $this->form_validation->set_rules('title', 'Tên công việc', 'required|max_length[255]');
        $this->form_validation->set_message('required', 'Vui lòng nhập tên công việc!');
        $this->form_validation->set_message('max_length', 'Tên công việc không được vượt quá {param} ký tự!');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('success' => false, 'errors' => validation_errors()));
            die();
        }
        $result = $this->Items_model->add($data);

        $meta = $this->Items_model->get_all_meta($result);

        echo json_encode(array('success' => $result ? true : false, "item_id" => $result , "data" => $data , "meta" => $meta));
    }

    public function update()
    {
        $result = $this->items_model->update($this->input->post(), $this->input->post('id'));
        if($result){
            echo json_encode(array('success' => true));
        }
    }

}