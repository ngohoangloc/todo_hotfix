<?php



class Authenticate

{

    protected $CI;



    public function __construct()

    {

        $this->CI = &get_instance();

        $this->CI->load->model('Permission_model');

        $this->CI->load->model('Role_model');

    }



    public function is_authenticated()

    {

        if (!$this->CI->session->userdata('logged_in')) {

            return false;

        }



        return true;

    }



    public function has_permission()

    {

        if (!$this->CI->session->userdata('logged_in')) {

            return false;

        }



        $user_id = $this->CI->session->userdata('user_id');



        $permission_name = $this->CI->uri->segment(1, 0);

        $action = $this->CI->uri->segment(2, 0);





        $permission_code = $action == '0' ? $permission_name : $permission_name . '.' . $action;

        // echo($permission_code);

        // die();

        $permission = $this->CI->Permission_model->get_permission_by_user_and_code($user_id, $permission_code);



        return !empty($permission);

    }



    public function check_ip($ip)

    {

        $query = $this->CI->db->get('ip_address');



        $ips_query = $query->result_array();



        $ips = [];



        foreach ($ips_query as $_ip) {

            $ips[] = $_ip['ip'];

        }



        // if (!in_array($ip, $ips)) {

        //     redirect('/login');

        // }

    }



    public function is_superadmin()

    {

    }

}

