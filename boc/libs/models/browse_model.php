<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-1-21
 * Time: 下午4:11
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Browse_model extends MY_Model {

    protected $table = 'browse';
    public function create_browse($data) {

        $data['timeline'] = time();
        $query = $this->db
            ->select('id')
            ->from($this->table)
            ->where(array('uid'=>$data['uid'],'rid'=>$data['rid'],'type'=>$data['type']))
            ->get();
        $result = $query->row_array();
        if(!$result) {
            $this->db->insert($this->table, $data);
            if ( $insert = $this->db->affected_rows()) {
                //1产品 2视频 3动态
                switch($data['type']) {
                    case 1:
                        $this->db->set('click','click+1',false)
                            -> where(array('id'=>$data['rid']))
                            -> update('product');
                        break;
                    case 2:
                        $this->db->set('click','click+1',false)
                            -> where(array('id'=>$data['rid']))
                            -> update('videos');
                        break;
                    case 3:
                        $this->db->set('click','click+1',false)
                            -> where(array('id'=>$data['rid']))
                            -> update('news');
                        break;
                }
                return $insert;
            }
        } else {
            $this->db->set(array('timeline'=>time()))
                -> where(array('uid'=>$data['uid'],'rid'=>$data['rid'],'type'=>$data['type']))
                -> update($this->table);
            return $this->db->affected_rows();
        }
    }
}