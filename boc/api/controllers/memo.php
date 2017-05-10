<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-5-9
 * Time: 下午5:20
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class memo extends API_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->_auto();
        $this->load->model('memo_model','mmemo');
        $this->Fields = 'id, title ,content,timeline,updatetime';
    }

    /**
     * 添加备忘录
     */
    public function create() {
        $data = array(
            'title'=>$this->data['title'],
            'content'=>$this->data['content'],
            'uid'=>$this->userinfo['id'],
            'timeline'=>time(),
            'updatetime'=>time(),
        );
        $memo  = $this->mmemo->create($data);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '添加成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $memo;
        $this->_send_json($this->vdata);
    }
    /**
     * 我的备忘录列表
     */
    public function mlist() {
        $where = array();
        //$where['audit'] = 1;
        $kw = isset($this->data['kw']) && $this->data['kw'] ? $this->data['kw'] : false;
        if ($kw) {
            $where['like title'] = array('title', $kw);
        }
        $where = array('uid'=>$this->userinfo['id']);
        $orderby = array('timeline'=>'desc','updatetime'=>'desc');
        $this->_list();
        if ($list = $this->mmemo->get_list($this->limit, $this->offset, $orderby, $where, $this->Fields)) {
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
    /**
     * 备忘录修改
     */
    public function edit() {
        $data = array(
            'title'=>$this->data['title'],
            'content'=>$this->data['content'],
            'updatetime'=>time(),
        );
        $memo  = $this->mmemo->update($data,array('id'=>$this->data['id']));
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '修改成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $memo;
        $this->_send_json($this->vdata);
    }
    /**
     * 备忘录删除
     */
    public function del() {
        $memo  = $this->mmemo->del($this->data['id']);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '删除成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $memo;
        $this->_send_json($this->vdata);
    }
}