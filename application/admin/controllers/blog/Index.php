<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('blog_model');
	}


	//博客列表
	public function blog_list()
	{
		$segment = intval($this->uri->segment(4));
		$offset = $segment > 0 ? ($segment - 1) * PER_PAAGE : 0;
		$res = $this->blog_model->get_blog_list($offset, PER_PAAGE,array(1 => 1),'create_time desc');
		$config['base_url'] = '/blog/index/blog_list/';
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $res['total'];
		$config['uri_segment'] = 4;
		$config['per_page'] = PER_PAAGE;
		$this->pagination->initialize($config);
		$page = $this->pagination->create_links();
		$data = array(
			'title' => '博客管理',
			'list' => $res['list'],
			'page' => $page,
		);
		$this->load->view('header', $data);
		$this->load->view('navbar', $data);
		$this->load->view('blog/blog_list', $data);
		$this->load->view('footer');
	}

	//发布博客
	public function blog_add()
	{
		$post = $this->input->post();
		if ($post) {
			$title = $this->input->post('title', true); //标题
			$content = $this->input->post('content'); //内容
			$hot = intval($this->input->post('hot', true)); //是否推荐
			$uid = $this->session->userdata('adm_uid');
			$imgs = '';
			preg_match_all('/<img src="(.*?)"/', $content, $img_arr);
			if (!empty($img_arr[0])){
				$imgs = implode(',',$img_arr[1]);
			}
			$res = $this->blog_model->add_blog($uid, $title, $content, $imgs, $hot);
			if ($res) {
				$this->response_success('发布成功', '/blog/index/blog_list');
			}
			$this->response_error('发布失败');
		}
		$data = array(
			'title' => '添加博客'
		);
		$this->load->view('header', $data);
		$this->load->view('navbar', $data);
		$this->load->view('blog/blog_add', $data);
		$this->load->view('footer');
	}

	public function blog_edit(){
		$id = $this->uri->segment(4);
		$post = $this->input->post();
		if ($post) {
			$id = $this->input->post('id', true); //标题
			$title = $this->input->post('title', true); //标题
			$content = $this->input->post('content'); //内容
			$hot = intval($this->input->post('hot', true)); //是否推荐
			$uid = $this->session->userdata('adm_uid');
			$imgs = '';
			preg_match_all('/<img src="(.*?)"/', $content, $img_arr);
			if (!empty($img_arr[0])){
				$imgs = implode(',',$img_arr[1]);
			}
			$res = $this->blog_model->edit_blog($id, $uid, $title, $content, $imgs, $hot);
			if ($res) {
				$this->response_success('编辑成功', '/blog/index/blog_list');
			}
			$this->response_error('编辑失败');

		}
		$res = $this->blog_model->get_blog_detail($id);
		$data = array(
			'title' => '博客详情',
			'detail' => $res['list'][0]
		);
//		var_dump($data['detail']);die();
		$this->load->view('header', $data);
		$this->load->view('navbar', $data);
		$this->load->view('blog/blog_edit', $data);
		$this->load->view('footer');
	}

	/**
	 * 添加博客图片
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function blog_img()
	{
		if (!isset($_FILES['file'])) {
			$this->response_error('请上传文件');
		}
		$this->load->library('tools');
		$this->load->model('upload_model');
		$upload_res = $this->tools->upload_photo('file', $this->get_blog_path());
		if ($upload_res) {
			$res = $this->upload_model->save_file('1', $upload_res['name'], $upload_res['path'], $upload_res['ext'], $upload_res['size']);
			if ($res) {
				$this->response_success(BASE_FILE_URL . '/' . $upload_res['path']);
			}
			$this->response_error('上传失败');
		}
		$this->response_error('上传失败');
	}

	private function get_blog_path()
	{
		$path = PATH_DATA . '/' . PATH_BLOG . '/' . date('Y-m-d');
		return $path;
	}

	/**
	 * 添加推荐
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function add_hot()
	{
		$this->set_hot(1);
	}

	/**
	 * 取消推荐
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function drop_hot()
	{
		$this->set_hot(2);
	}

	private function set_hot($hot)
	{
		$id = intval($this->input->post('id', true));
		if ($id < 1) {
			$this->response_error('操作失败');
			return;
		}
		$res = $this->blog_model->set_blog_hot($id, $hot);
		if ($res) {
			$this->response_success('操作成功');
		}
		$this->response_error('操作失败');
	}
}
