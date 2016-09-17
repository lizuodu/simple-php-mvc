<?php
require_once Application::App ()->basePath . '/utils/mailer/EMailer.php';
class Common {
	public function sendMail($name, $email, $subject, $body) {
		$mailBody = 'from:' . $name . '<br/>subject:' . $subject . '<br/>email:' . $email . '<br/>content:<br/>' . $body;
		// ////////////////////////////////////////////////////////////////////////
		$this->phpMailer ( $subject, $mailBody );
	}
	private function phpMailer($subject, $body) {
		$mailInfo = new PHPMailer ();
		$sendWay = 'SMTP';
		switch ($sendWay) {
			case "SMTP" :
				$mailInfo->isSMTP ();
				$mailInfo->Host = 'smtp.163.com';
				$mailInfo->SMTPAuth = true;
				$mailInfo->Username = 'lizuodu_web@163.com';
				$mailInfo->Password = 'lizuodu123456@';
				break;
			case "MAIL" :
				$mailInfo->isMail ();
				break;
			case "SENDMAIL" :
				$mailInfo->isSendmail ();
				break;
			case "QMAIL" :
				$mailInfo->isQmail ();
				break;
		}
		
		// Define From Address.
		$mailInfo->From = 'lizuodu_web@163.com';
		$mailInfo->FromName = 'http://lizuodu.com';
		$mailInfo->addAddress ( 'lizuodu@163.com' );
		// Add Subject.
		$mailInfo->Subject = "=?UTF-8?B?" . base64_encode ( stripslashes ( $subject ) ) . "?=";
		$mailInfo->IsHTML ( true );
		$mailInfo->CharSet = 'UTF-8';
		$mailInfo->Body = $body;
		$mailInfo->AltBody = "请使用HTML方式查看邮件。";
		$mailInfo->WordWrap = 50;
		return $mailInfo;
	}
}


	
	
	