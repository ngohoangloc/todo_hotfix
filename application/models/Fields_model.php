<?php

class Fields_model extends CI_Model
{
    function __construct()
    {
        $this->load->database();
    }
    public function add($data, $type)
    {
        $this->db->trans_start();

        $data['key'] = time();

        $result = $this->db->insert('fields', $data);

        $field_id = $this->db->insert_id();

        if ($result) {
            $log = [
                'type' => 'POST',
                'table' => 'fields',
                'table_id' => $field_id,
                'message' => 'success',
                'value_old' => null,
                'value_new' => $data['title'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => $this->session->userdata('user_id'),
                'ip' => $this->session->userdata('ip'),
            ];
            $this->logs_model->create($log);
        }

        $items_id = $data['items_id'];

        switch ($type) {
            case 'customtable':

                $tasks = $this->db->get_where("items", ['parent_id' => $items_id, 'deleted_at' => null])->result_object();

                if (count($tasks) > 0) {
                    foreach ($tasks as $key => $task) {
                        $value = "";

                        switch ($data['type_html']) {
                            case "status":
                                $value = "chuabatdau|secondary";
                                break;
                            default:
                                break;
                        }

                        $this->add_meta($task->id, $data['key'], $value);
                    }
                }

                break;

            case 'table':
                $groups = $this->db->get_where("items", ['parent_id' => $items_id, 'deleted_at' => null])->result_object();

                if (count($groups) > 0) {
                    foreach ($groups as $group) {

                        $tasks = $this->db->get_where("items", ['parent_id' => $group->id, 'deleted_at' => null])->result_object();

                        if (count($tasks) > 0) {
                            foreach ($tasks as $key => $task) {
                                $value = "";

                                switch ($data['type_html']) {
                                    case "status":
                                        $value = "chuabatdau|secondary";
                                        break;
                                    default:
                                        break;
                                }

                                $this->add_meta($task->id, $data['key'], $value);

                                $subtasks = $this->db->get_where("items", ['parent_id' => $task->id, 'deleted_at' => null])->result_object();

                                if (count($subtasks) > 0) {
                                    foreach ($subtasks as $key => $subtask) {
                                        $this->add_meta($subtask->id, $data['key'], $value);
                                    }
                                }
                            }
                        }
                    }
                }
                break;

            default:
                break;
        }


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            return $field_id;
        } else {

            $this->db->trans_commit();

            return $field_id;
        }
    }

    public function update($data, $id)
    {
        $old_data = $this->db->get_where('fields', ['id' => $id, 'deleted_at' => null])->row_array();

        $result = $this->db->update('fields', $data, ['id' => $id]);

        if ($result) {
            $new_data = $this->db->get_where('fields', ['id' => $id, 'deleted_at' => null])->row_array();
            if ($new_data) {
                foreach ($old_data as $key => $value) {
                    if (!in_array($key, ['created_at', 'updated_at', 'deleted_at'])) {
                        if ($new_data[$key] != $value) {
                            $log = [
                                'type' => 'PUT',
                                'table' => 'fields',
                                'table_id' => $id,
                                'message' => 'success',
                                'value_old' => $value,
                                'value_new' => $new_data[$key],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'user_id' => $this->session->userdata('user_id'),
                                'ip' => $this->session->userdata('ip'),
                            ];
                            $this->logs_model->create($log);
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function update_all($data, $items_id)
    {
        $result = $this->db->update('fields', $data, ['items_id' => $items_id]);
        return $result;
    }

    public function get_all()
    {
        $query = $this->db->get_where('fields', ['deleted_at' => null]);
        return $query->result_array();
    }
    public function get_by_id($id)
    {
        $query = $this->db->get_where('fields', ['id' => $id, 'deleted_at' => null]);
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    public function delete($id)
    {
        $field = $this->db->get_where("fields", ['id' => $id, 'deleted_at' => null])->row_object();

        $result = $this->db->update("fields", ['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => $this->session->userdata('user_id')], ['id' => $id]);

        if ($result) {
            $log = [
                'type' => 'DELETE',
                'table' => 'fields',
                'table_id' => $id,
                'message' => 'success',
                'value_old' => $field->title,
                'value_new' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => $this->session->userdata('user_id'),
                'ip' => $this->session->userdata('ip'),
            ];
            $this->logs_model->create($log);

            $items_meta = $this->db->get_where("items_meta", ['key' => $field->key, 'deleted_at' => null])->result_object();

            foreach ($items_meta as $key => $item_meta) {
                $this->db->update("items_meta", ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $item_meta->id]);
            }
        }

        return $result;
    }
    // Metas

    public function add_meta($items_id, $key, $value)
    {
        $data = [
            'items_id' => $items_id,
            'key' => $key,
            'value' => $value
        ];
        return $this->db->insert("items_meta", $data);
    }

    public function get_fields_and_meta_by_task($item_id)
    {

        $query = $this->db->get_where('items_meta', ['items_id', $item_id, 'deleted_at' => null]);
        return $query->result_object();
    }

    public function restore($field_id)
    {
        $field = $this->db->get_where('fields', ['id' => $field_id])->row_object();

        if (!empty($field)) {
            $data = [
                'deleted_at' => null,
                'deleted_by' => null,
            ];

            $items_meta_by_key = $this->Items_model->get_meta_by_key_include_deleted($field->key);

            foreach ($items_meta_by_key as $item_meta) {
                $this->Items_model->restore_meta($item_meta->id);
            }

            return $this->db->update('fields', $data, ['id' => $field_id]);
        }

        return false;
    }
    public function get_meta_by_key_distint($key, $type_html)
    {
        $query = $this->db->select('value')
            ->distinct('value')
            ->from('items_meta')
            ->where(["key" => $key])
            ->get()
            ->result_object();

        // Handle custom value by type html
        $data_arr = [];

        $users_arr = [];

        foreach ($query as $key => $meta) {
            switch ($type_html) {
                case "people":
                    $array_id = explode(",", $meta->value);

                    foreach ($array_id as $key => $user_id) {
                        if (!in_array($user_id, $users_arr)) {
                            if (!empty($user_id) || trim($user_id) != '') {
                                $users_arr[] = $user_id;
                            }
                        }
                    }
                    break;
                case "date":
                    if (!empty($meta->value) || trim($meta->value) != '') {
                        $meta->value = implode("-", array_reverse(explode("-", $meta->value)));
                    }
                    break;
                case "percent":
                    if (!empty($meta->value) || trim($meta->value) != '') {
                        $meta->value = $meta->value . "%";
                    }
                    break;
                default:
                    break;
            }

            if (!empty($meta->value) && $type_html != 'people') {
                $data_arr[] = (object)['value' => $meta->value];
            }
        }

        if ($type_html == 'people' && count($users_arr) > 0) {
            foreach ($users_arr as $uid) {
                $user = $this->User_model->get_user_by_id($uid);
                $meta->value = $user->firstname . " " . $user->lastname;
                $data_arr[] = (object)['value' => $meta->value, 'user_id' => $user->id];
            }
        }

        return $data_arr;
    }
}
