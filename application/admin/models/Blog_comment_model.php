<?php
/**
 *
 *
 * User: avery
 * Date: 2019-07-22
 * Time: 16:39
 */

class Blog_comment_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_BLOG_COMMENT;
	}

	/**
	 * 添加评论
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function add_blog_comment($uid, $blog_id, $content)
	{
		$insert_data = array(
			'blog_id' => $blog_id,
			'user_id' => $uid,
			'content' => $content,
			'comment_time' => time()
		);
		return $this->insert($insert_data);
	}

	/**
	 * 获取评论数量
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_comment_count($ids)
	{

		$res = $this->db->select('blog_id,id')
			->where_in('blog_id', $ids)
//			->group_by('blog_id')
			->get($this->table)->result_array();
		$arr = array();
		$this->load->model('blog_comment_reply_model');
		foreach ($res as $lv) {
			//重复累加同条帖子频率
			//回复评论+当前评论数1
			$comment_num = $this->blog_comment_reply_model->get_comment_reply_count($lv['id']) + 1;
			$arr[$lv['blog_id']] = empty($arr[$lv['blog_id']]) ? $comment_num : (intval($arr[$lv['blog_id']]) + $comment_num) ;
		}
		return $arr;
	}

}
