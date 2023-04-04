<div class="cluster">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">
				<div class="pull-left" style="max-width: 345px;">
					<?=$category->name?>
					<div class="input-group">
						<input type="text" id="search-box" class="form-control" placeholder="Nhập tên sản phẩm . . ." value="<?=$search_text?>">
						<span class="input-group-btn">
							<button class="btn btn-default btn-search-box" type="button">Tìm kiếm</button>
						</span>
					</div>
					<br>
				</div>
				<div class="pull-right">
					<ul class="action-icon-list">
						<li><a href="#" class="btn-unpublish"><i class="fa fa-eye-slash" aria-hidden="true"></i> Hide</a></li>
						<li><a href="#" class="btn-publish"><i class="fa fa-eye-slash" aria-hidden="true"></i> Show</a></li>
						<li><a href="#" class="btn-delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
						<li><a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/{$category->id}/add")?>"><i class="fa fa-plus" aria-hidden="true"></i> Add item</a></li>
					</ul>
				</div>
			</h1>
		</div>
		<form id="frm-admin" name="adminForm" action="" method="POST">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="boxchecked" name="boxchecked" value="0" />
			<input type="hidden" id="search_text" name="search_text" value="<?=$search_text?>">
			<table class="table table-bordered">
				<tr>
					<th class="text-center" width="30px">#</th>
					<th class="text-center" width="30px">
						<input type="checkbox" id="toggle" name="toggle" onclick="checkAll('<?=sizeof($items)?>');" />
					</th>
					<th>Title</th>
					<th width="180px">Update</th>
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
						<a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/{$category->id}/edit/{$item->id}")?>">
							<img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;"> <?=$item->title?> 
							<? if(!empty($item->title_en)) { ?>
							<br><img src="<?=IMG_URL?>english.png" alt="" style="width:15px;"> <?=$item->title_en?> 
							<? } ?>	
						</a>
						<ul class="action-icon-list">
							<li><a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/{$category->id}/edit/{$item->id}")?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update</a></li>
							<li><a href="#" onclick="return confirmBox('Delete item', 'Are you sure?', 'itemTask', ['cb<?=$i?>', 'delete']);"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
							<? if ($item->active) { ?>
							<li><a href="#" onclick="return itemTask('cb<?=$i?>','unpublish');"><i class="fa fa-eye-slash" aria-hidden="true"></i> Hide</a></li>
							<? } else { ?>
							<li><a href="#" onclick="return itemTask('cb<?=$i?>','publish');"><i class="fa fa-eye-slash" aria-hidden="true"></i> Show</a></li>
							<? } ?>
						</ul>
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
		<div class="col-md-12 text-center"><?=$pagination?></div>
	</div>
</div>

<script>
$(document).ready(function() {
	jQuery.noConflict();
	$(".btn-search-box").click(function(){
		var search_text = $('#search-box').val();
		$("#search_text").val(search_text);
		submitButton('search');
	});
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