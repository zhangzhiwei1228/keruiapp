<?php  if (! defined('BASEPATH')) {
     exit('No direct script access allowed');
 }



if (!function_exists('push_jpush')) {
   /**
   * 极光推送
   */
  function push_jpush($receiver, $content, $content_type='text', $extras=array(), $silence=false, $push_uri=0)
  {
    include_once LIBS_PATH . 'libraries/jpush/jpush.class.php';
    $jpush = new Jpush();
    $res = $jpush->push($receiver, $content, $content_type='text', $extras, $silence);
    return $res;
  }
}

if (!function_exists('sf_route')) {
  /**
  * 顺风测试
  * 发送soap请求  test no: 444513829069
  */
  function sf_route($no)
  {
    include_once LIBS_PATH . 'libraries/sf/sf_service.php';
    $sf = new sf_service('RouteService', array('route_mailno'=>$no));
    $res = $sf->go();
    $res = json_decode($res, 1);
    return $res;
    // print_r($res);
  }
}

if (!function_exists('sf_search')) {
  /**
  * 顺风测试
  * 发送soap请求  test no: 444513829069
  */
  function sf_search($no)
  {
    include_once LIBS_PATH . 'libraries/sf/sf_service.php';
    $sf = new sf_service('OrderSearchService', array('route_mailno'=>$no));
    $res = $sf->go();
    $res = json_decode($res, 1);
    return $res;
    // print_r($res);
  }
}

if (!function_exists('ordernum_time')) {
  // 生成订单时用
  // @param $insert  插入内容
  function ordernum_time($insert='', $noline=false)
  {
    $base = date('ymdHis', time());
    if ($insert) {
      return $base.$insert.nanoSecond();
    } else {
      if ($noline) {
        return $base.nanoSecond();
      } else {
        return $base.'-'.nanoSecond();
      }
    }
  }
}

// 获取请求头
if (!function_exists('getallheaders_for_hook')) {
  function getallheaders_for_hook()
  {
    foreach ($_SERVER as $name => $value) {
      if (substr($name, 0, 5) == 'HTTP_') {
        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
      }
    }
    return $headers;
  }
}

// API拦截以及验证
function apiValidate($data)
{
    if (is_post()) {
        //验证数据
        //TODO .. 拦截数据请求
        // 加载数据加密模块
        if (!isset($CI)) { $CI =& get_instance(); }
        $CI->load->library('AES');
        // 判断content
        if (isset($data['content'])) {
            $content = AES::decrypt($data['content'], KEY);
            $content = json_decode($content, 1);
            // 解密前打印日志
            logfile('解密前数据:'.$data['content'], 'apiValidate_');
            // 过滤数据
            if ($content) {
                //全局赋值
                $content = array_merge($data, $content['content']);
                $rules = array(
                    array(
                        "field" => "terminalNo",
                        "label" => "手机终端类型",
                        "rules" => "trim|required|numeric|max_length[1]|regex_match[/^[1234]*$/]"
                    ),
                    array(
                        "field" => "secure",
                        "label" => "是否加密",
                        "rules" => "trim|numeric|max_length[1]"
                    )
                );
                // 验证
                $CI->form_validation->set_rules($rules);
                // validate验证结果
                if ($CI->form_validation->run('', $content) == false) {
                    // 返回失败
                    return false;
                }
                // 解密后打印日志
                logfile('解密后数据:'.print_r($content, 1), 'apiValidate_');
                return $content;
            } else {
                //返回失败
                return false;
            }
        }
    } else {
        //返回失败
        return false;
    }
}

function getKuaidi100List($code='', $field='')
{
    // 加载数据加密模块
    if (!isset($CI)) { $CI =& get_instance(); }
    $CI->load->model('express_model', 'mexpress');

    $res = $CI->mexpress->get_all(array('status'=>1), 'code as id, title');

    if ($code) {
        foreach ($res as $k => $v) {
            if ($v['id'] == $code) {
                if ($field) {
                    return $v[$field];
                } else {
                    return $v;
                }
            }
        }
    }

    if ($field) {
      return '暂无数据';
    }

    return $res;
}

