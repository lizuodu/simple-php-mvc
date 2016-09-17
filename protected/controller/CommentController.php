<?php
class CommentController extends Controller {
	public function actionIndex() {
		$pid = ! isset ( $_GET ['id'] ) ? '' : $_GET ['id'];
		$status = ! isset ( $_GET ['status'] ) ? '1,2' : $_GET ['status'];
		$start = ! isset ( $_GET ['start'] ) ? 0 : $_GET ['start'];
		$limit = ! isset ( $_GET ['limit'] ) ? 0 : $_GET ['limit'];
		// echo '$pid: ' . $pid . ' $status: ' . $status . ' $start: '. $start . ' $limit: ' . $limit;
		// exit;
		// ////////////////////////////////////////////////////////////////////
		$model = $this->loadModel ( 'Comment' );
		$comment = $model->getCommentByCondition ( $pid, $status, $start, $limit );
		$listCount = $model->commentCount;
		$com = json_encode ( $comment );
		$this->zprint ( "{'total': {$listCount}, 'list': {$com}}", '' );
	}
	
	/**
	 * 修改评论状态
	 */
	public function actionModify() {
		if (! $this->isLogin ())
			$this->zprint ( '未登录操作', '' );
		$cids = ! isset ( $_POST ['ids'] ) ? 0 : trim ( $_POST ['ids'], ',' );
		$status = ! isset ( $_POST ['status'] ) ? '' : trim ( $_POST ['status'], ',' );
		$cids = explode ( ',', $cids );
		$status = explode ( ',', $status );
		if (0 == $cids)
			$this->zprint ( '评论编号不能为0', '' );
			// ////////////////////////////////////////////////////////////////////
		$model = $this->loadModel ( 'Comment' );
		$len = count ( $cids );
		for($i = 0; $i < $len; $i ++) {
			$model->id = $cids [$i];
			$model->status = $status [$i];
			if ($model->saveComment ( $model ))
				$this->zprint ( 'success', '' );
			else
				$this->zprint ( 'failure', '' );
		}
	}
	
	/**
	 * 删除评论
	 */
	public function actionDelete() {
		if (! $this->iIsLogin ())
			$this->zprint ( '未登录操作', '' );
		$ids = ! isset ( $_POST ['ids'] ) ? 0 : trim ( $_POST ['ids'], ',' );
		if ($ids == 0)
			$this->zprint ( '', '' );
		$model = $this->loadModel ( 'Comment' );
		if ($model->deleteCommentById ( $ids ) > 0)
			$this->zprint ( 'success', '' );
		else
			$this->zprint ( 'failure', '' );
	}
	
	/**
	 * 检查用户登录
	 * 
	 * @return bool
	 */
	public function isLogin() {
		session_start ();
		$admin = ! isset ( $_SESSION ['admin'] ) ? '' : $_SESSION ['admin'];
		if (empty ( $admin ))
			return false;
		else
			return true;
	}
}





