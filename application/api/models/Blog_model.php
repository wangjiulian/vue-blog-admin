<?php
/**
 *
 *
 * User: avery
 * Date: 2019-07-21
 * Time: 22:34
 */

class Blog_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_BLOG;
	}

	/**
	 * 博客列表
	 *
	 * @access public
	 * @param mixed $offset 偏移
	 * @param mixed $perpage 分页
	 * @param mixed $where where
	 * @param mixed $orderby 排序
	 * @return array
	 */

	public function get_blog_list($offset, $perpage, $where = array(1 => 1), $orderby = '', $is_detail = false, $search = '')
	{

		$this->db->select('a.id,a.read_num, a.user_id as uid,a.title,a.content,a.imgs,a.create_time,b.avatar,b.nick_name')
			->from($this->table . ' a ')
			->join(TABLE_USER . ' as b', 'a.user_id = b.id ', 'left')
			->where($where)
			->limit($perpage, $offset);
		if ($orderby) {
			$this->db->order_by($orderby);
		}
		if ($search) {
			$this->db->like('title', $search);
		}
		$res = $this->db->get()->result_array();
//		echo $this->db->last_query();die();
		if (empty($res)) {
			return array();
		}
		$ids = array();
		$tmp_list = array();
		foreach ($res as $lv) {
			$ids[] = $lv['id'];
			if (!empty($lv['imgs'])) {
				//字符串转为数组并拼接路径
				$lv['imgs'] = explode(',', $lv['imgs']);
//				$lv['imgs'] = array_map(function ($img) {
//					return BASE_FILE_URL . $img;
//				}, $imgs);
			} else {
				$lv['imgs'] = array();
			}
			if (!$is_detail) {
				$lv['content'] = mb_substr(strip_tags($lv['content']), 0, 250);
			}
			$tmp_list[$lv['id']] = $lv;

		}
		$this->load->model('blog_comment_model');
		$this->load->model('blog_like_model');
		$comments = $this->blog_comment_model->get_comment_count($ids);
		$likes = $this->blog_like_model->get_like_count($ids);
		$list = array();

		foreach ($tmp_list as $key => $value) {
			$value['comment_num'] = isset($comments[$key]) ? $comments[$key] : 0;
			$value['like_num'] = isset($likes[$key]) ? $likes[$key] : 0;
			$list[] = $value;
		}

		return $list;

	}

	/**
	 * 获取推荐列表
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_list_recommend_blog($offset, $perpage, $search, $type = 0)
	{
		$where = array(
			'a.hot' => 1
		);
		if ($type > 0){
			//根据类型查找
			$where['a.blog_type'] = $type;
		}

		return $this->get_blog_list($offset, $perpage, $where,'create_time desc,read_num desc',false,$search);
	}


	/**
	 * 添加博客
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function add_blog($uid, $title, $content, $imgs,$blog_type)
	{
		$insert_data = array(
			'user_id' => $uid,
			'type' => 1, //前端添加
			'blog_type' => $blog_type,
			'title' => $title,
			'content' => $content,
			'imgs' => $imgs,
			'create_time' => time()
		);
		return $this->insert($insert_data);
	}

	/**
	 * 博客详情
	 *
	 * @access public
	 * @param mixedblog_list
	 * @return
	 */
	public function get_blog_detail($blog_id, $uid = 0)
	{
		$res = $this->get_blog_list(0, 1, array('a.id' => $blog_id), '', true);
		if ($res) {
			if ($uid > 0) {
				$this->load->model('blog_like_model');
				$like_status = $this->blog_like_model->get_like_status($uid, $blog_id);
				$res[0]['like'] = $like_status;

			}
			return $res[0];
		}
		return null;
	}

	/**
	 * 关注博客列表
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_list_attention_blog($uid, $offset, $perpage)
	{
		$this->load->model('user_follow_model');
		//关注用户id
		$ids = $this->user_follow_model->get_follow_list_ids($uid);
		if (empty($ids)) {
			return array();
		}
		$list = $this->db->from($this->table)
			->select('id,user_id as uid,title,content,share_num,create_time')
			->where_in('user_id', $ids)
			->limit($perpage, $offset)
			->get()->result_array();

		return $list;
	}

	/**
	 * 增加阅读量
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function add_blog_read($blog_id)
	{
		return $this->db->set('read_num', 'read_num+1', false)->where(array('id' => $blog_id))->update($this->table);
	}

	/**
	 * 获取文章数量
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_blog_num($uid)
	{
		return $this->count(array('user_id' => $uid));
	}

}
