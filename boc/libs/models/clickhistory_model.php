<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class clickhistory_model extends MYSOFT_Model {

  public function __construct($table='')
  {
      parent::__construct();
      $this->softDelte = true;
  }

  /**
	 * @brief 为分页提供 筛选数据的数量
	 * @param $where = false 条件
	 * @param $table = false 非默认表
	 * @return int 符合数量
	 */
	public function get_count_all($where=FALSE,$table=FALSE){
    if (!$table) {
      return 0;
    }

    $this->db->where('is_del', '0');

		$count = 0;
		if ($where === FALSE) {
			$count  = $this->db->count_all_results($table);
		}else{
			$count  = $this->db->where($where)->count_all_results($table);
		}
		return $count;
	}

	// TODO: 为 list 提供精确字段读取
	/**
	 * 为普通index提供分页的list列表
	 * @param  integer $limit 开始位置
	 * @param  integer $start 取行数
	 * @param  boolean $where 条件
	 * @param  boolean $order 排序
	 * @return array         数组
	 */
   // TODO: 为 list 提供精确字段读取
 	/**
 	 * 为普通index提供分页的list列表
 	 * @param  integer $limit 开始位置
 	 * @param  integer $start 取行数
 	 * @param  boolean $where 条件
 	 * @param  boolean $order 排序
 	 * @return array         数组
 	 */
 	public function get_list_join($limit = 5, $start = 0, $order = false, $where = false, $fields = "*", $table=false)
 	{
    if (!$table) {
      return null;
    }

		$this->db
			->select($fields)
			->from($table.' as t')
			->join($table.'_click as tclick','tclick.did=t.id','left');

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
		if ($this->softDelte) {
			$this->db->where('tclick.is_del', '0');
			$this->db->where('t.is_del', '0');
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
}
