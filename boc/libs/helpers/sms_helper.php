<?php if (!defined('BASEPATH')) {
    exit('No direct access allowed.');
}


//
// // 验证码发送功能
// function Auth_Post($curlPost,$url){
// 		$curl = curl_init();
// 		curl_setopt($curl, CURLOPT_URL, $url);
// 		curl_setopt($curl, CURLOPT_HEADER, false);
// 		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// 		curl_setopt($curl, CURLOPT_NOBODY, true);
// 		curl_setopt($curl, CURLOPT_POST, true);
// 		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
// 		$return_str = curl_exec($curl);
// 		curl_close($curl);
// 		return $return_str;
// }
//
// function xml_to_array($xml){
// 	$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
// 	if(preg_match_all($reg, $xml, $matches)){
// 		$count = count($matches[0]);
// 		for($i = 0; $i < $count; $i++){
// 		$subxml= $matches[2][$i];
// 		$key = $matches[1][$i];
// 			if(preg_match( $reg, $subxml )){
// 				$arr[$key] = xml_to_array( $subxml );
// 			}else{
// 				$arr[$key] = $subxml;
// 			}
// 		}
// 	}
// 	return $arr;
// }

// # TODO: 发送失败处理
function send_authcode($phone, $send_code="", $ctype="")
{
    // post 方式
    $target = "http://120.26.69.248/msg/HttpSendSM";

    if (empty($send_code)) {
        $send_code = mt_rand(100000, 999999);
    }

    $send_code_return = array();

    if (empty($phone)) {
        $send_code_return['status'] = -1;
        exit('手机号码不能为空');
    }
    if($ctype==2){
        $post_data = "account=".SMS_ACCOUNT."&pswd=".SMS_PWD."&mobile=".$phone."&msg=".rawurlencode(SMS_PREFIX."您的验证码：${send_code}。（交道平台找回密码验证码，请完成验证），如非本人操作，请忽略本短信");
    }else{
        $post_data = "account=".SMS_ACCOUNT."&pswd=".SMS_PWD."&mobile=".$phone."&msg=".rawurlencode(SMS_PREFIX."欢迎注册交道平台，您的验证码为：${send_code}。");
    }
    

    if (ENVIRONMENT == "development") {
        $send_code_return['code'] = 0;
        $send_code_return['msg'] = '验证码已经发送，请注意接收(Development)!';
        $send_code_return['phone'] = $phone;
        $send_code_return['auth_code'] = $send_code;
    } else {
        $url = $target.'?'.$post_data;
        $gets = file_get_contents($url);
        $gets = explode(',', $gets);

        // if($gets[1]==0){
        $send_code_return['code'] = $gets[1];
        $send_code_return['msg'] = lang('sms_'.$gets[1]);
        $send_code_return['phone'] = $phone;
        $send_code_return['auth_code'] = $send_code;
        // }
    }

    logfile(compact('send_code_return', 'gets'), 'SMS_send_authcode_');
    return $send_code_return;
}

// 4额度查询接口
function sms_get_balance()
{
    // post 方式
    $target = "http://120.26.69.248/msg/QueryBalance";
    $post_data = "account=".SMS_ACCOUNT."&pswd=".SMS_PWD;

    $url = $target.'?'.$post_data;
    $gets = file_get_contents($url);
    $gets = explode(',', $gets);
    return $gets;
}

//
// # TODO: 发送失败处理
// function send_msg($phone, $content="") {
// 	// post 方式
// 	$target = "http://120.26.69.248/msg/HttpSendSM";
//
// 	$send_return = array();
//
// 	if (empty($content)) {
// 		exit('发送内容不能为空');
// 	}
//
// 	if(empty($phone)){
// 		exit('手机号码不能为空');
// 	}
//
// 	$post_data = "account=".SMS_ACCOUNT."&pswd=".SMS_PWD."&mobile=".$phone."&msg=".rawurlencode(SMS_PREFIX.$content);
//
// 	// $gets =  xml_to_array(Auth_Post($post_data, $target));
//
// 	if (ENVIRONMENT == "nodevelopment") {
// 		$send_return['code'] = 0;
// 		$send_return['msg'] = '验证码已经发送，请注意接收(Development)!';
// 		$send_return['phone'] = $phone;
// 		$send_return['auth_code'] = $content;
// 	} else {
// 		$url = $target.'?'.$post_data;
// 		$gets = file_get_contents($url);
// 		$gets = explode(',', $gets);
//
// 		// if($gets[1]==0) {
// 		// 	$send_return['code'] = $gets[1];
// 		// 	$send_return['msg'] = lang('sms_'.$gets[1]);
// 		// 	$send_return['phone'] = $phone;
// 		// 	$send_return['auth_code'] = $content;
// 		// } else {
// 		// 	$send_return['code'] = $gets[1];
// 		// 	$send_return['msg'] = lang('sms_'.$gets[1]);
// 		// 	$send_return['phone'] = $phone;
// 		// 	$send_return['auth_code'] = $content;
// 		// }
// 		$send_code_return['code'] = $gets[1];
// 		$send_code_return['msg'] = lang('sms_'.$gets[1]);
// 		$send_code_return['phone'] = $phone;
// 		$send_code_return['auth_code'] = $content;
//
// 	}
// 	logfile(compact('send_code_return', 'gets'), 'SMS_send_msg_');
// 	return $send_code_return;
// }
//
//
// if (!function_exists('del_authcode')) {
// // 获取用户等级
// 	/**
// 	 * @param $id 用户ID
// 	 * @param $type_id 用户类型
// 	 */
// 	function del_authcode($authcode) {
// 		$CI =& get_instance();
// 		if (!isset($CI->mcodetmp)) { $CI->load->model('codetmp_model','mcodetmp'); }
//
// 		$list = $CI->mcodetmp->get_all(array('code'=>$authcode), 'id');
//
// 		$list_ids = array();
//
// 		foreach ($list as $k => $v) {
// 			array_push($list_ids, $v['id']);
// 		}
//
// 		$CI->mcodetmp->del($list_ids);
// 	}
// }
;
