<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
/**
 * Class circle extends MY_Controller
 * @author lijie
 * 移动端用户接口类   商圈---圈子
 */
class circle_mer extends API_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('account_model', 'macc');
    $this->load->model('circle_member_model', 'mcircle_mer');
    $this->load->model('vcircle_member_model', 'mvcircle_mer');
    $this->load->model('letter_model', 'mletter');
     $this->load->model('circle_model', 'mcircle');//商圈表
     $this->load->model('coltypes_model', 'mctypes');
    $this->load->model('district_model', 'mdistrict');
    $this->load->model('friends_model', 'mfriends');
  }

  protected $rules = array(
    "add" => array(
      array(
        'field' => 'pid',
        'label' => '商圈编号',
        'rules' => 'trim|required',
      ),
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
    ),
    "invite" => array(
    array(
        'field' => 'uids',
        'label' => '会员ID',
        'rules' => 'trim|required',
      ),
      array(
        'field' => 'pid',
        'label' => '商圈编号',
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
        "field" => "uid",
        "label" => "会员编号",
        "rules" => "trim|required",
      ),
      array(
        'field' => 'pid',
        'label' => '商圈编号',
        'rules' => 'trim|required',
      ),
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
    ),
    "list_get" => array(
      array(
        "field" => "pid",
        "label" => "商圈编号",
        "rules" => "trim|required",
      ),array(
        "field" => "limit",
        "label" => "个数",
        "rules" => "trim",
      ),
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
    ),
    "list_other_get" => array(
      array(
        "field" => "aid",
        "label" => "加入人ID",
        "rules" => "trim|required",
      ),
      array(
        "field" => "page",
        "label" => "页码",
        "rules" => "trim|is_numeric",
      ),
      array(
        "field" => "limit",
        "label" => "每页数量",
        "rules" => "trim|is_numeric",
      )
    )
  );
