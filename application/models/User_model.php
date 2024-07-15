<?php
class User_model extends CI_Model
{

    public function get_all()
    {
        return $this->db->get('users')->result();
    }

    public function get_user_by_username($username)
    {
        $this->db->where('username', $username);
        return $this->db->get('users')->row();
    }

    public function get_board_members_of_the_same_department($user_id)
    {
        $user = $this->get_user_by_id($user_id);

        $this->db->where('department_id', $user->department_id);
        $this->db->where('status', true);

        return $this->db->get('users')->result_object();
    }

    public function verify_password($username, $password)
    {
        $user = $this->get_user_by_username($username);
        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return FALSE;
    }

    public function checkPassword($userId, $password)
    {
        $this->db->select('password');
        $this->db->where('id', $userId);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            $user = $query->row();
            if (password_verify($password, $user->password)) {
                return true;
            }
        }
        return false;
    }

    public function changePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Cập nhật mật khẩu mới vào cơ sở dữ liệu
        $this->db->set('password', $hashedPassword);
        $this->db->where('id', $userId);
        $this->db->update('users');
    }

    public function get_user_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('users')->row_object();
    }

    public function get_all_except($user_id)
    {
        $this->db->where('id !=', $user_id);
        return $this->db->get('users')->result();
    }

    public function update_user($userId, $data)
    {
        $this->db->where('id', $userId);
        $this->db->update('users', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_user($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('users');

        return $this->db->affected_rows() > 0;
    }

    public function get_user_by_email($email)
    {
        return $this->db->where('email', $email)->get('users')->row();
    }

    public function create_user($data)
    {
        $result = $this->db->insert('users', $data);
        if ($result) {
            $log = [
                'type' => 'POST',
                'title' => 'users',
                'value_old' => '',
                'value_new' => $data['username'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => $this->session->userdata('user_id'),
                'ip' => $this->session->userdata('ip'),
            ];
            $this->logs_model->create($log);
        }
        return $result;
    }

    public function find_in_set($id_string)
    {
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("FIND_IN_SET(id, '$id_string')");
        $result = $this->db->get()->result_object();
        return $result;
    }

    public function get_users_by_search($search)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('status', 1);
        $this->db->group_start();
        $this->db->like('username', $search);
        $this->db->or_like('email', $search);
        $this->db->or_like("CONCAT(firstname, ' ', lastname)", $search);
        $this->db->group_end();
        $query = $this->db->get();

        return $query->result_object();
    }

    public function get_department($search)
    {
        $all_departments = $this->Items_model->get_child_items($this->config->item('id_bang_phong_ban'));

        $departments = array();

        foreach ($all_departments as $department) {
            if (stripos($department->title, $search) !== false) {
                $departments[] = [
                    'id' => $department->id,
                    'title' => $department->title
                ];
            }
        }

        return $departments;
    }

    public function get_users_by_department($department_id)
    {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('department_id', $department_id);
        $query = $this->db->get();

        return $query->result_object();
    }

    public function is_superadmin()
    {
        $userId = $this->session->userdata('user_id');
        $user = $this->get_user_by_id($userId);

        if ($user->roles_id == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function get_user_by_role($role_id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('roles_id', $role_id);
        $query = $this->db->get();

        return $query->result_object();
    }

    public function get_users_by_role_and_department($role_id, $department_id)
    {
        $this->db->where('roles_id', $role_id);
        $this->db->where('department_id', $department_id);
        return $this->db->get('users')->result();
    }

    public function get_all_except_by_department($user_id, $department_id)
    {
        $this->db->where('id !=', $user_id);
        $this->db->where('department_id', $department_id);
        return $this->db->get('users')->result();
    }

    public function get_user_by_teacher_code($code)
    {
        $result = $this->db->get_where("users", ['magv' => $code])->row_object();
        return $result;
    }
}
