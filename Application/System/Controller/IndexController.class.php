<?php
namespace System\Controller;
use Think\Controller;
class IndexController extends Controller {
	protected function setnav() {
		$this->modules = json_encode(C('MODULE_LIST'));
		$this->controllers = json_encode(C('CONTROLLER_LIST'));
	}
    public function index() {
    	$type = D('Info/index')->getType();
    	if ($type == 0) {
    		$this->assign(D('Info/index')->getSmtp());
    		$tpl = 'admin';
    	} else {
    		$tpl = 'member';
    	}
		$this->setnav();
		$this->title = '系统配置';
        $this->display($tpl);
    }
    public function getConfig() {
        if (IS_AJAX) {
            $this->ajaxReturn(D('Info/index')->getConfig());
        }
    }
    public function admin() {
        if (IS_POST) {
            $data = M('user')->create();
            if ( ! $data) {
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            }
            $data['id'] = 1;
            $result = M('user')->save($data);
            if ( ! $result) {
                $this->ajaxReturn(array('status'=>'error','msg'=>'修改失败'));
            }

            D('Info/index')->unsetRsCache($data['id']);
            $this->ajaxReturn(array('status'=>'success','msg'=>'修改成功'));
        }
    }
    public function member() {
        if (IS_POST) {
            $data = M('user')->create();
            if ( ! $data) {
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            }
            $data['id'] = D('Info/index')->getId();
            $result = M('user')->save($data);
            if ( ! $result) {
                $this->ajaxReturn(array('status'=>'error','msg'=>'修改失败'));
            }

            D('Info/index')->unsetRsCache($data['id']);
            $this->ajaxReturn(array('status'=>'success','msg'=>'修改成功'));
        }
    }
}