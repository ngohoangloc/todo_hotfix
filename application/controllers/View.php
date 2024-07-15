<?php

class View extends CI_Controller
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

    public function index($item_id)
    {
        if ($this->check_is_owner($item_id)) {
            $data['project'] = $this->Items_model->find_by_id($item_id);
            $data['groups'] = $this->Items_model->get_groups_by_owner($item_id, "id", "asc");
            $data['is_owner'] = false;
            if (in_array($this->session->userdata('user_id'), explode(',', $data['project']->owners))) {
                $data['is_owner'] = true;
            }

            $data['types'] = $this->types_model->get_all();

            $this->load->admin("admin/views/index", $data);
        } else {
            return redirect('/items');
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
