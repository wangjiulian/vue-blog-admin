<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title">
					<label><input id="hot" type="checkbox" <?php if ($detail['hot'] == 1) echo 'checked = checked ' ?> class="i-checks"><span
							style="margin-left: 10px; height: 20px;line-height: 20px;">推荐</span></label>
					<div class="form-group ">
						<label class="col-form-label">文章标题</label>
						<input class="form-control col-sm-12" id="title" value="<?=$detail['title']?>"  placeholder="请输入标题"/>
					</div>
				</div>
				<div class="ibox-content">
					<form style="display: none;" action="/blog/index/blog_img" class="dropzone" id="dropzoneForm">
						<div class="fallback">
							<input name="file" type="file" multiple/>
						</div>
					</form>
					<textarea id="summer">
						<?=$detail['content']?>
					</textarea>
				</div>
				<div class="ibox-footer">
					<button class="btn btn-success col-sm-12" onclick="if(confirm('确定要发布吗')) edit_blog(<?=$detail['id']?>) ">修改
					</button>
				</div>
			</div>
		</div>
	</div>
	<div hidden id="add_file">

	</div>

</div>
<link href="<?= ADMIN_THEME ?>/css/plugins/dropzone/basic.css" rel="stylesheet">
<link href="<?= ADMIN_THEME ?>/css/plugins/dropzone/dropzone.css" rel="stylesheet">
<link href="<?= ADMIN_THEME ?>/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
<link href="<?= ADMIN_THEME ?>/css/plugins/codemirror/codemirror.css" rel="stylesheet">
<link href="<?= ADMIN_THEME ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">
<!-- SUMMERNOTE -->
<script src="<?= ADMIN_THEME ?>/js/plugins/summernote/summernote-bs4.js"></script>
<!-- Jasny -->
<script src="<?= ADMIN_THEME ?>/js/plugins/jasny/jasny-bootstrap.min.js"></script>
<!-- DROPZONE -->
<script src="<?= ADMIN_THEME ?>/js/plugins/dropzone/dropzone.js"></script>
<!-- CodeMirror -->
<script src="<?= ADMIN_THEME ?>/js/plugins/codemirror/codemirror.js"></script>
<script src="<?= ADMIN_THEME ?>/js/plugins/codemirror/mode/xml/xml.js"></script>
<link href="<?= ADMIN_THEME ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
<!-- iCheck -->
<script src="<?= ADMIN_THEME ?>/js/plugins/iCheck/icheck.min.js"></script>
<script>
	$(document).ready(function () {
		$('#summer').summernote({
			placeholder: '项目说明',
			height: 500,
			toolbar: [
				['style', ['style', 'bold', 'italic', 'underline', 'clear']],
				['font', ['strikethrough', 'superscript', 'subscript']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']],
				['insert', ['link', 'picture', 'video', 'table']],
				['view', ['fullscreen', 'codeview', 'help']],
			],callbacks: {
				onImageUpload: function(files, editor, welEditable) {
					for (var i = files.length - 1; i >= 0; i--) {
						sendFile(files[i], this);
					}
				}
			}
		});
		// $('#summer').summernote('code','');
		// summernote 上传
		function sendFile(file, el) {
			data = new FormData();
			data.append('path', 'summernote/question');
			data.append("file", file);
			$.ajax({
				type: "POST",
				url: "/blog/index/blog_img",
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
				success: function(response) {
					alert(response.re_info)
					$(el).summernote('editor.insertImage', response.re_info);
				},
				error: function(error) {
					console.log(error);
				},
				complete: function(response) {}
			});
		}
		$('#summernote').summernote('code', '');
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});

	});
	Dropzone.options.dropzoneForm = {
		paramName: "file", // The name that will be used to transfer the file
		maxFilesize: 5, // MB
		maxFiles: '9',
		dictDefaultMessage: "选择您要上传的图片",
		acceptedFiles: '.jpeg,.jpg,.png', //文件格式
		addRemoveLinks: true, //显示删除
		dictInvalidInputType: '不支持该文件类型',//文件类型被拒绝时的提示文本
		dictFileTooBig: '请选择5M以内的文件', //文件大小过大时的提示文本
		dictCancelUpload: '取消上传',
		dictCancelUploadConfirmation: '确认取消上传吗', //取消上传确认信息的文本
		dictMaxFilesExceeded: '最多上传9张图片', //超过最大文件数量的提示文本
		autoProcessQueue: true, //取消自动提交服务器
		dictRemoveFile: '删除文件',
		init: function () {
			this.on('addedfile', function (file) {

			});
			this.on('removedfile', function (file) {
				$('#' + file.tag).remove();
			});
			this.on('success', function (file, res) {
				res = JSON.parse(res);
				if (res.re_st == 'success') {
					var arr = res.re_info.split('/');
					var tag = arr[arr.length - 1].split('.')[0];
					file.tag = tag;
					var hidden_input = '<input tag="img" id="' + tag + '"  value="' + res.re_info + '" />';
					$('#add_file').append(hidden_input);
				}
			});

		},
	};

	//编辑博客
	function edit_blog(id) {
		var title = $('#title').val();
		var content = $('#summer').summernote('code');
		if (isEmpty(title)) {
			showError('请输入文章标题');
			return false;
		}
		if (isEmpty(content)) {
			showError('请输入文章内容');
			return false;
		}
		// var imgs = '';
		// $('#add_file').find('input[tag="img"]').each(function () {
		// 	imgs += $(this).val() + ',';
		// });
		// if (isEmpty(imgs)) {
		// 	showError('请至少上传一张图片');
		// 	return false;
		// }
		// imgs = imgs.substr(0, imgs.length - 1);
		var hot = $('#hot').is(':checked') ? '1' : '2';
		$.ajax({
			url: '/blog/index/blog_edit',
			method: 'post',
			data: {title: title, content: content, hot: hot, id:id},
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
		});


	}
</script>
