<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20
 * Time: 20:56
 */

namespace app\wx\controller;

use think\Exception;
use think\helper\Str;

class Index
{

    private $AppSecret = "da8d44a257a3df74d6795c462cf17ab2";

    public function index()
    {
        define("TOKEN", "yongdengbang");
        $echoStr = isset($_GET["echostr"]) ? $_GET["echostr"] : null;
        if (!is_null($echoStr)) {
            try {
                if ($this->checkSignature()) {
                    echo $echoStr;
                    exit;
                }
            } catch (Exception $e) {
            }
        } else {
            $this->responseMsg();
        }
    }

    /**检查服务器配置
     * @return bool
     * @throws Exception
     */
    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    private function responseMsg()
    {
        //get post data, May be due to the different environments
//        $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
        $postStr = file_get_contents("php://input");
        //extract post data
        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $event = $postObj->Event;
            $time = time();
            $textTpl = '<xml>'
                . '<ToUserName><![CDATA[%s]]></ToUserName>'
                . '<FromUserName><![CDATA[%s]]></FromUserName>'
                . '<CreateTime>%s</CreateTime>'
                . '<MsgType><![CDATA[%s]]></MsgType>'
                . '<Content><![CDATA[%s]]></Content>'
                . '<FuncFlag>0</FuncFlag>'
                . '</xml>';
            if ($event == "subscribe") {
                $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>1</ArticleCount>
					<Articles>
					<item>
					<Title><![CDATA[欢迎来到甘肃永登]]></Title> 
					<Description><![CDATA[永登，古称令居、庄浪，隶属于甘肃省兰州市]]></Description>
					<PicUrl><![CDATA[http://img1.imgtn.bdimg.com/it/u=2572839016,2682225406&fm=214&gp=0.jpg]]></PicUrl>
					<Url><![CDATA[https://baike.sogou.com/v118860.htm]]></Url>
					</item>
					</Articles>
					</xml>";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                echo $resultStr;
            }
            if (!empty($keyword)) {
                if (strpos($keyword, "商城") !== false) {
                    $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>1</ArticleCount>
					<Articles>
					<item>
					<Title><![CDATA[欢迎来到甘肃永登]]></Title> 
					<Description><![CDATA[永登，古称令居、庄浪，隶属于甘肃省兰州市]]></Description>
					<PicUrl><![CDATA[http://img1.imgtn.bdimg.com/it/u=2572839016,2682225406&fm=214&gp=0.jpg]]></PicUrl>
					<Url><![CDATA[http://47.94.234.255/qiye/public/]]></Url>
					</item>
					</Articles>
					</xml>";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                    echo $resultStr;
                    return;
                }
                $msgType = "text";
                $contentStr = '您输入了：' . $keyword;
                if (strpos($keyword, "天气") !== false) {
//                    $contentStr = '查询永登天气敬请期待！';
                    //接口地址：https://www.kancloud.cn/ccjin/yingq/603579
                    $file_contents = file_get_contents('https://www.tianqiapi.com/api/?version=v6&appid=32696743&appsecret=31HAJ1y1&city=永登');
                    if (!empty($file_contents)) {
                        $weather = json_decode($file_contents);
                        $contentStr = '城市：' . $weather->city . "\n"
                            . '日期：' . $weather->data . "\n"
                            . '现在温度：' . $weather->tem . "\n"
                            . '今日温度：' . $weather->tem2 . '~' . $weather->tem1 . "\n"
                            . '天气状况：' . $weather->wea . "\n"
                            . '空气质量：' . $weather->air_level . "\n"
                            . 'PM2.5：' . $weather->air_pm25 . "\n"
                            . '能见度：' . $weather->visibility . "\n"
                            . '气压hPa：' . $weather->pressure . "\n"
                            . '湿度：' . $weather->humidity . "\n"
                            . '风向：' . $weather->win . "\n"
                            . '风速等级：' . $weather->win_speed . "\n"
                            . '风速：' . $weather->win_meter . "\n"
                            . '气象台更新时间：' . $weather->update_time . "\n"
                            . $weather->air_tips . "\n";
                    }
                }
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $file_contents);
                echo $resultStr;
            } else {
                echo "Input something...";
            }
        } else {
            echo "HTTP_RAW_POST_DATA EMPTY";
            exit;
        }
    }

}