<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/*
|--------------------------------------------------------------------------
| 分页配制
|---------------------------------------------------------------
 */
$config['num_links'] = 5;

// 封装标签
$config['full_tag_open'] = "<ul class=\"pagination pagination-sm\">";
$config['full_tag_close'] = "</ul>";

// 第一页
$config['first_tag_open'] = '<li>';
$config['first_tag_close'] = '</li>';

// 最后一页
$config['last_tag_open'] = '<li>';
$config['last_tag_close'] = '</li>';

// 当前页
$config['cur_tag_open'] = '<li class="active"><a href="#">';
$config['cur_tag_close'] = '</a></li>';

// 数字页
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';

// 下一页
$config['next_tag_open'] = '<li>';
$config['next_tag_close'] = '</li>';

// 上一页
$config['prev_tag_open'] = '<li>';
$config['prev_tag_close'] = '</li>';

# 翻页说明文字
$config['first_link'] = "第一页";
$config['last_link'] = "最后一页";
$config['prev_link'] = "&lt&lt";
$config['next_link'] = "&gt&gt";

/* End of file pagination.php */
/* Location: ./application/manage/config/pagination.php */
