<?php
/**
 *
 *
 * User: avery
 * Date: 2019-07-21
 * Time: 18:39
 */
/*
| -------------------------------------------------------------------
| ARRAYS
| -------------------------------------------------------------------
|    全局数组函数
|
| Please see user guide for more info:
| http://codeigniter.org.cn/user_guide/helpers/array_helper.html
|
 */

$config = array(
	'upload_type' => array(
		UPLOAD_TYPE_BLOG => PATH_BLOG, //博客存放路径
		UPLOAD_TYPE_USER => PATH_USER, //用户存放路径
	),
	'blog_add_type' => array(
		BLOG_ADD_TYPE_MOBILE,
		BLOG_ADD_TYPE_PC
	),
	'blog_type' => array(1, 2, 3, 4, 5, 6, 7)
);
