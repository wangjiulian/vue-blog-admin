<?php

class Blog extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('blog_model');
		$this->load->model('blog_comment_model');
		$this->load->model('blog_like_model');
	}

	/**
	 * @api {post} /blog/list_recommend_blog 推荐帖子列表
	 * @apiVersion 0.1.0
	 * @apiName list_recommend_blog
	 * @apiGroup BLOG
	 *
	 * @apiParam {search} search (选填)搜索内容
	 * @apiParam {Int} page (必填)页码默认为1
	 * @apiParam {Int} type (非必填)默认为首页 0 首页 1 文化 2 娱乐 3 生活 4 健康 5体育 6 财经 7实事
	 * @apiSuccessExample 请求成功:
	 * {
	 * "re_st": "success",
	 * "re_info": {
	 * "data_list": [{
	 * "id": "2",
	 * "read_num": "42",
	 * "uid": "2",
	 * "title": "认真做早餐就是向生活致敬",
	 * "content": "上周在下厨房美食APP看到第二届早餐马拉松活动，立即报名参加，活动口号是“坚持21天早餐打卡，养成自律习惯”，不禁莞尔一笑，早在去年我就参加两次早餐打卡活动，坚持不重样早餐持续两个月，还获得奖励。今年家里发生变故，老公得了重症，儿子明年高考，我不得不挑起家庭所有重担，一方面照顾老公吃喝拉撒，督促儿子好好学习，一方面想方设法恢复平静生活，抚平创伤。那就从做早餐开始吧，既然选择，就要热爱，既然热爱，就要坚持，坚持做早餐，利用晨起时间读书，让我懂得自律的重要性，又锻炼意志力，生活是需要仪式感的，能唤醒",
	 * "imgs": ["http:\/\/api.blog.runsss.com\/data\/blog\/2019-09-11\/a08afc3be80ba944ba29db84355c724f.jpg"],
	 * "create_time": "1568181557",
	 * "avatar": "http:\/\/api.blog.com\/data\/blog\/2019-09-10\/4c164e19f90b5daebe24715193e9f5d6.jpg",
	 * "nick_name": "你瞅啥",
	 * "comment_num": 0,
	 * "like_num": "1"
	 * }, {
	 * "id": "1",
	 * "read_num": "22",
	 * "uid": "1",
	 * "title": "《二十岁的年纪，做八十岁想起来都会嘴角上扬的事》",
	 * "content": "01、走很远很远的路每次去旅行，走路大概是必须的一件事。比起去那些到哪都在路上伸手拦出租车，然后和这个城市擦肩而过的方式，我还是更喜欢用自己的双脚去丈量这个世界。曾二十八天徒搭于广袤无垠的西藏，也穿越在神秘色彩的鳌太，还行走于美丽的大西北这片炙热的土地上。或者漫步在一个城市的街道上，感受一个城市和其它地方不一样的氛围。只有这样从容的方式才有机会撞见不同的人，去发现发生在身边意外的惊喜。用自己的脚步去行走，这样可以在想停下来的时候停下来，也可以兴奋的想要奔跑时也能随时狂欢。然后，我会把每一天走的步",
	 * "imgs": ["http:\/\/api.blog.runsss.com\/data\/blog\/2019-09-11\/db600a87ef7f5f34ad2b583d46ddb16a.jpg"],
	 * "create_time": "1568171262",
	 * "avatar": "http:\/\/api.blog.runsss.com\/data\/blog\/2019-09-11\/91d4fbdf773e7d82c9d90aba4aa1251b.gif",
	 * "nick_name": "逆流得渔",
	 * "comment_num": 2,
	 * "like_num": "3"
	 * }]
	 * }
	 * }
	 * @apiErrorExample 请求失败
	 * {
	 *    "re_st": "error",
	 *    "re_info": "请求失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/blog/list_recommend_blog
	 */
	public function list_recommend_blog()
	{

		$search = $this->input->post('search', true);
		$page = intval($this->input->post('page', true)); //页码
		$type = intval($this->input->post('type', true)); //1 文化 2 娱乐 3 生活 4 健康 5体育 6 财经 7实事
		if ($type < 0 || !in_array($type, $this->config->item('blog_type'))) {
			$type = 0;
		}
		$offset = $page > 0 ? ($page - 1) * PERPAGE_BLOG : 0; //偏移
		$res = $this->blog_model->get_list_recommend_blog($offset, PERPAGE_BLOG, $search, $type);
		if ($res) {
			$this->response_success(array('data_list' => $res));
		}
		$this->response_empty();
	}


	/**
	 * @api {post} /blog/detail_blog 帖子详情
	 * @apiVersion 0.1.0
	 * @apiName detail_blog
	 * @apiGroup BLOG
	 *
	 * @apiParam {String} uid (非必填)用户id
	 * @apiParam {String} blog_id (必填)博客id
	 * @apiSuccessExample 请求成功:
	 * {
	 * "re_st": "success",
	 * "re_info": {
	 * "id": "2", //帖子id
	 * "read_num": "44", //阅读数
	 * "uid": "2", //发布者id
	 * "title": "认真做早餐就是向生活致敬",
	 * "content": "<p xss=removed>上周在下厨房美食APP看到第二届早餐马拉松活动，立即报名参加，活动口号是“坚持21天早餐打卡，养成自律习惯”，不禁莞尔一笑，早在去年我就参加两次早餐打卡活动，坚持不重样早餐持续两个月，还获得奖励。<\/p><p xss=removed>今年家里发生变故，老公得了重症，儿子明年高考，我不得不挑起家庭所有重担，一方面照顾老公吃喝拉撒，督促儿子好好学习，一方面想方设法恢复平静生活，抚平创伤。<\/p><p xss=removed>那就从做早餐开始吧，既然选择，就要热爱，既然热爱，就要坚持，坚持做早餐，利用晨起时间读书，让我懂得自律的重要性，又锻炼意志力，生活是需要仪式感的，能唤醒我们对内心的尊重，因而去尊重生活。<\/p><p xss=removed>罗曼罗兰说过：世界上只有一种英雄主义，就是看清生活的真相之后依然热爱生活。<\/p><p xss=removed>回想那个大雪纷飞的傍晚，赶地铁回家给儿子做饭，由于走的太急不小心滑倒，冷冰冰的雪水打湿裤子，此时同济医科大学校园寂寥无人，我挣扎着爬起来继续赶路，在地铁里，暖气吹的热乎乎的，我仿佛站在万丈悬崖边上，一点点的堙没在人群里。<\/p><p xss=removed>除了自己，没人可以依靠，也没人可以拯救。<\/p><p xss=removed>既然无法选择生活带来的残酷，也无法预测明天和意外哪个先到，我唯一能做的，遇到困境，依然面带微笑，坦然接受。成年人的世界没有容易两字，有些痛苦，必须承担，有些磨难，必须经历。<\/p><p xss=removed>当家里生活勉强恢复到正常时，我制定了新的学习计划，老公身体欠佳需要养病，我采取食疗法和药物结合，饮食清淡营养，鼓励老公读书和健身，晚餐后陪他出去散步，一路上东拉西扯，老公打趣我成了“话痨”。儿子学业辛苦，早出晚归，青春期男孩子饭量大，在学校吃不好，早餐就是重中之重。<\/p><p xss=removed>朋友说，家里有人需要照顾，为什么还要花心思做早餐呢？这也是我写这篇文章的目的。<\/p><p xss=removed>曾经在一本书读到一句话：真正会生活的人都会有两副面孔，一副面对世界，一副面对自己。<\/p><p xss=removed>两幅面孔，都是我们对抗残酷世界的力量。<\/p><p xss=removed>工作中，做好本职工作，唯唯诺诺，没有自我表现机会，千篇一律，也就是戴着面具入世。生活中，做最好最真的自己，用兴趣爱好释放压力，展现个性的一面，活得更像我们自己。<\/p><p xss=removed>选择做美食，是选择一种生活方式而已，就像写作，既是生活方式，也是情感发泄。我计划今年完成30万字写作，目前看来无法实现，人的精力是有限的，加上工作和家务事占据三分之二时间，我不得不舍弃写作和练字两项，专心致志研究烘焙和做美食。<\/p><p xss=removed>美食，拥有世间最大的力量。<\/p><p xss=removed>事实证明，我这个选择是明智的，精致的早餐，让家里充满朝气，生机勃勃，一份不敷衍的早餐，是唤醒家人最温柔的方式，用心做出来的早餐，给予家人一天幸福，活力满满。<\/p><p xss=removed>有朋友问我每天要花多长时间做早餐？<\/p><p xss=removed>作为职业女性，我都是下班后购买食材，吃完晚餐就开始准备明天早餐的食材，洗洗切切，放在冰箱里冷藏。夏天5点天就亮了，我5点半起床，开始做早餐，时间大约在30分钟-40分钟左右。儿子六点40分出门，等儿子上学后，趁着老公还没起来，这个时间段用来读书，比如洗衣服洗碗时，打开下载好的听书APP，别小看这个习惯，每天坚持10分钟，知识量就会成倍增长。<\/p><p xss=removed>下面我把最近做的早餐图发出来：<\/p><p xss=removed><br><\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"2880\" data-height=\"4032\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-7dca779718cd3d09.jpg\" data-original-width=\"2880\" data-original-height=\"4032\" data-original-format=\"image\/jpeg\" data-original-filesize=\"3745865\" data-image-index=\"0\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-7dca779718cd3d09.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>水果三明治<\/div><\/div><p xss=removed>原味吐司三片，抹上番茄酱，夹煎蛋，火腿片，生菜叶，西红柿片，猕猴桃，火龙果切片摆盘。<\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"2720\" data-height=\"2176\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-085b26fbba472a53.jpg\" data-original-width=\"2720\" data-original-height=\"2176\" data-original-format=\"image\/jpeg\" data-original-filesize=\"3045988\" data-image-index=\"1\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-085b26fbba472a53.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>可颂三明治<\/div><\/div><p xss=removed>可颂切开，抹上花生酱，夹煎蛋，培根，生菜叶，西红柿片，猕猴桃摆盘。<\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"2176\" data-height=\"3046\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-ae3faadb63bf8ec5.jpg\" data-original-width=\"2176\" data-original-height=\"3046\" data-original-format=\"image\/jpeg\" data-original-filesize=\"2081970\" data-image-index=\"2\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-ae3faadb63bf8ec5.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>长寿面<\/div><\/div><p xss=removed>这是老公生日那天做的，高汤打底，配上四色蔬菜，熟鸡蛋摆盘。<\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"2176\" data-height=\"3264\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-59c2a781ed735c73.jpg\" data-original-width=\"2176\" data-original-height=\"3264\" data-original-format=\"image\/jpeg\" data-original-filesize=\"10166105\" data-image-index=\"3\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-59c2a781ed735c73.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>韩式煎蛋，水果<\/div><\/div><p xss=removed>煎饺在平底锅煎熟，搭配水果吃爽歪歪！<\/p><p xss=removed><br><\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"2176\" data-height=\"3264\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-da85c153fa6a6a4b.jpg\" data-original-width=\"2176\" data-original-height=\"3264\" data-original-format=\"image\/jpeg\" data-original-filesize=\"7624132\" data-image-index=\"4\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-da85c153fa6a6a4b.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>意式番茄肉酱面<\/div><\/div><p xss=removed>肉酱选7：3比例五花肉沫，锅里加热放黄油融化，炒肉沫，加洋葱碎，口蘑片，番茄丁快速炒，加番茄酱，出锅前撒罗勒碎调味。<\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"3264\" data-height=\"2176\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-ae3725307fd9e050.jpg\" data-original-width=\"3264\" data-original-height=\"2176\" data-original-format=\"image\/jpeg\" data-original-filesize=\"9813362\" data-image-index=\"5\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-ae3725307fd9e050.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>吐司披萨<\/div><\/div><p xss=removed>黑芝麻吐司，抹上番茄酱，红椒丁，青椒丁，火腿片，芝士碎，烤箱180°20分钟。<\/p><p xss=removed><br><\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"2688\" data-height=\"4032\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-e7888155dc68fd4a.jpg\" data-original-width=\"2688\" data-original-height=\"4032\" data-original-format=\"image\/jpeg\" data-original-filesize=\"2570424\" data-image-index=\"6\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-e7888155dc68fd4a.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>水果吐司，水果椰汁西米露<\/div><\/div><p xss=removed><br><\/p><p xss=removed>全麦吐司，抹上沙拉酱，水果铺好。<\/p><p xss=removed><br><\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"2448\" data-height=\"3060\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-3001daf07e816276.jpg\" data-original-width=\"2448\" data-original-height=\"3060\" data-original-format=\"image\/jpeg\" data-original-filesize=\"3149675\" data-image-index=\"7\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-3001daf07e816276.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>原味吐司<\/div><\/div><p xss=removed>原味吐司一片，抹上番茄酱，煎蛋，培根，生菜叶，猕猴桃，西瓜汁。<\/p><p xss=removed><br><\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"2448\" data-height=\"3264\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-bf561aba89d6c515.jpg\" data-original-width=\"2448\" data-original-height=\"3264\" data-original-format=\"image\/jpeg\" data-original-filesize=\"8957599\" data-image-index=\"8\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-bf561aba89d6c515.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>老北京炸酱面<\/div><\/div><p xss=removed>炸酱用黄豆酱和甜面酱按照3：1比例调制，肉沫与炸酱洋葱碎炒好，面条煮熟捞起，舀炸酱，黄瓜丝，绿豆芽，胡萝卜丝码在面条上。<\/p><p xss=removed><br><\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"2688\" data-height=\"4032\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-2bcbd82b620acfb0.jpg\" data-original-width=\"2688\" data-original-height=\"4032\" data-original-format=\"image\/jpeg\" data-original-filesize=\"1911071\" data-image-index=\"9\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-2bcbd82b620acfb0.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>原味戚风蛋糕<\/div><\/div><p xss=removed><br><\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"2880\" data-height=\"4032\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-ab6665e8bc9d375d.jpg\" data-original-width=\"2880\" data-original-height=\"4032\" data-original-format=\"image\/jpeg\" data-original-filesize=\"2192509\" data-image-index=\"10\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-ab6665e8bc9d375d.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>菠萝包<\/div><\/div><p xss=removed><br><\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"3024\" data-height=\"3780\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-c8e3b5928a84cde9.jpg\" data-original-width=\"3024\" data-original-height=\"3780\" data-original-format=\"image\/jpeg\" data-original-filesize=\"3190768\" data-image-index=\"11\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-c8e3b5928a84cde9.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>紫薯蛋黄酥<\/div><\/div><p xss=removed><br><\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"3024\" data-height=\"1701\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-b36499fad8804667.jpg\" data-original-width=\"3024\" data-original-height=\"1701\" data-original-format=\"image\/jpeg\" data-original-filesize=\"1898602\" data-image-index=\"12\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-b36499fad8804667.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>切开紫薯蛋黄酥<\/div><\/div><p xss=removed><br><\/p><div class=\"image-package\" xss=removed><div class=\"image-container\" xss=removed><div class=\"image-container-fill\" xss=removed><\/div><div class=\"image-view\" data-width=\"2688\" data-height=\"4032\" xss=removed><img data-original-src=\"\/\/upload-images.jianshu.io\/upload_images\/5412749-1130bfab049b6625.jpg\" data-original-width=\"2688\" data-original-height=\"4032\" data-original-format=\"image\/jpeg\" data-original-filesize=\"3232024\" data-image-index=\"13\" class=\"\" src=\"https:\/\/upload-images.jianshu.io\/upload_images\/5412749-1130bfab049b6625.jpg?imageMogr2\/auto-orient\/strip|imageView2\/2\/w\/1200\/format\/webp\" xss=removed><\/div><\/div><div class=\"image-caption\" xss=removed>葡式蛋挞<\/div><\/div><p xss=removed>我的早餐有牛奶、酸奶、鸡蛋、肉制品、沙拉菜、水果、面包、坚果，中式早餐和西式早餐混搭着吃，怎么健康营养就怎么吃。以上图片均为原创，包括蛋糕，菠萝包，蛋黄酥都是自己做的，既可以当早餐，也可以当下午茶点。<br><\/p><p xss=removed>认真做早餐，让我的生活变得更有意义，好好吃饭，善待自己和家人的身体健康，是向生活致敬最好的方式。<\/p><p xss=removed>蒋勋老师说过一句话：生命怎么活都会有遗憾，关键在于你怎么去领悟，给这个遗憾的部分，更崇高的向往，然后尊重、包容它，反而让这个遗憾的部分变成一种生命力的圆满。生活本身就不完美，而知道怎样与这个“不完美”和谐共舞，才是生活的强者、自己命运的主宰者。<\/p><p xss=removed>愿我们好好吃饭，活得自由认真！<\/p><p xss=removed>愿我们历尽千帆，归来仍是少年！<\/p>",
	 * "imgs": ["http:\/\/api.blog.runsss.com\/data\/blog\/2019-09-11\/a08afc3be80ba944ba29db84355c724f.jpg"],
	 * "create_time": "1568181557",
	 * "avatar": "http:\/\/api.blog.com\/data\/blog\/2019-09-10\/4c164e19f90b5daebe24715193e9f5d6.jpg", //发布者头像
	 * "nick_name": "你瞅啥",//发布者昵称
	 * "comment_num": 0, //评论数
	 * "like_num": "1", //点赞数
	 * "like": "2" //1 已点赞 2 未点赞
	 * }
	 * }
	 * @apiErrorExample 请求失败
	 * {
	 *    "re_st": "error",
	 *    "re_info": "请求失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/blog/detail_blog
	 */
	public function detail_blog()
	{
		$uid = intval($this->input->post('uid', true));
		$blog_id = intval($this->input->post('blog_id', true)); //博客id
		if ($blog_id < 1) {
			$this->response_params_error();
		}
		$res = $this->blog_model->get_blog_detail($blog_id, $uid);
		$this->blog_model->add_blog_read($blog_id); //添加阅读量
		if ($res) {
			$this->response_success($res);
		}
		$this->response_error('获取失败,帖子不存在');
	}

	/**
	 * @api {post} /blog/add_blog 添加帖子
	 * @apiVersion 0.1.0
	 * @apiName add_blog
	 * @apiGroup BLOG
	 *
	 * @apiParam {String} uid  (必填)用户id
	 * @apiParam {String} token (必填)token
	 * @apiParam {String} title (必填)标题
	 * @apiParam {String} content (必填)内容
	 * @apiParam {String} cover (必填)封面
	 * @apiParam {String} imgs (手机端必填)多个图片地址按逗号拼接
	 * @apiParam {Int} blog_type (必填) 1 文化 2 娱乐 3 生活 4 健康 5体育 6 财经 7实事
	 * @apiParam {Int} type (必填)1 手机 2 PC
	 * @apiSuccessExample 请求成功:
	 * {
	 *    "re_st": "success",
	 *    "re_info": "发布成功"
	 * }
	 *
	 * @apiErrorExample 请求失败
	 * {
	 *    "re_st": "error",
	 *    "re_info": "请求失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/blog/add_blog
	 */
	public function add_blog()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		$title = $this->input->post('title', true);
		$cover = $this->input->post('cover', true);
		$content = $this->input->post('content', true);
		$type = intval($this->input->post('type', true));
		$blog_type = intval($this->input->post('blog_type', true));

		if (!in_array($blog_type, $this->config->item('blog_type')) || $uid < 1 || empty($token) || empty($title) || empty($content) || !in_array($type, $this->config->item('blog_add_type')) || empty($cover)) {
			$this->response_params_error();
		}
		$imgs = '';
		if ($type == BLOG_ADD_TYPE_MOBILE) {
			//手机上传
			$imgs = $this->input->post('imgs', true);
			if (empty($imgs)) {
				$this->response_error('请上传图片');
			}
			$imgs = $cover . ',' . $imgs;
		}
		if ($type == BLOG_ADD_TYPE_PC) {
			preg_match_all('/<img src="(.*?)"/', $content, $img_arr);
			if (!empty($img_arr[0])) {
				$imgs = implode(',', $img_arr[1]);
			}
			if (!empty($imgs)) {
				$imgs = $cover . ',' . $imgs;
			}
			$imgs = empty($imgs) ? $cover : ($cover . ',' . $imgs);
		}

		$this->check_token();
		$res = $this->blog_model->add_blog($uid, $title, $content, $imgs,$blog_type);
		if ($res) {
			$this->response_success('发布成功');
		}
		$this->reponse_error('发布失败');

	}

	/**
	 * @api {post} /blog/add_blog_comment 添加评论
	 * @apiVersion 0.1.0
	 * @apiName add_blog_comment
	 * @apiGroup BLOG
	 *
	 * @apiParam {String} uid  (必填)用户id
	 * @apiParam {String} token (必填)token
	 * @apiParam {String} blog_id (必填)博客id
	 * @apiParam {String} content (必填)评论内容
	 * @apiSuccessExample 请求成功:
	 * {
	 *    "re_st": "success",
	 *    "re_info": "1" //评论id
	 * }
	 *
	 * @apiErrorExample 请求失败
	 * {
	 *    "re_st": "error",
	 *    "re_info": "请求失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/blog/add_blog_comment
	 */
	public function add_blog_comment()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		$blog_id = intval($this->input->post('blog_id', true));
		$content = $this->input->post('content', true);
		if ($uid < 1 || $blog_id < 1 || empty($token) || empty($content)) {
			$this->response_success('参数不足');
		}
		$this->check_token();
