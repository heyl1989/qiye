<?php

namespace app\admin\controller;

use app\admin\common\Base;
use think\Request;
use app\admin\model\Banner as BannerModel;

class Banner extends Base
{
    /**模板渲染
     * @return string
     * @throws \think\Exception
     */
    public function index()
    {
        //1.获取到所有的数据记录
        $banner = BannerModel::all();
        $count = BannerModel::count();
        //2.模板赋值
        $this->view->assign('banner', $banner);
        $this->view->assign('count', $count);
        //3.模板渲染
        return $this->view->fetch('banner_list');
    }

    /**
     * 轮播主页
     *
     */
    public function create()
    {
        //模板渲染
        return $this->view->fetch('banner_add');
    }

    /**
     * 添加轮播图
     *
     * @param  \think\Request $request
     */
    public function save()
    {
        //判断提交类型
        if ($this->request->isPost()) {
            //1.获取提交的数据，包括文件
            $data = $this->request->param(true);
            //2.获取上传文件的对象
            $file = $this->request->file('image');
            //3.判断是否获取到了文件
            if (empty($file)) {
                $this->error($file->getError());
            }
            //4.上传文件
            $map = [
                'ext' => 'jpg,png',
                'size' => 3000000,
            ];
            $info = $file->validate($map)->move(ROOT_PATH . 'public/uploads');
            if (is_null($info)) {
                $this->error($file->getError());
            }
            //5.向表中写入数据
            $data['image'] = $info->getSaveName();
            $res = BannerModel::create($data);
            //6.判断是否新增成功
            if (is_null($res)) {
                $this->error('新增失败');
            }
            $this->success('新增成功');
        } else {
            $this->error('请求类型错误');
        }
    }

    /**
     * 编辑轮播
     *
     */
    public function edit($id)
    {
        //1.查询要编辑的记录
        $data = BannerModel::get($id);
        //2.模板赋值
        $this->view->assign('data', $data);
        //3.模板渲染
        return $this->view->fetch('banner_edit');
    }

    /**
     * 保存更新的轮播
     *
     */
    public function update()
    {
        //1.获取所有提交过来的数据，包括文件
        $data = $this->request->param(true);
        //2.对于文件单独操作，打包成一个文件对象
        $file = $this->request->file('image');
        //3.文件验证与上传
        $info = $file->validate(['ext' => 'jpg,png', 'size' => 3000000,])->move(ROOT_PATH . 'public/uploads');
        if (is_null($info)) {
            $this->error($file->getError());
        }
        //4.执行更新操作
        $res = BannerModel::update([
            'image' => $info->getSaveName(),
            'link' => $data['link'],
            'desc' => $data['desc'],
        ], ['id' => $data['id']]);
        //5.检查是否更新成功
        if (is_null($res)) {
            $this->error('更新失败~');
        }
        //6.更新成功
        $this->success('更新成功~');
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        BannerModel::destroy(['id' => $id]);
    }
}
