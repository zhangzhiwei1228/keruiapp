<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * Class friends extends MY_Controller
 * @author lj
 * 移动端用户接口类 好友
 */
class friends extends API_Controller {

	public function __construct() {
		parent::__construct();
    $this->load->model('accout_model', 'macc');
    $this->load->model('friends_model', 'mfri');
    $this->load->model('vfriends_model', 'mvfri');
    $this->load->model('letter_model', 'mletter');
    $this->load->model('circle_member_model', 'mcircle_mer');//加入商圈
	}

	protected $rules = array(
		"add" => array(
			array(
				'field' => 'suid',
				'label' => '好友编号',
				'rules' => 'trim|required',
			),
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
		),
    "deal" => array(
      array(
        'field' => 'id',
        'label' => '消息编号',
        'rules' => 'trim|required',
      ),array(
        'field' => 'resid',
        'label' => '结果编号',
        'rules' => 'trim|required',
      ),
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
    ),
		"del" => array(
			array(
				"field" => "id",
				"label" => "编号",
				"rules" => "trim|required",
			),
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
		),
    "check" => array(
      array(
        "field" => "suid",
        "label" => "编号",
        "rules" => "trim|required",
      ),
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
    ),
    "search" => array(
      array(
        "field" => "suid",
        "label" => "编号",
        "rules" => "trim|required",
      ),
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
    )
    ,
    "list_get" => array(
      array(
        "field" => "pid",
        "label" => "商圈ID",
        "rules" => "trim",
      ),
      array(
        "field" => "timeline",
        "label" => "时间",
        "rules" => "trim|required",
      ),
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
    )
	);

