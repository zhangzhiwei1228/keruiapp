<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class smscode_model extends MY_Model {
	protected $table = 'smscode';
	public function sendCode($phone,$type,$ctype)
	{
			$now = time();
			$tmp = parent::get_one(array('phone'=>$phone,'type'=>$type, 'timeline >'=>$now-60));
			if ($tmp) {
					$send_code_return['code'] = 421;
					$send_code_return['msg'] = '请再过'.(60 - ($now - $tmp['timeline'])).'秒请求短信';
					$send_code_return['phone'] = $phone;
					$send_code_return['auth_code'] = 0;
					return $send_code_return;
			}

			$send_info = send_authcode($phone,'',$ctype);

			if (!empty($send_info) && $send_info['code'] == 0) {
					$data = array('phone'=>$send_info['phone'],'type'=>$type, 'code'=>$send_info['auth_code']);
					if ($this->create($data)) {
							if (ENVIRONMENT != "development") {
									$send_info['auth_code'] = 0;
							}
							return $send_info;
					} else {
							return false;
					}
			} else {
					return false;
			}
	}

	public function verifySmsCode($phone, $code, $type)
	{
		$now = time();
		if (parent::get_one(array('phone'=>$phone, 'code'=>$code, 'type'=>$type, 'timeline >'=>$now-120))) {
			// 验证后删除短信验证码
			if (ENVIRONMENT != 'development') {
				$this->_delVerifySmsCode($phone);
			}
			return true;
		} else {
			if ((ENVIRONMENT == 'development') && ($code == '111111')) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function _delVerifySmsCode($phone)
	{
			$where_del = array('phone'=>$phone);
			parent::del('*', $where_del);
	}
}
