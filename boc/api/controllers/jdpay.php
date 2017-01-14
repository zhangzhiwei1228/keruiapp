<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * Class jdpay extends MY_Controller
 * @author
 * 移动端用户接口类 jdpay
 */
class jdpay extends API_Controller {

	public function __construct() {
		parent::__construct();
    $this->load->model('page_model', 'mpage');
    $this->load->model('account_order_model', 'maorder');
    $this->load->model('account_model', 'macc');
    $this->load->model('lists_model', 'mlists');
    
	}

	protected $rules = array(
    "list_get" => array(
      array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
    ),
    "order" => array(
      array(
        "field" => "id",
        "label" => "会员等级",
        "rules" => "trim|required",
      ),array(
        "field" => "paytype",
        "label" => "支付方式",
        "rules" => "trim",
      ),array(
        "field" => "token",
        "label" => "Token",
        "rules" => "trim|required",
      )
    )
	);


  //购买会员展示
  public function levelinfo() {
    // 验证
    $this->form_validation->set_rules($this->rules['list_get']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode'] = '0011';
      $this->vdata['returnInfo'] = $this->trim_validation_errors();
    } else {
      $where =array('cid'=>14);
      $data=$this->mlists->get_all($where,'id,title,mon,price');

      $this->vdata['returnCode'] = '200';
      $this->vdata['returnInfo'] = '操作成功';
      $this->vdata['secure'] = JSON_SECURE;
      $this->vdata['content'] = $data;
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }
  //购买会员
  public function orderpost() {
    // 验证
    $this->form_validation->set_rules($this->rules['order']);
    // validate验证结果
    if ($this->form_validation->run('', $this->data) == false) {
      // 返回失败
      $this->vdata['returnCode'] = '0011';
      $this->vdata['returnInfo'] = $this->trim_validation_errors();
    } else {
    	$data=$this->mlists->get_one($this->data['id']);
    	$this->db->order_by('id desc');
    	$info=$this->maorder->get_one(array('uid'=>$this->userinfo['id'],'pay'=>1));
    	$title="JD_".date("YmdHis").rand(100,999);
    	$oinfo=$this->maorder->get_one(array('title'=>$title));
      $o_info=$this->maorder->get_one(array('uid'=>$this->userinfo['id'],'mon'=>$data['mon'],'level'=>1,'price'=>$data['price'],'pay'=>0));
      if(empty($o_info)){
      	$create_data = array(
  	      'uid'        => $this->userinfo['id'],
  	      'mon'      => $data['mon'],
  	      'level'      => 1,
  	      'price'      => $data['price'],
  	      'production'      => 0,
  	      'timeline'      => time()
  	    );
  	    if($id = $this->maorder->create($create_data)) {
  	       if(!empty($oinfo)){
  	         $title=$title.$id;
  	       }
  	      $this->maorder->update(array('title'=>$title),array('id'=>$id));
  	      $re_data=array_merge(array('title'=>$title),$create_data);
        }
      }else{
        $re_data=$o_info;
      }
	      
	      if(empty($this->data['paytype'])){
	      	$this->data['paytype']=0;
          $re_data['paytype']=0;
          $this->vdata['returnCode']   = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure']     = JSON_SECURE;
            $this->vdata['content'] =$re_data;
	      }
	      if(!empty($this->data['paytype'])&&$this->data['paytype']==1){
	      	$paytype="支付宝支付";

          require_once LIBS_PATH . "alipay_api/alipay.config.php";
          require_once LIBS_PATH . "alipay_api/lib/alipay_notify.class.php";
           $paydata=array(
                 'app_id'=>$alipay_config['APPID'],
                 'method'=>"alipay.trade.app.pay",
                 'charset'=>'utf-8',
                 'sign_type'=>'RSA',
                 'format'=>'json',
                 'timestamp'=>date('Y-m-d H:i:s'),
                 'version'=>'1.0',
                 'notify_url'=>site_url('paynotify/alipay'),
                 'biz_content'=>json_encode(array('subject'=>'JIAODAO','seller_id'=>$alipay_config['partner'],'body'=>"USER_BUY".$this->userinfo['id'],'out_trade_no'=>$re_data['title'],'total_amount'=>$data['price'],'product_code'=>'QUICK_MSECURITY_PAY','timeout_express'=>'150m'))
                );
             $paydata=argSort($paydata);
             $str=createLinkstring($paydata);
             $paydata['sign']=rsaSign($str,trim($alipay_config['private_key_path']));
             
             $re_data['paycode']=createLinkstringUrlencode($paydata);
             $re_data['paytype']=1;

              //logfile($paydata);
             $this->vdata['returnCode']   = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure']     = JSON_SECURE;
            $this->vdata['content'] =$re_data;

	      }
	      if(!empty($this->data['paytype'])&&$this->data['paytype']==2){
	      	$paytype="微信支付";
           include_once LIBS_PATH."wxpaym/WxPayPubHelper.php";
           $notify = new Notify_pub();
         
           $paydata=array(
                 'appid'=>WxPayConf_pub::APPID,
                 'mch_id'=>WxPayConf_pub::MCHID,
                 'nonce_str'=>$notify->createNoncestr(),
                 'body'=>"USER_BUY".$this->userinfo['id'],
                 'out_trade_no'=>$re_data['title'],
                 'total_fee'=>$data['price']*100,
                 'spbill_create_ip'=>get_ip(),
                 'notify_url'=>site_url('paynotify/weixin'),
                 'trade_type'=>'APP'
                );
           // print_r($paydata);
            $paydata['sign']=$notify->getSign($paydata);
            $xml= $notify->postXmlCurl($notify->arrayToXml($paydata),'https://api.mch.weixin.qq.com/pay/unifiedorder');
            $paydatanew=$notify->xmlToArray($xml);
        
             if($paydatanew['return_code']=="SUCCESS"){
                 $arr['appid']=$paydatanew['appid'];
                 $arr['partnerid']=$paydatanew['mch_id'];
                 $arr['prepayid']=$paydatanew['prepay_id'];
                 $arr['package']="Sign=WXPay";
                 $arr['noncestr']=$notify->createNoncestr();
                 $arr['timestamp']=time();
                 $arr['sign']=$notify->getSign($arr);;
                $re_data['paycode']=$arr;
                $re_data['paytype']=2;
                $this->vdata['returnCode']   = '200';
                $this->vdata['returnInfo'] = '操作成功';
                $this->vdata['secure']     = JSON_SECURE;
                $this->vdata['content'] = $re_data;
                 //logfile($paydata);
             }else{
               $this->vdata['returnCode']   = '0011';
                $this->vdata['returnInfo'] = '统一下单失败';
                $this->vdata['secure']     = JSON_SECURE;
             }
	      }
        logfile("POST:");
        logfile($re_data);
    }
    // 返回json数据
    $this->_send_json($this->vdata);
  }

}
