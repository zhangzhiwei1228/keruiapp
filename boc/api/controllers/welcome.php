<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class welcome extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();

    // $this->load->model('banner_model', 'mbanner');
    // $this->load->model('qq_model', 'mqq');
    // $this->load->model('porder_model', 'mporder');
    // $this->load->model('product_model', 'mproduct');
  }

  /*public function index()
  {
    // 返回服务器时间以及预定义参数
    // $vdata['timeline']   = time();
    $vdata['msg']    = '请求成功';
    $vdata['data']    = null;
    $vdata['secure']     = 0;
    $vdata['returnCode']   = '200';

    $vdata['data']['index_title'] = '优邦管家';
    $vdata['data']['index_item'] = array(
      array(
        'id' => 1,
        'title' => '入库'
      ),
      array(
        'id' => 2,
        'title' => '出库'
      )
    );

    $this->output->set_content_type('application/json')->set_output(json_encode($vdata));
  }
*/

  // public function test()
  // {
  //   $this->load->model('action_model', 'maction');
  //   $this->maction->add_point(1, 345);
  //   echo $this->db->last_query();
  // }

  // public function search()
  // {
  //     //验证数据安全
  //     $data = $this->input->post();
  //     if (isset($data['secure']) && !empty($data['content'])) {
  //         if (!($this->data = apiValidate($data))) {
  //             $re = array('returnCode' => '9998',
  //                 'returnInfo' => '数据传输失败，请联系客服！',
  //                 'timeline' => time());
  //             header("Content-Type: application/json; charset=utf-8");
  //             echo json_encode($re);
  //             exit;
  //         }
  //     } else {
  //         $this->data = $data;
  //     }
  //
  //     $data_post = $this->data;
  //     $page = !empty($data_post) && isset($data_post['page']) ? ($data_post['page'] - 1) : 0;
  //     $this->limit = !empty($data_post) && isset($data_post['limit']) ? $data_post['limit'] : 5;
  //     $this->offset = $page * $this->limit;
  //     $this->orderby = !empty($data_post) && isset($data_post['orderby']) ? $data_post['orderby'] : 'sort_id';
  //
  //     if (!empty($data_post) && isset($data_post['orderdirection']) && in_array($data_post['orderdirection'], array('desc', 'asc'))) {
  //         $this->orderby = array($this->orderby=>$data_post['orderdirection']);
  //     }
  //
  //     $where = array('audit'=>1);
  //     if (isset($this->data['kw']) && $this->data['kw']) {
  //         $where['like title'] = array('title', $this->data['kw']);
  //     }
  //
  //     // 拉取数据
  //     if ($list = $this->mproductseller->get_list($this->limit, $this->offset, $this->orderby, $where, $this->sellersFields, 'vproductseller')) {
  //         foreach ($list as $k => &$v) {
  //             if ($v['title_sub']) {
  //                 $v['title'] = $v['title'].'('.$v['title_sub'].')';
  //             }
  //             unset($v['title_sub']);
  //         }
  //         photo2url($list);
  //
  //         $vdata['returnCode']   = '0000';
  //         $vdata['returnInfo'] = '操作成功';
  //         $vdata['secure']     = JSON_SECURE;
  //         $vdata['content']['sellers']    = $list;
  //     } else {
  //         $vdata['returnCode']   = '0000';
  //         $vdata['returnInfo'] = '暂未查询到任何数据';
  //         $vdata['content']['sellers']    = array();
  //     }
  //
  //     $this->output->set_content_type('application/json')->set_output(json_encode($vdata));
  // }

  // public function aes()
  // {
  //   //load_AES
  //   $this->load->library('AES');
  //   $vdata['rc4'] = base64_encode(AES::rc4('1_12345678', KEY));
  //   $vdata['aes_128'] = AES::encrypt('1_12345678', KEY);
  //   $vdata['base64'] = base64_encode('1_12345678');
  //   $vdata['serialize'] = serialize('1_12345678');
  //   $vdata['url_encode'] = urlencode('1_12345678');
  //   $vdata['htmlentities'] = htmlentities('1_12345678');
  //   $vdata['rawurlencode'] = rawurlencode('1_12345678');
  //   $vdata['intval_a'] = ord('a');
  //   $vdata['intval_A'] = ord('bXt4PvODljF7Sxku');
  //   $str = 'ABCDEFGHIJKLMNOPQRSJUVWXYZabcdefghijklmnopqrsjuvwxyz0123456789';
  //   for($i=0;$i<strlen($str);$i++){
  //     if (ord($str[$i])%2 == 0) {
  //       echo $str[$i];
  //     }
  //   }
  //   echo '<br />';
  //   for($i=0;$i<strlen($str);$i++){
  //     if (ord($str[$i])%2 == 1) {
  //       echo $str[$i];
  //     }
  //   }
  //   echo '<br />';
  //
  //   print_r($vdata);
  // }

}
