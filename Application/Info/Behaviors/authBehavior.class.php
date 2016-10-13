<?php
namespace Info\Behaviors;
class authBehavior {
	protected function modules() { 
		return array(
			'Home'=>array('title'=>'平台首页','open'=>false),
			'Member'=>array('title'=>'会员管理','open'=>true),
			'Product'=>array('title'=>'产品管理','open'=>true),
			'Info'=>array('title'=>'个人信息','open'=>true),
			'System'=>array('title'=>'系统管理','open'=>true));
	}
	protected function controllers($module) { 
		$controllers = array(
				'Home'=>array('Index'=>array('title'=>'平台首页','icon'=>'bars')),
				'Member'=>array(
					'Index'=>array('title'=>'会员列表','icon'=>'bars')),
				'Product'=>array(
					'Index'=>array('title'=>'产品列表','icon'=>'bars'),
					'Category'=>array('title'=>'分类管理','icon'=>'paper-clip'),
					'Number'=>array('title'=>'页码管理','icon'=>'tags-o')),
				'Info'=>array(
					'Index'=>array('title'=>'基本信息','icon'=>'user'),
					'Login'=>array('title'=>'登陆信息','icon'=>'save'),
					'Password'=>array('title'=>'密码修改','icon'=>'solution')),
				'System'=>array(
					'Index'=>array('title'=>'系统配置','icon'=>'user'),
					'Admin'=>array('title'=>'管理员管理','icon'=>'team'),
					'Staff'=>array('title'=>'员工管理','icon'=>'solution'),
					'Email'=>array('title'=>'邮件记录','icon'=>'mail')),);
		return $controllers[$module];
	}
	protected function admin() {//总管理员
		return array(
			'Home'=>array('Index'),
			'Member'=>array('Index'),
			'Info'=>array('Index','Login','Password'),
			'System'=>array('Index','Admin','Email'));
	}
	protected function member() {//普通管理员
		return array(
			'Home'=>array('Index'),
			'Product'=>array('Index','Category','Number'),
			'Info'=>array('Index','Login','Password'),
			'System'=>array('Index','Staff','Email'));
	}
    public function run(&$params) {//权限判断
    	if (in_array(MODULE_NAME,C('AUTH_ALLOW_MODULE')))
    		return;
    	$type = array('admin','member');
    	$userType = D('Info/index')->getType();
    	$action = $type[$userType];
    	$allowController = $this->$action();
    	if (isset($allowController[MODULE_NAME]) && in_array(CONTROLLER_NAME,$allowController[MODULE_NAME])) {
				$moduleList = array();
				$controllerList = array();
				$modules = $this->modules();
				foreach ($allowController as $module => $controllers) {
					if ($module === MODULE_NAME && $modules[$module]['open'] === true) {
						$info = $this->controllers($module);
						foreach ($controllers as $controller) {
							$controllerList[] = array(
								'title'=>$info[$controller]['title'],
								'key'=>$controller,
								'icon'=>$info[$controller]['icon'],
								'url'=>'/'.$module.'/'.$controller.'/index');
						}
						unset($info);
						C('CONTROLLER_LIST',$controllerList);
						unset($controllerList);
					}
					$moduleList[] = array(
						'title'=>$modules[$module]['title'], 
						'key'=>$module, 
						'url'=>'/'.$module.'/'.$controllers[0].'/index');
				}
				unset($modules);
				unset($allowController);
				C('MODULE_LIST',$moduleList);
				unset($moduleList);
    	} else {//非法访问
    		redirect('/Login');
    	}
    }
}