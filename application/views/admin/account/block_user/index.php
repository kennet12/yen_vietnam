<div class="cluster">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">
				Chặn số phone
				<div class="pull-right">
					<ul class="action-icon-list">
						<li><a href="#" class="btn-delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
						<li><a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/add")?>"><i class="fa fa-plus" aria-hidden="true"></i> Thêm</a></li>
					</ul>
				</div>
			</h1>
		</div>
		<div class="pull-left" style="max-width: 350px;">
			<div class="input-group input-group-sm">
				<input type="text" id="report_text" name="report_text" class="form-control" value="<?//=$search_text?>" placeholder="Nhập số điện thoại bạn muốn tìm">
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
					<th>Số điện thoại</th>
					<th class="" width="150px">Người chặn</th>
				</tr>
				<?
					$i = 0;
					foreach ($users as $user) {
				?>
				<tr class="row1">
					<td class="text-center">
						<?=$offset+1?>
					</td>
					<td class="text-center">
						<input type="checkbox" id="cb<?=$i?>" name="cid[]" value="<?=$user->id?>" onclick="isChecked(this.checked);">
					</td>
					<td>
						<a href="#"><?=$user->phone?></a>
						<ul class="action-icon-list">
							<li><a href="#" onclick="return confirmBox('Delete users', 'Are you sure?', 'itemTask', ['cb<?=$i?>', 'delete']);"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
						</ul>
					</td>
					<td class="">
						<?
							$updated_by = $this->m_user->load($user->updated_by);
							if (!empty($updated_by)) {
						?>
						<strong><?=$updated_by->fullname?></strong>
						<div class="action-icon-list"><span class="text-color-grey"><?=date("Y-m-d H:i:s", strtotime($user->updated_date))?></span></div>
						<?
							}
						?>
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