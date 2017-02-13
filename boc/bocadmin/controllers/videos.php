<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class videos extends Modules_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('videosclass_model','mvideosclass');
		$this->rules = array(
			"rule" => array(
				array(
					"field" => "title",
					"label" => lang('title'),
					"rules" => "trim|required|min_length[1]"
				)
				,array(
					"field" => "timeline",
					"label" => lang('time'),
					"rules" => "trim|strtotime"
				)
				,array(
					"field" => "content",
					"label" => lang('conent'),
					"rules" => "trim"
					// link_create tag 生成
				)
				,array(
					"field" => "photo",
					"label" => lang('photo'),
					"rules" => "trim"
				)
			)
		);

	}
	public function copypro()
    {
        $ids = $this->input->post('ids');

        $rs=$this->model->get_one($ids);

        unset($rs['id']);
        unset($rs['sort_id']);
        unset($rs['timeline']);

        $id = $this->model->create($rs);
        if ($id) {
            $vdata['msg'] = '复制成功，请刷新查看';
            $vdata['status'] = 1;
        }else{
            $vdata['msg'] = '复制失败，请刷新后重试';
            $vdata['status'] = 0;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($vdata));
    }
	protected function _rm_file($ids){
		$fids = array() ;
		if (is_numeric($ids)) {
			$tmp = $this->model->get_one($ids,'photo,files');
			$fids = explode(',',$tmp['photo']);
		}else if(is_array($ids)){
			// 使用 字符串where时
			$tmp = $this->model->get_all("`id` in (".implode(',', $ids).")",'photo,files');
			foreach ($tmp as $key => $v) {
				$fids = array_merge($fids, explode(',',$v['photo']),explode(',',$v['files']));
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
				$where = array(
					'cid' => 29,
					'audit' => 1,
				);
				$vdata['vclass'] = $this->mvideosclass->get_all($where,'id,title');

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

			$where = array(
				'cid' => 29,
				'audit' => 1,
			);
			$vdata['vclass'] = $this->mvideosclass->get_all($where,'id,title');
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
				$this->_display($vdata);
			}
		}else{
			$this->_edit();
		}
	}
}
