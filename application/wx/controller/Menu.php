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
    private $jsonmenu = '
    {
         "button":[
         {    
              "type":"click",
              "name":"天气预报",
          }]
    }';

    public function createMenu()
    {

    }


    //把token写入文件  加个时间戳
    public function cacheToken()
    {
        //判断文件是否存在  存在取读取token  在判断是否到期
        if (file_exists('token.json')) {
            $get_token = file_get_contents('token.json');
            $token = json_decode($get_token);
            echo '缓存的Token' . $get_token . "\n";
            if ($token->expires_in < time()) {
                $get_token = $this->getToken();
                if (!empty($get_token)) {
                    $token = json_decode($get_token);
                    $token->expires_in = time() + 7000;
                    file_put_contents('token.json', json_encode($token));
                }
            }
        } else {
            $get_token = $this->getToken();
            if (!empty($get_token)) {
                $token = json_decode($get_token);
                $token->expires_in = time() + 7000;
                file_put_contents('token.json', json_encode($token));
            }
        }
        echo $token->access_token;
        return $token->access_token;
    }

    private function getToken()
    {
        //测试获取access_token
        $appid = 'wx94655f69745f182f';
        $AppSecret = 'da8d44a257a3df74d6795c462cf17ab2';
        $file_contents = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$AppSecret");
        return $file_contents;
    }

}