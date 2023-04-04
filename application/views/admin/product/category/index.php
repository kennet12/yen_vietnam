<div class="cluster">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">
				Danh mục sản phẩm
				<div class="pull-right">
					<ul class="action-icon-list">
						<li><a href="#" class="btn-unpublish"><i class="fa fa-eye-slash" aria-hidden="true"></i> Ẩn</a></li>
						<li><a href="#" class="btn-publish"><i class="fa fa-eye" aria-hidden="true"></i> Hiện</a></li>
						<li><a href="#" class="btn-delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
						<li><a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/add")?>"><i class="fa fa-plus" aria-hidden="true"></i> Tạo</a></li>
					</ul>
				</div>
			</h1>
		</div>
		<? if (empty($items) || !sizeof($items)) { ?>
		<p class="help-block">No item found.</p>
		<? } else { ?>
		<form id="frm-admin" name="adminForm" action="" method="POST">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="boxchecked" name="boxchecked" value="0" />
			<table class="table table-bordered table-hover">
				<tr>
					<th class="text-center" width="30px">#</th>
					<th class="text-center" width="30px">
						<input type="checkbox" id="toggle" name="toggle" onclick="checkAll('<?=sizeof($items)?>');" />
					</th>
					<th>Tên danh mục</th>
					<th class="text-center" width="50px">Mã</th>
					<th class="" width="280px">Từ khóa</th>
					<th class="" width="180px">Ngày cập nhật</th>
				</tr>
				<?
					function level_indent($level) {
						for ($i=0; $i<$level; $i++) {
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; // 6 spaces
						}
					}
					function print_categories($obj, $categories, $level, &$i) {
						foreach ($categories as $category) {
							?>
							<tr class="row<?=$category->active?>">
								<td class="text-center"><?=($i+1)?></td>
								<td class="text-center">
									<input type="checkbox" id="cb<?=$i?>" name="cid[]" value="<?=$category->id?>" onclick="isChecked(this.checked);">
								</td>
								<td>
									<a href="<?=site_url("syslog/product-category/edit/{$category->id}")?>"><?=level_indent($level).($level?"|&rarr; ":"")?>
										<?=$category->name?> <img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;">
										<? if(!empty($category->name_en)) { ?>
										/ <?=$category->name_en?> <img src="<?=IMG_URL?>english.png" alt="" style="width:15px;">
										<? } ?>
									</a>
									<ul class="action-icon-list">
										<li><a href="<?=site_url("syslog/product-category/edit/{$category->id}")?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Cập nhật</a></li>
										<li><a href="#" onclick="return confirmBox('Delete items', 'Are you sure you want to delete the selected items?', 'itemTask', ['cb<?=$i?>', 'delete']);"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
										<? if ($category->active) { ?>
										<li><a href="#" onclick="return itemTask('cb<?=$i?>','unpublish');"><i class="fa fa-eye-slash" aria-hidden="true"></i> Ẩn</a></li>
										<? } else { ?>
										<li><a style="color:red" href="#" onclick="return itemTask('cb<?=$i?>','publish');"><i class="fa fa-eye" aria-hidden="true"></i> Hiện</a></li>
										<? } ?>
										<? if ($category->show_cate) { ?>
										<li><a style="color:red" href="#" onclick="return itemTask('cb<?=$i?>','normal');"><i class="fa fa-eye-slash" aria-hidden="true"></i> Thường</a></li>
										<? } else { ?>
										<li><a href="#" onclick="return itemTask('cb<?=$i?>','highlight');"><i class="fa fa-eye" aria-hidden="true"></i> Nổi bật</a></li>
										<? } ?>
										<li><a href="#" onclick="return itemTask('cb<?=$i?>','orderup');"><i class="fa fa-level-up" aria-hidden="true"></i> Lên</a></li>
										<li><a href="#" onclick="return itemTask('cb<?=$i?>','orderdown');"><i class="fa fa-level-down" aria-hidden="true"></i> Xuống</a></li>
									</ul>
								</td>
								<td class="text-center"><?=$category->code?></td>
								<td>
									<? 
										$keys = explode(',',$category->meta_key);
										foreach($keys as $key) {
									?>
									<a style="color: #fff;background: #03a9f4;padding: 3px;border-radius: 3px;margin: 0 2px;" href="#"><?=$key?></a>
									<? } ?>
								</td>
								<td class="">
									<?
										$updated_by = $obj->m_user->load($category->updated_by);
										if (!empty($updated_by)) {
									?>
									<strong><?=$updated_by->fullname?></strong>
									<div class="action-icon-list"><span class="text-color-grey"><?=date('Y-m-d h:i:s', strtotime($category->updated_date))?></span></div>
									<?
										}
									?>
								</td>
							</tr>
							<?
							$i++;
							$child_category_info = new stdClass();
							$child_category_info->parent_id = $category->id;
							$child_categories = $obj->m_product_category->items($child_category_info,null,null,null,'order_num','ASC');
							print_categories($obj, $child_categories, $level+1, $i);
						}
					}
					$i = 0;
					print_categories($this, $items, 0, $i);
				?>
			</table>
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
			submitButton("publish");
		}
	});
	$(".btn-unpublish").click(function(e){
		e.preventDefault();
		if ($("#boxchecked").val() == 0) {
			messageBox("ERROR", "Error", "Please make a selection from the list to unpublish.");
		}
		else {
			submitButton("unpublish");
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