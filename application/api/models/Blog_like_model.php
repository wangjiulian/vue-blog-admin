<?php
/**
 *
 *
 * User: avery
 * Date: 2019-07-22
 * Time: 16:39
 */

class Blog_like_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_BLOG_LIKE;
	}

	/**
	 * 修改点赞
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	private function change_blog_like($uid, $blog_id, $status)
	{

		$res = $this->select_one('id', array('user_id' => $uid, 'blog_id' => $blog_id));
		if ($res) {
			//更新数据
			$up_data = array(
				'status' => $status,
				'update_time' => time()
			);
			return $this->update($up_data, array('user_id' => $uid, 'blog_id' => $blog_id));
		}
		//插入数据
		$insert_data = array(
			'blog_id' => $blog_id,
			'user_id' => $uid,
			'status' => $status,
			'update_time' => time()
		);
		return $this->insert($insert_data);
	}

	/**
	 * 点赞
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function like_blog($uid, $blog_id)
	{

		return $this->change_blog_like($uid, $blog_id, '1');
	}

	/**
	 * 取消点赞
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function unlike_blog($uid, $blog_id)
	{
		return $this->change_blog_like($uid, $blog_id, '2');
	}

	/**
	 * 获取点赞数量
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_like_count($ids)
	{
		$res = $this->db->select('blog_id,count(`id`) as num ')
			->from($this->table)
			->where_in('blog_id', $ids)
			->group_by('blog_id')
			->get()->result_array();
		$arr = array();
		foreach ($res as $lv) {
			$arr[$lv['blog_id']] = $lv['num'];
		}
		return $arr;
	}

	/**
	 * 获取点赞状态
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_like_status($uid, $blog_id)
	{
		$res = $this->select_one('status', array('user_id' => $uid, 'blog_id' => $blog_id));
		if ($res) {
			return $res['status'];
		}
		return '2';
	}

	/**
	 * 获取点赞用户列表
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_like_user_list($uid, $offset, $perpage)
	{
		$res = $this->db->select('b.id as blog_id,a.update_time as like_time, b.title as blog_title, c.id as uid,c.nick_name,c.avatar')
			->from($this->table . ' a ')
			->join(TABLE_BLOG . ' b ', ' on a.blog_id = b.id ', 'left')
			->join(TABLE_USER . ' c ', ' on a.user_id = c.id ', 'left')
			->limit($perpage, $offset)
			->order_by('a.update_time desc')
			->where(array('b.user_id' => $uid, 'a.status' => 1))
			->get()->result_array();
		return $res;
	}
}

