<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Permission extends CI_Controller
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
    //Permission
    public function permission_list()
    {
        $data['permission'] = $this->Permission_model->get_all();
        $this->load->admin("admin/role/permission_list", $data);
    }


    public function permission_store()
    {

        $this->form_validation->set_rules('permissionName', 'Permission name', 'required|max_length[50]');
        $this->form_validation->set_rules('permissionCode', 'Permission code', 'required|max_length[50]');
        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'Name' => form_error('permissionName'),
                'Code' => form_error('permissionCode'),

            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $errors]));
            return;
        }
        $permissionName = $this->input->post('permissionName');
        $permissionCode = $this->input->post('permissionCode');


        $this->db->trans_start();

        $data = [
            'permission_name' => $permissionName,
            'permission_code' => $permissionCode,
            'parent' => 0
        ];
        $this->db->insert('permissions', $data);

        $parentId = $this->db->insert_id();

        $childPermissions = ['view', 'create', 'edit', 'delete'];
        foreach ($childPermissions as $childPermission) {
            $childData = [
                'permission_name' => $permissionName . ' ' . $childPermission,
                'permission_code' => $permissionCode . '.' . $childPermission,
                'parent' => $parentId
            ];
            $this->db->insert('permissions', $childData);
        }


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => 'Failed to add permissions']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 'success', 'message' => 'Permissions added successfully']);
        }
    }

    public function permission_update()
    {
        $this->form_validation->set_rules('permissionName', 'Permission name', 'required|max_length[50]');
        $this->form_validation->set_rules('permissionCode', 'Permission code', 'required|max_length[50]');
        
        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'Name' => form_error('permissionName'),
                'Code' => form_error('permissionCode'),
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $errors]));
            return;
        }
    
        $permissionId = $this->input->post('permissionId');
        $permissionName = $this->input->post('permissionName');
        $permissionCode = $this->input->post('permissionCode');
    
        $this->db->trans_start();
    
    
        $data = array(
            'permission_name' => $permissionName,
            'permission_code' => $permissionCode
        );
        $this->db->where('id', $permissionId);
        $this->db->update('permissions', $data);
    
        $this->update_child_permissions($permissionId, $permissionName, $permissionCode);
    

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => 'Failed to update permissions']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 'success', 'message' => 'Permissions updated successfully']);
        }
    }

    public function permission_delete()
    {
        $id = $this->input->post('id');

        if (!empty($id)) {
            $this->Permission_model->delete_permission($id);
            
        } 
    }

    private function update_child_permissions($parent_id, $permissionName, $permissionCode)
    {
        $this->db->select('id, permission_name, permission_code');
        $this->db->where('parent', $parent_id);
        $child_permissions = $this->db->get('permissions')->result_array();

        foreach ($child_permissions as $child) {
            $child_code_parts = explode('.', $child['permission_code']);
            $child_suffix = end($child_code_parts);

            $new_permission_code = $permissionCode . '.' . $child_suffix;

            $childData = array(
                'permission_name' => $permissionName . substr($child['permission_name'], strrpos($child['permission_name'], ' ')), 
                'permission_code' => $new_permission_code
            );
            $this->db->where('id', $child['id']);
            $this->db->update('permissions', $childData);
        }
    }

    //Subpermission
    public function get_subpermission()
    {
        $permissionId = $this->input->post('permissionId');
        $subPermissions = $this->Permission_model->get_by_id($permissionId);

        echo json_encode($subPermissions);
    }

    public function sub_permission_store()
    {
        $this->form_validation->set_rules('subPermissionName', 'Permission name', 'required|max_length[50]');
        $this->form_validation->set_rules('subPermissionCode', 'Permission code', 'required|max_length[50]|callback_check_permission_code_format');
        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'Name' => form_error('subPermissionName'),
                'Code' => form_error('subPermissionCode'),

            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $errors]));
            return;
        }


        $permissionId = $this->input->post('permissionId');
        $permissionName = $this->input->post('subPermissionName');
        $permissionCode = $this->input->post('subPermissionCode');
        $data = [
            'permission_name' => $permissionName,
            'permission_code' => $permissionCode,
            'parent' => $permissionId
        ];
        $this->db->insert('permissions', $data);

        echo json_encode(['status' => 'success', 'message' => 'Permissions added successfully']);
    }

    public function check_permission_code_format($code)
    {
        if (!preg_match('/^[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)+$/', $code)) {
            $this->form_validation->set_message('check_permission_code_format', 'Phần sau dấu chấm không được để trống');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function edit_subpermission()
    {
        $postData = $this->input->post('subPermissions');

        foreach ($postData as $id => $data) {
            $this->Permission_model->update_permission($id, $data);
        }

        echo json_encode(['status' => 'success']);
    }

    public function delete_subpermission()
    {
        $subPermissionId = $this->input->post('subPermissionId');
        $this->Permission_model->delete_subpermission($subPermissionId);

        echo json_encode(['status' => 'success']);
       
    }

    //Add permission to role
    public function addpermission($role_id)
    {
        $data['role'] = $this->Role_model->get_role_by_id($role_id);
        $data['permissions'] = $this->Permission_model->get_all();
        $data['assigned_permissions'] = $this->Permission_model->get_permissions_by_role_id($role_id);

        $this->load->admin("admin/role/addpermission", $data);
    }

    public function savepermission()
    {
        $role_id = $this->input->post('role_id');
        $permissions = $this->input->post('permissions');

        $this->Role_model->delete_permissions($role_id);
        if (!empty($permissions)) {
            $permission_data = [];
            foreach ($permissions as $permission_id) {
                $permission_data[] = [
                    'role_id' => $role_id,
                    'permission_id' => $permission_id,
                ];
            }
            $this->db->insert_batch('roles_permissions', $permission_data);
        }

        $this->session->set_flashdata('success', 'Permissions updated successfully.');
        redirect(base_url('role'));
    }
}
