<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title">Phân Quyền</h1>
		<form id="frm-admin" name="adminForm" action="" method="POST">
			<input type="hidden" id="task" name="task" value="">
			<div class="text-right">
				<button class="btn btn-sm btn-primary btn-save">Cập nhật</button>
				<button class="btn btn-sm btn-default btn-cancel">Hủy bỏ</button>
			</div><br>
			<table class="table table-bordered">
				<!-- <tr>
					<td class="table-head text-right" width="10%">Username</td>
					<td><input type="text" id="email" name="email" class="form-control" value="<?=!empty($user->email) ? $user->email : ''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Password</td>
					<td><input type="password" id="password_text" name="password_text" class="form-control" value="<?=!empty($user->password_text) ? $user->password_text : ''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Thumbnail</td>
					<td><input type="text" id="avatar" name="avatar" class="form-control" value="<?=!empty($user->avatar) ? $user->avatar : ''?>" onclick="openKCFinder4Textbox(this)" placeholder="Click here and select a file double clicking on it"></td>
				</tr> -->
				<tr>
					<td class="table-head text-right" width="10%">Họ Tên</td>
					<td><input type="text" id="fullname" name="fullname" class="form-control" value="<?=!empty($user->fullname) ? $user->fullname : ''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right">Xếp hạng</td>
					<td>
						<select id="user_rank" name="user_rank" class="form-control">
							<option value="0">Mới</option>
							<option value="1">Bạc</option>
							<option value="2">Vàng</option>
							<option value="3">Bạch kim</option>
							<option value="4">Kim cương</option>
						</select>
						<script type="text/javascript">
							$("#user_rank").val("<?=$user->user_rank?>");
						</script>
					</td>
				</tr>
				<tr>
					<td class="table-head text-right">Chức năng</td>
					<td>
						<select id="user_type" name="user_type" class="form-control">
							<option value="<?=USR_USER?>">User</option>
							<option value="<?=USR_MOD?>">Mod</option>
							<option value="<?=USR_ADMIN?>">Admin</option>
							<option value="<?=USR_SUPPER_ADMIN?>">Super Admin</option>
						</select>
						<script type="text/javascript">
							$("#user_type").val("<?=$user->user_type?>");
						</script>
					</td>
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