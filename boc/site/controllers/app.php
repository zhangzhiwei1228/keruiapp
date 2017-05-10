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

        $this->load->model('browse_model', 'mbrowse');
        $this->load->model('account_token_model', 'macctoken');
    }
    public function proInfo() {
        //http://www.kerui.com/index.php/app/proInfo?id=49&token=HjtP1FiDgZsw2O3SyCw2axjNYwDihyqGh78meAJ5t6v9xExz2orRcRf1EiZlWGs5&language=ZH
        $this->load->model('product_model','mproduct');
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
    public function videoInfo() {
        //http://www.kerui.com/index.php/app/videoInfo?id=1&token=HjtP1FiDgZsw2O3SyCw2axjNYwDihyqGh78meAJ5t6v9xExz2orRcRf1EiZlWGs5&language=ZH
        $this->load->model('videos_model','mvideos');
        $id = $this->input->get('id');
        $token = $this->input->get('token');
        $account = $this->macctoken->get_one(array('token' => $token), 'accountId,expiretime');
        $data = array(
            'cid' => 26,
            'uid' => $account['accountId'],
            'rid' => $id,
            'type'=> 2,
        );
        $this->mbrowse->create_browse($data);
        $language = $this->input->get('language');
        $vdata['header'] =array(
            'title'=> $this->mcfg->get_config('site','title_seo'),
            'tags'=> $this->mcfg->get_config('site','tags'),
            'intro' => $this->mcfg->get_config('site','intro')
        );
        $pro = $this->mvideos->get_one_next($id,"*",$language);
        $photo = one_upload($pro['photo']);
        $video = one_upload($pro['files']);
        $vdata['pro'] = $pro;
        $vdata['photo'] = $photo['url'];
        $vdata['video'] = $video['url'];
        //$videos = $this->mvideos->get_all(array('id <>'=>$id,'audit'=>1,'flag'=>1));
        $videos = $this->mvideos->get_list(4, 0, false, array('id <>'=>$id,'audit'=>1,'flag'=>1), '*');
        photo2url($videos);
        //var_dump($videos);die();
        $vdata['videos'] = $videos;
        $this->load->view('video_info',$vdata);
    }
    public function news() {

    }
}