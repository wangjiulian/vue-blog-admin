<div class="footer">
	<div class="float-right">
		10GB of <strong>250GB</strong> Free.
	</div>
	<div>
		<strong>Copyright</strong> Example Company &copy; 2014-2018
	</div>
</div>

</div>
</div>
</body>
</html>
<script src="<?= ADMIN_THEME ?>/js/popper.min.js"></script>
<script src="<?= ADMIN_THEME ?>/js/bootstrap.js"></script>
<script src="<?= ADMIN_THEME ?>/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="<?= ADMIN_THEME ?>/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<!-- Toastr script -->
<script src="<?= ADMIN_THEME ?>/js/plugins/toastr/toastr.min.js"></script>
<!-- Custom and plugin javascript -->
<script src="<?= ADMIN_THEME ?>/js/inspinia.js"></script>
<script src="<?= ADMIN_THEME ?>/js/plugins/pace/pace.min.js"></script>
<script type="text/javascript">
	function isEmpty(val) {
		if (typeof val == 'undefined' || val == '' || val == 'null') {
			return true;
		}
		return false;
	}


	function showSuccess(msg, time = 1000) {
		toastr.options = {
			closeButton: true,
			progressBar: true,
			showMethod: 'slideDown',
			timeOut: time
		};
		toastr.success(msg);

	}

	function showError(msg, time = 1000) {
		toastr.options = {
			closeButton: true,
			progressBar: true,
			showMethod: 'slideDown',
			timeOut: time
		};
		toastr.warning(msg);
	}

	function isEmpty(val) {
		if (typeof val == 'undefined' || val == '') return true;
		return false;
	}
</script>

