<?php
namespace System\Model;
use Think\Model;
class StaffModel extends Model{
	private function _cachePath() {
    	static $path;
    	if (!isset($path)) {
    		$path = str_replace(array('\\','Model'), array('/',''), __CLASS__).'/';
    	}
    	return $path;
	}
	public function getStaff($uid) {
        $list = F($this->_cachePath().$uid.'_staff');
        if (!$list) {
            $condition = array();
            $condition['uid'] = $uid;
            $list = M('staff')->where($condition)->order('sort desc,id desc')->select();
        	F($this->_cachePath().$uid.'_staff', $list);
        }
        return $list;
	}
    public function getIdMapName($uid) {
        static $data = array();
        if ($data)
            return $data;
        $list = $this->getStaff($uid);
        if ($list) foreach ($list as $val) {
            $data[$val['id']] = $val['name'];
        }
        return $data;
    }
	public function unsetStaff($uid) {
		F($this->_cachePath().$uid.'_staff', NULL);
	}
}