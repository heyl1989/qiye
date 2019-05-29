<?php

namespace app\admin\model;

use think\Collection;
use think\Model;

class Category extends Model
{
    protected $insert = [
        'cate_order' => 0,
    ];

    /**创建一个静态方法获取分类信息
     * @param int $pid 当前分类父ID
     * @param array $result 引用返回值
     * @param int $blank 设置分类之间的显示提示
     */
    public static function getCate($pid = 0, &$result = [], $blank = 0)
    {
        //1.分类表查询
        $res = self::all(['pid' => $pid]);
        //2.自定义分类前面显示的提示信息
        $blank += 2;
        //3.便利分类表
        foreach ($res as $key => $value) {
            //自定义分类名称前显示的格式
            $cate_name = '|--' . $value->cate_name;
            $value->cate_name = str_repeat('&nbsp', $blank) . $cate_name;
            //将当前查询到的记录保存在结果$result中
            $result[] = $value;
            //将当前记录的ID，作为下一级分类的父ID，PID进行递归
            self::getCate($value->id, $result, $blank);
        }
        //4.返回查询结果，调用结果集类make方法打包当前结果,转为二位数组返回
//        echo '<pre>';
//        print_r(Collection::make($result)->toArray());
        return Collection::make($result)->toArray();
    }
}
