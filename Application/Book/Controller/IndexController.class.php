<?php
namespace Book\Controller;
use Think\Controller;
class IndexController extends Controller {
    public $smtp = array(
        'host' => 'smtpdm.aliyun.com',
        'port' => 25,
        'username' => 'info@mailserver.ec-world.com',
        'password' => 'hzcy123456',
    );
    public function index($uid,$nid) {
        if ( ! session('uid')) {
            $this->redirect('Book/Login/index', array('uid'=>$uid,'nid'=>$nid));
        }
        if (session('uid') != $uid)
            $this->error_param();
        $uid = session('uid');
        $info = D('Info/index')->getCompany($uid);
        $this->company = $info['company'];

        $nrs = M('number')->field('uid,title,image')->find($nid);
        if ( ! $nrs || $nrs['uid'] != $uid)
            $this->error_param();
        $this->nrs = $nrs;

        $products = M('product')->where(array('nid'=>$nid))->field('id,title,imglist')->order('sort desc,id desc')->select();
        if ($products) foreach ($products as $key => $val) {
            $products[$key]['imglist'] = explode(',', $val['imglist']);
        }
        $this->products = $products;

        $staff = D('System/staff')->getStaff($uid);
        $this->staff = $staff;

        $this->display();
    }

    public function sent() {
        if (IS_POST) {
            $post  = I('post.');
            if ( ! $post || ! isset($post['data']) || ! isset($post['email']) || ! isset($post['sid']))
                $this->ajaxReturn(array('status'=>0, 'msg'=>'Invalid Data'));
            $style = '<style>
                div,p,strong{font-family:"Microsoft YaHei",Arial;font-size:14px;color:#555;line-height:150%;}.container{width:520px;margin:0px auto;padding:20px;border: 1px solid #e3e3e3;background-color: #f5f5f5;border-radius: 4px;-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);}h1{text-transform:Uppercase;margin-top:0;font-size:18px; border-bottom:#e3e3e3 1px solid; padding-bottom:12px;}h2{font-size:16px;margin:6px 0;border-left:#e3e3e3 12px solid; padding:6px 0 6px 6px;}h3{font-size:14px;color:#CC3333}.item {border-bottom:1px solid #666;}.item strong {color:red;}
            </style>';
            //公司信息
            $uid = session('uid');
            $info = D('Info/index')->getCompany($uid);
            $company = '<h1>'.$info['company']['title'].'</h1><p>'.nl2br($info['company']['desc']).'</p>';
            //采购商基本信息
            $stock  = '<div class="container">Buyer E-mail:'.$post['email'].'</p><p>Purchase content:</p></div>';
            //采购内容
            $ids = array();
            $host = 'http://'.$_SERVER['HTTP_HOST'];
            foreach ($post['data'] as $val) {
                $ids[] = $val['id'];
            }
            $products = M('product')->where(array('id'=>array('in',$ids)))->getField('id,title,pdf');
            $content  = '';
            foreach ($post['data'] as $val) {
                //产品信息
                $content .= '<h2>'.$products[$val['id']]['title'].'</h2><p><a href="'.$host.$products[$val['id']]['pdf'].'">Download attachments</a></p>';
                //订购的内容,采购商填写
                $content .= "<div class='item'>
                    <strong>Purchasing Content</strong>
                    <p>Item No: {$val['no']}</p>
                    <p>Fob Price: {$val['price']}</p>
                    <p>Size: {$val['size']}</p>
                    <p>remark: {$val['remark']}</p>
                </div>";
            }
			
			$staff = M('staff')->where(array('id'=>$post['sid']))->Field('name,email')->find();
			
            $company_contact = '<p>'.nl2br($info['company']['contact']).'</p><h3>This is a system message,do not reply .Please contact '.$staff['name'].' E_Mail:'.$staff['email'].'</h3>';

            $this->setSmtp();

            //发送邮件给采购商
            $presult = $this->sentEmail(
                $post['email'],
                'You order a product', 
                $style.'<div class="container">'.$company.$content.$company_contact.'</div>'
            );
            //发送提醒邮件给企业
            $uresult = $this->sentEmail(
                $info['smtp']['email'],
                'Reminder: There are customers purchasing your product', 
                $style.'<div class="container">'.$stock.$content.'</div>'
            );
            //发送提醒给业务员
            $sresult =  $this->sentEmail(
                $staff['email'],
                'Reminder: There are customers purchasing your product', 
                $style.'<div class="container">'.$stock.$content.'</div>'
            );
            //记录邮件发送状态
            $data = array();
            $data['uid'] = $uid;
            $data['sid'] = $post['sid'];
            $data['uemail'] = $info['smtp']['email'];
            $data['semail'] = $staff['email'];
            $data['pemail'] = $post['email'];
            $data['ustatus'] = $uresult === TRUE ? 0 : 1;
            $data['sstatus'] = $sresult === TRUE ? 0 : 1;
            $data['pstatus'] = $presult === TRUE ? 0 : 1;
            $data['addtime'] = NOW_TIME;
            if (M('email')->add($data))
                D('System/email')->unsetEmail($uid);
            if ( ! $uresult && ! $sresult && ! $presult) {
                $this->ajaxReturn(array('status'=>0, 'msg'=>'Failed to send a message, please try again later.'));
            } else if( ! $uresult || ! $sresult || ! $presult) {
                $this->ajaxReturn(array('status'=>1, 'msg'=>'Some messages sent successfully!'));
            }
            D('System/email')->unsetEmail($uid);
            $this->ajaxReturn(array('status'=>1, 'msg'=>'Mail sent successfully!'));
        }
    }

    public function setSmtp() {
        $uid = session('uid');
        $info = D('Info/index')->getCompany($uid);
        if (isset($info['smtp']['host'][0], $info['smtp']['username'][0], $info['smtp']['password'][0])) {
            $this->smtp['host'] = $info['smtp']['host'];
            $this->smtp['port'] = $info['smtp']['port'];
            $this->smtp['username'] = $info['smtp']['username'];
            $this->smtp['password'] = $info['smtp']['password'];
        } else {
            $smtp = D('Info/index')->getSmtp();
            $this->smtp['host'] = $smtp['host'];
            $this->smtp['port'] = $smtp['port'];
            $this->smtp['username'] = $smtp['username'];
            $this->smtp['password'] = $smtp['password'];
        }
    }

    protected function sentEmail($email, $title, $content) {
        Vendor('PHPMailer.PHPMailerAutoload');
        $mail = new \PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host = $this->smtp['host']; //smtp服务器的名称
        $mail->Port = $this->smtp['port'];
        $mail->SMTPAuth = TRUE; //启用smtp认证
        $mail->Username = $this->smtp['username']; //你的邮箱名
        $mail->Password = $this->smtp['password']; //邮箱密码
        $mail->From = $this->smtp['username']; //发件人地址（也就是你的邮箱地址）
        $mail->FromName = $this->smtp['username']; //发件人姓名
        $mail->AddAddress($email, $email);
        $mail->WordWrap = 80; //设置每行字符长度
        $mail->IsHTML(TRUE); // 是否HTML格式邮件
        $mail->CharSet = 'utf-8'; //设置邮件编码
        $mail->Subject =$title; //邮件主题
        $mail->Body = $content; //邮件内容
        $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
        $result = $mail->Send();
        return $result ? TRUE : FALSE;
    }

    protected function error_param() {
        send_http_status(404);
        echo file_get_contents('./Public/error_404.html');
        exit;
    }
}