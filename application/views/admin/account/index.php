<div class="cluster">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">
				Thành viên
			</h1>
		</div>
		<div class="pull-left" style="max-width: 350px;">
			<div class="input-group input-group-sm">
				<input type="text" id="report_text" name="report_text" class="form-control" value="<?//=$search_text?>" placeholder="Nhập tên hoặc email bạn muốn tìm...">
				<span class="input-group-btn">
					<button class="btn btn-default btn-search" type="button">Tìm kiếm</button>
				</span>
			</div>
		</div>
		<br><br>
		<? if (empty($users) || !sizeof($users)) { ?>
		<p class="help-block">No user found.</p>
		<? } else { ?>
		<form id="frm-admin" name="adminForm" action="" method="POST">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="boxchecked" name="boxchecked" value="0" />
			<input type="hidden" id="search_text" name="search_text" value="<?//=$search_text?>">
			<table class="table table-bordered">
				<tr>
					<th class="text-center" width="30px">#</th>
					<th class="text-center" width="30px">
						<input type="checkbox" id="toggle" name="toggle" onclick="checkAll('<?=sizeof($users)?>');" />
					</th>
					<th class="text-center" width="30px"></th>
					<th>Họ Tên</th>
					<th width="150px">Xếp hạng</th>
					<th width="150px">Chức năng</th>
					<th class="" width="150px">Ngày tạo</th>
				</tr>
				<?
					$i = 0;
					foreach ($users as $user) {
				?>
				<tr class="row<?=$user->active?>">
					<td class="text-center">
						<?=$offset+1?>
					</td>
					<td class="text-center">
						<input type="checkbox" id="cb<?=$i?>" name="cid[]" value="<?=$user->id?>" onclick="isChecked(this.checked);">
					</td>
					<td class="text-center"><span class="icon-account-type icon-<?=$user->user_type?>"></span></td>
					<td>
						<a href="#"><?=$user->fullname?></a>
						<? if($this->session->admin->user_type == USR_SUPPER_ADMIN || $this->session->admin->id == $user->id) { ?>
						<ul class="action-icon-list">
							<li><a href="<?=site_url("syslog/users/edit/{$user->id}")?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Phân Quyền</a></li>
							<li><a href="#" onclick="return confirmBox('Delete users', 'Are you sure?', 'itemTask', ['cb<?=$i?>', 'delete']);"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
							<? if ($user->active) { ?>
							<li><a href="#" onclick="return itemTask('cb<?=$i?>','unpublish');"><i class="fa fa-eye-slash" aria-hidden="true"></i> Khóa </a></li>
							<? } else { ?>
							<li><a href="#" onclick="return itemTask('cb<?=$i?>','publish');"><i class="fa fa-eye-slash" aria-hidden="true"></i> Mở khóa</a></li>
							<? } ?>
						</ul>
						<? } ?>
					</td>
					<td class="">
						<strong><?
						if ($user->user_rank == 0) {
							echo 'Mới';
						} else if ($user->user_rank == 1) {
							echo 'Bạc';
						} else if ($user->user_rank == 2) {
							echo 'Vàng';
						} else if ($user->user_rank == 3) {
							echo 'Bạch Kim';
						} else if ($user->user_rank == 4) {
							echo 'Kim Cương';
						}
						?></strong>
					</td>
					<td class="">
						<strong><?
						if ($user->user_type == USR_SUPPER_ADMIN) {
							echo 'Super Admin';
						} else if ($user->user_type == USR_ADMIN) {
							echo 'Admin';
						} else if ($user->user_type == USR_MOD) {
							echo 'Mod';
						} else if ($user->user_type == USR_USER) {
							echo 'User';
						}
						?></strong>
					</td>
					<td class="">
						<strong><?=date("H:i:s - d/m/Y", strtotime($user->created_date))?></strong>
					</td>
				</tr>
				<?
						$i++;$offset++;
					}
				?>
			</table>
			<div class="text-center"><?=$pagination?></div>
		</form>
		<? } ?>
	</div>
</div>

<script>
$(document).ready(function() {
	jQuery.noConflict();
	$(".btn-publish").click(function(e){
		e.preventDefault();
		if ($("#boxchecked").val() == 0) {
			messageBox("ERROR", "Error", "Please make a selection from the list to publish.");
		}
		else {
			submitButton("read");
		}
	});
	$(".btn-unpublish").click(function(e){
		e.preventDefault();
		if ($("#boxchecked").val() == 0) {
			messageBox("ERROR", "Error", "Please make a selection from the list to unpublish.");
		}
		else {
			submitButton("unread");
		}
	});
	$(".btn-delete").click(function(e){
		e.preventDefault();
		if ($("#boxchecked").val() == 0) {
			messageBox("ERROR", "Error", "Please make a selection from the list to delete.");
		}
		else {
			confirmBox("Delete items", "Are you sure you want to delete the selected items?", "submitButton", "delete");
		}
	});
	$(".btn-search").click(function(){
		$("#search_text").val($("#report_text").val());
		submitButton("search");
	});
	$("[data-ci-pagination-page]").click(function(event){
		event.preventDefault();
		$("#frm-admin").attr("action", $(this).attr("href")).submit();
	});
});
</script>