<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// 验证，自动过滤了crsf

/**
 * Validate
 */
class validate extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("account_model", "maccount");
        $this->load->helper('valid_helper');
    }

    // 支付宝返回数据处理
    public function pay_ali_notify()
    {
        logfile('阿里支付 开始支付:', 'alipay');
        include_once LIBS_PATH . 'libraries/alipay_api/pay_notify.php';
    }

    // 微信支付返回数据处理
    public function pay_wechat_notify()
    {
        logfile('微信支付 开始支付:', 'wechatpay');
        include_once LIBS_PATH . 'libraries/wechatpay/pay_notify.php';
    }

    // TODO: remove test
    // public function test_pay() {
    // 	$orderid = $this->input->post('orderid');
    //
    // 	$this->load->model('orderpay_model', 'morderpay');
    // 	$vdata = $this->morderpay->pay_ok($orderid, 2, 'ali123');
    //
    // 	$this->output->set_content_type('application/json')->set_output(json_encode($vdata));
    // }

    // todo: 调用
    // 订单产品库存处理
    private function product_have_process($oderid = false)
    {
        $this->load->model('order_model', 'morder');
        $result = $this->morder->resolve_have($oderid);
        logfile('处理结果:' . print_r($result, true), 'product_have');
    }

    // 开始执行阿里支付
    public function do_pay_ali($result)
    {
        logfile('阿里支付result:', 'alipay');
        logfile(json_encode($result), 'alipay');
        // 处理
        //商户订单号
        $out_trade_no = $result['out_trade_no'];

        //阿里交易号
        $trade_no = $result['trade_no'];

        //阿里支付金额
        if (ENVIRONMENT == 'development') {
          $money = 0.01;
        } else {
          $money = $result['total_fee']; // 以元为单位
        }

        $this->load->model('orderpay_model', 'morderpay');
        // 判定支付
        //对订单进行处理  , 对 订单号去除随机数处理
        //充值
        if (preg_match('/^RECHARGE[0-9]{3,}$/', $out_trade_no)) {
            logfile('阿里支付 result:', 'recharge');
            logfile($result, 'recharge');
            logfile('处理订单:' . $out_trade_no, 'recharge');
            $re = $this->morderpay->recharge_ok($out_trade_no, 2, $trade_no, $money);
            logfile('处理结果:' . print_r($re, 1), 'recharge');
            if ($re['status']) {
                return true;
            } else {
                return false;
            }
        // 订单
        } elseif (preg_match('/^WATER[0-9]{3,}$/', $out_trade_no)) {
            logfile('阿里支付result:', 'pay');
            logfile($result, 'pay');
            logfile('处理订单:' . $out_trade_no, 'pay');
            $re = $this->morderpay->pay_ok($out_trade_no, 2, $trade_no, $money);
            logfile('处理结果:' . print_r($re, 1), 'pay');
            if ($re['status']) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

        // 不会执行到这里来
        return true;
    }

    // 开始执行微信支付回传数据
    // 对订单前缀进行检测分辨 支付,充值等类型
    public function do_pay_wechat($result)
    {
        logfile('微信支付 result:', 'wechatpay');
        logfile(json_encode($result), 'wechatpay');
        // 处理
        //商户订单号
        $out_trade_no = $result['out_trade_no'];
        // $out_trade_no = explode('-', $out_trade_no);
        // $out_trade_no = $out_trade_no[0];
        // dump($out_trade_no);
        //微信交易号
        $trade_no = $result['transaction_id'];

        // 微信支付金额
        if (ENVIRONMENT == 'development') {
          $money = 1; // 以分为单位，转化为元
        } else {
          $money = $result['total_fee']/100; // 以分为单位，转化为元
        }

        $this->load->model('orderpay_model', 'morderpay');

        // 判定支付
        //对订单进行处理  , 对 订单号去除随机数处理
        //充值
        if (preg_match('/^RECHARGE[0-9]{3,}$/', $out_trade_no)) {
            logfile('微信支付 result:', 'recharge');
            logfile($result, 'recharge');
            logfile('处理订单:' . $out_trade_no, 'recharge');
            $re = $this->morderpay->recharge_ok($out_trade_no, 3, $trade_no, $money);
            logfile('处理结果:' . print_r($re, 1), 'recharge');
            if ($re['status']) {
                return true;
            } else {
                return false;
            }
        // 订单
        } elseif (preg_match('/^WATER[0-9]{3,}$/', $out_trade_no)) {
            logfile('微信支付result:', 'pay');
            logfile($result, 'pay');
            logfile('处理订单:' . $out_trade_no, 'pay');
            $re = $this->morderpay->pay_ok($out_trade_no, 3, $trade_no, $money);
            logfile('处理结果:' . print_r($re, 1), 'pay');
            if ($re['status']) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

        // 不会执行到这里来
        return true;
    }

    public function test_alipay_start()
    {
      /*$_POST = array(
        'discount' => '0.00',
        'payment_type' => '1',
        'subject' => '南博鳌亚洲风情广场有限公司订单统一支付',
        'trade_no' => '2016042621001004560236064463',
        'buyer_email' => 'lhz_0529@163.com',
        'gmt_create' => '2016-04-26 16:20:05',
        'notify_type' => 'trade_status_sync',
        'quantity' => '1',
        'out_trade_no' => 'WATER160426161952290721',
        'seller_id' => '2088221509597631',
        'notify_time' => '2016-04-26 16:20:06',
        'body' => '南博鳌亚洲风情广场有限公司订单统一支付',
        'trade_status' => 'TRADE_SUCCESS',
        'is_total_fee_adjust' => 'N',
        'total_fee' => '0.01',
        'gmt_payment' => '2016-04-26 16:20:06',
        'seller_email' => '361937866@qq.com',
        'price' => '0.01',
        'buyer_id' => '2088022139669563',
        'notify_id' => 'b984c84e916cd1611e82b07816dbc81kbm',
        'use_coupon' => 'N',
        'sign_type' => 'RSA',
        'sign' => 'EQM9jY0eDH1P0eVtF+jy+ReSePdREFnRUmOXNFCHYIRnPBYZLienMFzZaTgIVJjvWkZTCQFcvc+omLhJEpHBcmMEddZdFepRpXRgG473/Gqnsjt22L+tsyXk7hn8I1wAD89SQlYXCM1J2kD0E8pPOOM80ro1rv0ec8n0QCf85xI='
      );*/

    /*$_POST = array(
        'total_amount' => '0.01',
    'buyer_id' => '2088902972532391',
    'trade_no' => '2016121021001004390278481500',
    'body' => 'USER_BUY20',
    'notify_time' => '2016-12-10 11:40:49',
    'subject' => 'JIAODAO',
    'sign_type' => 'RSA',
    'buyer_logon_id' => '137***@163.com',
    'auth_app_id' => '2016120103710718',
    'charset' => 'utf-8',
    'notify_type' => 'trade_status_sync',
    'invoice_amount' => '0.01',
    'out_trade_no' => 'JD_20161210111556729',
    'trade_status' => 'TRADE_SUCCESS',
    'gmt_payment' => '2016-12-10 11:16:06',
    'version' => '1.0',
    'point_amount' => '0.00',
    'sign' => 'TQt4jUv17r+u8VV5Sm/dTgKrnGH+Q7HAgMNYyCRqMa+A1U9YLa/AAtPeqDawxS0CUvXYC/ZuYmwSVwGtYSkDW9JEQ56YzEYxFoPMd40r75VbueQzS5k/uelKsYScq6eAYkGnvSRD5zcnGS8G8zg3uytzGFMxaiyecR/gHmff1uI=',
    'gmt_create' => '2016-12-10 11:16:04',
    'buyer_pay_amount' => '0.01',
    'receipt_amount' => '0.01',
    'fund_bill_list' => '[{"amount":"0.01","fundChannel":"ALIPAYACCOUNT"}]',
    'app_id' => '2016120103710718',
    'seller_id' => '2088521308684555',
    'notify_id' => '521b3899cc211aafdbfc5aedcaf1b08j0e',
    'seller_email' => '2198152901@qq.com',
    );*/


$_POST = array(
        
'total_amount' => '0.01',
    'buyer_id' => '2088902972532391',
    'trade_no' => '2016121221001004390279200028',
    'body' => 'USER_BUY20',
    'notify_time' => '2016-12-12 10:11:20',
    'subject' => 'JIAODAO',
    'sign_type' => 'RSA',
    'buyer_logon_id' => '137***@163.com',
    'auth_app_id' => '2016120103710718',
    'charset' => 'utf-8',
    'notify_type' => 'trade_status_sync',
    'invoice_amount' => '0.01',
    'out_trade_no' => 'JD_20161212101053100',
    'trade_status' => 'TRADE_SUCCESS',
    'gmt_payment' => '2016-12-12 10:11:20',
    'version' => '1.0',
    'point_amount' => '0.00',
    'sign' => 'tVregs8Ur5WEjjE6OdOHd8+7ZalepuyUrgKeLC2FE+p/TGDbaDk8D5DDdomkpJeQRPWpPuyZmnCdyRCHbd0IhkMgTlzdRPgn7GCNxdm/o8QDpP4UzRSUkZFE5pCADD2dpBjo+a2BxQeTr3IidOql8VwKW+HkDzmfLrUiSeJCtzs=',
    'gmt_create' => '2016-12-12 10:11:18',
    'buyer_pay_amount' => '0.01',
    'receipt_amount' => '0.01',
    'fund_bill_list' => '[{"amount":"0.01","fundChannel":"ALIPAYACCOUNT"}]',
    'app_id' => '2016120103710718',
    'seller_id' => '2088521308684555',
    'notify_id' => 'be81f846e1c886657dd8fd96e0417aej0e',
    'seller_email' => '2198152901@qq.com',
    );





      include_once LIBS_PATH . 'alipay_api/pay_notify.php';
    }
    //
    // public function test_gethere($waterId = '')
    // {
    //   echo 'hehe';
    //   logfile('test_gethere', 'alipay');
    // }

    public function test_paywechat($waterId = '')
    {
        if ($waterId) {
            $re['out_trade_no']  = $waterId;
        } else {
            $re['out_trade_no']  = "WATER16050314065114199";
        }
        $re['transaction_id'] = "WECHATPAY12332413412341234";
        $result = $this->do_pay_wechat($re);
        echo $result?'OK':'DIE';
    }

    public function test_aliwechat()
    {
        $re['out_trade_no']  = "WATER16050314065114199";
        $re['trade_no'] = "ALIPAY12332413412341234";
        $this->do_pay_ali($re);
        echo 123;
    }
}
