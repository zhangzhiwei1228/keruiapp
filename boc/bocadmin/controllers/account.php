<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends Modules_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('account_model', 'maccount');
		$this->load->model('account_token_model', 'mtoken');
		$this->load->model('language_model', 'mlanguage');
		$this->rules = array(
			"rule" => array(
				array(
					"field" => "phone",
					"label" => '帐号',
					"rules" => "trim|required|xss_clear|min_length[1]|max_length[20]|callback_phone_check"
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
					"label" => '帐号',
					"rules" => "trim|required|xss_clear|min_length[1]|max_length[20]|callback_phone_check"
				)
			)
		);

	}
	public function create(){
		$this->form_validation->set_rules($this->_get_rule('create'));
		if ($this->form_validation->run() == false) {
			if ($this->input->is_ajax_request() AND is_post()) {
				$vdata['status'] = 0;
				$vdata['msg'] = validation_errors();
				$this->output->set_content_type('application/json')->set_output(json_encode($vdata));
			}else{
				$vdata['areas'] = $this->mlanguage->get_all(array('cid'=>19,'audit'=>1),'id,title');
				$this->_display($vdata);
			}
		}else{
			$this->_create();
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
		/*if(!is_mobile($name)) {
			$this->form_validation->set_message('phone_check', '%s : '.$name.'格式错误');
			return FALSE;
		}*/
		$key = $this->input->get_post('id',TRUE);
		$mid = $this->model->find_phone($name);
		if($key && $mid) {
			$mid = ($mid == $key) ? false : true;
		}
		if ($name && $mid) {
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
	protected function edit($key=false){
		if (!$key) {
			$key = $this->input->get_post('id',TRUE);
			if ($this->input->is_ajax_request()){
				if (!$key) {
					$vdata = array('msg'=>'没有提供标识','status'=>0);
					$this->output->set_content_type('application/json')->set_output(json_encode($vdata));
				}
			}else{
				if (!$key) {
					if (isset($this->cid)) {
						$index = '/index/'.$this->cid;
					}else{
						$index = '/index';
					}
					redirect(site_url($this->class.$index));
				}
			}
		}
		$this->form_validation->set_rules($this->_get_rule('edit'));
		if ($this->form_validation->run() == false) {

			$vdata['it'] = $this->model->get_one($key);

			if (!$vdata['it']) {
				$vdata = array('msg'=>'提供的标示是不存在的','status'=>0);
				if ($this->input->is_ajax_request()) {
					$this->output->set_content_type('application/json')->set_output(json_encode($vdata));
				}else{
					$this->load->view('msg',$vdata);
					return false;
				}
			}

			if ($this->input->is_ajax_request()) {
				if (is_post()) {
					$vdata['status'] = 0;
					$vdata['msg'] = validation_errors();
				}
				$this->output->set_content_type('application/json')->set_output(json_encode($vdata));
			}else{
				$vdata['areas'] = $this->mlanguage->get_all(array('cid'=>19,'audit'=>1),'id,title');
				$this->_display($vdata);
			}
		}else{
			$this->_edit();
		}
	}

}
