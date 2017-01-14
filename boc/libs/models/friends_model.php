<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Friends_model extends MY_Model {

	protected $table = 'friends';
	public function create($data){
        $this->db->insert($this->table, $data); 
        if ($this->db->affected_rows()) {
            return $this->db->insert_id();
        }
        return 0;
    }
    // 设定
    public function set($id,$arr)
    {
        $this->db->set($arr)
            -> where(array('id'=>$id))
            -> update($this->table);
        return $this->db->affected_rows();
    }
}