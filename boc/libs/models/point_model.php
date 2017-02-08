<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Point_model extends MY_Model {

	protected $table = 'point';
	public function get_point($code,$language) {
		$query = $this->db
			->select('id,'.$language.'_content')
			->from($this->table)
			->where('title',$code)
			->get();
		if ($this->db->affected_rows()) {
			$result = $query->row_array();
			return strip_tags($result[$language.'_content']);
		} else {
			return false;
		}
	}
}
