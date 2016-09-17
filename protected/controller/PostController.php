<?php
require_once Application::App ()->basePath . '/utils/Request.php';
/**
 * 文章CRUD控制器
 * 
 * @author lizuodu
 */
class PostController extends Controller {
	/**
	 * 获取所有的文章、归档、标签
	 */
	public function actionIndex() {
		$post = $this->loadModel ( 'Post' );
		$status = '1';
		// if ( $this->IsLogin ()) $status = '1,2';
		$content = $post->getAllPostByStatus ( $status, 0, 0, '', '' );
		$archive = $post->getArchivePostMonth ();
		$tag = $post->getTagAndPostCount ();
		$this->render ( 'post', 'index', array (
				'content' => $content,
				'archive' => $archive,
				'tag' => $tag 
		) );
	}
	/**
	 * 根据标题、分类查找列表
	 */
	public function actionSearch() {
		$keywords = ! isset ( $_POST ['q'] ) ? '' : Request::Filter ( $_POST ['q'] );
		$content = null;
		$post = $this->loadModel ( 'Post' );
		$list = array();
		if (! empty ( $keywords )) {
			$list = $post->getPostMetaInfo ( $keywords );
		}
		$archive = $post->getArchivePostMonth ();
		$tag = $post->getTagAndPostCount ();
		$this->render ( 'post', 'search', array (
				'list' => $list,
				'archive' => $archive,
				'tag' => $tag,
				'keywords' => $keywords
		) );
	}
	
	/**
	 * 获取文章列表
	 */
	public function actionList($content) {
		// $page = $_GET['page']; // 当前页
		$start = ! isset ( $_REQUEST ['start'] ) ? 0 : $_REQUEST ['start'];
		$limit = ! isset ( $_REQUEST ['limit'] ) ? 10 : $_REQUEST ['limit'];
		$category = ! isset ( $_REQUEST ['category'] ) ? '' : $_REQUEST ['category'];
		$status = ! isset ( $_REQUEST ['status'] ) ? '1,2,3' : $_REQUEST ['status'];
		$title = ! isset ( $_REQUEST ['title'] ) ? '' : $_REQUEST ['title'];
		// ///////////////////////////////////////////////////////////////////////////
		$post = $this->loadModel ( 'Post' );
		$data = null;
		// 所有的文章列表
		$data = $post->getAllPostByStatus ( $status, $start, $limit, $category, $title );
		$listCount = $post->postCount;
		$data = json_encode ( $data );
		// total对应状态的总记录数，list对应状态当前页记录
		$this->zprint ( "{'total': {$listCount}, 'list': {$data}}", '' );
	}
	
	/**
	 * 保存文章
	 */
	public function actionSave() {
		$this->savePost ();
	}
	protected function savePost() {
		// if (!$this->IsLogin()) $this->zprint('未登录操作','');
		$post = $this->loadModel ( 'Post' );
		$post->id = ! isset ( $_POST ['id'] ) ? '' : $_POST ['id'];
		$post->title = ! isset ( $_POST ['post-title'] ) ? '' : $_POST ['post-title'];
		$post->content = ! isset ( $_POST ['post-content'] ) ? '' : $_POST ['post-content'];
		$post->tags = ! isset ( $_POST ['post-tags'] ) ? '' : $_POST ['post-tags'];
		$post->status = ! isset ( $_POST ['post-status'] ) ? '' : $_POST ['post-status'];
		$post->create_time = ! isset ( $_POST ['create_time'] ) ? 0 : $_POST ['create_time'];
		$post->author_id = ! isset ( $_POST ['post-author'] ) ? '' : $_POST ['post-author'];
		$post->update_time = ! isset ( $_POST ['update_time'] ) ? 0 : $_POST ['update_time'];
		// ///////////////////////////////////////////////////////////////////////////////
		$result = $post->addOrEditPost ( $post );
		echo $result;
	}
	
	/**
	 * 删除文章
	 */
	public function actionDelete() {
		if (! $this->IsLogin ())
			$this->zprint ( '未登录操作', '' );
		$ids = ! isset ( $_POST ['ids'] ) ? 0 : trim ( $_POST ['ids'], ',' );
		if ($ids == 0)
			$this->zprint ( '', '' );
		$model = $this->loadModel ( 'Post' );
		if ($model->deletePostById ( $ids ) > 0)
			$this->zprint ( 'success', '' );
		else
			$this->zprint ( 'failure', '' );
	}
	
