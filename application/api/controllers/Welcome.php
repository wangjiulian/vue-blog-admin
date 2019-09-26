<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Faker\Factory;
use Hashids\Hashids;

class Welcome extends CI_Controller
{


	public function sendEmail(){
		$this->load->library('email');

		$this->email->from('avery.wang@gooddr.com', 'avery1');
		$this->email->to('1217334266@qq.com');
//		$this->email->cc('another@another-example.com');
//		$this->email->bcc('them@their-example.com');

		$this->email->subject('验证码');
		$this->email->message('<p>您得验证码是<strong>13456</strong></p>');

		$this->email->send();
		echo $this->email->print_debugger();
	}

	private function http_curl($url, $type = 'get', $res = 'json', $arr = '')
	{
		//1.初始化curl
		$ch = curl_init();
		//2.设置curl的参数
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11');
		if ($type == 'post') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
		}
		//3.采集
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output, true);
		//4.关闭

	}

	public function add_user()
	{

		$this->load->model('user_model');


		$faker = Faker\Factory::create('zh_CN');
		for ($i = 0; $i < 10000; $i++) {
			$this->user_model->register($faker->phoneNumber, '123456', $faker->name);
		}
		echo 'ok';
	}


	public function show_img(){
		$url = $_SERVER['DOCUMENT_ROOT'] . '/data/blog/2019-09-11/a08afc3be80ba944ba29db84355c724f.jpg';
//		$img = file_get_contents($url,true);
		$handle = fopen($url,'r');
		$res = fread($handle, filesize($url));
		fclose($handle);
//		$res = file_get_contents('http://api.blog.runsss.com/data/blog/2019-09-11/a08afc3be80ba944ba29db84355c724f.jpg',true);

		header('Content-type:image/jpg');
		echo $res;


	}

	public function index()
	{


//
//		echo $faker->phoneNumber;


//
//		$verify = array(
//			'user' => 'drsay',
//			'password' => password_hash('212112211212', PASSWORD_DEFAULT) //替换成password_hash返回的结果
//		);
//
//		if (!isset($_SERVER['PHP_AUTH_USER'])) {
//			header('WWW-Authenticate: Basic realm="查看信息"');
//			header('HTTP/1.0 401 Unauthorized');
//			echo '请输入验证信息';
//			exit;
//		} else {
//			if (strcmp($_SERVER['PHP_AUTH_USER'], $verify['user']) != 0 || !password_verify($_SERVER['PHP_AUTH_PW'], $verify['password'])) {
//				header('WWW-Authenticate: Basic realm="查看信息"');
//				header('HTTP/1.0 401 Unauthorized');
//				echo '请输入正确的验证信息';
//				exit;
//			}

//		}
//		phpinfo();die();
//
//		$hashids = new Hashids('eclin',10);
//		$e =  $hashids->encode(array('1','12'));
//		echo $e;
//		var_dump($hashids->decode($e));
//		echo $hashids->decode($e)[0];
//		echo $hashids->decode($e)[1];
//		echo 'hello world';
//	$this->load->view('welcome_message');
	}

	public function testRedis()
	{
		$key = 'id';
		if (Redis_lock::getInstance()->lockNoWait($key) !== true) {
			echo 'lock faild';
		} else {
			echo 'lock succes';
			sleep(10);

		}
	}
}
