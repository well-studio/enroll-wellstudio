<?php
namespace app\index\controller;
use \think\Db;
use \think\Controller;

class DoPost extends Controller {
	protected $beforeActionList = [
        'anti_spider',
        'csrf'
    ];
    /* 验证规则 */
    protected $validations_enroll = [
        ['u_id', '/\d{9}/', '学号格式不正确'],
        ['u_name', '/^[\x{4e00}-\x{9fa5}]{2,5}$/u', '姓名格式不正确'],
        ['u_sex', '/^(男|女)$/', '性别格式不正确'],
        ['u_nation', '/^.+族$/', '民族格式不正确'],
        ['u_native', '/.+/', '籍贯格式不正确'],
        ['u_birth', '/^\d{4}-\d{2}-\d{2}$/', '生日格式不正确'],
        ['u_class', '/.+/', '专业班级格式不正确'],
        ['u_qq', '/^\d{6,}$/', 'QQ号码格式不正确'],
        ['u_introduction', '/.+/', '自我介绍']
    ];
    protected function anti_spider(){
        if(session('anti_spider_token') !== 'well_studio'){
            die($this->error('error request'));
        }
    }
    protected function csrf(){
        if(input('_csrf') !== self::DJB(md5(date('D')))){
            die($this->error('error request'));
        }
    }

    public function update_detail(){
        $res = Db::query('SELECT `id` FROM `u_info` WHERE `id`=:id AND `password`=:password', ['id' => floatval(input('post.u_id')), 'password' => md5(input('u_password').config('pass_salt'))]);
        if(!isset($res[0])){
            return $this->error('操作失败，密码错误');
        }
        if(true !== ($res = $this->validate__($this->validations_enroll))){
            return $this->error($res);
        }
        if(input('post.update') === '提交更新'){
            try{
                Db::execute('UPDATE `u_info` SET `name`=?,`sex`=?,`nation`=?,`native`=?,`birth`=?,`class`=?,`domitory`=?,`phone`=?,`qq`=?,`introduction`=? WHERE `id`=?',[input('post.u_name'),input('post.u_sex'), input('post.u_nation'), input('post.u_native'), strtotime(input('post.u_birth')), input('post.u_class'), input('post.u_domitory'), input('post.u_phone'), input('post.u_qq'), input('post.u_introduction'), input('post.u_id')]);
            }catch(\Exception $e){
                // var_dump($e);exit;
                return $this->error('操作失败');
            }
            return $this->success('更新成功');
        }else if(input('post.delete') === '删除信息'){
            try{
                Db::execute('DELETE FROM `u_info` WHERE `id`=:id', ['id' => floatval(input('post.u_id'))]);
            }catch(\Exception $e){
                return $this->error('操作失败');
            }
            return $this->success('删除成功', config('site_root').'/');
        }else{
            return $this->error('非法操作');
        }
    }
    public function enroll_detail(){
        if(true !== ($res = $this->validate__($this->validations_enroll))){
            return $this->error($res);
        }
        try{
            Db::execute('INSERT INTO `u_info`(`id`,`name`,`sex`,`nation`,`native`,`birth`,`class`,`domitory`,`phone`,`qq`,`introduction`, `password`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)', [floatval(input('post.u_id')), input('post.u_name'),input('post.u_sex'), input('post.u_nation'), input('post.u_native'), strtotime(input('post.u_birth')), input('post.u_class'), input('post.u_domitory'), input('post.u_phone'), input('post.u_qq'), input('post.u_introduction'), md5(input('post.u_password').config('pass_salt'))]);
        }catch(\Exception $e){
            // var_dump($e); exit;
            return $this->error('操作失败，目测您已提交过信息了');
        }
        return $this->success('操作成功', config('site_root') . '/update?id=' . input('u_id'));
    }


    /* 不小心造了个轮子 */
    protected function validate__($rule){
        foreach($rule as $v){
            if(!preg_match($v[1], input($v[0]))){
                return $v[2];
            }
        }
        return true;
    }
    static function DJB($str){
        for($e=0,$d=5381,$b=$str,$h=strlen($b); $e<$h; ++$e)
            $d+= ($d<<5) + self::charCodeAt($b, $e);
        return ($d&2147483647) . '';
    }
    static function charCodeAt($str, $index){
        $char = mb_substr($str, $index, 1, 'UTF-8');
        if (mb_check_encoding($char, 'UTF-8')){
            $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
            return hexdec(bin2hex($ret));
        }else{
            return null;
        }
    }
}
