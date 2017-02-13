<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Product extends Modules_Controller
 * @author
 */
class Product extends Modules_Controller{
    protected $rules = array(
        "rule" => array(
            array(
                "field" => "title",
                "label" => "标题",
                "rules" => "trim|required"
            )
            ,array(
                "field" => "content",
                "label" => "内容",
                "rules" => "trim"
            )
        )
    );

    public function __construct(){
        parent::__construct();
        $this->load->model('Upload_model','mupload');
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
    public function index($cid=false,$page=1)
    {
        // 栏目路径
        $vdata['cpath']= $this->mcol->get_path_more($this->cid);
        $vdata['cchildren'] = $this->mcol->get_cols($this->cid);
        $title = $this->mcol->get_one($this->cid,"title");
        $vdata['title'] = $title['title'];

        $limit = $this->page_limit;
        $this->input->get('limit',TRUE) and is_numeric($this->input->get('limit')) AND $limit = $this->input->get('limit');

        $order = $this->_index_orders();
        if ($this->input->get('order',TRUE)) {
            // TODO: order
            // $orders = explode("-",$this->input->get('order',TRUE));
            $order = $this->input->get('order',TRUE);
        }
        $where_in = array("cid"=>$this->cid,"ccid"=>$this->ccid);
        // 条件必须
        $where = array_merge($this->_index_where(),$where_in);
        $vdata['pages'] = $this->_pages(site_url($this->class.'/index/'.$this->cid.'/'),$limit,$where,4);
        $vdata['list'] = $this->model->get_list($limit,$limit*($page-1),$order,$where);
        $this->_display($vdata);
    }
    public function create(){
        $this->form_validation->set_rules($this->_get_rule('create'));
        if ($this->form_validation->run() == false) {
            if ($this->input->is_ajax_request() AND is_post()) {
                $vdata['status'] = 0;
                $vdata['msg'] = validation_errors();
                $this->output->set_content_type('application/json')->set_output(json_encode($vdata));
            }else{
                $vdata = array();
                if($this->cid != 22) {
                    $where = array(
                        'cid' => $this->cid - 1,
                        'audit' => 1,
                        'show' => 1,
                    );
                    $vdata['data'] = $this->model->get_all($where,'id,title');
                }
                $this->_display($vdata);
            }
        }else{
            $this->_create();
        }
    }
    protected function _get_rule($rule){
        $rule = parent::_get_rule($rule);
        $rule_cid = array(
            'field'   => 'cid',
            'label'   => lang('modules_cid_change'),
            'rules'   => 'required|numeric|callback_checkcid'
        );
        if($this->cid != 22) {
            $rule_pid = array(
                'field'   => 'pid',
                'label'   => '上级产品ID',
                'rules'   => 'required|numeric|callback_checkpid'
            );
            array_push($rule, $rule_pid);
        }
        array_push($rule, $rule_cid);
        return $rule;
    }
    public function checkpid($pid) {
        if(!$pid) {
            $this->form_validation->set_message('checkpid','请先选择上级产品ID');
            return false;
        }
        $product = $this->model->get_one(array('cid'=>$this->cid - 1,'audit'=>1),'id');
        if(!$product) {
            $this->form_validation->set_message('checkpid','此产品ID不存在，请重新选择');
            return false;
        } else {
            return true;
        }
    }
    protected function _del_after($data){
        $this->model->delete_pids($data);
    }
    protected function _index_where(){
        $arr =array();
        if (isset($_GET['ctype'])) {
            $arr['ctype'] = $_GET['ctype'];
        }
        if (isset($_GET['pid'])) {
            $arr['pid'] = $_GET['pid'];
        }
        return $arr;
    }
    /**
     * @brief 默认编辑页面
     */
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
            if($this->cid != 22) {
                $where = array(
                    'cid' => $this->cid - 1,
                    'audit' => 1,
                    'show' => 1,
                );
                $vdata['data'] = $this->model->get_all($where,'id,title');
            }
            if (!$vdata['it']) {
                $vdata = array('msg'=>'提供的标示是不存在的','status'=>0);
                if ($this->input->is_ajax_request()) {
                    $this->output->set_content_type('application/json')->set_output(json_encode($vdata));
                } else {
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
                $vdata['id'] = $key;
                $ids = $this->model->get_child_ids($vdata['it']['pid']);
                $vdata['ids_count'] = count($ids);
                $vdata['ids'] = $ids ? $ids : 0;
                $this->_display($vdata);
            }
        }else{
            $this->_edit();
        }
    }
    public function childs() {
        $data = $this->model->get_childs($this->input->get('pid'));
        $data = $data ? $data : '';
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
