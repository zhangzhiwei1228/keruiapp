<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Paynotify extends MY_Controller {


	function __construct() {
		parent::__construct();
		$this->load->model('account_order_model', 'maorder');
    $this->load->model('account_model', 'macc');
	}

  protected $rules = array(
    "iospay" => array(
      array(
        "field" => "uid",
        "label" => "会员编号",
        "rules" => "trim|required",
      ),array(
        "field" => "month",
        "label" => "购买月份",
        "rules" => "trim|required",
      ),array(
        "field" => "buynoid",
        "label" => "流水号",
        "rules" => "trim|required",
      )
    )
  );
  //IOS内购 ---购买成功后数据处理
  public function iospay() {
    $data = $this->input->post();
    logfile("'pay_notify:".'iospay');
    logfile($data);
    $res= "fail";
    $data['res']=$res;
    $vdata['returnCode']   = '0011';
    $vdata['returnInfo'] = '操作失败';
    $vdata['secure']     = "true";
    $vdata['content'] = $data;
      $info=$this->maorder->get_one(array('uid'=>$data['uid'],'mon'=>$data['month'],'pay'=>0));
      if(!empty($info)){
        $uinfo=$this->macc->get_one($info['uid']);
        $nowtime=time();
        $this->maorder->update(array('pay'=>1,'buytype'=>0,'buynoid'=>$data['buynoid'],'buytimeline'=>$nowtime),array('id'=>$info['id']));
        $u_endtimeline=$nowtime;
        if(!empty($uinfo['endtimeline'])){
          $u_endtimeline=$uinfo['endtimeline'];
        }

        $mon=$info['mon'];
        $add_mon=date("m",$u_endtimeline)+$mon;
        if($add_mon>12){
          $add_mon=$add_mon-12;
          $t_time=strtotime(date('Y',$u_endtimeline) + 1 . '-' .$add_mon .'-'.date('d',$u_endtimeline));
        }else{
          $t_time=strtotime(date('Y',$u_endtimeline) . '-' .$add_mon .'-'. date('d',$u_endtimeline));
        }
        if($rid=$this->macc->update(array('level'=>1,'endtimeline'=>$t_time),array('id'=>$info['uid']))){
          $res= "success"; //请不要修改或删除
          $extras=array('type'=>"2");//附加消息表示系统消息
          push_jpush((string)$info['uid'], '感谢您购买交道会员，从此享受更多权益，快来体验一下吧！','',$extras,true);//极光推送
          
         $rescontent="恭喜你升级为VIP会员";
          l_msg_send($rescontent,10,0,$info['uid'],0,0,0);

           $data['res']=$res;
          $vdata['returnCode']   = '200';
          $vdata['returnInfo'] = '操作成功';
          $vdata['secure']     = "true";
          $vdata['content'] = $data;
        }
      }
      logfile("'pay_notify_end:".$res);
      
       
         // 返回json数据
       $this->_send_json($vdata);

  }

//支付宝 ---购买成功后数据处理
	public function alipay() {

		logfile("'阿里支付 in pay_notify:".'alipay');
		logfile("alipay" . print_r($_POST, 1));
		require_once LIBS_PATH . "alipay_api/alipay.config.php";
		require_once LIBS_PATH . "alipay_api/lib/alipay_notify.class.php";

		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);

		$verify_result = $alipayNotify->verifyNotify();

		logfile('alipayNotify'.print_r($alipayNotify, 1));
		if ($verify_result) {

			//验证成功
			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];
			//支付宝交易号
			$trade_no = $_POST['trade_no'];
			//交易状态
			$trade_status = $_POST['trade_status'];
			if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
				$out_trade_no_V = substr($out_trade_no, 0, 3);
			
        
  				if ($out_trade_no_V == "JD_") {

              $info=$this->maorder->get_one(array('title'=>$out_trade_no,'pay'=>0));
              if(!empty($info)){
                  $uinfo=$this->macc->get_one($info['uid']);
                  $nowtime=time();
                  $this->maorder->update(array('pay'=>1,'buytype'=>1,'buynoid'=>$trade_no,'buytimeline'=>$nowtime),array('id'=>$info['id']));
                  $u_endtimeline=$nowtime;
                  if(!empty($uinfo['endtimeline'])){
                    $u_endtimeline=$uinfo['endtimeline'];
                  }

                  $mon=$info['mon'];
                  $add_mon=date("m",$u_endtimeline)+$mon;
                  if($add_mon>12){
                    $add_mon=$add_mon-12;
                    $t_time=strtotime(date('Y',$u_endtimeline) + 1 . '-' .$add_mon .'-'.date('d',$u_endtimeline));
                  }else{
                    $t_time=strtotime(date('Y',$u_endtimeline) . '-' .$add_mon .'-'. date('d',$u_endtimeline));
                  }
                  if($rid=$this->macc->update(array('level'=>1,'endtimeline'=>$t_time),array('id'=>$info['uid']))){
                    $res= "success"; //请不要修改或删除
                    $extras=array('type'=>"2");//附加消息表示系统消息
                    push_jpush((string)$info['uid'], '感谢您购买交道会员，从此享受更多权益，快来体验一下吧！','',$extras,true);//极光推送

                   //push_jpush((string)$info['id'], '您的会员即将到期，为了在交道上可以更好的获取资源、发布需求，建议您再次购买会员哦！');//极光推送---定时发送
                   $rescontent="恭喜你升级为VIP会员";
                    l_msg_send($rescontent,10,0,$info['uid'],0,0,0);
                  }
              }
          }
			}

			// 支付更新结果
			if (isset($res)) {
				logfile("'阿里支付 in pay_notify of alipayNotify success.".'alipay');
				echo "success"; //请不要修改或删除
			} else {
          logfile("'阿里支付 in pay_notify of alipayNotify order_fail.".'alipay');
				//验证失败
				echo "fail";
			}

		} else {
       logfile("'阿里支付 in pay_notify of alipayNotify fail.".'alipay');
			//验证失败
			echo "fail";
		}

	}



  public function weixin(){
     
        
           include_once LIBS_PATH."wxpaym/WxPayPubHelper.php";

            //使用通用通知接口
            $notify = new Notify_pub();

            //存储微信的回调
            $xml = $GLOBALS['HTTP_RAW_POST_DATA'];  
             logfile("微信xml:");
            logfile($xml);
            $notify->saveData($xml);
            
            //验证签名，并回应微信。
            //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
            //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
            //尽可能提高通知的成功率，但微信不保证通知最终能成功。
            if($notify->checkSign() == FALSE){
                $notify->setReturnParameter("return_code","FAIL");//返回状态码
                $notify->setReturnParameter("return_msg","签名失败");//返回信息
            }else{
                $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
            }
            $returnXml = $notify->returnXml();
            
           echo $returnXml;
         

            if($notify->checkSign() == TRUE)
            {
                if ($notify->data["return_code"] == "FAIL") {
                    //此处应该更新一下订单状态，商户自行增删操作
                    logfile("微信【通信出错】:". $xml);
                   
                }
                elseif($notify->data["result_code"] == "FAIL"){
                    //此处应该更新一下订单状态，商户自行增删操作
                    logfile("微信【业务出错】:". $xml);
                    
                }
                else{
                    //此处应该更新一下订单状态，商户自行增删操作
                    logfile("微信【支付】:". print_r($notify,1));
                    $out_trade_no=$notify->data["out_trade_no"];
                    $out_trade_no_V = substr($out_trade_no, 0, 3);     
                    if ($out_trade_no_V == "JD_") {
                        $info=$this->maorder->get_one(array('title'=>$out_trade_no,'pay'=>0));
                        if(!empty($info)){
                            $uinfo=$this->macc->get_one($info['uid']);
                            $nowtime=time();
                            $this->maorder->update(array('pay'=>1,'buytype'=>2,'buynoid'=>$notify->data["transaction_id"],'buytimeline'=>$nowtime),array('id'=>$info['id']));
                            $u_endtimeline=$nowtime;
                            if(!empty($uinfo['endtimeline'])){
                              $u_endtimeline=$uinfo['endtimeline'];
                            }

                            $mon=$info['mon'];
                            $add_mon=date("m",$u_endtimeline)+$mon;
                            if($add_mon>12){
                              $add_mon=$add_mon-12;
                              $t_time=strtotime(date('Y',$u_endtimeline) + 1 . '-' .$add_mon .'-'.date('d',$u_endtimeline));
                            }else{
                              $t_time=strtotime(date('Y',$u_endtimeline) . '-' .$add_mon .'-'. date('d',$u_endtimeline));
                            }
                            if($rid=$this->macc->update(array('level'=>1,'endtimeline'=>$t_time),array('id'=>$info['uid']))){
                              $res= "success"; //请不要修改或删除
                              $extras=array('type'=>"2");//附加消息表示系统消息
                              push_jpush((string)$info['uid'], '感谢您购买交道会员，从此享受更多权益，快来体验一下吧！','',$extras,true);//极光推送

                             //push_jpush((string)$info['id'], '您的会员即将到期，为了在交道上可以更好的获取资源、发布需求，建议您再次购买会员哦！');//极光推送---定时发送
                             $rescontent="恭喜你升级为VIP会员";
                              l_msg_send($rescontent,10,0,$info['uid'],0,0,0);

                                logfile("'Weixin in pay_notify of Notify success.".'Weixin');
                            }
                        }
                    
                    }
                }
                //$notify->data["out_trade_no"]
               // $this->mord->update(array('zhuangtai'=>2,'zhifutime'=>time(),'zhifubaodingdan'=>"微信支付"),array('id' => $notify->data["out_trade_no"]));
                //redirect(site_url('mobile/order/payment_ok/'.$notify->data["out_trade_no"]), 'refresh');
                //商户自行增加处理流程,
                //例如：更新订单状态
                //例如：数据库操作
                //例如：推送支付完成信息
            }

    }



}