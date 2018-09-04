<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;

 
class Login extends Controller
{
	public function login(Request $request)
	{
		if($request->isPost()){
			$data=input('post.');
			$password=md5($data['password']);
			$result=Db::name('cx_user')->where('username',$data['username'])->where('password',$password)->find();
			if($result){
        $result['token'] = md5($result['username'].$result['password'].rand(1,9999));
        return json(['code' => 200,'msg' => '登陆成功！','data' => $result]);
			}else{
				return json(['code' => 401,'msg' => '用户名或者密码错误！']);
			}
		}
  }
}
