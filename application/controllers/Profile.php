<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }

    public function index()
    {
    }

    public function changePassword()
    {
        $userId = $this->session->userdata('user_id');
        $currentPassword = $this->input->post('currentPassword');
        $newPassword = $this->input->post('newPassword');
        $confirmPassword = $this->input->post('confirmPassword');

        $this->form_validation->set_rules('currentPassword', 'Mật khẩu hiện tại', 'required|callback_check_current_password');
        $this->form_validation->set_rules('newPassword', 'Mật khẩu mới', 'required');
        $this->form_validation->set_rules('confirmPassword', 'Xác nhận mật khẩu', 'required|matches[newPassword]');

        $this->form_validation->set_message('required', 'Vui lòng nhập {field}!');
        $this->form_validation->set_message('matches', '{field} không khớp với {param}!');
        $this->form_validation->set_message('check_current_password', 'Mật khẩu hiện tại không chính xác.');

        if ($this->form_validation->run() == false) {
            $errors = array(
                'currentPassword' => form_error('currentPassword'),
                'newPassword' => form_error('newPassword'),
                'confirmPassword' => form_error('confirmPassword')
            );
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $errors]));
            return;
        } else {
            $this->User_model->changePassword($userId, $newPassword);
            $response['success'] = true;
            $response['message'] = "Mật khẩu đã được thay đổi thành công.";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function check_current_password($currentPassword)
    {
        $userId = $this->session->userdata('user_id');
        if ($this->User_model->checkPassword($userId, $currentPassword)) {
            return true;
        } else {
            $this->form_validation->set_message('check_current_password', 'Mật khẩu hiện tại không chính xác.');
            return false;
        }
    }

    public function updateProfile()
    {
        $userId = $this->session->userdata('user_id');
        // $firstName = $this->input->post('firstName');
        // $lastName = $this->input->post('lastName');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
    
        // $this->form_validation->set_rules('firstName', 'Họ', 'required|max_length[50]');
        // $this->form_validation->set_rules('lastName', 'Tên', 'required|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Số điện thoại', 'regex_match[/^[0-9]+$/]|max_length[10]', array('regex_match' => 'Số điện thoại không hợp lệ.'));

        $this->form_validation->set_message('required', 'Vui lòng nhập {field}!');
        $this->form_validation->set_message('valid_email', 'Email không hợp lệ!');
        $this->form_validation->set_message('max_length', '{field} không được vượt quá {param} ký tự!');

        if ($this->form_validation->run() == false) {
            $errors = array(
                // 'firstName' => form_error('firstName'),
                // 'lastName' => form_error('lastName'),
                'email' => form_error('email'),
                'phone' => form_error('phone'),

            );
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $errors]));
            return;
        } else {
            if (!empty($_FILES['avatar']['name'])) {
                // $avatarFileName = $_FILES['avatar']['name'];
                // $avatarFilePath = 'uploads/avatars/' . $avatarFileName;

                $oldAvatar = $this->User_model->get_user_by_id($userId)->avatar;

                if (file_exists($oldAvatar) && $oldAvatar != "uploads/avatars/dapulse_default_photo.png") {
                    unlink($oldAvatar);                    
                }
                
                $config['upload_path'] = 'uploads/avatars/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 2048;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('avatar')) {
                    $response['success'] = false;
                    $response['message'] = $this->upload->display_errors();
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    return;
                } else {
                    $upload_data = $this->upload->data();
                    $avatar_url = 'uploads/avatars/' . $upload_data['file_name'];
                }
              
    
                $data = array(
                    // 'firstname' => $firstName,
                    // 'lastname' => $lastName,
                    'email' => $email,
                    'phone' => $phone,
                    'avatar' => $avatar_url
                );
                $update = $this->User_model->update_user($userId, $data);
    
                if ($update) {
                    $response['success'] = true;
                    $response['message'] = 'Thông tin người dùng và avatar đã được cập nhật thành công.';
                    $response['avatar_url'] = base_url() . $avatar_url;
                    $response['user'] = $this->User_model->get_user_by_id($userId);
                } else {
                    $response['error'] = 'Lỗi';
                }
            } else {
                // Không có hình được chọn, chỉ cập nhật thông tin người dùng
                $data = array(
                    // 'firstname' => $firstName,
                    // 'lastname' => $lastName,
                    'email' => $email,
                    'phone' => $phone
                );
                $this->User_model->update_user($userId, $data);
    
                $response['success'] = true;
                $response['message'] = 'Thông tin người dùng đã được cập nhật thành công.';
            }
        }
    
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    

}
