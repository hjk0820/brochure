<?php
namespace Product\Controller;
use Think\Controller;
class NumberController extends Controller {
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
            $list = D('number')->getNumber(D('Info/index')->getId());
            if ($list) foreach ($list as $key=>$val) {
                $list[$key]['key'] = $val['id'];
                //$list[$key]['image'] = cropimage($val['image'],'60x60');
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
        $this->title = '页码管理';
        $this->display();
    }
    public function add() {
        if (IS_POST) {
            $data = M('number')->create();
            if ( ! $data)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            if ( ! isset($data['image']))
                $data['image'] = '';
            $images = I('post.images');
            if ( ! empty($images)) {//删除已上传的无用图片
                $images = array_unique($images);
                foreach ($images as $val) {
                    if ($val != $data['image'] && is_file('.'.$val))
                        unlink('.'.$val);
                }
            }
            $data['uid'] = D('Info/index')->getId();
            $result = M('number')->add($data);
            if ( ! $result) {
                if ($data['image'] != '' && is_file('.'.$data['image']))
                    unlink('.'.$data['image']);
                $this->ajaxReturn(array('status'=>'error','msg'=>'添加失败'));
            }
            D('number')->unsetNumber($data['uid']);
            $this->ajaxReturn(array('status'=>'success','msg'=>'添加成功'));
        } else {
            $this->backurl = $this->_getBackurl();
            $this->setnav();
            $this->title = '添加页码';
            $this->display();
        }
    }
    public function mod($id) {
        if (IS_AJAX) {
            $data = M('number')->create();
            if ( ! $data)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            if ( ! isset($data['image']))
                $data['image'] = '';
            $images = I('post.images');
            if ( ! empty($images)) {//删除已上传的无用图片
                $images = array_unique($images);
                foreach ($images as $val) {
                    if ($val != $data['image'] && is_file('.'.$val))
                        unlink('.'.$val);
                }
            }
            $data['id'] = $id;
            $result = M('number')->save($data);
            if ( ! $result)
                $this->ajaxReturn(array('status'=>'error','msg'=>'修改失败'));
            D('number')->unsetNumber(D('Info/index')->getId());
            $this->ajaxReturn(array('status'=>'success','msg'=>'修改成功'));
        } else {
            $rs = M('number')->find($id);
            $rs['imageList'] = $rs['image'] ? array(
                array('uid'=> -1,
                      'name'=> $rs['title'],
                      'status'=> 'done',
                      'url'=> $rs['image'],
                      'thumbUrl'=> $rs['image'].'_98x98',
                )
            ) : array();
            $this->rs = $rs;
            $this->backurl = $this->_getBackurl();
            $this->setnav();
            $this->title = '修改信息';
            $this->display();
        }
    }
    public function del($id) {
        if (IS_AJAX) {
            $rs = M('number')->find($id);
            $result = M('number')->delete($rs['id']);
            if ( ! $result)
                $this->ajaxReturn(array('status'=>'error','msg'=>'删除失败'));
            if ( ! empty($rs['image']) && is_file('.'.$rs['image']))
                unlink('.'.$rs['image']);
            D('number')->unsetNumber(D('Info/index')->getId());
            $this->ajaxReturn(array('status'=>'success','msg'=>'删除成功'));
        }
    }
}