<?php
/**
 * api日志操作累
 *
 * User: avery
 * Date: 2019-08-04
 * Time: 14:13
 */

class Api_request_log_model extends MY_Model
{
	private $user_id = 0;
	private $request_uri;
	private $request_data;
	private $ip;
	private $request_time;
	private static $unset_keys = array(
		'token',
		'password'
	);
	const IS_SAVE = TRUE;

	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_API_REQUEST_LOG;
	}

	public function init()
	{
		$get = $_GET;
		$post = $_POST;
		$server = $_SERVER;
		$this->clean_data($get);
		$this->clean_data($post);
		$this->clean_data($server);
		$this->request_data = json_encode(
			array(
				'GET' => $get,
				'POST' => $post,
				'SERVER' => $server
			)
			, JSON_UNESCAPED_UNICODE);
		$this->user_id = isset($_POST['uid']) ? $_POST['uid'] : 0;
		$this->ip = $this->getip();
		$this->request_uri = $_SERVER['REQUEST_URI'];
		$this->request_time = time();
	}

	//保存请求
	public function save()
	{
		$data = array(
			'user_id' => $this->user_id,
			'request_uri' => $this->request_uri,
			'request_data' => $this->request_data,
			'ip' => $this->ip,
			'request_time' => $this->request_time
		);
		if (SELF::IS_SAVE){
			$this->insert($data);
		}
	}

	//清除数据
	private function clean_data(&$data)
	{
		foreach (self::$unset_keys as $lv) {
			unset($data[$lv]);
		}
	}

	function getip()
	{
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		} else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
			$ip = getenv("REMOTE_ADDR");
		} else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = "unknown";
		}
		return $ip;
	}


}
