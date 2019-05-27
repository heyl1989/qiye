<?php

namespace app\admin\controller;

use app\admin\common\Base;
use think\Request;
use app\admin\model\Admin as AdminModel;

class Admin extends Base
{

    //显示管理员信息
    public function index()
    {
        //1。读取admin管理员表的信息
        $admin = AdminModel::get(['username' => 'admin']);
        //2.将当前管理员的信息赋值给模板
        $this->view->assign('admin', $admin);
        //3.渲染模板
        return $this->view->fetch('admin_list');
    }

    /**
     * 显示编辑资源表单页.
     *
     */
    public function edit(Request $request)
    {
        //1.读取admin管理员的信息
        $admin = AdminModel::get($request->param('id'));
        //2.将当前管理员的信息赋值给模板
        $this->view->assign('admin', $admin);
        //3渲染模板
        return $this->view->fetch('admin_edit');
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        if ($request->isAjax(true)) {
            //获取提交的数据，自动过滤空值
            $data = array_filter($request->param());
            //设置更新条件
            $map = ['is_update' => $data['is_update']];
            //更新用户表
            $res = AdminModel::update($data, $map);
            //更新成功的提示信息
            $statue = 1;
            $message = '更新成功';
            //如果更新失败
            if (is_null($res)) {
                $statue = 0;
                $message = '更新失败';
            }
        }
        return ['status' => $statue, 'message' => $message];
    }

}
