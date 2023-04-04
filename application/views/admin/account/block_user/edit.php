<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title">Thêm số điện thoại</h1>
		<form id="frm-admin" name="adminForm" action="" method="POST">
			<input type="hidden" id="task" name="task" value="">
			<div class="text-right">
				<button class="btn btn-sm btn-primary btn-save">Cập nhật</button>
				<button class="btn btn-sm btn-default btn-cancel">Hủy bỏ</button>
			</div><br>
			<table class="table table-bordered">
				<tr>
					<td class="table-head text-right" width="10%">Số điện thoại</td>
					<td><input type="text" id="phone" name="phone" class="form-control" value="<?=!empty($user->phone) ? $user->fullname : ''?>"></td>
				</tr>
			</table>
		</form>
	</div>
</div>

<script>
$(document).ready(function() {
	$(".btn-save").click(function(){
		submitButton("save");
	});
	$(".btn-cancel").click(function(){
		submitButton("cancel");
	});
});
</script>