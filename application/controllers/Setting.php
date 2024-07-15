<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Setting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ip_model');
        $this->load->model('Config_model');
        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }


        if (!$this->authenticate->has_permission()) {
            redirect('/access_denied');
        }
    }

    public function index()
    {
        $data['ip'] = $this->Ip_model->get_all();
        $data['config'] = $this->Config_model->get_all();

        $this->load->admin("admin/views/setting", $data);
    }

    public function create()
    {
        $this->form_validation->set_rules('ip_address', 'IP Address', 'required|valid_ip');

        if ($this->form_validation->run() == FALSE) {
            $data['error'] = validation_errors();
            $data['ip'] = $this->Ip_model->get_all();

            $this->load->admin("admin/views/setting", $data);
        } else {
            $ip_address = $this->input->post('ip_address');
            $data = [
                'ip' => $ip_address
            ];
            $this->Ip_model->add_ip($data);
            redirect('/setting');
        }
    }

    public function edit()
    {
        $oldIp = $this->input->post('oldIp');
        $newIp = $this->input->post('newIp');

        $this->Ip_model->edit_ip($oldIp, $newIp);
    }


    public function delete()
    {
        $ip = $this->input->post("ip");
        $this->Ip_model->delete_ip($ip);
    }

    public function update_value($id)
    {
        $data =  $this->input->post('value');
        $result = $this->Config_model->update($id, $data);
        echo json_encode(array('success' => $result, $this->input->post()));
    }

    public function add_setting()
    {
        $name = $this->input->post('name');
        $key = $this->input->post('key');
        $value = $this->input->post('value');
        $type = $this->input->post('type');

        $data = [
            'name' => $name,
            'key' => $key,
            'value' => $value,
            'type' => $type
        ];

        $result = $this->Config_model->add($data);

        $data['id'] = $result;

        $config_item_html = $this->load->view("admin/views/components/setting/config-item" , $data , true);
                
        echo json_encode(array('success' => $result, 'html' => $config_item_html));
    }
}
