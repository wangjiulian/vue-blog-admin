<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>INSPINIA | Login</title>

	<link href="<?=ADMIN_THEME?>/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?=ADMIN_THEME?>/font-awesome/css/font-awesome.css" rel="stylesheet">

	<link href="<?=ADMIN_THEME?>/css/animate.css" rel="stylesheet">
	<link href="<?=ADMIN_THEME?>/css/style.css" rel="stylesheet">
	<link href="<?=ADMIN_THEME?>/css/plugins/toastr/toastr.min.css" rel="stylesheet">

</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen animated fadeInDown">
	<div>
		<div>
			<h1 class="logo-name"></h1>
		</div>
		<h3>博客系统</h3>

		<form id="login_form" class="m-t" method="post" role="form" action="">
			<div class="form-group">
				<input type="text" name="account" class="form-control" placeholder="账号" required="">
			</div>
			<div class="form-group">
				<input type="password" name="password" class="form-control" placeholder="密码" required="">
			</div>
			<button type="submit" class="btn btn-primary block full-width m-b">Login</button>

<!--			<a href="#"><small>Forgot password?</small></a>-->
<!--			<p class="text-muted text-center"><small>Do not have an account?</small></p>-->
<!--			<a class="btn btn-sm btn-white btn-block" href="register.html">Create an account</a>-->
		</form>
<!--		<p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p>-->
	</div>
</div>

<!-- Mainly scripts -->
<script src="<?=ADMIN_THEME?>/js/jquery-3.1.1.min.js"></script>
<script src="<?=ADMIN_THEME?>/js/jquery.form.min.js"></script>
<script src="<?=ADMIN_THEME?>/js/popper.min.js"></script>
<script src="<?=ADMIN_THEME?>/js/bootstrap.js"></script>
<script src="<?=ADMIN_THEME?>/js/plugins/toastr/toastr.min.js"></script>
<div id="subinfo" style="display: none;"></div>
<script type="text/javascript">
	$(document).ready(function() {
		var options = {
			target: '#subinfo',
			beforeSubmit:  showRequest,
			success: showResponse,
			timeout: 3000
		};
		$('#login_form').ajaxForm(options);
	});
	// pre-submit callback
	function showRequest(formData, jqForm, options) {


	}
	function showResponse(responseText, statusText, xhr, $form) {
		if (statusText == 'success') {
			if (typeof(responseText) == 'object') {
				var res = responseText;
			} else {
				var res = $.parseJSON(responseText);
			}

			if (res.re_st == 'success') {
				toastr.success(res.re_info);
				setTimeout('window.location="'+res.url+'"',1000);
			} else {
				toastr.error(res.re_info);
			}
		}
	}
</script>

</body>



</html>
