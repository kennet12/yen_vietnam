<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title">Sự kiện</h1>
		<form id="frm-admin" name="adminForm" action="" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="">
			<table class="table table-bordered">
				<tr>
					<td class="table-head text-right" width="10%">Tên sự kiện</td>
					<td><input type="text" id="name" name="name" class="form-control" value="<?=!empty($item->name) ? $item->name : ''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">URL alias</td>
					<td><input type="text" id="alias" name="alias" class="form-control" value="<?=!empty($item->alias) ? $item->alias : ''?>"></td>
				</tr>
				<!-- <td class="table-head text-right">Danh mục sản phẩm</td>
					<td>
						<?
							$arr_category_id = !empty($item->product_category_id)?$item->product_category_id:'';
						?>
						<input type="hidden" id="product_category_id" name="product_category_id" value='<?=$arr_category_id?>'>
						<div class="row">
							<? foreach ($categories as $category) { $check = (strpos($arr_category_id, '"'.$category->id.'"') !== false)?'checked':''; ?>
							<div class="col-md-2">
								<label class="">
									<input type="checkbox" class="filter" value="<?=$category->id?>" <?=$check?>>
									<?=$category->name?>
								</label>
							</div>
							<? } ?>
						</div>
						<script>
							$(".filter").click(function(){
								var value = $(this).val();
								var list = $('#product_category_id').val();
								if ($(this).is(":checked")) {
									if (list == '') {
										list += '"'+value+'"';
									} else {
										list += ','+'"'+value+'"';
									}
								} else {
									list = list.replace(',"'+value+'",',',');
									list = list.replace(',"'+value+'"','');
									list = list.replace('"'+value+'",','');
									list = list.replace('"'+value+'"','');
								}
								$('#product_category_id').val(list);
							});
						</script>
					</td>
				</tr> -->
				<!-- <tr>
					<td class="table-head text-right" width="10%">Hình ảnh</td>
					<td>
						<label class="wrap-upload-thumb" style="background: url('<?=BASE_URL?><?=!empty($item->thumbnail) ? $item->thumbnail : ''?>') no-repeat">
							<input type="file" name="thumbnail" id="file-upload" value="<?=!empty($item->title) ? $item->title : ''?>">
							<i class="fa fa-cloud-upload" aria-hidden="true"></i>
						</label>
					</td>
				</tr> -->
				<tr>
				<td class="table-head text-right" width="10%"> Nội dung</td>
					<td>
						<table class="table table-bordered">
							<tr>
								<td width="30px" class="text-center">
									<img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;">
								</td>
								<td>
									<textarea id="content" name="content" class=" form-control" rows="5"><?=!empty($item->content) ? $item->content : ''?></textarea>
								</td>
							</tr>
							<tr>
								<td width="30px" class="text-center">
									<img src="<?=IMG_URL?>english.png" alt="" style="width:15px;">
								</td>
								<td>
								<textarea id="content_en" name="content_en" class=" form-control" rows="5"><?=!empty($item->content_en) ? $item->content_en : ''?></textarea>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="table-head text-right">Trạng thái</td>
					<td>
						<select id="active" name="active" class="form-control">
							<option value="1">Hiện</option>
							<option value="0">Ẩn</option>
						</select>
						<script type="text/javascript">
							$("#active").val("<?=!empty($item->active) ? $item->active : 1 ?>");
						</script>
					</td>
				</tr>
			</table>
			<div class="pull-right">
				<button class="btn btn-sm btn-primary btn-save">Lưu</button>
				<button class="btn btn-sm btn-default btn-cancel">Hủy</button>
			</div>
		</form>
	</div>
</div>

<script>
$(document).ready(function() {
	jQuery.noConflict();
	$("#file-upload").change(function() {
		readURL(this);
	});
	
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('.wrap-upload-thumb').css({
					"background-image": "url('"+e.target.result+"')"
				});
				$('.wrap-upload-thumb > i').css({
					"color": "rgba(52, 73, 94, 0.38)"
				});
			};
			reader.readAsDataURL(input.files[0]);
		}
	}
	$(".btn-cancel").click(function(){
		submitButton("cancel");
	});
	$(".btn-save").click(function(){
		submitButton("save");
	});
});
</script>