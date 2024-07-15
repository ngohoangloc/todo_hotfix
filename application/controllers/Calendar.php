<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("items_model");
        $this->load->model("fields_model");
        $this->load->model("types_model");

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }

    public function view($folder_id,$id)
    {
        if ($this->check_is_owner($id)) {
            $data['project'] = $this->items_model->find_by_id($id);
            // $data['groups'] = $this->items_model->get_groups_by_owner($id);
            $data['fields'] = $this->items_model->get_fields($id);
            // $data['items_all'] = $this->items_model->get_all();
            $data['types'] = $this->types_model->get_all();

            $this->load->admin("admin/views/calendar", $data);
        } else {
            return redirect('/items');
        }
    }

    public function events($item_id)
    {
        $result = $this->items_model->get_events_by_project_item($item_id);
        
        echo json_encode(array('success' => true, 'data' => $result));
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

    public function update()
    {
        if ($this->input->is_ajax_request()) {

            $data = [];

            foreach($this->input->post() as $key => $value)
            {
                if($value != null)
                {
                    $data[$key] = $value;
                }
            }

            $result = $this->items_model->update($data, $this->input->post('id'));

            echo json_encode(array('success' => $result));
        }
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
}
