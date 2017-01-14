<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

// 省市控制器
class district extends CRUD_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('district_model', 'model');
	}

	// 获取省市区信息列表树
	public function index($parentid = 0, $more = 'nomore') {
    if ($this->input->get('code')) {
      $parentid = $this->input->get('code');
    }

		$vdata['status'] = 1;
		$vdata['msg'] = '省市区信息';
		$vdata['data'] = $this->model->get_tree($parentid, $more);

		$this->output->set_content_type('application/json')->set_output(json_encode($vdata));
	}

}
