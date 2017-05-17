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
        $this->load->model('collection_model', 'mcollection');
        $title = $this->data['language'] == 'ZH' ? 'title' : $this->data['language'].'_title';
        $this->Fields = 'id,'.$title.','.$this->data['language'].'_content,photo,click,collection,timeline,cid,level';
    }
    public function plist() {
        $pfrist = explode(',',$this->userinfo['pfrist']);
        $psecond = explode(',',$this->userinfo['psecond']);
        $level = isset($this->data['level']) && $this->data['level'] ? $this->data['level'] : 1;
        $pid = isset($this->data['pid']) && $this->data['pid'] ? $this->data['pid'] : 0;
        $where = array();
        switch($level) {
            case 1:
                $cid = $this->pfirst_cid;
                $where['in'] = array('id',$pfrist);
                break;
            case 2:
                $cid = $this->psecond_cid;
                $where['in'] = array('id',$psecond);
                break;
            case 3:
                $cid = $this->pthree_cid;
                break;
            default:
                $cid = $this->pfirst_cid;
        }
        $where = array_merge($where,array('cid'=>$cid,'pid'=>$pid,'audit'=>1));
        $first = $this->mproduct->get_all($where,'id,title');
        //echo ($this->db->last_query());
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
            $psecond = explode(',',$this->userinfo['psecond']);
            foreach($list as &$row) {
                if($row['level'] == 2) {
                    if(!in_array($row['id'],$psecond)){unset($row);}
                } else {
                    $row[$this->data['language'].'_content'] = strip_tags($row[$this->data['language'].'_content']);
                    $col_where = array(
                        'uid'=>$this->userinfo['id'],
                        'rid'=>$row['id'],
                        'type'=>1,
                        'cid'=>$row['cid'],
                    );
                    $col = $this->mcollection->get_one($col_where);
                    $row['is_collection'] = $col ? 1: 0;
                    $four = $this->mproduct->get_all(array('pid'=>$row['id'],'audit'=>1),'id');
                    $row['package'] = array();
                    foreach($four as $val) {
                        $row['package'][] = array(
                            'id' => $val['id'],
                            'url' => SITE_URL.('app/proInfo?id='.$val['id'].'&token='.$this->data['token'].'&language='.$this->data['language']),
                        );
                    }
                    $row['package'] = array_values($row['package']);
                }


            }
            photo2url($list);
            $data = array_values($list);
            $count = $this->mproduct->get_count_all($where);
            //$this->mproduct->get_count_all($where);
            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['count'] = $count;
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
        $photo = $data['photo'] ? one_upload($data['photo']) : '';
        $data['photo'] = $photo ? UPLOAD_URL.$photo['url'] : '';
        $data['img_url'] = extract_img_src(stripslashes($data[$this->data['language'].'_content']));
        $col_where = array(
            'uid'=>$this->userinfo['id'],
            'rid'=>$data['id'],
            'type'=>1,
            'cid'=>$data['cid'],
        );
        $col = $this->mcollection->get_one($col_where);
        $data['is_collection'] = $col ? 1: 0;
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $data;
        $this->_send_json($this->vdata);
    }
}