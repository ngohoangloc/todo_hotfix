<?php
class Permission_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }
    public function get_all()
    {
        return $this->db->get('permissions')->result();
    }

    public function get_permissions_by_role_id($role_id)
    {
        $this->db->select('p.*');
        $this->db->from('permissions AS p');
        $this->db->join('roles_permissions AS rp', 'p.id = rp.permission_id');
        $this->db->where('rp.role_id', $role_id);

        return $this->db->get()->result();
    }

    public function get_permission_by_user_and_code($user_id, $permission_code)
    {
        $this->db->select('p.id')
            ->from('users u')
            ->join('roles r', 'u.roles_id = r.id', 'left')
            ->join('roles_permissions rp', 'r.id = rp.role_id')
            ->join('permissions p', 'rp.permission_id = p.id')
            ->where('u.id', $user_id)
            ->where('p.permission_code', $permission_code)
            ->limit(1);

        return $this->db->get()->row_array();
    }


    public function get_permissions_by_parent()
    {
        $this->db->where('parent', '0');
        $query = $this->db->get('permissions');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }
    }

    public function get_permissions_by_role($role_id)
    {
        $this->db->select('*');
        $this->db->from('roles_permissions');
        $this->db->join('permissions', 'roles_permissions.permission_id = permissions.id');
        $this->db->where('roles_permissions.role_id', $role_id);
        $query = $this->db->get();
        return $query->result();
    }


    public function get_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('permissions');
        $this->db->where('parent', $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return [];
        }
    }

    public function update_permission($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('permissions', $data);
    }

    public function delete_subpermission($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('permissions');
    }
    public function delete_permission($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('permissions');
        $this->delete_children_permissions($id);
    }

    private function delete_children_permissions($parent_id)
    {
        $this->db->where('parent', $parent_id);
        $children = $this->db->get('permissions')->result();

        if (!empty($children)) {
            foreach ($children as $child) {
                $this->delete_permission($child->id);
            }
        }
    }
}
