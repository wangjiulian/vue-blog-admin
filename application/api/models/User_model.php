<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_USER;
	}

	/**
	 * 注册
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function register($account, $password, $nick_name)
	{
		$user = $this->select_one('account', array('account' => $account));
		if ($user) {
			return 0;
		}
		$insert_data = array(
			'account' => $account,
			'password' => password_hash($password, PASSWORD_DEFAULT),
			'nick_name' => $nick_name
		);
		$res = $this->insert($insert_data);
		if ($res) {
			return 1;
		}
		return -1;
	}

	/**
	 * 登录
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function login($account, $password)
	{
		$user = $this->select_one('id as uid,sex,account,password,avatar,nick_name,email,signature', array('account' => $account));
		if ($user) {
			if (password_verify($password, $user['password'])) {
				$login_token = md5(time() . $account . $password);
				$up_data = array(
					'login_token' => $login_token
				);
				$this->update($up_data, array('account' => $account));
				unset($user['password']);
				$user['login_token'] = $login_token;
				return $user;
			}
			return 0;
		}
		return -1;
	}

	/**
	 * 重制密码
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function reset_password($account, $password)
	{
		$user = $this->select_one('account', array('account' => $account));
		if ($user) {
			$up_data = array(
				'password' => password_hash($password, PASSWORD_DEFAULT),
			);
			$res = $this->update($up_data, array('account' => $account));
			if ($res) {
				return 1;
			}
			return -1;
		}
		return 0;
	}

	/**
	 * 检查登录
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function check_token($uid, $token)
	{
		$user = $this->select_one('account', array('id' => $uid, 'login_token' => $token));
		return $user;
	}

	/**
	 * 获取用户博客
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_user_blog_list($uid, $offset, $perpage, $hot = 0, $search = '')
	{
		$this->load->model('blog_model');
		$where = array(
			'a.user_id' => $uid
		);
		if ($hot == 1) {
			$where['hot'] = 1;
		}
		$res = $this->blog_model->get_blog_list($offset, $perpage, $where, 'create_time desc', false, $search);
		return $res;
	}

	/**
	 * 获取用详细信息
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function get_user_detail_info($host_id, $uid = 0)
	{
		$user = $this->select_one('id as uid, sex,avatar,nick_name,signature,email', array('id' => $host_id));
		if ($user) {
			$this->load->model('user_follow_model');
			$this->load->model('blog_model');
			$fans_num = $this->user_follow_model->get_fans_num($host_id);
			$follow_num = $this->user_follow_model->get_follow_num($host_id);
			$blog_num = $this->blog_model->get_blog_num($host_id);
			$user['fans_num'] = $fans_num;
			$user['follow_num'] = $follow_num;
			$user['blog_num'] = $blog_num;
			$user['follow'] = 2;
			if ($uid > 0 && $uid != $host_id) {
				//判断用户关注
				$status = $this->user_follow_model->get_follow_status($host_id, $uid);
				if ($status) {
					$user['follow'] = 1;
				}
			}
			return $user;
		}
		return null;
	}

	/**
	 * 保存信息
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function save_info($uid, $data)
	{
		return $this->update($data,array('id' => $uid));
	}
}
