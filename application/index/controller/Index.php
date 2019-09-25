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
        $weather = json_decode($file_contents);
        $contentStr = '城市：' . $weather->city . "\n"
            . '日期：' . $weather->date . "\n"
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
        return $contentStr;
    }
}
