<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class vaccount_model extends MY_Model {
	protected $table = 'account';

	// 个人信息
	public function get_info($where, $fresh = 'nofresh', $terminalNo = 0) {

		$this->db->select('*')->from($this->table);

    if (!is_numeric($where)) {
			$this->db->where($where);
		} else {
			$this->db->where(array('id' => $where));
		}

		$it = $this->db->get()->row_array();

		if (empty($it) && ($it = $this->get_one($where, 'id', 'account'))) {
			$token = $this->gettoken($it['id'], 'fresh', $terminalNo);

			$this->db->select('*')->from($this->table);

      if (!is_numeric($where)) {
  			$this->db->where($where);
  		} else {
  			$this->db->where(array('id' => $where));
  		}

			$it = $this->db->get()->row_array();
		}

		if (!empty($it)) {
			$this->_infoProcessor($fresh, $terminalNo, $it);
			return $it;
		} else {
			return false;
		}
	}

	// 个人信息
	public function getInfoByToken($where, $fresh = 'nofresh', $terminalNo = 0) {

		$query = $this->db
			->select('*')
			->from($this->table)
			->where(array('token' => $where));

		$it = $query->get()->row_array();

		if (!empty($it)) {
			$this->_infoProcessor($fresh, $terminalNo, $it);
			return $it;
		} else {
			return false;
		}
	}

	public function gettoken($accountId, $fresh = 'nofresh', $terminalNo = 0) {
		$token = genToken();
    $terminalNo = 1;
		$token_now = $this->get_one(array('accountId' => $accountId, 'terminalNo' => $terminalNo), '*', 'account_token');

		if ($token_now) {
      if ($fresh === 'nofresh') {
        $token = $token_now['token'];
      } else {
        $this->update(array('token' => $token, 'expiretime'=>time()+(TOKEN_TIME_EXPIRE*24*3600)), array('accountId' => $accountId, 'terminalNo' => $terminalNo), false, 'account_token');
      }
		} else {
      $this->create(array('token' => $token, 'expiretime'=>time()+(TOKEN_TIME_EXPIRE*24*3600), 'accountId' => $accountId, 'terminalNo' => $terminalNo), false, 'account_token');
		}

		return $token;
	}

  /**
   * 最后对输出信息处理
   */
	public function _infoProcessor($fresh, $terminalNo, &$it) {
    if ($fresh) {
	    $token = $this->gettoken($it['id'], $fresh, $terminalNo);
  		$it['token'] = $token;
    }

    // 推荐二维码
    // if (isset($it['rcode']) && $it['rcode']) {
    //   $it['rcode_url'] = site_url('qr/index?d='.$it['rcode']).'&l=Q';
    // } else {
    //   $it['rcode_url'] = '';
    // }

    if (isset($it['photo'])) {
      photo2url($it, 'false', 'false');
    }
	}

  /**
   * @brief 返回第一个值
   * @param $where 数字或者为字符串 数组形式的条件
   * @param $fields string 取字段
   * @return
   */
  public function get_one($where, $fields = "*", $table = false)
  {
      if (!$table) {
          $table = $this->table;
      }

      if (!$where) {
          return false;
      }

      $this->db->select($fields)->from($table);
      if (!is_numeric($where)) {
          $this->db->where($where);
      } else {
          $this->db->where('id', $where);
      }

      $query = $this->db->get();
      $row = $query->row_array();
      return $row;
  }

}
