<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends MY_Model {

	protected $table = 'product';
	public $data = array();
	/**
	 * @param $pid
	 * @param bool|false $where
	 * @return mixed
	 * 删除所有属于改pid的产品
	 */
	public function delete_pids($pid,$where = false) {
		if (is_numeric($pid)) {
			$this->db->where(array('pid'=>$pid));
		}
		if (is_array($pid)) {
			$this->db->where_in('pid',$pid);
		}
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db
			->select('photo')
			->from($this->table)
			->get();
		$photos = $query->result_array();
		$photo_ids = array();
		if($photos) {
			foreach($photos as $row) {
				if(!$row['photo']) continue;
				$photo_ids[] = $row['photo'];
			}
			unlink_upload($photo_ids);
			//$this->db->where_in('id',$photo_ids)->delete('upload');
		}
		if (is_numeric($pid)) {
			$products = $this->db->select('id')->from($this->table)->where(array('pid'=>$pid))->get()->result_array();
		}
		if (is_array($pid)) {
			$products = $this->db->select('id')->from($this->table)->where_in('pid',$pid)->get()->result_array();
		}
		if ($where) {
			$products = $this->db->select('id')->from($this->table)->where($where)->get()->result_array();
		}
		$ids = array();
		if($products) {
			foreach($products as $id) {
				$ids[] = $id['id'];
			}
			if($ids) {
				if (is_numeric($pid)) {
					$this->db->where(array('pid'=>$pid))->delete($this->table);
				}
				if (is_array($pid)) {
					$this->db->where_in('pid',$pid)->delete($this->table);
				}
				if ($where) {
					$this->db->where($where)->delete($this->table);
				}
				$this->delete_pids($ids);
			}
		}
		return $this->db->affected_rows();
	}

	public function get_childs($id) {
		$query = $this->db
			->select('id,title')
			->from($this->table)
			->where(array('pid'=>$id, 'audit' => 1))
			->get();
		$result = $query->result_array();
		return $result;
	}
	public function get_child_ids($pid) {
		$query = $this->db
			->select('id,pid')
			->from($this->table)
			->where(array('id'=>$pid, 'audit' => 1))
			->get();
		$result = $query->result_array();
		if($result) {
			foreach($result as $key=>$row) {
				if($row['pid']) {
					array_push($this->data,$row['id']);
					array_push($this->data,$row['pid']);
					$this->get_child_ids($row['pid']);
				}
			}
		}
		return array_values(array_unique($this->data));
	}
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
