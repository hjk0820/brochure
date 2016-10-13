<?php
namespace Product\Controller;
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
            $uid = D('Info/index')->getId();
            $category = D('category')->getIdMapTitle($uid);
            $number = D('number')->getIdMapTitle($uid);
            $list = D('product')->getProduct($uid);
            if ($list) foreach ($list as $key=>$val) {
                $list[$key]['key'] = $val['id'];
                $list[$key]['image'] = cropimage($val['imglist'],'60x60');
                $list[$key]['category'] = isset($category[$val['cid']]) ? $category[$val['cid']] : '';
                $list[$key]['number'] = isset($number[$val['nid']]) ? $number[$val['nid']] : '';
                $list[$key]['addtime'] = date('Y-m-d',$val['addtime']);
            }
            $this->ajaxReturn($list);
        }
    }
    public function index() {
        $page = I('get.page',1);
        if ($page < 0)
            $page = 1;
        $uid = D('Info/index')->getId();
        $category = D('category')->getIdMapTitle($uid);
        $number = D('number')->getIdMapTitle($uid);
        $this->category = $category;
        $this->number = $number;
        $this->page = $page;
        $this->pageSize = 10;
        $this->setnav();
        $this->title = '产品列表';
        $this->display();
    }
    public function add() {
        if (IS_POST) {
            $data = M('product')->create();
            if ( ! $data)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            $imageList = I('post.imageList');
            $images = I('post.images');
            $isEmpty = empty($imageList);
            if ( ! empty($images)) {
                foreach ($images as $val) {
                    if (is_file('.'.$val)) {
                        if ( ! $isEmpty) {
                            if ( ! in_array($val,$imageList))
                                unlink('.'.$val);
                        } else {
                            unlink('.'.$val);
                        }
                    }
                }
            }
            if ( ! $isEmpty)
                $data['imglist'] = implode(',', $imageList);
            if ( ! isset($data['pdf']))
                $data['pdf'] = '';
            $pdfs = I('post.pdfs');
            if ( ! empty($pdfs)) {//删除已上传的无用PDF
                $pdfs = array_unique($pdfs);
                foreach ($pdfs as $val) {
                    if ($val != $data['pdf'] && is_file('.'.$val))
                        unlink('.'.$val);
                }
            }
            $data['uid'] = D('Info/index')->getId();
            $data['addtime'] = NOW_TIME;
            $result = M('product')->add($data);
            if ( ! $result) {
                if ($data['pdf'] != '' && is_file('.'.$data['pdf']))
                    unlink('.'.$data['pdf']);
                $this->ajaxReturn(array('status'=>'error','msg'=>'添加失败'));
            }
            D('product')->unsetProduct($data['uid']);
            $this->ajaxReturn(array('status'=>'success','msg'=>'添加成功'));
        } else {
            $uid = D('Info/index')->getId();
            $category = D('category')->getIdMapTitle($uid);
            $number = D('number')->getIdMapTitle($uid);
            $this->category = $category;
            $this->number = $number;
            $this->backurl = $this->_getBackurl();
            $this->setnav();
            $this->title = '添加产品';
            $this->display();
        }
    }
    public function mod($id) {
        if (IS_AJAX) {
            $data = M('product')->create();
            if ( ! $data)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            $imageList = I('post.imageList');
            $images = I('post.images');
            $isEmpty = empty($imageList);
            if ( ! empty($images)) {
                foreach ($images as $val) {
                    if (is_file('.'.$val)) {
                        if ( ! $isEmpty) {
                            if ( ! in_array($val,$imageList))
                                unlink('.'.$val);
                        } else {
                            unlink('.'.$val);
                        }
                    }
                }
            }
            $data['imglist'] = ! $isEmpty ? implode(',', $imageList) : '';
            if ( ! isset($data['pdf']))
                $data['pdf'] = '';
            $pdfs = I('post.pdfs');
            if ( ! empty($pdfs)) {//删除已上传的无用图片
                $pdfs = array_unique($pdfs);
                foreach ($pdfs as $val) {
                    if ($val != $data['pdf'] && is_file('.'.$val))
                        unlink('.'.$val);
                }
            }
            $data['id'] = $id;
            $result = M('product')->save($data);
            if ( ! $result)
                $this->ajaxReturn(array('status'=>'error','msg'=>'修改失败'));
            D('product')->unsetProduct(D('Info/index')->getId());
            $this->ajaxReturn(array('status'=>'success','msg'=>'修改成功'));
        } else {
            $uid = D('Info/index')->getId();
            $category = D('category')->getIdMapTitle($uid);
            $number = D('number')->getIdMapTitle($uid);
            $rs = M('product')->find($id);
            if ( ! array_key_exists($rs['cid'],$category))
                $rs['cid'] = 0;
            if ( ! array_key_exists($rs['nid'],$number))
                $rs['nid'] = 0;
            $rs['imageList'] = array();
            $rs['images'] = array();
            if ($rs['imglist']) {
                $imglist = explode(',', $rs['imglist']);
                $rs['images'] = $imglist;
                foreach ($imglist as $key=>$value) {
                    $rs['imageList'][] = array(
                        'uid'=> 'image-'.$key,
                        'name'=> $rs['title'],
                        'status'=> 'done',
                        'url'=> cropimage($value),
                        'thumbUrl'=> cropimage($value,'60x60'));
                }
            }
            $rs['pdfList'] = $rs['pdf'] ? array(
                array('uid'=> 'PDF-1',
                      'name'=> $rs['title'].'.'.pathinfo($rs['pdf'], PATHINFO_EXTENSION),
                      'status'=> 'done',
                      'url'=> $rs['pdf'],
                )
            ) : array();
            $this->rs = $rs;
            $this->category = $category;
            $this->number = $number;
            $this->backurl = $this->_getBackurl();
            $this->setnav();
            $this->title = '修改信息';
            $this->display();
        }
    }
    public function del($id) {
        if (IS_AJAX) {
            $rs = M('product')->find($id);
            $result = M('product')->delete($rs['id']);
            if ( ! $result)
                $this->ajaxReturn(array('status'=>'error','msg'=>'删除失败'));
            if ($rs['imglist']) {
                $imglist = explode(',', $rs['imglist']);
                foreach ($imglist as $val) {
                    if (is_file('.'.$val))
                        unlink('.'.$val);
                }
            }
            if (is_file('.'.$rs['pdf']))
                unlink('.'.$rs['pdf']);
            D('product')->unsetProduct(D('Info/index')->getId());
            $this->ajaxReturn(array('status'=>'success','msg'=>'删除成功'));
        }
    }
    public function batchDelete() {
        if (IS_AJAX) {
            $ids = I('post.ids');
            if ( ! $ids)
                $this->ajaxReturn(array('status'=>'warning','msg'=>'无效数据'));
            $list = M('product')->Where(array('id'=>array('in',$ids)))->getField('id,imglist,pdf');
            $result = M('product')->Where(array('id'=>array('in',$ids)))->delete();
            if ( ! $result)
                $this->ajaxReturn(array('status'=>'error','msg'=>'删除失败'));
            if ($list) foreach ($list as $value) {
                if ($value['imglist']) {
                    $imglist = explode(',', $value['imglist']);
                    foreach ($imglist as $val) {
                        if (is_file('.'.$val))
                            unlink('.'.$val);
                    }
                }
                if (is_file('.'.$value['pdf']))
                    unlink('.'.$value['pdf']);
            }
            D('product')->unsetProduct(D('Info/index')->getId());
            $this->ajaxReturn(array('status'=>'success','msg'=>'删除成功'));
        }
    }
}