function getKuaidi100Data()
{
    $res = array(
        array(
        'code'=>'aae',
        'title'=>'aae全球专递',
        'status' => '0'
        ),
        array(
        'code'=>'anjie',
        'title'=>'安捷快递',
        'status' => '0'
        ),
        array(
        'code'=>'anxindakuaixi',
        'title'=>'安信达快递',
        'status' => '0'
        ),
        array(
        'code'=>'biaojikuaidi',
        'title'=>'彪记快递',
        'status' => '0'
        ),
        array(
        'code'=>'bht',
        'title'=>'bht',
        'status' => '0'
        ),
        array(
        'code'=>'baifudongfang',
        'title'=>'百福东方国际物流',
        'status' => '0'
        ),
        array(
        'code'=>'coe',
        'title'=>'中国东方（COE）',
        'status' => '0'
        ),
        array(
        'code'=>'changyuwuliu',
        'title'=>'长宇物流',
        'status' => '0'
        ),
        array(
        'code'=>'datianwuliu',
        'title'=>'大田物流',
        'status' => '0'
        ),
        array(
        'code'=>'debangwuliu',
        'title'=>'德邦物流',
        'status' => '0'
        ),
        array(
        'code'=>'dhl',
        'title'=>'dhl',
        'status' => '0'
        ),
        array(
        'code'=>'dpex',
        'title'=>'dpex',
        'status' => '0'
        ),
        array(
        'code'=>'dsukuaidi',
        'title'=>'d速快递',
        'status' => '0'
        ),
        array(
        'code'=>'disifang',
        'title'=>'递四方',
        'status' => '0'
        ),
        array(
        'code'=>'ems',
        'title'=>'ems快递',
        'status' => '1'
        ),
        array(
        'code'=>'fedex',
        'title'=>'fedex（国外）',
        'status' => '0'
        ),
        array(
        'code'=>'feikangda',
        'title'=>'飞康达物流',
        'status' => '0'
        ),
        array(
        'code'=>'fenghuangkuaidi',
        'title'=>'凤凰快递',
        'status' => '0'
        ),
        array(
        'code'=>'feikuaida',
        'title'=>'飞快达',
        'status' => '0'
        ),
        array(
        'code'=>'guotongkuaidi',
        'title'=>'国通快递',
        'status' => '0'
        ),
        array(
        'code'=>'ganzhongnengda',
        'title'=>'港中能达物流',
        'status' => '0'
        ),
        array(
        'code'=>'guangdongyouzhengwuliu',
        'title'=>'广东邮政物流',
        'status' => '0'
        ),
        array(
        'code'=>'gongsuda',
        'title'=>'共速达',
        'status' => '0'
        ),
        array(
        'code'=>'huitongkuaidi',
        'title'=>'汇通快运',
        'status' => '1'
        ),
        array(
        'code'=>'hengluwuliu',
        'title'=>'恒路物流',
        'status' => '0'
        ),
        array(
        'code'=>'huaxialongwuliu',
        'title'=>'华夏龙物流',
        'status' => '0'
        ),
        array(
        'code'=>'haihongwangsong',
        'title'=>'海红',
        'status' => '0'
        ),
        array(
        'code'=>'haiwaihuanqiu',
        'title'=>'海外环球',
        'status' => '0'
        ),
        array(
        'code'=>'jiayiwuliu',
        'title'=>'佳怡物流',
        'status' => '0'
        ),
        array(
        'code'=>'jinguangsudikuaijian',
        'title'=>'京广速递',
        'status' => '0'
        ),
        array(
        'code'=>'jixianda',
        'title'=>'急先达',
        'status' => '0'
        ),
        array(
        'code'=>'jjwl',
        'title'=>'佳吉物流',
        'status' => '0'
        ),
        array(
        'code'=>'jymwl',
        'title'=>'加运美物流',
        'status' => '0'
        ),
        array(
        'code'=>'jindawuliu',
        'title'=>'金大物流',
        'status' => '0'
        ),
        array(
        'code'=>'jialidatong',
        'title'=>'嘉里大通',
        'status' => '0'
        ),
        array(
        'code'=>'jykd',
        'title'=>'晋越快递',
        'status' => '0'
        ),
        array(
        'code'=>'kuaijiesudi',
        'title'=>'快捷速递',
        'status' => '0'
        ),
        array(
        'code'=>'lianb',
        'title'=>'联邦快递（国内）',
        'status' => '0'
        ),
        array(
        'code'=>'lianhaowuliu',
        'title'=>'联昊通物流',
        'status' => '0'
        ),
        array(
        'code'=>'longbanwuliu',
        'title'=>'龙邦物流',
        'status' => '0'
        ),
        array(
        'code'=>'lijisong',
        'title'=>'立即送',
        'status' => '0'
        ),
        array(
        'code'=>'lejiedi',
        'title'=>'乐捷递',
        'status' => '0'
        ),
        array(
        'code'=>'minghangkuaidi',
        'title'=>'民航快递',
        'status' => '0'
        ),
        array(
        'code'=>'meiguokuaidi',
        'title'=>'美国快递',
        'status' => '0'
        ),
        array(
        'code'=>'menduimen',
        'title'=>'门对门',
        'status' => '0'
        ),
        array(
        'code'=>'ocs',
        'title'=>'OCS',
        'status' => '0'
        ),
        array(
        'code'=>'peisihuoyunkuaidi',
        'title'=>'配思货运',
        'status' => '0'
        ),
        array(
        'code'=>'quanchenkuaidi',
        'title'=>'全晨快递',
        'status' => '0'
        ),
        array(
        'code'=>'quanfengkuaidi',
        'title'=>'全峰快递',
        'status' => '0'
        ),
        array(
        'code'=>'quanjitong',
        'title'=>'全际通物流',
        'status' => '0'
        ),
        array(
        'code'=>'quanritongkuaidi',
        'title'=>'全日通快递',
        'status' => '0'
        ),
        array(
        'code'=>'quanyikuaidi',
        'title'=>'全一快递',
        'status' => '0'
        ),
        array(
        'code'=>'rufengda',
        'title'=>'如风达',
        'status' => '0'
        ),
        array(
        'code'=>'santaisudi',
        'title'=>'三态速递',
        'status' => '0'
        ),
        array(
        'code'=>'shenghuiwuliu',
        'title'=>'盛辉物流',
        'status' => '0'
        ),
        array(
        'code'=>'shentong',
        'title'=>'申通',
        'status' => '1'
        ),
        array(
        'code'=>'shunfeng',
        'title'=>'顺丰',
        'status' => '1'
        ),
        array(
        'code'=>'sue',
        'title'=>'速尔物流',
        'status' => '0'
        ),
        array(
        'code'=>'shengfeng',
        'title'=>'盛丰物流',
        'status' => '0'
        ),
        array(
        'code'=>'saiaodi',
        'title'=>'赛澳递',
        'status' => '0'
        ),
        array(
        'code'=>'tiandihuayu',
        'title'=>'天地华宇',
        'status' => '0'
        ),
        array(
        'code'=>'tiantian',
        'title'=>'天天快递',
        'status' => '1'
        ),
        array(
        'code'=>'tnt',
        'title'=>'tnt',
        'status' => '0'
        ),
        array(
        'code'=>'ups',
        'title'=>'ups',
        'status' => '0'
        ),
        array(
        'code'=>'wanjiawuliu',
        'title'=>'万家物流',
        'status' => '0'
        ),
        array(
        'code'=>'wenjiesudi',
        'title'=>'文捷航空速递',
        'status' => '0'
        ),
        array(
        'code'=>'wuyuan',
        'title'=>'伍圆',
        'status' => '0'
        ),
        array(
        'code'=>'wxwl',
        'title'=>'万象物流',
        'status' => '0'
        ),
        array(
        'code'=>'xinbangwuliu',
        'title'=>'新邦物流',
        'status' => '0'
        ),
        array(
        'code'=>'xinfengwuliu',
        'title'=>'信丰物流',
        'status' => '0'
        ),
        array(
        'code'=>'yafengsudi',
        'title'=>'亚风速递',
        'status' => '0'
        ),
        array(
        'code'=>'yibangwuliu',
        'title'=>'一邦速递',
        'status' => '0'
        ),
        array(
        'code'=>'youshuwuliu',
        'title'=>'优速物流',
        'status' => '1'
        ),
        array(
        'code'=>'youzhengguonei',
        'title'=>'邮政包裹挂号信',
        'status' => '1'
        ),
        array(
        'code'=>'youzhengguoji',
        'title'=>'邮政国际包裹挂号信',
        'status' => '1'
        ),
        array(
        'code'=>'yuanchengwuliu',
        'title'=>'远成物流',
        'status' => '0'
        ),
        array(
        'code'=>'yuantong',
        'title'=>'圆通速递',
        'status' => '1'
        ),
        array(
        'code'=>'yuanweifeng',
        'title'=>'源伟丰快递',
        'status' => '0'
        ),
        array(
        'code'=>'yuanzhijiecheng',
        'title'=>'元智捷诚快递',
        'status' => '0'
        ),
        array(
        'code'=>'yunda',
        'title'=>'韵达快运',
        'status' => '1'
        ),
        array(
        'code'=>'yuntongkuaidi',
        'title'=>'运通快递',
        'status' => '0'
        ),
        array(
        'code'=>'yuefengwuliu',
        'title'=>'越丰物流',
        'status' => '0'
        ),
        array(
        'code'=>'yad',
        'title'=>'源安达',
        'status' => '0'
        ),
        array(
        'code'=>'yinjiesudi',
        'title'=>'银捷速递',
        'status' => '0'
        ),
        array(
        'code'=>'zhaijisong',
        'title'=>'宅急送',
        'status' => '1'
        ),
        array(
        'code'=>'zhongtiekuaiyun',
        'title'=>'中铁快运',
        'status' => '1'
        ),
        array(
        'code'=>'zhongtong',
        'title'=>'中通速递',
        'status' => '1'
        ),
        array(
        'code'=>'zhongyouwuliu',
        'title'=>'中邮物流',
        'status' => '1'
        ),
        array(
        'code'=>'zhongxinda',
        'title'=>'忠信达',
        'status' => '0'
        ),
        array(
        'code'=>'zhimakaimen',
        'title'=>'芝麻开门',
        'status' => '0'
        ),


    );

    return $res;
}

