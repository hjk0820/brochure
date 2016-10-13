<?php
namespace Info\Controller;
use Think\Controller;
class LoginController extends Controller {
	protected function setnav() {
		$this->modules = json_encode(C('MODULE_LIST'));
		$this->controllers = json_encode(C('CONTROLLER_LIST'));
	}
    public function getList() {
        if (IS_AJAX) {
            $condition = array();
            $condition['uid'] = D('index')->getId();
            $list =  M('login_log')->where($condition)->order('id desc')->select();
            if ($list) foreach ($list as $key=>$val) {
                $list[$key]['key'] = $val['id'];
                $list[$key]['status_msg'] = $val['status'] == 0 ? '成功登陆' : '登陆失败';
                $list[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
            }
            $this->ajaxReturn($list);
        }
    }
    public function index() {
        $page = I('get.page',1);
        if ($page < 0)
            $page = 1;
        $this->page = $page;
        $this->pageSize = 10;
        $this->setnav();
        $this->title = '登陆信息';
        $this->display();
    }
}