<?php

class Folder extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Items_model");
    }

    public function view($id)
    {
        $data['generalProjects'] = $this->Items_model->get_items_general($id);
        $data['personalProjects'] = $this->Items_model->get_where($id, ['user_id' => $this->session->userdata("user_id")]);;

        $this->load->admin("admin/folder", $data);
    }
}
