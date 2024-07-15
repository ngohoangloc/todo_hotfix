<?php
class Items_role_model extends CI_Model
{
    public function find_by_item_id($id)
    {
        $query = $this->db->get_where("items_role", ["items_id" => $id]);
        $result = $query->result_object();
        return $result;
    }

    public function get_by_id($id)
    {
        $query = $this->db->get_where("items_role", ["id" => $id]);
        $result = $query->row_object();
        return $result;
    }

    public function add($data)
    {
        $result = $this->db->insert('items_role', $data);

        if ($result) {
            $items_role_id = $this->db->insert_id();
        }
        return $items_role_id;
    }

    public function get_items_permission()
    {
        $this->db->from("items_permission");
        $query = $this->db->get();
        $result = $query->result_object();
        return $result;
    }

    public function get_users_by_role($role_id)
    {
        $this->db->select('*');
        $this->db->from('users u');
        $this->db->join('items_role_user_map irm', 'u.id = irm.user_id');
        $this->db->where('irm.role_id', $role_id);
        $query = $this->db->get();
        return $query->result();
    }
    

    public function get_permissions_by_role($role_id) {
        $this->db->select('permission_id');
        $this->db->from('items_permission_role_map');
        $this->db->where('role_id', $role_id);
        $query = $this->db->get();
        return array_column($query->result_array(), 'permission_id');
    }

    public function update_role_permissions($role_id, $permission_ids) {
        $this->db->trans_start();
        $this->db->where('role_id', $role_id);
        $this->db->delete('items_permission_role_map');
        if (!empty($permission_ids)) {
            $data = [];
            foreach ($permission_ids as $permission_id) {
                $data[] = [
                    'role_id' => $role_id,
                    'permission_id' => $permission_id
                ];
            }
            $this->db->insert_batch('items_permission_role_map', $data);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function add_user_to_role($data) {
        $this->db->insert('items_role_user_map', $data);
    }
    

}
