<?php
namespace Member\Controller;
use Think\Controller;
class IndexController extends Controller {
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
    public function getList() {
    	if (IS_AJAX) {
            $condition = array();
            $condition['type'] = 1;
        	$list = M('user')->where($condition)->field('id,company,username,contact,tel,usedspace,space,expire_data,addtime,status')->order('id desc')->select();
            if ($list) foreach ($list as $key=>$val) {
                $list[$key]['key'] = $val['id'];
                $list[$key]['space_class'] = $val['space'] < $val['usedspace'] ? 'red' : '';
                $list[$key]['expire_data'] = date('Y-m-d', $val['expire_data']);
                $list[$key]['expire_data_class'] = $val['expire_data'] < NOW_TIME ? 'red' : '';
                $list[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
                $list[$key]['status_msg'] = $val['status'] == 0 ? '启用' : '禁用';
            }
	    	$this->ajaxReturn($list);
    	}
    }
    public function check($username) {
        if (IS_AJAX) {
            $result = D('System/user')->getUsernameList();
            if ($result === false)
                $this->ajaxReturn(array('status'=>'error','msg'=>'查询失败'));
            if (in_array($username,$result))
                $this->ajaxReturn(array('status'=>'warning','msg'=>'该账号已经注册过'));
            $this->ajaxReturn(array('status'=>'success','msg'=>'数据不存在'));
        }
    }
    public function getYearTime() {
        return strtotime(date("Y-m-d 0:0:0",NOW_TIME).' +1 year');
    }
    public function index() {
        $page = I('get.page',1);
        if ($page < 0)
            $page = 1;
        $this->page = $page;
        $this->pageSize = 10;
        $this->setnav();
        $this->title = '会员列表';
        $this->display();
    }
    public function add() {
		if (IS_POST) {
			$data = M('user')->create();
            if ( ! $data)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            $data['type'] = 1;
            $data['password'] = md5(base64_encode($data['password']));
            if ($data['expire_data'] == 0) {
                $data['expire_data'] = $this->getYearTime();
            } else {
                $data['expire_data'] = strtotime(date('Y-m-d',I('post.expire_data')));
            }
            $data['addtime'] = NOW_TIME;
            $result = M('user')->add($data);
            if ( ! $result)
				$this->ajaxReturn(array('status'=>'error','msg'=>'添加失败'));
            D('System/user')->unsetUsernameList();
		    $this->ajaxReturn(array('status'=>'success','msg'=>'添加成功'));
		} else {
            $this->expire_data = date('Y-m-d',$this->getYearTime());
			$this->backurl = $this->_getBackurl();
			$this->setnav();
			$this->title = '添加会员';
			$this->display();
		}
    }
    public function mod($id) {
		if (IS_AJAX) {
			$data = M('user')->create();
            if ( ! $data)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            $data['id'] = $id;
            if ($data['expire_data'] == 0) {
                $data['expire_data'] = $this->getYearTime();
            } else {
                $data['expire_data'] = strtotime(date('Y-m-d',I('post.expire_data')));
            }
            $info = D('Info/index')->getRs($data['id']);
            if ($info['password'] != $data['password'])
                $data['password'] = md5(base64_encode($data['password']));
            $result = M('user')->save($data);
			if ( ! $result)
				$this->ajaxReturn(array('status'=>'error','msg'=>'修改失败'));
            D('Info/index')->unsetRsCache($data['id']);
		    $this->ajaxReturn(array('status'=>'success','msg'=>'修改成功'));
		} else {
            $info = D('Info/index')->getRs($id);
            $info['expire_data'] = date('Y-m-d',$info['expire_data']);
			$this->rs = $info;
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
			D('System/user')->unsetUsernameList();
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