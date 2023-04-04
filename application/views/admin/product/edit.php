<?
	$old_photos = array();
	if (!empty($item->id)) {
		$info = new stdClass();
		$info->product_id = $item->id;
		$old_photos = $this->m_product_gallery->items($info);
	}
	foreach ($old_photos as $old_photo) {
		$photo_list[] = $old_photo->file_path;
	}
	function get_list_cate_id($category,$obj,$str){
		if (!empty($category)){
			$cate_parent = $obj->m_product_category->load($category->parent_id);
			get_list_cate_id($cate_parent, $obj,$str.'-"'.$category->id.'"');
		} else {
			echo $str;
		}	
	}
	function get_list_cate_code($category,$obj,$str){
		if (!empty($category)){
			$cate_parent = $obj->m_product_category->load($category->parent_id);
			get_list_cate_code($cate_parent, $obj,$str.'-'.$category->code);
		} else {
			echo $str;
		}	
	}
?>
<div class="container-fluid">
	<h1 class="page-title">Thêm  Sản phẩm</h1>
	<? if (empty($item)) { ?>
	<p class="help-block">Not found item.</p>
	<? } else { ?>
	<form id="frm-admin" name="adminForm" action="" method="POST" enctype="multipart/form-data">
		<input type="hidden" id="task" name="task" value="">
		<table class="table table-bordered"><tr>
			<!-- <tr>
				<td class="table-head text-right">Danh mục chính</td>
				<td>
					<?
					// $cate_id = json_decode($item->category_id);
					// if (!empty($cate_id)){
					// 	echo $this->m_product_category->load(end($cate_id))->name;
					// } else {
					// 	echo $category_item->name;
					// }
					?>
				</td>
			</tr> -->
			<tr>
				<td class="table-head text-right">Danh mục chính</td>
				<td>
					<select id="category_main" name="category_main" class="form-control">
						<!-- <option value="0"> Dạnh mục gốc</option> -->
						<?
							function level_indent($level) {
								for ($i=0; $i<$level; $i++) {
									echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; // 6 spaces
								}
							}
							function print_categories($obj, $categories, $level,$str) {
								foreach ($categories as $category) {
									$id = '"'.$category->id.'"';
									$r = $str."{$id}";
									// if ($category->id == $curr_category_id) {
									// 	continue;
									// }
									?>
									<option value='<?=$r?>'><?=level_indent($level).($level?"|&rarr; ":"")?><?=$category->name?></option>
									<?
									$child_category_info = new stdClass();
									$child_category_info->parent_id = $category->id;
									$child_categories = $obj->m_product_category->items($child_category_info);
									print_categories($obj, $child_categories, $level+1,$str."{$id},");
								}
							}
							$category_info = new stdClass();
							$category_info->parent_id = 0;
							$categories = $this->m_product_category->items($category_info);
							
							print_categories($this, $categories, 0,'');
							$category_id = str_replace('[','',$item->category_id);
							$category_id = str_replace(']','',$category_id);
						?>
					</select>
					<script type="text/javascript">
						$("#category_main").val('<?=!empty($item->category_id)?$category_id:'0'?>');
					</script>
				</td>
			</tr>
			<td class="table-head text-right" width="10%">Mã</td>
				<? if(!empty($item->code_product)) { ?>
				<td>
					<input type="hidden" id="code" name="code" class="form-control" value="<?=$item->code?>">
					<input type="hidden" id="code_product" name="code_product" class="form-control" value="<?=$item->code_product?>">
					<strong class="code-label"><?=$item->code_product?></strong>
				</td>	
				<? } else { ?>
				<td>
					<input type="hidden" id="code-temp" cate_id='<?get_list_cate_id($category_item,$this,'')?>' value="<?get_list_cate_code($category_item,$this,'')?>">
					<input type="hidden" id="code" name="code" class="form-control" value="">
					<input type="hidden" id="code_product" name="code_product" class="form-control" value="">
					<script>
						$(document).ready(function() {
							var list_id = $('#code-temp').attr('cate_id').split('-');
							var str_id = '';
							
							for(var i=list_id.length-1;i>=0;i--){
								if (list_id[i]!='') {
									str_id+=list_id[i];
									if(i>1)
									str_id+=',';
								}
							}
							$('#category_main').val(str_id);
							//
							var val = $('#code-temp').val().split('-');
							var str = '';
							var r = (Math.floor(Math.random() * 9) + 1).toString();
							var d = (Math.floor(Math.random() * 9) + 1).toString();
							var product_id = 1000+<?=$this->m_product->get_auto_incre()?>;						
							for(var i=val.length-1;i>=0;i--){
								str+=val[i];
							}
							if (str.length == '2'){
								str+='000';	
							} else if (str.length == '3') {
								str+='00';
							}
							str+=product_id;
							str+=r+d;
							$('#code').val(str);
							$('#code_product').val(str);
							$('#photo-detail-modal-title').html(str);
							$('.code-label').html(str);
						});
					</script>
					<strong class="code-label"></strong>
				</td>
				<? } ?>
			</tr>
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
				<td class="table-head text-right" width="10%">Hình ảnh</td>
				<td>
					<div class="form-group">
						<div class="row">
							<? for ($i=0; $i<6; $i++) {
								$bg_img = '';
								$photo_name = '';
								?>
							<div class="col-xs-6 col-sm-2">
								<div class="fileupload">
									<a class="btn-file-upload" file-id="<?=$i?>">
									<? $bg_style=""; 
									if(!empty($photo_list[$i])){
										$photo_path = str_replace('./files','/files',$photo_list[$i]);
										$bg_style = "background-image: url('".BASE_URL."{$photo_path}');";
									}?>
										<div class="bg <?=(!empty($photo_list[$i]) ? "selected" : "")?>" id="bg-<?=$i?>" style="<?=$bg_style?>"></div>
									</a>
									<a class="close btn-file-close" file-id="<?=$i?>"><i class="fa fa-times"></i></a>
									<input type="hidden" id="hidden-userfile-<?=$i?>" name="hidden-userfile-<?=$i?>" value="<?=(!empty($photo_list[$i]) ? $photo_list[$i] : "")?>" />
									<input type="file" class="userfile" id_item="<?=!empty($item) ? $item->id : 0?>" id="userfile-<?=$i?>" old_photo="<?=(!empty($photo_list[$i]) ? $photo_list[$i] : "")?>" name="userfile_<?=$i?>" file-id="<?=$i?>" style="visibility: hidden;" />
								</div>
							</div>
							<? } ?>
						</div>
					</div>
				</td>
			</tr>
			<!-- <tr>
				<td class="table-head text-right" width="10%">Nguồn gốc xuất xứ</td>
				<td>
					<table class="table table-bordered">
						<tr>
							<td width="30px" class="text-center">
								<img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;">
							</td>
							<td>
								<input type="text" id="origin" name="origin" class="form-control" value="<?=!empty($item->origin) ? $item->origin : ''?>">
							</td>
						</tr>
						<tr>
							<td width="30px" class="text-center">
								<img src="<?=IMG_URL?>english.png" alt="" style="width:15px;">
							</td>
							<td>
								<input type="text" id="origin_en" name="origin_en" class="form-control" value="<?=!empty($item->origin_en) ? $item->origin_en : ''?>">
							</td>
						</tr>
					</table>
				</td>
			</tr> -->
			<?
				$list_category = !empty($item->list_category)?$item->list_category:'';
				$arr_category_id = str_replace('[','',$list_category) ;
				$arr_category_id = str_replace(']','',$arr_category_id) ;
				function gen_category_menu_product($product_categories, $obj, $arr_category_id,$str,$str_k) {
					foreach ($product_categories as $product_category) {
						$id = '"'.$product_category->id.'"';
						$key = $product_category->meta_key;
						$r = $str."{$id}";
						$k = $str_k."{$key}";
						if ($product_category->parent_id == 0){
							echo '<div class="col-md-3">';
								echo '<ul class="list">';
						}
						$child_category_info = new stdClass();
						$child_category_info->parent_id = $product_category->id;
						$child_categories = $obj->m_product_category->items($child_category_info,1,null,null,'order_num','ASC');

						if (!empty($child_categories)) {
							echo "<li>
									<div class='checkbox'>
										<label>
											<input type='checkbox' class='transition filter' disabled status='danh_muc' key='{$k}' value='{$r}'>
											{$product_category->name}
										</label>
									</div>
									<ul class='list' style='padding-left:20px;'>";
									gen_category_menu_product($child_categories, $obj, $arr_category_id, $str."{$id},", $str_k."{$key},");
							echo '	</ul>
								</li>';
						} else {
							$check = (strpos($arr_category_id, $r) !== false)?'checked':'';
							echo "<li>
									<div class='checkbox'>
										<label>
											<input type='checkbox' class='transition filter' status='danh_muc' {$check} key='{$k}' value='{$r}'>
											{$product_category->name}							
										</label>
									</div>
								</li>";
						}
						if ($product_category->parent_id == 0){
								echo '</ul>';
							echo '</div>';
						}
					}
				}
				?>
			<tr>
				<td class="table-head text-right" width="10%">Danh mục</td>
				<td>
					<div id="wrap-list-category">
						<input type="hidden" id="list_category" name="list_category" value='<?=$arr_category_id?>'>
						<div class="row">
						<? 
							$category_info = new stdClass();
							$category_info->parent_id = 0;
							$categories = $this->m_product_category->items($category_info);
							gen_category_menu_product($categories, $this, $arr_category_id,'','');?>
						</div>
						<script type="text/javascript">
							$("#category_id").val('<?=!empty($item->category_id)?$item->category_id:$category_item->id?>');
						</script>
					</div>
				</td>
			</tr>
			<script>
				$(".filter").click(function(){
					var value = $(this).val();
					var list = $('#list_category').val();
					var value_key = $(this).attr('key');
					var key = $('#meta_key').val().trim();
					var arr_key = value_key.split(',');
					if ($(this).is(":checked")) {
						if (list == '') {
							list += value;
						} else {
							list += ','+value;
						}
						//
						for (var i=0;i<arr_key.length;i++) {
							if (key.indexOf(arr_key[i]) == -1) {
								if (key[key.length - 1] == ',') {
									key += arr_key[i];
								} else {
									key += ','+arr_key[i];
								}
							}	
						}
					} else {
						list = list.replace(','+value+',',',');
						list = list.replace(','+value,'');
						list = list.replace(value+',','');
						list = list.replace(value,'');
						//
						for (var i=0;i<arr_key.length;i++) {
							key = key.replace(','+arr_key[i]+',',',');
							key = key.replace(','+arr_key[i],'');
							key = key.replace(arr_key[i]+',','');
							key = key.replace(arr_key[i],'');
						}
					}
					$('#list_category').val(list);
					$('#meta_key').val(key);
				});
			</script>
			<!-- <tr>
				<td class="table-head text-right" width="10%">Nhãn hiệu</td>
				<td>
					<div class="row">
						<? foreach ($brands as $brand) { ?>
						<div class="col-md-2">
							<div class="radio">
								<label><input type="radio" name="product_brand" value="<?=$brand->alias?>" <?=($item->brand == $brand->alias)?'checked':''?>><?=$brand->name?></label>
							</div>
						</div>
						<? } ?>
					</div>
				</div>
				</td>
			</tr>
			<tr>
				<td class="table-head text-right">Hàng nhập</td>
					<td>
						<select id="order_product" name="order_product" class="form-control">
							<option value="1">Tắt</option>
							<option value="2">Bật</option>
						</select>
						<script type="text/javascript">
							$("#order_product").val("<?=$item->order_product?>");
						</script>
					</td>
				</tr>
			<tr> -->
			<td class="table-head text-right" width="10%">Đặc tính SP</td>
				<td>
					<div class="row">
						<? $subtypename = array(); $c_product_types = count($product_types);?>
						<div class="col-md-6">
							<div class="wrap-type">
								<h6 class="type-1" qty="<?=$c_product_types?>">Nhóm 1</h6>
								<div class="list-item">
									<input type="text" name-item="name_categorize1" name="name_categorize1" class="form-control name-categorize1 keyupvalue" value="<?=$item->typename?>" placeholder="Tên nhóm 1">
									<hr size="1" color="white">
									<div class="item-categorizeite item-categorizeite1">
										<? $i=1; foreach ($product_types as $product_type) { 
											if ($i==1) $subtypename = ($product_type->subtypename != '""') ? json_decode($product_type->subtypename) : array(); ?>
										<div class="item">
											<div class="item-left">
												<input type="text" name-item="item-<?=$i?>" name="typename[]" class="form-control keyupvalue item-<?=$i?>" value="<?=$product_type->typename?>" placeholder="item">
											</div>
											<div class="item-right">
												<a class="text-color-red"><i stt="<?=$i?>" type="1" class="fa fa-trash-o" aria-hidden="true"></i></a>
											</div>
										</div>
										<? $i++;} ?>
									</div>
								</div>
								<a class="btn-pl" type="1" style="cursor:pointer">+ Thêm</a>
							</div>
						</div>
						<? $c_subtypename = count($subtypename); ?>
						<div class="col-md-6">
							<div class="wrap-type">
								<h6 class="type-2" qty="<?=$c_subtypename?>">Nhóm 2</h6>
								<div class="list-item">
									<input type="text" name-item="name_categorize2" name="name_categorize2" class="form-control name-categorize2 keyupvalue" value="<?=$item->subtypename?>" placeholder="Tên nhóm 2">
									<hr size="1" color="white">
									<div class="item-categorizeite item-categorizeite2">
										<? $i=1; foreach ($subtypename as $subname) { ?>
										<div class="item">
											<div class="item-left">
												<input type="text" name-item="item2-<?=$i?>" name="subtypename[]" class="form-control item2-<?=$i?> keyupvalue" value="<?=$subname?>" placeholder="item">
											</div>
											<div class="item-right">
												<a class="text-color-red"><i stt="<?=$i?>" type="2" class="fa fa-trash-o" aria-hidden="true"></i></a>
											</div>
										</div>
										<? $i++;} ?>
									</div>
								</div>
								<a class="btn-pl" type="2" style="cursor:pointer">+ Thêm</a>
							</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%"></td>
				<td>
					<div class="html-list-product">
						<? if (empty($subtypename) || $subtypename=='') { ?>
						<table class="table table-bordered list-product-cate">
						  <tr>
						 		<td width="18%" style="background:#eee;" class="name_categorize1"><?=$item->typename?></td>
						        <td width="14%" style="background:#eee;">Giá bán</td>
								<td width="14%" style="background:#eee;">Giảm giá (%)</td>
						        <td width="14%" style="background:#eee;">Số lượng</td>
								<td width="14%" style="background:#eee;">Giá vốn</td>
								<td width="8%" style="background:#eee;">Hình</td>
					      </tr>
					      <? $i=1; foreach ($product_types as $product_type) { 
						   $price 		= json_decode($product_type->price);
						   $quantity 	= json_decode($product_type->quantity);
						   $sale 		= json_decode($product_type->sale); 
						   $cost 		= json_decode($product_type->cost);
						   $photo 		= json_decode($product_type->photo);?>
					      <tr>
					        <td width="18%" rowspan="1" class="item-<?=$i?>"><?=$product_type->typename?></td>
					        <td width="14%">
					        	<input type="text" name="price<?=$i?>[]" class="form-control price-<?=$i?>" value="<?=$price[0]?>">
					        </td>
							<td width="14%">
					        	<input type="text" name="sale<?=$i?>[]" class="form-control sale-<?=$i?>" value="<?=!empty($sale[0])?$sale[0]:0?>">
					        </td>
					        <td width="14%">
					        	<input type="number" name="qty<?=$i?>[]" class="form-control qty-<?=$i?>" value="<?=$quantity[0]?>">
					        </td>
					        <td width="14%">
					        	<input type="text" name="cost<?=$i?>[]" class="form-control cost-<?=$i?>" value="<?=!empty($cost[0])?$cost[0]:0?>">
					        </td>
							<td width="8%">
								<input type="hidden" name="photo<?=$i?>[]" class="photo photo-<?=$i?>" value="<?=!empty($photo[0])?$photo[0]:''?>">
								<div class="photo-detail photo-detail-<?=$i?>" data-toggle="modal" data-target="#photo-detail-modal" stt="<?=$i?>" style="background:url('<?=!empty($photo[0])?BASE_URL.$photo[0]:IMG_URL.'images-default.jpg'?>')"></div>
					        </td>
					      </tr>
					      <? $i++;} ?>
						</table>
						<? } else { ?>
						<table class="table table-bordered list-product-cate">
							  <tr>
						        <td width="18%" style="background:#eee;" class="name_categorize1"><?=$item->typename?></td>
						        <td width="18%" style="background:#eee;" class="name_categorize2"><?=$item->subtypename?></td>
						        <td width="14%" style="background:#eee;">Giá bán</td>
								<td width="14%" style="background:#eee;">Giảm giá (%)</td>
						        <td width="14%" style="background:#eee;">Số lượng</td>
								<td width="14%" style="background:#eee;">Giá vốn</td>
								<td width="8%" style="background:#eee;">Hình</td>
						      </tr>
						      <? $i=1; foreach ($product_types as $product_type) { 
						      		$price 		= json_decode($product_type->price);
								 	$quantity 	= json_decode($product_type->quantity);
									$sale 		= json_decode($product_type->sale);
									$cost 		= json_decode($product_type->cost);
									$photo 		= json_decode($product_type->photo);?>
						      	  <? $j=0; foreach ($subtypename as $subname) { ?>
							      	  <? if ($j == 0) { ?>
								      <tr>
								        <td width="18%" class="item-<?=$i?>" rowspan="<?=$c_subtypename?>"><?=$product_type->typename?></td>
								        <td width="18%" class="item2-<?=$j+1?>"><?=$subname?></td>
								        <td width="14%">
								        	<input type="text" name="price<?=$i?>[]" class="form-control price-<?=$i?>-<?=$j+1?>" value="<?=$price[$j]?>">
								        </td>
										<td width="14%">
								        	<input type="text" name="sale<?=$i?>[]" class="form-control sale-<?=$i?>-<?=$j+1?>" value="<?=!empty($sale[$j])?$sale[$j]:0?>">
								        </td>
								        <td width="14%">
								        	<input type="number" name="qty<?=$i?>[]" class="form-control qty-<?=$i?>-<?=$j+1?>" value="<?=$quantity[$j]?>">
								        </td>
										<td width="14%">
								        	<input type="text" name="cost<?=$i?>[]" class="form-control cost-<?=$i?>-<?=$j+1?>" value="<?=!empty($cost[$j])?$cost[$j]:0?>">
								        </td>
										<td width="8%">
											<input type="hidden" name="photo<?=$i?>[]" class="photo photo-<?=$i?>-<?=$j+1?>" value="<?=!empty($photo[$j])?$photo[$j]:''?>">
											<div class="photo-detail photo-detail-<?=$i?>-<?=$j+1?>" data-toggle="modal" data-target="#photo-detail-modal" stt="<?=$i?>-<?=$j+1?>" style="background:url('<?=!empty($photo[$j])?BASE_URL.$photo[$j]:IMG_URL.'images-default.jpg'?>')"></div>
										</td>
								      </tr>
								 	  <? } else { ?>
							      	  <tr>
								        <td width="18%" class="item2-<?=$j+1?>"><?=$subname?></td>
								        <td width="14%">
								        	<input type="text" name="price<?=$i?>[]" class="form-control price-<?=$i?>-<?=$j+1?>" value="<?=$price[$j]?>">
								        </td>
										<td width="14%">
								        	<input type="text" name="sale<?=$i?>[]" class="form-control sale-<?=$i?>-<?=$j+1?>" value="<?=$sale[$j]?>">
								        </td>
								        <td width="14%">
								        	<input type="number" name="qty<?=$i?>[]" class="form-control qty-<?=$i?>-<?=$j+1?>" value="<?=$quantity[$j]?>">
								        </td>
										<td width="14%">
								        	<input type="text" name="cost<?=$i?>[]" class="form-control cost-<?=$i?>-<?=$j+1?>" value="<?=$cost[$j]?>">
								        </td>
										<td width="8%">
											<input type="hidden" name="photo<?=$i?>[]" class="photo photo-<?=$i?>-<?=$j+1?>" value="<?=!empty($photo[$j])?$photo[$j]:''?>">
											<div class="photo-detail photo-detail-<?=$i?>-<?=$j+1?>" data-toggle="modal" data-target="#photo-detail-modal" stt="<?=$i?>-<?=$j+1?>" style="background:url('<?=!empty($photo[$j])?BASE_URL.$photo[$j]:IMG_URL.'images-default.jpg'?>')"></div>
										</td>
								      </tr>
								      <? } ?>
							      <? $j++;} ?>
						  	  <? $i++;} ?>
						</table>
						<? } ?>
					</div>
					<!-- <div class="row">
						<div class="col-md-4">
							<div class="input-group">
								<span class="input-group-addon" id="basic-addon1">Affiliate (%)</span>
								<input type="number" name="affiliate" class="form-control" value="<?=$item->affiliate?>">
							</div>
						</div>
					</div> -->
				</td>
			</tr>
			<script>
				var img_default = '<?=IMG_URL.'images-default.jpg'?>';
				$(document).on('keyup','.keyupvalue',function(){
				    $('.'+$(this).attr('name-item')).html($(this).val());
				});
				$(document).on('click','.btn-pl',function(){
					var typ = parseInt($(this).attr('type'));

					var type_1 = parseInt($('.type-1').attr('qty'));
					var type_2 = parseInt($('.type-2').attr('qty'));
					if (typ == 1) type_1++; else type_2++;
					var str1 = '';
					var str2 = '';
					//
					for (var i=1; i <= type_2; i++) {
					var val_name2 = '';
					if ($('.item2-'+i).val() != undefined) { val_name2 = $('.item2-'+i).val(); }
					str2 += '<div class="item">';
						str2 += '<div class="item-left">';
							str2 += '<input type="text" name-item="item2-'+i+'" name="subtypename[]" class="form-control item2-'+i+' keyupvalue" value="'+val_name2+'" placeholder="item">';
						str2 += '</div>';
						str2 += '<div class="item-right">';
							str2 += '<a class="text-color-red"><i stt="'+i+'" type="2" class="fa fa-trash-o" aria-hidden="true"></i></a>';
						str2 += '</div>';
					str2 += '</div>';
					}

					var str_list = '<table class="table table-bordered list-product-cate"><tr>';
						str_list += '<td width="18%" style="background:#eee;" class="name_categorize1">'+$('.name-categorize1').val()+'</td>';
						if (type_2 >= 1) {
						str_list += '<td width="18%" style="background:#eee;" class="name_categorize2">'+$('.name-categorize2').val()+'</td>';
						}
						str_list += '<td width="14%" style="background:#eee;">Giá bán</td><td width="14%" style="background:#eee;">Giảm giá (%)</td><td width="14%" style="background:#eee;">Số lượng</td><td width="14%" style="background:#eee;">Giá vốn</td><td width="8%" style="background:#eee;">Hình</td>';
						for (var i=1; i <= type_1; i++) {
							var val_name = '';
							if ($('.item-'+i).val() != undefined) { val_name = $('.item-'+i).val(); }
							//
							str1 += '<div class="item">';
								str1 += '<div class="item-left">';
									str1 += '<input type="text" name-item="item-'+i+'" name="typename[]" class="form-control item-'+i+' keyupvalue" value="'+val_name+'" placeholder="item">';
								str1 += '</div>';
								str1 += '<div class="item-right">';
									str1 += '<a class="text-color-red"><i stt="'+i+'" type="1" class="fa fa-trash-o" aria-hidden="true"></i></a>';
								str1 += '</div>';
							str1 += '</div>';
							//
							if (type_2 > 0) {
								for (var j=1; j<=type_2; j++) {
									var val_name2 = '';
									if ($('.item2-'+j).val() != undefined) { val_name2 = $('.item2-'+j).val(); }
									var val_price = '0';
									if ($('.price-'+i+'-'+j).val() != undefined) { val_price = $('.price-'+i+'-'+j).val(); }
									var val_qty = '0';
									if ($('.qty-'+i+'-'+j).val() != undefined) { val_qty = $('.qty-'+i+'-'+j).val(); }
									var val_sale = '0';
									if ($('.sale-'+i+'-'+j).val() != undefined) { val_sale = $('.sale-'+i+'-'+j).val(); }
									var val_cost = '0';
									if ($('.cost-'+i+'-'+j).val() != undefined) { val_cost = $('.cost-'+i+'-'+j).val(); }
									var val_photo = '';
									if ($('.photo-'+i+'-'+j).val() != undefined) { val_photo = $('.photo-'+i+'-'+j).val(); }
									var bg_photo = img_default;
									if (val_photo != '') { bg_photo = '<?=BASE_URL?>'+val_photo }
									str_list += '<tr>';
										if (j == 1) {
										str_list += '<td width="18%" rowspan="'+type_2+'" class="item-'+i+'">'+val_name+'</td>';
										}
										str_list += '<td width="18%" class="item2-'+j+'">'+val_name2+'</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="price'+i+'[]" class="form-control price-'+i+'-'+j+'" value="'+val_price+'">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="sale'+i+'[]" class="form-control sale-'+i+'-'+j+'" value="'+val_sale+'">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="number" name="qty'+i+'[]" class="form-control qty-'+i+'-'+j+'" value="'+val_qty+'">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="cost'+i+'[]" class="form-control cost-'+i+'-'+j+'" value="'+val_cost+'">';
										str_list += '</td>';
										str_list += '<td width="8%">';
											str_list += '<input type="hidden" name="photo'+i+'[]" class="photo photo-'+i+'-'+j+'" value="'+val_photo+'">';
											str_list += '<div class="photo-detail photo-detail-'+i+'-'+j+'" data-toggle="modal" data-target="#photo-detail-modal" stt="'+i+'-'+j+'" style="background:url('+bg_photo+')"></div>';
										str_list += '</td>';
									str_list += '</tr>';
								}
							} else {
								var val_price = '0';
								if ($('.price-'+i).val() != undefined) { val_price = $('.price-'+i).val(); }
								var val_qty = '0';
								if ($('.qty-'+i).val() != undefined) { val_qty = $('.qty-'+i).val(); }
								var val_sale = '0';
								if ($('.sale-'+i).val() != undefined) { val_sale = $('.sale-'+i).val(); }
								var val_cost = '0';
								if ($('.cost-'+i).val() != undefined) { val_cost = $('.cost-'+i).val(); }
								var val_photo = '';
								if ($('.photo-'+i).val() != undefined) { val_photo = $('.photo-'+i).val(); }
								var bg_photo = img_default;
								if (val_photo != '') { bg_photo = '<?=BASE_URL?>'+val_photo }
								str_list += '<tr>';
									str_list += '<td width="18%" rowspan="1" class="item-'+i+'">'+val_name+'</td>';
									str_list += '<td width="14%">';
										str_list += '<input type="text" name="price'+i+'[]" class="form-control price-'+i+'" value="'+val_price+'">';
									str_list += '</td>';
									str_list += '<td width="14%">';
										str_list += '<input type="text" name="sale'+i+'[]" class="form-control sale-'+i+'" value="'+val_sale+'">';
									str_list += '</td>';
									str_list += '<td width="14%">';
										str_list += '<input type="number" name="qty'+i+'[]" class="form-control qty-'+i+'" value="'+val_qty+'">';
									str_list += '</td>';
									str_list += '<td width="14%">';
										str_list += '<input type="text" name="cost'+i+'[]" class="form-control cost-'+i+'" value="'+val_cost+'">';
									str_list += '</td>';
									str_list += '<td width="8%">';
										str_list +='<input type="hidden" name="photo'+i+'[]" class="photo photo-'+i+'" value="'+val_photo+'">';
										str_list += '<div class="photo-detail photo-detail-'+i+'" data-toggle="modal" data-target="#photo-detail-modal" stt="'+i+'" style="background:url('+bg_photo+')"></div>';
									str_list += '</td>';
								str_list += '</tr>';
							}	
						}
						str_list += '</table>';
					if (typ == 1) {
						$('.item-categorizeite1').html(str1);
						$('.type-1').attr('qty',parseInt($('.type-1').attr('qty'))+1);
					} else {
						$('.item-categorizeite2').html(str2);
						$('.type-2').attr('qty',parseInt($('.type-2').attr('qty'))+1);
					}
					$('.html-list-product').html(str_list);
					
				});
				$(document).on('click','.photo-detail',function(){
					let stt = $(this).attr('stt');
					let code = $('#code').val();
					$.ajax({
						url: '<?=site_url('syslog/load-img-product-detail');?>',
						type: 'POST',
						dataType: 'json',
						data: {code: code},
						success: (res) => {
							var str = '';
							for(var i=0;i<res.length;i++){
								str += '<li class="item-photo">';
									str += '<img src="<?=BASE_URL?>'+res[i]+'" stt="'+stt+'" data-src="'+res[i]+'" alt="" data-dismiss="modal">';
								str += '</li>';
							}
							$('.list-photo').html(str);
						}
					});
				});
				$(document).on('click','.item-photo > img',function(){
					let data = $(this).attr('data-src');
					let stt = $(this).attr('stt');
					$('.photo-'+stt).val(data);
					$('.photo-detail-'+stt).css('background','url(<?=BASE_URL?>'+data+')');
				});
				$(document).on('click','.fa-trash-o',function(){
					var typ = parseInt($(this).attr('type'));
					var stt = parseInt($(this).attr('stt'));
					var type_1 = parseInt($('.type-1').attr('qty'));
					var type_2 = parseInt($('.type-2').attr('qty'));
					var str1 = '';
					var str2 = '';

					var str_list = '<table class="table table-bordered list-product-cate"><tr>';
						str_list += '<td width="18%" style="background:#eee;" class="name_categorize1">'+$('.name-categorize1').val()+'</td>';
						if (type_2-1 > 0) {
						str_list += '<td width="18%" style="background:#eee;" class="name_categorize2">'+$('.name-categorize2').val()+'</td>';
						}
						str_list += '<td width="14%" style="background:#eee;">Giá bán</td><td width="14%" style="background:#eee;">Giảm giá (%)</td><td width="14%" style="background:#eee;">Số lượng</td><td width="14%" style="background:#eee;">Giá vốn</td><td width="14%" style="background:#eee;">Hình</td></tr>';
						if (typ == 1) {
							var k = 0;
							var t = 0; 
							for (var i=1; i <= type_1; i++) {
								if (i >= stt) t = 1;
								if (stt == type_1) t = 0; 
								var val_name = '';
								var val_qty = '0';
								var val_price = '0';
								var val_sale = '0';
								var val_cost = '0';
								var val_photo = '';
								var bg_photo = img_default;

								if (i < type_1) { 
									if (i >= stt) {
										k = k + 1;
										if ($('.item-'+(i+1)).val() != undefined) { val_name = $('.item-'+(i+1)).val(); }
										if ($('.price-'+(i+1)).val() != undefined) { val_price = $('.price-'+(i+1)).val(); }		
										if ($('.qty-'+(i+1)).val() != undefined) { val_qty = $('.qty-'+(i+1)).val(); }
										if ($('.sale-'+(i+1)).val() != undefined) { val_sale = $('.sale-'+(i+1)).val(); }		
										if ($('.cost-'+(i+1)).val() != undefined) { val_cost = $('.cost-'+(i+1)).val(); }
										if ($('.photo-'+(i+1)).val() != undefined) { val_photo = $('.photo-'+(i+1)).val(); }
									} else {
										k = i;
										if ($('.item-'+i).val() != undefined) { val_name = $('.item-'+i).val(); }
										if ($('.price-'+i).val() != undefined) { val_price = $('.price-'+i).val(); }		
										if ($('.qty-'+i).val() != undefined) { val_qty = $('.qty-'+i).val(); }
										if ($('.sale-'+i).val() != undefined) { val_sale = $('.sale-'+i).val(); }		
										if ($('.cost-'+i).val() != undefined) { val_cost = $('.cost-'+i).val(); }
										if ($('.photo-'+i).val() != undefined) { val_photo = $('.photo-'+i).val(); }
									}
									if (val_photo != '') { bg_photo = '<?=BASE_URL?>'+val_photo }
									str1 += '<div class="item">';
										str1 += '<div class="item-left">';
											str1 += '<input type="text" name-item="item-'+k+'" name="typename[]" class="form-control item-'+i+' keyupvalue" value="'+val_name+'" placeholder="item">';
										str1 += '</div>';
										str1 += '<div class="item-right">';
											str1 += '<a class="text-color-red"><i stt="'+k+'" type="1" class="fa fa-trash-o" aria-hidden="true"></i></a>';
										str1 += '</div>';
									str1 += '</div>';
									//
									if (type_2 > 0) {
										for (var j=1; j<=type_2; j++) {
											var val_name2 = '';
											if ($('.item2-'+j).val() != undefined) { val_name2 = $('.item2-'+j).val(); }
											var val_price = '0';
											var val_qty = '0';
											var val_sale = '0';
											var val_cost = '0';
											var val_photo = '';
											var bg_photo = img_default;
											if (i < stt){
												if ($('.price-'+i+'-'+j).val() != undefined) { val_price = $('.price-'+i+'-'+j).val(); }
												if ($('.qty-'+i+'-'+j).val() != undefined) { val_qty = $('.qty-'+i+'-'+j).val(); }
												if ($('.sale-'+i+'-'+j).val() != undefined) { val_sale = $('.sale-'+i+'-'+j).val(); }
												if ($('.cost-'+i+'-'+j).val() != undefined) { val_cost = $('.cost-'+i+'-'+j).val(); }
												if ($('.photo-'+i+'-'+j).val() != undefined) { val_photo = $('.photo-'+i+'-'+j).val(); }
											} else {
												if ($('.price-'+(i+t)+'-'+j).val() != undefined) { val_price = $('.price-'+(i+t)+'-'+j).val(); }
												if ($('.qty-'+(i+t)+'-'+j).val() != undefined) { val_qty = $('.qty-'+(i+t)+'-'+j).val(); }
												if ($('.sale-'+(i+t)+'-'+j).val() != undefined) { val_sale = $('.sale-'+(i+t)+'-'+j).val(); }
												if ($('.cost-'+(i+t)+'-'+j).val() != undefined) { val_cost = $('.cost-'+(i+t)+'-'+j).val(); }
												if ($('.photo-'+(i+t)+'-'+j).val() != undefined) { val_photo = $('.photo-'+(i+t)+'-'+j).val(); }
											}
											if (val_photo != '') { bg_photo = '<?=BASE_URL?>'+val_photo }
											str_list += '<tr>';
												if (j == 1) {
												str_list += '<td width="18%" rowspan="'+type_2+'" class="item-'+i+'">'+val_name+'</td>';
												}
												str_list += '<td width="18%" class="item2-'+j+'">'+val_name2+'</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="price'+i+'[]" class="form-control price-'+i+'-'+j+'" value="'+val_price+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="sale'+i+'[]" class="form-control sale-'+i+'-'+j+'" value="'+val_sale+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="number" name="qty'+i+'[]" class="form-control qty-'+i+'-'+j+'" value="'+val_qty+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="cost'+i+'[]" class="form-control cost-'+i+'-'+j+'" value="'+val_cost+'">';
												str_list += '</td>';
												str_list += '<td width="8%">';
													str_list += '<input type="hidden" name="photo'+i+'[]" class="photo photo-'+i+'-'+j+'" value="'+val_photo+'">';
													str_list += '<div class="photo-detail photo-detail-'+i+'-'+j+'" data-toggle="modal" data-target="#photo-detail-modal" stt="'+i+'-'+j+'" style="background:url('+bg_photo+')"></div>';
												str_list += '</td>';
											str_list += '</tr>';
										}
									} else {
										var val_price = '0';
										var val_qty = '0';
										var val_sale = '0';
										var val_cost = '0';
										var val_photo = '';
										var bg_photo = img_default;
										if (i < stt){
											if ($('.price-'+i).val() != undefined) { val_price = $('.price-'+i).val(); }
											if ($('.qty-'+i).val() != undefined) { val_qty = $('.qty-'+i).val(); }
											if ($('.sale-'+i).val() != undefined) { val_sale = $('.sale-'+i).val(); }
											if ($('.cost-'+i).val() != undefined) { val_cost = $('.cost-'+i).val(); }
											if ($('.photo-'+i).val() != undefined) { val_photo = $('.photo-'+i).val(); }
										} else {
											if ($('.price-'+(i+t)).val() != undefined) { val_price = $('.price-'+(i+t)).val(); }
											if ($('.qty-'+(i+t)).val() != undefined) { val_qty = $('.qty-'+(i+t)).val(); }
											if ($('.sale-'+(i+t)).val() != undefined) { val_sale = $('.sale-'+(i+t)).val(); }
											if ($('.cost-'+(i+t)).val() != undefined) { val_cost = $('.cost-'+(i+t)).val(); }
											if ($('.photo-'+(i+t)).val() != undefined) { val_photo = $('.photo-'+(i+t)).val(); }
										}
										if (val_photo != '') { bg_photo = '<?=BASE_URL?>'+val_photo }
										str_list += '<tr>';
											str_list += '<td width="18%" rowspan="1" class="item-'+i+'">'+val_name+'</td>';
											str_list += '<td width="14%">';
												str_list += '<input type="text" name="price'+i+'[]" class="form-control price-'+i+'" value="'+val_price+'">';
											str_list += '</td>';
											str_list += '<td width="14%">';
												str_list += '<input type="text" name="sale'+i+'[]" class="form-control sale-'+i+'" value="'+val_sale+'">';
											str_list += '</td>';
											str_list += '<td width="14%">';
												str_list += '<input type="number" name="qty'+i+'[]" class="form-control qty-'+i+'" value="'+val_qty+'">';
											str_list += '</td>';
											str_list += '<td width="14%">';
												str_list += '<input type="text" name="cost'+i+'[]" class="form-control cost-'+i+'" value="'+val_cost+'">';
											str_list += '</td>';
											str_list += '<td width="8%">';
												str_list += '<input type="hidden" name="photo'+i+'[]" class="photo photo-'+i+'" value="'+val_photo+'">';
												str_list += '<div class="photo-detail photo-detail-'+i+'" data-toggle="modal" data-target="#photo-detail-modal" stt="'+i+'" style="background:url('+bg_photo+')"></div>';
											str_list += '</td>';
										str_list += '</tr>';
									}
								}
							}
							$('.item-categorizeite1').html(str1);
							$('.type-1').attr('qty',parseInt($('.type-1').attr('qty'))-1);
						} else {
							var val_name = '';
							var val_name2 = '';
							var val_qty = '0';
							var val_price = '0';
							var val_sale = '0';
							var val_cost = '0';
							var val_photo = '';
							var bg_photo = img_default;
							var k = 0;
							let t = 0;
							for (var i=1; i <= type_2; i++) {
								if (i < type_2) {
									if (i >= stt) t = 1;
									if (stt == type_2) t = 0; 
									if (i >= stt) {
										k = i + t;
									} else {
										k = i;
									}
									if ($('.item2-'+k).val() != undefined) { val_name = $('.item2-'+k).val(); }
									str2 += '<div class="item">';
										str2 += '<div class="item-left">';
											str2 += '<input type="text" name-item="item2-'+k+'" name="subtypename[]" class="form-control item2-'+i+' keyupvalue" value="'+val_name+'" placeholder="item">';
										str2 += '</div>';
										str2 += '<div class="item-right">';
											str2 += '<a class="text-color-red"><i stt="'+k+'" type="2" class="fa fa-trash-o" aria-hidden="true"></i></a>';
										str2 += '</div>';
									str2 += '</div>';
								}
							}
							for (var i=1; i <= type_1; i++) {
								if ($('.item-'+i).val() != undefined) { val_name = $('.item-'+i).val(); }
								if (type_2-1 > 0) {
									for (var j=1; j<=type_2; j++) {
										if (j >= stt) t = 1;
										if (stt == type_2) t = 0;
										if (j < type_2) {
											if (j >= stt) {
												k = j + t;
											} else {
												k = j;
											}
											bg_photo = img_default;
											if ($('.item2-'+k).val() != undefined) { val_name2 = $('.item2-'+k).val(); }
											if ($('.price-'+i+'-'+k).val() != undefined) { val_price = $('.price-'+i+'-'+k).val(); }
											if ($('.qty-'+i+'-'+k).val() != undefined) { val_qty = $('.qty-'+i+'-'+k).val(); }
											if ($('.sale-'+i+'-'+k).val() != undefined) { val_sale = $('.sale-'+i+'-'+k).val(); }
											if ($('.cost-'+i+'-'+k).val() != undefined) { val_cost = $('.cost-'+i+'-'+k).val(); }
											if ($('.photo-'+i+'-'+k).val() != undefined) { val_photo = $('.photo-'+i+'-'+k).val(); }
											if (val_photo != '') { bg_photo = '<?=BASE_URL?>'+val_photo }
											str_list += '<tr>';
												if (j == 1) {
												str_list += '<td width="18%" rowspan="'+(type_2-1)+'" class="item-'+i+'">'+val_name+'</td>';
												}
												str_list += '<td width="18%" class="item2-'+k+'">'+val_name2+'</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="price[]" class="form-control price-'+i+'-'+j+'" value="'+val_price+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="sale[]" class="form-control sale-'+i+'-'+j+'" value="'+val_sale+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="number" name="qty[]" class="form-control qty-'+i+'-'+j+'" value="'+val_qty+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="cost[]" class="form-control cost-'+i+'-'+j+'" value="'+val_cost+'">';
												str_list += '</td>';
												str_list += '<td width="8%">';
													str_list += '<input type="hidden" name="photo'+i+'[]" class="photo photo-'+i+'-'+j+'" value="'+val_photo+'">';
													str_list += '<div class="photo-detail photo-detail-'+i+'-'+j+'" data-toggle="modal" data-target="#photo-detail-modal" stt="'+i+'-'+j+'" style="background:url('+bg_photo+')"></div>';
												str_list += '</td>';
											str_list += '</tr>';
										}
									}
								} else {
									str_list += '<tr>';
										str_list += '<td width="18%" rowspan="1" class="item-'+i+'">'+val_name+'</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="price[]" class="form-control price-'+i+'" value="">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="sale[]" class="form-control sale-'+i+'" value="">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="number" name="qty[]" class="form-control qty-'+i+'" value="">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="cost[]" class="form-control cost-'+i+'" value="">';
										str_list += '</td>';
										str_list += '<td width="8%">';
											str_list += '<input type="hidden" name="photo'+i+'[]" class="photo photo-'+i+'" value="">';
											str_list += '<div class="photo-detail photo-detail-'+i+'" data-toggle="modal" data-target="#photo-detail-modal" stt="'+i+'" style="background:url('+img_default+')"></div>';
										str_list += '</td>';
									str_list += '</tr>';
								}
							}
							$('.item-categorizeite2').html(str2);
							$('.type-2').attr('qty',parseInt($('.type-2').attr('qty'))-1);
						}
						str_list += '</table>';
					$('.html-list-product').html(str_list);
				});
			</script>
			<!-- <tr>
				<td class="table-head text-right" width="10%">Delivery & Return</td>
				<td><textarea id="ship_des" name="ship_des" class="tinymce form-control" rows="5"><?//=$item->ship_des?></textarea></td>
			</tr> -->
			<tr>
				<td class="table-head text-right" width="10%"> Video</td>
				<td>
					<input type="text" id="video" name="video" class="form-control" value="<?=!empty($item->video) ? $item->video : ''?>" placeholder="Url từ youtube">
				</td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Thông tin SP <img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;"></td>
				<td><textarea id="content" name="content" class="tinymce form-control" rows="20"><?=$item->content?></textarea></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Thông tin SP <img src="<?=IMG_URL?>english.png" alt="" style="width:15px;"></td>
				<td><textarea id="content_en" name="content_en" class="tinymce form-control" rows="20"><?=$item->content_en?></textarea></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">SEO</td>
				<td>
					<a id="get_seo" class="btn btn-primary">SEO Tự động</a>
					<br><br>
					<table class="table table-bordered">
						<tr>
							<td width="30px" class="text-center">
								<img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:15px;height: 12px;">
							</td>
							<td>
								<div class="seo-panel">
									<p class="title"><input type="text" id="meta_title" name="meta_title" class="form-control seo-control" maxlength="70" value="<?=$item->meta_title?>" placeholder="Title..."></p>
									<p class="url"><?=BASE_URL?>/<span id="meta_alias"><?=$item->alias?></span>.html</p>
									<p class="keywords"><input type="text" id="meta_key" name="meta_key" class="form-control seo-control" maxlength="255" value="<?=$item->meta_key?>" placeholder="Keywords..."></p>
									<p class="description"><input type="text" id="meta_des" name="meta_des" class="form-control seo-control" maxlength="160" value="<?=$item->meta_des?>" placeholder="Description..."></p>
								</div>
							</td>
						</tr>
						<tr>
							<td width="30px" class="text-center">
								<img src="<?=IMG_URL?>english.png" alt="" style="width:15px;">
							</td>
							<td>
							<div class="seo-panel">
								<p class="title"><input type="text" id="meta_title_en" name="meta_title_en" class="form-control seo-control" maxlength="70" value="<?=$item->meta_title_en?>" placeholder="Title..."></p>
								<p class="url"><?=BASE_URL?>/<span id="meta_alias_en"><?=$item->alias_en?></span>.html</p>
								<p class="keywords"><input type="text" id="meta_key_en" name="meta_key_en" class="form-control seo-control" maxlength="255" value="<?=$item->meta_key_en?>" placeholder="Keywords..."></p>
								<p class="description"><input type="text" id="meta_des_en" name="meta_des_en" class="form-control seo-control" maxlength="160" value="<?=$item->meta_des_en?>" placeholder="Description..."></p>
							</div>
							</td>
						</tr>
					</table>
					<script>
						$('#get_seo').click(function(e) { console.log(123);
							$('#meta_title').val($('#title').val());
							$('#meta_title_en').val($('#title_en').val());
							$('#meta_key').val($('#title').val()+", <?=$category_item->name?>");
							$('#meta_key_en').val($('#title_en').val()+", <?=$category_item->name_en?>");
							$('#meta_des').val("Mua online "+$('#title').val()+" tại <?=SITE_NAME?> miễn phí vận chuyển, hoàn tiền 110% nếu sản phẩm kém chất lượng.");
							$('#meta_des_en').val("Buy online "+$('#title_en').val()+" from <?=SITE_NAME?> free ship, refun money 110% if the product is of poor quality.");
						})
					</script>
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
		</table><br>
		<div class="text-right">
			<a class="btn btn-sm btn-primary btn-save">Cập nhật</a>
			<a class="btn btn-sm btn-default btn-cancel">Hủy</a>
		</div>
		<br><br>
	</form>
	<? } ?>
