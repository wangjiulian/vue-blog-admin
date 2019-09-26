<div class="wrapper wrapper-content">
	<div class="row">
		<div class="ibox col-md-12">

			<div class="ibox-content">

				<div class="form-group row">
					<label class="col-sm-1 col-form-label">账号</label>
					<input type="text" name="account" id="account" placeholder="请输入账号" class="form-control col-sm-5"/>
				</div>
				<div class="form-group row">
					<label class="col-sm-1 col-form-label ">密码</label>
					<input type="password" name="password" id="password" style="margin-top: 10px;" placeholder="请输入密码"
						   class="form-control col-sm-5"" />
				</div>
				<div class="form-group row">
					<label class="col-sm-1 col-form-label">确认密码</label>
					<input type="password" id="confirm_password" name="confirm_password" style="margin-top: 10px;"
						   placeholder="请确认密码"
						   class="form-control col-sm-5" />
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-1"></label>
					<button type="submit" onclick="add();" id="submit" style="margin-top: 20px;"
							class="btn btn-success col-md-5">添加
					</button>
				</div>
			</div>
		</div>

	</div>
</div>
<script type="text/javascript">
	function add() {
		var account = $('#account').val();
		var password = $('#password').val();
		var confirmPassword = $('#confirm_password').val();

		if (isEmpty(account)) {
			showError('请输入账号');
			return false;
		}
		if (isEmpty(password)) {
			showError('请输入密码');
			return false;
		}

		if (isEmpty(confirmPassword)) {
			showError('请确认密码');
			return false;
		}
		if (confirmPassword != password) {
			showError('两次密码不一致');
			return false;
		}
		$.ajax({
			url: '/setting/index/account_add',
			method: 'post',
			dataType: 'json',
			data: {account: account, password: password},
			success: function (res) {
				if (res.re_st == 'success') {
					showSuccess(res.re_info);
					setTimeout(function () {
						window.location.href = res.url;
					}, 2000);
				} else {
					showError(res.re_info);
				}
			},
			error: function () {

			}
		});
	}


</script>