	/*
	 * 根据文章id获取文章
	 */
	public function actionShow($pid) {
		$content = null;
		$archive = null;
		$tag = null;
		$post = $this->loadModel ( 'Post' );
		$content = $post->getPostById ( $pid ); // 获取文章信息
		if ($content == null) {
			$this->zprint("<span style='color:red;font-weight:bold;'>没有ID为{$pid}的文章:-)</span>");
		}
		// $archive = $post->getArchivePostMonth ();  // 根据月份获取归档文章
		$tag = $post->getTagAndPostCount ();		// 根据标签获取归档文章
		// /////////////////////////////////////////////
		$com = $this->loadModel ( 'Comment' );
		$comment = $com->getCommentByPostId ( $pid );
		$this->render ( 'post', 'show', array (
				'content' => $content,
				'comment' => $comment,
				'archive' => $archive,
				'tag' => $tag 
		) );
	}
	
	/**
	 * 根据id获取对应Tag的文章列表
	 * 
	 * @param string $name        	
	 */
	public function actionTag($name) {
		$name = ! isset ( $name ) ? '' : Request::Filter ( $name );
		$post = $this->loadModel ( 'Post' );
		$archive = $post->getArchivePostMonth ();
		$tag = $post->getTagAndPostCount ();
		$list = $post->getPostInfoByTagId ( $name );
		$this->render ( 'post', 'tag', array (
				'archive' => $archive,
				'tag' => $tag,
				'list' => $list,
				'tagname' => $name 
		) );
	}
	
	/**
	 * 根据id获取对应归档日期的文章列表
	 * 
	 * @param string $name        	
	 */
	public function actionArchive($name) {
		$name = ! isset ( $name ) ? '' : Request::Filter ( $name );
		$post = $this->loadModel ( 'Post' );
		$archive = $post->getArchivePostMonth ();
		$tag = $post->getTagAndPostCount ();
		$list = $post->getPostInfoByArchive ( $name );
		$this->render ( 'post', 'tag', array (
				'archive' => $archive,
				'tag' => $tag,
				'list' => $list,
				'tagname' => $name 
		) );
	}
	
	/**
	 * 验证码检测
	 */
	public function actionCheckmark() {
		if (! session_id ()) {
			session_start ();
		}
		
		$s_mark = ! isset ( $_SESSION ['mark'] ) ? '' : $_SESSION ['mark'];
		$r_mark = ! isset ( $_POST ['mark'] ) ? '' : $_POST ['mark'];
		
		if ($s_mark != $r_mark) {
			exit('验证码错误:-)');
		}
		else {
			exit('OK');
		}
	}
	
	/**
	 * 保存文章评论
	 */
	public function actionComment() {

		$comment = $this->loadModel ( 'Comment' );		
		$comment->id = ! isset ( $_POST ['id'] ) ? 0 : Request::Filter ( $_POST ['id'] );
		$comment->post_id = ! isset ( $_POST ['pid'] ) ? 0 : Request::Filter ( $_POST ['pid'] );
		$comment->status = ! isset ( $_POST ['status'] ) ? 2 : Request::Filter ( $_POST ['status'] );
		if ($comment->post_id == 0) {
			$this->zprint ( '文章id不能为空白:-)' );
		}
		$comment->author = ! isset ( $_POST ['name'] ) ? ' ' : Request::Filter ( $_POST ['name'] );
		if (empty ( $comment->author )) {
			$this->zprint ( '呢称不能为空白:-)' );
		}
		$comment->email = ! isset ( $_POST ['email'] ) ? '' : Request::Filter ( $_POST ['email'] );
		$comment->url = ! isset ( $_POST ['website'] ) ? '' : Request::Filter ( $_POST ['website'] );
		$content = ! isset ( $_POST ['content'] ) ? '' : Request::Filter ( $_POST ['content'] );
		// 管理员回复，背景黄色
		if ( $this->isLogin() ) {
			$comment->content = '<div class="yellow">' . $content .  '</div>';
			$comment->author = 'lizuodu';
		} else {
			$comment->content = $content;
		}
		$comment->create_time = ! isset ( $_POST ['create_time'] ) ? '' : Request::Filter ( $_POST ['create_time'] );
		if (empty ( $comment->content )) {
			$this->zprint ( '评论内容不能为空白:-)' );
		}
		
		// 保存
		$isSave = $comment->saveComment ( $comment );
		
		// 发邮件统治评论者
		if ($isSave) {
			require_once 'Common.php';
			$mail = new Common ();
			// 签名：$mail->sendMail($name, $email, $subject, $body)
			$mail->sendMail ( $comment->author, $comment->email, $comment->post_id, $comment->content );
			$this->zprint ( '保存成功，请等待审核:-)' );
		} else {
			$this->zprint ( '保存失败，请尝试从新保存:-)' );
		}
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



