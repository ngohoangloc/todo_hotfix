<?php
class Config_model extends CI_Model
{

    public function get_all()
    {
        return $this->db->get('config')->result();
    }

    public function add($data)
    {
        $result = $this->db->insert("config", $data);
        $config_id  = $this->db->insert_id();
        if($result){
            return $config_id;
        }
        return false;
    }

    public function update($id, $data)
    {  
        $config_update = $this->db->update("config", ["value" => $data] , ["id" => $id]);
        if($config_update){
            return true;
        }
        return false;
    }

    public function get_by_key($key)
    {
        return $this->db->get_where('config', ['key' => $key])->row_object();
    }
}
