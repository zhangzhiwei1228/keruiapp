<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class videos_model extends MY_Model {

	protected $table = 'videos';
	public function get_one_next($where, $fields = "*",$language='ZH')
	{
		$this->db->select($fields)->from($this->table);
		if(!is_numeric($where))
		{
			$this->db->where($where);
		} else {
			$this->db->where('id',$where);
		}
		//$this->db->where('audit',1);
		$query = $this->db->get();
		$row = $query->row_array();

		if($row)
		{
			$title = $language == 'ZH' ? 'title' : $language.'_title';
			$perv = $this->db->select('id,'.$title)
				->from($this->table)
				->where(array('cid'=>$row['cid'],'audit'=>1,'sort_id >'=>$row['sort_id']))
				->order_by('sort_id','asc')
				->limit(1)->get()->row_array();

			if($perv)
			{
				$row['prev_id'] = $perv['id'];
				$row['prev_title'] = $perv[$title];
			}

			$next = $this->db->select('id,'.$title)
				->from($this->table)
				->where(array('cid'=>$row['cid'],'audit'=>1,'sort_id <'=>$row['sort_id']))
				->order_by('sort_id','desc')
				->limit(1)->get()->row_array();
			if($next)
			{
				$row['next_id'] = $next['id'];
				$row['next_title'] = $next[$title];
			}
		}

		return $row;
	}
}