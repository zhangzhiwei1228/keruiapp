<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * Class example extends MY_Controller
 * @author lj
 * 移动端用户接口类
 */
class example extends API_Controller {

	public function __construct() {
		parent::__construct();
     $this->load->model('account_model', 'macc');
     $this->load->model('upload_model', 'mupload');
     $this->userInfoFields = 'id,phone,nickname,province,city,industry,autograph';
	}

	protected $rules = array(
		"get_token" => array(
	        array(
	            "field" => "id",
	            "label" => "账号编号",
	            "rules" => "trim|required"
	        ) 
        )
	);

 
  //融云---token
  public function get_token()
  {
    // 验证
    $this->form_validation->set_rules($this->rules['get_token']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
     $this->vdata['returnCode']   = '0011';
     $this->vdata['returnInfo'] = validation_errors();
    } else {
      include LIBS_PATH.'ry/rongcloud.php';
      $RongCloud = new RongCloud(RY_APPKEY,RY_APPSECRET);

        // 返回用户详细数据
        if ($finfo=$this->macc->get_one(array('id' => $this->data['id']))) {
        	$finfo['photourl']=static_file('no.png');
        	$finfo['nickname']='普通会员';
          /*if(!empty($finfo['photo'])){
			photo2url($finfo, 'false', 'false');
			$finfo['photourl']=$finfo['photo']['url'];
          }*/
          $result = $RongCloud->user()->getToken($finfo['id'], $finfo['nickname'], $finfo['photourl']);
          $res=json_decode($result);
          if(!empty($res->token)){
          	$this->vdata['returnCode'] = '200';
	          $this->vdata['returnInfo'] = '操作成功';
	          $this->vdata['secure'] = JSON_SECURE;
	          $this->vdata['content'] = $res;
	      }else{
	      	 $this->vdata['returnCode'] = '200';
             $this->vdata['returnInfo'] = '操作失败';
	      }
          
        } else {
          $this->vdata['returnCode'] = '200';
          $this->vdata['returnInfo'] = '操作失败';
        }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  
}
