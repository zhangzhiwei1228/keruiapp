<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class letter_model extends MY_Model {
    protected $table = 'letter'; 
    public function create($data){
        $data['timeline']=time();
        $this->db->insert($this->table, $data); 
        if ($this->db->affected_rows()) {
            return $this->db->insert_id();
        }
        return 0;
    }
    public function set($id,$arr)
    {
        $this->db->set($arr)
            -> where(array('id'=>$id))
            -> update($this->table);
        return $this->db->affected_rows();
    }
    
}