<?php

$config = array();

define('BASE_FILE_URL','http://api.blog.runsss.com/'); //图片域名
//define('BASE_FILE_URL', 'http://api.blog.com/'); //图片域名
define('UPLOAD_TYPE', 'upload_type'); //上传类型
define('PATH_UPLOAD', 'upload');//文件默认保存地址
define('PATH_DATA', 'data'); //文件上传根路径
define('PATH_BLOG', 'blog'); //博客文件存放目录
define('PATH_USER', 'user'); //用户文件存放目录
define('UPLOAD_TYPE_BLOG', 1); //上传博客文件
define('UPLOAD_TYPE_USER', 2); //上传用户文件
/**-------分页 START-------*/
define('PERPAGE_BLOG', 10); //博客分页
define('PERPAGE_USER_LIKE', 5); //用户评论
define('PERPAGE_USER_COMMENT', 5); //用户点赞
/**-------分页 END-------*/

/**-------REDIS START-------*/
define('REDIS_SERVER', '127.0.0.1');
define('REDIS_PORT', 6379);
define('REDIS_TIME_OUT', 86400);
define('REDIS_PCONNECT', true);

define('REDIS_LOCK_NAMESPACE', 'ADMIN_REDIS_LOCK_');
/**-------REDIS END-------*/

/**-------博客上传类型-------*/
define('BLOG_ADD_TYPE_MOBILE', 1); //手机上传
define('BLOG_ADD_TYPE_PC', 2); //PC上传



