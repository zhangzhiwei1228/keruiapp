<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-2-15
 * Time: 上午9:16
 * 我的收藏
 */
class collection extends API_Controller
{
    protected $Fields = '';
    protected $join_table = 'product';
    protected $join = array(
        'product',
        'product.id=collection.rid',
        'right'
    );

    public function __construct()
    {
        parent::__construct();
        $this->_auto();
        $this->load->model('collection_model', 'mcollection');
        $this->load->model('product_model', 'mproduct');
        switch($this->data['type']) {
            case 1:
                $this->join_table = 'product';
                break;
            case 2:
                $this->join_table = 'videos';
                break;
            case 3:
                $this->join_table = 'news';
                break;
        }
        $title = $this->data['language'] == 'ZH' ? 'title' : $this->data['language'] . '_title';
        $this->Fields = 'collection.id,collection.rid,collection.type,' . $this->join_table.'.'.$title . ',' .$this->join_table.'.photo,'.$this->join_table.'.timeline,collection.latest_time';
        $this->join = array(
            $this->join_table,
            $this->join_table.'.id=collection.rid',
            'right'
        );
    }
    public function create() {
        $data = array(
            'cid' => $this->data['cid'],
            'uid' => $this->userinfo['id'],
            'rid' => $this->data['id'],
            'type'=> $this->data['type'],
        );
        $result = $this->mcollection->create_collection($data);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $result;
        $this->_send_json($this->vdata);
    }
    public function clist() {
        $where = array();
        $where['collection.uid'] = $this->userinfo['id'];
        $where['collection.type'] = $this->data['type'];
        $where['collection.audit'] = 1;
        $where[$this->join_table.'.audit'] = 1;
        $this->_list();
        $order = array('collection.timeline'=>'desc');
        if ($list = $this->mcollection->get_list($this->limit, $this->offset, $order,$where,$this->Fields,false,$this->join)) {
            photo2url($list);
            foreach($list as &$row) {
                if($row['type'] == 1) {
                    $childs = $this->mproduct->get_all(array('pid'=>$row['rid']));
                    $row['is_file'] = $childs ? 1 : 0;
                }
                $row['is_update'] = $row['timeline'] >= $row['latest_time'] ? 1 : 0;
            }
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
    public function delete() {
        $id = explode(',',$this->data['id']);
        foreach($id as $row) {
            $result = $this->mcollection->del_collection(array('id'=>$row));
        }
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $result;
        $this->_send_json($this->vdata);
    }
    /**
     * 其他处点击取消收藏
     */
    public function update() {
        $this->_auto();
        $where = array(
            'uid'=>$this->userinfo['id'],
            'rid'=>$this->data['rid'],
            'type'=>$this->data['type'],
            'cid'=>$this->data['cid'],
        );
        $result = $this->mcollection->del_collection($where);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $result;
        $this->_send_json($this->vdata);
    }

}