<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Product extends Modules_Controller
 * @author
 */
class News extends Modules_Controller{
    protected $rules = array(
        "rule" => array(

        )
    );

    public function __construct(){
        parent::__construct();
        $this->load->model('Upload_model','mupload');

    }
    public function create(){
        $this->form_validation->set_rules($this->_get_rule('create'));
        if ($this->form_validation->run() == false) {
            if ($this->input->is_ajax_request() AND is_post()) {
                $vdata['status'] = 0;
                $vdata['msg'] = validation_errors();
                $this->output->set_content_type('application/json')->set_output(json_encode($vdata));
            }else{
                $vdata['ctypes'] = $this->model->get_all(array('cid'=>34,'audit'=>1),'id,title');
                $this->_display($vdata);
            }
        }else{
            $this->_create();
        }
    }
    protected function edit($key=false){
        if (!$key) {
            $key = $this->input->get_post('id',TRUE);
            if ($this->input->is_ajax_request()){
                if (!$key) {
                    $vdata = array('msg'=>'没有提供标识','status'=>0);
                    $this->output->set_content_type('application/json')->set_output(json_encode($vdata));
                }
            }else{
                if (!$key) {
                    if (isset($this->cid)) {
                        $index = '/index/'.$this->cid;
                    }else{
                        $index = '/index';
                    }
                    redirect(site_url($this->class.$index));
                }
            }
        }
        $this->form_validation->set_rules($this->_get_rule('edit'));
        if ($this->form_validation->run() == false) {

            $vdata['it'] = $this->model->get_one($key);
            $vdata['ctypes'] = $this->model->get_all(array('cid'=>34,'audit'=>1),'id,title');
            if (!$vdata['it']) {
                $vdata = array('msg'=>'提供的标示是不存在的','status'=>0);
                if ($this->input->is_ajax_request()) {
                    $this->output->set_content_type('application/json')->set_output(json_encode($vdata));
                }else{
                    $this->load->view('msg',$vdata);
                    return false;
                }
            }

            if ($this->input->is_ajax_request()) {
                if (is_post()) {
                    $vdata['status'] = 0;
                    $vdata['msg'] = validation_errors();
                }
                $this->output->set_content_type('application/json')->set_output(json_encode($vdata));
            }else{
                $this->_display($vdata);
            }
        }else{
            $this->_edit();
        }
    }
    public function _edit_data()
    {
        $form=$this->input->post();
        $form['timeline'] = time();
        return $form;
    }

    // 删除条目时删除文件
    protected function _rm_file($ids)
    {
        $fids = array() ;
        if (is_numeric($ids)) {
            $tmp = $this->model->get_one($ids,'photo');
            $fids = explode(',',$tmp['photo']);
        }else if(is_array($ids)){
            // 使用 字符串where时
            $tmp = $this->model->get_all("`id` in (".implode(',', $ids).")",'photo');
            foreach ($tmp as $key => $v) {
                $fids = array_merge($fids, explode(',',$v['photo']));
            }
        }
        // adminer funs helpers
        unlink_upload($fids);
    }
}
