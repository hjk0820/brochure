<?php
namespace Product\Model;
use Think\Model;
class ProductModel extends Model{
	private function _cachePath() {
    	static $path;
    	if (!isset($path)) {
    		$path = str_replace(array('\\','Model'), array('/',''), __CLASS__).'/';
    	}
    	return $path;
	}
	public function getProduct($uid) {
        $list = F($this->_cachePath().$uid.'_product');
        if (!$list) {
            $condition = array();
            $condition['uid'] = $uid;
            $list = M('product')->where($condition)->order('sort desc,id desc')->select();
        	F($this->_cachePath().$uid.'_product', $list);
        }
        return $list;
	}
	public function unsetProduct($uid) {
		F($this->_cachePath().$uid.'_product', NULL);
	}
}