//		$blog = $this->blog_model->get_blog_detail($blog_id);
//		if (empty($blog)) {
//			$this->response_success('评论失败,帖子不存在');
//		}

		$res = $this->blog_comment_model->add_blog_comment($uid, $blog_id, $content);
		if ($res) {
			$this->response_success($res);
		}
		$this->response_success('评论失败');

	}

	/**
	 * @api {post} /blog/like_blog 点赞
	 * @apiVersion 0.1.0
	 * @apiName like_blog
	 * @apiGroup BLOG
	 *
	 * @apiParam {String} uid  (必填)用户id
	 * @apiParam {String} token (必填)token
	 * @apiParam {String} blog_id (必填)博客id
	 * @apiSuccessExample 请求成功:
	 * {
	 *    "re_st": "success",
	 *    "re_info": "点赞成功"
	 * }
	 *
	 * @apiErrorExample 请求失败
	 * {
	 *    "re_st": "error",
	 *    "re_info": "点赞失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/blog/like_blog
	 */
	public function like_blog()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		$blog_id = intval($this->input->post('blog_id', true));
		if ($uid < 1 || $blog_id < 1 || empty($token)) {
			$this->response_success('参数不足');
		}
		$this->check_token();
//		$blog = $this->blog_model->get_blog_detail($blog_id);
//		if (empty($blog)) {
//			$this->response_success('点赞失败,帖子不存在');
//		}
		$res = $this->blog_like_model->like_blog($uid, $blog_id);
		if ($res) {
			$this->response_success('点赞成功');
		}
		$this->response_error('点赞失败');
	}

	/**
	 * @api {post} /blog/unlike_blog 取消点赞
	 * @apiVersion 0.1.0
	 * @apiName unlike_blog
	 * @apiGroup BLOG
	 *
	 * @apiParam {String} uid  (必填)用户id
	 * @apiParam {String} token (必填)token
	 * @apiParam {String} blog_id (必填)博客id
	 * @apiSuccessExample 请求成功:
	 * {
	 *    "re_st": "success",
	 *    "re_info": "取消点赞成功"
	 * }
	 *
	 * @apiErrorExample 请求失败
	 * {
	 *    "re_st": "error",
	 *    "re_info": "取消点赞失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/blog/unlike_blog
	 */
	public function unlike_blog()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		$blog_id = intval($this->input->post('blog_id', true));
		if ($uid < 1 || $blog_id < 1 || empty($token)) {
			$this->response_success('参数不足');
		}
		$this->check_token();
