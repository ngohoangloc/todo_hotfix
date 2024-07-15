<?php


class Fields_of_type extends CI_Model
{
    function __construct()
    {
        $this->load->database();
    }

    public function get_all()
    {
        $result = $this->db->get_where("fields_of_type", ['deleted_at' => null]);
        return $result->result_object();
    }
    public function get_types()
    {
        $result = $this->db->get("types");
        return $result->result_object();
    }

    public function add($data)
    {
        $result = $this->db->insert('fields_of_type', $data);
        return $result;
    }
    public function get_by_id($id)
    {
        $result = $this->db->get_where('fields_of_type', ['id' => $id])->row_object();
        return $result;
    }
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('fields_of_type', $data);
        return $result;
    }
    public function delete($id)
    {
        $result = $this->db->update('fields_of_type', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        return $result;
    }
}
