<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 17-1-18
 * Time: 下午5:56
 */
/**
 * @param $type
 * @return array|bool
 */
function get_code($type) {
    $CI =& get_instance();
    if (!isset($CI->mlanguage)) {
        $CI->load->model('language_model','mlanguage');
    }
    $language = $CI->mlanguage->get_one(array('cid'=>18,'title'=>$type));
    if(!$language) return false;
    switch($type) {
        case 'ZH':
            return array(
                'Login_fail'=>array(
                    'code' => 10001,
                    'msg' => '登录失败'
                ),
            );
            break;
        case 'EN':
            return array(

            );
            break;
        case 'RU':
            return array(

            );
            break;
        case 'FR':
            return array(

            );
            break;
        case 'ES':
            return array(

            );
            break;
        default:
            return array(

            );
            break;
    }
}
