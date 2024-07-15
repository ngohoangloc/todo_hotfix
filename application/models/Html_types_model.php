<?php


class Html_types_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }
    public function get_all()
    {
        return $this->db->get("html_types")->result_object();
    }
    public function add($data)
    {
        $dataInsert = [];

        if (array_key_exists("title", $data)) {
            $dataInsert["title"] = $data["title"];
        }

        if (array_key_exists("color", $data)) {
            $dataInsert["color"] = $data["color"];
        }

        if (array_key_exists("value", $data)) {
            $dataInsert["value"] = $data["value"];
        }

        return $this->db->insert("html_types", $dataInsert);
    }
    public function update($data, $id)
    {
        $dataUpdate = [];

        if (array_key_exists("title", $data)) {
            $dataUpdate["title"] = $data["title"];
        }

        if (array_key_exists("color", $data)) {
            $dataUpdate["color"] = $data["color"];
        }

        if (array_key_exists("value", $data)) {
            $dataUpdate["value"] = $data["value"];
        }

        return $this->db->update("html_types", $dataUpdate, ["id" => $id]);
    }
    public function view($id)
    {
        $result = $this->db->get_where("html_types", ["id" => $id])->row_array();

        return $result;
    }
    public function delete($id)
    {
        $result = $this->db->delete("html_types", ["id" => $id]);

        return $result;
    }
}
