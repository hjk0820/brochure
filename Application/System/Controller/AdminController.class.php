<?php
namespace System\Controller;
use Think\Controller;
class AdminController extends Controller {
	protected function setnav() {
		$this->modules = json_encode(C('MODULE_LIST'));
		$this->controllers = json_encode(C('CONTROLLER_LIST'));
	}
    private function _getBackurl() {
        $page = I('get.page', 1);
        foreach (C('MODULE_LIST') as $item) {
            if (MODULE_NAME === $item['key']) {
                $this->moduleBread = array('url'=>$item['url'],'title'=>$item['title']);
                break;
            }
        }
        foreach (C('CONTROLLER_LIST') as $item) {
            if (CONTROLLER_NAME === $item['key']) {
                $backurl = $item['url'];
                if ($page > 1)
                    $backurl .= '/page/'.$page;
                $this->controllerBread = array('url'=>$backurl,'title'=>$item['title']);
                break;
            }
        }
        return $backurl;
    }
    public function index() {
        $page = I('get.page',1);
        if ($page < 0)
            $page = 1;
        $this->page = $page;
        $this->pageSize = 10;
		$this->setnav();
		$this->title = '管理员管理';
        $this->display();
    }
    public function getList() {
    	if (IS_AJAX) {
            $condition = array();
            if (D('Info/index')->getId() != 1)
            	$condition['id'] = array('neq',1);
            $condition['type'] = 0;
        	$list = M('user')->where($condition)->field('id,username,lastip,lasttime,currentip,currenttime,status')->order('id desc')->select();
            if ($list) foreach ($list as $key=>$val) {
                $list[$key]['key'] = $val['id'];
                if ($val['lasttime'] != 0) {
                    $list[$key]['lasttime'] = date('Y-m-d H:i:s', $val['lasttime']);
                } else {
                    $list[$key]['lastip'] = '未登录';
                    $list[$key]['lasttime'] = '未登录';
                }
                if ($val['currenttime'] != 0) {
                    $list[$key]['currenttime'] = date('Y-m-d H:i:s', $val['currenttime']);
                } else {
                    $list[$key]['currentip'] = '未登录';
                    $list[$key]['currenttime'] = '未登录';
                }
                $list[$key]['status_msg'] = $val['status'] == 0 ? '启用' : '禁用';
            }
	    	$this->ajaxReturn($list);
    	}
    }
    public function check($username) {
        if (IS_AJAX) {
            $result = D('user')->getUsernameList();
            if ($result === false)
                $this->ajaxReturn(array('status'=>'error','msg'=>'查询失败'));
            if (in_array($username,$result))
                $this->ajaxReturn(array('status'=>'warning','msg'=>'该账号已经注册过'));
            $this->ajaxReturn(array('status'=>'success','msg'=>'数据不存在'));
        }
    }
    public function add() {
		if (IS_POST) {
			$data = M('user')->create();
            if ( ! $data)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            $data['password'] = md5(base64_encode($data['password']));
            $data['addtime'] = NOW_TIME;
            $result = M('user')->add($data);
            if ( ! $result)
				$this->ajaxReturn(array('status'=>'error','msg'=>'添加失败'));
            D('user')->unsetUsernameList();
		    $this->ajaxReturn(array('status'=>'success','msg'=>'添加成功'));
		} else {
			$this->backurl = $this->_getBackurl();
			$this->setnav();
			$this->title = '添加管理员';
			$this->display();
		}
    }
    public function mod($id) {
		if (IS_AJAX) {
			$data = M('user')->create();
            if ( ! $data)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            $data['id'] = $id;
            $data['password'] = md5(base64_encode($data['password']));
            $info = D('Info/index')->getRs($data['id']);
            if ($info['password'] == $data['password'])
                $this->ajaxReturn(array('status'=>'error','msg'=>'新旧密码一致'));
            $result = M('user')->save($data);
			if ( ! $result)
				$this->ajaxReturn(array('status'=>'error','msg'=>'修改失败'));
            D('Info/index')->unsetRsCache($data['id']);
		    $this->ajaxReturn(array('status'=>'success','msg'=>'修改成功'));
		} else {
            $info = D('Info/index')->getRs($data['id']);
			$this->username = $info['username'];
			$this->backurl = $this->_getBackurl();
			$this->setnav();
			$this->title = '修改信息';
			$this->display();
		}
    }
    public function del($id) {
		if (IS_AJAX) {
            if ($id == 1)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'系统管理员禁止删除'));
			$result = M('user')->delete($id);
			if ( ! $result)
				$this->ajaxReturn(array('status'=>'error','msg'=>'删除失败'));
			D('user')->unsetUsernameList();
		    $this->ajaxReturn(array('status'=>'success','msg'=>'删除成功'));
		}
    }
    public function status($id, $status) {
        if (IS_AJAX) {
            $data = M('user')->create();
            if ( ! $data)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            if ($data['id'] == 1)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'系统管理员禁止操作'));
            $data['status'] = $data['status'] == 0 ? 1 : 0;
            $result = M('user')->save($data);
            if ( ! $result)
                $this->ajaxReturn(array('status'=>'error','msg'=>'操作失败'));
            D('Info/index')->unsetRsCache($data['id']);
            $this->ajaxReturn(array('status'=>'success','msg'=>'操作成功'));
        }
    }
}