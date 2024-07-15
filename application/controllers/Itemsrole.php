<?php
class ItemsRole extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Items_role_model');
        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }

    public function add()
    {
        $items_role_name = $this->input->post('items_role_name');
        $items_id = $this->input->post('items_id');

        $data = [
            'items_role_name' => $items_role_name,
            'items_id' => $items_id
        ];
        $result = $this->Items_role_model->add($data);

        if ($result) {
            $new_item_role = $this->Items_role_model->get_by_id($result); 
            $data['items_role'] = $new_item_role;
            $new_member_section_html = $this->load->view("admin/views/components/items_role/member-section", $data, true);
            echo json_encode(['success' => true, 'html' => $new_member_section_html]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function get_role_permissions() {
        $role_id = $this->input->post('role_id');
        $permissions = $this->Items_role_model->get_permissions_by_role($role_id);
        echo json_encode($permissions);
    }

    public function set_permission() {
        $role_id = $this->input->post('role_id');
        $permission_ids = $this->input->post('permission_ids');

        $result = $this->Items_role_model->update_role_permissions($role_id, $permission_ids);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function get_owners_by_group() {
        $role_id = $this->input->post('role_id');
        $group_id = $this->input->post('group_id');

        $owners = $this->Items_model->get_owners($group_id);

        $data['owners'] = $owners;
        $html = $this->load->view('admin/views/components/items_role/owners_list', $data, true);

        echo json_encode(['success' => true, 'html' => $html]);
    }

    public function add_users_to_role() {
        $role_id = $this->input->post('role_id');
        $owner_ids = $this->input->post('owner_ids');
    
        if (!empty($owner_ids)) {
            foreach ($owner_ids as $owner_id) {
                $data = [
                    'role_id' => $role_id,
                    'user_id' => $owner_id
                ];
                $this->Items_role_model->add_user_to_role($data);
            }
            $result = $this->Items_role_model->get_users_by_role($role_id);
            $html = $this->load->view('admin/views/components/items_role/members_list', ['members' => $result], true);
    
            echo json_encode(['success' => true, 'html' => $html]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No users selected']);
        }
    }
}
