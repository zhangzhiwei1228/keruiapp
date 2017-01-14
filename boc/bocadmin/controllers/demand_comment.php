<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Demand_Comment extends CRUD_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('coltypes_model','mctypes');
		$this->load->model('account_model','macc');
		$this->load->model('district_model','mdistrict');
		$this->load->model('demand_reply_model','mdemand_reply');
		$this->aid_info_cache = array();

		$this->rules = array(
			"rule" => array(
				array(
					"field" => "title",
					"label" => lang('title'),
					"rules" => "trim"
				)
				,array(
					"field" => "timeline",
					"label" => lang('time'),
					"rules" => "trim|strtotime"
				)
				,array(
					"field" => "content",
					"label" => lang('conent'),
					"rules" => "trim"
					// link_create tag 生成
				)
				,array(
					"field" => "photo",
					"label" => lang('photo'),
					"rules" => "trim"
				)
			)
		);
	}

	protected function _vdata(&$vdata)
  {
		$vdata['title'] = lang('demand');
		$ctypes_tmp = $this->mctypes->get_all(array('name'=>'industry', 'show'=>1), 'id, title');
		$ctypes = array();
		if ($ctypes_tmp) {
			foreach ($ctypes_tmp as $k => $v) {
				$ctypes[$v['id']] = $v;
			}
		}

		if (in_array($this->method, array('index'))) {
			if ($vdata['list']) {
				foreach ($vdata['list'] as $key => &$item) {
					$this->_parseAidInfo($item);
					$item['reply_count'] = $this->mdemand_reply->get_count_all(array(
						'dcid' => $item['id']
					));
				}
			}
		} else if (in_array($this->method, array('edit'))) {
			$this->_parseAidInfo($vdata['it']);
		}

		$vdata['ctypes'] = $ctypes;
		dump($vdata);
  }

	// 对index提供where条件
	protected function _index_where(){
		$where = array();
		$where['did'] = $this->input->get('did');
		if ($content = $this->input->get('content')) {
			$where['like content'] = array('content', $content);
		}
		return $where;
	}

	protected function _edit_data(){
		$form = array();
		$form['id'] = $this->input->post('id');
		$form['content'] = $this->input->post('content');
		return $form;
	}

	// 获取需求用户信息
  private function _parseAidInfo(&$target = false, $field='aid')
  {
    if ($target && isset($target[$field]) && $target[$field]) {
			if (isset($this->aid_info_cache[$target[$field]])) {
      	$target[$field.'_info'] = $this->aid_info_cache[$target[$field]];
			} else if ($aidInfo = $this->macc->get_one(array('id'=>$target[$field]), 'id, photo, nickname, timeline, level')) {
	      photo2url($aidInfo, 'false', 'false');
	      $this->_parseTimeline($aidInfo);
	      if ($aidLevel = $this->mctypes->get_one(array('id'=>$aidInfo['level'], 'name'=>'level'), 'id, title, identify')) {
	        $aidInfo['level_title'] = $aidLevel['title'];
	        $aidInfo['level_identify'] = $aidLevel['identify'];
	      } else {
	        $aidInfo['level_title'] = "普通会员";
	        $aidInfo['level_identify'] = "0";
	      }
	      $target[$field.'_info'] = $aidInfo;
				$this->aid_info_cache[$target[$field]] = $aidInfo;
			} else {
				$target[$field.'_info'] = array();
			}
    } else {
			$target[$field.'_info'] = array();
		}
  }
	
	// 时间格式化
  private function _parseTimeline(&$target = false)
  {
    if (isset($target['timeline']) && $target['timeline'] && $target['timeline'] > 0) {
      $target['timeline'] = date("Y-m-d", $target['timeline']);
    } else {
      $target['timeline'] = "";
    }
  }
}
