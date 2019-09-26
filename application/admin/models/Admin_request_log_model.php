<?php
/**
 *
 *
 * User: avery
 * Date: 2019-08-03
 * Time: 21:08
 */

class Admin_request_log_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = TABLE_ADMIN_REQUEST_LOG;
	}

	public function save($data){
		$this->insert($data);
	}

}
