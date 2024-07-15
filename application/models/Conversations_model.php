<?php

class Conversations_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get($id)
    {
        $conversation = $this->db->get_where('conversations', ['id' => $id, 'deleted_at' => null])->row_object();
        return $conversation;
    }

    public function get_by_task($task_id)
    {
        // $this->db->order_by('id', 'desc');
        $this->db->where([
            'item_id' => $task_id,
            // 'parent_id' => 0,
            'deleted_at' => null
        ]);
        $conversations = $this->db->get('conversations')->result_object();

        return $conversations;
    }

    public function get_replies($conversation_id)
    {
        $replies = $this->db->get_where('conversations', ['parent_id' => $conversation_id, 'deleted_at' => null])->result_object();

        return $replies;
    }

    public function create($conversation)
    {
        $result = $this->db->insert('conversations', $conversation);

        if ($result) {
            $item = $this->db->get_where('items', ['id' => $conversation['item_id'], 'deleted_at' => NULL])->row_object();

            $group = $this->db->get_where('items', ['id' => $item->parent_id, 'deleted_at' => NULL])->row_object();

            $meta = $this->db->select('items_meta.*')->from('items_meta')
                ->join('fields', 'fields.key = items_meta.key')
                ->where('fields.deleted_at', NULL)
                ->where('fields.type_html', 'people')
                ->where('items_meta.items_id', $conversation['item_id'])->get()->row_object();

            $task_owners = explode(',', $meta->value);
            $group_owners = explode(',', $group->owners);

            // Kết hợp các mảng và loại bỏ các phần tử null hoặc rỗng
            $receivers = array_filter(array_merge($task_owners, $group_owners), function ($value) {
                return !is_null($value) && $value !== '';
            });

            foreach ($receivers as $receiver) {
                if($receiver != $this->session->userdata('user_id'))
                {
                    $notification = [
                        'title' => $item->title,
                        'message' => 'Có một thông báo mới!',
                        'user_id' => $receiver,
                    ];
    
                    $this->Notifications_model->create($notification);
                }
            }
        }

        return $result;
    }

    public function update($id, $conversation)
    {
        $result = $this->db->update('conversations', $conversation, ['id' => $id]);

        return $result;
    }

    public function delete($id)
    {
        $result = $this->db->update('conversations', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);

        $replies = $this->get_replies($id);

        foreach ($replies as $reply) {
            $this->delete($reply->id);
        }

        return $result;
    }
}