//		$blog = $this->blog_model->get_blog_detail($blog_id);
//		if (empty($blog)) {
//			$this->response_success('取消点赞失败,帖子不存在');
//		}
		$res = $this->blog_like_model->unlike_blog($uid, $blog_id);
		if ($res) {
			$this->response_success('取消点赞成功');
		}
		$this->response_error('取消点赞失败');
	}

	/**
	 * @api {post} /blog/list_comment 评论列表
	 * @apiVersion 0.1.0
	 * @apiName unlike_blog
	 * @apiGroup BLOG
	 * @apiParam {String} blog_id (必填)博客id
	 * @apiSuccessExample 请求成功:
	 * {
	 * "re_st": "success",
	 * "re_info": {
	 * "data_list": [
	 * {
	 * "id": "1",
	 * "blog_id": "2",
	 * "uid": "1",
	 * "content": "2122121",
	 * "comment_time": "1567136405",
	 * "nick_name": "小米",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg"
	 * },
	 * {
	 * "id": "2",
	 * "blog_id": "2",
	 * "uid": "1",
	 * "content": "2121",
	 * "comment_time": "1567136445",
	 * "nick_name": "小米",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg"
	 * },
	 * {
	 * "id": "3",
	 * "blog_id": "2",
	 * "uid": "1",
	 * "content": "2122121",
	 * "comment_time": "1567136501",
	 * "nick_name": "小米",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg"
	 * },
	 * {
	 * "id": "4",
	 * "blog_id": "2",
	 * "uid": "1",
	 * "content": "212212121212",
	 * "comment_time": "1567136505",
	 * "nick_name": "小米",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg"
	 * },
	 * {
	 * "id": "5",
	 * "blog_id": "2",
	 * "uid": "1",
	 * "content": "21221",
	 * "comment_time": "1567137008",
	 * "nick_name": "小米",
	 * "avatar": "http://api.blog.com/theme/admin/img/a7.jpg"
	 * }
	 * ]
	 * }
	 * }
	 * @apiErrorExample 请求失败
	 * {
	 *    "re_st": "error",
	 *    "re_info": "取消点赞失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/blog/unlike_blog
	 */
	public function list_comment()
	{
		$uid = intval($this->input->post('uid', true));
		$blog_id = intval($this->input->post('blog_id', true));
		if ($blog_id < 1) {
			$this->response_params_error();
		}
		$offset = 0;
		$this->load->model('blog_comment_model');
		$res = $this->blog_comment_model->get_comment_list($blog_id, $offset, PERPAGE_BLOG);
		if ($res) {
			$this->response_datalist($res);
		}
		$this->response_empty();
	}

	/**
	 * @api {post} /blog/add_comment_reply 添加评论回复
	 * @apiVersion 0.1.0
	 * @apiName add_comment_reply
	 * @apiGroup BLOG
	 * @apiParam {Int} uid (必填)uid
	 * @apiParam {String} token (必填)token
	 * @apiParam {String} comment_id (必填)评论id
	 * @apiParam {String} content (必填)评论内容
	 * @apiSuccessExample 请求成功:
	 * {
	 * "re_st": "success",
	 * "re_info": "11" //回复id
	 * }
	 * }
	 * @apiErrorExample 请求失败
	 * {
	 *    "re_st": "error",
	 *    "re_info": "回复失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/blog/add_comment_reply
	 */
	public function add_comment_reply()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		$comment_id = intval($this->input->post('comment_id', true));
		$content = $this->input->post('content', true);
		if ($comment_id < 1 || $uid < 1 || empty($content) || empty($token)) {
			$this->response_params_error();
		}
		$this->check_token();
		$this->load->model('blog_comment_reply_model');
		$res = $this->blog_comment_reply_model->add_comment_reply($comment_id, $uid, $content);
		if ($res) {
			$this->response_success($res);
		}
		$this->response_error('回复失败');
	}

	/**
	 * @api {post} /blog/add_reply_comment 添加回复评论
	 * @apiVersion 0.1.0
	 * @apiName add_reply_comment
	 * @apiGroup BLOG
	 * @apiParam {Int} uid (必填)uid
	 * @apiParam {String} token (必填)token
	 * @apiParam {String} comment_id (必填)评论id
	 * @apiParam {String} reply_id (必填)回复id
	 * @apiParam {String} content (必填)评论内容
	 * @apiSuccessExample 请求成功:
	 * {
	 * "re_st": "success",
	 * "re_info": "11" //回复id
	 * }
	 * }
	 * @apiErrorExample 请求失败
	 * {
	 *    "re_st": "error",
	 *    "re_info": "回复失败"
	 * }
	 *
	 * @apiSampleRequest http://api.blog.runsss.com/blog/add_reply_comment
	 */
	public function add_reply_comment()
	{
		$uid = intval($this->input->post('uid', true));
		$token = $this->input->post('token', true);
		$comment_id = intval($this->input->post('comment_id', true));
		$reply_id = intval($this->input->post('reply_id', true));
		$content = $this->input->post('content', true);
		if ($reply_id < 1 || $comment_id < 1 || $uid < 1 || empty($content) || empty($token)) {
			$this->response_params_error();
		}
		$this->check_token();
		$this->load->model('blog_comment_reply_model');
		$res = $this->blog_comment_reply_model->add_reply_comment($comment_id, $reply_id, $uid, $content);
		if ($res) {
			$this->response_success($res);
		}
		$this->response_error('回复失败');
	}

}
