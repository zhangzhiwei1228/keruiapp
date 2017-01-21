<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends MY_Model {

	protected $table = 'product';

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
}
