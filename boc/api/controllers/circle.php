<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

// 商圈
class circle extends API_Controller {
	protected $rules = array(
		"create" => array(
			array(
				"field" => "title",
				"label" => "标题",
				"rules" => "trim|required",
			),
			array(
				"field" => "province",
				"label" => "省",
				"rules" => "trim|required",
			),
			array(
				"field" => "city",
				"label" => "市",
				"rules" => "trim|required",
			),
			array(
				"field" => "ctype",
				"label" => "行业",
				"rules" => "trim|required",
			),
			array(
				"field" => "tags",
				"label" => "标签",
				"rules" => "trim",
			),
			array(
				"field" => "content",
				"label" => "描述",
				"rules" => "trim",
			),
			array(
				"field" => "photo",
				"label" => "图片",
				"rules" => "trim",
			),
			array(
				"field" => "token",
				"label" => "身份验证",
				"rules" => "trim|required",
			),
		),
		"edit" => array(
			array(
				"field" => "did",
				"label" => "商圈ID",
				"rules" => "trim|required|is_numeric|callback_edit_did_check",
			),
			array(
				"field" => "title",
				"label" => "标题",
				"rules" => "trim|required",
			),
			array(
				"field" => "province",
				"label" => "省",
				"rules" => "trim|required",
			),
			array(
				"field" => "city",
				"label" => "市",
				"rules" => "trim|required",
			),
			array(
				"field" => "ctype",
				"label" => "行业",
				"rules" => "trim|required",
			),
			array(
				"field" => "tags",
				"label" => "标签",
				"rules" => "trim",
			),
			array(
				"field" => "content",
				"label" => "描述",
				"rules" => "trim",
			),
			array(
				"field" => "photo",
				"label" => "图片",
				"rules" => "trim",
			),
			array(
				"field" => "token",
				"label" => "身份验证",
				"rules" => "trim|required",
			),
		),
		"list_get" => array(
			array(
				"field" => "recommend",
				"label" => "Banner",
				"rules" => "trim",
			),
			array(
				"field" => "kw",
				"label" => "搜索关键字",
				"rules" => "trim",
			),
			array(
				"field" => "aid",
				"label" => "用户ID",
				"rules" => "trim|is_numeric",
			),
			array(
				"field" => "province",
				"label" => "省",
				"rules" => "trim|is_numeric",
			),
			array(
				"field" => "city",
				"label" => "市",
				"rules" => "trim|is_numeric",
			),
			array(
				"field" => "order",
				"label" => "排序",
				"rules" => "trim|is_numeric",
			),
			array(
				"field" => "ctype",
				"label" => "行业ID",
				"rules" => "trim|is_numeric",
			),
			array(
				"field" => "page",
				"label" => "页码",
				"rules" => "trim|is_numeric",
			),
			array(
				"field" => "limit",
				"label" => "每页数量",
				"rules" => "trim|is_numeric",
			),
		),
		"info" => array(
			array(
				"field" => "did",
				"label" => "商圈ID",
				"rules" => "trim|is_numeric",
			)
		),
		"delete" => array(
			array(
				"field" => "did",
				"label" => "商圈ID",
				"rules" => "trim|required|is_numeric|callback_delete_did_check",
			),
			array(
				"field" => "token",
				"label" => "身份验证",
				"rules" => "trim|required",
			),
		),
	);

	public function __construct() {
		parent::__construct();

		$this->aid_info_cache = array();

		$this->load->model('circle_model', 'mcircle');
		$this->load->model('circle_comment_model', 'mcirclecomment');
		$this->load->model('circle_reply_model', 'mcirclereply');
		$this->load->model('circle_recommend_model', 'mcirclerecommend');
		$this->load->model('circle_click_model', 'mcircleclick');
		$this->load->model('circle_comment_praise_model', 'mcirclecommentpraise');
		$this->load->model('friends_model', 'mfriends');
		$this->load->model('coltypes_model', 'mctypes');
		$this->load->model('district_model', 'mdistrict');
	}

