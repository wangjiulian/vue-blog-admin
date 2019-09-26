<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	public  function index(){
		$data = array(
			'title' => '首页'
		);
		$this->load->view('header',$data);
		$this->load->view('navbar', $data);
		$this->load->view('index');
		$this->load->view('footer');
	}
}
