<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: text/html;charset=utf-8");
		$this->load->model('api_request_log_model');
		$this->api_request_log_model->init();
	}

	public function __destruct()
	{
		$this->api_request_log_model->save();
	}

	protected function response_success($re_info)
	{
		$this->response(array('re_st' => 'success', 're_info' => $re_info));

	}

	protected function response_datalist($res)
	{
		$this->response(array('re_st' => 'success', 're_info' => array('data_list'  => $res)));

	}
	protected function response_params_error()
	{
		$this->response(array('re_st' => 'error',
			're_info' => '参数异常'));
	}


	protected function response_error($msg)
	{
		$this->response(array('re_st' => 'error', 're_info' => $msg));
	}

	protected function response_empty()
	{
		$this->response(array('re_st' => 'empty', 're_info' => '暂无数据'));
	}

	/**
	 *
	 *
	 * @access public
	 * @param mixed $res
	 * @return
	 */
	private function response($res)
	{
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
		exit();
	}

	/**
	 * 检查token
	 *
	 * @access protected
	 * @param mixed
	 * @return
	 */
	protected function check_token()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		if (empty($token)){
			$this->response_params_error();
		}
		$this->load->model('user_model');
		$user = $this->user_model->check_token($uid, $token);
		if (empty($user)){
			$this->response(array('re_st' => 're_login', 're_info' => '登录失效，请重新获取token'));
		}
	}
}
