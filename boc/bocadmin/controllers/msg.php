<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Product extends Modules_Controller
 * @author
 */
class Msg extends Modules_Controller{
    protected $rules = array(
        "rule" => array(
            array(
                "field" => "content",
                "label" => "内容",
                "rules" => "trim|required|min_length[6]"
            )
        )
    );

    public function __construct(){
        parent::__construct();
        $this->load->model('Upload_model','mupload');
        $this->load->model('language_model','mlanguage');
    }

    public function _edit_data()
    {
        $form=$this->input->post();
        $form['timeline'] = time();
        return $form;
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
        $where_in = array("msg.cid"=>$this->cid,"msg.ccid"=>$this->ccid);
        // 条件必须
        $where = array_merge($this->_index_where(),$where_in);
        $vdata['pages'] = $this->_pages(site_url($this->class.'/index/'.$this->cid.'/'),$limit,$where,4);
        $vdata['list'] = $this->model->get_list($limit,$limit*($page-1),$order,$where,'msg.*,language.id as lid,language.title',false,array('language','language.id=msg.area','right'));
        $this->_display($vdata);
    }
    // 此处返回数组
    protected function _index_orders(){
        return array( 'msg.sort_id'=>'desc' );
    }
    public function create(){
        $this->form_validation->set_rules($this->_get_rule('create'));
        if ($this->form_validation->run() == false) {
            if ($this->input->is_ajax_request() AND is_post()) {
                $vdata['status'] = 0;
                $vdata['msg'] = validation_errors();
                $this->output->set_content_type('application/json')->set_output(json_encode($vdata));
            }else{
                $vdata['area'] = $this->mlanguage->get_all(array('cid'=>19,'audit'=>1));
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
                $vdata['area'] = $this->mlanguage->get_all(array('cid'=>19,'audit'=>1));
                $this->_display($vdata);
            }
        }else{
            $this->_edit();
        }
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
    protected function _create_after($data){
        $this->load->model('account_model','maccount');
        $this->load->model('msgs_model','mmsgs');
        $uids = $this->maccount->get_ids($data['area']);
        $udata = array();
        if($uids) {
            foreach($uids as $key=>$id) {
                $udata[$key] = array(
                    'uid' => $id['id'],
                    'rid' => $data['id'],
                    'timeline' => time(),
                    'type' => 1
                );
            }
            $this->mmsgs->create_array($udata);
        }
    }
}
