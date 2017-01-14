<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

// 包裹入库相关功能
class service extends API_Controller {
  protected $rules = array(
  );

  public function __construct() {
    parent::__construct();
  }

  // 常用语
  public function get_phrase_list() {
    $this->load->model('phrase_model', 'mphrase');
    $where = array('audit'=>1);
    // 初始化翻页
    $this->_list();
    // 拉取数据
    if ($list = $this->mphrase->get_list($this->limit, $this->offset, false, $where, 'id, title')) {
      $this->vdata['returnCode'] = '200';
      $this->vdata['returnInfo'] = '操作成功';
      $this->vdata['secure'] = JSON_SECURE;
      $this->vdata['content']['phrase'] = $list;
      $this->vdata['content']['phrase_len'] = $this->mphrase->get_count_all($where);
    } else {
      $this->vdata['returnCode'] = '200';
      $this->vdata['returnInfo'] = '操作失败';
      $this->vdata['content']['phrase'] = array();
      $this->vdata['content']['phrase_len'] = 0;
    }

    // 返回json数据
    $this->_send_json($this->vdata);
  }

  // 手机号提示
  public function get_phone_list() {
    $this->load->model('banphone_model', 'mbanphone');
    // 初始化翻页
    $this->_list();
    // 拉取数据
    if ($list = $this->mbanphone->get_list($this->limit, $this->offset, array('id'=>'asc'), array(), 'id, ban, phone')) {
      $this->vdata['returnCode'] = '200';
      $this->vdata['returnInfo'] = '操作成功';
      $this->vdata['secure'] = JSON_SECURE;
      $this->vdata['content']['phones'] = $list;
    } else {
      $this->vdata['returnCode'] = '200';
      $this->vdata['returnInfo'] = '操作失败';
      $this->vdata['content']['phones'] = array();
    }

    // 返回json数据
    $this->_send_json($this->vdata);
  }

  // 地址选择信息
  public function getFullAddrList($parentid = 0, $more = 'more')
  {
    $this->load->model('district_model', 'mdistrict');
    $result = $this->mdistrict->get_tree();
    // 拉取数据
    if ($list = $this->mdistrict->get_tree()) {
      $this->vdata['returnCode'] = '200';
      $this->vdata['returnInfo'] = '操作成功';
      $this->vdata['secure'] = JSON_SECURE;
      $this->vdata['content']['districts'] = $list;
    } else {
      $this->vdata['returnCode'] = '200';
      $this->vdata['returnInfo'] = '操作失败';
      $this->vdata['content']['districts'] = array();
    }

    // 返回json数据
    $this->_send_json($this->vdata);
  }

  // 订单列表
  public function get_express_company_list() {
    $this->load->model('express_model', 'mexpress');
    // 初始化翻页
    $this->_list();
    // 拉取数据
    if ($list = $this->mexpress->get_list($this->limit, $this->offset, array('id'=>'asc'), array('audit'=>1), 'id, code_kuaidi100, code_jisu, title')) {
      $this->vdata['returnCode'] = '200';
      $this->vdata['returnInfo'] = '操作成功';
      $this->vdata['secure'] = JSON_SECURE;
      $this->vdata['content']['expresses'] = $list;
    } else {
      $this->vdata['returnCode'] = '200';
      $this->vdata['returnInfo'] = '操作失败';
      $this->vdata['content']['expresses'] = array();
    }

    // 返回json数据
    $this->_send_json($this->vdata);
  }

}
