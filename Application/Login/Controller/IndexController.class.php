<?php
namespace Login\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	if (IS_AJAX) {
	    	$post = I('post.');
		    if ( ! $post)
		    	$this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
	        $user = M('user')->where(array('username'=>trim($post['username']),'status'=>0))->find();
		    if ( ! $user)
		    	$this->ajaxReturn(array('status'=>'error','msg'=>'账户有误'));
        	$data = array();
        	$data['uid'] = $user['id'];
        	$data['ip'] = get_client_ip();
        	$data['area'] = getIpLocation($data['ip']);
        	$data['area'] = $data['area'] ? $data['area'] : '-';
        	$data['addtime'] = NOW_TIME;
		    if (md5(base64_encode($post['password'])) !== $user['password']) {
		    	$data['status'] = 1;
	    		if ( ! M('login_log')->add($data))
	    			$this->ajaxReturn(array('status'=>'error','msg'=>'记录失败'));
		    	$this->ajaxReturn(array('status'=>'error','msg'=>'密码错误'));
		    }
    		if ( ! M('login_log')->add($data))
    			$this->ajaxReturn(array('status'=>'error','msg'=>'记录失败'));
            $userData = array();
            $userData['id'] = $user['id'];
            $userData['lasttime'] = $user['currenttime'];
            $userData['lastip'] = $user['currentip'];
            $userData['currenttime'] = $data['addtime'];
            $userData['currentip'] = $data['ip'];
            if ($user['type'] == 1) {//企业会员登陆
                if ($user['expire_data'] < NOW_TIME)
                    $this->ajaxReturn(array('status'=>'error','msg'=>'账号到期，请联系管理管员'));
                if ($user['space'] < $user['usedspace'])
                    $this->ajaxReturn(array('status'=>'error','msg'=>'空间已满，请联系管理管员'));
                $dir = './Uploads/'.$user['id'];
                if (is_dir($dir)) {
                    $space = getDirSize($dir);
                    if ($user['space']*1024*1024 < $space)
                        $this->ajaxReturn(array('status'=>'error','msg'=>'空间已满，请联系管理管员'));
                    $userData['usedspace'] = round($space/(1024*1024),2);
                }
            }
			if ( ! M('user')->save($userData))
				$this->ajaxReturn(array('status'=>'error','msg'=>'登陆失败'));
			//设置session
			$sessionData = array();
			$sessionData['id'] = $user['id'];
			session('user',$sessionData);
			D('Info/index')->unsetRsCache($user['id']);
		    $this->ajaxReturn(array('status'=>'success','msg'=>'登陆成功'));
    	} else {
	    	$bgimg = array('bg_01.jpg', 'bg_02.jpg', 'bg_03.jpg',
	    		           'bg_04.jpg', 'bg_05.jpg', 'bg_06.jpg',
	    		           'bg_07.jpg');
	    	$this->bgimg = $bgimg[array_rand($bgimg,1)];
	        $this->display();
    	}
    }
}