<?php
namespace Product\Model;
use Think\Model;
class NumberModel extends Model{
	private function _cachePath() {
    	static $path;
    	if (!isset($path)) {
    		$path = str_replace(array('\\','Model'), array('/',''), __CLASS__).'/';
    	}
    	return $path;
	}
	public function getNumber($uid) {
        $list = F($this->_cachePath().$uid.'_number');
        if (!$list) {
            $condition = array();
            $condition['uid'] = $uid;
            $list = M('number')->where($condition)->order('sort asc,id asc')->select();
        	F($this->_cachePath().$uid.'_number', $list);
        }
        return $list;
	}
    public function getIdMapTitle($uid) {
        static $data = array();
        if ($data)
            return $data;
        $list = $this->getNumber($uid);
        if ($list) foreach ($list as $val) {
            $data[$val['id']] = $val['title'];
        }
        return $data;
    }
	public function unsetNumber($uid) {
		F($this->_cachePath().$uid.'_number', NULL);
	}
}