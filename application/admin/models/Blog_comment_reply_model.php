<?php
/**
 *
 *
 * User: avery
 * Date: 2019-09-11
 * Time: 17:58
 */

class Blog_comment_reply_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_BLOG_COMMENT_REPLY;
	}

	/**
	 * 获取回复评论数量
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_comment_reply_count($comment_id)
	{
		return $this->count(array('comment_id' => $comment_id));

	}
}
