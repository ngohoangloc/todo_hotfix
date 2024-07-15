<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomTable extends CI_Controller
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

    public function index()
    {
        $item_id = $this->input->get('item_id');

        var_dump($item_id);
        die();
    }

    function view($folder_id, $id)
    {
        if ($this->check_is_owner($id)) {
            $data['project'] = $this->Items_model->find_by_id($id);
            $data['groups'] = $this->Items_model->get_groups_by_owner($id, "id", "asc");
            $data['is_owner'] = false;
            if(in_array($this->session->userdata('user_id'), explode(',', $data['project']->owners)))
            {
                $data['is_owner'] = true;
            }

            $data['types'] = $this->types_model->get_all();

            $this->load->admin("admin/views/customtable", $data);
            
        } else {
            return redirect('/items');
        }
    }

    function add()
    {
        $this->Items_model->add($this->input->post());
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
