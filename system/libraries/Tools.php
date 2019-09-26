<?php
/**
 *
 *
 * User: avery
 * Date: 2019-07-20
 * Time: 18:02
 */

class CI_Tools{
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	public function upload_photo($file_name,$path = PATH_UPLOAD){
		if (!file_exists($path)){
			mkdir($path,0777,true);
		}
		$config['upload_path'] = $path;
		$config['file_name'] = md5(time(). $_FILES[$file_name]['name']);
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size'] = 1024 * 10;
		$this->CI->load->library('upload', $config);
		if (!$this->CI->upload->do_upload($file_name)){
			$error = array('error' => $this->CI->upload->display_errors());
			var_dump($error);die();
			return "";
		}else{
			$res = $this->CI->upload->data();
			$data = array(
				'name' => $res['file_name'],
				'path' => $path . '/' . $res['file_name'],
				'ext' =>  pathinfo($res['file_name'], PATHINFO_EXTENSION),
				'size' => $res['file_size']
			);
			return $data;
		}
	}
}
