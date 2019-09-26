<?php
$timeStr1 = date('Y/m/d,H:i:s', 1566297706);
$timeStr2 = date('Y/m/d,H:i:s', 1566384106);
$timeArr = array(
	$timeStr1,
	$timeStr2
);

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://cdn.bootcss.com/twitter-bootstrap/4.3.0/css/bootstrap.min.css"/>
	<script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/twitter-bootstrap/4.3.0/js/bootstrap.min.js"></script>

</head>
<body>
<div class="content"></div>

<?php $index =0; foreach ($timeArr as $lv) { $index ++;  ?>
  <div class="time_<?=$index?>" tag='goods' time="<?=$lv?>"></div>
<?php } ?>
</body>
<script type="text/javascript">
       $('div[tag="goods"]').each(function () {
			countDown($(this).attr('class'), $(this).attr('time'));
	   });

	function countDown(className,timeStr) {
		var start = new Date();  //开始时间
		var end = new Date(timeStr);//结束时间，可以设置时间
//parseInt()取整
		var result = parseInt((end.getTime() - start.getTime()) / 1000);//计算出豪秒
		var d = parseInt(result / (24 * 60 * 60));//用总共的秒数除以1天的秒数
		var h = parseInt(result / (60 * 60) % 24);//精确小时，用去余
		var m = parseInt(result / 60 % 60);//剩余分钟就是用1小时等于60分钟进行趣余
		var s = parseInt(result % 60);
		document.querySelector('.'+className).innerHTML = '距离过年回家还有' + d + '天' + h + '时' + m + '分' + s + '秒';

		setTimeout(function () {
			countDown(className,timeStr);
		}, 500);
	}

</script>
