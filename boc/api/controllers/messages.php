<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * Class messages extends MY_Controller
 * @author lijie
 * 移动端用户接口类---系统消息
 */
class messages extends API_Controller {

	public function __construct() {
		parent::__construct();
    $this->load->model('letter_model', 'mletter');
    $this->load->model('vaccount_model', 'mvaccount');
    $this->load->model('circle_model', 'mcircle');//商圈表
    $this->load->model('demand_model', 'mdemand');//
    $this->load->model('resource_model', 'mresource');//
	}

	protected $rules = array(
		"rule" => array(
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
		),"rulesee" => array(
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
    )
	);

	// // 个人中心-互动消息
	public function interact() {
		// 返回服务器时间以及预定义参数
		$this->vdata['timeline'] = time();
		$this->vdata['content'] = '';
		$this->vdata['secure'] = 0;
		// 验证
		$this->form_validation->set_rules($this->rules['rule']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
      $where = array('ruid'=>$this->userinfo['id']);
       //$where = array();
      $this->db->where_in('type_id',array(1,4,8,9));
       $this->db->order_by('timeline desc');
      $list = $this->mletter->get_all($where);

      foreach ($list as $key => $v) {
        if($v['show']==0){
          $this->mletter->update(array('show'=>1),array('id'=>$v['id']));
        }
        $uinfo=$this->mvaccount->get_one($v['uid']);
        $ruinfo=$this->mvaccount->get_one($v['ruid']);

        $list[$key]['uname']='会员';
        if(!empty($uinfo)&&!empty($uinfo['nickname'])){
            $list[$key]['uname']=$uinfo['nickname'];
        }
        if($v['type_id']==1){//申请添加好友
         $list[$key]['content']=$list[$key]['uname'].'申请添加你为好友';
        }
        if($v['type_id']==4){//邀请加入商圈
         $cinfo=$this->mcircle->get_one($v['pid']);
        if(!empty($cinfo)){
           $list[$key]['ctitle']=$cinfo['title'];
           $list[$key]['content']=$list[$key]['uname'].'邀请你加入"'.$cinfo['title'].'"商圈';
        }else{
          //$list[$key]['content']='';
          $this->mletter->del(array('id'=>$v['id']));
          unset($list[$key]);
        }

        }
        if($v['type_id']==8||$v['type_id']==9){//回复 评论1商圈2资源3需求
          if($v['ctype']==3){
            $r_info=$this->mdemand->get_one($v['pid']);
            if(empty($r_info)){
              $this->mletter->del(array('id'=>$v['id']));
              unset($list[$key]);
            }
          }
           if($v['ctype']==2){
             $r_info=$this->mresource->get_one($v['pid']);
            if(empty($r_info)){
              $this->mletter->del(array('id'=>$v['id']));
              unset($list[$key]);
            }
          }

        }
        if($v['type_id']==8){//回复 评论

         $list[$key]['content']=$list[$key]['uname'].'回复了你';
        }
        if($v['type_id']==9){//评论
         $list[$key]['content']=$list[$key]['uname'].'评论了你';
        }
      }
      if(!empty($list)){
        $this->_filterList($list);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $list;
      }else{
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = array();
      }

		}
		 $this->_send_json($this->vdata);
	}
  // // 个人中心-系统消息
  public function go() {
    // 返回服务器时间以及预定义参数
    $this->vdata['timeline'] = time();
    $this->vdata['content'] = '';
    $this->vdata['secure'] = 0;
    // 验证
    $this->form_validation->set_rules($this->rules['rule']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode'] = '0011';
      $this->vdata['returnInfo'] = $this->trim_validation_errors();
    } else {
      $where = array('ruid'=>$this->userinfo['id']);
      // $where = array();
      $this->db->where_in('type_id',array(2,3,5,6,7,10,11,12));
      $this->db->order_by('timeline desc');
      $list = $this->mletter->get_all($where);
      foreach ($list as $key => $v) {
        if($v['show']==0){
          $this->mletter->update(array('show'=>1),array('id'=>$v['id']));
        }
        $uinfo=$this->mvaccount->get_one($v['uid']);
        $ruinfo=$this->mvaccount->get_one($v['ruid']);
        $cinfo=$this->mcircle->get_one($v['pid']);

        $list[$key]['uname']='会员';
        $list[$key]['cname']='';
        if(!empty($uinfo)&&!empty($uinfo['nickname'])){
            $list[$key]['uname']=$uinfo['nickname'];
        }
        if(!empty($cinfo)&&!empty($cinfo['title'])){
            $list[$key]['cname']=$cinfo['title'];
        }
        /*if($v['type_id']==7||$v['type_id']==10||$v['type_id']==11||$v['type_id']==12){
         $list[$key]['content']=$v['title'];
        }else{
          $list[$key]['content']=$list[$key]['uname'].$v['title'];
        }*/
        if($v['type_id']==2){$list[$key]['content']="你和".$list[$key]['uname']."已经是好友啦！恭喜你在交道上又多了一位朋友！";}
        if($v['type_id']==3){$list[$key]['content']="抱歉，".$list[$key]['uname']."拒绝了你的好友申请~快去发现交道上其他志同道合的朋友吧！";}
        if($v['type_id']==5){$list[$key]['content']="恭喜您成功邀请".$list[$key]['uname'].",".$list[$key]['cname']."商圈又多了一员！";}
        if($v['type_id']==6){$list[$key]['content']="抱歉，".$list[$key]['uname']."暂时拒绝了成为".$list[$key]['cname']."商圈的一员！";}
        if($v['type_id']==7){$list[$key]['content']="感谢您注册交道，成为这里的一员。从今天起，你将发现许多可以帮到你的资源，也期待你分享自己的资源，予便他人。";}
        if($v['type_id']==10){$list[$key]['content']="感谢您购买交道会员，从此享受更多权益，快来体验一下吧！";}
        if($v['type_id']==11){$list[$key]['content']="您的会员即将到期，为了在交道上可以更好的获取资源、发布需求，建议您再次购买会员哦！";}
        if($v['type_id']==12){$list[$key]['content']="为感谢广大交道朋友的厚爱，特此推出购买会员优惠活动，用更低的价格享更多的权益。还等什么，快来体验吧！";}
      }
      if(!empty($list)){
        $this->_filterList($list);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $list;
      }else{
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = array();
      }
    }
    $this->_send_json($this->vdata);
  }

