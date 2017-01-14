<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class test extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		if ('ENVIRONMENT' == 'development') {
			$res1 = sina_shorturl();
			$res2 = sina_shorturl(site_url());

			$res = '';
			if (is_array($res2)) {
				$res = reset($res2);
				print_r($res->url_short);
			}
		}
	}

	public function sms() {
		if ('ENVIRONMENT' == 'development') {
			$this->load->model('shelforder_model', 'mshelforder');
			$it = $this->mshelforder->get_one(14);
			$this->sms->send('02_shelf_change', $it);
		}
	}

	public function jpush($id) {
		if (ENVIRONMENT == 'development') {
			push_jpush($id, '来个测试极光消息');
			push_jpush(array($id, 'a', 'b'), '来个测试极光消息array');
		}
		push_jpush('1', '来个测试极光消息');
		// account_msg_send('这只是测试消息标题', '来个测试极光消息', 2, 1);
	}
}
