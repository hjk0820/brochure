<?php
namespace Upload\Controller;
use Think\Controller;
class PdfController extends Controller {
	public function Index() {
		if (IS_AJAX) {
			$uid = D('Info/index')->getId();
			if ( ! isset($uid)) {
				$data = array('status'=>'error', 'msg'=>'会员ID无效');
				echo json_encode($data);
				exit;
			}
			$upload = new \Think\Upload();
			$upload->maxSize = C('UPLOAD_LIMIT_SIZE')*1024*1024; //3M = 3*1024*1024
			$upload->exts = array('jpg', 'gif', 'png', 'jpeg', 'pdf', 'doc', 'docx');
			$upload->savePath = '/'.$uid.'/pdf/';
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