<div class="wrapper  wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">

				<div class="ibox-content">
					<a href="/setting/index/account_add" style="float: right;margin-bottom: 20px;"
					   class="btn btn-sm btn-outline-success">添加账号</a>
					<table class="table table-striped table-bordered table-hover dataTables-example">
						<thead>
						<tr>
							<th width="100px;">序号</th>
							<th width="100px;">头像</th>
							<th width="20%;">账号</th>
							<th>昵称</th>
							<th width="120px;">操作</th>
						</tr>
						</thead>
						<tbody>
						<?php $index = 0;
						foreach ($list as $lv): ?>
							<tr class="gradeA">
								<td class="align-middle"><?= ++$index ?></td>
								<td class="align-middle"><img width="48px" height="48px" class="rounded-circle" src="<?php if(empty($lv['avatar'])) { echo ADMIN_THEME . '/img/profile_small.jpg';} else{echo BASE_FILE_URL . $lv['avatar'];}?>"></td>
								<td class="align-middle"><?= $lv['account'] ?></td>
								<td class="align-middle"><?= $lv['nick_name'] ?></td>
								<td class="align-middle">
									<a style="display: inline-block" class="btn btn-sm btn-success"
									   href="/setting/index/account_detail/<?= $lv['id'] ?>">编辑</a>
									<?php if ($lv['super'] != 1) { ?>
										<button style="display: inline-block" class="btn btn-sm btn-danger"
												onclick="if(confirm('您确定要删除吗')) del(<?= $lv['id'] ?>)">删除
										</button>
									<?php } ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="ibox-footer">
					<div><?=$page?></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function del(id) {

		$.ajax(
			{
				url: '/setting/index/account_del',
				method: 'post',
				data: {uid: id},
				dataType: 'json',
				success: function (res) {
					if (res.re_st == 'success') {
						showSuccess(res.re_info);
						setTimeout(function () {
							window.location.href = res.url;
						}, 1000);
					} else {
						showError(res.re_info);
					}
				},
				error: function (err) {
					showError(err);
				}
			}
		);
	}


</script>
