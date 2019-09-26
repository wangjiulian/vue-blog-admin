<?php
$segment = intval($this->uri->segment(4)) > 0 ? intval($this->uri->segment(4)) : 0;
$default_cover = ADMIN_THEME . '/img/p5.jpg'

?>
<div class="wrapper  wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-content">
					<a href="/blog/index/blog_add" style="float: right;margin-bottom: 20px;"
					   class="btn btn-sm btn-outline-success">添加文章</a>
					<table class="table table-striped table-bordered table-hover dataTables-example">
						<thead>
						<tr>
							<th>序号</th>
							<th>封面</th>
							<th>标题</th>
							<!--							<th>内容</th>-->
							<th>评论数</th>
							<th>点赞数</th>
							<th>推荐</th>
							<th>发布时间</th>
						</tr>
						</thead>
						<tbody>
						<?php $index = ($segment > 0 ? ($segment - 1) * PER_PAAGE : 0);
						foreach ($list as $lv): ?>
							<tr class="gradeA">
								<td><?= ++$index ?></td>
								<td><img width="80px" height="80px" style="object-fit: cover;"
										 src="<?php if (empty($lv['imgs'])) {
											 echo $default_cover;
										 } else {
											 echo $lv['imgs'][0];
										 } ?>"></td>
								<td><a href="/blog/index/blog_edit/<?=$lv['id']?>"><?= $lv['title'] ?></a></td>
								<!--								<td>--><? //= $lv['content'] ?><!--</td>-->
								<td><?= $lv['comment_num'] ?></td>
								<td><?= $lv['like_num'] ?></td>
								<td><label> <input tag="<?= $lv['id'] ?>"
												   type="checkbox" <?php if ($lv['hot'] == 1) echo 'checked = checked ' ?>
												   class="i-checks"><span style="margin-left: 10px;"></span></label>
								</td>
								<td><?= date('Y-m-d H:i:s', $lv['create_time']) ?></td>
								<!--								<td><a style="display: inline-block" class="btn btn-sm btn-success" href="/setting/index/account_detail/-->
								<? //=$lv['id']?><!--">编辑</a>-->
								<!--									<button style="display: inline-block" class="btn btn-sm btn-danger">删除</button>-->
								<!--								</td>-->
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="ibox-footer">
					<div><?= $page ?></div>
				</div>
			</div>

		</div>
	</div>
</div>
<link href="<?= ADMIN_THEME ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
<!-- iCheck -->
<script src="<?= ADMIN_THEME ?>/js/plugins/iCheck/icheck.min.js"></script>
<script>
	$(document).ready(function () {
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});
	});
	//方法名称：获取当前选中的值；icheck-radio change事件；点击事件;
	$("input:checkbox").on('ifChecked', function (event) {
		set_hot($(this).attr('tag'), 1);
	});
	$("input:checkbox").on('ifUnchecked', function (event) {
		set_hot($(this).attr('tag'), 2);
	});

	function set_hot(id, hot) {
		var url = (hot == 1 ? '/blog/index/add_hot' : '/blog/index/drop_hot');
		$.ajax({
			url: url,
			method: 'post',
			dataType: 'json',
			data: {id: id},
			success: function (res) {
				if (res.re_st == 'success') {
					showSuccess(res.re_info);
				} else {
					showError(res.re_info);
				}
			},
			error: function (err) {
				showError(err.toString());
			}

		});

	}
</script>
