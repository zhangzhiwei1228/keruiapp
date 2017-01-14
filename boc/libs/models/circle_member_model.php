<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Circle_member_model extends MY_Model {

	protected $table = 'circle_member';
	public function create($data){
        $this->db->insert($this->table, $data); 
        if ($this->db->affected_rows()) {
            return $this->db->insert_id();
        }
        return 0;
    }
}