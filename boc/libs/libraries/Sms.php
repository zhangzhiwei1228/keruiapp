<?php

/**
*   sms 客户消息发送
*/
class Sms
{

  function __construct()
  {
      # code...
  }

  protected $sender_status = array(
    0 => '发送中',
    101 => '无此用户,请检查短信服务商提供的账号密码',
    102 => '密码错',
    103 => '提交过快（提交速度超过流速限制）',
    104 => '系统忙（因平台侧原因，暂时无法处理提交的短信）',
    105 => '敏感短信（短信内容包含敏感词）',
    106 => '消息长度错（>536或<=0）',
    107 => '包含错误的手机号码',
    108 => '手机号码个数错（群发>50000或<=0）',
    109 => '无发送额度（该用户可用短信数已使用完）',
    110 => '不在发送时间内(验证码通知7*24小时发送)',
    111 => '超出该账户当月发送额度限制',
    112 => '无此产品，用户没有订购该产品',
    113 => 'extno格式错（非数字或者长度不对）',
    115 => '自动审核驳回',
    116 => '签名不合法，未带签名（用户必须带签名的前提下）',
    117 => 'IP地址认证错,请求调用的IP地址不是系统登记的IP地址',
    118 => '用户没有相应的发送权限',
    119 => '用户已过期',
    120 => '内容不在白名单中',
  );

