<?php

namespace app\admin\controller;

use app\admin\common\Base;
use think\Request;
use app\admin\model\Admin;
use think\Session;

class Login extends Base
{
    /**
     * 渲染登录界面
     */
    public function index()
    {
        echo __ROOT__;
        echo __STATIC__;
        exit();
        $this->alreadyLogin();
        return $this->view->fetch('login');
    }


    /**
     * 验证用户身份
     */
    public function check(Request $request)
    {
        //设置status
        $status = 0;
        //获取表单的数据并存在变量中
        $data = $request->param();
        $userName = $data['username'];
        $passWord = md5($data['password']);
        $code = strtolower($data['code']);
        //在admin表中进行查询，以用户名为条件
        $map = ['username' => $userName];
        $admin = Admin::get($map);
        //将用户名与密码分开验证

        //如果没有查询到用户
        if (is_null($admin)) {
            //设置返回信息
            $message = '用户名不正确';
        } elseif ($admin->password != $passWord) {
            //设置密码不正确的提示语
            $message = '密码不正确';
        } else if ($code != $_SESSION['code']) {
            $message = '验证码'.$code.'不正确'.$_SESSION['code'];
        } else {
            //如果用户名和密码都通过了验证，表明该用户是合法用户
            //修改返回信息
            $status = 1;
            $message = '验证通过，请点击确认进入后台';
            //更新表中登录次数和最后登录时间
            $admin->setInc('login_count');
            $admin->save(['last_time' => time()]);
            //将用户信息保存到session中，供其他控制器进行判断
            Session::set('user_id', $userName);
            Session::set('user_info', $admin);
        }
        return ['status' => $status, 'message' => $message];
    }

    /**退出登录
     * @param Request $request
     */
    public function logout()
    {
        //删除当前用户session值
        Session::delete('user_id');
        Session::delete('user_info');
        //执行成功，并返回登录界面
        $this->success('注销成功，正在返回。。。', 'login/index');
    }
}
