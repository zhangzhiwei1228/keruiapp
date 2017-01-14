<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * Class account extends MY_Controller
 * @author xiejianwei
 * 移动端用户接口类
 */
class account extends API_Controller {

	public function __construct() {
		parent::__construct();
    $this->load->model('account_opinion_model', 'maopinion');
    $this->load->model('vfriends_model', 'mvfri');
     $this->load->model('friends_model', 'mfri');
    $this->load->model('circle_model', 'mcircle');
    $this->load->model('demand_model', 'mdemand');
    $this->load->model('resource_model', 'mresource');
    $this->userInfoFields = 'id,phone,nickname,province,city,industry,autograph,level,score,endtimeline';
	}

	protected $rules = array(
		"edit_head" => array(
			array(
				'field' => 'photo',
				'label' => '头像',
				'rules' => 'trim|required|numeric',
			),
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
		),
		"edit_title" => array(
			array(
				"field" => "title",
				"label" => "商户名称",
				"rules" => "trim|required",
			),
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
		),
		"edit_pwd" => array(
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      ),
			array(
				'field' => 'oldpassword',
				'label' => '原始密码',
				'rules' => 'trim|required|min_length[6]|callback_oldpassword_check',
			)
			, array(
				'field' => 'password',
				'label' => '密码',
				'rules' => 'trim|required|min_length[6]',
			)
			, array(
				'field' => 'password_re',
				'label' => '确认密码',
				'rules' => 'trim|required|min_length[6]|matches[password]',
			)
		),
    "get_update" => array(
      array(
        "field" => "nickname",
        "label" => "昵称",
        "rules" => "trim",
      ),array(
        "field" => "autograph",
        "label" => "个性签名",
        "rules" => "trim",
      ),array(
        "field" => "province",
        "label" => "省",
        "rules" => "trim",
      ),array(
        "field" => "city",
        "label" => "市",
        "rules" => "trim",
      ),array(
        "field" => "industry",
        "label" => "行业",
        "rules" => "trim",
      ),array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      ),
    ),
    "check_opinion" => array(
      array(
        "field" => "puid",
        "label" => "被评价人ID",
        "rules" => "trim|required",
      ),array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      ),
    ),
    "add_opinion" => array(
      array(
        "field" => "puid",
        "label" => "被评价人ID",
        "rules" => "trim|required",
      ),array(
        "field" => "score",
        "label" => "得分",
        "rules" => "trim|required",
      ),array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      ),
    ),"info" => array(
      array(
        "field" => "id",
        "label" => "好友ID",
        "rules" => "trim|required",
      ),array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
    ),"get_token" => array(
      array(
        "field" => "id",
        "label" => "编号ID",
        "rules" => "trim|required",
      )
    )
	);
