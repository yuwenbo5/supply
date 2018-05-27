<?php
namespace app\index\model;
use think\Model;

class Product extends Model
{
    protected $table = null;


    //模型初始化
    protected function initialize(){

        //需要调用`Model`的`initialize`方法
        parent::initialize();

    }


}