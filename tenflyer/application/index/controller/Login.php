<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Session;

class Login extends Controller
{
    public function login()
    {

        //登录验证
        $request = Request::instance();
        if($request->isAjax() && $request->post('action') == 'checkLogin'){
            $result = array('rs'=>0,'info'=>'system error!');

            $username = addslashes(trim(strip_tags($request->post('username'))));
            if($username == ''){
                $result['info'] = '用户名不能为空！';
                return json($result);
            }
            $password = addslashes(trim(strip_tags($request->post('password'))));
            if($password == ''){
                $result['info'] = '密码不能为空！';
                return json($result);
            }

            $password = md5($password);

            $userInfo = db('user')->where(array('username'=>$username,'password'=>$password))->find();
            if($userInfo){
                if(!$userInfo['status']){
                    $result['info'] = '您的账户处于不可用状态,无法登录！';
                    return json($result);
                }
                Session::set('username',trim(strip_tags($userInfo['username'])));
                Session::set('email', $userInfo['email']);
                Session::set('nickname', trim(strip_tags($userInfo['nickname'])));
                Session::set('expire_time', time() + 24 * 60 * 60);
                $result['rs'] = 1;
                $result['info'] = '登录成功!';
                return json($result);
            }else{
                $result['info'] = '用户名或密码错误!';
                return json($result);
            }
        }

        //登录页面
        return view('login');
    }

    //退出登录
    public function loginOut(){
        session(null);
        $this->redirect('index/index');
    }
}
