<?php

class Types_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_all()
    {
        $query = $this->db->get("types");

        return $query->result_object();
    }
    public function get_where($condition)
    {
        $query = $this->db->get_where("types", $condition);

        return $query->result_object();;
    }
    public function add($data)
    {
        $data_items = [];

        if (array_key_exists('title', $data)) {
            $data_items['title'] = $data['title'];
        }

        if (array_key_exists('slug', $data)) {
            $data_items['slug'] = $data['slug'];
        }

        $data_items['user_id'] = $this->session->userdata('user_id');

        $this->db->insert("types", $data_items);
    }
    public function update($id)
    {
        $title = $this->input->post('title');
        $slug = $this->input->post('slug');
        $this->db->set('title', $title);
        $this->db->set('slug', $slug);
        $this->db->where('id', $id);
        $result = $this->db->update('types');
        return $result;
    }
    public function delete($id = null)
    {
        $this->db->delete('types', ['id' => $id]);
    }
    public function get_type($id = null)
    {
        $query = $this->db->get_where('types', ['id' => $id]);
        return $query->row_array();
    }
}
