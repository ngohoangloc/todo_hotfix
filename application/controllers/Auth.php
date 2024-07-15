<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('logs_model');
    }

    public function show_login()
    {
        if ($this->authenticate->is_authenticated()) {
            return redirect('/items');
        }
       
        if (isset($_GET['code'])) {

            $ip_address = $this->input->ip_address();
            $this->authenticate->check_ip($ip_address);

            $this->googleplus->getAuthenticate();
            $this->session->set_userdata('login', true);
            $gpInfo = $this->googleplus->getUserInfo();

            $userInfo['email'] = $gpInfo['email'];

            $checkUser = $this->db->get_where('users', ['email' => $userInfo['email']])->row();
            
            $user = $this->User_model->get_user_by_email($userInfo['email']);

            if ($checkUser) {
                $this->session->set_userdata('logged_in', TRUE);
                $this->session->set_userdata('user_id', $user->id);
                $this->session->set_userdata('email', $user->email);
                $this->session->set_userdata('username', $user->username);
                $this->session->set_userdata('role_id', $user->roles_id);
                $this->session->set_userdata('full_name', $user->firstname . ' ' . $user->lastname);

                $ip_address = $this->input->ip_address();
                $this->session->set_userdata('ip', $ip_address);

                $log = [
                    'type' => 'LOGIN',
                    'table' => 'users',
                    'table_id' => $user->id,
                    'message' => 'login google success',
                    'value_old' => null,
                    'value_new' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at'=> date('Y-m-d H:i:s'),
                    'user_id' => $user->id,
                    'ip' => $ip_address
                ];

                $this->logs_model->create($log);

                return redirect('/items');
            } else {
                $this->session->set_flashdata('errors', 'Email không tồn tại');
            }
        }

        $data['login_url'] = $this->googleplus->loginURL();
        $this->load->au('login', $data);
    }

    public function login()
    {
        $this->form_validation->set_rules('username', 'Tên người dùng', 'required');
        $this->form_validation->set_rules('password', 'Mật khẩu', 'required');

        $this->form_validation->set_message('required', 'Vui lòng nhập {field}!');
        $this->form_validation->set_message('regex_match', '{field} không đúng định dạng!');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();

            $this->session->set_flashdata('errors', $errors);
            return redirect('/login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $ip_address = $this->input->ip_address();
            $this->authenticate->check_ip($ip_address);

            $this->load->model('User_model');
            $user = $this->User_model->verify_password($username, $password);
            
            if ($user && $user->status == true) {
                $this->session->set_userdata('logged_in', TRUE);
                $this->session->set_userdata('user_id', $user->id);
                $this->session->set_userdata('username', $user->username);
                $this->session->set_userdata('email', $user->email);
                $this->session->set_userdata('role_id', $user->roles_id);
                $this->session->set_userdata('full_name', $user->firstname . ' ' . $user->lastname);

                $ip_address = $this->input->ip_address();
                $this->session->set_userdata('ip', $ip_address);

                $ip_address = $this->input->ip_address();
                $this->session->set_userdata('ip', $ip_address);

                $log = [
                    'type' => 'LOGIN',
                    'table' => 'users',
                    'table_id' => $user->id,
                    'message' => 'success',
                    'value_old' => null,
                    'value_new' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at'=> date('Y-m-d H:i:s'),
                    'user_id' => $user->id,
                    'ip' => $ip_address
                ];

                $this->logs_model->create($log);

                return redirect('/items');
            }
            else if($user && !$user->status) {
                $this->session->set_flashdata('errors', 'Tài khoản đã bị vô hiệu hóa!');
                return redirect('/login');
            }
            else {
                $this->session->set_flashdata('errors', 'Tên người dùng hoặc mật khẩu không chính xác.');
                return redirect('/login');
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->googleplus->revokeToken();

        redirect('/login');
    }

    public function access_denied()
    {
        if (!$this->authenticate->is_authenticated()) {
            return redirect('/login');
        }
        $this->load->view('access_denied');
    } 
}
