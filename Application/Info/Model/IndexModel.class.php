<?php
namespace Info\Model;
use Think\Model;
class IndexModel extends Model{
	protected $tableName = 'user';
	private function _cachePath() {
    	static $path;
    	if (!isset($path)) {
    		$path = str_replace(array('\\','Model'), array('/',''), __CLASS__).'/';
    	}
    	return $path;
	}
	public function info() {
        $rs = $this->getRs(session('user.id'));
        return $rs;
	}
	public function getType() {
		$info = $this->info();
		return (int)$info['type'];
	}
    public function getId() {
        $info = $this->info();
        return (int)$info['id'];
    }
    public function getValue($field) {
        $info = $this->info();
        return isset($info[$field]) ? $info[$field] : NULL;
    }
    public function getRs($id) {
        $rs = F($this->_cachePath().$id.'_info');
        if (!$rs) {
            $rs = $this->where(array('id'=>$id))->find();
            F($this->_cachePath().$id.'_info', $rs);
        }
        return $rs;
    }
    //获取系统邮件配置
    public function getSmtp() {
        $rs = $this->getRs(1);
        return array(
            'host'=>$rs['smtp_host'],'port'=>$rs['smtp_port'],
            'username'=>$rs['smtp_username'],'password'=>$rs['smtp_password']);
    }
    //获取会员系统配置
    public function getConfig() {
        $info = $this->info();
        return array(
            'smtp'=>array(
                'host'=>$info['smtp_host'],'port'=>$info['smtp_port'],
                'username'=>$info['smtp_username'],'password'=>$info['smtp_password'],
                'email'=>$info['email']),
            'company'=>array(
                'slogan'=>$info['company_slogan'],'desc'=>$info['company_desc'],'contact'=>$info['company_contact']),
            'auth'=>array(
                'password'=>$info['auth_password']));
    }
    //获取会员系统配置
    public function getCompany($uid) {
        $rs = $this->getRs($uid);
        return array(
            'smtp'=>array(
                'host'=>$rs['smtp_host'],'port'=>$rs['smtp_port'],
                'username'=>$rs['smtp_username'],'password'=>$rs['smtp_password'],
                'email'=>$rs['email']),
            'company'=>array(
                'title'=>$rs['company'],'slogan'=>$rs['company_slogan'],
                'desc'=>$rs['company_desc'],'contact'=>$rs['company_contact']),
            'auth'=>array(
                'password'=>$rs['auth_password']));
    }
    //对外接口
    public function unsetCache() {
        F($this->_cachePath().$this->getId().'_info', NULL);
    }
    //删除指定缓存
    public function unsetRsCache($id) {
        F($this->_cachePath().$id.'_info', NULL);
    }
}