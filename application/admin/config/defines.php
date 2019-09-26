<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array();
define('ADMIN_THEME', '/theme/admin'); //框架资源路径
define('PER_PAAGE', 10); //分页数量
define('PATH_UPLOAD','upload');//文件默认保存地址
define('PATH_DATA', 'data'); //文件上传跟路径
define('PATH_ADMIN', 'admin'); //后台文件地址
define('PATH_BLOG', 'blog'); //博客文件地址
define('BASE_FILE_URL','http://admin.blog.runsss.com/'); //图片域名
//define('BASE_FILE_URL','http://admin.blog.com/'); //图片域名

/**-------REDIS START-------*/
define('REDIS_SERVER', '127.0.0.1');
define('REDIS_PORT', 6379);
define('REDIS_TIME_OUT', 86400);
define('REDIS_PCONNECT', true);

define('REDIS_LOCK_NAMESPACE', 'ADMIN_REDIS_LOCK_');
/**-------REDIS END-------*/

