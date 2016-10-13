<?php
namespace System\Controller;
use Think\Controller;
class EmailController extends Controller {
    protected function setnav() {
        $this->modules = json_encode(C('MODULE_LIST'));
        $this->controllers = json_encode(C('CONTROLLER_LIST'));
    }
    public function getCompany() {
        return M('user')->where(array('type'=>1))->getField('id,company');
    }
    public function index() {
        $page = I('get.page',1);
        if ($page < 0)
            $page = 1;
        $this->page = $page;
        $this->pageSize = 10;
        $tpl = 'member';
        if (D('Info/index')->getType() == 0) {
            $tpl = 'admin';
            $this->company = $this->getCompany();
        }
        $this->setnav();
        $this->title = '邮件记录';
        $this->display($tpl);
    }
    public function admin() {
        if (IS_AJAX) {
            $list = D('email')->getList();
            if ($list) {
                $company = $this->getCompany();
                foreach ($list as $key=>$val) {
                    $list[$key]['key'] = $val['id'];
                    $list[$key]['company'] = isset($company[$val['uid']]) ? $company[$val['uid']] : '';
                    $list[$key]['ustatus_class'] = $val['ustatus'] == 0 ? 'green' : 'red';
                    $list[$key]['sstatus_class'] = $val['sstatus'] == 0 ? 'green' : 'red';
                    $list[$key]['pstatus_class'] = $val['pstatus'] == 0 ? 'green' : 'red';
                    $list[$key]['ustatus_msg'] = $val['ustatus'] == 0 ? '回执成功' : '回执失败';
                    $list[$key]['sstatus_msg'] = $val['sstatus'] == 0 ? '回执成功' : '回执失败';
                    $list[$key]['pstatus_msg'] = $val['pstatus'] == 0 ? '发送成功' : '发送失败';
                    $list[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
                }
            }
            $this->ajaxReturn($list);
        }
    }
    public function member() {
        if (IS_AJAX) {
            $list = D('email')->getEmail();
            if ($list) {
                $company = M('user')->where(array('type'=>1))->getField('id,company');
                foreach ($list as $key=>$val) {
                    $list[$key]['key'] = $val['id'];
                    $list[$key]['company'] = isset($company[$val['uid']]) ? $company[$val['uid']] : '';
                    $list[$key]['ustatus_class'] = $val['ustatus'] == 0 ? 'green' : 'red';
                    $list[$key]['sstatus_class'] = $val['sstatus'] == 0 ? 'green' : 'red';
                    $list[$key]['pstatus_class'] = $val['pstatus'] == 0 ? 'green' : 'red';
                    $list[$key]['ustatus_msg'] = $val['ustatus'] == 0 ? '回执成功' : '回执失败';
                    $list[$key]['sstatus_msg'] = $val['sstatus'] == 0 ? '回执成功' : '回执失败';
                    $list[$key]['pstatus_msg'] = $val['pstatus'] == 0 ? '发送成功' : '发送失败';
                    $list[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
                }
            }
            $this->ajaxReturn($list);
        }
    }
}