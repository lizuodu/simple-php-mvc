<?php

/**
 * Class Post
 * @author lizuodu
 */
class Post extends Model {
	private $_link;
	
	/**
	 * 数据表 'tbl_post'列:
	 * 
	 * @var int $id 文章编号
	 * @var string $title 文章标题
	 * @var string $content 文章内容
	 * @var string $tags 文章所属标签
	 * @var string $status 文章状态[1|2|3]
	 * @var string $create_time 创建时间
	 * @var string $author_id 文章作者
	 * @var string $update_time 修改时间
	 * @var int frequency 频数，可修改文章优先级
	 */
	public $id;
	public $title;
	public $content;
	public $tags;
	public $status;
	public $create_time;
	public $author_id;
	public $update_time;
	public $postCount;
	public function __construct($link = null) {
		$this->_link = $link;
	}
	
	/**
	 * 根据id获取对应的文章
	 * 
	 * @param string $pid        	
	 * @return object 文章信息实体
	 */
	public function getPostById($pid) {
		$post = array ();
		try {
			$sql = '';
			if (! session_id ())
				session_start ();
			if (isset ( $_SESSION ['admin'] )) {
				$sql = "SELECT id,title,content,tags,status,create_time,author_id
	    		    FROM tbl_post WHERE id=:pid LIMIT 0, 1";
			} else {
				// 普通用户只能查询发布和归档的文章
				$sql = "SELECT id,title,content,tags,status,create_time,author_id
	    		    FROM tbl_post WHERE status IN (1,3) AND id=:pid LIMIT 0, 1";
			}
			$stmt = $this->_link->prepare ( $sql );
			$stmt->bindValue ( ':pid', ( string ) $pid, PDO::PARAM_STR );
			$stmt->execute ();
			$post = $stmt->fetchAll ( PDO::FETCH_CLASS, 'post' );
		} catch ( PDOException $ex ) {
			$this->zprint ( '根据文章Id获取文章失败：', $ex->getMessage () );
		}
		return $post;
	}
	
	protected function IsLogin() {
		if (! session_id ()) {
			session_start();
		}
		if (isset ( $_SESSION ['admin' ] )) {
			return true;
		}
		return false;
	}
	
	/**
	 * 获取文章信息
	 * 
	 * @param string $postStatus
	 *        	文章状态
	 * @param int $start
	 *        	开始记录位置
	 * @param int $limit
	 *        	文章条数
	 * @return array 文章信息结果集
	 */
	public function getAllPostByStatus($postStatus, $start, $limit, $category, $title) {
		$post = NULL;
		$sql = '';
		$count_sql = 'SELECT COUNT(id) FROM tbl_post WHERE {where};';
		$sql = 'SELECT p.id, p.title, p.content, p.status, p.create_time, p.update_time, p.author_id, p.tags, count(c.id) as comment_count
    		    FROM tbl_post p LEFT JOIN tbl_comment c ON p.id=c.post_id WHERE {where} 
	    		GROUP BY p.id,p.title, p.content, p.status, p.create_time, p.update_time, p.author_id, p.tags 
	    		ORDER BY p.update_time DESC ';
		$status = explode ( ',', $postStatus );
		$where = '';
		$count_where = '';
		$paramCount = count ( $status );
		for($i = 0; $i < $paramCount; $i ++) {
			if (empty ( $where )) {
				$where = " p.status LIKE :{$i}";
				$count_where = " status LIKE :{$i}";
			} else {
				$where .= " OR p.status LIKE :{$i}";
				$count_where .= " OR status LIKE :{$i}";
			}
		}
		$tmp_where = '';
		if ( $title != '' && $category == '' ) {
			$tmp_where = '(' . $where . ')' . ' AND p.title LIKE :title ';
		}
		else if ( $title == '' && $category != '' ) {
			$tmp_where = '(' . $where . ')' . ' AND p.tags LIKE :category ';
		}
		else {
			$tmp_where = '(' . $where . ')' . ' AND (p.title LIKE :title OR p.tags LIKE :category) ';
		}
		$sql = str_replace ( '{where}', '(' . $tmp_where . ')' . ' AND (p.title LIKE :title OR p.tags LIKE :category) ', $sql );
		$count_sql = str_replace ( '{where}', $count_where, $count_sql );
		// 获取文章
		if ($limit != 0)
			$sql .= " LIMIT {$start},{$limit}";
		try {
			$stmt = $this->_link->prepare ( $sql );
			$count_stmt = $this->_link->prepare ( $count_sql );
			for($i = 0; $i < $paramCount; $i ++) {
				$stmt->bindValue ( ":{$i}", ( string ) '%' . $status [$i] . '%', PDO::PARAM_STR );
				$count_stmt->bindValue ( ":{$i}", ( string ) '%' . $status [$i] . '%', PDO::PARAM_STR );
			}
			$stmt->bindValue ( ':title', ( string ) '%' . $title . '%', PDO::PARAM_STR );
			$stmt->bindValue ( ':category', ( string ) '%' . $category . '%', PDO::PARAM_STR );
			$stmt->execute ();
			$post = $stmt->fetchAll ( PDO::FETCH_CLASS, 'post' );
			
			unset ( $stmt );
			
			// 获取文章总数
			$count_stmt->execute ();
			$this->postCount = $count_stmt->fetch () [0];
		} catch ( PDOException $ex ) {
			$this->zprint ( '根据文章状态获取所有文章失败：', $ex->getMessage () );
		}
		return $post;
	}
	