//商圈-添加商圈
  public function add_circle()
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
        $cinfo = $this->mcircle_mer->get_one(array('uid'=>$this->userinfo['id'],'pid'=>$this->data['pid']));
        $sinfo = $this->mcircle->get_one(array('id'=>$this->data['pid']));
        if(!empty($cinfo)){
          $this->vdata['returnCode']   = '200';
          $this->vdata['returnInfo'] = '已加入该商圈';
          $this->vdata['secure']     = JSON_SECURE;
        }else{
          if(!empty($sinfo)){
           $create_data = array(
                'uid'      => $this->userinfo['id'],
                'cuid'        => $sinfo['aid'],
                'pid'      => $this->data['pid'],
                'timeline'        =>time()
            );
            if ($id = $this->mcircle_mer->create($create_data)) {
               //添加一个圈友
              $this->mcircle->update(array('member_count'=>$sinfo['member_count']+1),array('id'=>$sinfo['id']));

              rygroup_join(array($this->userinfo['id']),$sinfo['id'],$sinfo['title']);//ry_join
              $this->vdata['returnCode']   = '200';
              $this->vdata['returnInfo'] = '操作成功';
              $this->vdata['secure']     = JSON_SECURE;

            } else {
                $this->vdata['returnCode']   = '0011';
                $this->vdata['returnInfo'] = '操作失败，请刷新后重试';
                 $this->vdata['secure']     = JSON_SECURE;
            }
          }else {
              $this->vdata['returnCode']   = '0011';
              $this->vdata['returnInfo'] = '操作失败，请刷新后重试';
              $this->vdata['secure']     = JSON_SECURE;
          }
        }
      } else {
       $this->vdata['returnCode']   = '0011';
       $this->vdata['returnInfo'] = '请求失败,请刷新后重试';
       $this->vdata['secure']     = JSON_SECURE;
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  //邀请商圈加入
  public function invite_circle()
  {
    // 验证
    $this->form_validation->set_rules($this->rules['invite']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
     $this->vdata['returnCode']   = '0011';
     $this->vdata['returnInfo'] = validation_errors();
    } else {
      if ($this->userinfo) {
        $info = $this->macc->get_one($this->userinfo['id']);
        $uids=explode(',', $this->data['uids']);
        if(!empty($uids)){
          foreach ($uids as $key => $v) {
            $cinfo = $this->mcircle_mer->get_one(array('uid'=>$v,'pid'=>$this->data['pid']));//$this->userinfo['id']
            $yinfo = $this->macc->get_one($v);
            $sinfo = $this->mcircle->get_one(array('id'=>$this->data['pid']));
            $error=-1;$errorname=""; $yqname="";
            if(!empty($cinfo)){$error=1;$errorname="已经加入该商圈";}
            if($this->userinfo['id']==$v){$error=1;$errorname="不需要邀请自己";}
            if($sinfo['aid']==$v){$error=1;$errorname="不需要邀请圈主";}
            if(!empty($sinfo)&&$error==-1){
                 l_msg_send('邀请商圈加入', 4, $this->userinfo['id'],$v,1,$sinfo['aid'],$this->data['pid']);
                 $yqname.=$yinfo['phone']." ";
                 $extras=array('type'=>"1");//附加消息表示互动消息
                 push_jpush((string)$yinfo['id'], $info['nickname'].' 发现了一个靠谱的商圈，邀请你加入。','',$extras,true);//极光推送
            }
          }
          $this->vdata['returnCode']   = '200';
          $this->vdata['returnInfo'] = '已经成功发送邀请，等待对方接受';//'已经成功邀请用户:'.$yqname;
          $this->vdata['secure']     = JSON_SECURE;

          /*if(!empty($yqname)){
            $this->vdata['returnCode']   = '200';
            $this->vdata['returnInfo'] = '已经成功发送邀请，等待对方接受';//'已经成功邀请用户:'.$yqname;
            $this->vdata['secure']     = JSON_SECURE;
          }else{
            $this->vdata['returnCode']   = '200';
            $this->vdata['returnInfo'] = '邀请失败';
            $this->vdata['secure']     = JSON_SECURE;
          }*/
        }else{
          $this->vdata['returnCode']   = '0011';
          $this->vdata['returnInfo'] = '没有选择好友';
          $this->vdata['secure']     = JSON_SECURE;
        }
      } else {
       $this->vdata['returnCode']   = '0011';
       $this->vdata['returnInfo'] = '请求失败,请刷新后重试';
       $this->vdata['secure']     = JSON_SECURE;
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  //邀请商圈-处理通知
  public function deal_circle()
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
        $info = $this->mletter->get_one(array('audit'=> 0,'id'=>$this->data['id'],'ruid'=>$this->userinfo['id']));
        if(!empty($info)){
           if ($id = $this->mletter->set($this->data['id'], array('audit'=> $this->data['resid']))) {
              $cinfo = $this->mcircle_mer->get_one(array('uid'=>$this->userinfo['id'],'pid'=>$info['pid']));
              $sinfo = $this->mcircle->get_one(array('id'=>$info['pid']));

              $uinfo=$this->macc->get_one($info['uid']);
               if(empty($cinfo)){
                if($this->data['resid']==1){
                   $create_data = array(
                      'uid'      => $this->userinfo['id'],
                      'cuid'        => $sinfo['aid'],
                      'pid'      => $sinfo['id'],
                      'timeline'        =>time()
                  );
                   $this->mcircle_mer->create($create_data);
                   //添加一个圈友
                   $this->mcircle->update(array('member_count'=>$sinfo['member_count']+1),array('id'=>$sinfo['id']));
                   l_msg_send('同意加入商圈', 5, $this->userinfo['id'],$info['uid'],0,0,$info['pid']);
                   rygroup_join(array($this->userinfo['id']),$sinfo['id'],$sinfo['title']);//ry_join
                   $extras=array('type'=>"2");//附加消息表示系统消息
                   push_jpush((string)$uinfo['id'], '恭喜您成功邀请'.$uinfo['nickname'].'，'.$sinfo['title'].'商圈又多了一员！','',$extras,true);//极光推送
                   $this->vdata['returnCode']   = '200';
                   $this->vdata['returnInfo'] = '操作成功';
                   $this->vdata['secure']     = JSON_SECURE;
                 }
                 if($this->data['resid']==2){
                   l_msg_send('拒绝加入商圈', 6, $this->userinfo['id'],$info['uid'],0,0,$info['pid']);
                   $extras=array('type'=>"2");//附加消息表示系统消息
                   push_jpush((string)$uinfo['id'], '抱歉，'.$uinfo['nickname'].'暂时拒绝了成为'.$sinfo['title'].'商圈的一员！','',$extras,true);//极光推送
                   $this->vdata['returnCode']   = '200';
                   $this->vdata['returnInfo'] = '操作成功';
                   $this->vdata['secure']     = JSON_SECURE;
                 }
               }else{
                   l_msg_send('该好友已加入商圈', 5, $this->userinfo['id'],$info['uid'],0,0,$info['pid']);
                   $this->vdata['returnCode']   = '200';
                   $this->vdata['returnInfo'] = '操作成功';
                   $this->vdata['secure']     = JSON_SECURE;
               }

          }else {
                $this->vdata['returnCode']   = '0011';
                $this->vdata['returnInfo'] = '操作失败，请刷新后重试';
                $this->vdata['secure']     = JSON_SECURE;
          }
        }else{
          $this->vdata['returnCode']   = '0011';
          $this->vdata['returnInfo'] = '操作失败，请刷新后重试';
          $this->vdata['secure']     = JSON_SECURE;
        }
      } else {
       $this->vdata['returnCode']   = '0011';
       $this->vdata['returnInfo'] = '请求失败,请刷新后重试';
       $this->vdata['secure']     = JSON_SECURE;
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }


  //商圈-圈主删除成员
  public function del_circle()
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
         $uids=explode(',', $this->data['uid']);
         $res=0;
        if(!empty($uids)){
          foreach ($uids as $key => $v) {
            $cinfo = $this->mcircle_mer->get_one(array('pid'=>$this->data['pid'],'uid'=>$v));
             $sinfo = $this->mcircle->get_one(array('id'=>$this->data['pid']));
            if(!empty($cinfo)){
              if($cinfo['cuid']==$this->userinfo['id']||$this->userinfo['id']==$cinfo['uid']){
               if($inid=$this->mcircle_mer->del(array('id'=>$cinfo['id']))){
                //删除一个圈友
                 $this->mcircle->update(array('member_count'=>$sinfo['member_count']-1),array('id'=>$sinfo['id']));
                 rygroup_quit(array($cinfo['uid']),$sinfo['id'],$sinfo['title']);
                 $res++;
                  $this->vdata['returnCode']   = '200';
                  $this->vdata['returnInfo'] = '操作成功';
                  $this->vdata['secure']     = JSON_SECURE;
                }
              }
            }
          }
          if($res>0){
            $this->vdata['returnCode']   = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure']     = JSON_SECURE;
          }else{
            $this->vdata['returnCode']   = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure']     = JSON_SECURE;
          }

        }else{
          $this->vdata['returnCode']   = '0011';
         $this->vdata['returnInfo'] = '请注意传参';
         $this->vdata['secure']     = JSON_SECURE;
        }
      } else {
       $this->vdata['returnCode']   = '0011';
       $this->vdata['returnInfo'] = '请求失败,请刷新后重试';
       $this->vdata['secure']     = JSON_SECURE;
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }

  //商圈成员--列表
  public function list_get() {
    // 验证
    $this->form_validation->set_rules($this->rules['list_get']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode'] = '0011';
      $this->vdata['returnInfo'] = $this->trim_validation_errors();
    } else {
       if(!isset($this->data['limit'])){
          $this->data['limit']=-1;
        }
        $cinfo=$this->mcircle->get_one($this->data['pid']);
        if(!empty($cinfo)){
           $list_creat=$this->macc->get_one($cinfo['aid']);
        }
        //0普通会员1标准会员2高级会员
        if(!empty($list_creat)){//圈主
          $list[0]['aid']=$list_creat['id'];
          $list[0]['phone']=$list_creat['phone'];
          $list[0]['photo']=$list_creat['photo'];
          $list[0]['autograph']=$list_creat['autograph'];
          $list[0]['nickname']=$list_creat['nickname'];
          $list[0]['level']=$list_creat['level'];
          if(!empty($list_creat['endtimeline'])&&$list_creat['endtimeline']<time()){
		      $list[0]['level']= -1;
		  }
		  if(!empty($list[0]['endtimeline'])){$list[0]['endtimeline'] =date("Y-m-d", $list[0]['endtimeline']);}
          $list[0]['score']=round($list_creat['score']);
          $list[0]['qz']=1;
      }

      if(!empty($this->data['limit'])&&$this->data['limit']>0){
         $where = array('pid'=>$this->data['pid']);
         $list_list = $this->mvcircle_mer->get_list($this->data['limit']-1,0,'',$where);
      }else{
         $where = array('pid'=>$this->data['pid']);
        $list_list = $this->mvcircle_mer->get_all($where);
      }

      if(!empty($list_list)){
        foreach ($list_list as $key => $v) {
          $list[$key+1]['aid']=$v['aid'];
          $list[$key+1]['phone']=$v['phone'];
          $list[$key+1]['photo']=$v['photo'];
          $list[$key+1]['autograph']=$v['autograph'];
          $list[$key+1]['nickname']=$v['nickname'];
          $list[$key+1]['level']=$v['level'];
          if(!empty($v['endtimeline'])&&$v['endtimeline']<time()){
		      $list[$key+1]['level']= -1;
		  }
		  if(!empty($v['endtimeline'])){$list[$key+1]['endtimeline'] =date("Y-m-d", $v['endtimeline']);}
          $list[$key+1]['score']=round($v['score']);
          $list[$key+1]['qz']=0;
        }
      }
      // 拉取数据
      if (!empty($list)) {
        $this->_filterList($list);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $list;
      } else {
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作失败';
        $this->vdata['content'] = array();
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  //商圈成员--他加入的
  public function list_other_get() {
    // 验证
    $this->form_validation->set_rules($this->rules['list_other_get']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode'] = '0011';
      $this->vdata['returnInfo'] = $this->trim_validation_errors();
    } else {
      $where = array('uid'=>$this->data['aid']);
      $listall = $this->mvcircle_mer->get_all($where);
      foreach ($listall as $key => $v) {
        $circle=$this->mcircle->get_one($v['pid']);
        if(empty($circle)){
          $this->mcircle_mer->del(array('id'=>$v['id']));
        }
      }

      if(!isset($this->data['page'])){
        $page=1;
      }else{
        $page=$this->data['page'];
      }
      if(!isset($this->data['limit'])){
        $limit=6;
      }else{
        $limit=$this->data['limit'];
      }

      $offset = $limit * $page-$limit;
      $list = $this->mvcircle_mer->get_list($limit,$offset,'',$where);
      //$list_sq=array();
      foreach ($list as $key => $v) {
        $circle=$this->mcircle->get_one($v['pid']);
        $list_sq[]=$circle;
      }

      // 拉取数据
      if (!empty($list_sq)) {
        $this->_filter_oList($list_sq);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content']['circles'] = $list_sq;
      } else {
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作失败';
        $this->vdata['content']['circles'] = array();
      }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  private function _filter_oList(&$target = false, $is_list = true) {
    if ($is_list && $target) {
      if (array_key_exists('photo', $target['0'])) {
        photo2url($target, 'false');
      }
      foreach ($target as $k => &$v) {
        $this->_parseTimeline($v);
        $this->_parseDistrictIds($v);
        $this->_parseIndustry($v);
        $this->_parseAidInfo($v);
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
      $this->_parseDistrictIds($target);
      $this->_parseIndustry($target);
    }
  }
  // 获取商圈用户信息
  private function _parseDistrictIds(&$target = false) {
    if (isset($target['province'])) {
      if ($info = $this->mdistrict->get_one($target['province'])) {
        $target['province_title'] = $info['name'] . $info['suffix'];
      } else {
        $target['province_title'] = "";
      }
    }
    if (isset($target['city'])) {
      if ($info = $this->mdistrict->get_one($target['city'])) {
        $target['city_title'] = $info['name'] . $info['suffix'];
      } else {
        $target['city_title'] = "";
      }
    }
    if (isset($target['district'])) {
      if ($info = $this->mdistrict->get_one($target['district'])) {
        $target['district_title'] = $info['name'] . $info['suffix'];
      } else {
        $target['district_title'] = "";
      }
    }
  }
  // 获取商圈用户信息
  private function _parseIndustry(&$target = false) {
    if (isset($target['ctype'])) {
      if ($info = $this->mctypes->get_one($target['ctype'])) {
        $target['ctype_title'] = $info['title'];
      } else {
        $target['ctype_title'] = "";
      }
    }
  }
  // 获取商圈用户信息
  private function _parseAidInfo(&$target = false, $field = 'aid') {
     if (isset($target[$field])) {
       if ($aidInfo = $this->macc->get_one(array('id' => $target[$field]), 'id, photo, nickname, create_time as timeline, level, endtimeline')) {
        if (isset($this->userinfo) && $this->userinfo && ($friend_info = $this->mfriends->get_one(array('audit'=>1, 'uid'=>$this->userinfo['id'], 'suid'=>$target[$field]), 'id, remarkname'))) {
          $aidInfo['nickname'] = $friend_info['remarkname'];
        }
         if(!empty($aidInfo['endtimeline'])&&$aidInfo['endtimeline']<time()){
		      $aidInfo['level']= -1;
		  }
		  if(!empty($aidInfo['endtimeline'])){$aidInfo['endtimeline'] =date("Y-m-d", $aidInfo['endtimeline']);}
        if (!empty($aidInfo['photo'])) {
          photo2url($aidInfo, 'false', 'false');
        }else{
          $aidInfo['photo']=null;
        }
        $this->_parseTimeline($aidInfo);
        if ($aidLevel = $this->mctypes->get_one(array('id' => $aidInfo['level'], 'name' => 'level'), 'id, title, identify')) {
          $aidInfo['level_title'] = $aidLevel['title'];
          $aidInfo['level_identify'] = $aidLevel['identify'];
        } else {
          $aidInfo['level_title'] = "普通会员";
          $aidInfo['level_identify'] = "0";
        }
        $target[$field . '_info'] = $aidInfo;
        $this->aid_info_cache[$target[$field]] = $aidInfo;
      } else {
        $target[$field . '_info'] = null;
      }
    }
  }
   private function _filterList(&$target = false, $is_list=true)
  {
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
