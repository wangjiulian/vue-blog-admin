<?php
/**
 *
 *
 * User: avery
 * Date: 2019-07-21
 * Time: 16:42
 */

class Upload_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_FILE_UPLOAD;
	}

	/**
	 * 保存文件
	 *
	 * @access public
	 * @param mixed $type 文件类型
	 * @param mixed $name 文件名称
	 * @param mixed $path 文件路径
	 * @param mixed $ext 文件后缀
	 * @param mixed $size 文件大小
	 * @return
	 */
	public function save_file($type, $name, $path, $ext, $size){
		$data = array(
			'type' => $type,
			'name' => $name,
			'path' => $path,
			'ext' => $ext,
			'size' => $size
		);
		return $this->insert($data);
	}

}
