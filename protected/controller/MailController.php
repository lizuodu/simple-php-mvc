<?php
require_once Application::App ()->basePath . '/utils/Request.php';
require_once 'Common.php';
class MailController extends Controller {
	public function actionIndex() {
		$name = isset ( $_POST ['name'] ) ? Request::filter ( $_POST ['name'] ) : '';
		$email = isset ( $_POST ['email'] ) ? Request::filter ( $_POST ['email'] ) : '';
		$subject = isset ( $_POST ['subject'] ) ? Request::filter ( $_POST ['subject'] ) : '';
		$body = isset ( $_POST ['content'] ) ? Request::filter ( $_POST ['content'] ) : '';
		// ////////////////////////////////////////////////////////////////////////
		$mail = new Common ();
		$mail = $this->sendMail ( $name, $email, $subject, $body );
	}
}





