<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class demand_click_model extends MYSOFT_Model {
  protected $table = 'demand_click';

	public function add_click($did, $aid=false)
	{
    $res = 0;
    if (!parent::get_one(array('aid'=>$aid, 'did'=>$did))) {
      $create_data = array();
      $create_data['did'] = $did;
      $create_data['aid'] = $aid;
      $create_data['timeline'] = time();
      $res = parent::create($create_data);
    }

		return $res;
	}
}
