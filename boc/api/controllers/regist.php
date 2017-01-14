<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * Class account extends MY_Controller
 * @author hanj
 * 移动端用户接口类(用户类, 店长等身份用)
 */
class regist extends API_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('district_model', 'mdistrict');

		$this->userInfoFields = 'id,phone,nickname,province,city,industry';
	}
	protected $rules = array(
		"go" => array(
			array(
				"field" => "phone",
				"label" => "手机号码",
				"rules" => "trim|required|numeric|min_length[11|max_length[11]|is_mobile|callback_account_check",
			)
			, array(
				"field" => "smscode",
				"label" => "手机验证码",
				"rules" => "trim|required|min_length[6]|max_length[6]|callback_smscode_check",
			)
			, array(
				"field" => "nickname",
				"label" => "昵称",
				"rules" => "trim|required",
			)
			, array(
				"field" => "pwd",
				"label" => "登录密码",
				"rules" => "trim|required|min_length[6]|max_length[12]",
			)
			, array(
				"field" => "province",
				"label" => "省份",
				"rules" => "trim|required",
			)
			, array(
				"field" => "city",
				"label" => "城市",
				"rules" => "trim|required",
			)
			, array(
				"field" => "industry",
				"label" => "行业",
				"rules" => "trim|required",
			),
		),
		// 注册时用
		"smsCode" => array(
			array(
				'field' => 'phone',
				'label' => '手机号',
				'rules' => 'trim|required|numeric|min_length[11]|max_length[11]|is_mobile|callback_account_check',
			),
		),
	);

	// 用户注册
	public function go() {
		// 返回服务器时间以及预定义参数
		$this->vdata['timeline'] = time();
		$this->vdata['content'] = '';
		$this->vdata['secure'] = 0;

		// 验证
		$this->form_validation->set_rules($this->rules['go']);

		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = validation_errors();
		} else {
			 $use_tree=get_lang('cfg','users_level1');
			 $zy=$use_tree['zy'];
	         $xq=$use_tree['xq'];
	         $qz=$use_tree['qz'];
			// 组装创建数据
	         if(time()<EXPIRES_TIME){
				$create_data = array(
				'phone' => $this->data['phone'],
				'pwd' => passwd($this->data['pwd']),
				'province' => $this->data['province'],
				'city' => $this->data['city'],
				'industry' => $this->data['industry'],
				'nickname' => $this->data['nickname'],
				'level' => 1,
				'endtimeline' => EXPIRES_TIME,
				'uptimeline' => time(),
				'audit' => '1',
				);
			}else{
				$create_data = array(
				'phone' => $this->data['phone'],
				'pwd' => passwd($this->data['pwd']),
				'province' => $this->data['province'],
				'city' => $this->data['city'],
				'industry' => $this->data['industry'],
				'nickname' => $this->data['nickname'],
				'uptimeline' => time(),
				'zy' => $zy,
				'xq' => $xq,
				'qz' => $qz,
				'audit' => '1',
				);

			}
			// 创建数据
			if ($id = $this->macc->create($create_data)) {
				logfile($id);
				if (!isset($this->data['terminalNo'])) {
					$this->data['terminalNo'] = 0;
				}
				// 返回用户详细数据
				if ($info = $this->mvacc->get_info(array('id' => $id), $this->userInfoFields, $this->data['terminalNo'])) {
					$info['provincename'] = get_addr($info['province']);
					$info['cityname'] = get_addr($info['city']);
					$info['industryname'] = get_industry($info['industry']);
					if(time()<EXPIRES_TIME){
					 $extras=array('type'=>"2");//附加消息表示系统消息
					 push_jpush((string)$info['id'], '为感谢广大交道朋友的厚爱，特此推出购买会员优惠活动，用更低的价格享更多的权益。还等什么，快来体验吧！','',$extras,true);//极光推送---优惠期间
					}

					if (isset($info['sn'])) {
						unset($info['sn']);
					}
					l_msg_send('恭喜注册成功', 7, 0, $info['id']);
					$this->vdata['returnCode'] = '200';
					$this->vdata['returnInfo'] = '操作成功';
					$this->vdata['secure'] = JSON_SECURE;
					$this->vdata['content'] = $info;
				} else {
					$this->vdata['returnCode'] = '200';
					$this->vdata['returnInfo'] = '操作失败';
				}
			} else {
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
			}
		}

		// 返回json数据
		$this->_send_json($this->vdata);
	}

	// 发送验证码(创建用户时用)
	public function smsCode() {
		// 返回服务器时间以及预定义参数
		$this->vdata['timeline'] = time();
		$this->vdata['content'] = '';
		$this->vdata['secure'] = 0;

		// 验证
		$this->form_validation->set_rules($this->rules['smsCode']);

		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = validation_errors();
		} else {
			$this->load->model('smscode_model', 'msmscode');

			if ($content = $this->msmscode->sendCode($this->data['phone'], 1,1)) {
				if ($content['code'] == 421) {
					$this->vdata['returnCode'] = '0011';
					$this->vdata['returnInfo'] = $content['msg'];
					$this->vdata['secure'] = JSON_SECURE;

				} else {
					// 返回成功
					$this->vdata['returnCode'] = '200';
					$this->vdata['returnInfo'] = '操作成功';
					$this->vdata['secure'] = JSON_SECURE;
					$this->vdata['content'] = $content;
				}

			} else {
				// 返回成功
				$this->vdata['returnCode'] = '0011';
				$this->vdata['returnInfo'] = '短信发送失败';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content'] = $content;
			}
		}

		// 返回json数据
		$this->_send_json($this->vdata);
	}

	//////////////////////////////////////
	///////////////规则验证////////////////
	//////////////////////////////////////

	// 检验找回密码账户
	public function account_check($account) {
		// 查找用户
		if ($this->info = $this->macc->get_one(array('phone' => $account))) {
			$this->form_validation->set_message('account_check', '该手机号已经被注册！');
			return false;
		} else {
			return true;
		}
	}

	public function smscode_check($smscode) {
		$this->load->model('smscode_model', 'msmscode');

		if (!$this->msmscode->verifySmsCode($this->data['phone'], $smscode, 1)) {
			// 返回失败
			$this->form_validation->set_message('smscode_check', '验证码验证失败,请重新填写');
			return false;
		}
		return true;
	}
}
