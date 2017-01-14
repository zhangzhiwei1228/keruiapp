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
}
