<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller {

	protected $beforeActionList = [
        'checkLogin'
    ];

	public function index(){
		$res = db('u_info')->field('id, name, sex, class')->select();
		if(!isset($res[0])){
			return '还没有任何数据哦~';
		}
		$this->assign('res', $res);
		return $this->fetch();
	}

	protected function checkLogin(){
		if(empty(session('user')) || session('user') !== 'admin'){
			die($this->error('未登录，请先登录', config('site_root').'/login'));
		}
	}


	public function update_user_info(){
		$tempUser = input('post.');
		$tempUser['u_birth'] = strtotime($tempUser['u_birth']);
		if(empty($tempUser['u_password'])){
			unset($tempUser['u_password']);
		}
		$user = [];
		foreach($tempUser as $key => $value){
			if(preg_match('/^u_/', $key)){
					$user[substr($key, 2)] = $value;
			}
		}
		// var_dump($user);
		$fields = ['id','name','sex','nation','native','birth','class','domitory','phone','qq','introduction'];
		if(!empty(input('post.u_password'))){
			$fields[] = 'password';
			$user['password'] = md5(input('post.u_password').config('pass_salt'));
		}
		try{
			db('u_info')->field($fields)->where('id', $user['id'])->update($user);
		}catch(\Exception $e){
			return $this->error('未知错误');
		}
		return $this->success('修改成功');
	}

	public function delete_user_info(){
		$id = input('post.u_id');
		db('u_info')->where('id', $id)->delete();
		return $this->success('删除成功');
	}

	public function iframe(){
		$id = input('get.u_id');
		if(is_null($id)){
			return '';
		}
		$res = db('u_info')->where('id', $id)->find();
		$this->assign('u', $res);
		return $this->fetch();
	}

	public function do_post(){
		if(input('post.do') === '修改信息表'){
			$this -> update_user_info();
		}else if(input('post.do') === '删除信息表'){
			$this -> delete_user_info();
		}else return $this->error('无效操作');
	}
}

?>