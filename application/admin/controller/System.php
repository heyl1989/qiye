<?php

namespace app\admin\controller;

use app\admin\common\Base;
use think\Request;
use app\admin\model\System as SystemModel;

class System extends Base
{

    public function index()
    {
        //1.获取配置信息
        $system = SystemModel::get(1);
        //2.模板赋值
        $this->view->assign('system', $system);
        //3.模板渲染
        return $this->view->fetch('system_set');
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
        //1判断提交类型
        if ($request->isAjax(true)){

        }
    }

}
