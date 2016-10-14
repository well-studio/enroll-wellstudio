<?php
namespace app\index\controller;
use \think\Controller;
use \think\Db;

class Index extends Controller{
	protected $beforeActionList = [
        'anti_spider_init',
        'csrf_init'
    ];
    protected function csrf_init(){
        $this->assign('csrfToken', self::DJB(md5(date('D'))));
    }
    protected function anti_spider_init(){
        session('anti_spider_token', 'well_studio');
    }
    public function index(){
        return $this->fetch();
    }
    public function update(){
        $res = Db::query('SELECT `id`,`name`,`sex`,`nation`,`native`,`birth`,`class`,`domitory`,`phone`,`qq`,`introduction`, `password` FROM `u_info` WHERE `id`=:id LIMIT 1', ['id' => floatval(input('get.id'))]);
        if(isset($res[0])){
            $this->assign('u', $res[0]);
            return $this->fetch();
        }else{
            return $this->error('没有这个学号哦~');
        }
    }
    static function DJB($str){
        for($e=0, $d=5381,$b=$str,$h=strlen($b); $e<$h; ++$e)
            $d+= ($d<<5) + self::charCodeAt($b,$e);
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
