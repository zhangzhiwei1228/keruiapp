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
        $this->Fields = 'id,' . $title . ',' . $this->data['language'] . '_content,photo,click,collection,timeline';
    }
    public function nlist() {
        $where = array();
        $where['audit'] = 1;
        $kw = isset($this->data['kw']) && $this->data['kw'] ? $this->data['kw'] : false;
        if ($kw) {
            $where['like title'] = array('title', $kw);
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
}