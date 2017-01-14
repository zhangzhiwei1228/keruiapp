<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class account_model extends MY_Model {
	protected $table = 'account';

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

      $this->db->order_by('id', 'desc');

      // 假删
      $this->db->where('is_del', '0');

      $query = $this->db->get();
      $row = $query->row_array();
      return $row;
  }


	// 检测manager是否存在
	public function find_manager($manager) {
		$query = $this->db
			->select('id,pwd')
			->from($this->table)
			->where('manager', $manager)
			->get();
		if ($this->db->affected_rows()) {
			$re = $query->row_array();
			return $re;
		} else {
			return false;
		}
	}

	public function find_phone($phone) {
		$query = $this->db
			->select('id,pwd')
			->from($this->table)
			->where('phone', $phone)
			->get();
		if ($this->db->affected_rows()) {
			$re = $query->row_array();
			return $re;
		} else {
			return false;
		}
	}

	public function find_email($email) {
		$query = $this->db
			->select('id,pwd')
			->from($this->table)
			->where('email', $email)
			->get();
		if ($this->db->affected_rows()) {
			$re = $query->row_array();
			return $re;
		} else {
			return false;
		}
	}

	public function find_inviteCode($inviteCode) {
		$query = $this->db
			->select('id')
			->from($this->table)
			->where('inviteCode', $inviteCode)
			->get();
		if ($this->db->affected_rows()) {
			$id = $query->row_array();
			return $id['id'];
		} else {
			return false;
		}
	}

	// 注册
	public function create($data) {
		$data['create_time'] = time();
		$data['modify_time'] = time();
		$data['login_time'] = time();
		$data['status'] = 1;
		$data['login_ip'] = get_ip();
		$data['timeline'] = time();

		$this->db->insert($this->table, $data);
		if ($this->db->affected_rows()) {
			return $this->db->insert_id();
		}
		return 0;
	}

	/**
	 * @brief 返回第一个值
	 * @param $where 数字或者为字符串 数组形式的条件
	 * @param $fields string 取字段
	 * @return
	 */
	public function set_manager($data) {
		if (parent::get_one(array('UserSn' => $data['UserSn']), 'id', 'manager')) {
			parent::update($data, array('UserSn' => $data['UserSn']), false, 'manager');
		} else {
			parent::create($data, false, 'manager');
		}
	}

	/**
	 * @brief 返回第一个值
	 * @param $where 数字或者为字符串 数组形式的条件
	 * @param $fields string 取字段
	 * @return
	 */
	public function getinfo($where, $fields = "*", $fresh = 'nofresh') {
		$table = $this->table;

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
		$it = $query->row_array();

		// Token
		if (!empty($it)) {
			photo2url($it, 'false', 'false');
			$token = $this->gettoken($it['id'], $fresh);
			$it['token'] = $token;
		} else {
			return false;
		}

		return $it;
	}

	public function gettoken($accountId, $fresh = 'nofresh') {
		$token = genToken();

		if (!isset($this->maccount_token)) {
			$this->load->model('manager_token_model', 'maccount_token');
		}

		$token_now = $this->maccount_token->get_one(array('accountId' => $accountId));

		if (!empty($token_now)) {
			if ($fresh === 'nofresh') {
				$token = $token_now['token'];
			} else {
				$this->maccount_token->update(array('token' => $token), array('accountId' => $accountId));
			}
		} else {
			$this->maccount_token->create(array('token' => $token, 'accountId' => $accountId));
		}

		return $token;
	}

	// 未使用
	public function getrcode($manager_id) {

		if (!isset($this->maccount_token)) {
			$this->load->model('manager_token_model', 'maccount_token');
		}

		$user_info = parent::get_one(array('id' => $manager_id));

		if ($user_info && $user_info['rcode']) {
			return $user_info['rcode'];
		} else {
			$rcode = genToken();
			$insert_id = parent::update(array('rcode' => $rcode), array('id' => $manager_id));

			if ($insert_id) {
				return $token;
			} else {
				return $insert_id;
			}
		}

	}

	// 获取登录者身份
	public function getposition($position) {
		$it = parent::get_one(array('id' => $position), 'id, title, level', 'manager_group');
		return $it;
	}

	// 通过用户Id获取登录者身份
	public function getpositionByAccountId($accountId) {
		$acc_info = parent::get_one(array('id' => $accountId), 'id, position, nickname');
		// $it = parent::get_one(array('id'=>$acc_info['position']), 'title, level', 'manager_group');
		$it = $this->getposition($acc_info['position']);
		return $it;
	}

	// 获取授权身份列表
	public function getpositionlist($info, $identityType) {
		$where = array('level > ' => $info['position_info']['level']);
		$where['identityType'] = $identityType;
		$list = parent::get_all($where, 'id, title, level', false, 'manager_group');
		return $list;
	}

	// 设定
	public function set($id, $arr) {
		$this->db->set($arr)
			->where(array('id' => $id))
			->update($this->table);
		return $this->db->affected_rows();
	}

	// 登录成功后保存登录信息
	public function setlogin($id) {
		// 获取上次信息
		$this->db->set('login_ip', get_ip());
		$this->db->set('login_time', time());
		$this->db->set('pwd_errors', 0);
		$this->db->where('id', $id);
		$this->db->update($this->table);
		return $this->db->affected_rows();
	}

	// 设置登录密码
	public function set_pwd($aid, $pwd) {
		$this->db->set('pwd', $pwd);
		$this->db->where('id', $aid);
		$this->db->update($this->table);
		return $this->db->affected_rows();
	}

	// 设置支付密码
	public function set_pwd_pay($aid, $pwd) {
		$this->db->set('tradepwd', $pwd);
		$this->db->where('id', $aid);
		$this->db->update($this->table);
		return $this->db->affected_rows();
	}

  // 今日收入[商户]
  public function get_my_earning($aid)
  {
    $where = array(
      'aid' => $aid,
      'like daytime' => array(
        'daytime', date('Y-m-d')
      )
    );
		$query = $this->db
			->select('sum(income) as earning')
			->from('vachievement')
			->where($where)
			->get();

		if ($this->db->affected_rows()) {
			$res = $query->row_array();
			return $res['earning']?$res['earning']:'0';
		} else {
			return '0';
		}
  }

  // 完成订单[商户]
  public function get_order_count($aid)
  {
    $where = array(
      'aid' => $aid,
      'like daytime' => array(
        'daytime', date('Y-m-d')
      )
    );
		$query = $this->db
			->select('*')
			->from('vachievement')
			->where($where)
			->get();

		if ($this->db->affected_rows()) {
			$res = $query->row_array();
			return $res['count']?$res['count']:'0';
		} else {
			return '0';
		}
  }

}
