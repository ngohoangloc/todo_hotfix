<?php
class Ip_model extends CI_Model
{

    public function get_all()
    {
        return $this->db->get('ip_address')->result();
    }

    public function add_ip($data)
    {
        return $this->db->insert('ip_address', $data);
    }


    public function edit_ip($oldIp, $newIp)
    {
        $this->db->where('ip', $oldIp);
        $this->db->update('ip_address', array('ip' => $newIp));
    }


    public function delete_ip($ip)
    {
        $this->db->where('ip', $ip);
        $this->db->delete('ip_address');
    }
}
