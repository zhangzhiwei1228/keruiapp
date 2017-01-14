<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * Class account_third extends MY_Controller
 * @author lj
 * 移动端用户接口类
 */
class account_third extends API_Controller {

	public function __construct() {
		parent::__construct();
    $this->load->model('account_opinion_model', 'maopinion');
     $this->load->model('upload_model', 'mupload');
     $this->userInfoFields = 'id,phone,nickname,province,city,industry,autograph,level';
	}

	protected $rules = array(
		 "login_party" => array(
        array(
            "field" => "authtype",
            "label" => "第三方方式",
            "rules" => "trim|required"
        )
        ,array(
             "field" => "authkey",
             "label" => "key",
             "rules" => "trim|required"
         ),array(
             "field" => "nickname",
             "label" => "昵称",
             "rules" => "trim"
         )
        ),
		"edit_reg" => array(
        array(
            "field" => "authtype",
            "label" => "第三方方式",
            "rules" => "trim|required"
        ),array(
             "field" => "authkey",
             "label" => "key",
             "rules" => "trim|required"
         ),array(
            "field" => "phone",
            "label" => "手机号码",
            "rules" => "trim|required|numeric|min_length[11|max_length[11]|is_mobile|callback_account_check",
          )
          , array(
            "field" => "smscode",
            "label" => "手机验证码",
            "rules" => "trim|required|min_length[6]|max_length[6]|callback_smscode_check",
          )
          , array(
            "field" => "pwd",
            "label" => "登录密码",
            "rules" => "trim|required|min_length[6]|max_length[12]",
          )
          , array(
            "field" => "province",
            "label" => "省份",
            "rules" => "trim|required",
          )
          , array(
            "field" => "city",
            "label" => "城市",
            "rules" => "trim|required",
          )
          , array(
            "field" => "industry",
            "label" => "行业",
            "rules" => "trim|required",
          )
        )
	);

	// 第三方注册，登录
	public function go() {
		// 返回服务器时间以及预定义参数
		$this->vdata['timeline'] = time();
		$this->vdata['content'] = '';
		$this->vdata['secure'] = 0;
		// 验证
		$this->form_validation->set_rules($this->rules['login_party']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
      if (!isset($this->data['terminalNo'])) {
        $this->data['terminalNo'] = 0;
      }
       if ($userinfo = $this->macc->get_one(array('authtype'=> $this->data['authtype'],'authkey'=> $this->data['authkey']))) {
         $this->vdata['returnCode']   = '200';
          $this->vdata['returnInfo'] = '1111';
         // 获取用户数据
        if ($res = $this->macc->setlogin($userinfo['id'])) {
          $info = $this->mvacc->get_info($userinfo['id'], 'fresh', $this->data['terminalNo']);
          $info['provincename']=get_addr($info['province']);
           $info['cityname']=get_addr($info['city']);
           $info['industryname']=get_industry($info['industry']);
            if(!empty($info['endtimeline'])&&$info['endtimeline']<time()){
		      	$info['level'] = -1;
		      }
		      if(!empty($info['endtimeline'])){$info['endtimeline'] =date("Y-m-d", $info['endtimeline']);}
          unset($info['store_id']);
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
       }else{
           // 组装创建数据
            $nickname1='普通会员';
            $nickname2='VIP会员';
            if(!empty($this->data['nickname'])){
              $nickname1=$this->data['nickname'];
              $nickname2=$this->data['nickname'];
            }
            $photo='';
            if(!empty($this->data['photo'])){
              $photourl['url']=$this->data['photo'];
              if($pid=$this->mupload->create($photourl)){
                $photo=$pid;
              }
            }
            if(time()<EXPIRES_TIME){
				$create_data = array(
	              'authtype' => $this->data['authtype'],
	              'authkey' => $this->data['authkey'],
	              'nickname' => $nickname2,
	              'photo' => $photo,
	              'audit' => '1',
	              'level' => 1,
				  'endtimeline' => EXPIRES_TIME,
	            );
			}else{
				$create_data = array(
	              'authtype' => $this->data['authtype'],
	              'authkey' => $this->data['authkey'],
	              'nickname' => $nickname1,
	              'photo' => $photo,
	              'audit' => '1',
	            );

			}
            // 创建数据
            if ($id = $this->macc->create($create_data)) {
              // 返回用户详细数据
              if ($info = $this->mvacc->get_info(array('id' => $id), $this->userInfoFields, $this->data['terminalNo'])) {
                l_msg_send('恭喜注册成功', 7, 0, $info['id']);
                $this->vdata['returnCode'] = '200';
                $this->vdata['returnInfo'] = '操作成功';
                $this->vdata['secure'] = JSON_SECURE;
                $this->vdata['content'] = $info;
              } else {
                $this->vdata['returnCode'] = '200';
                $this->vdata['returnInfo'] = '操作失败';
              }
            } else {
              $this->vdata['returnCode'] = '200';
              $this->vdata['returnInfo'] = '操作失败';
            }

       }
		}
		$this->_send_json($this->vdata);
	}

  //个人中心- 更新个人数据
  public function edit_reg()
  {
    // 验证
    $this->form_validation->set_rules($this->rules['edit_reg']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
     $this->vdata['returnCode']   = '0011';
     $this->vdata['returnInfo'] = validation_errors();
    } else {
      if(!isset($this->data['terminalNo'])){
        $this->data['terminalNo']=0;
      }
      if ($userinfo = $this->macc->get_one(array('authtype'=> $this->data['authtype'],'authkey'=> $this->data['authkey']))) {
            $edit_data = array(
            'phone' => $this->data['phone'],
            'pwd' => passwd($this->data['pwd']),
            'province' => $this->data['province'],
            'city' => $this->data['city'],
            'industry' => $this->data['industry'],
          );
        if ($id = $this->macc->update($edit_data,array('id'=>$userinfo['id']))) {
          // 返回用户详细数据
          if ($info = $this->mvacc->get_info(array('id' => $userinfo['id']), $this->userInfoFields, $this->data['terminalNo'])) {
            $info['provincename'] = get_addr($info['province']);
            $info['cityname'] = get_addr($info['city']);
            $info['industryname'] = get_industry($info['industry']);
            if(!empty($info['endtimeline'])&&$info['endtimeline']<time()){
		      	$info['level'] = -1;
		      }
		      if(!empty($info['endtimeline'])){$info['endtimeline'] =date("Y-m-d", $info['endtimeline']);}
            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure'] = JSON_SECURE;
            $this->vdata['content'] = $info;
          } else {
            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作失败';
          }
        }else{
          $this->vdata['returnCode'] = '200';
          $this->vdata['returnInfo'] = '操作失败';
        }
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  //////////////////////////////////////
  ///////////////规则验证////////////////
  //////////////////////////////////////

  // 检验找回密码账户
  public function account_check($account) {
    // 查找用户
    if ($this->info = $this->macc->get_one(array('phone' => $account))) {
      $this->form_validation->set_message('account_check', '该手机号已经被注册！');
      return false;
    } else {
      return true;
    }
  }

  public function smscode_check($smscode) {
    $this->load->model('smscode_model', 'msmscode');

    if (!$this->msmscode->verifySmsCode($this->data['phone'], $smscode, 1)) {
      // 返回失败
      $this->form_validation->set_message('smscode_check', '验证码验证失败,请重新填写');
      return false;
    }
    return true;
  }
}
