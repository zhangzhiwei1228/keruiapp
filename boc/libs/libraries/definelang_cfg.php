<?php 
/**
* 
*/
class definelang_cfg
{
	
	private $definelang = array();

	function __construct(){
		//级别
		$definelang['users_level']=array(
			'0'=>'免费会员',
			'1'=>'标准会员',
			'2'=>'高级会员',
			
		);
		//会员-免费会员
		$definelang['users_level1']=array(
			'level'=>'0',
			'zy'=>'20',
			'xq'=>'10',
			'qz'=>'1',
			'pricey'=>'0',
			'price'=>'0'
			
		);
		//会员-标准会员
		$definelang['users_level2']=array(
			'level'=>'1',
			'zy'=>'50',
			'xq'=>'20',
			'qz'=>'4',
			'pricey'=>'165',
			'price'=>'188'
			
		);
		//会员-高级会员
		$definelang['users_level3']=array(
			'level'=>'2',
			'zy'=>'10000',
			'xq'=>'10000',
			'qz'=>'10000',
			'pricey'=>'365',
			'price'=>'388'
		);
		$this->definelang = $definelang;
	}

	public function get($value){
		if (!isset($this->definelang[$value])) {
			return false;
		}
		return $this->definelang[$value];
	}

}
 ?>