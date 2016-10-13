<?php
namespace Info\Controller;
use Think\Controller;
class IndexController extends Controller {
	protected function setnav() {
		$this->modules = json_encode(C('MODULE_LIST'));
		$this->controllers = json_encode(C('CONTROLLER_LIST'));
	}
    public function index() {
        $tpl = 'admin';
    	$type = C('USER_TYPE');
    	$rs = D('index')->info();
    	$rs['typename'] = $rs['id'] == 1 ? '系统管理员' : $type[$rs['type']];
        $rs['currenttime'] = date('Y/m/d H:s:i',$rs['currenttime']);
        if ($rs['lasttime'] == 0) {
            $rs['lastip'] = '未登陆';
            $rs['lasttime'] = '未登陆';
        } else {
            $rs['lasttime'] = date('Y/m/d H:s:i',$rs['lasttime']);
        }
        if ($rs['type'] != 0) {
            $tpl = 'member';
            $rs['space_class'] = $rs['space'] < $rs['usedspace'] ? 'red' : '';
            $rs['expire_data_class'] = $rs['expire_data'] < NOW_TIME ? 'red' : '';
            $rs['expire_data'] = date('Y-m-d', $rs['expire_data']);
        }
    	$this->rs = $rs;
		$this->setnav();
		$this->title = '基本信息';
        $this->display($tpl);
    }
}