<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-2-7
 * Time: 下午4:18
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Class account extends MY_Controller
 * @author xiejianwei
 * 移动端用户接口类
 */
class product extends API_Controller {

    protected $pfirst_cid = 22;
    protected $psecond_cid = 23;
    protected $pthree_cid = 24;
    protected $pfour_cid = 25;
    protected $Fields = '';
    public function __construct() {
        parent::__construct();
        $this->_auto();
        $this->load->model('product_model', 'mproduct');
        $title = $this->data['language'] == 'ZH' ? 'title' : $this->data['language'].'_title';
        $this->Fields = 'id,'.$title.','.$this->data['language'].'_content,photo,click,collection,timeline';
    }
    public function plist() {
        $first = $this->mproduct->get_all(array('cid'=>$this->pfirst_cid,'audit'=>1),'id,title');
        $this->vdata['returnCode']   = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure']     = JSON_SECURE;
        $this->vdata['content'] = $first;
        $this->_send_json($this->vdata);
    }
    public function ChildList() {
        $where = array();
        $where['audit'] = 1;
        $kw = isset($this->data['kw']) && $this->data['kw'] ? $this->data['kw'] : false;
        if ($kw) {
            $where['like title'] = array('title', $kw);
        } else {
            $id = isset($this->data['id']) && $this->data['id'] ? $this->data['id'] : false;
            if(!$id) {
                $this->_error_msg('missing_required_parameter');
            }
            $where['pid'] = $id;
        }
        // 初始化翻页
        $this->_list();
        if ($list = $this->mproduct->get_list($this->limit, $this->offset, $this->orderby, $where, $this->Fields)) {
            foreach($list as &$row) {
                $row[$this->data['language'].'_content'] = strip_tags($row[$this->data['language'].'_content']);
            }
            photo2url($list);
            $data['content'] = array_values($list);
            $data['count'] = count($list);
            //$this->mproduct->get_count_all($where);
            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure'] = JSON_SECURE;
            $this->vdata['content'] = $data;
        } else {
            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作失败';
            $this->vdata['content'] = '';
        }
        // 返回json数据
        $this->_send_json($this->vdata);
    }
    //浏览记录
    public function browse() {
        $this->load->model('browse_model', 'mbrowse');
        $data = array(
            'cid' => $this->psecond_cid,
            'uid' => $this->userinfo['id'],
            'rid' => $this->data['id'],
            'type'=> 1,
        );
        $result = $this->mbrowse->create_browse($data);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $result;
        $this->_send_json($this->vdata);
    }
    //发表评论
    public function comment() {
        $this->load->model('comment_model', 'mcomment');
        $data = array(
            'cid'       => 33,
            'content'   => $this->data['content'],
            'timeline'  => time(),
            'type'      => 1,
            'rid'       => $this->data['id'],
            'uid'       => $this->userinfo['id'],
            'rcid'      => $this->data['cid'],
        );

        $result = $this->mcomment->create($data);
        $msgs = array(
            'timeline'  =>  time(),
            'type'      =>  2,
            'uid'       =>  $this->userinfo['id'],
            'rid'       =>  $result
        );
        $this->load->model('msgs_model', 'mmsgs');
        $this->mmsgs->create($msgs);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $result;
        $this->_send_json($this->vdata);
    }
    //产品详情
    public function details() {
        $id = isset($this->data['id']) && $this->data['id'] ? $this->data['id'] : false;
        if(!$id) {
            $this->_error_msg('missing_required_parameter');
        }
        $data = $this->mproduct->get_one($this->data['id'], $this->Fields);
        $data['img_url'] = extract_img_src(stripslashes($data[$this->data['language'].'_content']));
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $data;
        $this->_send_json($this->vdata);
    }
}