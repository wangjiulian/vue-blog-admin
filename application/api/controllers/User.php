<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('user_follow_model');
	}

	public function index()
	{
		echo password_hash('123456', PASSWORD_DEFAULT);
	}

	/**
	 * @api {post} /user/login 登录
	 * @apiVersion 0.1.0
	 * @apiName login
	 * @apiGroup USER
	 *
	 * @apiParam {String} account (必填)账号
	 * @apiParam {String} password (必填)密码
	 * @apiSuccessExample 请求成功:
	 * {
	 *        "re_st": "success",
	 *        "re_info": {
	 *        "id": "1", //用户id
	 *        "account": "15985866257", //手机号
	 *        "avatar": "", //头像
	 *        "nick_name": "",  //昵称
	 *        "email": "", //邮箱
	 *        "signature": "" //签名
	 * }
	 * }
	 *
	 * @apiErrorExample 请求失败:
	 * {
	 * "re_st": "error",
	 * "re_info": "账号或密码错误"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/user/login
	 */
	public function login()
	{
		$account = trim($this->input->post('account', true));
		$password = trim($this->input->post('password', true));
		if (empty($account) || empty($password)) {
			$this->response_params_error();
		}
		if (!preg_match('/^1[34578]\d{9}$/', $account)) {
			$this->response_error('手机号不合法');
		}
		$res = $this->user_model->login($account, $password);
		if ($res == 0) {
			$this->response_error('账号或密码错误');
		}
		if ($res == -1) {
			$this->response_error('账号不存在');
		}
		$this->response_success($res);


	}

	/**
	 * @api {post} /user/register 注册
	 * @apiVersion 0.1.0
	 * @apiName register
	 * @apiGroup USER
	 *
	 * @apiParam {String} account (必填)手机号
	 * @apiParam {String} password (必填)密码
	 * * @apiParam {String} nick_name (非必填)昵称
	 * @apiSuccessExample 请求成功:
	 * {
	 *        "re_st": "success",
	 *        "re_info": {
	 *        "id": "1", //用户id
	 *        "account": "15985866257", //手机号
	 *        "avatar": "", //头像
	 *        "nick_name": "",  //昵称
	 *        "email": "", //邮箱
	 *        "signature": "" //签名
	 * }
	 * }
	 *
	 * @apiErrorExample 请求失败:
	 * {
	 *        "re_st": "error",
	 *        "re_info": "账号已经存在"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/user/register
	 */
	public function register()
	{
		$account = trim($this->input->post('account', true));
		$password = trim($this->input->post('password', true));
		$nick_name = trim($this->input->post('nick_name', true));
		if (empty($account) || empty($password)) {
			$this->response_params_error();
		}
		if (!preg_match('/^1[34578]\d{9}$/', $account)) {
			$this->response_error('手机号不合法');
		}
		if (!empty($nick_name)) {
			if (strlen($nick_name) > 30) {
				$this->response_error('昵称太长');
			}
		}
		$res = $this->user_model->register($account, $password, $nick_name);
		switch ($res) {
			case 1:
				$user = $this->user_model->login($account, $password);
				if ($user) {
					$this->response_success($user);
				} else {
					$this->response_error('注册异常');
				}

				break;
			case 0:
				$this->response_error('账号已经存在');
				break;
			case -1:
				$this->response_error('注册异常');
				break;
		}
	}

	/**
	 * @api {post} /user/reset_password 重置密码
	 * @apiVersion 0.1.0
	 * @apiName reset_password
	 * @apiGroup USER
	 *
	 * @apiParam {String} account (必填)账号
	 * @apiParam {String} password (必填)密码
	 * @apiSuccessExample 请求成功:
	 * {
	 *        "re_st": "success",
	 *        "re_info": "密码更新成功"
	 * }
	 *
	 * @apiErrorExample 请求失败:
	 * {
	 *        "re_st": "error",
	 *        "re_info": "账号不存在"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/user/reset_password
	 */
	public function reset_password()
	{
		$account = trim($this->input->post('account', true));
		$password = trim($this->input->post('password', true));
		if (empty($account) || empty($password)) {
			$this->response_params_error();
		}
		if (!preg_match('/^1[34578]\d{9}$/', $account)) {
			$this->response_error('手机号不合法');
		}
		$res = $this->user_model->reset_password($account, $password);
		switch ($res) {
			case 1:
				$this->response_success('密码更新成功');
				break;
			case -1:
				$this->response_error('操作失败,请稍后重试');
				break;
			case 0:
				$this->response_error('账号不存在');
				break;
		}
	}

	public function edit()
	{

	}

	/**
	 * @api {post} /user/follow_user 关注用户
	 * @apiVersion 0.1.0
	 * @apiName follow_user
	 * @apiGroup USER
	 *
	 * @apiParam {String} uid (必填)用户id
	 * @apiParam {String} token (必填)token
	 * @apiParam {String} follow_id (必填)被关注用户id
	 * @apiSuccessExample 请求成功:
	 * {
	 *        "re_st": "success",
	 *        "re_info": "关注成功"
	 * }
	 *
	 * @apiErrorExample 请求失败:
	 * {
	 *        "re_st": "error",
	 *        "re_info": "关注失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/user/follow_user
	 */
	public function follow_user()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		$follow_id = intval($this->input->post('follow_id', true));
		if ($uid < 1 || $follow_id < 1 || empty($token)) {
			$this->response_params_error();
		}
		$this->check_token();
		if ($follow_id == $uid) {
			$this->response_error('自己不能关注自己');
		}
		$res = $this->user_follow_model->follow_user($uid, $follow_id);
		if ($res) {
			$this->response_success('关注成功');
		}
		$this->response_error('关注失败');
	}

	/**
	 * @api {post} /user/unfollow_user 取消关注用户
	 * @apiVersion 0.1.0
	 * @apiName unfollow_user
	 * @apiGroup USER
	 *
	 * @apiParam {String} uid (必填)用户id
	 * @apiParam {String} token (必填)token
	 * @apiParam {String} unfollow_id (必填)取消关注用户id
	 * @apiSuccessExample 请求成功:
	 * {
	 *        "re_st": "success",
	 *        "re_info": "取消关注成功"
	 * }
	 *
	 * @apiErrorExample 请求失败:
	 * {
	 *        "re_st": "error",
	 *        "re_info": "取消关注失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/user/unfollow_user
	 */
	public function unfollow_user()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		$unfollow_id = intval($this->input->post('unfollow_id', true));
		if ($uid < 1 || $unfollow_id < 1 || empty($token)) {
			$this->response_params_error();
		}
		$this->check_token();
		if ($unfollow_id == $uid) {
			$this->response_error('自己不能取消关注自己');
		}
		$res = $this->user_follow_model->unfollow_user($uid, $unfollow_id);
		if ($res > 0) {
			$this->response_success('取消关注成功');
		}
		if ($res == -1) {
			$this->response_error('您还未关注该用户');
		}
		$this->response_error('取消关注失败');
	}

	/**
	 * @api {post} /user/get_follow_list 关注列表
	 * @apiVersion 0.1.0
	 * @apiName get_follow_list
	 * @apiGroup USER
	 *
	 * @apiParam {String} uid (非必填)用户uid
	 * @apiParam {String} host_id (必填)查询id
	 * @apiSuccessExample 请求成功:
	 * {
	 * "re_st": "success",
	 * "re_info": {
	 * "data_list": [{
	 * "uid": "1",
	 * "nick_name": "逆流得渔",
	 * "avatar": "http:\/\/api.blog.runsss.com\/data\/blog\/2019-09-11\/91d4fbdf773e7d82c9d90aba4aa1251b.gif",
	 * "signature": ".清醒时做事，糊涂时读书，大怒时睡觉，独处时思考；做一个幸福的人，读书，旅行，努力工作，关心身体和心情。",
	 * "follow": "2" //1已关注 2未关注
	 * }]
	 * }
	 * }
	 *
	 * @apiErrorExample 请求失败:
	 * {
	 *        "re_st": "empty",
	 *        "re_info": "暂无数据"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/user/get_follow_list
	 */
	public function get_follow_list()
	{
		$host_id = intval($this->input->post('host_id', true));
		$uid = intval($this->input->post('uid', true));
		if ($host_id < 1) {
			$this->response_params_error();
		}
		$res = $this->user_follow_model->get_follow_list($host_id, $uid);
		if ($res) {
			$this->response_success(array('data_list' => $res));
		}
		$this->response_empty();
	}

	/**
	 * @api {post} /user/get_fans_list 粉丝列表
	 * @apiVersion 0.1.0
	 * @apiName get_fans_list
	 * @apiGroup USER
	 *
	 * @apiParam {String} uid (非必填)用户id
	 * @apiParam {String} host_id (必填)查询id
	 *
	 * @apiSuccessExample 请求成功:
	 * {
	 * "re_st": "success",
	 * "re_info": {
	 * "data_list": [{
	 * "uid": "1",
	 * "nick_name": "逆流得渔",
	 * "avatar": "http:\/\/api.blog.runsss.com\/data\/blog\/2019-09-11\/91d4fbdf773e7d82c9d90aba4aa1251b.gif",
	 * "signature": ".清醒时做事，糊涂时读书，大怒时睡觉，独处时思考；做一个幸福的人，读书，旅行，努力工作，关心身体和心情。",
	 * "follow": "2" //1已关注 2 未关注
	 * }]
	 * }
	 * }
	 *
	 * @apiErrorExample 请求失败:
	 * {
	 *        "re_st": "empty",
	 *        "re_info": "暂无数据"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/user/get_fans_list
	 */
	public function get_fans_list()
	{
		$host_id = intval($this->input->post('host_id', true));
		$uid = intval($this->input->post('uid', true));
		if ($host_id < 1) {
			$this->response_params_error();
		}
		$res = $this->user_follow_model->get_fans_list($host_id, $uid);
		if ($res) {
			$this->response_success(array('data_list' => $res));
		}
		$this->response_empty();
	}

	/**
	 * @api {post} /user/get_user_blog 获取个人帖子列表
	 * @apiVersion 0.1.0
	 * @apiName get_user_blog
	 * @apiGroup USER
	 *
	 * @apiParam {String} host_id (必填)查询用户id
	 * @apiParam {search} search (非选填)搜索内容
	 * @apiParam {Int} hot (选填) 1 热门文章 否则普通文章
	 * @apiParam {Int} page (必填)页码默认为1
	 * @apiSuccessExample 请求成功:
	 *
	 * {
	 * "re_st": "success",
	 * "re_info": {
	 * "data_list": [
	 * {
	 * "id": "1", //帖子id
	 * "uid": "8", //发布者id
	 * "title": "我得第一篇文章", //标题
	 * "content": "就会大大大大大啊三大大大大", //html内容
	 * "comment_num": "0", //评论数量
	 * "like_num": "0", //点赞数量
	 * "share_num": "0", //分享数量
	 * "create_time": "1563766795" //发布时间
	 * }
	 * ]
	 * }
	 * }
	 * @apiErrorExample 请求失败
	 * {
	 *    "re_st": "error",
	 *    "re_info": "请求失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/user/get_user_blog
	 */
	public function get_user_blog()
	{
		$host_id = intval($this->input->post('host_id', true));
		$search = $this->input->post('search', true);
		$hot = intval($this->input->post('hot', true));
		$page = intval($this->input->post('page', true));
		$offset = $page > 0 ? ($page - 1) * PERPAGE_BLOG : 0; //偏移
		if ($host_id < 1) {
			$this->response_params_error();
		}
		$res = $this->user_model->get_user_blog_list($host_id, $offset, PERPAGE_BLOG, $hot, $search);
		if ($res) {
			$this->response_success(array('data_list' => $res));
		}
		$this->response_empty();
	}

	/**
	 * @api {post} /user/get_user_detail 用户详细信息
	 * @apiVersion 0.1.0
	 * @apiName get_user_detail
	 * @apiGroup USER
	 *
	 * @apiParam {String} host_id (必填)查询用户id
	 * * @apiParam {String} uid (必填)uid
	 * @apiSuccessExample 请求成功:
	 *{
	 * "re_st": "success",
	 * "re_info": {
	 * "uid": "1",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg",
	 * "nick_name": "小米",
	 * "signature": "",
	 * "fans_num": 0,
	 * "follow_num": 0,
	 * "blog_num": 769
	 * }
	 * }
	 *
	 * @apiErrorExample 请求失败:
	 * {
	 *        "re_st": "empty",
	 *        "re_info": "暂无数据"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/user/get_user_detail
	 */
	public function get_user_detail()
	{
		$host_id = intval($this->input->post('host_id', true));
		$uid = intval($this->input->post('uid', true));
		if ($host_id < 1) {
			$this->response_params_error();
		}
		$res = $this->user_model->get_user_detail_info($host_id, $uid);
		if ($res) {
			unset($res['email']);
			$this->response_success($res);
		}
		$this->response_error('用户不存在');
	}

	/**
	 * @api {post} /user/get_user_comment 获取用户帖子评论
	 * @apiVersion 0.1.0
	 * @apiName get_user_comment
	 * @apiGroup USER
	 *
	 * @apiParam {String} uid (必填)uid
	 * @apiParam {String} token (必填)token
	 * @apiParam {Int} page (必填)默认1
	 * @apiSuccessExample 请求成功:
	 * {
	 * "re_st": "success",
	 * "re_info": {
	 * "data_list": [
	 * {
	 * "id": "1",
	 * "comment_time": "1567136405",
	 * "blog_id": "2",
	 * "blog_title": "没有大学文凭，建议你先从这几门编程语言开始",
	 * "uid": "1",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg",
	 * "nick_name": "小米"
	 * },
	 * {
	 * "id": "2",
	 * "comment_time": "1567136445",
	 * "blog_id": "2",
	 * "blog_title": "没有大学文凭，建议你先从这几门编程语言开始",
	 * "uid": "1",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg",
	 * "nick_name": "小米"
	 * },
	 * {
	 * "id": "3",
	 * "comment_time": "1567136501",
	 * "blog_id": "2",
	 * "blog_title": "没有大学文凭，建议你先从这几门编程语言开始",
	 * "uid": "1",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg",
	 * "nick_name": "小米"
	 * },
	 * {
	 * "id": "4",
	 * "comment_time": "1567136505",
	 * "blog_id": "2",
	 * "blog_title": "没有大学文凭，建议你先从这几门编程语言开始",
	 * "uid": "1",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg",
	 * "nick_name": "小米"
	 * },
	 * {
	 * "id": "5",
	 * "comment_time": "1567137008",
	 * "blog_id": "2",
	 * "blog_title": "没有大学文凭，建议你先从这几门编程语言开始",
	 * "uid": "1",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg",
	 * "nick_name": "小米"
	 * }
	 * ]
	 * }
	 * }
	 *
	 * @apiErrorExample 请求失败:
	 * {
	 *        "re_st": "empty",
	 *        "re_info": "暂无数据"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/user/get_user_comment
	 */
	public function get_user_comment()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		$page = intval($this->input->post('page', true));
		$offset = $page > 0 ? ($page - 1) * PERPAGE_USER_COMMENT : 0; //偏移
		if ($uid < 1 || empty($token)) {
			$this->response_params_error();
		}
		$this->check_token();
		$this->load->model('blog_comment_model');
		$res = $this->blog_comment_model->get_comment_user_list($uid, $offset, PERPAGE_USER_COMMENT);
		if ($res) {
			$this->response_datalist($res);
		}
		$this->response_empty();
	}

	/**
	 * @api {post} /user/get_user_like 获取用户帖子点赞
	 * @apiVersion 0.1.0
	 * @apiName get_user_like
	 * @apiGroup USER
	 *
	 * @apiParam {String} uid (必填)uid
	 * @apiParam {String} token (必填)token
	 * @apiParam {Int} page (必填)默认1
	 * @apiSuccessExample 请求成功:
	 * {
	 * "re_st": "success",
	 * "re_info": {
	 * "data_list": [
	 * {
	 * "blog_id": "2",
	 * "blog_title": "没有大学文凭，建议你先从这几门编程语言开始",
	 * "uid": "1",
	 * "nick_name": "小米",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg"
	 * },
	 * {
	 * "blog_id": "3",
	 * "blog_title": "4G网速变慢？工信部就此约谈三家运营商",
	 * "uid": "1",
	 * "nick_name": "小米",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg"
	 * }
	 * ]
	 * }
	 * }
	 *
	 * @apiErrorExample 请求失败:
	 * {
	 *        "re_st": "empty",
	 *        "re_info": "暂无数据"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/user/get_user_like
	 */
	public function get_user_like()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		$page = intval($this->input->post('page', true));
		$offset = $page > 0 ? ($page - 1) * PERPAGE_USER_LIKE : 0; //偏移
		if ($uid < 1 || empty($token)) {
			$this->response_params_error();
		}
		$this->check_token();
		$this->load->model('blog_like_model');
		$res = $this->blog_like_model->get_like_user_list($uid, $offset, PERPAGE_USER_LIKE);
		if ($res) {
			$this->response_datalist($res);
		}
		$this->response_empty();
	}

	public function edit_info()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		$avatar = $this->input->post('avatar', true);
		$nick_name = $this->input->post('nick_name', true);
		$sex = intval($this->input->post('sex', true));
		$email = $this->input->post('email', true);
		$signature = $this->input->post('signature', true);
		if ($uid < 1 || empty($token) || empty($avatar) || empty($nick_name) || !in_array($sex, array(1, 2)) || empty($email) || empty($signature)) {
			$this->response_params_error();
		}
		if (strlen($nick_name) > 30) {
			$this->response_error('昵称太长');
		}
		if (strlen($signature) > 300) {
			$this->response_error('个性签名太长');
		}
		$this->check_token();
		$save_data = array(
			'avatar' => $avatar,
			'nick_name' => $nick_name,
			'sex' => $sex,
			'email' => $email,
			'signature' => $signature
		);
		$res = $this->user_model->save_info($uid, $save_data);
		if ($res) {
			$user = $this->user_model->get_user_detail_info($uid);
			if ($user) {
				$this->response_success($user);
			}
			$this->response_error('编辑失败');
		}
		$this->response_error('编辑失败');
	}
}
