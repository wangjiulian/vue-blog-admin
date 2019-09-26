<div class="wrapper-content wrapper">
	<div class="col-lg-12">
		<div class="row">

			<div class="ibox col-md-12">
				<div class="ibox-title"></div>
				<div class="ibox-content">
					<form id="edit_form" action="" method="post"  enctype="multipart/form-data" onsubmit="return check_info();">
						<?php
						if (!empty($avatar)){
							echo '<img class="rounded-circle" width="48px" height="48px" ' . 'src="' . $avatar .'">';
						}
						?>
						<div class="form-group row">
							<label class="col-sm-1 col-form-label">头像</label>
							<div class="col-sm-10 custom-file">

								<input id="logo" name="avatar" type="file" class="custom-file-input">
								<label for="logo" class="custom-file-label">选择头像</选择头像></label>
							</div>
						</div>
						<div class="hr-line-dashed"></div>
						<div class="form-group row">
							<label class="col-sm-1 col-form-label">昵称</label>
							<input name="nick_name"  value="<?=$nick_name?>" id="nick_name"  class="col-sm-10 form-control" placeholder="请输入昵称">
						</div>
						<button class="btn btn-success col-md-12" type="submit">保存</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="subinfo" style="display: none;"></div>
<!-- Mainly scripts -->
<script src="<?=ADMIN_THEME?>/js/jquery.form.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var options = {
			target: '#subinfo',
			beforeSubmit:  showRequest,
			success: showResponse,
			timeout: 3000
		};
		$('#edit_form').ajaxForm(options);
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
	$('.custom-file-input').on('change', function() {
		let fileName = $(this).val().split('\\').pop();
		$(this).next('.custom-file-label').addClass("selected").html(fileName);
	});
	function check_info() {
		var nick_name = $('#nick_name').val();
		if (isEmpty(nick_name)){
			showError('请填写昵称');
			return false;
		}
	}
</script>
