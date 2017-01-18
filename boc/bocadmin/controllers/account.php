<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends Modules_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('account_model', 'maccount');
		$this->load->model('account_token_model', 'mtoken');
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
			),
			"edit" => array(
				array(
					"field" => "phone",
					"label" => '手机',
					"rules" => "trim|xss_clear|mobile"
				)
			)
		);

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
		if(!is_mobile($name)) {
			$this->form_validation->set_message('phone_check', '%s : '.$name.'格式错误');
			return FALSE;
		}
		if ($name and $mid = $this->model->find_phone($name)) {
			$this->form_validation->set_message('phone_check', '%s : '.$name.'已经被使用。');
			return FALSE;
		}else{
			return TRUE;
		}
    }
	protected function _create_data(){
		$form=$this->input->post();
		$form['pwd'] = passwd($form['pwd']);
		$form['nickname'] = $form['phone'];
		return $form;
	}
	protected function _create_after($data){
		if (isset($data['tags'])) {
			!!$data['tags'] and $this->mtags->add(str_replace(array('，',' ','　','|'), ',', $data['tags']),$this->cid,$data['id']);
		}
		$token = genToken();
		$this->maccount->gettoken($token, $data['id'],false,$data['terminalNo']);
	}
	protected function _edit_after($data){
		if (isset($data['tags'])) {
			!!$data['tags'] and $this->mtags->add(str_replace(array('，',' ','　','|'), ',', $data['tags']),$this->cid,$data['id']);
		}
		$token = genToken();
		$this->maccount->gettoken($token, $data['id'],true,$data['terminalNo']);
	}
	protected function _del_after($data){
		$this->mtoken->delete($data);
	}

}
