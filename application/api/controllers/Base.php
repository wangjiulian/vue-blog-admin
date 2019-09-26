<?php
/**
 * 基础
 *
 * User: avery
 * Date: 2019-07-17
 * Time: 09:18
 */

class Base extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		echo 'hello world';
	}

	/**
	 * @api {post} /base/upload_file 上传文件
	 * @apiVersion 0.1.0
	 * @apiName upload_file
	 * @apiGroup BASE
	 *
	 * @apiParam {Int} type (必填)类型 1 博客文件 2 用户文件
	 * @apiParam {File} file (必填)文件
	 * @apiSuccessExample 请求成功:
	 * {
	 *    "re_st": "success",
	 *    "re_info": "http://api.blog.com/data/blog/2019-07-21/4cc71fa3db2acaf3f40c7b1136b15923.png" //文件路径
	 * }
	 * @apiErrorExample 请求失败
	 * {
	 *    "re_st": "error",
	 *    "re_info": "上传失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/base/upload_file
	 */
	public function upload_file()
	{
		$type = intval($this->input->post('type', true));
		if ($type < 1) {
			$this->response_error('请上传type类型');
		}
		$type_array = array_keys($this->config->item(UPLOAD_TYPE));
		if (!in_array($type, $type_array)) {
			$this->response_error('不支持该type类型');
		}
		if (!isset($_FILES['file'])) {
			$this->response_error('请上传文件');
		}
		$this->load->library('tools');
		$this->load->model('upload_model');
		$save_path = $this->get_blog_save_path($type);//保存路径
		$save_data = $this->tools->upload_photo('file', $save_path);
		if ($save_data) {
			//保存上传数据
			$save_res = $this->upload_model->save_file($type, $save_data['name'], $save_data['path'], $save_data['ext'], $save_data['size']);
			if ($save_res) {
				$this->response_success(BASE_FILE_URL . $save_data['path']);
			}
			$this->response_error('上传失败');
		}
		$this->response_error('上传失败');
	}


	//获取文件
	private function get_blog_save_path($type)
	{
		$type_path = $this->config->item(UPLOAD_TYPE)[$type];
		$path = PATH_DATA . '/' . $type_path . '/' . date('Y-m-d');
		return $path;
	}


}
