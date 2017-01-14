<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

// 所有回调处理
class callback extends MY_Controller {
  protected $rules = array(
  );

  public function __construct() {
    parent::__construct();
  }

  // 短信发送状态报告
  public function sms_status() {
    $this->load->library('sms');
    $res = $this->input->get();
    $this->sms->status_callback($res);
  }

}
