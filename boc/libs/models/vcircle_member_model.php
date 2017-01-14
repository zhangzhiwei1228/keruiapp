<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vcircle_member_model extends MY_Model {

	protected $table = 'vcircle_member';
	public function create($data){
        $this->db->insert($this->table, $data); 
        if ($this->db->affected_rows()) {
            return $this->db->insert_id();
        }
        return 0;
    }
}