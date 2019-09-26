<?php
/**
 *
 *
 * User: avery
 * Date: 2019-07-21
 * Time: 11:06
 */

class Admin_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_ADMIN_USER;
	}

	/**
	 * 登录
	 *
	 * @access public
	 * @param mixed $account 账号
	 * @return
	 */
	public function login($account)
	{
		$user = $this->select_one('id,account,password,avatar,super,nick_name', array('account' => $account));
		return $user;
	}

	/**
	 * 编辑个人信息
	 *
	 * @access public
	 * @param mixed $uid 用户id
	 * @param mixed $nick_name 昵称
	 * @return
	 */
	public function edit_info($uid, $nick_name)
	{
		$fileRes = array();
		//是否上传文件
		if (isset($_FILES['avatar'])) {
			$this->load->library('tools');
			$fileRes = $this->tools->upload_photo('avatar', PATH_DATA . '/' . PATH_ADMIN);
			if (!$fileRes) {
				return false;
			}
		}
		$this->db->trans_start();
		if (!empty($fileRes)) {
			//保存文件路径
			$this->load->model('upload_model');
			//保存图片地址
			$this->upload_model->save_file($fileRes['name'], $fileRes['path'], $fileRes['ext'], $fileRes['size']);
		}

		//更新个人信息
		$upData = array(
			'nick_name' => $nick_name
		);
		if (!empty($fileRes)) {
			$upData['avatar'] = $fileRes['path'];
		}
		$this->update($upData, array('id' => $uid));
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return false;
		}
		//更新本地账号缓存
		$this->session->set_userdata('adm_nick_name', $upData['nick_name']);
		if (!empty($upData['avatar'])) {
			$this->session->set_userdata('adm_avatar', BASE_FILE_URL . $upData['avatar']);
		}
		return true;
	}


	/**
	 * 后台账号列表
	 *
	 * @access public
	 * @param mixed $offset 偏移
	 * @param mixed $perpage 分页数量
	 * @return
	 */
	public function get_account($offset, $perpage)
	{
		$total = $this->count();
		$list = $result = $this->select_limit('id,account,avatar,nick_name,super', $offset, $perpage);
		$res = array(
			'total' => $total,
			'list' => $list
		);
		return $res;
	}

	/**
	 * 账号详情
	 *
	 * @access public
	 * @param mixed $uid 用户id
	 * @return
	 */
	public function get_account_detail($uid)
	{
		return $result = $this->select_one('id,account,avatar,nick_name', array('id' => $uid));

	}

	/**
	 * 修改密码
	 *
	 * @access public
	 * @param mixed $uid
	 * @param mixed $password
	 * @return
	 */
	public function change_password($uid, $password)
	{
		$up_data = array(
			'password' => password_hash($password, PASSWORD_DEFAULT)
		);
		return $this->update($up_data, array('id' => $uid));
	}

	/**
	 * 添加账号
	 *
	 * @access public
	 * @param mixed
	 * @return
	 */
	public function account_add($account, $password)
	{
		//查询账号是否存在
		$user = $this->select_one('account', array('account' => $account));
		if (empty($user['account'])) {
			$inser_data = array(
				'account' => $account,
				'password' => password_hash($password, PASSWORD_DEFAULT)
			);
			$res = $this->insert($inser_data);
			if (empty($res)) {
				return -1;
			}
			return 1;
		}
		return 0;
	}

	/**
	 * 删除账号
	 *
	 * @access public
	 * @param mixed $uid 账号id
	 * @return
	 */
	public function account_del($uid){
		return $this->delete(array('id' => $uid));
	}

}