if (!function_exists('photo2url')) {
    /**
     * 图片id转url
     * @param  string $ids 上传列表值 '1,2,3[...]'
     * @return array|false 数组或逻辑false
     */
    function photo2url(&$list, $more='false', $islist='true', $field='photo')
    {
        if ($islist == 'true' && !empty($list)) {
            foreach ($list as $k => $v) {
                if ($more == 'false') {
                    if (!empty($v[$field])) {
                        $tmp_url = one_upload($v[$field]);
                        $tmp_urls = null;
                        if (!empty($tmp_url)) {
                            $url1=UPLOAD_URL.$tmp_url['url'];
                            if(substr($tmp_url['url'],0,4)=="http"){
                                $url1=$tmp_url['url'];
                            }
                            $tmp_urls = array('id'=>$tmp_url['id'], 'url'=>$url1);
                        }
                    } else {
                        $tmp_urls = null;
                    }
                    // print_r($tmp_urls);
                    $list[$k][$field] = $tmp_urls;
                } else {
                    if (!empty($v[$field])) {
                        $tmp_list = list_upload($v[$field]);
                        $tmp_urls = array();
                        foreach ($tmp_list as $ks => $vs) {
                            $url2=UPLOAD_URL.$vs['url'];
                            if(substr($vs['url'],0,4)=="http"){
                                $url2=$vs['url'];
                            }
                            array_push($tmp_urls, array('id'=>$vs['id'], 'url'=>$url2));
                        }
                    } else {
                        $tmp_urls = null;
                    }
                    $list[$k][$field] = $tmp_urls;
                }
            }
        } else {
            if ($more == 'false') {
                if (!empty($list[$field])) {
                    $tmpInfo = one_upload($list[$field]);
                    if ($tmpInfo) {
                        $url3=UPLOAD_URL.$tmpInfo['url'];
                        if(substr($tmpInfo['url'],0,4)=="http"){
                            $url3=$tmpInfo['url'];
                        }
                      $list[$field] = array('id'=>$tmpInfo['id'],'url'=>$url3);
                    } else {
                      $list[$field] = null;
                    }
                } else {
                    $list[$field] = null;
                }
            } else {
                $tmp_list = list_upload($list[$field]);
                $tmp_urls = array();

                foreach ($tmp_list as $ks => $vs) {
                     $url4=UPLOAD_URL.$vs['url'];
                    if(substr($vs['url'],0,4)=="http"){
                        $url4=$vs['url'];
                    }
                    array_push($tmp_urls, array('id'=>$vs['id'], 'url'=> $url4));
                }
                $list[$field] = $tmp_urls;
            }
        }
    }
}

if (!function_exists('cutphone')) {
  // 手机号截取
  function cutphone($phone)
  {
    $str = substr($phone, 0, 3).'****'.substr($phone, -4, 4);
    return $str;
  }
}

if (!function_exists('getallheaders')) {
  function getallheaders(){
    $headers = array();
    foreach ($_SERVER as $name => $value)
    {
      if (substr($name, 0, 5) == 'HTTP_')
      {
        $headers[strtolower(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))))] = $value;
      }
    }
    return $headers;
  }
}
