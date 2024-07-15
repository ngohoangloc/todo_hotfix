<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Role_model');

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }

        if (!$this->authenticate->has_permission()) {
            redirect('/access_denied');
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        $department_id = $this->input->get('department_id');

        if ($user->roles_id == 26) { // role 26 "phòng đào tạo" chỉ hiển thị ,chỉ thêm sửa xóa role 25 "giáo viên" 
            if ($department_id) {
                $data['users'] = $this->User_model->get_users_by_role_and_department(25, $department_id);
            } else {
                $data['users'] = $this->User_model->get_user_by_role(25);
            }
            $data['roles'] = $this->Role_model->get_role(25);
        } else {
            if ($department_id && $department_id != 0) {
                $data['users'] = $this->User_model->get_all_except_by_department($user_id, $department_id);
            } else {
                $data['users'] = $this->User_model->get_all_except($user_id);
            }
            $data['roles'] = $this->Role_model->get_all();
        }

        $data['departments'] = $this->Items_model->get_child_items($this->config->item('id_bang_phong_ban'));
        $data['selected_department_id'] = $department_id;


        $this->load->admin('admin/users/index', $data);
    }



    public function store()
    {
        $this->form_validation->set_rules('Firstname', 'Họ', 'required|max_length[50]');
        $this->form_validation->set_rules('Lastname', 'Tên', 'required|max_length[50]');
        $this->form_validation->set_rules('Email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('Username', 'Username', 'required|min_length[5]|max_length[50]|is_unique[users.username]');
        $this->form_validation->set_rules('Password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('ConfirmPassword', 'Confirm Password', 'required|matches[Password]');
        $this->form_validation->set_rules('Role', 'Role', 'required|numeric');

        $this->form_validation->set_message('required', 'Vui lòng nhập {field}!');
        $this->form_validation->set_message('min_length', '{field} không được ít hơn {param} ký tự!');
        $this->form_validation->set_message('max_length', '{field} không được vượt quá {param} ký tự!');
        $this->form_validation->set_message('valid_email', 'Email không hợp lệ!');
        $this->form_validation->set_message('regex_match', '{field} không đúng format!');

        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'Firstname' => form_error('Firstname'),
                'Lastname' => form_error('Lastname'),
                'Email' => form_error('Email'),
                'Username' => form_error('Username'),
                'Password' => form_error('Password'),
                'ConfirmPassword' => form_error('ConfirmPassword'),
                'Role' => form_error('Role'),

            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $errors]));
            return;
        }

        $firstname = $this->input->post('Firstname');
        $lastname = $this->input->post('Lastname');
        $email = $this->input->post('Email');
        $username = $this->input->post('Username');
        $password = $this->input->post('Password');
        $confirmPassword = $this->input->post('ConfirmPassword');
        $roleId = $this->input->post('Role');
        $departmentId = $this->input->post('Department');
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'roles_id' => $roleId,
            'department_id' => $departmentId,
            'status' => true,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('users', $data);

        if ($this->db->affected_rows() > 0) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => 'Tạo người dùng thành công.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Lỗi.']));
        }
    }


    public function update()
    {
        $email = $this->input->post('Email1');

        if ($this->input->post('Old_Email') == $email) {
            $this->form_validation->set_rules('Email1', 'Email', 'required|valid_email');
        } else {
            $this->form_validation->set_rules('Email1', 'Email', 'required|valid_email|is_unique[users.email]');
        }
        $this->form_validation->set_rules('Firstname1', 'First name', 'required|max_length[50]');
        $this->form_validation->set_rules('Lastname1', 'Last name', 'required|max_length[50]');
        $this->form_validation->set_rules('Role1', 'Role', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'Firstname' => form_error('Firstname1'),
                'Lastname' => form_error('Lastname1'),
                'Email' => form_error('Email1'),
                'Role' => form_error('Role1'),

            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $errors]));
            return;
        }
        $id = $this->input->post('id1');
        $firstname = $this->input->post('Firstname1');
        $lastname = $this->input->post('Lastname1');
        $email =  $this->input->post('Email1');
        $roleId = $this->input->post('Role1');
        $departmentId = $this->input->post('Department1');


        $data = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'roles_id' => $roleId,
            'department_id' => $departmentId,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id);
        $this->db->update('users', $data);
    }


    public function updatePassword()
    {

        $this->form_validation->set_rules('NewPassword', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('ConfirmPassword1', 'Confirm Password', 'required|matches[NewPassword]');

        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'NewPassword' => form_error('NewPassword'),
                'ConfirmPassword' => form_error('ConfirmPassword1')

            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $errors]));
            return;
        }

        $id = $this->input->post('id');
        $newpassword = $this->input->post('NewPassword');
        $confirmpassword = $this->input->post('ConfirmPassword1');

        if ($newpassword !== $confirmpassword) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Passwords do not match.']));
            return;
        }

        $hashedPassword = password_hash($newpassword, PASSWORD_BCRYPT);

        $this->db->where('id', $id);

        $data = [
            'password' => $hashedPassword,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->db->update('users', $data)) {
            $this->session->set_flashdata('success', 'Password updated successfully.');
            redirect('user');
        } else {
            $this->session->set_flashdata('error', 'Failed to update password.');
            redirect('user/changepassword/' . $id);
        }
    }

    public function delete($id)
    {
        $user = $this->db->get_where('users', ['id' => $id])->row();
        if (!$user) {
            return;
        }
        $newStatus = !$user->status;
        $data = [
            'status' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('id', $id);
        $this->db->update('users', $data);
    }

    public function search_users()
    {
        if ($this->input->get('search') != null) {
            $users = $this->User_model->get_users_by_search($this->input->get('search'));
        } else {
            $users = [];
        }
        echo json_encode(array('success' => true, 'data' => $users));
    }

    // public function search_department()
    // {
    //     if ($this->input->get('search') != null) {
    //         $department = $this->User_model->get_department($this->input->get('search'));
    //     } else {
    //         $department = [];
    //     }
    //     echo json_encode(array('success' => true, 'data' => $department));
    // }

    // public function search_users_input()
    // {
    //     if ($this->input->get('search') == '') {
    //         $user_id = $this->session->userdata('user_id');

    //         $users = $this->User_model->get_board_members_of_the_same_department($user_id);
    //     } else {
    //         $search = $this->input->get('search');
            
    //         $users = $this->User_model->get_users_by_search($search);
    //     }

    //     $html = '';

    //     foreach ($users as $user) {
    //         $data = [
    //             'user' => $user,
    //             'project_id' => $this->input->get('project_id'),
    //             'group_id' => $this->input->get('group_id'),
    //             'meta_id' => $this->input->get('meta_id'),
    //         ];

    //         $html .= $this->load->view('admin/views/components/input/people_search', $data, true);
    //     }

    //     echo json_encode(['success' => true, 'data' => $html]);
    //     die();
    // }

    public function filter()
    {
    }
}
