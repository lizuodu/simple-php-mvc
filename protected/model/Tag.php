<?php

/**
 * Class Tag
 * @author lizuodu
 */
class Tag extends Model {
	private $_link;
	public function __construct($link) {
		$this->_link = $link;
	}
	
	/**
	 * 获取所有的分类标签
	 */
	public function getAllTags() {
		$sql = 'SELECT id, name FROM tbl_tag;';
		return $this->_link->query ( $sql )->fetchAll ();
	}
}