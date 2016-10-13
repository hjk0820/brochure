<?php
namespace Book\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index($uid,$nid) {
    	if (IS_POST) {
    		$password = I('post.password');
            if ( ! $password)
                $this->ajaxReturn(array('status'=>0, 'msg'=>'Invalid Data'));
            $company = D('Info/index')->getCompany($uid);
            if ( ! $company)
            	$this->ajaxReturn(array('status'=>0, 'msg'=>'Invalid parameter'));
            if ($password != $company['auth']['password'])
            	$this->ajaxReturn(array('status'=>0, 'msg'=>'Password error'));
    		session('uid',$uid);
    		$this->ajaxReturn(array('status'=>1, 'msg'=>'Successful landing, the jump page',
    			'url'=>U('Book/Index/index',array('uid'=>$uid,'nid'=>$nid))));
    	}
        $info = D('Info/index')->getRs($uid);
        if ( ! $info || $info['status'] == 1 || $info['expire_data'] < NOW_TIME || $info['space'] < $info['usedspace'])
            exit('Invalid scan code');
    	$this->display();
	}
}