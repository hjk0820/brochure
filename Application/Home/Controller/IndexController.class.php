<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if (D('Info/index')->getType() == 0) {
            $tpl = 'admin';
        } else {
            $tpl = 'member';
        }
    	$this->modules = json_encode(C('MODULE_LIST'));
        $this->display($tpl);
    }
    public function admin() {
        $totalMember = M('user')->where(array('type'=>1))->count();
        $totalEmail = M('email')->count();
        $monthEmail = M('email')->where(array('addtime'=>array('between',$this->getMonth())))->count();
        $login = $this->getLogin();
        $smtp = $this->getSmtp();
        $statistics = $this->getStatistics();
        $this->ajaxReturn(array(
            'totalMember'=>$totalMember,
            'totalEmail'=>$totalEmail,
            'monthEmail'=>$monthEmail,
            'login'=>$login,
            'smtp'=>$smtp,
            'statistics'=>$statistics)
        );
    }
    public function member() {
        $uid = D('Info/index')->getId();
        $totalProduct = M('product')->where(array('uid'=>$uid))->count();
        $totalEmail = M('email')->where(array('uid'=>$uid))->count();
        $monthEmail = M('email')->where(array('uid'=>$uid,'addtime'=>array('between',$this->getMonth())))->count();
        $login = $this->getLogin();
        $smtp = $this->getCompany($uid);
        $statistics = $this->getCompanyStatistics();
        $this->ajaxReturn(array(
            'totalProduct'=>$totalProduct,
            'totalEmail'=>$totalEmail,
            'monthEmail'=>$monthEmail,
            'login'=>$login,
            'smtp'=>$smtp,
            'statistics'=>$statistics)
        );
    }
    //获取本月日期
    public function getMonth(){
        $firstday = date("Y-m-01 0:0:0",NOW_TIME);
        $lastday = date("Y-m-d 0:0:0",strtotime("$firstday +1 month"));
        return array(strtotime($firstday),strtotime($lastday));
    }
    //登陆信息
    public function getLogin(){
        $rs = array();
        $info = D('Info/index')->info();
        $rs['currentip'] = $info['currentip'];
        $rs['currenttime'] = date('Y/m/d H:s:i',$info['currenttime']);
        if ($info['lasttime'] == 0) {
            $rs['lastip'] = '未登陆';
            $rs['lasttime'] = '未登陆';
        } else {
            $rs['lastip'] = $info['lastip'];
            $rs['lasttime'] = date('Y/m/d H:s:i',$info['lasttime']);
        }
        return $rs;
    }
    //邮件服务器配置
    public function getSmtp(){
        $rs = array();
        $smtp = D('Info/index')->getSmtp();
        $rs['host'] = $smtp['host'] ? $smtp['host'] : '未配置';
        $rs['port'] = $smtp['port'] ? $smtp['port'] : '未配置';
        $rs['username'] = $smtp['username'] ? $smtp['username'] : '未配置';
        return $rs;
    }
    //企业邮件服务器配置
    public function getCompany($uid){
        $rs = array();
        $smtp = D('Info/index')->getCompany($uid);
        $rs['host'] = $smtp['smtp']['host'] ? $smtp['smtp']['host'] : '未配置';
        $rs['port'] = $smtp['smtp']['port'] ? $smtp['smtp']['port'] : '未配置';
        $rs['username'] = $smtp['smtp']['username'] ? $smtp['smtp']['username'] : '未配置';
        return $rs;
    }
    //邮件统计
    public function getStatistics(){
        $list = array();
        $count = M('email')->group('uid')->order('num')->getField('uid,count(*) as num');
        $company = M('user')->where(array('type'=>1))->getField('id,company');
        if ($company) foreach ($company as $key => $val) {
            $list[] = array(
                'company' => $val,
                'num' => isset($count[$key]) ? $count[$key] : 0
            );
        }
        return $list;
    }
    //企业邮件统计
    public function getCompanyStatistics(){
        $list = array();
        $uid = D('Info/index')->getId();
        $count = M('email')->where(array('uid'=>$uid))->group('sid')->order('num')->getField('sid,count(*) as num');
        $staff = D('System/staff')->getIdMapName($uid);
        if ($staff) foreach ($staff as $key => $val) {
            $list[] = array(
                'name' => $val,
                'num' => isset($count[$key]) ? $count[$key] : 0
            );
        }
        return $list;
    }
}