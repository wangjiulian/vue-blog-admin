<?php
/**
 *
 *
 * User: avery
 * Date: 2019-07-22
 * Time: 20:21
 */

class User_follow_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_USER_FOLLOW;
	}

	/**
	 *
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function follow_user($uid, $follow_id)
	{
		//查询是否已关注
		$follow = $this->select_one('id', array('user_id' => $uid, 'host_id' => $follow_id));
		if ($follow) {
			return 1;
		}
		$insert_data = array(
			'user_id' => $uid,
			'host_id' => $follow_id
		);
		return $this->insert($insert_data);
	}

	/**
	 * 取消关注
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function unfollow_user($uid, $unfollow_id)
	{
		//查询是否已关注
		$follow = $this->select_one('id', array('user_id' => $uid, 'host_id' => $unfollow_id));

		if ($follow) {
			return $this->delete(array('host_id' => $unfollow_id, 'user_id' => $uid));
		}
		return -1;
	}

	/**
	 * 获取关注列表
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_follow_list($host_id, $uid)
	{
		$res = $this->db->select('b.id as uid, b.nick_name,b.avatar,b.signature')
			->from($this->table . ' as a')
			->join(TABLE_USER . ' as b', ' a.host_id = b.id', 'left')
			->where('a.user_id', $host_id)
			->get()
			->result_array();

		$arr = array();
		if ($res){
			foreach ($res as $lv){
				$status = $this->get_follow_status($lv['uid'], $uid);
				$lv['follow'] = empty($status) ? '2' : '1';
				$arr[] = $lv;
			}
		}

		return $arr;
	}

	/**
	 * 获取关注用户id
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_follow_list_ids($uid){

		$res = $this->select('host_id', array('user_id' => $uid));
		$ids = array();
		foreach ($res as $lv){
			$ids[] = $lv['host_id'];
		}
		return $ids;

	}

	/**
	 * 获取粉丝列表
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_fans_list($host_id, $uid)
	{
		$res = $this->db->select('b.id as uid, b.nick_name,b.avatar,b.signature')
			->from($this->table . ' as a')
			->join(TABLE_USER . ' as b', ' a.user_id = b.id', 'left')
			->where('a.host_id', $host_id)
			->get()
			->result_array();
		$arr = array();
		if ($res){
			foreach ($res as $lv){
				$status = $this->get_follow_status($lv['uid'], $uid);
				$lv['follow'] = empty($status) ? '2' : '1';
				$arr[] = $lv;
			}
		}

		return $arr;
	}

	/**
	 * 获取粉丝数量
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_fans_num($uid){
		return	$this->count(array('host_id' => $uid));
	}


	/**
	 * 获取关注数量
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_follow_num($uid){
		return	$this->count(array('user_id' => $uid));
	}

	/**
	 * 获取关注状态
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_follow_status($host_id, $uid){
		return $this->select_one('id', array('host_id' => $host_id,'user_id' => $uid));
	}

}
