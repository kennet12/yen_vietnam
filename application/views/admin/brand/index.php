<div class="cluster">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">Nhãn hiệu<div class="pull-right">
					<ul class="action-icon-list">
						<li><a href="#" class="btn-unpublish"><i class="fa fa-eye-slash" aria-hidden="true"></i> Ẩn</a></li>
						<li><a href="#" class="btn-publish"><i class="fa fa-eye-slash" aria-hidden="true"></i> Hiện</a></li>
						<li><a href="#" class="btn-delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
						<li><a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/add")?>"><i class="fa fa-plus" aria-hidden="true"></i> Thêm</a></li>
					</ul>
				</div>
			</h1>
		</div>
		<? if (empty($items) || !sizeof($items)) { ?>
		<p class="help-block">Not found item.</p>
		<? } else { ?>
		<form id="frm-admin" name="adminForm" action="" method="POST">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="boxchecked" name="boxchecked" value="0" />
			<table class="table table-bordered">
				<tr>
					<th class="text-center" width="30px">#</th>
					<th class="text-center" width="30px">
						<input type="checkbox" id="toggle" name="toggle" onclick="checkAll('<?=sizeof($items)?>');" />
					</th>
					<th>Tên nhãn hiệu</th>
					<th>Nội dung</th>
					<th width="180px">Cập nhật</th>
				</tr>
				<?
					$i = 0;
					foreach ($items as $item) {
				?>
				<tr class="row<?=$item->active?>">
					<td class="text-center"><?=($i+1)?></td>
					<td class="text-center">
						<input type="checkbox" id="cb<?=$i?>" name="cid[]" value="<?=$item->id?>" onclick="isChecked(this.checked);">
					</td>
					<td>
						<a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/edit/{$item->id}")?>"><?=$item->name?></a>
						<ul class="action-icon-list">
							<li><a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/edit/{$item->id}")?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>  Chỉnh sửa</a></li>
							<li><a href="#" onclick="return confirmBox('Delete item', 'Are you sure?', 'itemTask', ['cb<?=$i?>', 'delete']);"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
							<? if ($item->active) { ?>
							<li><a href="#" onclick="return itemTask('cb<?=$i?>','unpublish');"><i class="fa fa-eye-slash" aria-hidden="true"></i> Ẩn</a></li>
							<? } else { ?>
							<li><a href="#" onclick="return itemTask('cb<?=$i?>','publish');"><i class="fa fa-eye-slash" aria-hidden="true"></i> Hiện</a></li>
							<? } ?>
						</ul>
					</td>
					<td>
						<?=$item->content?>
					</td>
					<td>
						<?
							$updated_by = $this->m_user->load($item->updated_by);
							if (!empty($updated_by)) {
						?>
						<strong><?=$updated_by->fullname?></strong>
						<div class="action-icon-list"><span class="text-color-grey"><?=date("Y-m-d H:i:s", strtotime($item->updated_date))?></span></div>
						<?
							}
						?>
					</td>
				</tr>
				<?
						$i++;
					}
				?>
			</table>
		</form>
		<? } ?>
		<div class="col-md-12 text-center"><?=$pagination?></div>
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
});
</script>