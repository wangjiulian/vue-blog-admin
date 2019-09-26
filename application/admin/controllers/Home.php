<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	public  function index(){
		$this->checkLogin();
		$data = array(
			'title' => '首页'
		);
		$this->load->view('header',$data);
		$this->load->view('navbar', $data);
		$this->load->view('home');
		$this->load->view('footer');
	}

	public function login(){
		$post = $this->input->post();
		if ($post){
			$account = $this->input->post('account', true);
			$password = $this->input->post('password', true);
			if (empty($account) || empty($password)){
				$this->response_params_error();
			}
			$this->load->model('admin_model');
			$res = $this->admin_model->login($account);
			if ($res){
				if (password_verify($password, $res['password'])){
					$data = array(
						'adm_uid' => $res['id'],
						'adm_super' => $res['super'],
						'adm_nick_name' => $res['nick_name'],
						'adm_avatar' => empty($res['avatar']) ? '' : BASE_FILE_URL . $res['avatar'],
					);
					$this->session->set_userdata($data);
					$this->response_success('登录成功','/');
				}
				$this->response_error('账号或密码错误');
			}else{
				$this->response_error('账号不存在');
			}
		}

		$this->load->view('login');

	}

	function logout(){
		$this->session->sess_destroy();
		redirect('/home/login');
	}
}
