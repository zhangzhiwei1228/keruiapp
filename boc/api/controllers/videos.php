<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-2-13
 * Time: 下午3:48
 */
class videos extends API_Controller
{

    protected $cid = 26;
    protected $fcid = 29;
    protected $Fields = '';

    public function __construct()
    {
        parent::__construct();
        $this->_auto();
        $this->load->model('videos_model', 'mvideos');
        $this->load->model('videosclass_model', 'mvideosclass');
        $title = $this->data['language'] == 'ZH' ? 'title' : $this->data['language'] . '_title';
        $content = $this->data['language'] . '_content';
        $this->Fields = 'id,' . $title . ',' . $content . ',photo,click,collection,timeline,files';
    }
    public function ListClass() {
        $first = $this->mvideosclass->get_all(array('cid'=>$this->fcid,'audit'=>1),'id,title');
        $this->vdata['returnCode']   = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure']     = JSON_SECURE;
        $this->vdata['content'] = $first;
        $this->_send_json($this->vdata);
    }
    public function ChildList() {
        $where = array();
        $where['audit'] = 1;
        $where['cid'] = $this->cid;
        $kw = isset($this->data['kw']) && $this->data['kw'] ? $this->data['kw'] : false;
        if ($kw) {
            $where['like title'] = array('title', $kw);
        } else {
            $id = isset($this->data['id']) && $this->data['id'] ? $this->data['id'] : false;
            if(!$id) {
                $this->_error_msg('missing_required_parameter');
            }
            $where['vid'] = $id;
        }
        // 初始化翻页
        $this->_list();
        if ($list = $this->mvideos->get_list($this->limit, $this->offset, $this->orderby, $where, $this->Fields)) {
            foreach($list as &$row) {
                $row[$this->data['language'].'_content'] = strip_tags($row[$this->data['language'].'_content']);
            }
            photo2url($list);
            photo2url($list,'false', 'true', 'files');
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
    //浏览记录
    public function browse() {
        $this->load->model('browse_model', 'mbrowse');
        $data = array(
            'cid' => $this->cid,
            'uid' => $this->userinfo['id'],
            'rid' => $this->data['id'],
            'type'=> 2,
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
            'type'      => 2,
            'rid'       => $this->data['id'],
            'uid'       => $this->userinfo['id'],
            'rcid'      => $this->data['cid'],
        );
        $result = $this->mcomment->create($data);
        $msgs = array(
            'timeline' => time(),
            'type'  =>  2,
            'uid'   =>  $this->userinfo['id'],
            'rid'   =>  $result
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