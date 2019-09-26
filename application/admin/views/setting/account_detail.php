<div class="wrapper-content">
	<div class="row">
		<div class="ibox col-md-12">
			<div class="ibox-content">
				<div class="form-group row">
					<label class="col-sm-1 col-form-label">账号</label>
					<input  id="account" readonly type="text" class="col-sm-10 form-control"  value="<?=$detail['account']?>" class="form-control">
				</div>
				<div class="form-group row">
					<label class="col-sm-1 col-form-label">密码</label>
					<input name="nick_name"  type="password" id="fir_pwd"  class="col-sm-10 form-control" placeholder="请输入密码">
				</div>
				<div class="form-group row">
					<label class="col-sm-1 col-form-label">密码确认</label>
					<input name="nick_name"  type="password" id="sec_pwd"  class="col-sm-10 form-control" placeholder="请确认密码">
				</div>
			</div>



		</div>


		<div>
			<button onclick='edit("<?=$detail['id']?>")' type="text" class="btn btn-w-m btn-success">修改</button>
		</div>
	</div>
</div>
<script type="text/javascript">

	function edit(id) {
		var fir_pwd = $('#fir_pwd').val();
		var sec_pwd = $('#sec_pwd').val();
		var account = $('#account').val();
		if(isEmpty(fir_pwd)){
			showError('请输入密码');
			return false;
		}
		if(isEmpty(sec_pwd)){
			showError('请确认密码');
			return false;
		}
		if (fir_pwd != sec_pwd){
			showError('两次密码不一致');
			return false;
		}

		$.ajax({
			url:'/setting/index/account_edit',
			method:'post',
			dataType:'json',
			data:{id:id,password:fir_pwd},
			success:function (res) {
				if (res.re_st == 'success'){
					showSuccess(res.re_info);
					setTimeout(function () {
						window.location.href = res.url;
					},1000);
				}else {
					showError(res.re_info);
				}
			},
			error:function (err) {
				alert(err);
			}
		});
	}

</script>
