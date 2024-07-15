<?php

class File_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function add($data)
    {

        $data_insert = [
            'title' => $data['title'],
            'type' => $data['type'],
            'item_meta_id' => $data['meta_id'],
            'item_id' => $data['item_id'],
            'path' => $data['path'],
            'content_type' => $data['content_type']
        ];

        $this->db->insert('files', $data_insert);

        $file_id = $this->db->insert_id();

        return $file_id;
    }
    public function update_meta($files_id, $meta_id, $type)
    {
        $old_value = $this->db->get_where("items_meta", ['id' => $meta_id, 'deleted_at' => null])->row_object();

        switch ($type) {
            case "add_file":
                $files_id = array_merge($files_id, explode(',', $old_value->value));
                break;
            case "clear_file":
                $file = $this->db->get_where("files", ['id' => $files_id[0]])->row_object();

                $this->db->delete("files", ['id' => $files_id[0]]);
                // $this->db->update("files", ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $files_id[0]]);

                $old_files_id_value = explode(',', $old_value->value);

                $key_file = array_search($files_id[0], $old_files_id_value);

                unset($old_files_id_value[$key_file]);

                $files_id = $old_files_id_value;

                $file_path = FCPATH . $file->path;

                unlink($file_path);

                break;

            default:
                return false;
        }

        $files_id = implode(",", $files_id);

        return $this->db->update("items_meta", ['value' => empty($files_id) ? null : $files_id], ['id' => $meta_id]);
    }

    public function find_in_set($string_id)
    {
        $this->db->select("*");
        $this->db->from("files");
        $this->db->where("FIND_IN_SET(id, '$string_id')");
        $result = $this->db->get()->result_object();
        return $result;
    }

    public function get_files_by_item_id($id)
    {

        $result = $this->db->get_where("files", ['item_id' => $id, 'deleted_at' => null])->result_object();

        return $result;
    }
    public function get_file($condition = [])
    {

        $result = $this->db->get_where("files", array_merge(['deleted_at' => null], $condition))->row_object();

        return $result;
    }

    public function update($data, $id)
    {
        $data_update = array();

        if (isset($data['desc'])) {

            $data_update['description'] = $data['desc'];
        }

        if (isset($data['title'])) {

            $data_update['title'] = $data['title'];
        }

        $this->db->update("files", $data_update, ['id' => $id]);

        $result = $this->db->get_where("files", ['id' => $id])->row_object();

        return $result;
    }
}
