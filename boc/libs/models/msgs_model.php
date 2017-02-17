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
			->select('msgs.id,msgs.is_read,msgs.rid,msgs.type,msgs.timeline,account.nickname')
			->from('msgs')
			->join('account', 'account.id = msgs.uid', 'left')
			->limit($limit,$start)
			->where($where)
			->order_by('msgs.timeline','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		foreach($result as &$row) {
			switch($row['type']) {
				case 1:
					$msg = $this->db->select('content')->from('msg')->where(array('id'=>$row['rid']))->get()->row_array();
					$row['content'] = $this->msubstr(strip_tags($msg['content']),0,35);
					break;
				case 2:
					$msg = $this->db->select('content,comment,timeline,type,rid')->from('comment')->where(array('id'=>$row['rid']))->get()->row_array();
					switch($msg['type']) {
						case 1:
							$product = $this->db->select('id,title')->from('product')->where(array('id'=>$msg['rid']))->get()->row_array();
							break;
						case 2:
							$product = $this->db->select('id,title')->from('videos')->where(array('id'=>$msg['rid']))->get()->row_array();
							break;
						case 3:
							$product = $this->db->select('id,title')->from('news')->where(array('id'=>$msg['rid']))->get()->row_array();
							break;
					}

					$row['content'] = $this->msubstr(strip_tags($msg['content']),0,35);
					$row['comment'] = $this->msubstr(strip_tags($msg['comment']),0,35);
					$row['ctime'] = $msg['timeline'];
					$row['title'] = $product['title'];
					break;
				case 3:
					$msg = $this->db->select('content,answer,timeline_answer')->from('feedback')->where(array('id'=>$row['rid']))->get()->row_array();
					$row['content'] = $this->msubstr(strip_tags($msg['content']),0,35);
					$row['comment'] = $this->msubstr(strip_tags($msg['answer']),0,35);
					$row['ctime'] = $msg['timeline_answer'];
					$row['title'] = '意见反馈';
					break;
			}
		}
		return $result;
	}
	public function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
	{
		$suffix_str = '';
		if ($this->mstrlen($str) > $length and $suffix ) {
			$suffix_str = is_string($suffix) ? $suffix : '...';
		}
		if(function_exists("mb_substr")){
			$str = mb_substr($str, $start, $length, $charset);
			return $str.$suffix_str;
		}elseif(function_exists('iconv_substr')) {
			$str = iconv_substr($str,$start,$length,$charset);
			return $str.$suffix_str;
		}
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312']  = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']     = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']    = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
		return $slice.$suffix_str;
	}
	public  function mstrlen($str,$charset = 'UTF-8'){
		if (function_exists('mb_substr')) {
			$length=mb_strlen($str,$charset);
		} elseif (function_exists('iconv_substr')) {
			$length=iconv_strlen($str,$charset);
		} else {
			preg_match_all("/[x01-x7f]|[xc2-xdf][x80-xbf]|xe0[xa0-xbf][x80-xbf]|[xe1-xef][x80-xbf][x80-xbf]|xf0[x90-xbf][x80-xbf][x80-xbf]|[xf1-xf7][x80-xbf][x80-xbf][x80-xbf]/", $text, $ar);
			$length=count($ar[0]);
		}
		return $length;
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

