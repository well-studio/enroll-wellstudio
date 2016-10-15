<?php
namespace app\index\controller;

use think\Controller;

class Login extends Controller {
	function index(){
		return $this->fetch();
	}

	function doLogin(){
		$password = input('post.password');
		if(empty($password)){
			return json_encode(['error' => '1', 'msg' => '密码不能为空']);
		}
		if($password === config('password')){
			session('user','admin');
			$this->redirect(config('site_root').'/admin');
		}
		return json_encode(['error' => '2', 'msg' => '密码错误']);
	}
}


?>