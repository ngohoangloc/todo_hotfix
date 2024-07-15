<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Conversation extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');

        $this->load->model('items_model');
        $this->load->model('conversations_model');

        $this->load->library('word_filter');

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }

    //Get all conversations of task
    public function fetch_conversations_by_task()
    {
        $item_id = $this->input->get('item_id');
        $user_id = $this->input->get('user_id');

        $conversations = $this->conversations_model->get_by_task($item_id);

        $html = '';
        $currentUser = '';

        foreach ($conversations as $conversation) {
            // Kiểm tra tin nhắn hiện tại có cùng người gửi với tin nhắn trước đó không
            if ($currentUser != $conversation->user_id) {

                // Gán lại giá trị cho người gửi hiện tại
                $currentUser = $conversation->user_id;

                // Kiểm tra người gửi có phải là tài khoản đang đăng nhập hay không
                if ($currentUser == $user_id) {
                    $data = [
                        'chat' => $conversation,
                        'user' => $this->User_model->get_user_by_id($conversation->user_id),
                    ];
                    $html .= $this->load->view('admin/views/components/conversation/sent-chat', $data, true);
                }
                // Nếu người gửi là người khác
                else {
                    $data = [
                        'chat' => $conversation,
                        'user' => $this->User_model->get_user_by_id($conversation->user_id),
                        'flag' => true, // Đoạn chat mới
                    ];
                    $html .= $this->load->view('admin/views/components/conversation/received-chat', $data, true);
                }
            }
            // Nếu tin nhắn hiện tại cùng người gửi với tin nhắn trước đó
            else {
                // Kiểm tra người gửi có phải là tài khoản đang đăng nhập hay không
                if ($currentUser == $user_id) {
                    $data = [
                        'chat' => $conversation,
                        'user' => $this->User_model->get_user_by_id($conversation->user_id),
                    ];
                    $html .= $this->load->view('admin/views/components/conversation/sent-chat', $data, true);
                }
                // Nếu người gửi là người khác
                else {
                    $data = [
                        'chat' => $conversation,
                        'user' => $this->User_model->get_user_by_id($conversation->user_id),
                        'flag' => false, // Đoạn chat mới
                    ];
                    $html .= $this->load->view('admin/views/components/conversation/received-chat', $data, true);
                }
            }
        }

        echo json_encode(array('success' => true, 'data' => $html));
    }

    //Get all replies of conversation
    public function fetch_replies_by_conversation()
    {
        $conversation_id = $this->input->get('conversation_id');
        $replies = $this->conversations_model->get_replies($conversation_id);

        $html = '';

        if (!empty($replies)) {
            foreach ($replies as $reply) {
                $data = [
                    'reply' => $reply,
                    'user' => $this->User_model->get_user_by_id($reply->user_id),
                ];

                $html .= $this->load->view('admin/views/components/conversation/reply', $data, true);
            }
        }

        echo json_encode(array('success' => true, 'data' => $html));
    }

    //Create a new conversation or reply
    public function create()
    {
        if ($this->input->post('contents') != null) {
            $conversation = [
                'item_id' => $this->input->post('item_id'),
                'user_id' => $this->input->post('user_id'),
                'parent_id' => $this->input->post('parent_id'),
                'contents' => $this->word_filter->filter($this->input->post('contents')),
            ];

            $result = $this->conversations_model->create($conversation);

            if ($result) {
                $this->db->limit(1);
                $this->db->order_by('id', 'desc');

                $query = $this->db->get('conversations');
                $conversation = $query->row_object();



                if ($conversation->parent_id == 0) {
                    $data = [
                        'chat' => $conversation,
                        'user' => $this->User_model->get_user_by_id($conversation->user_id),
                    ];
                    $html = $this->load->view('admin/views/components/conversation/sent-chat', $data, true);
                } else {
                    $data = [
                        'chat' => $conversation,
                        'user' => $this->User_model->get_user_by_id($conversation->user_id),
                    ];

                    $html = $this->load->view('admin/views/components/conversation/sent-chat', $data, true);
                }

                echo json_encode(['success' => $result, 'data' => $html]);
                die();
            }

            echo json_encode(['success' => $result]);
            die();
        }

        echo json_encode(['success' => false]);
        die();
    }


    public function like()
    {

        $conversation_id = $this->input->post('conversation_id');
        $user_id = $this->input->post('user_id');

        $conversation = $this->conversations_model->get($conversation_id);

        if ($conversation->liked_users_id == null) {
            $conversation->liked_users_id = $user_id;
        } else {

            $liked_users_id = explode(',', $conversation->liked_users_id);

            $flag = true;

            foreach ($liked_users_id as $user) {
                if ($user == $user_id) {

                    $flag = false;
                }
            }

            if ($flag) {

                $liked_users_id[] = $user_id;
            }

            $conversation->liked_users_id = implode(',', $liked_users_id);
        }

        $result = $this->conversations_model->update($conversation->id, $conversation);

        if ($result) {

            $totalLike = $conversation->liked_users_id == null ? 0 : count(explode(',', $conversation->liked_users_id));

            echo json_encode(array('success' => $result, 'data' => $totalLike));
            die();
        }

        echo json_encode(['success' => $result]);
        die();
    }

    public function unlike()
    {
        $conversation_id = $this->input->post('conversation_id');
        $user_id = $this->input->post('user_id');

        $conversation = $this->conversations_model->get($conversation_id);


        $liked_users_id = explode(',', $conversation->liked_users_id);

        foreach ($liked_users_id as $key => $_user_id) {
            if ($_user_id == $user_id) {
                unset($liked_users_id[$key]);
            }
        }

        $conversation->liked_users_id = implode(',', $liked_users_id);

        $result = $this->conversations_model->update($conversation->id, $conversation);

        if ($result) {
            $totalLike = $conversation->liked_users_id == null ? 0 : count(explode(',', $conversation->liked_users_id));

            echo json_encode(array('success' => $result, 'data' => $totalLike));
            die();
        }

        echo json_encode(['success' => $result]);
        die();
    }

    public function countLike($conversation_id)
    {
        $conversation = $this->conversations_model->get($conversation_id);

        $liked_users_id = explode(',', $conversation->liked_users_id);

        $total = count($liked_users_id);

        return $total;
    }

    public function update($conversation_id)
    {
        $data = $this->input->post();

        $update_conversation = [];

        if (array_key_exists('contents', $data)) {
            $update_conversation['contents'] = $this->word_filter->filter($data['contents']);
        }
        if (array_key_exists('liked_users_id', $data)) {
            $update_conversation['liked_users_id'] = $data['liked_users_id'];
        }
        if (array_key_exists('deleted_at', $data)) {
            $update_conversation['deleted_at'] = $data['deleted_at'];
        }

        $result = $this->conversations_model->update($conversation_id, $update_conversation);

        echo json_encode(['success' => $result]);
    }

    public function delete()
    {
        $conversation_id = $this->input->post('conversation_id');

        $result = $this->conversations_model->delete($conversation_id);

        echo json_encode(['success' => $result]);
    }
}
