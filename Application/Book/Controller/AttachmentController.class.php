<?php
namespace Book\Controller;
use Think\Controller;
class AttachmentController extends Controller {
    public function index($id) {
        if (is_numeric($id)) {
            $rs = M('product')->field('title,pdf')->find($id);
            if ($rs) {
                if ( ! is_file('.'.$rs['pdf']))
                    exit('Resources do not exist!');
                $mimes = array(
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'gif' => 'image/gif',
                    'png' => 'image/png',
                    'pdf' => 'application/pdf',
                    'doc' => 'application/msword',
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                );
                $type = pathinfo($rs['pdf'], PATHINFO_EXTENSION);
                if (isset($mimes[$type]))
                    header('Content-type: '.$mimes[strtolower($type)]);
                header('Content-Disposition: attachment; filename="'.$rs['title'].'.'.$type.'"');
                readfile('.'.$rs['pdf']);
            }
        }
        exit;
    }
}