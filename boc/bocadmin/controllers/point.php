<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Product extends Modules_Controller
 * @author
 */
class Point extends Modules_Controller{
    protected $rules = array(
        "rule" => array(
            array(
                "field" => "title",
                "label" => "标题",
                "rules" => "trim|required|min_length[6]"
            ),array(
                "field" => "ZH_content",
                "label" => "内容",
                "rules" => "trim"
            )
        )
    );

    public function __construct(){
        parent::__construct();
    }

    public function _edit_data()
    {
        $form=$this->input->post();
        $form['timeline'] = time();
        return $form;
    }
}
