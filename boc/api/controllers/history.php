<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

// 浏览历史
class history extends API_Controller {
	protected $rules = array(
		"list_get" => array(
			array(
				"field" => "htype",
				"label" => "浏览类型",
				"rules" => "trim|required|callback_htype_check",
			),
			array(
				"field" => "kw",
				"label" => "搜索关键字",
				"rules" => "trim",
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
			array(
				"field" => "token",
				"label" => "Token",
				"rules" => "trim|required",
			),
		),
		"delete" => array(
			array(
				"field" => "htype",
				"label" => "浏览类型",
				"rules" => "trim|required|callback_htype_check",
			),
			array(
				"field" => "did",
				"label" => "记录ID",
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

		$this->load->model('clickhistory_model', 'mclickhistory');
		$this->load->model('friends_model', 'mfriends');
		$this->load->model('coltypes_model', 'mctypes');
		$this->load->model('district_model', 'mdistrict');

		$this->lisetFields = 't.id, t.aid, t.title, t.ctype, t.province, t.city, t.timeline, t.photo, t.click';
	}


	public function list_befor() {
		$this->vdata['returnCode'] = '200';
		$this->vdata['returnInfo'] = '操作成功';
		$this->vdata['secure'] = JSON_SECURE;
		$this->vdata['content']['htypes'] = array(
		  'demand' => '需求',
		  'resource' => '资源',
		  'circle' => '商圈',
		);
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 需求列表
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
			$where['tclick.aid'] = $this->userinfo['id'];

			// 初始化翻页
			$this->_list();

			// 智能排序
			$this->orderby['timeline'] = 'desc';

			// 拉取数据
			if ($list = $this->mclickhistory->get_list_join(
				$this->limit,
				$this->offset,
				$this->orderby,
				$where,
				$this->lisetFields,
				$this->htype)
			) {
				$this->_filterList($list);
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['list'] = $list;
				$this->vdata['content']['list_count'] = $this->mclickhistory->get_count_all(array('aid'=>$this->userinfo['id'], 'is_del'=>0), $this->htype.'_click');
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['list'] = array();
				$this->vdata['content']['list_count'] = 0;
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 删除
	public function delete() {
		// 验证
		$this->form_validation->set_rules($this->rules['delete']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			if ($effectID = $this->mbaseclick->del(false, array('did'=>$this->click_info['did']))) {
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
				$this->_parseAidInfo($v);
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
			$this->_parseAidInfo($target);
		}
	}

	// 获取需求用户信息
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

	// 获取需求用户信息
	private function _parseIndustry(&$target = false) {
		if (isset($target['ctype'])) {
			if ($info = $this->mctypes->get_one($target['ctype'])) {
				$target['ctype_title'] = $info['title'];
			} else {
				$target['ctype_title'] = "";
			}
		}
	}

	// 获取需求用户信息
	private function _parseComment(&$target = false, $commentLimit = 6, $commentPage = 1, $replyLimit = 6, $replyPage = 1) {
		$commentPage -= 1;
		$offset = $commentPage * $commentLimit;
		$where = array();
		$where['audit'] = 1;
		$where['did'] = $target['id'];
		$comments = $this->mbasecomment->get_list_join_dcp(
			$commentLimit,
			$offset,
			array(),
			$where,
			array(
				'dc.id as id',
				'did',
				'dc.aid as aid',
				'content',
				'like',
				'if(isnull(dcp.status), 0, dcp.status) as has_praise',
				'praise_count',
				'dc.timeline as timeline'
			),
			isset($this->userinfo)?$this->userinfo['id']:0
		);
		// echo $this->db->last_query();
		foreach ($comments as $key => &$comment) {
			$this->_parseTimeline($comment);
			$this->_parseAidInfo($comment, 'aid');
			$this->_parseReply($comment, $replyLimit, $replyPage);
		}
		$target['comments'] = $comments;
		$target['comments_count'] = $this->mbasecomment->get_count_all($where);
	}

	// 获取需求用户信息
	private function _parseReply(&$target = false, $replyLimit = 6, $replyPage = 1) {
		$replyPage -= 1;
		$offset = $replyPage * $replyLimit;
		$where = array();
		$where['audit'] = 1;
		$where['dcid'] = $target['id'];
		$replys = $this->mbasereply->get_list($replyLimit, $offset, array(), $where, 'id, did, dcid, aid, content, timeline');
		foreach ($replys as $key => &$reply) {
			$reply['did_aid'] = $target['aid'];
			$this->_parseTimeline($reply);
			$this->_parseAidInfo($reply, 'aid');
			$this->_parseAidInfo($reply, 'did_aid');
		}
		$target['replys'] = $replys;
		$target['replys_count'] = $this->mbasereply->get_count_all($where);
	}

	// 获取需求用户信息
	private function _parseAidInfo(&$target = false, $field = 'aid') {
		if ($target && isset($target[$field]) && $target[$field]) {
			if (isset($this->aid_info_cache[$target[$field]])) {
				$target[$field . '_info'] = $this->aid_info_cache[$target[$field]];
			} else if ($aidInfo = $this->macc->get_one(array('id' => $target[$field]), 'id, photo, nickname, create_time as timeline, level,endtimeline')) {
				if (isset($this->userinfo) && $this->userinfo && ($friend_info = $this->mfriends->get_one(array('audit'=>1, 'uid'=>$this->userinfo['id'], 'suid'=>$target[$field]), 'id, remarkname'))) {
					$aidInfo['nickname'] = $friend_info['remarkname'];
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

	// 获取需求用户信息
	private function _parseRecommend(&$target = false) {
		if ($recommendInfo = $this->mbaserecommend->get_one(array('did' => $target['id'], 'aid' => $this->userinfo['id']))) {
			if ($recommendInfo['status'] == 1) {
				$target['has_recommend'] = (string) 1;
			} else {
				$target['has_recommend'] = (string) 0;
			}
		} else {
			$target['has_recommend'] = (string) 0;
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


	public function htype_check($str)
	{
		if (!in_array($str, array('demand', 'resource', 'circle'))) {
			$this->form_validation->set_message('htype_check', '浏览类型错误');
			return false;
		} else {
			$this->htype = $str;
			switch ($this->htype) {
				case 'demand':
						$this->load->model('demand_comment_model', 'mbasecomment');
						$this->load->model('demand_reply_model', 'mbasereply');
						$this->load->model('demand_recommend_model', 'mbaserecommend');
						$this->load->model('demand_click_model', 'mbaseclick');
						$this->load->model('demand_comment_praise_model', 'mbasecommentpraise');
					break;
				case 'resource':
						$this->load->model('resource_comment_model', 'mbasecomment');
						$this->load->model('resource_reply_model', 'mbasereply');
						$this->load->model('resource_recommend_model', 'mbaserecommend');
						$this->load->model('resource_click_model', 'mbaseclick');
						$this->load->model('resource_comment_praise_model', 'mbasecommentpraise');
					break;
				case 'circle':
						$this->load->model('circle_comment_model', 'mbasecomment');
						$this->load->model('circle_reply_model', 'mbasereply');
						$this->load->model('circle_recommend_model', 'mbaserecommend');
						$this->load->model('circle_click_model', 'mbaseclick');
						$this->load->model('circle_comment_praise_model', 'mbasecommentpraise');

						$this->lisetFields = 't.id, t.aid, t.content, t.title, t.ctype, t.province, t.city, t.timeline, t.photo, t.click, t.member_count';
					break;
			}
			return true;
		}
	}

	public function delete_did_check($str)
	{
		if ($click_info = $this->mbaseclick->get_one(array('did'=>$str), 'id, aid, did, timeline')) {
			$this->click_info = $click_info;
			return true;
		} else {
			$this->form_validation->set_message('delete_did_check', '浏览历史不存在');
			return false;
		}
	}
}
