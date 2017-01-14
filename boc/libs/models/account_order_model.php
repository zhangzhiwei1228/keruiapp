<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_order_model extends MY_Model {

	protected $table = 'account_order';
	public function create($data){
        $this->db->insert($this->table, $data); 
        if ($this->db->affected_rows()) {
            return $this->db->insert_id();
        }
        return 0;
    }
}