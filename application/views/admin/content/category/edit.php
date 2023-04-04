<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title"><?=$title?></h1>
		<form id="frm-admin" name="adminForm" action="" method="POST">
			<input type="hidden" id="task" name="task" value="">
			<table class="table table-bordered">
				<tr>
					<td class="table-head text-right" width="10%"> Tên danh mục</td>
					<td>
						<table class="table table-bordered">
							<tr>
								<td width="30px" class="text-center">
									<img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;">
								</td>
								<td>
									<input type="text" id="name" name="name" class="form-control" value="<?=!empty($item->name) ? $item->name : ''?>">
								</td>
							</tr>
							<tr>
								<td width="30px" class="text-center">
									<img src="<?=IMG_URL?>english.png" alt="" style="width:15px;">
								</td>
								<td>
									<input type="text" id="name_en" name="name_en" class="form-control" value="<?=!empty($item->name_en) ? $item->name_en : ''?>">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%"> Url alias</td>
					<td>
						<table class="table table-bordered">
							<tr>
								<td width="30px" class="text-center">
									<img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;">
								</td>
								<td>
									<input type="text" id="alias" name="alias" class="form-control" value="<?=!empty($item->alias) ? $item->alias : ''?>">
								</td>
							</tr>
							<tr>
								<td width="30px" class="text-center">
									<img src="<?=IMG_URL?>english.png" alt="" style="width:15px;">
								</td>
								<td>
									<input type="text" id="alias_en" name="alias_en" class="form-control" value="<?=!empty($item->alias_en) ? $item->alias_en : ''?>">
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
							$("#active").val("<?=$item->active?>");
						</script>
					</td>
				</tr>
			</table>
			<div class="text-right">
				<button class="btn btn-sm btn-primary btn-save">Lưu</button>
				<button class="btn btn-sm btn-default btn-cancel">Hủy</button>
			</div>
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