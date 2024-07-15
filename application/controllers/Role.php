<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Role extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Role_model');
        $this->load->model('Permission_model');

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }

        if (!$this->authenticate->has_permission()) {
            redirect('/access_denied');
        }
        
    }

    public function index()
    {
        $data['role'] = $this->Role_model->get_all();
        $this->load->admin("admin/role/index", $data);
    }


    public function create()
    {
        $this->load->admin("admin/role/create");
    }

    public function store()
    {

        $this->form_validation->set_rules('Rolename', 'Role name', 'required|regex_match[/^[a-zA-Z0-9\s]+$/]');
        $this->form_validation->set_message('required', 'Vui lòng nhập {field}!');
        $this->form_validation->set_message('regex_match', '{field} không đúng format!');
        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'Rolename' => form_error('Rolename')
            ];
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $errors]));
            return;
        }
        

        $rolename = $this->input->post('Rolename');


        $data = [
            'role_name' => $rolename,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->Role_model->insert('roles', $data);

    }

    public function edit($id)
    {
        $role = $this->Role_model->get_role_by_id($id);
        echo json_encode($role);
    }

    public function update()
    {
        $this->form_validation->set_rules('Role_name', 'Role name', 'required|regex_match[/^[a-zA-Z0-9\s]+$/]');
        $this->form_validation->set_message('required', 'Vui lòng nhập {field}!');
        $this->form_validation->set_message('regex_match', '{field} không đúng format!');
        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'Role_name' => form_error('Role_name')
            ];
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $errors]));
            return;
        }
        $id = $this->input->post('Role_id');
        $rolename = $this->input->post('Role_name');

        $data = [
            'role_name' => $rolename,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id); 
        $this->db->update('roles', $data);

        $log = [
            'type' => 'PUT',
            'table' => 'roles',
            'table_id' => $id,
            'message' => 'success',
            'value_old' => null,
            'value_new' => $data['role_name'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'user_id' => $this->session->userdata('user_id'),
        ];
        $this->logs_model->create($log);
    }

    public function delete($id)
    {
        $this->Role_model->delete_role($id);
    }

    
}
