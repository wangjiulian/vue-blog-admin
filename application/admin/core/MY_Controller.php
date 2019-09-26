<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$cls = $this->router->fetch_class();
		if ($cls != 'home') {
			$this->checkLogin();
		}
		$this->request_log();
	}


	protected function response_success($re_info, $url = '')
	{
		$this->response(array('re_st' => 'success',
			're_info' => $re_info,
			'url' => $url));
	}

	protected function response_error($msg)
	{
		$this->response(array('re_st' => 'error',
			're_info' => $msg));
	}

	private function response($res)
	{
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
		exit();
	}

	protected function checkLogin()
	{
		$id = $this->session->userdata('adm_uid');
		if (empty($id)) {
			redirect('/home/logout');
		}
		return true;
	}

	public function getClientIP()
	{
		global $ip;
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else $ip = "";
		return $ip;
	}

	//写入日志
	private function request_log(){
		$adm_id = $this->session->userdata('adm_uid');
		$adm_name = $this->session->userdata('adm_nick_name');
		$data = array(
			'adm_id' => $adm_id ? $adm_id : 0,
			'adm_name' => $adm_name ? $adm_name : '',
			'request_uri' => $this->router->uri->uri_string,
			'request_data' => json_encode(array(
				'GET' => $_GET,
				'POST' => $_POST,
				'SERVER' => $_SERVER
			), JSON_UNESCAPED_UNICODE),
			'ip' => $this->getClientIP(),
			'request_time' => time()
		);
		$this->load->model('admin_request_log_model');
		$this->admin_request_log_model->save($data);
	}
}