  protected $callback_status = array(
    'DELIVRD' =>'成功',
    'MBBLACK' =>'黑名单号码',
    'NOROUTE' =>'无通道',
    'ROUTEERR' =>'通道异常',
    'REJECT' =>'审核驳回',
    'DISTURB' =>'手机号码发送次数过多',
    'EMSERR' =>'长短信不完整',
    'SIGNERR' =>'签名错',
    'KEYWORD' =>'敏感词',
    'WHITESMS' =>'短信内容不在白名单中',
    'CMPP20ERR:XXX' => '提交到网关时网关返回了XXX的错误码',
    'SMGPERR:XXX' => '提交到网关时网关返回了XXX的错误码',
    'SGIPERR:XXX' => '提交到网关时网关返回了XXX的错误码',
    '**RESP:XXX' =>'提交到网关时网关返回了XXX的错误码',
    'SY1RESP:-9' =>'扩展码错误 不用填写扩展码',
    'DB:0141' =>'用户处在黑名单中',
    'ID:0103' =>'用户欠费',
    'ID:0102' =>'用户停机',
    'ID:0101' =>'手机号码错误',
    'MX:0013' =>'通道黑名单',
    '601' =>'空号 暂停服务',
    'GL:0000  获取频繁' =>'被拦截了',
    'ERR:104' =>'系统忙',
    'REJECT' =>'审核驳回',
    'SIGFAIL' =>'签名没有报备',
    '其他' =>'网关状态',
    // Status from excel of 移动
    'ACCEPTED' => '短消息已经被最终用户接收。',
    'CA:0051' => '尚未建立连接',
    'CA:0052' => '尚未成功登录',
    'CA:0054' => '超时未接收到响应消息',
    'CA:0054' => '发送消息失败',
    'CA:0111' => 'SCP厂家自定义的错误码',
    'CA:XXXX' => 'SCP不返回响应消息时的状态报告。',
    'CB:0001' => '非神州行预付费用户',
    'CB:0001' => '非神州行预付费用户',
    'CB:0001' => '非神州行预付费用户（用户已经被销号）',
    'CB:0002' => '数据库操作失败',
    'CB:0005' => 'PPS用户状态异常（包括未头次使用、储值卡被封锁、储值卡进入保留期、储值卡挂失）',
    'CB:0005' => 'PPS用户状态异常（包括未头次使用、储值卡被封锁、储值卡进入保留期、储值卡挂失）',
    'CB:0005' => '移动用户帐户数据异常',
    'CB:0007' => '用户余额不足',
    'CB:0007' => '用户余额不足',
    'CB:0007' => '用户余额不足',
    'CB:0016' => '参数错误',
    'CB:0018' => '重复发送消息序列号msgid相同的计费请求消息',
    'CB:0022' => 'SCP互联失败',
    'CB:0047' => '过期用户或者用户不支持梦网业务',
    'CB:0053' => '梦网用户不存在',
    'CB:0053' => '梦网用户不存在',
    'CB' => 'SCP鉴权失败。',
    'CB:XXXX' => 'SCP返回错误响应消息时的状态报告。',
    'DA:0054' => '超时未接收到响应消息',
    'DA:0054' => '出现这个问题是由于网关发送鉴权批价请求到MISC时，MISC不给响应或者网关和MISC连接阻塞引起；',
    'DA:XXXX' => 'DSMP不返回响应消息时的状态报告。',
    'DB:0100' => '手机号码不存在',
    'DB:0101' => '手机号码错误',
    'DB:0101' => '手机号码错误',
    'DB:0102' => '用户停机',
    'DB:0102' => '用户停机',
    'DB:0103' => '用户欠费',
    'DB:0104' => '用户没有使用该业务的权限',
    'DB:0105' => '行业网关中没有找到此客户的企业信息，需要BOSS系统进行更新同步！',
    'DB:0105' => '业务代码错误',
    'DB:0106' => '服务代码错误',
    'DB:0107' => '业务不存在',
    'DB:0107' => '业务不存在',
    'DB:0107' => '业务不存在',
    'DB:0108' => '是MAS欠费',
    'DB:0108' => '该业务暂停服务',
    'DB:0109' => '该服务种类不存在',
    'DB:0110' => '该服务种类尚未开通',
    'DB:0111' => '该业务尚未开通',
    'DB:0112' => 'SP代码错误',
    'DB:0113' => '鉴权未通过，客户数据还没有跟BOSS系统同步造成',
    'DB:0113' => 'SP不存在',
    'DB:0114' => '是暂停服务，通常为客户合同到期',
    'DB:0114' => 'SP暂停服务',
    'DB:0115' => '用户没有订购该业务',
    'DB:0115' => '用户没有定购该业务',
    'DB:0116' => '用户暂停订购该业务',
    'DB:0116' => '用户暂停定购该业务',
    'DB:0117' => '该业务不能对该用户开放',
    'DB:0118' => '用户已经订购了该业务',
    'DB:0119' => '用户不能取消该业务',
    'DB:0120' => '话单格式错误',
    'DB:0121' => '没有该类业务',
    'DB:0122' => '接收异常',
    'DB:0122' => '接收异常',
    'DB:0123' => '业务价格为负',
    'DB:0124' => '业务价格格式错误',
    'DB:0125' => '业务价格超出范围',
    'DB:0126' => '该用户不是神州行用户',
    'DB:0127' => '该用户没有足够的余额',
    'DB:0128' => '补款,冲正失败',
    'DB:0129' => '用户已经是梦网用户',
    'DB:0130' => '用户在BOSS中没有相关用户数据',
    'DB:0131' => 'BOSS系统数据同步出错',
    'DB:0132' => '相关信息不存在',
    'DB:0133' => '用户数据同步出错',
    'DB:0134' => 'SP数据同步出错',
    'DB:0135' => '业务数据同步出错',
    'DB:0136' => '用户密码错误',
    'DB:0137' => '伪码信息错误',
    'DB:0138' => '用户相关信息不存在',
    'DB:0140' => '黑白名单因素造成',
    'DB:0141' => '黑名单',
    'DB:0140' => '用户未点播该业务',
    'DB:9001' => '网络异常',
    'DB:9001' => '网络异常',
    'DB:9007' => '业务网关超过限制的流量',
    'DB:9007' => '业务网关超过限制的流量',
    'DB:XXXX' => 'DSMP返回错误响应消息时的状态报告。',
    'DELETED' => '短消息已经被删除。',
    'EXPIRED' => '因为用户长时间关机或者不在服务区等导致的短消息超时没有递交到用户手机上、超时、不处理',
    'IA:XXXX' => '下一级ISMG不返回响应消息时的状态报告。',
    'IB:0008' => '流量控制错',
    'IB:0009' => '前转判断错误',
    'IB:0070' => '网络断连或者目的设备关闭端口',
    'IB:0100' => '移动内部错误',
    'IB:0113' => '移动内部错误',
    'IB:0255' => '移动内部错误',
    'IB:XXXX' => '下一级ISMG返回错误响应消息时的状态报告。',
    'IB' => '前转网关返回的错误响应消息。',
    'IC:0154' => '移动内部错误',
    'IC:XXXX' => '没有从下一级ISMG收到状态报告时的状态报告。',
    'ID:0012' => '是计费地址错误(外省号码，发送失败）',
    'ID:0013' => '异网（电信和联通）号码，发送失败）',
    'ID:0020' => 'SPACE用户鉴权模块：鉴权用户停机或欠费错误。',
    'ID:0021' => 'SPACE用户鉴权模块：用户销户错误。',
    'ID:0063' => '资费代码错误',
    'ID:XXXX' => 'infoX-SMS',
    'ID' => '等待应答超时。',
    'MA:0022' => '频繁获取被拦截',
    'MA:0051' => '尚未建立连接',
    'MA:0054' => '超时未接收到响应消息',
    'MA:0191' => 'SMSC厂家自定义的错误码',
    'MA:XXXX' => 'SMSC不返回响应消息时的状态报告。',
    'MB:0001' => 'Submit包里面的短信内容的长度和短信内容的真实长度不符（比如，短信内容后面有空的字符串）',
    'MB:0019' => '移动内部错误',
    'MB:0020' => '无效的SYSTEMID',
    'MB:0065' => '目的地址错误',
    'MB:0066' => '无效的定时时间',
    'MB:0066' => '短信中心回的，超作最大发送次数、可能是手机满了。',
    'MB:0070' => '移动内部错误',
    'MB:0077' => '移动内部错误',
    'MB:0088' => '移动内部错误',
    'MB:0145' => 'SMSC厂家自定义的错误码',
    'MB:0147' => 'SMSC厂家自定义的错误码',
    'MB:0192' => 'SMSC厂家自定义的错误码',
    'MB:0193' => 'SMSC厂家自定义的错误码',
    'MB:0241' => 'SMSC厂家自定义的错误码',
    'MB:0244' => 'SMSC厂家自定义的错误码',
    'MB:0250' => 'SMSC厂家自定义的错误码',
    'MB:1042' => '被叫用户因关机、内存满，暂时无法接收短信',
    'MB:4024' => '移动内部错误',
    'MB:4025' => '移动内部错误',
    'MB:XXXX' => 'SMSC返回错误响应消息时的状态报告。',
    'MB' => '短信中心返回错误响应。',
    'MC:0015' => '移动内部错误',
    'MC:0021' => '移动内部错误',
    'MC:0055' => '移动内部错误',
    'MC:0151' => '移动内部错误',
    'MC:0199' => '移动内部错误',
    'MC:XXXX' => '没有从SMSC接收到状态报告时的状态报告。',
    'MC:xxxx' => '系统未从短信中心接收到状态报告',
    'MC:xxxx' => '没有从SMSC处接收到状态报告时的状态报告',
    'MH:0000' => '移动内部错误',
    'MH:zzzz' => '其它值。',
    'MI::zzzz' => 'SMSC返回状态报告的状态值为EXPIRED。',
    'MI:0000' => '移动内部错误',
    'MI:0008' => '移动内部错误',
    'MI:0010' => '被叫用户因关机、内存满，暂时无法接收短信',
    'MI:0013' => '移动内部错误',
    'MI:0017' => '被叫手机内存满',
    'MI:0022' => '移动内部错误',
    'MI:0024' => '被叫手机关机',
    'MI:0024' => '移动内部错误',
    'MI:0029' => '移动内部错误',
    'MI:0036' => '移动内部错误',
    'MI:0045' => '移动内部错误',
    'MI:0057' => '移动内部错误',
    'MI:0255' => '移动内部错误',
    'MI:xxxx' => '同“EXPIRED”',
    'MJ:0000' => '移动内部错误',
    'MJ:zzzz' => 'SMSC返回状态报告的状态值为DELETED。',
    'MK:0000' => '无错误',
    'MK:0001' => '号码未分配,确认是否为空号',
    'MK:0004' => '被叫用户无短信功能，一般为欠费停机导致',
    'MK:0005' => '被叫用户无短信功能，一般为欠费停机导致',
    'MK:0008' => '移动内部错误',
    'MK:0009' => '非法用户',
    'MK:0010' => '被叫用户因关机、内存满，暂时无法接收短信',
    'MK:0011' => '用户未申请短信业务',
    'MK:0013' => '禁止呼叫',
    'MK:0015' => '被叫手机终端故障导致接收短信失败',
    'MK:0015' => '可能是手机满了。',
    'MK:0017' => '被叫手机内存满',
    'MK:0019' => '接收端手机不支持短信服务',
    'MK:0020' => '接收端手机错误',
    'MK:0021' => '接收方网路不支持短信',
    'MK:0022' => '接收端手机存储器满',
    'MK:0024' => '被叫手机关机',
    'MK:0029' => '用户当前不在网络',
    'MK:0030' => '接收端手机忙',
    'MK:0036' => '网络错误',
    'MK:0044' => '设备IMEI属于EIR的黑名单',
    'MK:0053' => '移动内部错误',
    'MK:0057' => '移动内部错误',
    'MK:0060' => '用户不在服务区',
    'MK:0061' => 'GMSC拥塞',
    'MK:0063' => '查询超时',
    'MK:0064' => 'MSC/SGSN超时',
    'MK:0072' => 'MT拥塞',
    'MK:0080' => '用户不在服务区',
    'MK:0081' => '用户关机',
    'MK:0082' => '限制漫游',
    'MK:0083' => '在HLR中未找到MSC号码',
    'MK:0089' => 'MSC无用户信息',
    'MK:0101' => '系统拥塞',
    'MK:0104' => '错误的SME地址',
    'MK:0112' => '发送方欠费',
    'MK:0113' => '接收方欠费',
    'MK:0114' => '预付费系统错误',
    'MK:0115' => '信息安全鉴权失败',
    'MK:0116' => '消息被过滤',
    'MK:0121' => '待发队列满',
    'MK:0122' => '超过License限制',
    'MK:0129' => 'Relay操作失败',
    'MK:0150' => '消息过期',
    'MK:0152' => '无法匹配原消息',
    'MK:0153' => '某网络设备认为数据错误',
    'MK:0154' => '系统资源短缺',
    'MK:0155' => '目的地不可达',
    'MK:0156' => '消息鉴权失败',
    'MK:0157' => '其他网络或终端无应答',
    'MK:0158' => '网络链接临时错误',
    'MK:0200' => '短信被DCS过滤',
    'MK:0253' => '其他永久性错误',
    'MK:0254' => '其他临时性错误',
    'MK:0255' => '未知错误',
    'MK:xxxx' => '同UNDELIV',
    'MK:zzzz' => 'SMSC返回状态报告的状态值为UNDELIV。',
    'MK' => '短信中心返回的状态报告值，短信状态为UNDELIV.',
    'ML:zzzz' => 'SMSC返回状态报告的状态值为ACCEPTD。',
    'MM:zzzz' => 'SMSC返回状态报告的状态值为UNKNOWN。',
    'MN:xxxx' => '同“REJECTD”',
    'MN:zzzz' => 'SMSC返回状态报告的状态值为REJECTD。',
    'NOROUTE' => '查找路由失败。',
    'REJECTD' => '消息因为某些原因被拒绝',
    'REJECTED' => '短消息被拒绝。',
    'SA:XXXX' => 'SP不返回响应消息时的状态报告。',
    'SB:XXXX' => 'SP返回错误响应消息时的状态报告。',
    'UNDELIV' => '全球通用户因为状态不正确如处于停机、挂起等状态而导致用户无法接收到短信',
    'UNKNOWN' => '未知的短消息状态。',
    // Status from excel of 联通
    'CU0' => '无错误，命令正确接收',
    'CU1' => '非法登陆，如登陆名、口令出错、登录名与口令不符等',
    'CU2' => '重复登录',
    'CU3' => '连接过多，指单个节点要求同时建立的连接数过多。',
    'CU4' => '登录类型错，指bind命令中的logintype字段出错',
    'CU5' => '参数格式错，指命令中参数值与参数类型不符或与协议规定的范围不符',
    'CU6' => '非法手机号码',
    'CU7' => '消息id错',
    'CU8' => '信息长度错',
    'CU9' => '非法序列号，包括序列号重复、序列号格式错误等（手机内存满）',
    'CU10' => '无法接通',
    'CU11' => '节点忙',
    'CU12' => '空号',
    'CU21' => '目的地址不可达，指路由表存在路由且消息路由正确单被路由节点暂时不能提供服务的情况',
    'CU24' => '计费号码无效（暂停使用）',
    'CU25' => '用户不能通信（如不在服务区，未开机等情况）',
    'CU26' => '手机内存不足',
    'CU27' => '手机不支持短信息',
    'CU28' => '手机接收短信出现错误',
    'CU29' => '不知道的用户',
    'CU30' => '不能提供此功能',
    'CU31' => '非法设备',
    'CU32' => '系统失败',
    'CU50' => '短信内容非法',
    'CU56' => '空号停机',
    'CU61' => '不再使用中',
    'CU67' => '联通黑名单',
    'CU86' => '联通黑名单',
    'CU101' => '非法手机号码',
    'CU102' => '停机欠费',
    'CU104' => '空号',
    'CU168' => '空号停机',
    'CU221' => '黑名单用户',
    'CU255' => '停机、空号、关机、暂停使用等',
  );

