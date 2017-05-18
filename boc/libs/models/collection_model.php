<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-1-21
 * Time: 下午4:10
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Collection_model extends MY_Model {

    protected $table = 'collection';
    public function create_collection($data) {
        $data['timeline'] = time();
        $data['latest_time'] = time();
        $where = array('uid'=>$data['uid'],'rid'=>$data['rid'],'cid'=>$data['cid'],'type'=>$data['type']);
        $query = $this->db
            ->select('id,audit')
            ->from($this->table)
            ->where($where)
            ->get();
        $result = $query->row_array();
        if(!$result) {
            $this->db->insert($this->table, $data);
            if ( $insert = $this->db->affected_rows()) {
                //1产品 2视频 3动态
                switch($data['type']) {
                    case 1:
                        $this->db->set('collection','collection+1',false)
                            -> where(array('id'=>$data['rid']))
                            -> update('product');
                        break;
                    case 2:
                        $this->db->set('collection','collection+1',false)
                            -> where(array('id'=>$data['rid']))
                            -> update('videos');
                        break;
                    case 3:
                        $this->db->set('collection','collection+1',false)
                            -> where(array('id'=>$data['rid']))
                            -> update('news');
                        break;
                }
                return $insert;
            }
        } else {
            $audit = $result['audit'] ? $result['audit'] : 0;
            $this->db->set(array('timeline' => time(),'audit' => $audit))
                -> where($where)
                -> update($this->table);
            return $this->db->affected_rows();
        }
    }
    public function del_collection($data) {
        if(isset($data['id']) && $data['id']) {
            $query = $this->db
                ->select('id,type')
                ->from($this->table)
                ->where(array('id'=>$data['id']))
                ->get();
            $result = $query->row_array();
        } else {
            $where = array('uid'=>$data['uid'],'rid'=>$data['rid'],'cid'=>$data['cid'],'type'=>$data['type']);
            $query = $this->db
                ->select('id,type')
                ->from($this->table)
                ->where($where)
                ->get();
            $result = $query->row_array();
        }

        if($result) {
            $this->db->where(array('id'=>$result['id']));
            $this->db->delete('collection');
            $data['type'] = $result['type'];
            //1产品 2视频 3动态
            switch($data['type']) {
                case 1:
                    $this->db->set('collection','collection-1',false)
                        -> where(array('id'=>$data['rid']))
                        -> update('product');
                    break;
                case 2:
                    $this->db->set('collection','collection-1',false)
                        -> where(array('id'=>$data['rid']))
                        -> update('videos');
                    break;
                case 3:
                    $this->db->set('collection','collection-1',false)
                        -> where(array('id'=>$data['rid']))
                        -> update('news');
                    break;
            }
        }
        return $result;
    }
}