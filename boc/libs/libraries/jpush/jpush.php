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
      // 极光推送账号：bizcrossroad@qq.com    密码NJBC2016
      $app_key = CLIENT_JPUSH_APPKEY;
      $master_secret = CLIENT_JPUSH_MASTERSSCRET;
      $this->client = new JPushClient($app_key, $master_secret);

      $app_key = CLIENT_JPUSH_APPKEY;
      $master_secret = CLIENT_JPUSH_MASTERSSCRET;
      $this->doctor = new JPushClient($app_key, $master_secret);

      JPushLog::setLogHandlers(array(new StreamHandler('logs/jpush'.date('Ymd').'.log', Logger::DEBUG)));
    }

    /**
     * $receiver = array(接收人信息
     *  app: user_tag
     *  to_type: 接收人类型 1->医生, 2->用户
     * )
     *
     */
    public function push($receiver, $content, $content_type='text', $extras=array(), $silence=false) {
      $user_tag = $receiver['app'];
      try {
        if (isset($receiver['to_type'])) {
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
        } else {
          $result = $this->client->push()
              ->setPlatform(M\Platform('android', 'ios'))
              ->setAudience(M\audience(M\alias(array($user_tag))));
        }

        if ($silence) {
          $result = $result->setMessage(M\message($content, SITE_TITLE, $content_type, $extras));
        } else {
          $result = $result->setNotification(M\notification($content));
        }
        // ->printJSON()
        $result->send();
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
