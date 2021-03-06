<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * Class other extends MY_Controller
 * @author xiejianwei
 * 移动端用户接口类 其他
 */
class other extends API_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('page_model', 'mpage');
        $this->load->model('feedback_model', 'mfeedback');
        $this->load->model('links_model', 'mlinks');
    }

	protected $rules = array(
        "list_get" => array(
            array(
                "field" => "token",
                "label" => "Token",
                "rules" => "trim",
            )
        ),
        "feed" => array(
            array(
                'field' => 'content',
                'label' => '内容',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'title',
                'label' => '联系方式',
                'rules' => 'trim|required',
            ),
            array(
                "field" => "token",
                "label" => "Token",
                "rules" => "trim|required",
            )
        )
	);


    //关于我们
    public function about() {
        // 验证
        $this->form_validation->set_rules($this->rules['list_get']);
        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode'] = '0011';
            $this->vdata['returnInfo'] = $this->trim_validation_errors();
        } else {
            $where = array('cid'=>9);
            $list = $this->mpage->get_one($where);
            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure'] = JSON_SECURE;
            $this->vdata['content'] = $list;
        }
        // 返回json数据
        $this->_send_json($this->vdata);
    }
    //购买协议
    public function about_xy() {
        // 验证
        $this->form_validation->set_rules($this->rules['list_get']);
        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode'] = '0011';
            $this->vdata['returnInfo'] = $this->trim_validation_errors();
        } else {
            $where = array('cid'=>10);
            $list = $this->mpage->get_one($where);
            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure'] = JSON_SECURE;
            $this->vdata['content'] = $list;
        }
        // 返回json数据
        $this->_send_json($this->vdata);
    }
  //购买协议---服务协议
    public function about_ser() {
        // 验证
        $this->form_validation->set_rules($this->rules['list_get']);
        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode'] = '0011';
            $this->vdata['returnInfo'] = $this->trim_validation_errors();
        } else {
            $where = array('cid'=>11);
            $list = $this->mpage->get_one($where);
            $this->vdata['returnCode'] = '200';
            $this->vdata['returnInfo'] = '操作成功';
            $this->vdata['secure'] = JSON_SECURE;
            $this->vdata['content'] = $list;
        }
        // 返回json数据
        $this->_send_json($this->vdata);
    }
  //意见反馈
    public function feedback() {
        // 验证
        $this->form_validation->set_rules($this->rules['feed']);
        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode'] = '0011';
            $this->vdata['returnInfo'] = $this->trim_validation_errors();
        } else {
            if ($this->userinfo) { //更新会员表
                if(!isset($this->data['terminalNo'])){
                    $this->data['terminalNo']=0;
                }
                $info = $this->mvacc->get_info($this->userinfo['id'], '', $this->data['terminalNo']);
                // 组装创建数据
                $create_data = array(
                    'title' => $this->data['title'],
                    'content' => $this->data['content'],
                    'tel' => $info['phone'],
                    'timeline' => time()
                );
                // 创建数据
                if ($id = $this->mfeedback->create($create_data)) {
                    $this->vdata['returnCode'] = '200';
                    $this->vdata['returnInfo'] = '操作成功';
                    $this->vdata['secure'] = JSON_SECURE;
                } else {
                    $this->vdata['returnCode'] = '0011';
                    $this->vdata['returnInfo'] = '操作失败';
                }
            }
        }
        // 返回json数据
        $this->_send_json($this->vdata);
    }
    //分享信息
    public function share() {
        // 验证
        $this->form_validation->set_rules($this->rules['list_get']);
        // validate验证结果
        if ($this->form_validation->run('', $this->data) == false) {
            // 返回失败
            $this->vdata['returnCode'] = '0011';
            $this->vdata['returnInfo'] = $this->trim_validation_errors();
        } else {
            $where = array('audit'=>1);
            $list = $this->mlinks->get_one($where);
            // 拉取数据
            if (!empty($list)) {
                if(!empty($list['photo'])){
                    $url=tag_photo($list['photo']);
                    if(!empty($url)){
                        $list['photo']=UPLOAD_URL.tag_photo($list['photo']);
                    }else{
                        $list['photo']=null;
                    }

                }
                $this->vdata['returnCode'] = '200';
                $this->vdata['returnInfo'] = '操作成功';
                $this->vdata['secure'] = JSON_SECURE;
                $this->vdata['content'] = $list;
            } else {
                $this->vdata['returnCode'] = '200';
                $this->vdata['returnInfo'] = '操作失败';
                $this->vdata['content'] = array();
            }
        }
        // 返回json数据
        $this->_send_json($this->vdata);
    }
    //语言选择
    public function language() {
        $this->load->model('language_model', 'mlanguage');
        $language = $this->mlanguage->get_all(array('cid'=>18 ,'audit' => 1),'id,title');
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $language ;
        $this->_send_json($this->vdata);
    }
    //语言选择
    public function area() {
        $this->load->model('language_model', 'mlanguage');
        $lang = $this->data['language'];
        $fieds = $lang.'_title';
        if($lang == 'ZH') $fieds = 'title';
        $language = $this->mlanguage->get_all(array('cid'=>19 ,'audit' => 1),'id,'.$fieds);
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $language ;
        $this->_send_json($this->vdata);
    }
    //请求的链接
    public function url() {
        $type = $this->data['type'];
        //1 proInfo 2 videoInfo 3 news 4 secret 5 about 6 replyDetail
        switch($type) {
            case 1:
                $url = SITE_URL.('app/proInfo?id='.$this->data['id'].'&token='.$this->data['token'].'&language='.$this->data['language']);
                break;
            case 2:
                $url = SITE_URL.('app/videoInfo?id='.$this->data['id'].'&token='.$this->data['token'].'&language='.$this->data['language']);
                break;
            case 3:
                $url = SITE_URL.('app/news?id='.$this->data['id'].'&token='.$this->data['token'].'&language='.$this->data['language']);
                break;
            case 4:
                $url = SITE_URL.('app/secret?language='.$this->data['language']);
                break;
            case 5:
                $url = SITE_URL.('app/about?language='.$this->data['language']);
                break;
            case 6:
                //http://www.kerui.com/app/replyDetail?id=9
                $url = SITE_URL.('app/replyDetail?id='.$this->data['id']);
                break;
        }
        $this->vdata['returnCode'] = '200';
        $this->vdata['returnInfo'] = '操作成功';
        $this->vdata['secure'] = JSON_SECURE;
        $this->vdata['content'] = $url ;
        $this->_send_json($this->vdata);
    }
}
