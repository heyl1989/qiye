<?php

namespace app\app\controller;

use app\admin\model\Banner;
use think\Controller;

class Index extends Controller
{
    public function getTestData()
    {
        if ($this->request->isGet()) {
            $msg = "请求方式错误";
            $data = "请用GET请求";
            $header = null;
        } else {
            $header = $this->request->header('ticket');
            $msg = $this->request->param('msg');
            $data = $this->request->param('data');
        }
        $result = array(
            'flag' => 'Success',
            'msg' => $msg,
            'data' => $data,
            'header' => $header
        );
        print json_encode($result);
    }

    public function getBanner()
    {
        $banner = Banner::all();
        print json_encode($banner);
    }
}
