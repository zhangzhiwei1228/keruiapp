<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
* Class account extends MY_Controller
 * @author hanj
 * 移动端用户接口类(用户类, 店长等身份用)
 */
class forgot extends API_Controller
{

    public function __construct()
    {
      parent::__construct();

      $this->userInfoFields = 'id,audit,phone,nickname,photo,province,city,industry';
    }
    protected $rules = array(
      "go" => array(
        array(
          "field" => "phone",
          "label" => "手机号码",
          "rules" => "trim|required|numeric|min_length[11|max_length[11]|is_mobile|callback_account_check"
        )
        ,array(
          "field" => "smscode",
          "label" => "手机验证码",
          "rules" => "trim|required|callback_smscode_check"
        )
        ,array(
          "field" => "pwd",
          "label" => "登录密码",
          "rules" => "trim|required|min_length[6]|max_length[16]"
        )
      ),
      // 注册时用
      "smsCode" => array(
        array(
          'field'   => 'phone',
          'label'   => '手机号',
          'rules'   => 'trim|required|numeric|min_length[11]|max_length[11]|is_mobile|callback_account_check'
        )
      )
    );

    // 用户忘记密码-1
    public function go()
    {
        // 返回服务器时间以及预定义参数
        $this->vdata['timeline']   = time();
        $this->vdata['content']    = '';
        $this->vdata['secure']     = 0;

        // 验证
        $this->form_validation->set_rules($this->rules['go']);

        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode']   = '0011';
            $this->vdata['returnInfo'] = validation_errors();
        } else {
            // 创建数据
           $uinfo=$this->mvacc->get_info(array('phone'=>$this->data['phone']));
           if(!empty($uinfo)){
              if ($id = $this->macc->set_pwd($uinfo['id'], passwd($this->data['pwd']))) {

                  if (!isset($this->data['terminalNo'])) {
                    $this->data['terminalNo'] = 0;
                  }
                  // 返回用户详细数据
                   if ($info = $this->mvacc->get_info(array('id'=>$uinfo['id'], 'terminalNo'=>$this->data['terminalNo']))) {
                      $this->vdata['returnCode']   = '200';
                      $this->vdata['returnInfo'] = '操作成功';
                      $this->vdata['secure']     = JSON_SECURE;
                      $this->vdata['content']['userinfo']    = $info;
                  } else {
                      $this->vdata['returnCode']   = '0011';
                      $this->vdata['returnInfo'] = '服务器请求失败';
                      $this->vdata['secure']     = JSON_SECURE;
                  }
              } else {
                  $this->vdata['returnCode']   = '200';
                  $this->vdata['returnInfo'] = '密码未变动';
                  $this->vdata['secure']     = JSON_SECURE;
                  $this->vdata['content']    = $uinfo;
              }
           }else {
              $this->vdata['returnCode']   = '0011';
              $this->vdata['returnInfo'] = '账号不存在';
              $this->vdata['secure']     = JSON_SECURE;
          }
            
        }

        // 返回json数据
        $this->_send_json($this->vdata);
    }

   

    // 发送验证码(创建用户时用)
    public function smsCode()
    {
        // 返回服务器时间以及预定义参数
        $this->vdata['timeline']   = time();
        $this->vdata['content']    = '';
        $this->vdata['secure']     = 0;

        // 验证
        $this->form_validation->set_rules($this->rules['smsCode']);

        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {

            // 返回失败
            $this->vdata['returnCode']   = '0011';
            $this->vdata['returnInfo'] = validation_errors();
        } else {
            $this->load->model('smscode_model', 'msmscode');

            if ($content = $this->msmscode->sendCode($this->data['phone'],2,2)) {
                if($content['code']==421){
                    $this->vdata['returnCode']   = '0011';
                    $this->vdata['returnInfo'] = $content['msg'];
                    $this->vdata['secure']     = JSON_SECURE;

                }else{
                    // 返回成功
                    $this->vdata['returnCode']   = '200';
                    $this->vdata['returnInfo'] = '操作成功';
                    $this->vdata['secure']     = JSON_SECURE;
                    $this->vdata['content'] = $content;
                }
            } else {
                // 返回成功
                $this->vdata['returnCode']   = '0011';
                $this->vdata['returnInfo'] = '短信发送失败';
                $this->vdata['secure']     = JSON_SECURE;
                $this->vdata['content'] = $content;
            }
        }

        // 返回json数据
        $this->_send_json($this->vdata);
    }

    //////////////////////////////////////
    ///////////////规则验证////////////////
    //////////////////////////////////////

  // 检验找回密码账户
  public function account_check($account)
  {
    // 查找用户
    if ($this->info = $this->macc->get_one(array('phone'=>$account))) {
      return true;
    } else {
      $this->form_validation->set_message('account_check', '该手机号用户不存在！');
      return false;
    }
  }

  public function smscode_check($smscode)
  {
    $this->load->model('smscode_model', 'msmscode');

    if (!$this->msmscode->verifySmsCode($this->data['phone'], $smscode,2)) {
      // 返回失败
      $this->form_validation->set_message('smscode_check', '验证码验证失败,请重新填写');
      return false;
    }
    return true;
  }
}