  protected $balance_status = array(
    '0' => '成功',
    '101' => '无此用户',
    '102' => '密码错',
    '103' => '查询过快（建议每10秒查询一次）',
  );

  protected $msg_code = array(
    // 入库
    '01_inbound_success' => array (
      'code' => '1',
      'title' => '入库',
      // 'txt' => '您有{{expcom_title}}（{{expno}}）到达{{store_title}}，取货编码【{{pickcode}}】（链接：{{pickcode_qr}}），客服电话{{custom_tel}}，地址：{{store_addr}}',
      // 'txt' => '您有{{expcom_title}}快递到达{{store_title}}，取货码{{pickcode}}（链接：http://t.cn/RyhQ2V2），地址：{{store_addr}}',
      // 'txt' => '您快递到达{{store_title}}网点{{custom_tel}}，{{store_addr}}，取货码{{pickcode}}（{{pickcode_qr}}）',
      'txt' => '您的{{expcom_title}}已到达{{store_title}}网点{{custom_tel}}，取货码{{pickcode}}，{{store_addr}}',
      'wx' => '您有{{expcom_title}}（{{expno}}）到达{{store_title}}，取货编码【{{pickcode}}】，客服电话{{custom_tel}}，地址：{{store_addr}}。取货二维码：'
    ),
    '02_shelf_change' => array (
      'code' => '2',
      'title' => '货架变动',
      // 'txt' => '您的{{expcom_title}}（{{expno}}）由{{store_title}}员工移动了位置，新的取货编码【{{pickcode}}】（链接：{{pickcode_qr}}），客服电话{{custom_tel}}，地址：{{store_addr}}',
      // 'txt' => '您的{{expcom_title}}（{{expno}}）移动了位置，新取货编码【{{pickcode}}】地址：{{store_addr}}',
      'txt' => '货架变动，{{pickcode}}（{{pickcode_qr}}），{{store_title}}网点{{custom_tel}}，{{store_addr}}',
      'wx' => '您的{{expcom_title}}（{{expno}}）由{{store_title}}员工移动了位置，新的取货编码【{{pickcode}}】，客服电话{{custom_tel}}，地址：{{store_addr}}。取货二维码：'
    ),
    '03_taken' => array (
      'code' => '3',
      'title' => '取件',
      'txt' => '您的{{expcom_title}}（{{expno}}）已于9月10日10点取件。',
      'wx' => '您的{{expcom_title}}（{{expno}}）已于今日10点取件。'
    ),
    '04_returned' => array (
      'code' => '4',
      'title' => '快递退回',
      'txt' => '您的{{expcom_title}}（{{expno}}）由{{store_title}}进行了快递退回操作，如有疑问可联系我们客服电话{{custom_tel}}，地址：{{store_addr}}',
      'wx' => '您的{{expcom_title}}（{{expno}}）由{{store_title}}进行了快递退回操作，如有疑问可联系我们客服客服电话{{custom_tel}}，地址：{{store_addr}}'
    ),
    '05_delivered' => array (
      'code' => '5',
      'title' => '送货上门',
      'txt' => '您的{{expcom_title}}（{{expno}}）由{{store_title}}{{sender_name}}（送货人姓名）送货上门并签收，如有疑问可联系我们客服电话{{custom_tel}}，地址：{{store_addr}}',
      'wx' => '您的{{expcom_title}}（{{expno}}）由{{store_title}}{{sender_name}}（送货人姓名）送货上门并签收，如有疑问可联系我们客服电话{{custom_tel}}，地址：{{store_addr}}'
    ),
  );

