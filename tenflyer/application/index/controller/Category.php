<?php
namespace app\index\controller;
use think\Db;

class Category extends Base
{
    /**
     * 获取所有分类
     * @return array
     */
    public function getAllCate(){
        $return_data = array();

        $topCateArr = Db::name('category')->where('use_state',1)->select();
        if(!empty($topCateArr)){
            foreach($topCateArr as $value){
                $return_data[$value['id']] = $value;
            }
        }

        return $return_data;
    }

    /**
     * 获取一级分类
     * @return array
     */
    public function getTopLevelCate(){
        $return_data = array();

        $topCateArr = Db::name('category')->where('parentId',0)->where('use_state',1)->select();
        if(!empty($topCateArr)){
            foreach($topCateArr as $value){
                $return_data[$value['id']] = $value['cls_name'];
            }
        }

        return $return_data;
    }

    /**
     * 根据分类id获取下级分类
     * @param $cate_id
     * @return array
     */
    public function getNextCateById($cate_id){
        $return_data = array();

        $cateArr = Db::name('category')->where("parentId",$cate_id)->where('use_state',1)->select();
        if(!empty($cateArr)){
            foreach($cateArr as $value){
                $return_data[$value['id']] = $value['cls_name'];
            }
        }

        return $return_data;
    }

    /**
     * 根据三级分类id获取一二级分类
     * @param $cate_id3
     */
    public function getAllCateByLastId($cate_id3){
        $return_data = array();

        //先获取所有分类
        $allCateArr = $this->getAllCate();
        $cate3 = $allCateArr[$cate_id3];
        $cate2 = $allCateArr[$cate3['parentId']];
        $cate1 = isset($allCateArr[$cate2['parentId']]) ? $allCateArr[$cate2['parentId']] : 0;
        $cate3_arr = $cate2_arr = $cate1_arr = array();

        foreach($allCateArr as $key => $value){
            if($value['parentId'] == $cate3['parentId']){
                $cate3_arr[] = $value;
            }

            if($value['parentId'] == $cate2['parentId']){
                $cate2_arr[] = $value;
            }

            if($value['parentId'] == $cate1['parentId']){
                $cate1_arr[] = $value;
            }
        }

        $return_data = array(
            'cate_id1' => $cate1['id'],
            'cate_id2' => $cate2['id'],
            'cate_id3' => $cate3['id'],
            'cate1' => $cate1_arr,
            'cate2' => $cate2_arr,
            'cate3' => $cate3_arr,
        );

        return $return_data;
    }
}
