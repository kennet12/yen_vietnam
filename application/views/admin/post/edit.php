<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title"><?=!empty($item->title) ? $item->title : ''?></h1>
		<form id="frm-admin" name="adminForm" action="" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="">
			<div class="text-right">
				<button class="btn btn-sm btn-primary btn-save">Update</button>
				<button class="btn btn-sm btn-default btn-cancel">Cancel</button>
			</div><br>
			<table class="table table-bordered">
				<tr>
					<td class="table-head text-right" width="10%"> Tên sản phẩm</td>
					<td>
						<table class="table table-bordered">
							<tr>
								<td width="30px" class="text-center">
									<img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;">
								</td>
								<td>
									<input type="text" id="title" name="title" class="form-control" value="<?=!empty($item->title) ? $item->title : ''?>">
								</td>
							</tr>
							<tr>
								<td width="30px" class="text-center">
									<img src="<?=IMG_URL?>english.png" alt="" style="width:15px;">
								</td>
								<td>
									<input type="text" id="title_en" name="title_en" class="form-control" value="<?=!empty($item->title_en) ? $item->title_en : ''?>">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
				<td class="table-head text-right" width="10%">Nội dung <img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;"></td>
					<td><textarea id="content" name="content" class="tinymce form-control" rows="20"><?=$item->content?></textarea></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Nội dung <img src="<?=IMG_URL?>english.png" alt="" style="width:15px;"></td>
					<td><textarea id="content_en" name="content_en" class="tinymce form-control" rows="20"><?=$item->content_en?></textarea></td>
				</tr>
				<td class="table-head text-right" width="10%">SEO</td>
					<td>
						<div class="seo-panel">
							<p class="title"><input type="text" id="meta_title" name="meta_title" class="form-control seo-control" maxlength="70" value="<?=$item->meta_title?>" placeholder="Title..."></p>
							<p class="url"><?=BASE_URL?>/.../<?=$item->alias?>.html</p>
							<p class="keywords"><input type="text" id="meta_key" name="meta_key" class="form-control seo-control" maxlength="255" value="<?=$item->meta_key?>" placeholder="Keywords..."></p>
							<p class="description"><input type="text" id="meta_des" name="meta_des" class="form-control seo-control" maxlength="160" value="<?=$item->meta_des?>" placeholder="Description..."></p>
						</div>
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
							$("#active").val("<?=$item->active?>");
						</script>
					</td>
				</tr>
			</table>
		</form>
		<br>
	</div>
</div>

<script>
$(document).ready(function() {
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
	$(".btn-save").click(function(){
		submitButton("save");
	});
	$(".btn-cancel").click(function(){
		submitButton("cancel");
	});
});
</script>