  // 快单数据模样方式发送
  public function send($msg_type, $shelforder) {
  	$CI =& get_instance();
  	if (!isset($CI->mexpress)) { $CI->load->model('express_model','mexpress'); }
  	if (!isset($CI->mstore)) { $CI->load->model('store_model','mstore'); }
  	if (!isset($CI->mdistrict)) { $CI->load->model('district_model','mdistrict'); }
  	if (!isset($CI->mshelforder_msg)) { $CI->load->model('shelforder_msg_model','mshelforder_msg'); }

    $history_tmp = $CI->mshelforder_msg->get_one(array(
      'shelforder_id' => $shelforder['id'],
      'shelforder_timeline' => $shelforder['timeline'],
    ));

    // 60s防重复发送
    if ($history_tmp && ((time()-$history_tmp['timeline']) < 60)) {
      $res_new = array(
        'res' => false,
        'msg' => '一分钟内只允许发送一次，请'.(60-(time()-$history_tmp['timeline'])).'秒后再发送消息',
        'count' => 60-(time()-$history_tmp['timeline'])
      );
      return $res_new;
    }

    $express_info = $CI->mexpress->get_one($shelforder['expcom']);
    $store_info = $CI->mstore->get_one($shelforder['store_id']);
    $store_addr = '';
    if ($store_info) {
      // $store_addr = $CI->mdistrict->transIds($store_info['provice_id'], $store_info['city_id']).$store_info['area'];
      $store_addr = $store_info['area'];
    } else {
      $store_addr = '无';
    }

    $qr_url_raw = API_URL.'index.php/qr?d='.$shelforder['pickcode'];
    $qr_url = $this->shorturl($qr_url_raw);

    $replace_params_kv = array(
      '{{expcom_title}}' => $express_info['title'],
      '{{expno}}' => $shelforder['expno'],
      '{{store_title}}' => $store_info['title'],
      '{{sender_name}}' => $shelforder['sender_name'],
      '{{pickcode}}' => $shelforder['pickcode'],
      // '{{pickcode_qr}}' => site_url('qr?d='.$shelforder['pickcode']),
      '{{pickcode_qr}}' => $qr_url,
      '{{custom_tel}}' => $store_info['custom_tel'],
      '{{store_addr}}' => $store_addr,
    );

    $msg_tpl = $this->msg_code[$msg_type];
    $msg = str_replace(array_keys($replace_params_kv), array_values($replace_params_kv), $msg_tpl['txt']);
    // logfile($msg, 'sms/sms_msg_');

    $res_new = array();
    $shelforder_update = array();
    $data_create = array();
    $data_create['phone'] = $shelforder['to_phone'];
    $data_create['shelforder_id'] = $shelforder['id'];
    $data_create['shelforder_timeline'] = $shelforder['timeline'];

    if (isset($shelforder['to_phone']) && $shelforder['to_phone'] && preg_match('/^[(86)|0]?(1\d{10})|(13\d{9})|(15\d{9})|(18\d{9}|(1\d{10}))$/', $shelforder['to_phone'])) {
      $res = $this->_sender($shelforder['to_phone'], $msg);
      $data_create['msg_type'] = 1;
      $data_create['msg_code'] = $msg_tpl['code'];
      $data_create['content'] = $msg;
      $data_create['sender_status'] = isset($res['1'])?$res['1']:'';
      $data_create['sender_msgid'] = isset($res['2'])?$res['2']:'';
      $data_create['sender_raw'] = is_array($res)?implode(',', $res):'';
      $data_create['timeline'] = time();

      if (sizeof($res) == 3) {
        // 记录发送记录
        $CI->mshelforder_msg->create($data_create);
        $res_new = array(
          'res' => true,
          'msg' => '发送成功'
        );
        $shelforder_update['msg_status'] = 0;
      } else {
        $res_new = array(
          'res' => false,
          'msg' => $this->sender_status[$res[1]]
        );
        $shelforder_update['msg_status'] = $res[1];
      }
      // 1:短信，2微信
      $shelforder_update['msg_type'] = 1;
    } else {
      if (isset($shelforder['to_phone'])) {
        logfile('手机号格式错误：'.$shelforder['to_phone'], 'sms/sms_failed_');
      } else {
        logfile('手机号为空。', 'sms/sms_failed_');
      }
      $res_new = array(
        'res' => false,
        'msg' => (isset($shelforder['to_phone']) && $shelforder['to_phone'])?'手机号格式错误':'手机号为空'
      );
      $shelforder_update['msg_type'] = 1;
      $shelforder_update['msg_status'] = 400;
    }

    // 更新订单消息发送状态
    if ($shelforder_update && isset($data_create) && isset($data_create['shelforder_id']) && isset($data_create['shelforder_timeline'])) {
      $CI->load->model('shelforder_model','mshelforder');
      $CI->mshelforder->update($shelforder_update, array('id'=>$data_create['shelforder_id'], 'timeline'=>$data_create['shelforder_timeline']));
    }

    return $res_new;
  }

