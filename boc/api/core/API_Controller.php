<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * MY Controller
 */
class API_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        // 返回服务器时间以及预定义参数
        $this->vdata['timeline'] = time();
        $this->vdata['content'] = null;
        $this->vdata['secure'] = 0;
        $this->vdata['returnCode'] = '';
        $this->vdata['returnInfo'] = '';

        //加载account_model
        $this->load->model('account_model', 'macc');
        $this->load->model('account_token_model', 'macctoken');
        $this->load->model('vaccount_model', 'mvacc');

        // 获取头部传参数
        $headers = getallheaders();
        //验证数据安全
        $data = $this->input->post();
        if (isset($headers['token']) && $headers['token']) {
            $data['token'] = $headers['token'];
        }

        // 请求类型
        if (isset($headers['platform']) && $headers['platform']) {
          $data['terminalNo'] = $headers['platform'];
        }

        // 翻页相关
        if (is_numeric($this->input->get('page'))) {
          $data['page'] = $headers['page'];
        }

        if (is_numeric($this->input->get('limit'))) {
          $data['limit'] = $headers['limit'];
        }

        if (ENVIRONMENT == 'development') {
          // 接口请求日志
          if (isset($this->router) && isset($this->router->uri) && isset($this->router->uri->uri_string)) {
            logfile('URI: '.print_r($this->router->uri->uri_string, 1), 'api/post_data_');
          }
          logfile('Post Data: '.print_r($data, 1), 'api/post_data_');
        }

        if (isset($data['secure']) && $data['secure'] && !empty($data['content'])) {
            if (!($this->data = apiValidate($data))) {
                $re = array('returnCode' => '401',
                    'returnInfo' => '数据传输失败，请联系客服！',
                    'timeline' => time());
                $re = array_merge($this->vdata, $re);
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode($this->_send_json_befor($re));
                exit;
            }
        } else if(isset($data['content']) && !empty($data['content']) && is_array($data['content'])) {
            $this->data = array_merge($data, $data['content']);
        } else {
            $this->data = $data;
        }

        //验证失败操作
        if (!$this->data) {
            $re = array('returnCode' => '401',
                'returnInfo' => '请求操作失败！',
                'timeline' => time());
            $re = array_merge($this->vdata, $re);
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($this->_send_json_befor($re));
            exit;
        } elseif (isset($this->data['token']) && !empty($this->data['token'])) {
            $this->usertoken = $this->macctoken->get_one(array('token' => $this->data['token'], 'expiretime >'=>time()), 'accountId');
            if ($this->usertoken) {
                $this->userinfo = $this->macc->get_one(array('id' => $this->usertoken['accountId']));
            } else {
                $re = array(
                    'returnCode' => '401',
                    'returnInfo' => '身份验证失败，请重新登录！',
                    'timeline' => time()
                );
                $re = array_merge($this->vdata, $re);
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode($this->_send_json_befor($re));
                exit;
            }
        }

        // if (ENVIRONMENT == "development"){ //开发模式
        // 	logfile(page_profiler(), 'profiler_');
        // }

        // test
        // $this->load->model('action_model', 'maction');
        // $this->maction->CreateShelfOrder('shelforder');
    }

    protected function _send_json($data)
    {
        $vdata = $this->_send_json_befor($data);
        if ((ENVIRONMENT == "development") && false) {
            $querys = array();
            if (count($this->db->queries) > 0)
                {
                    foreach ($this->db->queries as $key => $val)
                    {
                        $item['time'] = number_format($this->db->query_times[$key], 4);
                        $item['content'] = $val;
                        $querys[] = $item;
                    }
                }
            $vdata['query_count'] = count($querys);
            $vdata['querys'] = $querys;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($vdata));

    }

    protected function _send_json_befor($data)
    {
        $vdata['returnCode'] = $data['returnCode'];
        $vdata['msg'] = trim($data['returnInfo']);
        $vdata['secure'] = $data['secure'];
        if ($data['secure'] != '0') {
            //load_AES
            $this->load->library('AES');
            $vdata['data'] = AES::encrypt(json_encode($data['content']), KEY);
        } else {
            $vdata['data'] = $data['content'];
        }

        return $vdata;
    }

    // 初始化翻页
    protected function _list()
    {
        $data_post = $this->data;
        $page = !empty($data_post) && isset($data_post['page']) ? ($data_post['page'] - 1) : 0;
        $this->limit = !empty($data_post) && isset($data_post['limit']) ? $data_post['limit'] : 100;
        $this->offset = $page * $this->limit;
        $this->orderby = !empty($data_post) && isset($data_post['orderby']) ? $data_post['orderby'] : array('sort_id'=>'desc');

        if (!empty($data_post) && isset($data_post['orderdirection']) && in_array($data_post['orderdirection'], array('desc', 'asc'))) {
            $this->orderby = array($this->orderby=>$data_post['orderdirection']);
        }
    }

    public function trim_validation_errors()
    {
        return strip_tags(validation_errors(false, false, true));
    }

    public function loginAccount_check($phone)
    {

        //手机号优先
        if (is_numeric($phone) && $this->info = $this->macc->get_one(array('phone' => $phone))) {
            $this->phone = $phone;
            return true;
        } elseif (!is_numeric($phone) && $this->info = $this->macc->get_one(array('email' => $phone))) {
            $this->phone = $this->info['phone'];
            return true;
        }

        $this->form_validation->set_message('loginAccount_check', '您输入的手机号码有误，请重新输入！');
        return false;
    }

    // 登录检验账户Id
    public function loginAccountId_check($id)
    {

        //手机号优先
        if ($this->info = $this->macc->get_one(array('id' => $id))) {
            return true;
        }

        $this->form_validation->set_message('loginAccountId_check', '您输入的手机号码有误，请重新输入！');
        return false;
    }

    // 密码校验
    public function pwd_verify($password)
    {

        // 帐号存在则过
        if (is_array($this->info) and $this->info) {
            if ($password and passwd($password) == $this->info['pwd']) {
                return true;
            }

            $this->form_validation->set_message('pwd_verify', '登陆密码错误,请重新填写');
            return false;
        }
    }

    // Token校验
    public function token_verify($token)
    {
        //手机号优先
        if ($this->info = $this->mvacc->getInfoByToken($token, 'fresh')) {
            return true;
        }

        $this->form_validation->set_message('token_verify', '该token不存在或已经过期');
        return false;
    }
    public function pid_verify($pid)
    {
        if (!isset($this->mpro)) {
            $this->load->model('project_model', 'mpro');
        }

        //手机号优先
        if ($this->project = $this->mpro->get_one($pid)) {
            return true;
        }

        $this->form_validation->set_message('pid_verify', '该项目不存在！');
        return false;
    }
}
