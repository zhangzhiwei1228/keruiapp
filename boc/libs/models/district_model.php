<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class district_model extends MY_Model {
	protected $table = 'district';


	function __construct()
	{
		parent::__construct();
		$this->trans_ids_cache = array();
	}

	/**
	 * @brief 返回第一个值
	 * @param $where 数字或者为字符串 数组形式的条件
	 * @param $fields string 取字段
	 * @return
	 */
	public function get_one($where,$fields = "*",$table=FALSE){
		if (!$table) {
			$table = $this->table;
		}
		if (!$where) {
			return FALSE;
		}
		$this->db->select($fields)->from($table);
		if (!is_numeric($where)) {
			$this->db->where($where);
		}else{
			$this->db->where('id',$where);
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$row = $query->row_array();

		return $row;
	}

	public function get_tree($parentid = 0, $more='more') {
		$cols = $this->_get_subs($parentid);

		if ($more == 'more') {
			foreach ($cols as $k => $v) {
				if ($v['more'] > 0) {
					$cols[$k]['more'] = $this->get_tree($v['id']);
				} else {
					$cols[$k]['more'] = array();
				}
			}
		}

		if (!empty($cols)) {
			return $cols;
		} else {
			return 0;
		}
	}

  public function transIds($province='', $city='', $district='')
	{
		$ids = array();
		if ($province) {
			array_push($ids, $province);
		}

		if ($city) {
			array_push($ids, $city);
		}

		if ($district) {
			array_push($ids, $district);
		}

		$str = '';

		if (!empty($ids)) {
			$where['in'] = array('id', $ids);
			$orders = array('id'=>'asc');
			$listInfo = parent::get_all($where, 'id, name, suffix', $orders);

			foreach ($listInfo as $k => $v) {
				$str .= $v['name'].$v['suffix'];
			}
		}

		return $str;
	}

	// 获得栏目, todo 修改多级
	protected function _get_subs($parentid = 0)
	{
		$query = $this->db
			->select('f.id, f.id as f_id, f.parentid, f.name, f.initial, f.initials, f.pinyin, f.suffix, (select count(`parentid`) from '.$this->db->dbprefix.$this->table.' where `parentid` = `f_id` ) as more')
			->from($this->table.' as f')
			->where('f.parentid',$parentid)
			->get();
		return $query->result_array();
	}
}