  public function _sender($phone,$msg) {
    #示远科技验证码短信平台
    //header("Content-Type: text/html; charset=utf-8");
    $post_data = array();
    $post_data['account'] = SMS_ACCOUNT;   //帐号
    $post_data['pswd'] = SMS_PWD;  //密码
    $post_data['msg'] =urlencode($msg); //短信内容需要用urlencode编码下
    $post_data['mobile'] = $phone; //手机号码， 多个用英文状态下的 , 隔开
    $post_data['product'] = ''; //产品ID  不用填写
    $post_data['needstatus'] = 'true'; //是否需要状态报告，需要true，不需要false
    $post_data['extno'] = '';  //扩展码      不用填写
    // $url='http://120.26.69.248/msg/HttpSendSM';
    $url='http://send.18sms.com/msg/HttpBatchSendSM';
    $o='';
    foreach ($post_data as $k=>$v)
    {
       $o.="$k=".urlencode($v).'&';
    }
    $post_data=substr($o,0,-1);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
    $result = curl_exec($ch);
    logfile('raw('.$phone.':'.$msg.'):'.$result, 'sms/sms_log_');
    $result = preg_replace("/\s/", ",", trim($result));
    $result_arr = explode(',', $result);
    return $result_arr;
  }

  // 额度查询接口
  public function balance() {
    // 60秒更新一次
    if (get_config_site('sms','balance_time') < (time()-60)) {
      // 短信额度
      $tmp = $this->_get_balance();
      if (isset($tmp[2]) && is_numeric($tmp[2])) {
        set_config_site('sms','balance', $tmp[2]);
        set_config_site('sms','balance_time', time());
      }
    }
  }

