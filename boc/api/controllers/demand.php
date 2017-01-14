<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

// 需求
class demand extends API_Controller {
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
				"label" => "需求ID",
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
				"label" => "需求ID",
				"rules" => "trim|is_numeric",
			),
			array(
				"field" => "comment_limit",
				"label" => "评论每页数",
				"rules" => "trim|required|is_numeric",
			),
			array(
				"field" => "comment_page",
				"label" => "评论页码",
				"rules" => "trim|required|is_numeric",
			),
			array(
				"field" => "reply_limit",
				"label" => "回复每页数",
				"rules" => "trim|required|is_numeric",
			),
			array(
				"field" => "reply_page",
				"label" => "回复页码",
				"rules" => "trim|required|is_numeric",
			),
		),
		"comment" => array(
			array(
				"field" => "did",
				"label" => "需求ID",
				"rules" => "trim|required|is_numeric|callback_comment_did_check",
			),
			array(
				"field" => "content",
				"label" => "内容",
				"rules" => "trim|required",
			),
			array(
				"field" => "token",
				"label" => "身份验证",
				"rules" => "trim|required",
			),
		),
		"comment_list" => array(
			array(
				"field" => "did",
				"label" => "需求ID",
				"rules" => "trim|is_numeric",
			),
			array(
				"field" => "comment_limit",
				"label" => "评论每页数",
				"rules" => "trim|required|is_numeric",
			),
			array(
				"field" => "comment_page",
				"label" => "评论页码",
				"rules" => "trim|required|is_numeric",
			),
			array(
				"field" => "reply_limit",
				"label" => "回复每页数",
				"rules" => "trim|required|is_numeric",
			),
			array(
				"field" => "reply_page",
				"label" => "回复页码",
				"rules" => "trim|required|is_numeric",
			),
		),
		"comment_praise" => array(
			array(
				"field" => "did",
				"label" => "需求ID",
				"rules" => "trim|is_numeric",
			),
			array(
				"field" => "dcid",
				"label" => "评论ID",
				"rules" => "trim|required|is_numeric|callback_comment_praise_dcid_check",
			),
			array(
				"field" => "token",
				"label" => "身份验证",
				"rules" => "trim|required",
			),
		),
		"reply" => array(
			array(
				"field" => "dcid",
				"label" => "评论ID",
				"rules" => "trim|required|is_numeric|callback_reply_dcid_check",
			),
			array(
				"field" => "content",
				"label" => "内容",
				"rules" => "trim|required",
			),
			array(
				"field" => "token",
				"label" => "身份验证",
				"rules" => "trim|required",
			),
		),
		"reply_list" => array(
			array(
				"field" => "dcid",
				"label" => "评论ID",
				"rules" => "trim|required|is_numeric",
			),
			array(
				"field" => "reply_limit",
				"label" => "回复每页数",
				"rules" => "trim|required|is_numeric",
			),
			array(
				"field" => "reply_page",
				"label" => "回复页码",
				"rules" => "trim|required|is_numeric",
			),
		),
		"recommend_toggle" => array(
			array(
				"field" => "did",
				"label" => "需求ID",
				"rules" => "trim|required|is_numeric|callback_recommend_toggle_did_check",
			),
			array(
				"field" => "token",
				"label" => "身份验证",
				"rules" => "trim|required",
			),
		),
		"comment_praise_toggle" => array(
			array(
				"field" => "dcid",
				"label" => "评论ID",
				"rules" => "trim|required|is_numeric|callback_comment_praise_toggle_dcid_check",
			),
			array(
				"field" => "token",
				"label" => "身份验证",
				"rules" => "trim|required",
			),
		),
		"delete" => array(
			array(
				"field" => "did",
				"label" => "需求ID",
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

		$this->load->model('demand_model', 'mdemand');
		$this->load->model('demand_comment_model', 'mdemandcomment');
		$this->load->model('demand_reply_model', 'mdemandreply');
		$this->load->model('demand_recommend_model', 'mdemandrecommend');
		$this->load->model('demand_click_model', 'mdemandclick');
		$this->load->model('demand_comment_praise_model', 'mdemandcommentpraise');
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
		//   3 => '更多需求',
		// );
		$this->vdata['content']['ctype_title'] = '行业';
		$this->vdata['content']['ctypes'] = $this->mctypes->get_all(array('name' => 'industry', 'show' => 1), 'id, title');
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 需求添加
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
		      }else if($info['level']==0&&$info['xq']<1){
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

				if ($insertID = $this->mdemand->create($createData)) {
					$this->macc->update(array('xq'=>$info['xq']-1),array('id'=>$this->userinfo['id']));
					unset($createData['audit']);
					$createData['id'] = (string) $insertID;
					photo2url($createData, 'false', 'false');
					$this->_parseTimeline($createData);
					$this->vdata['returnCode'] = '200';
					$this->vdata['returnInfo'] = '操作成功';
					$this->vdata['secure'] = JSON_SECURE;
					$this->vdata['content']['demand'] = $createData;
					$this->vdata['content']['res'] = $insertID;
				} else {
					$this->vdata['returnCode'] = '200';
					$this->vdata['returnInfo'] = '操作失败';
					$this->vdata['content']['demand'] = array();
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

	// 需求添加
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
			$where['id'] = $this->demand['id'];

			$editData = array();
			$editData['aid'] = $this->userinfo['id'];
			$editData['title'] = $this->form_validation->set_value('title');
			$editData['province'] = $this->form_validation->set_value('province');
			$editData['city'] = $this->form_validation->set_value('city');
			$editData['ctype'] = $this->form_validation->set_value('ctype');
			$editData['tags'] = $this->form_validation->set_value('tags');
			$editData['content'] = $this->form_validation->set_value('content');
			$editData['photo'] = $this->form_validation->set_value('photo');

			if ($effects = $this->mdemand->update($editData, $where)) {
				$editData['id'] = $this->demand['id'];
				photo2url($editData, 'false', 'false');
				$this->_parseTimeline($editData);
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['demand'] = $editData;
				$this->vdata['content']['res'] = $effects;
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['demand'] = array();
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
		//   3 => '更多需求',
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
			$this->orderby = array();
			if ($order) {
				switch ($order) {
				case 1:
					$this->orderby['recommend_count'] = 'desc';
					break;
				case 2:
					$this->orderby['click'] = 'desc';
					break;
				case 3:
					$this->orderby['comment_count'] = 'desc';
					break;
				}
			}

			// 拉取数据
			if ($list = $this->mdemand->get_list($this->limit, $this->offset, $this->orderby, $where, 'id, title, ctype, province, city, timeline, photo, click,recommend_count,tags')) {
				// echo $this->db->last_query();
				$this->_filterList($list);
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['demands'] = $list;
				$this->vdata['content']['demands_count'] = $this->mdemand->get_count_all($where);
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['demands'] = array();
				$this->vdata['content']['demands_count'] = 0;
			}

			$recommend = $this->form_validation->set_value('recommend');
			if ($recommend) {
				$where = array();
				$where['audit'] = 1;
				$where['flag'] = 1;
				$list = $this->mdemand->get_all($where, 'id, title, ctype, province, city, timeline, photo, click ,recommend_count,tags');
				if ($list) {
					$this->_filterList($list);
				} else {
					$list = null;
				}
				$this->vdata['content']['demands_flag'] = $list;
				$this->vdata['content']['demands_flag_count'] = $this->mdemand->get_count_all($where);
			} else {
				$this->vdata['content']['demands_flag'] = null;
				$this->vdata['content']['demands_flag_count'] = 0;
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 需求详情
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

			if ($it = $this->mdemand->get_one($where, 'id, aid, title, province, city, ctype, tags, content, photo, timeline, recommend_count , click')) {
				$this->mdemand->add_click($it['id']);
				if (isset($this->userinfo) && $this->userinfo) {
					$this->mdemandclick->add_click($it['id'], $this->userinfo['id']);
				}
				$this->_filterList($it, false);
				$this->_parseRecommend($it);
				$this->_parseAidInfo($it);
				$this->_parseComment($it, $comment_limit, $comment_page, $reply_limit, $reply_page);
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['demand'] = $it;
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['demand'] = array();
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 评论
	public function comment() {
		// 验证
		$this->form_validation->set_rules($this->rules['comment']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			$commentData = array();
			$commentData['aid'] = $this->userinfo['id'];
			$commentData['did'] = $this->form_validation->set_value('did');
			$commentData['content'] = $this->form_validation->set_value('content');
			$commentData['like'] = (string) 0;
			$commentData['praise_count'] = (string) 0;
			$commentData['timeline'] = (string) time();

			if ($insertID = $this->mdemandcomment->create($commentData)) {
				$commentData['id'] = (string) $insertID;
				$this->_parseAidInfo($commentData);
				$this->_parseTimeline($commentData);
				l_msg_send("评论了你",9,$commentData['aid'],$this->demand['aid'],3,0,$commentData['did']);
				// 更新总评论数
				$comment_count = $this->mdemandcomment->get_count_all(array('did' => $commentData['did'], 'audit' => 1));
				$this->mdemand->update(array('comment_count' => $comment_count), array('id' => $commentData['did']));
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['comment'] = $commentData;
				$this->vdata['content']['res'] = $insertID;
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['comment'] = array();
				$this->vdata['content']['res'] = 0;
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 需求评论列表
	public function comment_list() {
		// 验证
		$this->form_validation->set_rules($this->rules['comment_list']);
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

			// 拉取数据
			if ($it = $this->mdemand->get_one($where, 'id, aid, title, province, city, ctype, tags, content, photo, timeline, recommend_count')) {
				$this->_parseComment($it, $comment_limit, $comment_page, $reply_limit, $reply_page);
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['comments'] = $it['comments'];
				$this->vdata['content']['comments_count'] = $it['comments_count'];
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['comments'] = array();
				$this->vdata['content']['comments_count'] = 0;
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 评论点赞
	public function comment_praise() {
		// 验证
		$this->form_validation->set_rules($this->rules['comment_praise']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			if ($effectID = $this->mdemandcomment->add_praise($this->comment['id'])) {
				$it = $this->mdemandcomment->get_one(array('id' => $this->comment['id'], 'audit' => 1), 'id, praise_count');
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['praise_count'] = $it['praise_count'];
				$this->vdata['content']['res'] = $effectID;
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['praise_count'] = isset($this->comment) ? $this->comment['praise_count'] : '0';
				$this->vdata['content']['res'] = 0;
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 评论
	public function reply() {
		// 验证
		$this->form_validation->set_rules($this->rules['reply']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			$replyData = array();
			$replyData['did'] = $this->comment['did'];
			$replyData['dcid'] = $this->comment['id'];
			$replyData['aid'] = $this->userinfo['id'];
			$replyData['content'] = $this->form_validation->set_value('content');
			$replyData['timeline'] = (string) time();

			if ($insertID = $this->mdemandreply->create($replyData)) {
				$replyData['id'] = (string) $insertID;
				$this->_parseAidInfo($replyData);
				l_msg_send("回复了你",8,$replyData['aid'],$this->comment['aid'],3,0,$replyData['did']);
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['reply'] = $replyData;
				$this->vdata['content']['res'] = $insertID;
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['reply'] = array();
				$this->vdata['content']['res'] = 0;
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 需求回复列表
	public function reply_list() {
		// 验证
		$this->form_validation->set_rules($this->rules['reply_list']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			$where = array();
			$where['audit'] = 1;
			$where['id'] = $this->form_validation->set_value('dcid');

			$reply_limit = $this->form_validation->set_value('reply_limit');
			$reply_page = $this->form_validation->set_value('reply_page');

			// 拉取数据
			if ($it = $this->mdemandcomment->get_one($where, 'id, did, aid, content, like, timeline')) {
				$this->_parseReply($it, $reply_limit, $reply_page);
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['replys'] = $it['replys'];
				$this->vdata['content']['replys_count'] = $it['replys_count'];
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['content']['replys'] = array();
				$this->vdata['content']['replys_count'] = 0;
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 收藏开关
	public function recommend_toggle() {
		// 验证
		$this->form_validation->set_rules($this->rules['recommend_toggle']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			// 添加到购物车
			if ($this->toggleResult === 0) {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '取消推荐成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = (string) $this->toggleResult;
			} elseif ($this->toggleResult === 1) {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '添加推荐成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = (string) $this->toggleResult;
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = "";
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 收藏开关
	public function comment_praise_toggle() {
		// 验证
		$this->form_validation->set_rules($this->rules['comment_praise_toggle']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			// 添加到购物车
			if ($this->toggleResult === 0) {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '取消推荐成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = (string) $this->toggleResult;
			} elseif ($this->toggleResult === 1) {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '添加推荐成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = (string) $this->toggleResult;
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = "";
			}
		}
		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 需求删除
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
			if ($effectID = $this->mdemand->del($this->demand_info['id'], array('aid' => $this->userinfo['id']))) {
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
		$this->db->cache_on();
		if ($is_list && $target) {
			if (array_key_exists('photo', $target['0'])) {
				photo2url($target, 'false');
			}
			foreach ($target as $k => &$v) {
				$this->_parseTimeline($v);
				$this->_parseDistrictIds($v);
				$this->_parseIndustry($v);
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
		}
		$this->db->cache_off();
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
				if($info['fid']!=0){
					$info_f = $this->mctypes->get_one($info['fid']);
					$target['ctype_title'] =$info_f['title'] .' '. $info['title'];
				}else{
					$target['ctype_title'] = $info['title'];
				}
				
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
		$comments = $this->mdemandcomment->get_list_join_dcp(
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
		$target['comments_count'] = $this->mdemandcomment->get_count_all($where);
	}

	// 获取需求用户信息
	private function _parseReply(&$target = false, $replyLimit = 6, $replyPage = 1) {
		$replyPage -= 1;
		$offset = $replyPage * $replyLimit;
		$where = array();
		$where['audit'] = 1;
		$where['dcid'] = $target['id'];
		$replys = $this->mdemandreply->get_list($replyLimit, $offset, array(), $where, 'id, did, dcid, aid, content, timeline');
		foreach ($replys as $key => &$reply) {
			$reply['did_aid'] = $target['aid'];
			$this->_parseTimeline($reply);
			$this->_parseAidInfo($reply, 'aid');
			$this->_parseAidInfo($reply, 'did_aid');
		}
		$target['replys'] = $replys;
		$target['replys_count'] = $this->mdemandreply->get_count_all($where);
	}

	// 获取需求用户信息
	private function _parseAidInfo(&$target = false, $field = 'aid') {
		if ($target && isset($target[$field]) && $target[$field]) {
			if (isset($this->aid_info_cache[$target[$field]])) {
				$target[$field . '_info'] = $this->aid_info_cache[$target[$field]];
			} else if ($aidInfo = $this->macc->get_one(array('id' => $target[$field]), 'id, photo, nickname, create_time as timeline, level, score,endtimeline')) {
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
		if (isset($this->userinfo) && $this->userinfo && $recommendInfo = $this->mdemandrecommend->get_one(array('did' => $target['id'], 'aid' => $this->userinfo['id']))) {
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

	////////////////////////////////////////////////////////////////////////////
	///////////////规则验证///////////////////////////////规则验证////////////////
	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////
	///////////////规则验证///////////////////////////////规则验证////////////////
	////////////////////////////////////////////////////////////////////////////

	public function edit_did_check($str) {
		$demand = $this->mdemand->get_one(array('id' => $str, 'audit' => 1), 'id, aid');
		if ($demand) {
			if ($demand['aid'] != $this->userinfo['id']) {
				$this->form_validation->set_message('edit_did_check', '该需求不是你发布的，不能修改！');
				return false;
			} else {
				$this->demand = $demand;
				return true;
			}
		} else {
			$this->form_validation->set_message('edit_did_check', '该需求信息不存在！');
			return false;
		}
	}

	public function comment_did_check($str) {
		$demand = $this->mdemand->get_one(array('id' => $str), 'id, aid');
		if ($demand) {
			$this->demand = $demand;
			return true;
		} else {
			$this->form_validation->set_message('comment_did_check', '该需求信息不存在！');
			return false;
		}
	}

	public function comment_praise_dcid_check($str) {
		$comment = $this->mdemandcomment->get_one(array('id' => $str), 'id, aid');
		if ($comment) {
			$this->comment = $comment;
			return true;
		} else {
			$this->form_validation->set_message('comment_praise_dcid_check', '该评论信息不存在！');
			return false;
		}
	}

	public function reply_dcid_check($str) {
		$comment = $this->mdemandcomment->get_one(array('id' => $str), 'id, did, aid');
		if ($comment) {
			$this->comment = $comment;
			return true;
		} else {
			$this->form_validation->set_message('reply_dcid_check', '该评论不存在！');
			return false;
		}
	}

	// 需求推荐
	public function recommend_toggle_did_check($str) {
		if ($demand_info = $this->mdemand->get_one(array('id' => $str, 'audit' => 1), 'id, title')) {
			if ($love_info = $this->mdemandrecommend->get_one(array('aid' => $this->userinfo['id'], 'did' => $str))) {
				// 删除收藏
				if ($love_info['status'] == 1) {
					$this->mdemandrecommend->update(array('status' => 0), array('aid' => $this->userinfo['id'], 'did' => $str));
					$this->toggleResult = 0;
				} else {
					$this->mdemandrecommend->update(array('status' => 1), array('aid' => $this->userinfo['id'], 'did' => $str));
					$this->toggleResult = 1;
				}
			} else {
				// 添加收藏
				$data = array_merge(array('title' => $demand_info['title']), array('aid' => $this->userinfo['id'], 'did' => $str, 'status' => 1));
				$this->mdemandrecommend->create($data);
				$this->toggleResult = 1;
			}
			// 更新主体 recommend 数量
			$recommend_count = $this->mdemandrecommend->get_count_all(array('did' => $demand_info['id'], 'status' => 1));
			$this->mdemand->update(array('recommend_count' => $recommend_count), array('id' => $demand_info['id']));
			return true;
		} else {
			$this->form_validation->set_message('recommend_toggle_did_check', '该需求不存在，请重新选择');
			return false;
		}
	}

	// 评论推荐
	public function comment_praise_toggle_dcid_check($str) {
		if ($comment_info = $this->mdemandcomment->get_one(array('id' => $str, 'audit' => 1), 'id, title')) {
			if ($praise_info = $this->mdemandcommentpraise->get_one(array('aid' => $this->userinfo['id'], 'dcid' => $str))) {
				// 删除推荐
				if ($praise_info['status'] == 1) {
					$this->mdemandcommentpraise->update(array('status' => 0), array('aid' => $this->userinfo['id'], 'dcid' => $str));
					$this->toggleResult = 0;
				} else {
					$this->mdemandcommentpraise->update(array('status' => 1), array('aid' => $this->userinfo['id'], 'dcid' => $str));
					$this->toggleResult = 1;
				}
			} else {
				// 添加推荐
				$data = array('aid' => $this->userinfo['id'], 'dcid' => $str, 'status' => 1);
				$this->mdemandcommentpraise->create($data);
				$this->toggleResult = 1;
			}
			// 更新主体 praise_count 数量
			$praise_count = $this->mdemandcommentpraise->get_count_all(array('dcid' => $comment_info['id'], 'status' => 1));
			$this->mdemandcomment->update(array('praise_count' => $praise_count), array('id' => $comment_info['id']));
			return true;
		} else {
			$this->form_validation->set_message('comment_praise_toggle_dcid_check', '该评论不存在，请重新选择');
			return false;
		}
	}

	// 需求删除检测
	public function delete_did_check($str) {
		if ($demand_info = $this->mdemand->get_one(array('id' => $str, 'audit' => 1), 'id, aid, title')) {
			if ($demand_info['aid'] != $this->userinfo['id']) {
				$this->form_validation->set_message('delete_did_check', '该需求不是你发布的，不能删除！');
				return false;
			} else {
				$this->demand_info = $demand_info;
				return true;
			}
		} else {
			$this->form_validation->set_message('delete_did_check', '该需求不存在，请重新选择');
			return false;
		}
	}

}
