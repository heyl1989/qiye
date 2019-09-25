<?php

namespace app\index\controller;

use app\admin\common\Base;
use app\admin\model\Banner;

class Index extends Base
{
    public function index()
    {
        //1.获取banner数据
        $banner = Banner::all();
        //2.模板赋值
        $this->view->assign('banner', $banner);
        //3.模板渲染
        return $this->view->fetch('index');
    }

    public function wx()
    {
        $file_contents = file_get_contents('https://www.tianqiapi.com/api/?version=v6&appid=32696743&appsecret=31HAJ1y1&city=永登');
        return $file_contents;
    }
}
