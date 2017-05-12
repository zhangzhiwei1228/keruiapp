<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-5-10
 * Time: 上午11:02
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class browse extends API_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->_auto();
        $this->load->model('browse_model', 'mbrowse');
        $this->load->model('product_model', 'mproduct');
    }
    public function blist() {
        //type 1产品2视频3动态
        $type = $this->data['type'];
        switch($type) {
            case 1:
                $join = array('product','product.id=browse.rid','left');
                $title = $this->data['language'] == 'ZH' ? 'product.title' : 'product.'.$this->data['language'] . '_title';
                $Fields = 'browse.id,' . $title . ',product.photo,browse.timeline,browse.type,product.id as pid';
                break;
            case 2:
                $join = array('videos','videos.id=browse.rid','left');
                $title = $this->data['language'] == 'ZH' ? 'videos.title' : 'videos.'.$this->data['language'] . '_title';
                $Fields = 'browse.id,' . $title . ',videos.photo,browse.timeline,browse.type,product.id as pid';
                break;
            case 3:
                $join = array('news','news.id=browse.rid','left');
                $title = $this->data['language'] == 'ZH' ? 'news.title' : 'news.'.$this->data['language'] . '_title';
                $Fields = 'browse.id,' . $title . ',news.photo,browse.timeline,browse.type,product.id as pid';
                break;
        }
        $where = array('uid'=>$this->userinfo['id'],'type'=>$type);
        $orderby = array('timeline'=>'desc');
        $this->_list();
        if ($list = $this->mbrowse->get_list($this->limit, $this->offset, $orderby, $where, $Fields,false,$join)) {
            foreach($list as &$row) {
                if($row['type'] == 1) {
                    $childs = $this->mproduct->get_all(array('pid'=>$row['pid']));
                    $row['is_file'] = $childs ? 1 : 0;
                }
            }
            photo2url($list);
            //$this->mproduct->get_count_all($where);
            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure'] = JSON_SECURE;
            $this->vdata['content'] = $list;
        } else {
            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作失败';
            $this->vdata['content'] = '';
        }
        // 返回json数据
        $this->_send_json($this->vdata);
    }

}