  // // 个人中心-消息数量
  public function getnum() {
    // 返回服务器时间以及预定义参数
    $this->vdata['timeline'] = time();
    $this->vdata['content'] = '';
    $this->vdata['secure'] = 0;
    // 验证
    $this->form_validation->set_rules($this->rules['rule']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode'] = '0011';
      $this->vdata['returnInfo'] = $this->trim_validation_errors();
    } else {
       $where = array('ruid'=>$this->userinfo['id'],'show'=>0);
      $this->db->where_in('type_id',array(1,4,8,9));
       $this->db->order_by('timeline desc');
      $list1 = $this->mletter->get_count_all($where);

      $where = array('ruid'=>$this->userinfo['id'],'show'=>0);
      $this->db->where_in('type_id',array(2,3,5,6,7,10,11,12));
      $this->db->order_by('timeline desc');
      $list2 = $this->mletter->get_count_all($where);

      $res['hd']=0;
      $res['xt']=0;
      if(!empty($list1)){$res['hd']=$list1;}
      if(!empty($list2)){$res['xt']=$list2;}
    
       $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $res;
    }
    $this->_send_json($this->vdata);
  }
  // // 个人中心-消息查看
  public function getsee() {
    // 返回服务器时间以及预定义参数
    $this->vdata['timeline'] = time();
    $this->vdata['content'] = '';
    $this->vdata['secure'] = 0;
    // 验证
    $this->form_validation->set_rules($this->rules['rulesee']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode'] = '0011';
      $this->vdata['returnInfo'] = $this->trim_validation_errors();
    } else {
      $info=$this->mletter->get_one(array('id'=>$this->data['id'],'audit'=>0));
      if(!empty($info)&&$this->userinfo['id']==$info['ruid'])
      {
        if($info['type_id']==8||$info['type_id']==9){
          $this->mletter->update(array('audit'=>1),array('id'=>$info['id']));
          $this->vdata['returnCode'] = '200';
          $this->vdata['returnInfo'] = '操作成功';
          $this->vdata['secure'] = JSON_SECURE;
        }else{
          $this->vdata['returnCode'] = '200';
          $this->vdata['returnInfo'] = '无权查看';
          $this->vdata['secure'] = JSON_SECURE;
        }
      }else{
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '无权查看';
        $this->vdata['secure'] = JSON_SECURE;
      }       
    }
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

  private function _parseTimeline(&$target = false) {
    if (isset($target['timeline']) && $target['timeline'] && $target['timeline'] > 0) {
      $target['timeline'] = date("Y-m-d H:i", $target['timeline']);
    } else {
      $target['timeline'] = "";
    }
  }

}
