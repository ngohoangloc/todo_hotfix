<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('User_model');
        $this->load->model('items_model');

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }
    public function getUserByRoom()
    {

?>
        <div class="row mt-3">
            <span style="margin-bottom: 5px; font-size: 13px;">
                Gợi ý
            </span>

            <ul class="user_list">
                <?php $users_of_department = $this->User_model->get_board_members_of_the_same_department($this->session->userdata('user_id')) ?>

                <?php foreach ($users_of_department as $user) : ?>
                    <li class="user_list_item">
                        <img src="<?= base_url($user->avatar) ?>" alt="" style="border-radius: 50%;">
                        <span><?= $user->firstname . " " . $user->lastname ?></span>
                    </li>
                <?php endforeach ?>

            </ul>
        </div>
<?php

    }
    public function getDepartment()
    {
        $term = $this->input->get("term");
        if ($term)
            $this->db->like("title", $term);
        $this->db->where("parent_id", $this->config->item('id_bang_phong_ban'));
        $query =  $this->db->get("items");
        echo json_encode($query->result_array());
    }
}
