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
        $this->Fields = 'id,'.$title.','.$this->data['language'].'_content,photo,thumb,click,collection,timeline';
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