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
        if($videos) {
            photo2url($videos);
        }
        //var_dump($videos);die();
        $vdata['videos'] = $videos;
        $this->load->view('video_info',$vdata);
    }
    public function news() {
        //http://www.kerui.com/index.php/app/news?id=2&token=HjtP1FiDgZsw2O3SyCw2axjNYwDihyqGh78meAJ5t6v9xExz2orRcRf1EiZlWGs5&language=ZH
        $this->load->model('news_model', 'mnews');
        $id = $this->input->get('id');
        $token = $this->input->get('token');
        $account = $this->macctoken->get_one(array('token' => $token), 'accountId,expiretime');
        $data = array(
            'cid' => 35,
            'uid' => $account['accountId'],
            'rid' => $id,
            'type'=> 3,
        );
        $this->mbrowse->create_browse($data);
        $language = $this->input->get('language');
        $vdata['header'] =array(
            'title'=> $this->mcfg->get_config('site','title_seo'),
            'tags'=> $this->mcfg->get_config('site','tags'),
            'intro' => $this->mcfg->get_config('site','intro')
        );
        $pro = $this->mnews->get_one_next($id,"*",$language);
        $vdata['pro'] = $pro;
        $videos = $this->mnews->get_list(4, 0, false, array('id <>'=>$id,'cid'=>35,'audit'=>1,'flag'=>1), '*');
        if($videos) {
            photo2url($videos);
        }
        $vdata['news'] = $videos;
        $this->load->view('active_info',$vdata);
    }
    //保密协议
    public function secret() {
        //http://www.kerui.com/index.php/app/secret?language=ZH
        $this->load->model('page_model','mpage');
        $vdata['header'] =array(
            'title'=> $this->mcfg->get_config('site','title_seo'),
            'tags'=> $this->mcfg->get_config('site','tags'),
            'intro' => $this->mcfg->get_config('site','intro')
        );
        $page = $this->mpage->get_one(array('cid'=>36));
        $language = $this->input->get('language');
        $vdata['content'] = $page[$language.'_content'];
        $this->load->view('safe',$vdata);
    }
    //关于我们
    public function about() {
        //http://www.kerui.com/index.php/app/about?language=ZH
        $this->load->model('page_model','mpage');
        $vdata['header'] =array(
            'title'=> $this->mcfg->get_config('site','title_seo'),
            'tags'=> $this->mcfg->get_config('site','tags'),
            'intro' => $this->mcfg->get_config('site','intro')
        );
        $page = $this->mpage->get_one(array('cid'=>37));
        $language = $this->input->get('language');
        $vdata['content'] = $page[$language.'_content'];
        $this->load->view('about',$vdata);
    }
    //消息详情(管理员回复)
    public function reply(){
        //http://www.kerui.com/app/reply?token=GlqOVKxt8GwAUZYZDzoqAawATXTfSRmY4TmOda9bhvb2K1ROp6qVrqoEAKXSxvMG&language=ZH
        if(isset($this->reg[0])){$page=$this->reg[0];}else{$page=1;}

        $this->load->model('msgs_model','mmsgs');
        $vdata['header'] =array(
            'title'=> $this->mcfg->get_config('site','title_seo'),
            'tags'=> $this->mcfg->get_config('site','tags'),
            'intro' => $this->mcfg->get_config('site','intro')
        );
        $token = $this->input->get('token');
        $account = $this->macctoken->get_one(array('token' => $token), 'accountId,expiretime');
        $where = array();
        $where['msgs.uid'] = $account['accountId'];
        if(isset($this->data['type']) && $this->data['type'] == 4) {
            $where['in'] =array('msgs.type', array(2,3));
        } elseif(isset($this->data['type']) && $this->data['type'] == 1) {
            $where['msgs.type'] = $this->data['type'];
        } else {
            $where['in'] =array('msgs.type', array(1,2,3));
        }
        $limit = 8;
        $count = $this->mmsgs->get_count_all($where);
        $pages = _pages(site_url('/app/reply'),$limit,$count,3);
        $datas = $this->mmsgs->get_list($limit,$limit*($page-1),array('msgs.is_read'=>'asc'),$where);
        //var_dump($datas);
        $vdata['notices'] = $datas;
        $vdata['pages'] = $pages;
        $this->load->view('news',$vdata);
    }
}