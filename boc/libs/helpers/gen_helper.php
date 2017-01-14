<?php if (!defined('BASEPATH')) {
    exit('No direct access allowed.');
}

if (!function_exists('genPickcode')) {
    /**
     * 生成取货码
     */
    function genPickcode($store_id='', $shelfno='')
    {
      return genPickcode_by_order($store_id, $shelfno);
    }
}

if (!function_exists('genPickcode_by_order')) {
    /**
     * 生成顺序取货码
     */
    function genPickcode_by_order($store_id='', $shelfno='')
    {
      $CI =& get_instance();
      if (!isset($CI->mshelforder)) {
    	   $CI->load->model('shelforder_model','mshelforder');
      }
      $where = array();
      $where['store_id'] = $store_id;
      // 当天时间范围
      $timeline_start = strtotime(date("Y-m-d"));
      $where['timeline >'] = $timeline_start;
      $where['timeline <'] = $timeline_start + 24 * 3600 - 1;
      $count = $CI->mshelforder->get_count_all($where);
      $count ++;

      $pickcode = $shelfno.date("md").sprintf("%02d", $count);

      return $pickcode;
    }
}

if (!function_exists('genPickcode_by_random')) {
    /**
     * 生成随机取货码
     */
    function genPickcode_by_random($store_id='', $shelfno='')
    {
      $CI =& get_instance();
      // if (!isset($CI->mshelforder)) {
    	//    $CI->load->model('shelforder_model','mshelforder');
      // }
      $max_try = 10;
      // return '';
      for ($i=0; $i < $max_try; $i++) {
        $sql = "call zb_ybgj.get_pickcode($store_id, '$shelfno', @new_pickcode);";
        $CI->db->query($sql);
        $result = $CI->db->query("select @new_pickcode as new_pickcode")->row_array();
        if (isset($result) && isset($result['new_pickcode']) && $result['new_pickcode']) {
          return $result['new_pickcode'];
        }
      }
      return '';
    }
}

if (!function_exists('genToken')) {
    // 生成用户token
    /**
     * @param len长度
     */
    function genToken($len=64)
    {
        $CI =& get_instance();
        if (!isset($CI->maccount_token)) {
            $CI->load->model('account_token_model', 'maccount_token');
        }
        $token = rand_str($len);

        $tmp = $CI->maccount_token->get_one(array('token'=>$token));

        do {
            $token = rand_str($len);
            $tmp = $CI->maccount_token->get_one(array('token'=>$token));
        } while (!empty($tmp));

        return $token;
    }
}

if (!function_exists('genrcode')) {
    // 生成用户token
    /**
     * @param len长度
     */
    function genRcode($len=16, $prefix='BDFHJLNPRJVXZbdfhjlnprjvxz02468')
    {
      $CI =& get_instance();
      if (!isset($CI->maccount)) {
          $CI->load->model('account_model', 'maccount');
      }
      $rcode = rand_str(1, $prefix).rand_str($len-1);

      $tmp = $CI->maccount->get_one(array('rcode'=>$rcode));

      do {
        $rcode = rand_str(1, $prefix).rand_str($len-1);
        $tmp = $CI->maccount->get_one(array('rcode'=>$rcode));
      } while (!empty($tmp));

      return $rcode;
    }
}
