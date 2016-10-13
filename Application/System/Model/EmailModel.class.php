<?php
namespace System\Model;
use Think\Model;
class EmailModel extends Model{
	private function _cachePath() {
    	static $path;
    	if (!isset($path)) {
    		$path = str_replace(array('\\','Model'), array('/',''), __CLASS__).'/';
    	}
    	return $path;
	}
    public function getList() {
        $list = F($this->_cachePath().'email');
        if (!$list) {
            $condition = array();
            $list = M('email')->where($condition)->order('id desc')->select();
            F($this->_cachePath().'email', $list);
        }
        return $list;
    }
	public function getEmail() {
        $uid = D('Info/index')->getId();
        $list = F($this->_cachePath().$uid.'_email');
        if (!$list) {
            $condition = array();
            $condition['uid'] = $uid;
            $list = M('email')->where($condition)->order('id desc')->select();
        	F($this->_cachePath().$uid.'_email', $list);
        }
        return $list;
	}
	public function unsetEmail($uid) {
        F($this->_cachePath().'email', NULL);
		F($this->_cachePath().$uid.'_email', NULL);
	}
}