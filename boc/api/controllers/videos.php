<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-2-13
 * Time: 下午3:48
 */
class videos extends API_Controller
{

    protected $cid = 26;
    protected $Fields = '';

    public function __construct()
    {
        parent::__construct();
        $this->_auto();
        $this->load->model('videos_model', 'mvideos');
        $title = $this->data['language'] == 'ZH' ? 'title' : $this->data['language'] . '_title';
        $content = $this->data['language'] . '_content';
        $this->Fields = 'id,' . $title . ',' . $content . ',photo,click,collection,timeline';
    }
}