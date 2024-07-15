<?php

class Notifications_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function get_notification($id, $user_id)
    {
        return $this->db->get_where('notifications', ['id' => $id, 'user_id' => $user_id])->row_object();
    }

    public function get_all_notifications($user_id, $limit = null, $offset = null)
    {

        $this->db->from('notifications');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at','DESC');
        $this->db->order_by('status','ASC');

        if($limit && $offset)
        {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();

        return $query->result_object();
    }

    public function get_unread_notifications($user_id)
    {
        $unread_notifications = $this->db->get_where('notifications', ['user_id' => $user_id, 'status' => true])->result_object();

        return $unread_notifications;
    }

    public function get_read_notifications($user_id)
    {
        $read_notifications = $this->db->get_where('notifications', ['user_id' => $user_id, 'status' => false])->result_object();

        return $read_notifications;
    }

    public function count_unread_notifications($user_id)
    {
        $this->db->from('notifications');
        $this->db->where('user_id', $user_id);
        $this->db->where('status', true);

        $query = $this->db->get();

        return $query->num_rows();
    }

    public function create($data)
    {

        $notification = [
            'title' => $data['title'],
            'message' => $data['message'],
            'user_id' => $data['user_id'],
        ];

        $result = $this->db->insert('notifications', $notification);

        if ($result) {
            $this->load->library('notification');
            $this->notification->sendNotification($notification);
        }

        return $result;
    }

    public function read($id, $user_id)
    {
        $notification = $this->get_notification($id, $user_id);

        if (!empty($notification)) {
            $notification->status = false;

            return $this->update($notification->id, $notification);
        }
        
        return false;
    }

    public function update($id, $notification)
    {
        return $this->db->update('notifications', $notification, ['id' => $id]);
    }
}
