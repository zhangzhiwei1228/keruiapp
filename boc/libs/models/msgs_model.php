<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Msg_model extends CI_Model 
 * 消息提醒
 * @author era
 */
class Msgs_model extends MY_Model
{
	protected $table = 'msgs';
	public function create_array($data) {
		$this->db->insert_batch($this->table, $data);
		return $this->db->affected_rows();
	}
	public function get_list($limit=5,$start=0,$order=false,$where=false){
		$this->db
			->select('msgs.id,msgs.is_read.msgs.timeline,msg.content,account.nickname')
			->from('msgs')
			->join('account', 'account.id = msgs.uid', 'left')
			->join('msg', 'msg.id = msgs.rid', 'left')
			->limit($limit,$start)
			->where($where)
			->order_by('msgs.timeline','desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	// 标记 阅读等状态
	public function mark($where)
	{
		$this->db->update('msgs',array('is_read'=>1),$where);
		return $this->db->affected_rows();	
	}

	// 未读数据的数量
	public function get_num($mid){
		$num = $this->db->select('count(id) as num')->from('msgs')->where( array('is_read'=>0,'uid'=>$mid))->get()->row_array();
		return $num['num'];
	}

}

