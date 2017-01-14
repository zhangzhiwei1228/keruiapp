<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once LIBS_PATH.'libraries/jpush/autoload.php';

use JPush\Model as M;
use JPush\JPushClient;
use JPush\JPushLog;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

class Jpush {
    var $br = '<br/>';
    var $spilt = ' - ';

    /**
     * 初始化极光推送参数
     *
     */
    public function __construct() {
      $app_key = CLIENT_JPUSH_APPKEY;
      $master_secret = CLIENT_JPUSH_MASTERSSCRET;
      $this->client = new JPushClient($app_key, $master_secret);

      $app_key = CLIENT_JPUSH_APPKEY;
      $master_secret = CLIENT_JPUSH_MASTERSSCRET;
      $this->doctor = new JPushClient($app_key, $master_secret);

      JPushLog::setLogHandlers(array(new StreamHandler(LOG_PATH.'jpush/'.date('Ymd').'.log', Logger::DEBUG)));
    }

    /**
     * $receiver = array(接收人信息
     *  app: user_tag
     *  to_type: 接收人类型 1->医生, 2->用户
     * )
     *
     */
    public function push($receiver, $content, $content_type='text', $extras=array(), $silence=false) {

      $user_tag = $receiver;
      try {
        if (is_array($receiver) && isset($receiver['to_type'])) {
          $user_tag = $receiver['app'];
          switch ($receiver['to_type']) {
            case 1:
              // 推送医生端
              $result = $this->doctor->push()
                  ->setPlatform(M\Platform('android', 'ios'))
                  ->setAudience(M\audience(M\alias(array($user_tag))));
              break;
            case 2:
              // 推送用户端
              $result = $this->client->push()
                  ->setPlatform(M\Platform('android', 'ios'))
                  ->setAudience(M\audience(M\alias(array($user_tag))));
              break;
            default:
                break;
          }
        } elseif($user_tag) {
          $result = $this->client->push()
              ->setPlatform(M\Platform('android', 'ios'))
              ->setAudience(M\audience(M\alias(is_array($user_tag)?$user_tag:array($user_tag))))
              ->setOptions(M\options(null, null, null, true, null));
        } else {
          logfile('设备别名', 'JPush_APIRequestException_');
          return 0;
        }
        if ($silence) {
          //$result = $result->setMessage(M\message($content, '新消息', $content_type, $extras));
         $result = $result ->setNotification(M\notification($content,
                  M\android($content, '新消息', 1, $extras),
                  M\ios($content, '新消息', "+1", true, $extras, "")
              ));

        } else {
          $result = $result->setNotification(M\notification($content));
        }
        // ->printJSON()
        // 是否使用定时推送
        if (false) {
          $payload = $result->build();
          $result = $this->client->schedule()->createSingleSchedule("", $payload, array("time"=>date("Y-m-d H:i:s", time()+5)));
        } else {
          $result->send();
        }

        logfile('PUSH:'.$result->getJSON(), 'JPush_');
      } catch (APIRequestException $e) {
        logfile($e, 'JPush_APIRequestException_');
        return 0;
      } catch (APIConnectionException $e) {
        logfile($e, 'JPush_APIConnectionException_');
        return 0;
      }
    }

}