  function _get_balance() {
    // post 方式
    $target = "http://120.26.69.248/msg/QueryBalance";
    $post_data = "account=".SMS_ACCOUNT."&pswd=".SMS_PWD;

    $url = $target.'?'.$post_data;
    $gets = file_get_contents($url);
    $gets = explode(',', $gets);
    return $gets;
  }

  // 短信改善状态回调
  public function status_callback($res=array()) {
    // 接收到请求示例 http://121.41.128.239:8080/ybgj/web/api/index.php/callback/sms_status?receiver=bocadmin&pswd=JBhkQEStO1syqCVq&msgid=1000928094002038800&status=DELIVRD
    // receiver: bocadmin
    // pswd: JBhkQEStO1syqCVq
    logfile('GET:'.print_r($res, 1), 'sms/sms_status_');
    if (isset($res['msgid']) && isset($res['receiver']) && isset($res['pswd']) && ($res['receiver']==get_config_site('sms_callback','receiver')) && ($res['pswd']==get_config_site('sms_callback','pswd'))) {
      $CI =& get_instance();
      $CI->load->model('shelforder_msg_model','mshelforder_msg');
      $msgInfo = $CI->mshelforder_msg->get_one(array('sender_msgid'=>$res['msgid']));
      if ($msgInfo) {
        $msgUpdate = array();
        $msgUpdate['sender_msgid_status'] = is_numeric($res['status'])?'CU'.$res['status']:$res['status'];
        $CI->mshelforder_msg->update($msgUpdate, array('id'=>$msgInfo['id'], 'timeline'=>$msgInfo['timeline']));
        // 同步更新订单消息发送状态
        if ($res['status'] == 'DELIVRD') {
          $CI->load->model('shelforder_model','mshelforder');
          $CI->mshelforder->update(array('msg_status'=>$msgUpdate['sender_msgid_status'], 'msg_type'=>1), array('id'=>$msgInfo['shelforder_id'], 'timeline'=>$msgInfo['shelforder_timeline']));
        }
      }
    }
  }

  public function _status_update($msgInfo=array())
  {
    if ($msgInfo) {
      // 同步更新订单消息发送状态
      $CI->load->model('shelforder_model','mshelforder');
      $CI->mshelforder->update(array('msg_status'=>$msgInfo['sender_msgid_status'], 'msg_type'=>1), array('id'=>$msgInfo['shelforder_id'], 'timeline'=>$msgInfo['shelforder_timeline']));
    }
  }

