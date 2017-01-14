<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * Class platform extends MY_Controller
 * @author lj
 * 移动端用户接口类
 */
class platform extends API_Controller {

	public function __construct() {
		parent::__construct();
    $this->load->model('platform_model', 'mplatform');
	}

	protected $rules = array(
		 "create" => array(
        array(
            "field" => "id",
            "label" => "平台",
            "rules" => "trim|required"
        )
        )
	);

	// 信息写入
	public function info() {
		// 返回服务器时间以及预定义参数
		$this->vdata['timeline'] = time();
		$this->vdata['content'] = '';
		$this->vdata['secure'] = 0;
		// 验证
		$this->form_validation->set_rules($this->rules['create']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
       if ($info = $this->mplatform->get_one($this->data['id'],'version,versioninfo,level,url')) {
        //返回用户详细数据
          $this->vdata['returnCode']   = '200';
          $this->vdata['returnInfo'] = '操作成功';
          $this->vdata['secure']     = JSON_SECURE;
          $this->vdata['content'] = $info;
       }else{
           $this->vdata['returnCode'] = '200';
           $this->vdata['returnInfo'] = '操作失败';
           $this->vdata['secure']     = JSON_SECURE;
          $this->vdata['content'] = array();

       }
		}
		$this->_send_json($this->vdata);
	}
}
