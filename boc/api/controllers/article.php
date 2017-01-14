<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Article extends MY_Controller {
protected $rules = array(
    "info" => array(
      array(
        "field" => "id",
        "label" => "编号ID",
        "rules" => "trim|required",
      )
    ),
  );

	function __construct() {
		parent::__construct();
    $this->load->model('article_model', 'marticle');
    $this->load->model('demand_model', 'mdemand');
    $this->load->model('resource_model', 'mresource');

	}
  public function banner_get() {
    $vdata['returnCode'] = '0011';
    $vdata['returnInfo'] = '未知错误';
    $vdata['content'] = '';
    $list1=$this->marticle->get_list(4,0,'',array('audit'=>1),'id,title,click,photo,thumb,timeline');
    foreach ($list1 as $key => $v) {
      $list1[$key]['type']=1;
      $list1[$key]['url']=site_url("about/".$v['id']);
    }

   // 
    $sum=count($list1);
    if($sum<4){
       $list2=$this->mdemand->get_list(4-$sum,0,'',array('audit'=>1,'flag'=>1),'id,title,click,photo,thumb,timeline');
       foreach ($list2 as $key => $v) {
        $list2[$key]['type']=2;
       }
       $list3=$this->mresource->get_list(4-$sum,0,'',array('audit'=>1,'flag'=>1),'id,title,click,photo,thumb,timeline');
       foreach ($list3 as $key => $v) {
        $list3[$key]['type']=3;
       }

       $list=Array_merge($list1,$list2,$list3);
    }else{
      $list=$list1;
    }
    
    if(!empty($list)){
      $this->_filterList($list);
      $vdata['returnCode']   = '200';
      $vdata['returnInfo'] = '操作成功';
      $vdata['secure']     = JSON_SECURE;
      $vdata['content'] = $list;
    }
    
         // 返回json数据
    $this->_send_json($vdata);

  }
   public function banner_info() {
    // 验证
    $this->form_validation->set_rules($this->rules['info']);
    // validate验证结果
    $vdata['returnCode'] = '0011';
    $vdata['returnInfo'] = '';
    $vdata['content'] = '';
    if ($this->form_validation->run('') == false) {
      // 返回失败
      $vdata['returnCode'] = '0011';
      $vdata['returnInfo'] = validation_errors();
    } else {
      $where = array();
      $where['audit'] = 1;
      $where['id'] = $this->form_validation->set_value('id');
      if ($it = $this->marticle->get_one($where, 'id,title,click,photo,thumb,timeline,content')) {
        $this->marticle->add_click($it['id']);

        $this->_filterList($it, false);
        $vdata['returnCode'] = '200';
        $vdata['returnInfo'] = '操作成功';
        $vdata['secure'] = JSON_SECURE;
        $vdata['content'] = $it;
      } else {
        $vdata['returnCode'] = '200';
        $vdata['returnInfo'] = '操作失败';
        $vdata['content'] = array();
      }
    }
    $this->_send_json($vdata);

  }
  private function _filterList(&$target = false, $is_list = true) {
    $this->db->cache_on();
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
      if (is_null($target['content'])) {
        $target['content'] = '';
      }
      $this->_parseTimeline($target);
    }
    $this->db->cache_off();
  }
  // 时间格式化
  private function _parseTimeline(&$target = false) {
    if (isset($target['timeline']) && $target['timeline'] && $target['timeline'] > 0) {
      $target['timeline'] = date("Y-m-d", $target['timeline']);
    } else {
      $target['timeline'] = "";
    }
  }
}