// 会员详细信息
  public function info() {
    // 验证
    $this->form_validation->set_rules($this->rules['info']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode'] = '0011';
      $this->vdata['returnInfo'] = $this->trim_validation_errors();
    } else {
      if (!isset($this->data['terminalNo'])) {
          $this->data['terminalNo'] = 0;
        }
        // 返回用户详细数据
        if ($info = $this->mvacc->get_info(array('id' => $this->data['id']), 'nofresh', $this->data['terminalNo'])) {
          $info['provincename'] = get_addr($info['province']);
          $info['cityname'] = get_addr($info['city']);
          $info['industryname'] = get_industry($info['industry']);
          if(!empty($info['endtimeline'])&&$info['endtimeline']<time()){
	      	$info['level'] = -1;
	      }
	      if(!empty($info['endtimeline'])){$info['endtimeline'] =date("Y-m-d", $info['endtimeline']);}
          $info['is_friend'] = 0;
          $finfo=$this->mvfri->get_one(array('suid' => $this->data['id'],'uid'=>$this->userinfo['id'],'audit'=>1));
          if(!empty($finfo)){
            $info['is_friend'] = 1;
          }
          $info['score'] = round($info['score']);

          $this->vdata['returnCode'] = '200';
          $this->vdata['returnInfo'] = '操作成功';
          $this->vdata['secure'] = JSON_SECURE;
          $this->vdata['content'] = $info;
        } else {
          $this->vdata['returnCode'] = '200';
          $this->vdata['returnInfo'] = '操作失败';
        }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  // 自己会员详细信息
  public function infome() {
    // 验证
    $this->form_validation->set_rules($this->rules['info']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode'] = '0011';
      $this->vdata['returnInfo'] = $this->trim_validation_errors();
    } else {
      if ($this->userinfo&&$this->userinfo['id']==$this->data['id']) { //会员表
        if (!isset($this->data['terminalNo'])) {
            $this->data['terminalNo'] = 0;
          }
          // 返回用户详细数据
          if ($info = $this->mvacc->get_info(array('id' => $this->data['id']), 'nofresh', $this->data['terminalNo'])) {
            $uinfo= $this->macc->get_one(array('id' => $this->data['id']),'id,phone,zy,xq,qz,level,endtimeline');
            if(!empty($uinfo['endtimeline'])&&$uinfo['endtimeline']<time()){
              $uinfo['level'] = -1;
              $uinfo['zy']=0;
              $uinfo['xq']=0;
              $uinfo['qz']=0;
            }
            if(!empty($uinfo['endtimeline'])){$uinfo['endtimeline'] =date("Y-m-d", $uinfo['endtimeline']);}
            $uinfo['circle_count']=$this->mcircle->get_count_all(array('aid'=>$this->data['id']));
            $demand_count=$this->mdemand->get_count_all(array('aid'=>$this->data['id']));
            $resource_count=$this->mresource->get_count_all(array('aid'=>$this->data['id']));
            $uinfo['creat_count']=$demand_count+$resource_count;


            $uinfo['fri_count']=$this->mvfri->get_count_all(array('uid'=>$this->data['id'],'audit'=>1));

            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure'] = JSON_SECURE;
            $this->vdata['content'] = $uinfo;
          } else {
            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作失败';
          }
        }else{
           $this->vdata['returnCode'] = '200';
          $this->vdata['returnInfo'] = '操作失败';
        }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }

	// // 个人中心-设置头像
	public function edit_head() {
		// 返回服务器时间以及预定义参数
		$this->vdata['timeline'] = time();
		$this->vdata['content'] = '';
		$this->vdata['secure'] = 0;
		// 验证
		$this->form_validation->set_rules($this->rules['edit_head']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			if ($res = $this->macc->set($this->userinfo['id'], array('photo' => $this->data['photo'],'uptimeline'=>time()))) {//更新会员表
        $it = one_upload($this->data['photo'], 'id, url');
        if ($it) {
          $it['url'] = UPLOAD_URL.$it['url'];
        }
				// 返回成功
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = (string) $res;
				$this->vdata['content']['photo_info'] = $it;
			} else {
				// 返回失败
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = '';
			}
		}
		$this->_send_json($this->vdata);
	}
    // 个人中心-更改登录密码
  public function edit_pwd() {
    // 验证
    $this->form_validation->set_rules($this->rules['edit_pwd']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode'] = '0011';
      $this->vdata['returnInfo'] = $this->trim_validation_errors();
    } else {
      if ($res = $this->macc->set($this->userinfo['id'], array('pwd' => passwd($this->data['password'])))) {
        // 返回成功
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功,请重新登录';
        $this->vdata['secure'] = JSON_SECURE;
      } else {
        // 返回失败
        $this->vdata['returnCode'] = '0011';
        $this->vdata['returnInfo'] = '更新失败,请刷新后重试';
        $this->vdata['secure'] = JSON_SECURE;
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }


  //个人中心- 更新个人数据
  public function get_update()
  {
    // 验证
    $this->form_validation->set_rules($this->rules['get_update']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
     $this->vdata['returnCode']   = '0011';
     $this->vdata['returnInfo'] = validation_errors();
    } else {
      if ($this->userinfo) { //更新会员表
         if(!isset($this->data['terminalNo'])){
                $this->data['terminalNo']=0;
              }

        if(!empty($this->data['nickname'])){
           $res =  $this->macc->update(array('nickname'=>$this->data['nickname'],'uptimeline'=>time()),array('id'=>$this->userinfo['id']));
          $finfo = $this->mfri->get_all(array('suid'=>$this->userinfo['id']));
          foreach ($finfo as  $f) {
             $f_res =  $this->mfri->update(array('remarkname'=>$this->data['nickname']),array('id'=>$f['id']));
          }
        }
        if(!empty($this->data['autograph'])){
           $res =  $this->macc->update(array('autograph'=>$this->data['autograph'],'uptimeline'=>time()),array('id'=>$this->userinfo['id']));
        }
        if(!empty($this->data['province'])&&!empty($this->data['city'])){
           $res =  $this->macc->update(array('province'=>$this->data['province'],'city'=>$this->data['city'],'uptimeline'=>time()),array('id'=>$this->userinfo['id']));
        }
        if(!empty($this->data['industry'])){
           $res =  $this->macc->update(array('industry'=>$this->data['industry'],'uptimeline'=>time()),array('id'=>$this->userinfo['id']));
        }
        $info = $this->mvacc->get_info($this->userinfo['id'], $this->userInfoFields, $this->data['terminalNo']);
        $info['provincename']=get_addr($info['province']);
         $info['cityname']=get_addr($info['city']);
         $info['industryname']=get_industry($info['industry']);
         if(!empty($info['endtimeline'])&&$info['endtimeline']<time()){
	          $info['level'] = -1;
	      }
	      if(!empty($info['endtimeline'])){$info['endtimeline'] =date("Y-m-d", $info['endtimeline']);}
        //返回用户详细数据
        $this->vdata['returnCode']   = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure']     = JSON_SECURE;
        $this->vdata['content']= $info;
      } else {
        // 返回成功
       $this->vdata['returnCode']   = '200';
       $this->vdata['returnInfo'] = '请求失败';
       $this->vdata['secure']     = JSON_SECURE;
       $this->vdata['content']= array();
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
   //个人中心- 是否评价
  public function check_opinion()
  {
    // 验证
    $this->form_validation->set_rules($this->rules['check_opinion']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
     $this->vdata['returnCode']   = '0011';
     $this->vdata['returnInfo'] = validation_errors();
    } else {
      if ($this->userinfo) {
        $info = $this->maopinion->get_one(array('uid'=>$this->userinfo['id'],'puid'=>$this->data['puid']));
        if(!empty($info)){
          //返回用户详细数据
          $this->vdata['returnCode']   = '200';
          $this->vdata['returnInfo'] = '已评价';
          $this->vdata['secure']     = JSON_SECURE;
          $this->vdata['content']['res'] = 1;
        }else{
          $this->vdata['returnCode']   = '200';
          $this->vdata['returnInfo'] = '未评价';
          $this->vdata['secure']     = JSON_SECURE;
          $this->vdata['content']['res'] = 0;
        }

      } else {
        // 返回成功
       $this->vdata['returnCode']   = '200';
       $this->vdata['returnInfo'] = '请求失败';
       $this->vdata['secure']     = JSON_SECURE;
       $this->vdata['content']['res'] = -1;
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
   //个人中心- 评价
  public function add_opinion()
  {
    // 验证
    $this->form_validation->set_rules($this->rules['add_opinion']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
     $this->vdata['returnCode']   = '0011';
     $this->vdata['returnInfo'] = validation_errors();
    } else {
      if ($this->userinfo) {
        $info = $this->maopinion->get_one(array('uid'=>$this->userinfo['id'],'puid'=>$this->data['puid']));
        if(!empty($info)){
          //返回用户详细数据
          $this->vdata['returnCode']   = '200';
          $this->vdata['returnInfo'] = '已评价过该用户';
          $this->vdata['secure']     = JSON_SECURE;
        }else if($this->userinfo['id']==$this->data['puid']) {
           $this->vdata['returnCode']   = '200';
          $this->vdata['returnInfo'] = '不能评价自己';
          $this->vdata['secure']     = JSON_SECURE;
        }else{
          // 组装创建数据
            $create_data = array(
                'puid'        => $this->data['puid'],
                'uid'      => $this->userinfo['id'],
                'score'      => $this->data['score'],
                'timeline'        =>time()
            );
            if ($id = $this->maopinion->create($create_data)) {
              //计算用户评分平均值
              $puinfo = $this->maopinion->get_all(array('puid'=>$this->data['puid']));
              $usum = count($puinfo);
              $score=array(0);
              foreach ($puinfo as $key => $v) {
                $score[]=$v['score'];
              }
              $uscre=round(array_sum($score)/$usum,1);
              $up_data = array(
                'score'        => $uscre,
                'uptimeline'        =>time()//更新会员表格
              );
              $res = $this->macc->set($this->data['puid'], $up_data);
              // 返回用户详细数据
              if ($info = $this->maopinion->get_one(array('id' => $id))) {
                $this->vdata['returnCode']   = '200';
                $this->vdata['returnInfo'] = '操作成功';
                $this->vdata['secure']     = JSON_SECURE;
                $this->vdata['content']    = $info;
              } else {
                $this->vdata['returnCode']   = '200';
                $this->vdata['returnInfo'] = '操作失败';
                 $this->vdata['secure']     = JSON_SECURE;
              }
            } else {
              $this->vdata['returnCode']   = '200';
              $this->vdata['returnInfo'] = '操作失败';
               $this->vdata['secure']     = JSON_SECURE;
            }

        }

      } else {
        // 返回成功
       $this->vdata['returnCode']   = '200';
       $this->vdata['returnInfo'] = '请求失败';
       $this->vdata['secure']     = JSON_SECURE;
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }



   //个人中心- 获取token
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
       if (!isset($this->data['terminalNo'])) {
          $this->data['terminalNo'] = 0;
        }
        // 返回用户详细数据
        if ($info = $this->mvacc->get_info(array('id' => $this->data['id']), 'nofresh', $this->data['terminalNo'])) {
          $this->vdata['returnCode'] = '200';
          $this->vdata['returnInfo'] = '操作成功';
          $this->vdata['secure'] = JSON_SECURE;
          $this->vdata['content'] = $info;
        } else {
          $this->vdata['returnCode'] = '200';
          $this->vdata['returnInfo'] = '操作失败';
        }

    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }



	//////////////////////////////////////
	///////////////规则验证////////////////
	//////////////////////////////////////
	public function oldpassword_check($str) {
		if (isset($this->userinfo) && ($this->userinfo['pwd'] == passwd($str))) {
			return true;
		}
		$this->form_validation->set_message('oldpassword_check', '原密码错误！');
		return false;
	}

}
