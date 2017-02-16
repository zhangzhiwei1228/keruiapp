<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Product extends Modules_Controller
 * @author
 */
class Comment extends Modules_Controller{
    protected $rules = array(
        "rule" => array(
            array(
                "field" => "comment",
                "label" => "内容",
                "rules" => "trim|required|min_length[6]"
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
        $where_in = array("comment.cid"=>$this->cid,"comment.ccid"=>$this->ccid);
        // 条件必须
        $where = array_merge($this->_index_where(),$where_in);
        $vdata['pages'] = $this->_pages(site_url($this->class.'/index/'.$this->cid.'/'),$limit,$where,4);
        $vdata['list'] = $this->model->get_list($limit,$limit*($page-1),$order,$where);
        $this->_display($vdata);
    }
    protected function _edit_after($data){
        $this->load->model('msgs_model','mmsgs');
        $comment = $this->model->get_one($data['id']);
        $udata = array(
            'uid' => $comment['uid'],
            'rid' => $data['id'],
            'timeline' => time(),
            'type' => 2
        );
        $msgs = $this->mmsgs->get_one(array('uid'=>$comment['uid'],'type'=>2,'rid'=>$data['id']));
        if($msgs) {
            $update_data = array('comment'=>$comment['comment']);
            $this->mmsgs->update($update_data,'id = '.$msgs['id']);
        } else {
            $this->mmsgs->create($udata);
        }

    }
}
