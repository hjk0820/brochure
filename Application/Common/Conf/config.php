<?php
return array(
	//URL模式
	'URL_MODEL' => 2,
	//'配置项'=>'配置值'
	'TMPL_L_DELIM'    =>    '<{',
	'TMPL_R_DELIM'    =>    '}>',
	/* sqlite配置 */
	'DB_TYPE' => 'mysql',
	'DB_HOST'   => '', // 服务器地址
	'DB_NAME'   => '', // 数据库名
	'DB_USER'   => '', // 用户名
	'DB_PWD'    => '', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'hjk_', // 数据库表前缀
	'DB_CHARSET' => 'utf8', // 数据库编码默认采用utf8
	'DEFAULT_MODULE' => 'Home',  // 默认模块
	// 默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_CASE_INSENSITIVE' => true,
	//把所有异常和错误统一指向404页面
	//'ERROR_PAGE' => '/Public/404.html',
	//'TMPL_EXCEPTION_FILE'  => './Public/404.html',
	//===========自定义信息=============
	'SITE_TITLE' => '宣传册管理系统',
	'SITE_COPY' => '©2016 杭州联舟广告有限公司',
	//权限放行模块
	'AUTH_ALLOW_MODULE' => array('Login','Upload'),
	//人员类型
	'USER_TYPE' => array(
		'管理员',
		'企业会员',
	),
	//上传大小限制
	'UPLOAD_LIMIT_SIZE' => 3 , //3M = 3*1024*1024

);