<?php
namespace Login\Behaviors;
class checkBehavior {
    public function run(&$params) {
    	if (MODULE_NAME !== 'Login') {
    		if ( ! session('user.id'))
    			redirect('/Login/Index/index');
    	} else {
    		if (CONTROLLER_NAME !== 'Out') {
    			if (session('user.id')) {//处于登陆状态
    				redirect('/');
    			}
    		}
    	}
    }
}