<?php

class Notification extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Notifications_model');
        $this->load->helper('url');
        $this->load->library('session');

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }

    public function countNotification()
    {
        $user_id = $this->session->userdata('user_id');

        $total_unread_notifications = $this->Notifications_model->count_unread_notifications($user_id);

        echo json_encode(['success' => true, 'data' => $total_unread_notifications]);
    }

    public function fetchUnreadNotifications()
    {

    ?>
        <?php $notifications = $this->Notifications_model->get_unread_notifications($this->input->get('user_id')) ?>

        <?php foreach ($notifications as $notification) : ?>
            <div class="notification-list <?= $notification->status ? "notification-list--unread" : "notification-list" ?>" data-id="<?= $notification->id ?>">
                <div class="notification-list_content">
                    <div class="notification-list_detail">
                        <p><b><?= $notification->title ?></b></p>
                        <p class="text-muted"><?= $notification->message ?></p>
                        <p class="text-muted"><small><?= date('H:i d-m-y', strtotime($notification->created_at)) ?></small></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php

    }

    public function fetchNotifications()
    {
    ?>

        <?php $notifications = $this->Notifications_model->get_all_notifications($this->input->get('user_id')) ?>

        <?php foreach ($notifications as $notification) : ?>
            <div class="notification-list <?= $notification->status ? "notification-list--unread" : "notification-list" ?>" data-id="<?= $notification->id ?>">
                <div class="notification-list_content">
                    <div class="notification-list_detail">
                        <p><b><?= $notification->title ?></b></p>
                        <p class="text-muted"><?= $notification->message ?></p>
                        <p class="text-muted"><small><?= date('H:i d-m-y', strtotime($notification->created_at)) ?></small></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
<?php
    }

    public function readNotification($id)
    {
        $user_id = $this->session->userdata('user_id');

        $result = $this->Notifications_model->read($id, $user_id);

        echo json_encode(['success' => $result]);
    }

    public function readNotifications()
    {
        $ids = $this->input->post('ids');
        $user_id = $this->session->userdata('user_id');

        foreach ($ids as $id) {
            $result = $this->Notifications_model->read($id, $user_id);
        }

        echo json_encode(['success' => $result]);
    }
}
