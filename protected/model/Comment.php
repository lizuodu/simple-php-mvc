<?php

/**
 * Class Comment
 * @author lizuodu
 */
class Comment extends Model {
	private $_link;
	
	/**
	 * 数据表 'tbl_comment'列:
	 * 
	 * @var int $id 评论编号
	 * @var string $content 评论内容
	 * @var int $status 评论状态 默认为3
	 * @var string $create_time 创建时间
	 * @var string $author 评论者
	 * @var string $email 评论者邮件
	 * @var string $url 评论者博客
	 * @var int $post_id 评论文章编号
	 */
	public $id;
	public $content;
	public $status;
	public $create_time;
	public $author;
	public $email;
	public $url;
	public $post_id;
	public $commentCount;
	public function __construct($link = null) {
		$this->_link = $link;
	}
	
	/**
	 * 保存文章评论
	 * 
	 * @param object $comment        	
	 * @return bool
	 */
	public function saveComment($comment) {
		$cid = ! isset ( $comment->id ) ? '' : $comment->id;
		$url = $comment->url;
		if ((strpos ( $url, 'http://' ) <= 0) || (strpos ($url, 'https://') <= 0))
			$comment->url = 'http://' . $url;
		$sql = '';
		if (empty ( $cid )) {
			// 新增评论
			$sql = "INSERT INTO tbl_comment(content,status,create_time,author,email,url,post_id) 
					VALUES(:content,:status,:create_time,:author,:email,:url,:post_id);";
		} else {
			// 修改评论状态
			$sql = "UPDATE tbl_comment SET status=:status WHERE id=:id";
		}
		try {
			$stmt = $this->_link->prepare ( $sql );
			if ($cid != 0) {
				$stmt->bindValue ( ':id', ( int ) $comment->id, PDO::PARAM_INT );
				$stmt->bindValue ( ':status', ( int ) $comment->status, PDO::PARAM_INT );
			} else {
				$stmt->bindValue ( ':content', ( string ) $comment->content, PDO::PARAM_STR );
				$stmt->bindValue ( ':status', 2, PDO::PARAM_STR );
				$comment->create_time = date ( 'Y-m-d H:i:s' );
				$stmt->bindValue ( ':create_time', ( string ) $comment->create_time, PDO::PARAM_STR );
				$stmt->bindValue ( ':author', ( string ) $comment->author, PDO::PARAM_STR );
				$stmt->bindValue ( ':email', ( string ) $comment->email, PDO::PARAM_STR );
				$stmt->bindValue ( ':url', ( string ) $comment->url, PDO::PARAM_STR );
				$stmt->bindValue ( ':post_id', ( int ) $comment->post_id, PDO::PARAM_INT );
			}
			$stmt->execute ();
			if ($stmt->rowCount () <= 0)
				return false;
		} catch ( PDOException $ex ) {
			$this->zprint ( '保存评论失败：', $ex->getMessage () );
		}
		return true;
	}
	
	/**
	 * 根据文章id获取文章评论
	 * 
	 * @param string $pid        	
	 */
	public function getCommentByPostId($pid = '') {
		$sql = '';
		if (empty ( $pid )) {
			$sql = 'SELECT id,content,status,create_time,author,email,url,post_id
					FROM tbl_comment
					WHERE status=1
					ORDER BY id DESC;';
			return $this->_link->query ( $sql )->fetchAll ( PDO::FETCH_CLASS, 'comment' );
		} else {
			$sql = 'SELECT id,content,status,create_time,author,email,url,post_id
					FROM tbl_comment
					WHERE post_id=:pid AND status=1
					ORDER BY id DESC;';
			$stmt = $this->_link->prepare ( $sql );
			$stmt->bindValue ( ':pid', ( int ) $pid, PDO::PARAM_INT );
			$stmt->execute ();
			return $stmt->fetchAll ( PDO::FETCH_CLASS, 'comment' );
		}
	}
	
	/**
	 * 根据文章id，评论状态获取所有文章评论
	 * 
	 * @param int $pid        	
	 * @param string $status        	
	 * @param int $start        	
	 * @param int $limit        	
	 */
	public function getCommentByCondition($pid, $status, $start, $limit) {
		$comment = null;
		$sql = '';
		$count_sql = 'SELECT COUNT(id) FROM tbl_comment WHERE {WHERE};';
		$sql = 'SELECT id, content, status, create_time, author, email, url, post_id 
				FROM tbl_comment
				WHERE {WHERE} ORDER BY id DESC';
		$status = explode ( ',', $status );
		$paramCount = count ( $status );
		$where = '';
		$count_where = '';
		for($i = 0; $i < $paramCount; $i ++) {
			if (empty ( $where )) {
				$where = " status LIKE :{$i}";
				$count_where = " status LIKE :{$i}";
			} else {
				$where .= " OR status LIKE :{$i}";
				$count_where .= " OR status LIKE :{$i}";
			}
		}
		if (! empty ( $pid )) {
			// 获取相应文章的所有评论
			$sql = str_replace ( '{WHERE}', '(' . $where . ')' . ' AND post_id=:pid ', $sql );
		} else {
			$sql = str_replace ( '{WHERE}', $where, $sql );
		}
		$count_sql = str_replace ( '{WHERE}', $count_where, $count_sql );
		// 获取所有评论
		if ($limit != 0) {
			$sql .= " LIMIT {$start},{$limit};";
		}
		try {
			$stmt = $this->_link->prepare ( $sql );
			$count_stmt = $this->_link->prepare ( $count_sql );
			for($i = 0; $i < $paramCount; $i ++) {
				$stmt->bindValue ( ":{$i}", ( string ) '%' . $status [$i] . '%', PDO::PARAM_STR );
				$count_stmt->bindValue ( ":{$i}", ( string ) '%' . $status [$i] . '%', PDO::PARAM_STR );
			}
			if (! empty ( $pid )) {
				$stmt->bindValue ( ':pid', ( int ) $pid, PDO::PARAM_INT );
				$count_stmt->bindValue ( ':pid', ( int ) $pid, PDO::PARAM_INT );
			}
			$stmt->execute ();
			// exit($stmt->queryString);
			$comment = $stmt->fetchAll ( PDO::FETCH_CLASS, 'Comment' );
			
			unset ( $stmt );
			
			// 获取文章总数
			$count_stmt->execute ();
			$this->commentCount = $count_stmt->fetch () [0];
		} catch ( PDOException $ex ) {
			$this->zprint ( '获取评论失败：', $ex->getMessage () );
		}
		return $comment;
	}
	
	/**
	 * 根据评论id删除评论
	 * 
	 * @param string $id
	 *        	评论id，多个id用逗号分隔
	 * @param
	 *        	int
	 */
	public function deleteCommentById($id) {
		$ids = explode ( ',', $id );
		$sql = 'DELETE FROM tbl_comment WHERE id IN {condition};';
		$condition = '(';
		foreach ( $ids as $id ) {
			$condition .= intval ( $id ) . ',';
		}
		$condition = trim ( $condition, ',' ) . ')';
		$sql = str_replace ( '{condition}', $condition, $sql );
		return $this->_link->exec ( $sql );
	}
}

