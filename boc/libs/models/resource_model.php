<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class resource_model extends MYSOFT_Model {
  protected $table = 'resource';

	public function add_click($id, $aid=false)
	{
		$this->db->set('click','click+1',FALSE);
		$this->db->where('id',$id);
		$this->db->update($this->table);
    $res = $this->db->affected_rows();

		return $res;
	}
}
