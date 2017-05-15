<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-2-14
 * Time: 上午9:39
 */
class news extends API_Controller
{
    protected $cid = 30;
    protected $Fields = '';

    public function __construct()
    {
        parent::__construct();
        $this->_auto();
        $this->load->model('news_model', 'mnews');
        $title = $this->data['language'] == 'ZH' ? 'title' : $this->data['language'] . '_title';
        $this->Fields = 'id,' . $title . ',photo,timeline,cid';
    }
    public function plist() {
        $title = $this->data['language'] == 'ZH' ? 'title' : $this->data['language'] . '_title';
        $plist = $this->mnews->get_all(array('cid'=>34,'audit'=>1),'id,'.$title);
        $this->vdata['returnCode']   = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure']     = JSON_SECURE;
        $this->vdata['content'] = $plist;
        $this->_send_json($this->vdata);
    }
    public function nlist() {
        $where = array();
        $where['audit'] = 1;

        $kw = isset($this->data['kw']) && $this->data['kw'] ? $this->data['kw'] : false;
        if ($kw) {
            $where['like title'] = array('title', $kw);
        } else {
            $where['ctype'] = $this->data['ctype'];
        }
        $this->_list();
        if ($list = $this->mnews->get_list($this->limit, $this->offset, $this->orderby, $where, $this->Fields)) {
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
    public function browse() {
        $this->load->model('browse_model', 'mbrowse');
        $data = array(
            'cid' => $this->cid,
            'uid' => $this->userinfo['id'],
            'rid' => $this->data['id'],
            'type'=> 3,
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
            'type'      => 3,
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
}