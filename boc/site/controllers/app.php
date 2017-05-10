<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-5-10
 * Time: 下午3:05
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class app extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('product_model','mproduct');
        $this->load->model('browse_model', 'mbrowse');
        $this->load->model('account_token_model', 'macctoken');
    }
    public function proInfo() {
        $id = $this->input->get('id');
        $token = $this->input->get('token');
        $account = $this->macctoken->get_one(array('token' => $token), 'accountId,expiretime');
        $data = array(
            'cid' => 25,
            'uid' => $account['accountId'],
            'rid' => $id,
            'type'=> 1,
        );
        $this->mbrowse->create_browse($data);
        $language = $this->input->get('language');
        $vdata['header'] =array(
            'title'=> $this->mcfg->get_config('site','title_seo'),
            'tags'=> $this->mcfg->get_config('site','tags'),
            'intro' => $this->mcfg->get_config('site','intro')
        );
        $pro = $this->mproduct->get_one_next($id,"*",$language);

        $vdata['pro'] = $pro;
        $this->load->view('pro_info',$vdata);
    }
}