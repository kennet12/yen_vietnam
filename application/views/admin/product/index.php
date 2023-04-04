<div class="container-fluid">
	<div class="tool-bar clearfix">
		<h1 class="page-title">Danh sách sản phẩm</h1>
		<div class="pull-right">
			<div class="clearfix">
				<div class="pull-left" style="margin-right: 5px;">
					<div class="input-group input-group-sm">
						<input type="text" id="report_text" name="report_text" class="form-control" value="<?=$search_text?>" placeholder="Nhập Tên SP, Mã SP"  style="width: 300px;">
					</div>
					<?
					$category_id = !empty($category->id)?$category->id:0;
					$info = new stdClass();
					$info->parent_id = $category_id;
					$check_child = $this->m_product_category->items($info);
					if (empty($check_child)) { ?>
					<ul class="action-icon-list">
						<li><a href="#" class="btn-unpublish"><i class="fa fa-eye-slash" aria-hidden="true"></i> Ẩn</a></li>
						<li><a href="#" class="btn-publish"><i class="fa fa-eye-slash" aria-hidden="true"></i> Hiện</a></li>
						<li><a href="#" class="btn-delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
						<li><a href="<?=site_url("syslog/product/{$category_id}/add")?>"><i class="fa fa-plus" aria-hidden="true"></i> Thêm</a></li>
					</ul>
					<? } ?>
				</div>
				<div class="pull-left">
					<button class="btn btn-sm btn-default btn-report" type="button">Gửi</button>
				</div>
			</div>
			<br>
		</div>
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
				<th class="text-center" width="30px"></th>
				<th width="80" class="">Mã</th>
				<th>Tên SP</th>
				<th>Chi tiết</th>
				<th width="150px">Ngày cập nhật</th>
			</tr>
			<?
				$i = 0;
				$page = !empty($_GET['page'])?"?page={$_GET['page']}":"";

				foreach ($items as $item) {
				$category_main = json_decode($item->category_id);
			?>
			<tr>
				<td class="text-center"><?=($offset+1)?></td>
				<td class="text-center">
					<input type="checkbox" id="cb<?=$i?>" name="cid[]" value="<?=$item->id?>" onclick="isChecked(this.checked);">
				</td>
				<td class="text-center"><span class="icon-product-type icon-product-<?=$item->product_company?>"></span></td>
				<td>NA<?=$item->code_product?></td>
				<td>
					<div class="lang-item">
						<a href="<?=site_url("syslog/product/{$category_id}/edit/{$item->id}")."{$page}"?>"><?=$item->title?></a><b> - (<?=$this->m_product_category->load(end($category_main))->name?>)</b>
						<div class="rating">
						<? if ($item->rating_cmt != '0'){
							$point = round($item->rating_point/$item->rating_cmt,1);
						} else {
							$point = 0;
						}
						for($j=1;$j<=5;$j++) { ?>
							<? if ($j <= $point) { ?>
								<i class="fa fa-star"></i>
							<? } else { ?>
								<? if (($point > ($j-1)) && $point < $j) { ?>
									<i class="fa fa-star-half"></i>
								<? } else { ?>
									<i class="fa fa-star-o"></i>
								<? } ?>
							<? } ?>
						<? } ?><span style="font-size:10px;color:#999"> - <?=$item->rating_cmt?> đánh giá | </span><span style="font-size:10px;color: #ff5722;">Affiliate: <?=$item->affiliate?>%</span>
						</div>
						<ul class="action-icon-list">
							<li><a href="<?=site_url("syslog/product/{$category_id}/edit/{$item->id}")."{$page}"?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Chỉnh sửa</a></li>
							<li><a href="#" onclick="return confirmBox('Delete item', 'Are you sure ?', 'itemTask', ['cb<?=$i?>', 'delete']);"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
							<? if ($item->active) { ?>
							<li><a href="#" onclick="return itemTask('cb<?=$i?>','unpublish');"><i class="fa fa-eye-slash" aria-hidden="true"></i> Ẩn</a></li>
							<? } else { ?>
							<li><a href="#" onclick="return itemTask('cb<?=$i?>','publish');" style="color:red;"><i class="fa fa-eye-slash" aria-hidden="true"></i> Hiện</a></li>
							<? } ?>
							<li><a target="_blank" href="<?=site_url("$item->alias")?>"><i class="fa fa-link" aria-hidden="true"></i> Xem chi tiết</a></li>
							<li><a target="_blank" href="<?=site_url("syslog/enter-box/{$category_id}/detail/{$item->id}")?>"><i class="fa fa-cube" aria-hidden="true"></i> Kiểm kho</a></li>
						</ul>
					</div>
				</td>
				<td>
					<table class="table table-bordered">
						<tr>
							<?if($item->typename !="") {?>
							<th><?=$item->typename?></th>
							<? } ?>
							<?if($item->subtypename !="") {?>
							<th><?=$item->subtypename?></th>
							<? } ?>
							<th>Số lượng</th>
							<th>Giảm giá (%)</th>
							<th>Giá bán</th>
						</tr>
						<?=$arr_type[$item->id]?>
					</table>
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
					$i++; $offset++ ;
				}
			?>
		</table>
	</form>
</div>
<script type="text/javascript">
	$('.btn-action').click(function(event) {
		var hr = $(this).attr('hr');
		var cf = confirm('Are you sure?');
		if (cf) {
			window.location.href = hr;
		}
	});
</script>
<div class="col-sm-12 text-center"><?=$pagination?></div>
<script>
$(document).ready(function() {
	jQuery.noConflict();
	$('#pagi').on('change', function(e) {
		<? if (isset($_GET['pagi'])){ ?> var a = <?=$_GET['pagi']?>; <? } ?>
		var get_request = window.location.href;
		if (typeof a != 'undefined'){
			var res = get_request.replace("pagi="+<?=$pagi?>,"pagi="+$('#pagi').val());
			window.location.href = res;
		}else{
			var params = {};
				params = {
						pagi: $('#pagi').val()
						};
			get_request += '?' + jQuery.param( params );
			window.location.href = get_request;
		}
	});
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
	$(".btn-report").click(function(){
		$("#search_text").val($("#report_text").val());
		submitButton("search");
	});
});
</script>