<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class account_token_model extends MY_Model {
	protected $table = 'account_token';

	public function get_one($where, $fields = "*", $table = FALSE) {
		if (!$table) {
			$table = $this->table;
		}
		if (!$where) {
			return FALSE;
		}

		$this->db->select($fields)->from($table);
		if (!is_numeric($where)) {
			$this->db->where($where);
		} else {
			$this->db->where('token', $where);
		}

		$query = $this->db->get();
		$row = $query->row_array();
		return $row;
	}
	public function delete($key,$where=false) {
		if (is_numeric($key)) {
			$this->db->where(array('accountId'=>$key));
		}

		if (is_array($key)) {
			$this->db->where_in('accountId',$key);
		}

		if ($where) {
			$this->db->where($where);
		}

		$this->db->delete($this->table);
		return $this->db->affected_rows();
	}
}
