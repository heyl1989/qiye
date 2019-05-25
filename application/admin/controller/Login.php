<?php

namespace app\admin\controller;

use app\admin\common\Base;
use think\Request;

class Login extends Base
{
    /**
     * 渲染登录界面
     */
    public function index()
    {
        return $this->view->fetch('login');
    }


    /**
     * 验证用户身份
     */
    public function check()
    {
        //
    }

    /**退出登录
     * @param Request $request
     */
    public function save(Request $request)
    {
        //
    }
}
