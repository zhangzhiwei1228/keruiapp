<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

//移动端用户接口类
class login extends API_Controller
{

    public function __construct()
    {
      parent::__construct();

      $this->userInfoFields = 'id,audit,phone,nickname,photo,language';
    }

    protected $rules = array(
        "withPWD" => array(
            array(
                "field" => "phone",
                "label" => "帐号",
                "rules" => "trim|is_phone_required|callback_account_check"
            ),
            array(
                "field" => "pwd",
                "label" => "登录密码",
                "rules" => "trim|required|min_length[6]|max_length[16]|callback_pwd_verify"
            ),
            array(
                "field" => "terminalNo",
                "label" => "终端类型",
                "rules" => "trim"
            )
        ),
        "withCode" => array(
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
        ),
        "smsCode" => array(
            array(
                'field'   => 'phone',
                'label'   => '手机号',
                'rules'   => 'trim|required|numeric|min_length[11]|max_length[11]|callback_sendSmsCode_check'
            )
        ),
    );

    // 用户密码登录
    public function index()
    {
        // 验证
        $this->form_validation->set_rules($this->rules['withPWD']);
        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode']   = '0011';
            $this->vdata['returnInfo'] = $this->trim_validation_errors();
        } else {
            if (!isset($this->data['terminalNo'])) {
                $this->data['terminalNo'] = 0;
            }
            // 获取用户数据
            if ($res = $this->macc->setlogin($this->info['id'])) {
                $info = $this->mvacc->get_info($this->info['id'], 'fresh', $this->data['terminalNo'], $this->userInfoFields);
                //返回用户详细数据
                $this->vdata['returnCode']   = '200';
                $this->vdata['returnInfo'] = '操作成功';
                $this->vdata['secure']     = JSON_SECURE;
                $this->vdata['content'] = $info;
            } else {
                $this->vdata['returnCode']   = '200';
                $this->vdata['returnInfo'] = '操作失败';
                $this->vdata['content'] = array();
            }
        }
        // 返回json数据
        $this->_send_json($this->vdata);
    }
    // 检验找回密码账户
    public function account_check($account)
    {
        // 查找用户
        if ($info = $this->macc->get_one(array('phone' => $account,'audit' => 1))) {
            $this->info = $info;
            return true;
        } else {
            $this->form_validation->set_message('account_check', '该账号不存在或已被禁用！');
            return false;
        }
    }

    // 检验找回密码账户
    public function sendSmsCode_check($account)
    {
        // 查找用户
        if ($this->info = $this->macc->get_one(array('phone'=>$account))) {
            return true;
        } else {
            // 创建用户 组装创建数据
            $create_data = array(
                'phone'        => $account,
                //  'pwd'      => 'passwd(rand_str(16))',
                'pwd'      => '0',
                'username'      => $account,
                'nickname'      => $account,
                'realname'      => $account,
                'terminalNo'    => $this->data['terminalNo'],
                'audit'        =>'1'
            );

            // 创建数据
            if ($id = $this->macc->create($create_data)) {
            // 返回用户详细数据
                if ($this->info = $this->macc->get_one(array('id' => $id), $this->userInfoFields, 'fresh')) {
                    // 操作成功
                    return true;
                } else {
                    $this->form_validation->set_message('sendSmsCode_check', '用户信息请求失败');
                    return false;
                }
            } else {
                $this->form_validation->set_message('sendSmsCode_check', '用户创建失败');
                return false;
            }
        }
    }

    // 密码校验
    public function pwd_verify($password)
    {
        // 消除通过路由的请求
        if ($this->router->method == 'pwd_verify') {
            show_404();
        }

        // 帐号存在则过
        if (isset($this->info) and is_array($this->info) and $this->info) {
            if ($password and passwd($password) == $this->info['pwd']) {
                return true;
            }
            $this->form_validation->set_message('pwd_verify', '登陆密码错误,请重新填写');
            return false;
        }
    }

    public function smscode_check($smscode)
    {
        $this->load->model('smscode_model', 'msmscode');

        if (!$this->msmscode->verifySmsCode($this->data['phone'], $smscode)) {
            // 返回失败
            $this->form_validation->set_message('smscode_check', '验证码验证失败,请重新填写');
            return false;
        }
        return true;
    }
}
