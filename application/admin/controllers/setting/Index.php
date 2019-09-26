<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	//后台账号列表
	public function index()
	{
		$segment = intval($this->uri->segment(4));
		$offset = $segment > 0 ? ($segment-1) * PER_PAAGE : 0;
		$this->load->model('admin_model');
		$res = $this->admin_model->get_account($offset, PER_PAAGE);
		$config['base_url'] = '/setting/index/index/';
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $res['total'];
		$config['uri_segment'] = 4;
		$config['per_page'] = PER_PAAGE;
		$this->pagination->initialize($config);
		$page = $this->pagination->create_links();
		$data = array(
			'title' => '管理账号',
			'list' => $res['list'],
			'page' => $page
		);
		$this->load->view('header', $data);
		$this->load->view('navbar', $data);
		$this->load->view('setting/index', $data);
		$this->load->view('footer');
	}

	//账号详情
	public function account_detail()
	{
		$id = $this->uri->segment(4);
		if (empty($id)) {
			return;
		}
		$this->load->model('admin_model');
		$res = $this->admin_model->get_account_detail($id);
		$data = array(
			'title' => '编辑账号',
			'detail' => $res,
		);
		$this->load->view('header', $data);
		$this->load->view('navbar', $data);
		$this->load->view('setting/account_detail', $data);
		$this->load->view('footer');
	}

	//编辑账号
	public function account_edit()
	{
		$id = $this->input->post('id', true);
		$password = $this->input->post('password', true);
		if (empty($id) || empty($password)) {
			response_params_error();
		}
		$this->load->model('admin_model');
		$res = $this->admin_model->change_password($id, $password);
		if (empty($res)) {
			$this->response_error('修改失败');
		}
		$this->response_success('修改成功', '/setting/index');
	}

	//添加账户
	public function account_add()
	{
		$post = $this->input->post();
		if ($post) {
			$account = $this->input->post('account', true);
			$password = $this->input->post('password', true);
			if (empty($account) || empty($password)) {
				$this->response_params_error();
			}
			$this->load->model('admin_model');
			$result = $this->admin_model->account_add($account, $password);
			if ($result == 1){
				$this->response_success('添加成功','/setting/index');
			}
			$error_msg = '添加失败';
			if ($result == 0){
				$error_msg = '账号已经存在';
			}
			$this->response_error($error_msg);
		}
		$data = array(
			'title' => '添加账号',
		);
		$this->load->view('header', $data);
		$this->load->view('navbar', $data);
		$this->load->view('setting/account_add', $data);
		$this->load->view('footer');
	}

	//删除账号
	function account_del(){
		$uid = intval($this->input->post('uid', true));
		if ($uid > 0){
			$this->load->model('admin_model');
			$res = $this->admin_model->account_del($uid);
			if (empty($res)){
				$this->response_error('删除失败');
			}
			$this->response_success('删除成功', '/setting/index');
		}
	}
}
