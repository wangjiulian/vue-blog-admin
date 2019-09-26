<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo 'user';
	}

	//编辑账号
	public function edit(){
		$this->load->model('admin_model');
		$uid = $this->session->userdata('adm_uid');
		$post = $this->input->post();
		if ($post){
			$nick_name = $post['nick_name'];
			if (!isset($nick_name)){
				$this->response_params_error();
			}
			$res = $this->admin_model->edit_info($uid, $nick_name);

			if ($res){
				$this->response_success('编辑成功','/');
			}
			$this->response_error('编辑失败，请稍后重试');
		}
		$res = $this->admin_model->get_account_detail($uid);
		$data = array(
			'title' => '编辑账号',
			'avatar' => empty($res['avatar']) ? '' : BASE_FILE_URL . $res['avatar'],
			'nick_name' => $res['nick_name']
		);
		$this->load->view('header', $data);
		$this->load->view('navbar', $data);
		$this->load->view('/user/user_edit', $data);
		$this->load->view('footer', $data);
	}
}
