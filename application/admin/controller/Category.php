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
        $cate_list = CategoryModel::order(['id' => 'desc'])->paginate(5);
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
     * 显示编辑资源表单页.
     *
     * @param  int $id
     */
    public function edit(Request $request)
    {
        //1.获取请求数据
        $cate_id = $request->param('id');
        //2.查询分类ID对应数据
        $cate_now = CategoryModel::get($cate_id);
        //3.递归查询所有的分类信息
        $cate_level = CategoryModel::getCate();
        //4.模板赋值
        $this->view->assign('cate_now', $cate_now);
        $this->view->assign('cate_level', $cate_level);
        //模板渲染
        return $this->view->fetch('category_edit');
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     */
    public function update(Request $request)
    {
        //1.获取提交的数据
        $data = $request->param();
        //2.更新操作
        $res = CategoryModel::update([
            'cate_name' => $data['cate_name'],
            'cate_order' => $data['cate_order'],
            'pid' => $data['pid'],
        ], ['id' => $data['id']]);
        //3.设置返回值
        $status = 1;
        $message = '更新成功';
        //4.设置失败的返回值
        if (is_null($res)) {
            $status = 0;
            $message = '更新失败';
        }
        //5.返回结果
        return ['status' => $status, 'message' => $message];
    }

    /**
     * 删除指定分类
     *
     * @param  int $id
     */
    public function delete($id)
    {
        //1.删除以当前id为父分类pid的所有分类
        CategoryModel::destroy(function ($query) use ($id) {
            $query->where(['pid' => $id])->field('id');
        });
        //2.删除当前分类
        CategoryModel::destroy($id);
    }
}