  // 获取解析快单数据短信发送状态
  public function get_status($shelforder=array()) {
  	$CI =& get_instance();
  	if (!isset($CI->mshelforder_msg)) { $CI->load->model('shelforder_msg_model','mshelforder_msg'); }

    $res = array();
    if ($shelforder) {
      $msg_info = $CI->mshelforder_msg->get_one(array('shelforder_id'=>$shelforder['id'], 'shelforder_timeline'=>$shelforder['timeline']));
      if (!$msg_info) {
        $res['status'] = 401; // 发送失败
        $res['status_title'] = '发送失败';
        $res['send_type'] = '无';

        // todo::补充内容 更新完后应删除
        $CI->load->model('shelforder_model','mshelforder');
        $CI->mshelforder->update(array('msg_status'=>'400', 'msg_type'=>1), array('id'=>$shelforder['id'], 'timeline'=>$shelforder['timeline']));
      } else {
        // todo::补充内容 更新完后应删除
        // 更新订单消息发送状态
        $CI->load->model('shelforder_model','mshelforder');
        if ($msg_info['sender_msgid_status']) {
          $CI->mshelforder->update(array('msg_status'=>$msg_info['sender_msgid_status'], 'msg_type'=>1), array('id'=>$msg_info['shelforder_id'], 'timeline'=>$msg_info['shelforder_timeline']));
        } else {
          $CI->mshelforder->update(array('msg_status'=>$msg_info['sender_status'], 'msg_type'=>1), array('id'=>$msg_info['shelforder_id'], 'timeline'=>$msg_info['shelforder_timeline']));
        }

        if (isset($msg_info['sender_msgid_status']) && $msg_info['sender_msgid_status']) {
          if (isset($this->callback_status[$msg_info['sender_msgid_status']])) {
            $res['status_title'] = $this->callback_status[$msg_info['sender_msgid_status']];
            if ($msg_info['sender_msgid_status'] == 'DELIVRD') {
              $res['status'] = 200; // 发送成功
            } else {
              $res['status'] = 402; // 发送失败
            }
          } else {
            $res['status_title'] = '发送失败';
            $res['status'] = 403; // 发送失败
          }
          $res['send_type'] = $msg_info['msg_type']==1?'短信':'微信';
        } else if (isset($msg_info['sender_status']) && isset($this->sender_status[$msg_info['sender_status']])) {
          $res['status'] = $msg_info['sender_status']==0?100:400; // 发送中
          $res['status_title'] = $this->sender_status[$msg_info['sender_status']];
          $res['send_type'] = $msg_info['msg_type']==1?'短信':'微信';
        } else {
          $res['status'] = 404; // 发送失败
          $res['status_title'] = '发送失败';
          $res['send_type'] = $msg_info['msg_type']==1?'短信':'微信';
        }

        // 重发间隔
        $res['send_interval'] = (60-(time()-$msg_info['timeline']))>0?(60-(time()-$msg_info['timeline'])):0;
        $res['timeline'] = $msg_info['timeline'];
      }
    } else {
      $res['status'] = 405; // 发送失败
      $res['status_title'] = '发送失败';
      $res['send_type'] = '无';
    }

    return $res;
  }

  // 获取解析快单数据短信发送状态 简化版本
  public function trans_status($shelforder=array(), $recheck_status=false) {
  	$CI =& get_instance();
    $res = array();
    if ($shelforder) {
      // 发送端识别
      if (isset($shelforder['msg_type']) && $shelforder['msg_type']) {
        if ($shelforder['msg_type'] == 1) {
          $res['send_type'] = '短信';
        } else if ($shelforder['msg_type'] == 2) {
          $res['send_type'] = '微信';
        } else {
          $res['send_type'] = '无';
        }
      } else {
        $res['send_type'] = '无';
      }

      // 收货状态重新检查
      if ($recheck_status) {
        if (!isset($CI->mshelforder)) { $CI->load->model('shelforder_model','mshelforder'); }
        if (!isset($CI->mshelforder_msg)) { $CI->load->model('shelforder_msg_model','mshelforder_msg'); }

        if (empty($shelforder['msg_status']) || is_numeric($shelforder['msg_status'])) {
          $msg_info = $CI->mshelforder_msg->get_one(array('shelforder_id'=>$shelforder['id'], 'shelforder_timeline'=>$shelforder['timeline']));
          dump($msg_info);
          if (!$msg_info) {
            $CI->mshelforder->update(array('msg_status'=>400, 'msg_type'=>1), array('id'=>$shelforder['id'], 'timeline'=>$shelforder['timeline']));
          } else {
            if ($msg_info['sender_msgid_status']) {
              $CI->mshelforder->update(array('msg_status'=>$msg_info['sender_msgid_status'], 'msg_type'=>1), array('id'=>$msg_info['shelforder_id'], 'timeline'=>$msg_info['shelforder_timeline']));
            } else if(!$msg_info['sender_msgid_status'] && $msg_info['sender_status']==0 && $shelforder['status']==SDB_TAKEN) {
              $CI->mshelforder->update(array('msg_status'=>400, 'msg_type'=>1), array('id'=>$shelforder['id'], 'timeline'=>$shelforder['timeline']));
            } else {
              $CI->mshelforder->update(array('msg_status'=>$msg_info['sender_status'], 'msg_type'=>1), array('id'=>$msg_info['shelforder_id'], 'timeline'=>$msg_info['shelforder_timeline']));
            }
          }
          $shelforder = $CI->mshelforder->get_one(array('id'=>$shelforder['id'], 'timeline'=>$shelforder['timeline']));
        }
      }

      // 状态决策
      if (isset($shelforder['msg_status']) && is_numeric($shelforder['msg_status'])) {
        if ($shelforder['msg_status'] == '0' && ((time() - $shelforder['timeline']) < 24*3600)) {
          if (!isset($CI->mshelforder_msg)) { $CI->load->model('shelforder_msg_model','mshelforder_msg'); }
          $msg_info = $CI->mshelforder_msg->get_one(array('shelforder_id'=>$shelforder['id'], 'shelforder_timeline'=>$shelforder['timeline']));
          // 重发间隔
          $res['send_interval'] = (60-(time()-$msg_info['timeline']))>0?(60-(time()-$msg_info['timeline'])):0;
          $res['timeline'] = $msg_info['timeline'];
        } else {
          $res['timeline'] = $shelforder['timeline'];
        }
        $res['status'] = $shelforder['msg_status']==0?100:400; // 发送中
        if ($shelforder['msg_type'] == 1) {
          $res['send_type'] = '短信';
        } else if ($shelforder['msg_type'] == 2) {
          $res['send_type'] = '微信';
        } else {
          $res['send_type'] = '无';
        }
        if (isset($this->sender_status[$shelforder['msg_status']])) {
          $res['status_title'] = $this->sender_status[$shelforder['msg_status']];
        } else {
          $res['status_title'] = '发送失败';
        }
      } else if (isset($shelforder['msg_status']) && is_string($shelforder['msg_status'])) {
        if (isset($this->callback_status[$shelforder['msg_status']])) {
          $res['status_title'] = $this->callback_status[$shelforder['msg_status']];
          if ($shelforder['msg_status'] == 'DELIVRD') {
            $res['status'] = 200; // 发送成功
          } else {
            $res['status'] = 402; // 发送失败
          }
        } else {
          $res['status_title'] = '发送失败';
          $res['status'] = 403; // 发送失败
        }

      } else {
        $res['status'] = 405; // 发送失败
        $res['status_title'] = '发送失败';
        $res['send_type'] = '无';
      }
    } else {
      $res['status'] = 410; // 发送失败
      $res['status_title'] = '发送失败';
      $res['send_type'] = '无';
    }

    return $res;
  }

