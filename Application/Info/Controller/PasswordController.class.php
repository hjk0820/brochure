<?php
namespace Info\Controller;
use Think\Controller;
class PasswordController extends Controller {
	protected function setnav() {
		$this->modules = json_encode(C('MODULE_LIST'));
		$this->controllers = json_encode(C('CONTROLLER_LIST'));
	}
    public function index() {
		if (IS_AJAX) {
		    $post = I('post.');
			if ( ! $post)
				$this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
			$info = D('index')->info();
	    	if (md5(base64_encode($post['oldpassword'])) !== $info['password']) {
	    		$this->ajaxReturn(array('status'=>'error','msg'=>'旧密码有误'));
	    	}
	    	$result = M('user')->save(array('id'=>$info['id'], 'password'=>md5(base64_encode($post['newpassword']))));
			if ( ! $result)
				$this->ajaxReturn(array('status'=>'error','msg'=>'修改失败'));
			D('index')->unsetRsCache($info['id']);
		    $this->ajaxReturn(array('status'=>'success','msg'=>'修改成功'));
		} else {
			$this->rs = D('index')->info();
			$this->setnav();
			$this->title = '密码修改';
			$this->display();
		}
    }
}