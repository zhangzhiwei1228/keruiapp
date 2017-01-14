<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class demand_comment_model extends MYSOFT_Model {
	protected $table = 'demand_comment';

	// TODO: 为 list 提供精确字段读取
	/**
	 * 为普通index提供分页的list列表
	 * @param  integer $limit 开始位置
	 * @param  integer $start 取行数
	 * @param  boolean $where 条件
	 * @param  boolean $order 排序
	 * @return array         数组
	 */
	public function get_list_join_dcp($limit = 5, $start = 0, $order = false, $where = false, $fields = "*", $praise_aid=0)
	{
			$table = $this->table;

			$this->db
					->select($fields)
					->from($table.' as dc')
					->join('demand_comment_praise as dcp','dcp.dcid=dc.id and dcp.aid='.$praise_aid,'left');

			// =0 getall
			if ($limit) {
					$this->db->limit($limit, $start);
			}

			if ($order) {
					if (is_array($order)) {
							foreach ($order as $k => $v) {
									$this->db->order_by($k, $v);
							}
					} elseif (is_string($order)) {
							$this->db->order_by($order);
					}
			} else {
					$this->db->order_by('sort_id', 'desc');
			}

			// 假删
			if ($this->softDelte && $this->db->field_exists('dc.is_del', $table)) {
					$this->db->where('dc.is_del', '0');
			}

			if ($where) {
					if (is_string($where)) {
							$where = ' ' . $where . ' ';
					} elseif (is_array($where)) {
							$this->db->where($where);
					}
			}
			$query = $this->db->get();
			return $query->result_array();
	}

	public function add_praise($id)
	{
		$this->db->set('praise_count','praise_count+1',FALSE);
		$this->db->where('id',$id);
		$this->db->update($this->table);

		return $this->db->affected_rows();
	}
}
