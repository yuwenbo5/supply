<?php
namespace app\index\controller;
use think\Controller;
use think\Session;

class Base extends Controller{

    //每页记录数查询
    public $PAGE_LIST = array();

    //递增、递减排序
    public $ORDER_LIST = array();

    public function _initialize(){
        parent::_initialize();

        $this->is_login();

        $this->PAGE_LIST = array(
            '20' => '20/页',
            '50' => '50/页',
            '100' => '100/页',
            '200' => '200/页',
            '500' => '500/页',
            '1000' => '1000/页',
        );

        $this->ORDER_LIST = array(
            'asc' => array(
                'name' => '递增',
                'is_default' => 0,
            ),
            'desc' => array(
                'name' => '递减',
                'is_default' => 1,
            ),
        );
    }

    //验证是否登录
    protected function is_login(){
        if(Session::get('expire_time') && Session::get('expire_time') < time()){
            session_destroy();
        }
        if(!Session::get('username') || Session::get('username') == ''){
            $this->redirect('login/login');
        }
    }

    protected function getSearch($searchArr){
        $return_data = array(
            'pageNums' => 20,
            'order' => '',
            'where' => array(
                1,
            ),
        );

        if(!empty($searchArr)){
            unset($searchArr['searchsubmit']);
            $return_data['pageNums'] = $searchArr['pageNums'];
            unset($searchArr['pageNums']);
            $return_data['order'] = $searchArr['order'].' order by '.$searchArr['order_by'];
            unset($searchArr['order'],$searchArr['order_by']);
            foreach($searchArr as $key => $val){
                if($val != ''){
                    $return_data['where'][$key] = $val;
                }
            }
        }

        return $return_data;
    }
}