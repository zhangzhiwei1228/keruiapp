<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_model extends MY_Model {

	protected $table = 'comment';
	public function get_list($limit=5,$start=0,$order=false,$where=false){
		$this->db
			->select('comment.*,account.nickname')
			->from($this->table)
			->join('account', 'account.id = comment.uid', 'left')
			->limit($limit,$start)
			->where($where)
			->order_by('comment.timeline','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		foreach($result as &$row) {
			//1产品2视频3动态
			switch($row['type']) {
				case 1:
					$product = $this->db->select('id,title,photo,thumb')->from('product')->where(array('id'=>$row['rid']))->get();
					break;
				case 2:
					$product = $this->db->select('id,title,photo,thumb')->from('videos')->where(array('id'=>$row['rid']))->get();
					break;
				case 3:
					$product = $this->db->select('id,title,photo,thumb')->from('news')->where(array('id'=>$row['rid']))->get();
					break;
			}
			$rdata = $product->row_array();
			$row['title'] = $rdata['title'];
			$row['thumb'] = $rdata['thumb'];
			$row['photo'] = $rdata['photo'];
		}
		return $result;
	}
	public function get_one($where,$fields = "*",$table=FALSE){
		if (!$table) {
			$table = $this->table;
		}
		if (!$where) {
			return FALSE;
		}
		$this->db->select($fields)->from($table);
		if (!is_numeric($where)) {
			$this->db->where($where);
		}else{
			$this->db->where('id',$where);
		}
		if ($this->db->field_exists('sort_id',$table)) {
			$this->db->order_by('sort_id','desc');
		}else{
			$this->db->order_by('id','desc');
		}
		$query = $this->db->get();
		$row = $query->row_array();
		switch($row['type']) {
			case 1:
				$product = $this->db->select('id,title,photo,thumb')->from('product')->where(array('id'=>$row['rid']))->get();
				break;
			case 2:
				$product = $this->db->select('id,title,photo,thumb')->from('videos')->where(array('id'=>$row['rid']))->get();
				break;
			case 3:
				$product = $this->db->select('id,title,photo,thumb')->from('news')->where(array('id'=>$row['rid']))->get();
				break;
		}
		$account = $this->db->select('nickname')->from('account')->where(array('id'=>$row['uid']))->get()->row_array();
		$rdata = $product->row_array();
		$row['title'] = $rdata['title'];
		$row['thumb'] = $rdata['thumb'];
		$row['photo'] = $rdata['photo'];
		$row['nickname'] = $account['nickname'];
		return $row;
	}
}
