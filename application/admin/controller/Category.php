<?php

namespace app\admin\controller;

use app\admin\common\Base;
use think\Request;
use app\admin\model\Category as CategoryModel;

class Category extends Base
{
    /**渲染分类管理模板
     * @return string
     * @throws \think\Exception
     */
    public function index()
    {
        //1.获取分类信息
        $cate = CategoryModel::getCate();
        //2.用模型获取分页数据
        $cate_list = CategoryModel::paginate(5);
        //3.获取记录数量
        $count = CategoryModel::count();
        //3.模板赋值
        $this->view->assign('cate', $cate);
        $this->view->assign('cate_list', $cate_list);
        $this->view->assign('count', $count);
        //4、模板渲染
        return $this->view->fetch('category_list');

    }

    /**
     * 添加分类数据
     *
     */
    public function create(Request $request)
    {
        //1.设置返回的值
        $status = 1;
        $message = '添加成功';
        //2.添加数据到分类表中
        $res = CategoryModel::create([
            'cate_name' => $request->param('cate_name'),
            'pid' => $request->param('pid'),
        ]);
        //3.添加失败的处理
        if (is_null($res)) {
            $status = 0;
            $message = '添加失败';
        }
        return ['status' => $status, 'message' => $message, 'res' => $res->toJson()];
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
