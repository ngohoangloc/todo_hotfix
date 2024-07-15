<?php 
class Role_model extends CI_Model {

    public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}
    public function get_all() {
        return $this->db->get_where("roles", ['deleted_at' => null])->result();
    }

	public function get_role($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('roles');
        return $query->result();
    }

    public function insert($table, $data)
	{
		$result = $this->db->insert($table, $data);
		if ($result) {
			$log = [
                'type' => 'POST',
                'table' => $table,
                'table_id' => $this->db->insert_id,
                'message' => 'success',
                'value_old' => null,
                'value_new' => $data['role_name'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
                'user_id' => $this->session->userdata('user_id'),
				'ip' => $this->session->userdata('ip'),
            ];
            $this->logs_model->create($log);
		}
		return $result;
	}

	public function get_role_by_id($role_id)
	{
		$this->db->where('id', $role_id);
		$query = $this->db->get('roles');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	public function delete_role($id){
		$role =  $this->get_role_by_id($id);
		$this->db->where('id' , $id);
		$this->db->update('roles', ['deleted_at' => date('Y-m-d H:i:s')] ,  ['id' => $id]);

		$log = [
			'type' => 'DELETE',
			'table' => 'roles',
			'table_id' => $role->id,
			'message' => 'success',
			'value_old' => $role->role_name,
			'value_new' => null,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at'=> date('Y-m-d H:i:s'),
			'user_id' => $this->session->userdata('user_id'),
			'ip' => $this->session->userdata('ip'),
		];
		$this->logs_model->create($log);

        return $this->db->affected_rows() > 0;
	}

	public function delete_permissions($role_id) {
		$this->db->where('role_id', $role_id);
		$this->db->delete('roles_permissions');

		return $this->db->affected_rows();
	  }

}