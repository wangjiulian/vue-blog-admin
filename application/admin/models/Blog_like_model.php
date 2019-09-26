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
			);
			return $this->update($up_data, array('user_id' => $uid, 'blog_id' => $blog_id));
		}
		//插入数据
		$insert_data = array(
			'blog_id' => $blog_id,
			'user_id' => $uid,
			'status' => $status
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
	public function get_like_count($ids){
		$res =  $this->db->select('blog_id,count(`id`) as num ')
			->from($this->table)
			->where_in('blog_id',$ids)
			->group_by('blog_id')
			->get()->result_array();
		$arr = array();
		foreach ($res as $lv){
			$arr[$lv['blog_id']] = $lv['num'];
		}
		return $arr;
	}
}

