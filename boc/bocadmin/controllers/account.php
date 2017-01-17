<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends Modules_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('coltypes_model','mctypes');
		$this->load->model('district_model','mdistrict');

		$this->rules = array(
			"rule" => array(
				array(
					"field" => "phone",
					"label" => '手机',
					"rules" => "trim|xss_clear|callback_phone_check"
				)
				,array(
					"field" => "timeline",
					"label" => lang('time'),
					"rules" => "trim|strtotime"
				)
			)
		);

	}
	protected function _vdata(&$vdata)
  {
		$vdata['title'] = '会员管理';
		$ctypes_tmp = $this->mctypes->get_all(array('name'=>'industry', 'show'=>1), 'id, title');
		$ctypes = array();
		if ($ctypes_tmp) {
			foreach ($ctypes_tmp as $k => $v) {
				$ctypes[$v['id']] = $v;
			}
		}

		if (in_array($this->method, array('index','search'))) {
			if ($vdata['list']) {
				foreach ($vdata['list'] as $key => &$item) {
					$this->_parseAidInfo($item);
				}
			}
		} else if (in_array($this->method, array('edit'))) {
			$this->_parseAidInfo($vdata['it']);
		}

		$vdata['ctypes'] = $ctypes;
		// 对图片文件进行处理
		if ($this->method == 'edit') {
			$where=array('uid'=>$vdata['it']['id']);
			$this->db->order_by('id desc');
		}
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

	//用户搜索
	public function search($page = 1) {
		if(isset($this->reg[0])){$page=$this->reg[0];}else{$page=1;}
		$vdata['title'] = '用户搜索';
		$limit = 10;
		$this->input->get('limit') and is_numeric($this->input->get('limit')) and $limit = $this->input->get('limit');

		$where = array();
		$phone = $this->input->get('phone');
		if ($phone) {
			$where = array_merge($where, array('phone LIKE' => '%' . $phone . '%'));
		}

		$orders = array('id' => 'desc');

		$vdata['pages'] = $this->_pages(site_url($this->class . '/search'), $limit, $where);
		$vdata['list'] = $this->model->get_list($limit, $limit * ($page - 1), $orders, $where);
		$this->_display($vdata, 'account_index');
	}
	// 验证tel是否被使用
     public function phone_check($name = FALSE)
    {
       if ($name and $mid = $this->model->find_phone($name)) {
            $this->form_validation->set_message('phone_check', '%s : '.$name.'已经被使用。');
            return FALSE;
        }else{
            return TRUE;
        }
    }

}
