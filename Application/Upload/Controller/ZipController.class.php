<?php
namespace Upload\Controller;
use Think\Controller;
class ZipController extends Controller {
	public function Index() {
		if (IS_AJAX) {
			$upload = new \Think\Upload();
			$upload->maxSize = C('UPLOAD_LIMIT_SIZE')*1024*1024; //3M = 3*1024*1024
			$upload->exts = array('zip','rar');
			$upload->savePath = '/zip/';
			$info = $upload->upload();
			if (!$info) {
				$data = array('status'=>'error', 'msg'=>$upload->getError());
			} else {
				$data = array('status'=>'success', 'url'=> '/Uploads'.$info['file']['savepath'].$info['file']['savename']);
			}
			echo json_encode($data);
			exit;
		}
	}
}