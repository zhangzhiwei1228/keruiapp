<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-2-16
 * Time: 下午2:48
 */
class notice extends API_Controller
{
    protected $cid = 30;
    protected $Fields = '';

    public function __construct()
    {
        parent::__construct();
        $this->_auto();
        $this->load->model('msgs_model','mmsgs');
    }
    public function nlist() {
        $where = array();
        $where['msgs.uid'] = $this->userinfo['id'];
        $where['msgs.type'] = $this->data['type'];
        $this->_list();
        if ($list = $this->mmsgs->get_list($this->limit, $this->offset, $this->orderby, $where, $this->Fields)) {
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