<?php
namespace app\index\controller;
use app\index\controller\Category;
use think\Db;

class Product extends Base
{
    public $AUDIT_YES = 1;
    public $AUDIT_NO = 0;
    public $AUDIT_ARR = array();

    public function _initialize()
    {
        parent::_initialize();

        $this->AUDIT_ARR = array(
            $this->AUDIT_YES => '已审核',
            $this->AUDIT_NO => '未审核',
        );
    }

    //产品列表页面
    public function index()
    {
        //接收查询参数
        $input = input('post.');
        $search = $this->getSearch($input);
        dump($search);

        //根据以上查询条件获取产品列表信息(从erp库查)
        $data_list = Db::connect('database_foo')->table('cb_product_main')->where($search['where'])->select();
        $this->assign('product_list',$data_list);

        //审核状态
        $this->assign('audit_status',$this->AUDIT_ARR);

        //每页记录数
        $this->assign('page_list',$this->PAGE_LIST);

        //排序数组
        $order_arr = array(
            'id' => array(
                'name' => '默认排序',
                'is_default' => 0,
            ),
            'create_time' => array(
                'name' => '创建时间',
                'is_default' => 1,
            ),
        );
        $this->assign('order_arr',$order_arr);

        //递增、递减
        $this->assign('order_list',$this->ORDER_LIST);

        return view('list');
    }

    //添加产品页面
    public function add(){
        $cateObj = new Category();
        $topCate = $cateObj->getTopLevelCate();
        $this->assign('newClass',$topCate);
        return view();
    }

    //产品修改页面
    public function edit(){

        return view();
    }

    //产品导入
    public function import(){
        return view();
    }

    //产品添加修改导入，提交保存
    public function save(){
        //获取到操作的类型
        $actionId = input('request.ActionId');

        switch($actionId){
            case 'add':

                break;

            case 'edit':

                break;

            case 'import':

                break;

            default:
                return url_jump(0,'错误','参数提交有误！','index',4);
                break;
        }
    }

    //删除产品
    public function del_product(){

    }

    //ajax操作
    public function ajax(){
        //获取操作
        $action = input('post.action');
        $result = array('rs'=>0,'info'=>'');

        if($action == 'getCateOption'){
            $id = input('post.cID');
            //获取其下架分类
            $cateObj = new Category();
            $nextCate = $cateObj->getNextCateById($id);
            if(!empty($nextCate)){
                $result['rs'] = 1;
                foreach($nextCate as $key => $val){
                    $result['info'] .= "<option value='$key'>$val</option>";
                }
            }
        }

        return json($result);
    }
}
