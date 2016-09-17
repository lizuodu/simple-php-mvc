<?php
require_once Application::App ()->basePath . '/utils/Request.php';
class HomeController extends Controller {
	public function actionIndex() {
	}
	
	/**
	 * 生成验证码
	 */
	public function actionMark($num) {
		require_once Application::App ()->basePath . '/utils/mark/Mark.php';
		Mark::genMark ();
	}
	
	/**
	 * 简历
	 */
	public function actionMe() {
		$this->render('home','me');
	}
	
	/**
	 * 登录验证
	 */
	public function actionLogin() {
		if (! session_id ())
			session_start ();
			// 验证码
		$s_mark = ! isset ( $_SESSION ['mark'] ) ? '' : $_SESSION ['mark'];
		$r_mark = ! isset ( $_POST ['mark'] ) ? '' : $_POST ['mark'];
		
		if ($s_mark != $r_mark) {
			exit('mark-failure');
		}
		
		$username = ! isset ( $_POST ['username'] ) ? '' : Request::Filter ( $_POST ['username'] );
		$password = ! isset ( $_POST ['password'] ) ? '' : Request::Filter ( $_POST ['password'] );
		
		$model = $this->loadModel ( 'User' );
		
		$userInfo = $model->getUserInfoByName ( $username, $password );
		if ($userInfo) {
			echo 'login-success';
			$_SESSION ['admin'] = 'yes';
		} else {
			echo 'login-failure';
		}
	}
	
	/**
	 * 文件上传
	 *
	 * @param
	 *        	string 日期 yyyy/mm/dd
	 */
	public function actionUpload() {
		$date = ! isset ( $_POST ['date'] ) ? '' : $_POST ['date'];
		// if (!$this->IsLogin()) $this->zprint('未登录操作','');
		require_once Application::App ()->basePath . '/utils/Upload.php';
		try {
			$upload = new Upload ( $date );
		} catch ( Exception $ex ) {
			$this->zprint ( 'error', $ex->getMessage () );
		}
	}
	
	/**
	 * 退出系统
	 */
	public function actionLogout() {
		if (! session_id ()) {
			session_start ();
		}

		// 重置会话中的所有变量
		$_SESSION = array ();
		session_destroy ();
		
		header('Location: http://lizuodu.com');
	}
	
	/**
	 * 检查用户登录
	 *
	 * @return bool
	 */
	public function IsLogin() {
		if (! session_id ())
			session_start ();
		$admin = ! isset ( $_SESSION ['admin'] ) ? '' : $_SESSION ['admin'];
		if (empty ( $admin )) {
			return false;
		} else {
			return true;
		}
	}

}



