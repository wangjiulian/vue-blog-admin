<?php
/**
 *
 *
 * User: avery
 * Date: 2019-07-19
 * Time: 16:23
 */

class Blog_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_BLOG;
	}

	public function get_blog_list($offset, $perpage, $where = array(1 => 1), $orderby = '', $is_detail = false)
	{
		$total = $this->count();
		$this->db->select('a.id,a.user_id as uid,a.hot,a.title,a.content,a.imgs,a.create_time,b.avatar,b.nick_name')
			->from($this->table . ' a ')
			->join(TABLE_USER . ' as b', 'a.user_id = b.id ', 'left')
			->where($where)
			->limit($perpage, $offset);
		if ($orderby) {
			$this->db->order_by($orderby);
		}
		$data = $this->db->get()->result_array();
		$list = array();
		if (!empty($data)) {
			$ids = array();
			$tmp_list = array();
			foreach ($data as $val) {
				if (!empty($val['imgs'])) {
					$val['imgs'] = explode(',', $val['imgs']);
				} else {
					$val['imgs'] = array();
				}
				if (!$is_detail) {
					//详情不截取内容
					$val['content'] = substr(strip_tags($val['content']), 0, 1000);
				}
				$tmp_list[$val['id']] = $val;
				$ids[] = $val['id'];
			}

			$this->load->model('blog_comment_model');
			$this->load->model('blog_like_model');
			$comments = $this->blog_comment_model->get_comment_count($ids);
			$likes = $this->blog_like_model->get_like_count($ids);
			foreach ($tmp_list as $key => $value) {
				$value['comment_num'] = isset($comments[$key]) ? $comments[$key] : 0;
				$value['like_num'] = isset($likes[$key]) ? $likes[$key] : 0;
				$list[] = $value;
			}
		}
		$result = array(
			'total' => $total,
			'list' => $list
		);
		return $result;
	}

	/**
	 * 添加博客
	 *
	 * @access public
	 * @param mixed $uid 用户id
	 * @param mixed $title 标题
	 * @param mixed $content 内容
	 * @param mixed $imgs 图片字符串
	 * @param mixed $hot 1推荐
	 * @return
	 */
	public function add_blog($uid, $title, $content, $imgs = '', $hot = 2)
	{
		$insert_data = array(
			'user_id' => $uid,
			'type' => 2,
			'title' => $title,
			'content' => $content,
			'imgs' => $imgs,
			'hot' => $hot,
			'create_time' => time()
		);
		return $this->insert($insert_data);
	}

	/**
	 * 编辑博客
	 *
	 * @access public
	 * @param mixed $id 帖子id
	 * @param mixed $uid 用户id
	 * @param mixed $title 标题
	 * @param mixed $content 内容
	 * @param mixed $imgs 图片字符串
	 * @param mixed $hot 1推荐
	 * @return
	 */
	public function edit_blog($id, $uid, $title, $content, $imgs = '', $hot = 2)
	{
		$update_data = array(
			'user_id' => $uid,
			'type' => 2,
			'title' => $title,
			'content' => $content,
			'imgs' => $imgs,
			'hot' => $hot,
			'create_time' => time()
		);
		return $this->update($update_data, array('id' => $id));
	}


	/**
	 * 设置推荐
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function set_blog_hot($id, $hot)
	{
		$up_data = array(
			'hot' => $hot
		);
		return $this->update($up_data, array('id' => $id));
	}

	/**
	 * 获取博客详情
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_blog_detail($id)
	{
		return $this->get_blog_list(0, 1, array('a.id' => $id), '', true);

	}
}
