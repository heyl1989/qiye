<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20
 * Time: 20:56
 */

namespace app\wx\controller;

use think\Exception;

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
        $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';

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
					<Title><![CDATA[欢迎来我的公众号]]></Title> 
					<Description><![CDATA[我的学习，第一个]]></Description>
					<PicUrl><![CDATA[http://img.taopic.com/uploads/allimg/140729/240450-140HZP45790.jpg]]></PicUrl>
					<Url><![CDATA[https://www.baidu.com/]]></Url>
					</item>
					</Articles>
					</xml>";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                echo $resultStr;
            }
            if (!empty($keyword)) {
                $msgType = "text";
                $contentStr = "Hello World!";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
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