	public function create_befor() {
		$this->vdata['returnCode'] = '200';
		$this->vdata['returnInfo'] = '操作成功';
		$this->vdata['secure'] = JSON_SECURE;
		// $this->vdata['content']['ctype'] = array(
		//   1 => '我创建的',
		//   2 => '我加入的',
		//   3 => '更多商圈',
		// );
		$this->vdata['content']['ctype_title'] = '行业';
		$this->vdata['content']['ctypes'] = $this->mctypes->get_all(array('name' => 'industry', 'show' => 1), 'id, title');
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 商圈添加
	public function create() {
		// 验证
		$this->form_validation->set_rules($this->rules['create']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
        	if ($info = $this->macc->get_one($this->userinfo['id'])) {
	          if(!empty($info['endtimeline'])&&$info['endtimeline']<time()){
		      	  $this->vdata['returnCode'] = '0011';
		          $this->vdata['returnInfo'] = '会员已经过期';
		          $this->vdata['secure'] = JSON_SECURE;
		      }else if($info['level']==0&&$info['qz']<1){
		      	  $this->vdata['returnCode'] = '0012';
		          $this->vdata['returnInfo'] = '无权发布商圈，可以购买会员后使用！';
		          $this->vdata['secure'] = JSON_SECURE;
		      }else{
		      	$createData = array();
				$createData['audit'] = 1;
				$createData['aid'] = $this->userinfo['id'];
				$createData['title'] = $this->form_validation->set_value('title');
				$createData['province'] = $this->form_validation->set_value('province');
				$createData['city'] = $this->form_validation->set_value('city');
				$createData['ctype'] = $this->form_validation->set_value('ctype');
				$createData['tags'] = $this->form_validation->set_value('tags');
				$createData['content'] = $this->form_validation->set_value('content');
				$createData['photo'] = $this->form_validation->set_value('photo');
				$createData['timeline'] = (string) time();

				if ($insertID = $this->mcircle->create($createData)) {
					$this->macc->update(array('qz'=>$info['qz']-1),array('id'=>$this->userinfo['id']));
					unset($createData['audit']);
					$createData['id'] = (string) $insertID;
					// 创建聊天室
					rygroup_creat(array($createData['aid']),$createData['id'],$createData['title']);
					photo2url($createData, 'false', 'false');
					$this->_parseTimeline($createData);
					$this->vdata['returnCode'] = '200';
					$this->vdata['returnInfo'] = '操作成功';
					$this->vdata['secure'] = JSON_SECURE;
					$this->vdata['content']['circle'] = $createData;
					$this->vdata['content']['res'] = $insertID;
				} else {
					$this->vdata['returnCode'] = '200';
					$this->vdata['returnInfo'] = '操作失败';
					$this->vdata['content']['circle'] = null;
					$this->vdata['content']['res'] = 0;
				}

		      }	          
	        }else{
		      	$this->vdata['returnCode'] = '0011';
		        $this->vdata['returnInfo'] = '操作失败';
		        $this->vdata['secure'] = JSON_SECURE;
		    }			
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 商圈添加
	public function edit() {
		// 验证
		$this->form_validation->set_rules($this->rules['edit']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			$where = array();
			$where['id'] = $this->circle_info['id'];

			$editData = array();
			$editData['aid'] = $this->userinfo['id'];
			$editData['title'] = $this->form_validation->set_value('title');
			$editData['province'] = $this->form_validation->set_value('province');
			$editData['city'] = $this->form_validation->set_value('city');
			$editData['ctype'] = $this->form_validation->set_value('ctype');
			$editData['tags'] = $this->form_validation->set_value('tags');
			$editData['content'] = $this->form_validation->set_value('content');
			$editData['photo'] = $this->form_validation->set_value('photo');

			if ($effects = $this->mcircle->update($editData, $where)) {
				// 修改聊天室
				rygroup_refresh($this->circle_info['id'],$this->circle_info['title']);
				$editData['id'] = $this->circle_info['id'];
				photo2url($editData, 'false', 'false');
				$this->_parseTimeline($editData);
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['circle'] = $editData;
				$this->vdata['content']['res'] = $effects;
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['circle'] = null;
				$this->vdata['content']['res'] = 0;
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	public function list_befor() {
		$this->vdata['returnCode'] = '200';
		$this->vdata['returnInfo'] = '操作成功';
		$this->vdata['secure'] = JSON_SECURE;
		// $this->vdata['content']['ctype'] = array(
		//   1 => '我创建的',
		//   2 => '我加入的',
		//   3 => '更多商圈',
		// );
		$this->vdata['content']['ctype_title'] = '行业';
		$this->vdata['content']['ctypes'] = $this->mctypes->get_all(array('name' => 'industry', 'show' => 1), 'id, title');
		$this->vdata['content']['order_title'] = '智能排序';
		$this->vdata['content']['orders'] = array(
			array(
				'id' => '1',
				'title' => '好评优先',
			),
			array(
				'id' => '2',
				'title' => '关注人数',
			),
			array(
				'id' => '3',
				'title' => '评论最多',
			),
			array(
				'id' => '4',
				'title' => '热度排序',
			),
		);
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 商圈列表
	public function list_get() {
		// 验证
		$this->form_validation->set_rules($this->rules['list_get']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			$where = array();
			$where['audit'] = 1;

			// 用户
			$aid = $this->form_validation->set_value('aid');
			if ($aid) {
				$where['aid'] = $aid;
			}

			// 地区
			$province = $this->form_validation->set_value('province');
			if ($province) {
				$where['province'] = $province;
			}
			$city = $this->form_validation->set_value('city');
			if ($city) {
				$where['city'] = $city;
			}

			// 行业
			$ctype = $this->form_validation->set_value('ctype');
			if ($ctype) {
				$where['ctype'] = $ctype;
			}

			// 搜索关键字
			$kw = $this->form_validation->set_value('kw');
			if ($kw) {
				$where['like title'] = array('title', $kw);
			}

			// 初始化翻页
			$this->_list();

			// 智能排序
			$order = $this->form_validation->set_value('order');
			if ($order) {
				$this->orderby = array();
				switch ($order) {
				case '1':
					$this->orderby['recommend_count'] = 'desc';
					break;
				case '2':
					$this->orderby['click'] = 'desc';
					break;
				case '3':
					$this->orderby['comment_count'] = 'desc';
					break;
				case '4':
					$this->orderby['member_count'] = 'desc';
					break;
				}
			}

			// 拉取数据
			if ($list = $this->mcircle->get_list($this->limit, $this->offset, $this->orderby, $where, 'id, title, content, ctype, province, city, timeline, photo, click, member_count, aid')) {
				$this->_filterList($list);
				foreach ($list as $k => &$v) {
					$this->_parseAidInfo($v);
				}
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['circles'] = $list;
				$this->vdata['content']['circles_count'] = $this->mcircle->get_count_all($where);
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['circles'] = null;
				$this->vdata['content']['circles_count'] = 0;
			}

			$recommend = $this->form_validation->set_value('recommend');
			if ($recommend) {
				$where = array();
				$where['audit'] = 1;
				$where['flag'] = 1;
				$list = $this->mcircle->get_all($where, 'id, title, ctype, province, city, timeline, photo, click');
				if ($list) {
					$this->_filterList($list);
				} else {
					$list = null;
				}
				$this->vdata['content']['circles_flag'] = $list;
				$this->vdata['content']['circles_flag_count'] = $this->mcircle->get_count_all($where);
			} else {
				$this->vdata['content']['circles_flag'] = null;
				$this->vdata['content']['circles_flag_count'] = 0;
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 商圈详情
	public function info() {
		// 验证
		$this->form_validation->set_rules($this->rules['info']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			$where = array();
			$where['audit'] = 1;
			$where['id'] = $this->form_validation->set_value('did');

			$comment_limit = $this->form_validation->set_value('comment_limit');
			$comment_page = $this->form_validation->set_value('comment_page');
			$reply_limit = $this->form_validation->set_value('reply_limit');
			$reply_page = $this->form_validation->set_value('reply_page');

			if ($it = $this->mcircle->get_one($where, 'id, aid, title, province, city, ctype, tags, content, photo, timeline, member_count, click')) {
				$this->mcircle->add_click($it['id']);
				if (isset($this->userinfo) && $this->userinfo) {
					$this->mcircleclick->add_click($it['id'], $this->userinfo['id']);
				}
				$this->_filterList($it, false);
				$this->_parseAidInfo($it);
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['circle'] = $it;
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['circle'] = null;
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 商圈详情
	public function info_short() {
		// 验证
		$this->form_validation->set_rules($this->rules['info']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			$where = array();
			$where['audit'] = 1;
			$where['id'] = $this->form_validation->set_value('did');

			if ($it = $this->mcircle->get_one($where, 'id, title')) {
				$it['icon'] = static_file('api/img/circle.png');
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['circle'] = $it;
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['circle'] = null;
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}


	// 商圈删除
	public function delete() {
		// 验证
		$this->form_validation->set_rules($this->rules['delete']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			// 添加到购物车
			if ($effectID = $this->mcircle->del($this->circle_info['id'], array('aid' => $this->userinfo['id']))) {
				// 删除聊天室
				rygroup_dismiss($this->circle_info['id'],$this->circle_info['title']);
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '删除成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = (string) $effectID;
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = "0";
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	private function _filterList(&$target = false, $is_list = true) {
		if ($is_list && $target) {
			if (array_key_exists('photo', $target['0'])) {
				photo2url($target, 'false');
			}
			foreach ($target as $k => &$v) {
				$this->_parseTimeline($v);
				$this->_parseDistrictIds($v);
				$this->_parseIndustry($v);
				if (isset($this->userinfo) && $this->userinfo) {
					$v['im_in'] = check_circle($v['id'], $this->userinfo['id']);
				} else {
					$v['im_in'] = 0;
				}
			}
		} else {
			if (isset($target['photo'])) {
				photo2url($target, 'false', 'false');
			}
			if (is_null($target['tags'])) {
				$target['tags'] = '';
			}
			if (is_null($target['content'])) {
				$target['content'] = '';
			}
			$this->_parseTimeline($target);
			$this->_parseDistrictIds($target);
			$this->_parseIndustry($target);
			if (isset($this->userinfo) && $this->userinfo) {
				$target['im_in'] = check_circle($target['id'], $this->userinfo['id']);
			} else {
				$target['im_in'] = 0;
			}
		}
	}

	// 获取商圈用户信息
	private function _parseDistrictIds(&$target = false) {
		if (isset($target['province'])) {
			if ($info = $this->mdistrict->get_one($target['province'])) {
				$target['province_title'] = $info['name'] . $info['suffix'];
			} else {
				$target['province_title'] = "";
			}
		}
		if (isset($target['city'])) {
			if ($info = $this->mdistrict->get_one($target['city'])) {
				$target['city_title'] = $info['name'] . $info['suffix'];
			} else {
				$target['city_title'] = "";
			}
		}
		if (isset($target['district'])) {
			if ($info = $this->mdistrict->get_one($target['district'])) {
				$target['district_title'] = $info['name'] . $info['suffix'];
			} else {
				$target['district_title'] = "";
			}
		}
	}

	// 获取商圈用户信息
	private function _parseIndustry(&$target = false) {
		if (isset($target['ctype'])) {
			if ($info = $this->mctypes->get_one($target['ctype'])) {
				$target['ctype_title'] = $info['title'];
			} else {
				$target['ctype_title'] = "";
			}
		}
	}

	// 获取商圈用户信息
	private function _parseAidInfo(&$target = false, $field = 'aid') {
		if ($target && isset($target[$field]) && $target[$field]) {
			if (isset($this->aid_info_cache[$target[$field]])) {
				$target[$field . '_info'] = $this->aid_info_cache[$target[$field]];
			} else if ($aidInfo = $this->macc->get_one(array('id' => $target[$field]), 'id, photo, nickname, create_time as timeline, level, score,endtimeline')) {
				// echo $this->db->last_query();
				if (isset($this->userinfo) && $this->userinfo && ($friend_info = $this->mfriends->get_one(array('audit'=>1, 'uid'=>$this->userinfo['id'], 'suid'=>$target[$field]), 'id, remarkname'))) {
					$aidInfo['nickname'] = (sizeof($friend_info['remarkname'])>0)?$friend_info['remarkname']:$aidInfo['nickname'];
				}
				if(!empty($aidInfo['endtimeline'])&&$aidInfo['endtimeline']<time()){
				      $aidInfo['level']= -1;
				  }
				  if(!empty($aidInfo['endtimeline'])){$aidInfo['endtimeline'] =date("Y-m-d", $aidInfo['endtimeline']);}
				photo2url($aidInfo, 'false', 'false');
				$this->_parseTimeline($aidInfo);
				if ($aidLevel = $this->mctypes->get_one(array('id' => $aidInfo['level'], 'name' => 'level'), 'id, title, identify')) {
					$aidInfo['level_title'] = $aidLevel['title'];
					$aidInfo['level_identify'] = $aidLevel['identify'];
				} else {
					$aidInfo['level_title'] = "普通会员";
					$aidInfo['level_identify'] = "0";
				}
				$target[$field . '_info'] = $aidInfo;
				$this->aid_info_cache[$target[$field]] = $aidInfo;
			} else {
				$target[$field . '_info'] = null;
			}
		}
	}

	// 时间格式化
	private function _parseTimeline(&$target = false) {
		if (isset($target['timeline']) && $target['timeline'] && $target['timeline'] > 0) {
			$target['timeline'] = date("Y-m-d", $target['timeline']);
		} else {
			$target['timeline'] = "";
		}
	}

	////////////////////////////////////////////////////////////////////////////
	///////////////规则验证///////////////////////////////规则验证////////////////
	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////
	///////////////规则验证///////////////////////////////规则验证////////////////
	////////////////////////////////////////////////////////////////////////////

	public function edit_did_check($str) {
		$circle_info = $this->mcircle->get_one(array('id' => $str, 'audit' => 1), 'id, aid, title');
		if ($circle_info) {
			if ($circle_info['aid'] != $this->userinfo['id']) {
				$this->form_validation->set_message('edit_did_check', '该商圈不是你发布的，不能修改！');
				return false;
			} else {
				$this->circle_info = $circle_info;
				return true;
			}
		} else {
			$this->form_validation->set_message('edit_did_check', '该商圈信息不存在！');
			return false;
		}
	}

	// 商圈删除检测
	public function delete_did_check($str) {
		if ($circle_info = $this->mcircle->get_one(array('id' => $str, 'audit' => 1), 'id, aid, title')) {
			if ($circle_info['aid'] != $this->userinfo['id']) {
				$this->form_validation->set_message('delete_did_check', '该商圈不是你发布的，不能删除！');
				return false;
			} else {
				$this->circle_info = $circle_info;
				return true;
			}
		} else {
			$this->form_validation->set_message('delete_did_check', '该商圈不存在，请重新选择');
			return false;
		}
	}

}
