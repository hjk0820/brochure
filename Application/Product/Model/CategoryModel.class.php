<?php
namespace Product\Model;
use Think\Model;
class CategoryModel extends Model{
	private function _cachePath() {
    	static $path;
    	if (!isset($path)) {
    		$path = str_replace(array('\\','Model'), array('/',''), __CLASS__).'/';
    	}
    	return $path;
	}
	public function getCategory($uid) {
        $list = F($this->_cachePath().$uid.'_category');
        if (!$list) {
            $condition = array();
            $condition['uid'] = $uid;
            $list = M('category')->where($condition)->order('sort asc,id asc')->select();
        	F($this->_cachePath().$uid.'_category', $list);
        }
        return $list;
	}
    public function getIdMapTitle($uid) {
        static $data = array();
        if ($data)
            return $data;
        $list = $this->getCategory($uid);
        if ($list) foreach ($list as $val) {
            $data[$val['id']] = $val['title'];
        }
        return $data;
    }
	public function unsetCategory($uid) {
		F($this->_cachePath().$uid.'_category', NULL);
	}
}