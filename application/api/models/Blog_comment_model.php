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

	/**
	 * 评论列表
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_comment_list($id, $offset, $perpage){
		$res = $this->db->select('a.id,a.blog_id,a.user_id as uid,a.content,a.comment_time,b.nick_name,b.avatar')
			->from($this->table . ' a ')
			->join(TABLE_USER . ' b', 'on a.user_id = b.id', 'left')
			->where(array('a.blog_id' => $id))
			->get()->result_array();
		$list = array();
		if ($res){
			foreach ($res as $lv){
				$this->load->model('blog_comment_reply_model');
				$reply_iist = $this->blog_comment_reply_model->get_comment_reply_list($lv['id']);
				$lv['reply_list'] = $reply_iist;
				$list[] = $lv;
			}
		}
		return $list;
	}

	/**
	 * 获取评论用户列表
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_comment_user_list($uid, $offset, $perpage){
		$res = $this->db->select('a.id,a.comment_time,b.id as blog_id,b.title as blog_title,c.id as uid,c.avatar,c.nick_name')
			->from($this->table . ' a ')
			->join(TABLE_BLOG . ' b ', 'on a.blog_id = b.id ', 'left')
			->join(TABLE_USER . ' c ', 'on a.user_id = c.id ', 'left')
			->limit($perpage, $offset)
			->order_by('a.comment_time desc')
			->where(array('b.user_id' => $uid))
			->get()->result_array();
		return $res;
	}
}
