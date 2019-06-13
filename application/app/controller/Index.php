<?php
namespace app\app\controller;

use think\Controller;

class Index extends Controller
{
    public function getTestData()
    {
        if($this->request->isGet()){
            $msg = "请求方式错误";
            $data = "请用GET请求";
        }else{
            $msg = $this->request->param('msg');
            $data = $this->request->param('data');
        }
        $result = array(
            'flag' => 'Success',
            'msg' => $msg,
            'data' => $data
        );
        print json_encode($result);
    }
}
