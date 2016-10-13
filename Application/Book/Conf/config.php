<?php
return array(
    //把所有异常和错误统一指向404页面
    //'ERROR_PAGE' => '/Public/error_404.html',
    'TMPL_EXCEPTION_FILE'  => './Public/error_404.html',
    'SITE_FOOTER' => 'Copyright ©2016&nbsp;&nbsp;&nbsp;Hangzhou, the boat Advertising Co., Ltd.',
	'TMPL_PARSE_STRING' => array(
		'__PUBLIC__' => '/Public/web',
		'__AMAZEUI__' => '/Public/web/plugins/amazeui',
	),
);