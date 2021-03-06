<?php
namespace Product\Controller;
use Think\Controller;
class CategoryController extends Controller {
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
            $list = D('category')->getCategory(D('Info/index')->getId());
            if ($list) foreach ($list as $key=>$val) {
                $list[$key]['key'] = $val['id'];
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
        $this->title = '分类管理';
        $this->display();
    }
    public function add() {
        if (IS_POST) {
            $data = M('category')->create();
            if ( ! $data)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            $data['uid'] = D('Info/index')->getId();
            $result = M('category')->add($data);
            if ( ! $result)
                $this->ajaxReturn(array('status'=>'error','msg'=>'添加失败'));
            D('category')->unsetCategory($data['uid']);
            $this->ajaxReturn(array('status'=>'success','msg'=>'添加成功'));
        } else {
            $this->backurl = $this->_getBackurl();
            $this->setnav();
            $this->title = '添加分类';
            $this->display();
        }
    }
    public function mod($id) {
        if (IS_AJAX) {
            $data = M('category')->create();
            if ( ! $data)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            $data['id'] = $id;
            $result = M('category')->save($data);
            if ( ! $result)
                $this->ajaxReturn(array('status'=>'error','msg'=>'修改失败'));
            D('category')->unsetCategory(D('Info/index')->getId());
            $this->ajaxReturn(array('status'=>'success','msg'=>'修改成功'));
        } else {
            $rs = M('category')->find($id);
            $this->rs = $rs;
            $this->backurl = $this->_getBackurl();
            $this->setnav();
            $this->title = '修改信息';
            $this->display();
        }
    }
    public function del($id) {
        if (IS_AJAX) {
            $result = M('category')->delete($id);
            if ( ! $result)
                $this->ajaxReturn(array('status'=>'error','msg'=>'删除失败'));
            D('category')->unsetCategory(D('Info/index')->getId());
            $this->ajaxReturn(array('status'=>'success','msg'=>'删除成功'));
        }
    }
}