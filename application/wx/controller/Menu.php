<?php
/**
 * Created by PhpStorm.
 * User: heyl
 * Date: 2019/7/15
 * Time: 14:14
 */

namespace app\wx\controller;


class Menu
{
    public function index()
    {
        //测试获取access_token
        $appid = 'wx94655f69745f182f';
        $AppSecret = 'da8d44a257a3df74d6795c462cf17ab2';
        $file_contents = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$AppSecret");
        if (!empty($file_contents)) {
            $token = json_decode($file_contents);
            $contentStr = $token->access_token;
            echo $contentStr;
        }
    }

}