	//好友-添加好友
  public function add_friend()
  {
    // 验证
    $this->form_validation->set_rules($this->rules['add']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
     $this->vdata['returnCode']   = '0011';
     $this->vdata['returnInfo'] = validation_errors();
    } else {
      if ($this->userinfo) {
        $info = $this->macc->get_one($this->userinfo['id']);
        $sinfo = $this->macc->get_one($this->data['suid']);
        $finfo = $this->mfri->get_one(array('uid'=>$this->userinfo['id'],'suid'=>$this->data['suid']));
        $finfo2 = $this->mfri->get_one(array('suid'=>$this->userinfo['id'],'uid'=>$this->data['suid']));
        $this->vdata['content']['res'] = 0;
        if(empty($info)||empty($sinfo)){
          $this->vdata['returnCode']   = '200';
          $this->vdata['returnInfo'] = '会员不存在';
          $this->vdata['secure']     = JSON_SECURE;
        }elseif($this->userinfo['id']==$this->data['suid']){
           $this->vdata['returnCode']   = '0011';
          $this->vdata['returnInfo'] = '不能添加自己为好友';
          $this->vdata['secure']     = JSON_SECURE;
        }else{
          if(!empty($finfo)||!empty($finfo2)){
            if(!empty($finfo)&&$finfo['audit']==1){
              $this->vdata['returnCode']   = '200';
              $this->vdata['returnInfo'] = '已是好友关系';
              $this->vdata['secure']     = JSON_SECURE;
            }elseif(!empty($finfo2)&&$finfo2['audit']==1){
              $this->vdata['returnCode']   = '200';
              $this->vdata['returnInfo'] = '已是好友关系';
              $this->vdata['secure']     = JSON_SECURE;
            }elseif(!empty($finfo)&&$finfo['audit']==0){
              $this->vdata['returnCode']   = '0011';
              $this->vdata['returnInfo'] = '已发送添加好友通知,等待对方同意';
              $this->vdata['secure']     = JSON_SECURE;
            }elseif(!empty($finfo2)&&$finfo2['audit']==0){
              $this->vdata['returnCode']   = '0011';
              $this->vdata['returnInfo'] = '对方已发送添加好友通知，请查看互动消息';
              $this->vdata['secure']     = JSON_SECURE;
            }else{
              $this->vdata['returnCode']   = '0011';
              $this->vdata['returnInfo'] = '已发送添加好友通知';
              $this->vdata['secure']     = JSON_SECURE;
            }

          }else{
            $create_data = array(
                'uid'      => $this->userinfo['id'],
                'uphone'      => $info['phone'],
                'suid'        => $this->data['suid'],
                'suphone'      => $sinfo['phone'],
                'remarkname'      => $sinfo['nickname'],
                'timeline'        =>time()
            );
            if ($id = $this->mfri->create($create_data)) {
              l_msg_send('申请添加好友', 1, $this->userinfo['id'],$this->data['suid']);
              //融云消息
              $fromUserId=$this->userinfo['id'];
              $toUserId=$this->data['suid'];
              $content=array('content'=>"申请添加你为好友");
              $pushContent=array('content'=>"申请添加你为好友");
              $res=send_yrmsg($fromUserId,$toUserId,json_encode(json_encode($content)), json_encode(json_encode($pushContent))) ;

              $extras=array('type'=>"1");//附加消息表示互动消息
              push_jpush((string)$sinfo['id'], $info['nickname'].' 申请加你为好友，方便分享资源，对接需求哦。','',$extras,true);//极光推送

              $this->vdata['returnCode']   = '200';
              $this->vdata['returnInfo'] = '操作成功，已发送通知';
              $this->vdata['secure']     = JSON_SECURE;

            } else {
                $this->vdata['returnCode']   = '200';
                $this->vdata['returnInfo'] = '操作失败';
                 $this->vdata['secure']     = JSON_SECURE;
              }

          }
        }
      } else {
       $this->vdata['returnCode']   = '0011';
       $this->vdata['returnInfo'] = '请求失败';
       $this->vdata['secure']     = JSON_SECURE;
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  //好友-处理通知
  public function deal_friend()
  {
    // 验证
    $this->form_validation->set_rules($this->rules['deal']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
     $this->vdata['returnCode']   = '0011';
     $this->vdata['returnInfo'] = validation_errors();
    } else {
      if ($this->userinfo) {
        $info = $this->mletter->get_one(array('audit'=> 0,'id'=>$this->data['id']));
        if(!empty($info)){
           if ($id = $this->mletter->set($this->data['id'], array('audit'=> $this->data['resid']))) {
            //account_msg_send('***同意or拒绝了你的添加好友', 1, $info['id']);
              $uinfo = $this->macc->get_one($this->userinfo['id']);
              $suinfo = $this->macc->get_one($info['uid']);
              $finfo = $this->mfri->get_one(array('suid'=>$this->userinfo['id'],'uid'=>$info['uid'],'audit !='=>1));
              if(!empty($finfo)&&$this->data['resid']==1){
               $create_data = array(
                  'uid'=> $this->userinfo['id'],
                  'uphone'=> $uinfo['phone'],
                  'suid'=> $suinfo['id'],
                  'suphone'=> $suinfo['phone'],
                  'remarkname'      => $suinfo['nickname'],
                  'audit'=>1,
                  'timeline'=>time()
              );
               $this->mfri->create($create_data);
               $this->mfri->set($finfo['id'],array('audit'=>1));
               l_msg_send('同意添加好友', 2, $this->userinfo['id'],$info['uid']);
              
                //融云消息
              $fromUserId=$this->userinfo['id'];
              $toUserId=$info['uid'];
              $content=array('content'=>"对方已添加你为好友");
              $pushContent=array('content'=>"对方已添加你为好友");
             $res=send_yrmsg($fromUserId,$toUserId,json_encode(json_encode($content)), json_encode(json_encode($pushContent))) ;
              $extras=array('type'=>"2");//附加消息表示系统消息
              push_jpush((string)$info['uid'], '你和'.$suinfo['nickname'].'已经是好友啦！恭喜你在交道上又多了一位朋友！','',$extras,true);//极光推送

               $this->vdata['returnCode']   = '200';
               $this->vdata['returnInfo'] = '操作成功,已添加好友关系';
               $this->vdata['secure']     = JSON_SECURE;
             }
             if(!empty($finfo)&&$this->data['resid']==2){
               $this->mfri->del($finfo['id']);
               l_msg_send('拒绝添加好友', 3, $this->userinfo['id'],$info['uid']);
               //融云消息
              $fromUserId=$this->userinfo['id'];
              $toUserId=$info['ruid'];
              $content=array('content'=>"对方拒绝添加你为好友");
              $pushContent=array('content'=>"对方拒绝添加你为好友");
              $res=send_yrmsg($fromUserId,$toUserId,json_encode(json_encode($content)), json_encode(json_encode($pushContent))) ;
              
              $extras=array('type'=>"2");//附加消息表示系统消息
              push_jpush((string)$info['uid'], '抱歉，'.$suinfo['nickname'].'拒绝了你的好友申请~快去发现交道上其他志同道合的朋友吧！','',$extras,true);//极光推送

               $this->vdata['returnCode']   = '200';
               $this->vdata['returnInfo'] = '操作成功,拒绝添加好友';
               $this->vdata['secure']     = JSON_SECURE;
             }
             if(empty($finfo)){
              $this->vdata['returnCode']   = '200';
              $this->vdata['returnInfo'] = '操作成功,已是好友关系';
              $this->vdata['secure']     = JSON_SECURE;
             }

           }else {
                $this->vdata['returnCode']   = '0011';
                $this->vdata['returnInfo'] = '操作失败';
                $this->vdata['secure']     = JSON_SECURE;
          }
        }else{
          $this->vdata['returnCode']   = '0011';
          $this->vdata['returnInfo'] = '操作失败';
          $this->vdata['secure']     = JSON_SECURE;
        }
      } else {
       $this->vdata['returnCode']   = '0011';
       $this->vdata['returnInfo'] = '请求失败';
       $this->vdata['secure']     = JSON_SECURE;
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }


  //好友-添加好友
  public function del_friend()
  {
    // 验证
    $this->form_validation->set_rules($this->rules['del']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
     $this->vdata['returnCode']   = '0011';
     $this->vdata['returnInfo'] = validation_errors();
    } else {
      if ($this->userinfo) {
        $finfo = $this->mfri->get_one(array('uid'=>$this->userinfo['id'],'suid'=>$this->data['id'],'audit'=>1));
        $finfo2 = $this->mfri->get_one(array('suid'=>$this->userinfo['id'],'uid'=>$this->data['id'],'audit'=>1));
        if(!empty($finfo)&&!empty($finfo2)){
           if($inid=$this->mfri->del($finfo['id'])){
              $this->vdata['returnCode']   = '200';
              $this->vdata['returnInfo'] = '操作成功';
              $this->vdata['secure']     = JSON_SECURE;
            }else{
              $this->vdata['returnCode']   = '0011';
              $this->vdata['returnInfo'] = '操作失败';
              $this->vdata['secure']     = JSON_SECURE;
            }
            if($inid=$this->mfri->del($finfo2['id'])){
              $this->vdata['returnCode']   = '200';
              $this->vdata['returnInfo'] = '操作成功';
              $this->vdata['secure']     = JSON_SECURE;
            }else{
              $this->vdata['returnCode']   = '0011';
              $this->vdata['returnInfo'] = '操作失败';
              $this->vdata['secure']     = JSON_SECURE;
            }
        }else{
          $this->vdata['returnCode']   = '0011';
          $this->vdata['returnInfo'] = '不是好友关系不能操作';
          $this->vdata['secure']     = JSON_SECURE;

        }

      } else {
       $this->vdata['returnCode']   = '0011';
       $this->vdata['returnInfo'] = '请求失败';
       $this->vdata['secure']     = JSON_SECURE;
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  //好友-添加好友
  public function check_friend()
  {
    // 验证
    $this->form_validation->set_rules($this->rules['check']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
     $this->vdata['returnCode']   = '0011';
     $this->vdata['returnInfo'] = validation_errors();
    } else {
      if ($this->userinfo) {
        $finfo = $this->mfri->get_one(array('uid'=>$this->userinfo['id'],'suid'=>$this->data['suid'],'audit'=>1));
        $finfo2 = $this->mfri->get_one(array('suid'=>$this->userinfo['id'],'uid'=>$this->data['suid'],'audit'=>1));
        if(!empty($finfo)&&!empty($finfo2)){
          $this->vdata['returnCode']   = '200';
          $this->vdata['returnInfo'] = '操作成功,好友关系';
          $this->vdata['secure']     = JSON_SECURE;
        }else{
          $this->vdata['returnCode']   = '0011';
          $this->vdata['returnInfo'] = '不是好友关系';
          $this->vdata['secure']     = JSON_SECURE;

        }

      } else {
       $this->vdata['returnCode']   = '0011';
       $this->vdata['returnInfo'] = '请求失败';
       $this->vdata['secure']     = JSON_SECURE;
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  //好友--列表
  public function list_get() {
    // 验证
    $this->form_validation->set_rules($this->rules['list_get']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode'] = '0011';
      $this->vdata['returnInfo'] = $this->trim_validation_errors();
    } else {
      $where = array('uid'=>$this->userinfo['id'],'audit'=>1,'uptimeline >='=>$this->data['timeline']);
      $list = $this->mvfri->get_all($where,'uid,suid,phone,photo,autograph,nickname,score,level,endtimeline');
      //print_r($this->db->last_query());
      foreach ($list as $key => $v) {
    	if(!empty($v['endtimeline'])&&$v['endtimeline']<time()){
		      $list[$key]['level']= -1;
		  }
		  if(!empty($v['endtimeline'])){$list[$key]['endtimeline'] =date("Y-m-d", $v['endtimeline']);}
          $list[$key]['is_circle'] = 0;
          if(isset($this->data['pid'])){
          $is_circle=$this->mcircle_mer->get_one(array('uid'=>$v['suid'],'pid'=>$this->data['pid']));
          if(!empty($is_circle)){
            $list[$key]['is_circle'] = 1;
          }
        }
      }
      // 拉取数据
      if (!empty($list)) {
        $this->_filterList($list);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content']['friends'] = $list;
        $this->vdata['content']['friends_count'] = count($list);
        $this->vdata['content']['updatetime'] = time();
      } else {
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作失败';
        $this->vdata['content']['friends'] = array();
        $this->vdata['content']['friends_count'] = 0;
        $this->vdata['content']['updatetime'] = time();
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  private function _filterList(&$target = false, $is_list = true) {
    if ($is_list && $target) {
      if (array_key_exists('photo', $target['0'])) {
        photo2url($target, 'false');
      }
      foreach ($target as $k => &$v) {
        $this->_parseTimeline($v);
      }
    } else {
      if (isset($target['photo'])) {
        photo2url($target, 'false', 'false');
      }
      if (is_null($target['tags'])) {
        $target['tags'] = '';
      }
      if (is_null($target['content'])) {
        $target['content'] = '';
      }
      $this->_parseTimeline($target);
    }
  }
  // 时间格式化
  private function _parseTimeline(&$target = false)
  {
    if (isset($target['timeline']) && $target['timeline'] && $target['timeline'] > 0) {
      $target['timeline'] = date("Y-m-d", $target['timeline']);
    } else {
      $target['timeline'] = "";
    }
  }

}

