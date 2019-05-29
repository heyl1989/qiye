<?php

namespace app\admin\controller;

use app\admin\common\Base;
use think\Request;
use app\admin\model\System as SystemModel;

class System extends Base
{

    public function index()
    {
        //1.模板赋值
        $this->view->assign('system', $this->getSystem());
        //2.模板渲染
        return $this->view->fetch('system_set');
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     */
    public function update(Request $request)
    {
        //1判断提交类型
        if ($request->isAjax(true)) {
            //获取提交的数据
            $data = $request->param();
            //设置一下更新条件
            $map = ['is_update' => $data['is_update']];
            //执行更新操作
            $res = SystemModel::update($data, $map);

            //设置更新返回信息
            $status = 1;
            $message = '更新成功';
            //如果更新失败
            if (is_null($res)) {
                $status = 0;
                $message = '更新失败';
            }
            return ['status' => $status, 'message' => $message];
        }
    }

}
