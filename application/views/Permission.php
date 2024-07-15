<?php

class Permission {

    protected $CI;

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->model('Permission_model'); // Load your permission model
    }

    /**
     * Checks if the current user has a specific permission.
     *
     * @param string $permission_code The permission code to check.
     * @return bool TRUE if the user has the permission, FALSE otherwise.
     */
    public function has_permission($permission_code) {
        if (!$this->CI->session->userdata('logged_in')) {
            return false; // Not logged in, no access
        }

        $user_id = $this->CI->session->userdata('user_id');
        $permission = $this->CI->Permission_model->get_permission_by_user_and_code($user_id, $permission_code);

        return !empty($permission);
    }

}