	/**
	 * 保存文章(新增、修改保存)
	 * 
	 * @param mixed $post
	 *        	文章模型实体
	 * @return string
	 */
	public function addOrEditPost($post) {
		// 文章id==0表示新增文章，否则为编辑文章
		$id = $post->id == '' ? 'add' : $post->id;
		$sql = '';
		if (! $this->saveTags ( $post->tags ))
			return '保存分类标签失败';
		
		if ($id != 'add') {
			$sql = 'UPDATE tbl_post SET title=:title,content=:content,
		 			tags=:tags,author_id=:author_id,status=:status,
		 			create_time=:create_time,update_time=:update_time
		 			WHERE id=:pid';
			try {
				$stmt = $this->_link->prepare ( $sql );
				$stmt->bindValue ( ':title', ( string ) $post->title, PDO::PARAM_STR );
				$stmt->bindValue ( ':content', ( string ) $post->content, PDO::PARAM_STR );
				$stmt->bindValue ( ':tags', ( string ) $post->tags, PDO::PARAM_STR );
				$stmt->bindValue ( ':status', ( string ) $post->status, PDO::PARAM_STR );
				$stmt->bindValue ( ':author_id', ( string ) $post->author_id, PDO::PARAM_STR );
				$stmt->bindValue ( ':pid', ( int ) $id, PDO::PARAM_INT );
				$stmt->bindValue ( ':create_time', ( string ) $post->create_time, PDO::PARAM_STR );
				$stmt->bindValue ( ':update_time', date ( 'Y-m-d H:i:s' ), PDO::PARAM_STR );
				$stmt->execute ();
			} catch ( PDOException $ex ) {
				return '更新失败：' . $ex->getMessage ();
			}
			// return $stmt->rowCount();
			return 'success';
		} else {
			$sql = 'INSERT INTO tbl_post(title,content,tags,status,create_time,update_time,author_id)
		            VALUES(:title,:content,:tags,:status,:create_time,:update_time,:author_id);';
			try {
				$stmt = $this->_link->prepare ( $sql );
				$stmt->bindValue ( ':title', ( string ) $post->title, PDO::PARAM_STR );
				$stmt->bindValue ( ':content', ( string ) $post->content, PDO::PARAM_STR );
				$stmt->bindValue ( ':tags', ( string ) $post->tags, PDO::PARAM_STR );
				$stmt->bindValue ( ':status', ( string ) $post->status, PDO::PARAM_STR );
				$stmt->bindValue ( ':create_time', ( string ) $post->create_time, PDO::PARAM_STR );
				$stmt->bindValue ( ':author_id', ( string ) $post->author_id, PDO::PARAM_STR );
				$stmt->bindValue ( ':update_time', ( string ) $post->create_time, PDO::PARAM_STR );
				$stmt->execute ();
			} catch ( PDOException $ex ) {
				return '新增失败：' . $ex->getMessage ();
			}
			return 'success';
		}
	}
	
	/**
	 * 保存博文分类标签
	 * 
	 * @param string $tag        	
	 * @return bool
	 */
	public function saveTags($tag) {
		if ($tag == null)
			return;
		$tags = explode ( ',', $tag );
		$len = count ( $tags );
		if ($len <= 0)
			return false;
		for($i = 0; $i < $len; $i ++) {
			$sql = "SELECT 1 FROM tbl_tag WHERE name=:name";
			$stmt = $this->_link->prepare ( $sql );
			$stmt->bindValue ( ':name', $tags [$i], PDO::PARAM_STR );
			$stmt->execute ();
			$count = $stmt->fetchColumn ();
		//	var_dump($count);exit;	
			if ($count <= 0) {
				$sql = "INSERT INTO tbl_tag(name) VALUES(:name);";
				$stmt = $this->_link->prepare ( $sql );
				$stmt->bindValue ( ':name', $tags [$i], PDO::PARAM_STR );
				$stmt->execute ();
			}
		}
		return true;
	}
	
	/**
	 * 获取归档的Tag和对应的文章
	 */
	public function getTagAndPostCount() {
		// 这里不能直接用 COUNT() 函数，结合 GROUP BY,因为 tags 字段里面可能有
		// 多个用逗号分隔的 tag。我槽，不知道自己当时为什么这样设计
		$sql = 'SELECT DISTINCT id,tags FROM tbl_post WHERE status IN (1,3);';
		$split_tags = array ();
		$tags = $this->_link->query ( $sql )->fetchAll ();
		foreach ( $tags as $row ) {
			$row_arr = explode ( ',', $row ['tags'] );
			foreach ( $row_arr as $col ) {
				$col = trim ( $col, ' ' );
				if (array_key_exists ( $col, $split_tags )) {
					// 存在对应的tag，将其数量+1
					$split_tags [$col] = intval ( $split_tags [$col] ) + 1;
				} else {
					// 不存在，则新增
					$new_tag = array ( $col => 1 );
					$split_tags = array_merge ( $split_tags, $new_tag );
				}
			}
		}
		return $this->descByFrequency($split_tags);
	}
	
