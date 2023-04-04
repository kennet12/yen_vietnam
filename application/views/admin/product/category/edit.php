<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title"> Danh mục sản phẩm</h1>
		<form id="frm-admin" name="adminForm" action="" method="POST">
			<input type="hidden" id="task" name="task" value="">
			<table class="table table-bordered">
				<tr>
					<td class="table-head text-right" width="10%">Mã danh mục</td>
					<td><input type="text" id="code" name="code" class="form-control" value="<?=!empty($item->code)?$item->code:''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Tên danh mục</td>
					<td>
						<table class="table table-bordered">
							<tr>
								<td width="30px" class="text-center">
									<img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;">
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
									<img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;">
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
					<td class="table-head text-right">Danh mục cha</td>
					<td>
						<select id="parent_id" name="parent_id" class="form-control">
							<option value="0"> Dạnh mục gốc</option>
							<?
								function level_indent($level) {
									for ($i=0; $i<$level; $i++) {
										echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; // 6 spaces
									}
								}
								function print_categories($obj, $categories, $curr_category_id, $level) {
									foreach ($categories as $category) {
										if ($category->id == $curr_category_id) {
											continue;
										}
										?>
										<option value="<?=$category->id?>"><?=level_indent($level).($level?"|&rarr; ":"")?><?=$category->name?></option>
										<?
										$child_category_info = new stdClass();
										$child_category_info->parent_id = $category->id;
										$child_categories = $obj->m_product_category->items($child_category_info);
										print_categories($obj, $child_categories, $curr_category_id, $level+1);
									}
								}
								$category_info = new stdClass();
								$category_info->parent_id = 0;
								$categories = $this->m_product_category->items($category_info);
								print_categories($this, $categories, $item->id, 0);
							?>
						</select>
						<script type="text/javascript">
							$("#parent_id").val("<?=$item->parent_id?>");
						</script>
					</td>
				</tr>
				<tr>
					<td class="table-head text-right">Hàng nhập</td>
					<td>
						<select id="order_category" name="order_category" class="form-control">
							<option value="1">Tắt</option>
							<option value="2">Bật</option>
						</select>
						<script type="text/javascript">
							$("#order_category").val("<?=$item->order_category?>");
						</script>
					</td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%"> Mô tả <img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;"></td>
					<td><input type="text" id="description" name="description" class="form-control" value="<?=!empty($item->description)?$item->description:''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%"> Mô tả <img src="<?=IMG_URL?>english.png" alt="" style="width:15px;"></td>
					<td><input type="text" id="description_en" name="description_en" class="form-control" value="<?=!empty($item->description_en)?$item->description_en:''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Từ khóa</td>
					<td>
						<div class="seo-panel">
							<p class="keywords"><input type="text" id="meta_key" name="meta_key" class="form-control seo-control" maxlength="255" value="<?=!empty($item->meta_key) ? $item->meta_key : ''?>" placeholder="Keywords..."></p>
						</div>
					</td>
				</tr>
				<tr>
					<td class="table-head text-right"></td>
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