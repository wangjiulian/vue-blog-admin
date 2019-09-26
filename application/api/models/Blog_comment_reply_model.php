<?php
/**
 *
 *
 * User: avery
 * Date: 2019-08-30
 * Time: 23:19
 */

class Blog_comment_reply_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_BLOG_COMMENT_REPLY;
	}

	/**
	 * 添加评论回复
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function add_comment_reply($comment_id, $uid, $content)
	{
		$insert_data = array(
			'comment_id' => $comment_id,
			'user_id' => $uid,
			'content' => $content,
			'comment_time' => time()
		);
		return $this->insert($insert_data);
	}

	/**
	 * 获取评论回复列表
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_comment_reply_list($comment_id)
	{
		return $this->db->select('a.id,a.user_id as uid, a.content,a.comment_time, b.nick_name,b.avatar,d.nick_name as feed_nick_name,d.avatar as feed_avatar,d.id as feed_uid')
			->from($this->table . ' a ')
			->join(TABLE_USER . ' b ', 'on a.user_id = b.id ', 'left')
			->join($this->table . ' c ', 'c.id = a.reply_id', 'left')
			->join(TABLE_USER . ' d ', ' d.id = c.user_id', 'left')
			->where(array('a.comment_id' => $comment_id))
			->get()->result_array();
	}

	/**
	 * 添加回复评论
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function add_reply_comment($comment_id, $reply_id, $uid, $content)
	{
		$insert_data = array(
			'comment_id' => $comment_id,
			'reply_id' => $reply_id,
			'user_id' => $uid,
			'content' => $content,
			'comment_time' => time()
		);
		return $this->insert($insert_data);
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