</div>
<div class="modal fade" id="photo-detail-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="photo-detail-modal-title"><?=!empty($item->code_product)?$item->code_product:''?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-photo"></ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
<script>
	$(document).on('click','.btn-save',function() {
		// jQuery.noConflict();
		event.preventDefault();
		var err = 0;
		var msg = [];

		clearFormError();

		if ($('#title').val() == "") {
			$('#title').addClass("error");
			err++;
			msg.push("Tên sản phẩm không bỏ trống");
		} else {
			$('#title').removeClass("error");
		}

		if ($('#list_category').val() == "") {
			$('#wrap-list-category').addClass("error");
			err++;
			msg.push("Danh mục không được để trống");
		} else {
			$('#wrap-list-category').removeClass("error");
		}

		if ($('.name-categorize1').val() == "") {
			$('.name-categorize1').addClass("error");
			err++;
			msg.push("Đặc tính không được bỏ trống");
		}
		else {
			$('.name-categorize1').removeClass("error");
		}

		var type_1 = $('.type-1').attr('qty');
		if (type_1 == 0) {
			err++;
			msg.push("Vui lòng thêm thông tin sản phẩm");
		} else {
			var type_2 = $('.type-2').attr('qty');
			if (type_2 == 0) {
				for (var i=1;i<=type_1;i++) {
					if ($('.item-'+i).val() == "") {
						$('.item-'+i).addClass("error");
						err++;
					}
					else {
						$('.item-'+i).removeClass("error");
					}
					//
					if ($('.price-'+i).val() == "") {
						$('.price-'+i).addClass("error");
						err++;
					}
					else {
						$('.price-'+i).removeClass("error");
					}
					if ($('.sale-'+i).val() == "") {
						$('.sale-'+i).addClass("error");
						err++;
					}
					else {
						$('.sale-'+i).removeClass("error");
					}
					if ($('.qty-'+i).val() == "") {
						$('.qty-'+i).addClass("error");
						err++;
					}
					else {
						$('.qty-'+i).removeClass("error");
					}
					if ($('.cost-'+i).val() == "") {
						$('.cost-'+i).addClass("error");
						err++;
					}
					else {
						$('.cost-'+i).removeClass("error");
					}
				}
			} else {
				if ($('.name-categorize2').val() == "") {
					$('.name-categorize2').addClass("error");
					err++;
				}
				else {
					$('.name-categorize2').removeClass("error");
				}
				for (var i=1;i<=type_1;i++) {
					if ($('.item-'+i).val() == "") {
						$('.item-'+i).addClass("error");
						err++;
					}
					else {
						$('.item-'+i).removeClass("error");
					}
				}
				for (var j=1;j<=type_2;j++) {
					if ($('.item2-'+j).val() == "") {
						$('.item2-'+j).addClass("error");
						err++;
					}
					else {
						$('.item2-'+j).removeClass("error");
					}
					//
					for (var i=1;i<=type_1;i++) {
						if ($('.price-'+i+'-'+j).val() == "") {
							$('.price-'+i+'-'+j).addClass("error");
							err++;
						}
						else {
							$('.price-'+i+'-'+j).removeClass("error");
						}
						if ($('.sale-'+i+'-'+j).val() == "") {
							$('.sale-'+i+'-'+j).addClass("error");
							err++;
						}
						else {
							$('.sale-'+i+'-'+j).removeClass("error");
						}
						if ($('.qty-'+i+'-'+j).val() == "") {
							$('.qty-'+i+'-'+j).addClass("error");
							err++;
						}
						else {
							$('.qty-'+i+'-'+j).removeClass("error");
						}
						if ($('.cost-'+i+'-'+j).val() == "") {
							$('.cost-'+i+'-'+j).addClass("error");
							err++;
						}
						else {
							$('.cost-'+i+'-'+j).removeClass("error");
						}
					}
				}
			}
		}
		if (!err) {
			submitButton("save");
		} else {
			showErrorMessage(msg);
		}
	});
	$(".btn-cancel").click(function(){
		submitButton("cancel");
	});
	$(".btn-file-upload").click(function(){
		var file_id = $(this).attr("file-id");
		$('#userfile-'+file_id).click();
	});
	$(".btn-file-close").click(function(){
		var file_id = $(this).attr("file-id");
		$('#hidden-userfile-'+file_id).val("");
		$('#userfile-'+file_id).val("");
		$('#bg-'+file_id).css({
			"background-image": "none"
		});
		$('#bg-'+file_id).removeClass("selected");
		$('#hidden-userfile-'+file_id).val('');
	});
	$(document).on('change','.userfile',function() {
		var file_id = $(this).attr("file-id");
		var form_data = new FormData();
		var file_data = $(this).prop("files")[0];
		var file_name = "userfile_"+file_id;
		var code = $('#code').val();
		
		var new_photo = file_data['name'].split('.');
		new_photo = slug(new_photo[0])+Math.floor(Math.random() * 1000).toString();
		form_data.append(file_name, file_data);
		form_data.append('old_photo', $(this).attr("old_photo"));
		form_data.append('new_photo', new_photo);
		var format = file_data['name'].split('.')[1];

		$.ajax({
			url: BASE_URL+'/syslog/upload-image-product/'+code+'.html',
			cache: false,
			contentType: false,
			processData: false,
			async: false,
			data: form_data,
			dataType: 'json',
			type: 'post',
			success: function(data) {
				$('#hidden-userfile-'+file_id).val(data);
			}
		});
		readURL(this, file_id);
	});

	function readURL(input, file_id) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#bg-'+file_id).css({
					"background-image": "url('"+e.target.result+"')"
				});
				$('#bg-'+file_id).addClass("selected");
				// $('#hidden-userfile-'+file_id).val(e.target.result);
			};
			reader.readAsDataURL(input.files[0]);
		}
	}
	$('#meta_title').keypress(function(e) {
		var tval = $('#meta_title').val(),
			tlength = tval.length,
			set = 70
			remain = parseInt(set - tlength);
		if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
			$('#meta_title').val((tval).substring(0, tlength - 1))
		}
	});
	$('#meta_desc').keypress(function(e) {
		var tval = $('#meta_desc').val(),
			tlength = tval.length,
			set = 160
			remain = parseInt(set - tlength);
		if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
			$('#meta_desc').val((tval).substring(0, tlength - 1))
		}
	});
	$('#meta_key').keypress(function(e) {
		var tval = $('#meta_key').val(),
			tlength = tval.length,
			set = 255
			remain = parseInt(set - tlength);
		if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
			$('#meta_key').val((tval).substring(0, tlength - 1))
		}
	});
</script>
