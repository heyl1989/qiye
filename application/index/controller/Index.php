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
        $this->view->assign('banner',$banner);
        //3.模板渲染
        return $this->view->fetch('index');
    }
}
