<?php
namespace Login\Controller;
use Think\Controller;
class OutController extends Controller {
    public function index(){
    	//可增加退出记录
    	session(NULL);
    	redirect('/Login');
    }
}