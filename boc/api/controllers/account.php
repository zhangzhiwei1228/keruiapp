<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * Class account extends MY_Controller
 * @author xiejianwei
 * 移动端用户接口类
 */
class account extends API_Controller {

	public function __construct() {
		parent::__construct();
	}

    protected $rules = array(
        "edit" => array(
            array(
                'field' => 'photo',
                'label' => '头像',
                'rules' => 'trim|required|numeric',
            ),
            array(
                "field" => "token",
                "label" => "Token",
                "rules" => "trim|required",
            ),
            array(
                "field" => "nickname",
                "label" => "姓名",
                "rules" => "trim|required",
            )
        ),
        "edit_pwd" => array(
            array(
                "field" => "token",
                "label" => "Token",
                "rules" => "trim|required",
            ),
            array(
                'field' => 'oldpassword',
                'label' => '原始密码',
                'rules' => 'trim|required|min_length[6]|callback_oldpassword_check',
            )
            , array(
                'field' => 'password',
                'label' => '密码',
                'rules' => 'trim|required|min_length[6]',
            )
            , array(
                'field' => 'password_re',
                'label' => '确认密码',
                'rules' => 'trim|required|min_length[6]|matches[password]',
            )
        ),
        "get_token" => array(
            array(
                "field" => "id",
                "label" => "编号ID",
                "rules" => "trim|required",
            )
        )
    );
// 会员详细信息
    public function info() {
        // 验证
        $this->form_validation->set_rules($this->rules['info']);
        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode'] = '0011';
            $this->vdata['returnInfo'] = $this->trim_validation_errors();
        } else {
            if (!isset($this->data['terminalNo'])) {
                $this->data['terminalNo'] = 0;
            }
            // 返回用户详细数据
            if ($info = $this->mvacc->get_info(array('id' => $this->data['id']), 'nofresh', $this->data['terminalNo'])) {
                $this->vdata['content'] = $info;
            } else {
                $this->vdata['returnCode'] = '200';
                $this->vdata['returnInfo'] = '操作失败';
            }
        }
        // 返回json数据
        $this->_send_json($this->vdata);
    }

	// // 个人中心-设置头像
	public function edit() {
		// 返回服务器时间以及预定义参数
		$this->vdata['timeline'] = time();
		$this->vdata['content'] = '';
		$this->vdata['secure'] = 0;
		// 验证

		$this->form_validation->set_rules($this->rules['edit']);
		// validate验证结果
		if ($this->form_validation->run('', $this->data) == false) {
			// 返回失败
			$this->vdata['returnCode'] = '0011';
			$this->vdata['returnInfo'] = $this->trim_validation_errors();
		} else {
			if ($res = $this->macc->set($this->userinfo['id'], array('photo' => $this->data['photo'],'nickname' => $this->data['nickname'],'uptimeline'=>time()))) {//更新会员表
                $it = one_upload($this->data['photo'], 'id, url');
                if ($it) {
                  $it['url'] = UPLOAD_URL.$it['url'];
                }
				// 返回成功
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作成功';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = (string) $res;
				$this->vdata['content']['photo_info'] = $it;
			} else {
				// 返回失败
				$this->vdata['returnCode'] = '200';
				$this->vdata['returnInfo'] = '操作失败';
				$this->vdata['secure'] = JSON_SECURE;
				$this->vdata['content']['res'] = '';
			}
		}
		$this->_send_json($this->vdata);
	}
    // 个人中心-更改登录密码
    public function edit_pwd() {
        // 验证
        $this->form_validation->set_rules($this->rules['edit_pwd']);
        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode'] = '0011';
            $this->vdata['returnInfo'] = $this->trim_validation_errors();
        } else {
            if ($res = $this->macc->set($this->userinfo['id'], array('pwd' => passwd($this->data['password'])))) {
                // 返回成功
                $this->vdata['returnCode'] = '200';
                $this->vdata['returnInfo'] = '操作成功,请重新登录';
                $this->vdata['secure'] = JSON_SECURE;
            } else {
                // 返回失败
                $this->vdata['returnCode'] = '0011';
                $this->vdata['returnInfo'] = '更新失败,请刷新后重试';
                $this->vdata['secure'] = JSON_SECURE;
            }
        }
        // 返回json数据
        $this->_send_json($this->vdata);
    }


  //个人中心- 更新个人数据
    public function get_update()
    {
        // 验证
        $this->form_validation->set_rules($this->rules['get_update']);
        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode']   = '0011';
            $this->vdata['returnInfo'] = validation_errors();
        } else {
            if ($this->userinfo) { //更新会员表
            if(!isset($this->data['terminalNo'])){
                $this->data['terminalNo']=0;
            }

            if(!empty($this->data['nickname'])){
                $res =  $this->macc->update(array('nickname'=>$this->data['nickname'],'uptimeline'=>time()),array('id'=>$this->userinfo['id']));
                $finfo = $this->mfri->get_all(array('suid'=>$this->userinfo['id']));
                foreach ($finfo as  $f) {
                    $f_res =  $this->mfri->update(array('remarkname'=>$this->data['nickname']),array('id'=>$f['id']));
                }
            }
            if(!empty($this->data['autograph'])){
                $res =  $this->macc->update(array('autograph'=>$this->data['autograph'],'uptimeline'=>time()),array('id'=>$this->userinfo['id']));
            }
            if(!empty($this->data['province'])&&!empty($this->data['city'])){
                $res =  $this->macc->update(array('province'=>$this->data['province'],'city'=>$this->data['city'],'uptimeline'=>time()),array('id'=>$this->userinfo['id']));
            }
            if(!empty($this->data['industry'])){
                $res =  $this->macc->update(array('industry'=>$this->data['industry'],'uptimeline'=>time()),array('id'=>$this->userinfo['id']));
            }
            $info = $this->mvacc->get_info($this->userinfo['id'], $this->userInfoFields, $this->data['terminalNo']);
            $info['provincename']=get_addr($info['province']);
            $info['cityname']=get_addr($info['city']);
            $info['industryname']=get_industry($info['industry']);
            if(!empty($info['endtimeline'])&&$info['endtimeline']<time()){
                $info['level'] = -1;
            }
            if(!empty($info['endtimeline'])){
                $info['endtimeline'] =date("Y-m-d", $info['endtimeline']);
            }
            //返回用户详细数据
            $this->vdata['returnCode']   = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure']     = JSON_SECURE;
            $this->vdata['content']= $info;
        } else {
            // 返回成功
            $this->vdata['returnCode']   = '200';
            $this->vdata['returnInfo'] = '请求失败';
            $this->vdata['secure']     = JSON_SECURE;
            $this->vdata['content']= array();
        }
    }
    // 返回json数据
    $this->_send_json($this->vdata);
    }
   //个人中心- 获取token
    public function get_token()
    {
        // 验证
        $this->form_validation->set_rules($this->rules['get_token']);
        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode']   = '0011';
            $this->vdata['returnInfo'] = validation_errors();
        } else {
            if (!isset($this->data['terminalNo'])) {
                $this->data['terminalNo'] = 0;
            }
            // 返回用户详细数据
            if ($info = $this->mvacc->get_info(array('id' => $this->data['id']), 'nofresh', $this->data['terminalNo'])) {
                $this->vdata['returnCode'] = '200';
                $this->vdata['returnInfo'] = '操作成功';
                $this->vdata['secure'] = JSON_SECURE;
                $this->vdata['content'] = $info;
            } else {
                $this->vdata['returnCode'] = '200';
                $this->vdata['returnInfo'] = '操作失败';
            }
        }
        // 返回json数据
        $this->_send_json($this->vdata);
    }

    public function oldpassword_check($str) {
        if (isset($this->userinfo) && ($this->userinfo['pwd'] == passwd($str))) {
            return true;
        }
        $this->form_validation->set_message('oldpassword_check', '原密码错误！');
        return false;
    }

}