	// 根据 tbl_tag 排序
	protected function descByFrequency($split_tags) {
		$tags = $this->_link->query( 'SELECT name, frequency FROM tbl_tag ORDER BY frequency DESC;' )->fetchAll ();
		$result_arr = array();
		foreach ($tags as $t) {
			foreach ($split_tags as $key=>$value) {
				if ($t['name'] == $key) {
					$result_arr[$key] = $value;
				}
			}	
		}
		return $result_arr;
	}
	
	/**
	 * 获取归档日期及归档文章数，status=3
	 */
	public function getArchivePostMonth() {
		$sql = "SELECT strftime('%Y年%m月',create_time,'localtime') AS archive_date,COUNT(id) AS post_count 
				FROM tbl_post 
				WHERE status=3
				GROUP BY strftime('%Y年%m月',create_time,'localtime') ORDER BY create_time DESC;";
		return $this->_link->query ( $sql )->fetchAll ();
	}
	
	/**
	 * 根据tag标签获取文章信息
	 * 
	 * @param string $tag      	
	 * @return array
	 */
	public function getPostInfoByTagId($tag) {
		// frequency 频数越大，显示越考前
		$sql = "SELECT id,title,create_time,tags FROM tbl_post 
				WHERE status IN (1,3) AND tags LIKE :tag ORDER BY id DESC;";
		$stmt = $this->_link->prepare ( $sql );
		$stmt->bindValue ( ":tag", '%' . $tag . '%', PDO::PARAM_STR );
		$stmt->execute ();
		return $stmt->fetchAll ( PDO::FETCH_CLASS, 'Post' );
	}
	
	/**
	 * 搜索文章信息
	 * @param string $keywords 标题、分类
	 * @return array 文章元信息
	 */
	public function getPostMetaInfo($keywords) {
		$status = '1,3';
		// if ( $this->IsLogin ()) $status = '1,2,3';
		$words = explode(" ", $keywords);
		$sql = "SELECT id,title,create_time,tags FROM tbl_post
				WHERE status IN (".$status.") AND (tags LIKE :tag OR title LIKE :title)
				ORDER BY id DESC;";
		$stmt = $this->_link->prepare( $sql );
		$key_len = count($words);
		for ($i = 0; $i < $key_len; $i++) {
			$stmt->bindValue( ":tag", '%' . $words[$i] . '%', PDO::PARAM_STR );
			$stmt->bindValue( ":title", '%' . $words[$i] . '%', PDO::PARAM_STR );
		}
		$stmt->execute();
		return $stmt->fetchAll ( PDO::FETCH_CLASS, 'Post' );
	}
	
	/**
	 * 根据归档日期获取博文信息
	 * 
	 * @param string $archDate
	 *        	归档日期
	 * @return array
	 */
	public function getPostInfoByArchive($archDate) {
		$sql = "SELECT id,title,create_time,tags FROM tbl_post
				WHERE create_time LIKE :date
				AND status IN(2,3) ORDER BY id DESC;";
		$stmt = $this->_link->prepare ( $sql );
		$stmt->bindValue ( ':date', ( string ) '%' . $archDate . '%', PDO::PARAM_STR );
		$stmt->execute ();
		return $post = $stmt->fetchAll ( PDO::FETCH_CLASS, 'Post' );
	}
	
	/**
	 * 根据文章id删除文章，删除文章对应评论
	 * 
	 * @param string $id
	 *        	文章id，多个id用逗号分隔
	 * @param
	 *        	int
	 */
	public function deletePostById($id) {
		$ids = explode ( ',', $id );
		$sql = 'DELETE FROM tbl_comment WHERE post_id IN {condition};';
		$condition = '(';
		foreach ( $ids as $id ) {
			$condition .= intval ( $id ) . ',';
		}
		$condition = trim ( $condition, ',' ) . ')';
		$sql = str_replace ( '{condition}', $condition, $sql );
		// ///////////////////////////////////////////////////////////////////////
		$this->_link->beginTransaction ();
		try {
			if (! $this->_link->exec ( $sql )) {
				//throw new Exception ( '删除评论失败！' );
			}
			$sql = 'DELETE FROM tbl_post WHERE id IN {condition};';
			$sql = str_replace ( '{condition}', $condition, $sql );
			if (! $this->_link->exec ( $sql )) {
				throw new Exception ( '删除文章失败！' );
			}
			$this->_link->commit ();
		} catch ( PDOException $ex ) {
			$this->_link->rollback ();
			return -1;
		}
		return 1;
	}
	
}




