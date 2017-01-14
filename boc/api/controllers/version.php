<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
* Class account extends MY_Controller
 * @author hanj
 * 移动端用户接口类(用户类, 店长等身份用)
 */
class version extends API_Controller
{

  protected $rules = array(
      "index" => array(
          array(
              "field" => "vsersion_val",
              "label" => "版本值",
              "rules" => "trim|required"
          )
          ,array(
              "field" => "type",
              "label" => "客户端类型",
              "rules" => "trim|required"
          )
      ),
  );

  public function __construct()
  {
    parent::__construct();

    $this->load->model('version_model', 'mversion');
  }

  // 用户忘记密码
  public function index()
  {
    // 验证
    $this->form_validation->set_rules($this->rules['index']);

    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode']   = '0011';
      $this->vdata['returnInfo'] = validation_errors();
    } else {
      $where = array(
        'type'=>$this->data['type']
      );
      if ($version = $this->mversion->get_one($where, 'title,value,is_force,url,type')) {
        // photo2url($data, 'false', 'false');
        // level＝0   你当前是最新版本
        // level＝1  是强制性更新
        // level＝2  有新版本
        if ($version['value'] == $this->data['vsersion_val']) {
          $version['level'] = '0';
        } elseif (($version['value'] >= $this->data['vsersion_val']) && ($version['is_force'] == 1)) {
          $version['level'] = '1';
        } elseif (($version['value'] >= $this->data['vsersion_val']) && ($version['is_force'] == 0)) {
          $version['level'] = '2';
        } else {
          $version['level'] = '0';
        }
        unset($version['is_force']);
        unset($version['type']);
        $this->vdata['returnCode']   = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure']     = JSON_SECURE;
        $this->vdata['content']['version']    = $version;
      } else {
        $this->vdata['returnCode']   = '200';
        $this->vdata['returnInfo'] = '版本信息未查到';
        $this->vdata['secure']     = JSON_SECURE;
        $this->vdata['content']['version'] = array();
      }
    }

    // 返回json数据
    $this->_send_json($this->vdata);
  }

}
