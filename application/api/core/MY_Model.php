<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{

	protected $table;//表名

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 插入操作
	 *
	 * @access protected
	 * @param mixed $data 数据
	 * @return
	 */
	protected function insert($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	/**
	 * 批量插入操作
	 *
	 * @access protected
	 * @param mixed $data
	 * @return
	 */
	protected function insert_batch($data)
	{
		return $this->db->insert_batch($this->table, $data);
	}

	/**
	 * 查询数据
	 *
	 * @access protected
	 * @param mixed $column 查询列
	 * @param mixed $where  条件数组
	 * @return array
	 */
	protected function select($column, $where= array(1=>1)){
		return  $this->db->select($column)
			->where($where)
			->get($this->table)
			->result_array();
	}

	protected function select_limit($column, $offset, $perpage, $where = array(1=>1)){
		return $this->db->select($column)
			->where($where)
			->limit($perpage, $offset)
			->get($this->table)->result_array();
	}

	/**
	 * 查询一条数据
	 *
	 * @access protected
	 * @param mixed $column 查询列
	 * @param mixed $where  条件数组
	 * @return array
	 */
	protected function select_one($column, $where= array(1=>1)){
		return  $this->db->select($column)
			->where($where)
			->get($this->table)
			->row_array();
	}

	/**
	 * 更新操作
	 *
	 * @access protected
	 * @param mixed $data
	 * @param mixed $where
	 * @return
	 */
	protected function update($data, $where)
	{
		$rows = $this->db->set($data)->where($where)->update($this->table);
		return $rows > 0 ? true : false;
	}

	/**
	 * 批量更新
	 *
	 * @access protected
	 * @param mixed $data
	 * @param mixed $where
	 * @return
	 */
	protected function update_batch($data, $where)
	{
		$rows = $this->db->set($data)->where($where)->update_batch($this->table);
		return $rows > 0 ? true : false;
	}

	/**
	 * 查询数量
	 *
	 * @access protected
	 * @param mixed $where
	 * @return
	 */
	protected function count($where= array(1=>1)){
		return  $this->db->from($this->table)->where($where)->count_all_results();
	}

	/**
	 * 删除数据
	 *
	 * @access protected
	 * @param mixed $where
	 * @return
	 */
	protected function delete($where)
	{
		$rows = $this->db->where($where)->delete($this->table);
		return $rows > 0 ? true : false;
	}

}
