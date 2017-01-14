<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

// 配置参数
class Webcfg extends API_Controller {
  protected $rules = array(
      "ctype" => array(
        array(
          "field" => "name",
          "label" => "Name",
          "rules" => "trim|required"
        )
      )
    );
  
  public function __construct() {
    parent::__construct();

    $this->load->model('coltypes_model', 'mcoltypes');
  }

  // 行业
    public function uGet_ctype()
    {
        // 返回服务器时间以及预定义参数
        $this->vdata['timeline']   = time();
        $this->vdata['content']    = '';
        $this->vdata['secure']     = 0;

        // 验证
        $this->form_validation->set_rules($this->rules['ctype']);

        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode']   = '0011';
            $this->vdata['returnInfo'] = validation_errors();
        } else {
            if ($info=$this->mcoltypes->get_all(array('name'=>$this->data['name'],'depth'=>'0'),'id,title,name,depth')) {
              foreach ($info as $key => $v) {
                $info[$key]['next']=$this->mcoltypes->get_all(array('name'=>$this->data['name'],'depth'=>'1','fid'=>$v['id']),'id,title,name,depth');
              }
              $this->vdata['returnCode']   = '200';
              $this->vdata['returnInfo'] = '操作成功';
              $this->vdata['secure']     = JSON_SECURE;
              $this->vdata['content']    = $info;
            } else {
                $this->vdata['returnCode']   = '200';
                $this->vdata['returnInfo'] = '服务器请求失败';
                $this->vdata['secure']     = JSON_SECURE;
            }
        }

        // 返回json数据
        $this->_send_json($this->vdata);
    }
}