  // 消息发送记录列表中获取解析快单数据短信发送状态
  public function get_status_by_msg($msg_info=array()) {
    if (!$msg_info) {
      $res['status'] = 401; // 发送失败
      $res['status_title'] = '发送失败';
      $res['send_type'] = '无';
    } else {
      if (isset($msg_info['sender_msgid_status']) && $msg_info['sender_msgid_status']) {
        if (isset($this->callback_status[$msg_info['sender_msgid_status']])) {
          $res['status_title'] = $this->callback_status[$msg_info['sender_msgid_status']];
          if ($msg_info['sender_msgid_status'] == 'DELIVRD') {
            $res['status'] = 200; // 发送成功
          } else {
            $res['status'] = 402; // 发送失败
          }
        } else {
          $res['status_title'] = '发送失败';
          $res['status'] = 403; // 发送失败
        }
        $res['send_type'] = $msg_info['msg_type']==1?'短信':'微信';
      } else if (isset($msg_info['sender_status']) && isset($this->sender_status[$msg_info['sender_status']])) {
        $res['status'] = $msg_info['sender_status']==0?100:400; // 发送中
        $res['status_title'] = $this->sender_status[$msg_info['sender_status']];
        $res['send_type'] = $msg_info['msg_type']==1?'短信':'微信';
      } else {
        $res['status'] = 404; // 发送失败
        $res['status_title'] = '发送失败';
        $res['send_type'] = $msg_info['msg_type']==1?'短信':'微信';
      }
      // 重发间隔
      $res['send_interval'] = (60-(time()-$msg_info['timeline']))>0?(60-(time()-$msg_info['timeline'])):0;
    }

    return $res;
  }

  // 获取所有消息点发送名称
  public function get_send_reasons()
  {
    $res = array();
    foreach ($this->msg_code as $k => $v) {
      if (in_array($v['code'], array(1,2))) {
        $v['id'] = $v['code'];
        array_push($res, $v);
      }
    }

    return $res;
  }

  // 获取消息发送点名称
  public function get_send_reason($msg_info=array())
  {
    $reason = '未查询到';
    if ($msg_info) {
      $reason_arr = $this->find_in_map('code', $msg_info['msg_code'], $this->msg_code);
      if ($reason_arr) {
        $reason = $reason_arr['title'];
      }
    }
    return $reason;
  }

  // 后台订单列表 查询条件 for shelforders
  public function msg_status_arr()
  {
    $res = array(
      array(
        'id' => '100',
        'title' => '发送中',
        'condition' => array('msg_status' => '0'),
      ),
      array(
        'id' => '200',
        'title' => '发送成功',
        'condition' => array('msg_status' => 'DELIVRD'),
      ),
      array(
        'id' => '400',
        'title' => '发送失败',
        'condition' => array('not_in msg_status' => array('msg_status', array('0', 'DELIVRD'))),
      ),
    );
    return $res;
  }

  // 选用 sina 短链接服务
  public function shorturl($url='')
  {
    $query = $this->sina_shorturl($url);

    $res = '';
    if (is_array($query)) {
      $res = reset($query);
      if (isset($res->url_short)) {
        $res = $res->url_short;
      }
    }

    return $res?$res:$url;
  }

  public function find_in_map($key,$value,$array) {
		foreach ($array as $k => $v) {
			if ($v[$key] == $value) {
				return $v;
			}
		}
		return false;
	}

  // 百度短接口服务
  public function baidu_shorturl() {
    $ch = curl_init();
    $url = 'http://apis.baidu.com/chazhao/shorturl/shorturl';
    $header = array(
        'Content-Type:application/json;charset=UTF-8',
        'apikey:59343528075a48981eb6d104b8f87919',
    );

    $data = array(
      'type' =>1,
      'url' => array(
        'http://www.bocweb.com/'.rand_str()
      )
    );

    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    // 添加参数
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 执行HTTP请求
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);

    print_r(json_decode($res));
  }

  // 新浪短接口服务
  public function sina_shorturl($url='') {
    $data = 'http://api.t.sina.com.cn/short_url/shorten.json?source=111389220&url_long='.$url;

    $ch = curl_init($data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
    $res = curl_exec($ch);

    return json_decode($res);
  }

}
