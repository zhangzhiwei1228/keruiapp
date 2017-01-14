<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class platform_model extends MY_Model
{
	protected $table = 'platform';
	// 注册
    public function create($data){
        $data['timeline'] = time();
        $this->db->insert($this->table, $data); 
        if ($this->db->affected_rows()) {
            return $this->db->insert_id();
        }
        return 0;
    }
}