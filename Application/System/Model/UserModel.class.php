<?php
namespace System\Model;
use Think\Model;
class UserModel extends Model{
	private function _cachePath() {
    	static $path;
    	if (!isset($path)) {
    		$path = str_replace(array('\\','Model'), array('/',''), __CLASS__).'/';
    	}
    	return $path;
	}
	public function getUsernameList() {
        $list = F($this->_cachePath().'username');
        if (!$list) {
        	$list = $this->getField('username',true);
        	F($this->_cachePath().'username', $list);
        }
        return $list;
	}
	public function unsetUsernameList() {
		F($this->_cachePath().'username', NULL);
	}
}