<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Input extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model("Items_model");

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }
    public function index()
    {
        $data = [];
        $this->load->admin('admin/input',$data);
       
    }
    public function gettypehtml()
    {
        $type_html = $this->input->post('type_html');
        $this->load->view("admin/views/input/" . $type_html);
    }
}