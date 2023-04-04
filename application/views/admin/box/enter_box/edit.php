<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
<?
$category_info = new stdClass();
$category_info->parent_id = 0;
$categories = $this->m_product_category->items($category_info);
?>
<div class="container-fluid">
	<h1 class="page-title"><?=($action == 'add')?'Thêm ':'Chỉnh sửa'?> Kho hàng</h1>
	<form id="frm-admin" name="adminForm" action="" method="POST" enctype="multipart/form-data">
		<input type="hidden" id="product_item_id" name="product_id">
		<input type="hidden" id="code_cate" name="code_cate">
		<input type="hidden" id="task" name="task" value="">
		<table class="table table-bordered">
			<tr>
				<td class="table-head text-right" width="10%">Danh mục chính</td>
				<td>
					<div class="wrap-lvl">
						<input type="hidden" id="category_main" name="category_id" value="">
						<div class="row">
							<div class="col-md-1">
								<label style="margin: 8px 10px;"> Cấp 1:</label>
							</div>
							<div class="col-md-11">
								<select lv="1" class="form-control frm-input lv-cate-main">
								<option value="">Chọn danh mục</option>
								<? $category_info = new stdClass();
									$category_info->parent_id = 0;
									$categories = $this->m_product_category->items($category_info,$active=null, $limit=null, $offset=null, $order_by='code', $sort_by='ASC');
									foreach($categories as $categorie) {
								?> 
								<option code='<?=$categorie->code?>' list_cate='["<?=$categorie->id?>"]' value="<?=$categorie->id?>"><?=$categorie->name?></option>
								<? } ?>
								</select>
							</div>
						</div>
						<div style="margin:5px 0;" class="lv-1"></div>
					</div>
				</td>
			</tr>
			<script>
				$(document).on('change','.lv-cate-main',function(){
					let lv = $(this).attr('lv');
					let lv_next = parseInt(lv)+1;
					let cate_id = $('option:selected', this).val();
					let list_cate = $('option:selected', this).attr('list_cate');
					$('#category_main').val(list_cate);
						list_cate = list_cate.replace('[','');
						list_cate = list_cate.replace(']','');
					let code = $('option:selected', this).attr('code');
					$('#code_cate').val(code);
					$.ajax({
						url: '<?=site_url('syslog/get-product-in-cate');?>',
						type: 'POST',
						dataType: 'json',
						data: {cate_id: cate_id},
						success: (res) => {
							let str = '<select class="form-control selectpicker" id="product_id" data-live-search="true" require><option data-tokens="">Sản phẩm mới - 0</option></select>';
							if(res[0]=='cate'){
								let str_cate = '<div class="row">';
										str_cate += '<div class="col-md-1"><label style="margin: 8px 10px;"> Cấp '+lv_next+':</label></div>';
										str_cate +='<div class="col-md-11">';
											str_cate +='<select lv="'+lv_next+'" class="form-control frm-input lv-cate-main">';
											str_cate +='<option value="">Chọn danh mục</option>';
											for (var i=0;i<res[1].length;i++) {
												var list_cate_temp = '['+list_cate;
													list_cate_temp += ',"'+res[1][i].id+'"'+']';
												var code_temp = code;
													code_temp += res[1][i].code;
												str_cate +='<option code='+code_temp+' list_cate='+list_cate_temp+' value="'+res[1][i].id+'">'+res[1][i].name+'</option>';
											}
											str_cate +='</select>';
										str_cate +='</div>';
									str_cate +='</div>';
									str_cate += '<div style="margin:5px 0;" class="lv-'+lv_next+'"></div>';
								$('.lv-'+lv).html(str_cate);
							} else {
								str = '<select class="form-control selectpicker" id="product_id" data-live-search="true" require> <option data-tokens="">Sản phẩm mới - 0</option>';
								for (var i=0;i<res[1].length;i++) {
									str += '<option category_id="'+res[1][i].category_id+'" data-tokens="'+res[1][i].title+' - NA'+res[1][i].code_product+' - '+res[1][i].id+'">'+res[1][i].title+' - NA'+res[1][i].code_product+' - '+res[1][i].id+'</option>';
								}
								str += '</select>';
								$('.lv-'+lv).html('');
							}
							$('.selectpicker').html(str).selectpicker('refresh');
						}
					});
				});
			</script>
			<tr <?=($action == 'add')?'style="display:table-row"':'style="display:none"'?>>
				<td class="table-head text-right" width="10%">Danh sách sản phẩm</td>
				<td class="ahihi">
					<select class="form-control selectpicker" id="product_id" data-live-search="true" require>
						<option data-tokens="">Sản phẩm mới - 0</option>
					</select>
				</td>
				<script type="text/javascript">
					$("#product_id").val("<?=!empty($item->product_id) ? $item->product_id : ''?>");
				</script>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Tên sản phẩm</td>
				<td><input type="text" id="title" name="title" class="form-control" value="<?=!empty($item->title) ? $item->title : ''?>"></td>
			</tr>
			<?
				$list_category = !empty($item->list_category)?$item->list_category:'';
				$arr_category_id = str_replace('[','',$list_category) ;
				$arr_category_id = str_replace(']','',$arr_category_id) ;
				function gen_category_menu_product($product_categories, $obj,$arr_category_id,$str) {
					foreach ($product_categories as $product_category) {
						$id = '"'.$product_category->id.'"';
						$r = $str."{$id}";
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
											<input type='checkbox' class='transition filter' disabled status='danh_muc'  value='{$r}'>
											{$product_category->name}
										</label>
									</div>
									<ul class='list' style='padding-left:20px;'>";
									gen_category_menu_product($child_categories, $obj, $arr_category_id, $str."{$id},");
							echo '	</ul>
								</li>';
						} else {
							$check = (strpos($arr_category_id, $r) !== false)?'checked':'';
							echo "<li>
									<div class='checkbox'>
										<label>
											<input type='checkbox' class='transition filter' status='danh_muc' {$check} value='{$r}'>
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
			<tr class="check-new-product" <?=($action == 'add')?'style="display:table-row"':'style="display:none"'?>>
				<td class="table-head text-right" width="10%">Danh mục</td>
				<td>
					<div id="wrap-list-category">
						<input type="hidden" id="list_category" name="list_category" value='<?=$arr_category_id?>'>
						<div class="row">
						<?gen_category_menu_product($categories, $this, $arr_category_id,'','');?>
						</div>
						<script type="text/javascript">
							$("#category_id").val("<?=!empty($item->category_id)?$item->category_id:''?>");
						</script>
					</div>
				</td>
			</tr>
			<script>
				$(".filter").click(function(){
					var value = $(this).val();
					var list = $('#list_category').val();
					var cheked = $(this).is(":checked")
					list = list.replace('[','');
					list = list.replace(']','');
					if (cheked) {
						if (list == '') {
							list += value;
						} else {
							list += ','+value;
						}
					} else {
						list = list.replace(','+value+',',',');
						list = list.replace(','+value,'');
						list = list.replace(value+',','');
						list = list.replace(value,'');
					}
					$('#list_category').val('['+list+']');
				});
			</script>
			<tr class="check-new-product" <?=($action == 'add')?'style="display:table-row"':'style="display:none"'?>>
				<td class="table-head text-right">Loại sản phẩm <br>(Có thuộc cty không)</td>
				<td>
					<select id="product_company" name="product_company" class="form-control">
						<option value="1">Thuộc cty</option>
						<option value="2">Không thuộc cty</option>
					</select>
					<script type="text/javascript">
						$("#product_company").val("<?=!empty($item->product_company)?$item->product_company:'1'?>");
					</script>
				</td>
			</tr>
			<tr class="check-new-product" <?=($action == 'add')?'style="display:table-row"':'style="display:none"'?>>
				<td class="table-head text-right" width="10%">Nguồn gốc xuất xứ</td>
				<td><input type="text" id="origin" name="origin" class="form-control" value="<?=!empty($item->origin)?$item->origin:''?>"></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Đặc tính sản phẩm</td>
				<td>
					<div class="row">
						<? $subtypename = array(); $c_box_details = count($box_details);?>
						<div class="col-md-6">
							<div class="wrap-type">
								<h6 class="type-1" qty="<?=$c_box_details?>">Nhóm 1</h6>
								<div class="list-item">
									<input type="text" name-item="name_categorize1" name="name_categorize1" class="form-control name-categorize1 keyupvalue" value="<?=!empty($item->typename) ? $item->typename : ''?>" placeholder="Nhóm 1">
									<hr size="1" color="white">
									<div class="item-categorizeite item-categorizeite1">
										<? $i=1; foreach ($box_details as $box_detail) { 
											if ($i==1) $subtypename = ($box_detail->subtypename != '""') ? json_decode($box_detail->subtypename) : array(); ?>
										<div class="item">
											<div class="item-left">
												<input type="text" name-item="item-<?=$i?>" name="typename[]" class="form-control keyupvalue item-<?=$i?>" value="<?=$box_detail->typename?>" placeholder="item">
											</div>
											<div class="item-right">
												<a class="text-color-red"><i stt="<?=$i?>" type="1" class="fa fa-trash-o" aria-hidden="true"></i></a>
											</div>
										</div>
										<? $i++;} ?>
									</div>
								</div>
								<a class="btn-pl" type="1" style="cursor:pointer">+ Thêm nhóm</a>
							</div>
						</div>
						<? $c_subtypename = count($subtypename); ?>
						<div class="col-md-6">
							<div class="wrap-type">
								<h6 class="type-2" qty="<?=$c_subtypename?>">Nhóm 2</h6>
								<div class="list-item">
									<input type="text" name-item="name_categorize2" name="name_categorize2" class="form-control name-categorize2 keyupvalue" value="<?=!empty($item->subtypename) ? $item->subtypename : ''?>" placeholder="Nhóm 2">
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
										<? $i++; } ?>
									</div>
								</div>
								<a class="btn-pl" type="2" style="cursor:pointer">+ Thêm nhóm</a>
							</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%"></td>
				<td>
					<div class="html-list-product">
						<? if (empty($subtypename)) { ?>
						<table class="table table-bordered list-product-cate">
						  <tr>
					        <td width="15%" style="background:#eee;" class="name_categorize1"><?=!empty($item->typename) ? $item->typename : ''?></td>
					        <td width="14%" style="background:#eee;">Giá bán</td>
							<td width="14%" style="background:#eee;">Giảm giá (%)</td>
							<td width="14%" style="background:#eee;">Giá vốn</td>
					        <td width="14%" style="background:#eee;">SL nhập kho</td>
							<td width="14%" style="background:#eee;">SL hiện tại</td>
					      </tr>
					      <? $i=1; foreach ($box_details as $box_detail) { 
						   $price 		= json_decode($box_detail->price);
						   $quantity 	= json_decode($box_detail->quantity_box);
						   $sale 		= json_decode($box_detail->sale);
						   $cost 		= json_decode($box_detail->cost); ?>
					      <tr>
					        <td width="15%" rowspan="1" class="item-<?=$i?>"><?=$box_detail->typename?></td>
					        <td width="14%">
					        	<input type="text" name="price<?=$i?>[]" class="form-control price-<?=$i?>" value="<?=$price[0]?>">
					        </td>
							<td width="14%">
					        	<input type="text" name="sale<?=$i?>[]" class="form-control sale-<?=$i?>" value="<?=$sale[0]?>">
					        </td>
							<td width="14%">
					        	<input type="text" name="cost<?=$i?>[]" class="form-control cost-<?=$i?>" value="<?=$cost[0]?>">
					        </td>
					        <td width="14%">
					        	<input type="text" name="qty<?=$i?>[]" class="form-control qty-<?=$i?>" value="<?=$quantity[0]?>">
					        </td>
							<td width="14%">
					        	<input type="number" name="total_qty<?=$i?>[]" class="form-control total-qty-<?=$i?>" value="0">
					        </td>
					      </tr>
					      <? $i++;} ?>
						</table>
						<? } else { ?>
						<table class="table table-bordered list-product-cate">
							  <tr>
						        <td width="15%" style="background:#eee;" class="name_categorize1"><?=!empty($item->typename) ? $item->typename : ''?></td>
						        <td width="15%" style="background:#eee;" class="name_categorize2"><?=!empty($item->subtypename) ? $item->subtypename : ''?></td>
						        <td width="14%" style="background:#eee;">Giá bán</td>
								<td width="14%" style="background:#eee;">Giảm giá (%)</td>
								<td width="14%" style="background:#eee;">Giá vốn</td>
						        <td width="14%" style="background:#eee;">SL nhập kho</td>
								<td width="14%" style="background:#eee;">SL hiện tại</td>
						      </tr>
						      <? $i=1; foreach ($box_details as $box_detail) { 
						      	$price = json_decode($box_detail->price);
								$quantity = json_decode($box_detail->quantity_box);
								$sale = json_decode($box_detail->sale);
								$cost = json_decode($box_detail->cost); ?>
						      	  <? $j=0; foreach ($subtypename as $subname) { ?>
							      	  <? if ($j == 0) { ?>
								      <tr>
								        <td width="15%" class="item-<?=$i?>" rowspan="<?=$c_subtypename?>"><?=$box_detail->typename?></td>
								        <td width="15%" class="item2-<?=$j+1?>"><?=$subname?></td>
								        <td width="14%">
								        	<input type="text" name="price<?=$i?>[]" class="form-control price-<?=$i?>-<?=$j+1?>" value="<?=$price[$j]?>">
								        </td>
										<td width="14%">
								        	<input type="text" name="sale<?=$i?>[]" class="form-control sale-<?=$i?>-<?=$j+1?>" value="<?=$sale[$j]?>">
								        </td>
										<td width="14%">
								        	<input type="text" name="cost<?=$i?>[]" class="form-control cost-<?=$i?>-<?=$j+1?>" value="<?=$cost[$j]?>">
								        </td>
								        <td width="14%">
								        	<input type="text" name="qty<?=$i?>[]" class="form-control qty-<?=$i?>-<?=$j+1?>" value="<?=$quantity[$j]?>">
								        </td>
										<td width="14%">
								        	<input type="number" name="total_qty<?=$i?>[]" class="form-control total-qty-<?=$i?>-<?=$j+1?>" value="0">
								        </td>
								      </tr>
								 	  <? } else { ?>
							      	  <tr>
								        <td width="15%" class="item2-<?=$j+1?>"><?=$subname?></td>
								        <td width="14%">
								        	<input type="text" name="price<?=$i?>[]" class="form-control price-<?=$i?>-<?=$j+1?>" value="<?=$price[$j]?>">
								        </td>
										<td width="14%">
								        	<input type="text" name="sale<?=$i?>[]" class="form-control sale-<?=$i?>-<?=$j+1?>" value="<?=$sale[$j]?>">
								        </td>
										<td width="14%">
								        	<input type="text" name="cost<?=$i?>[]" class="form-control cost-<?=$i?>-<?=$j+1?>" value="<?=$cost[$j]?>">
								        </td>
								        <td width="14%">
								        	<input type="text" name="qty<?=$i?>[]" class="form-control qty-<?=$i?>-<?=$j+1?>" value="<?=$quantity[$j]?>">
								        </td>
										<td width="14%">
								        	<input type="number" name="total_qty<?=$i?>[]" class="form-control total-qty-<?=$i?>-<?=$j+1?>" value="0">
								        </td>
								      </tr>
								      <? } ?>
							      <? $j++;} ?>
						  	  <? $i++;} ?>
						</table>
						<? } ?>
					</div>
				</td>
			</tr>
			<script>
			$('#product_id').change(function(event) {
				let id = $(this).val().split(' - ');
					id = id[id.length-1];
				let hml = $('option:selected', this).html();
				$('#product_item_id').val(id);
				$("#title").val(hml);
				if (id != '0') {
					$.ajax({
						url: '<?=site_url('syslog/ajax-get-product');?>',
						type: 'POST',
						dataType: 'json',
						data: {id: id},
						success: (res) => {
							let str_box1 = '';
							let str_box2 = '';
							let sub = JSON.parse(res.type[0].subtypename).length;
							let str = '<table class="table table-bordered list-product-cate">';
								str += '<tr>';
									str += '<td width="15%" style="background:#eee;" class="name_categorize1">'+res.typename+'</td>';
									if (sub != 0)
									str += '<td width="15%" style="background:#eee;" class="name_categorize2">'+res.subtypename+'</td>';
									str += '<td width="14%" style="background:#eee;">Giá bán</td>';
									str += '<td width="14%" style="background:#eee;">Giảm giá (%)</td>';
									str += '<td width="14%" style="background:#eee;">Giá vốn</td>';
									str += '<td width="14%" style="background:#eee;">SL nhập kho</td>';
									str += '<td width="14%" style="background:#eee;">SL hiện tại</td>';
								str += '</tr>';

							res.type.map((typ, i) => {
								let price 			= JSON.parse(typ.price); 
								let quantity 		= JSON.parse(typ.quantity);
								let sale 			= JSON.parse(typ.sale);
								let cost 			= JSON.parse(typ.cost);
								if (sub != 0) {
									str_box2 = '';
									JSON.parse(typ.subtypename).map((box2, j) => {
										str_box2 += '<div class="item">';
											str_box2 += '<div class="item-left">';
												str_box2 += '<input type="text" name-item="item2-'+(j+1)+'" name="subtypename[]" class="form-control item2-'+(j+1)+' keyupvalue" value="'+box2+'" placeholder="item">';
											str_box2 += '</div>';
											str_box2 += '<div class="item-right">';
												str_box2 += '<a class="text-color-red"><i stt="'+(j+1)+'" type="2" class="fa fa-trash-o" aria-hidden="true"></i></a>';
											str_box2 += '</div>';
										str_box2 += '</div>';
										
										str += '<tr>';
											if (j == 0) str += '<td width="15%" rowspan="'+sub+'" class="item-'+(i+1)+'">'+typ.typename+'</td>';
											str += '<td width="15%" class="item2-'+(j+1)+'">'+box2+'</td>';
											str += '<td width="14%">';
												str += '<input type="text" name="price'+(i+1)+'[]" class="form-control price-'+(i+1)+'-'+(j+1)+'" value="'+price[j]+'">';
											str += '</td>';
											str += '<td width="14%">';
												str += '<input type="text" name="sale'+(i+1)+'[]" class="form-control sale-'+(i+1)+'-'+(j+1)+'" value="'+sale[j]+'">';
											str += '</td>';
											str += '<td width="14%">';
												str += '<input type="text" name="cost'+(i+1)+'[]" class="form-control cost-'+(i+1)+'-'+(j+1)+'" value="'+cost[j]+'">';
											str += '</td>';
											str += '<td width="14%">';
												str += '<input type="text" name="qty'+(i+1)+'[]" class="form-control change-qty qty-'+(i+1)+'-'+(j+1)+'" value="0">';
											str += '</td>';
											str += '<td width="14%">';
												str += '<input type="number" name="total_qty'+(i+1)+'[]" class="form-control total-qty-'+(i+1)+'-'+(j+1)+'" val="'+quantity[j]+'" value="'+quantity[j]+'">';
											str += '</td>';
										str += '</tr>';
									});
								} else {
									str += '<tr>';
										str += '<td width="15%" rowspan="1" class="item-'+(i+1)+'">'+typ.typename+'</td>';
										str += '<td width="14%">';
											str += '<input type="text" name="price'+(i+1)+'[]" class="form-control price-'+(i+1)+'" value="'+price[0]+'">';
										str += '</td>';
										str += '<td width="14%">';
											str += '<input type="text" name="sale'+(i+1)+'[]" class="form-control sale-'+(i+1)+'" value="'+sale[0]+'">';
										str += '</td>';
										str += '<td width="14%">';
											str += '<input type="text" name="cost'+(i+1)+'[]" class="form-control cost-'+(i+1)+'" value="'+cost[0]+'">';
										str += '</td>';
										str += '<td width="14%">';
											str += '<input type="text" name="qty'+(i+1)+'[]" class="form-control change-qty qty-'+(i+1)+'" value="0">';
										str += '</td>';
										str += '<td width="14%">';
											str += '<input type="number" name="total_qty'+(i+1)+'[]" class="form-control total-qty-'+(i+1)+'" val="'+quantity[0]+'" value="'+quantity[0]+'">';
										str += '</td>';
									str += '</tr>';
								}
								str_box1 += '<div class="item">';
								str_box1 += '<div class="item-left">';
									str_box1 += '<input type="text" name-item="item-'+(i+1)+'" name="typename[]" class="form-control keyupvalue item-'+(i+1)+'" value="'+typ.typename+'" placeholder="item">';
								str_box1 += '</div>';
								str_box1 += '<div class="item-right">';
									str_box1 += '<a class="text-color-red"><i stt="'+(i+1)+'" type="1" class="fa fa-trash-o" aria-hidden="true"></i></a>';
								str_box1 += '</div>';
							str_box1 += '</div>';
							});

							str += '</table>';

							$('.type-1').attr('qty',res.type.length);
							$('.type-2').attr('qty',sub);
							$('.name-categorize1').val(res.typename);
							$('.name-categorize2').val(res.subtypename);
							$('.name_categorize1').html(res.typename);
							$('.name_categorize2').html(res.subtypename);
							$('.item-categorizeite1').html(str_box1);
							$('.item-categorizeite2').html(str_box2);
							$('.html-list-product').html(str);
						}
					});
					$('.check-new-product').css('display','none');
				} else {
					let str = '<table class="table table-bordered list-product-cate">';
							str += '<tr>';
								str += '<td width="15%" style="background:#eee;" class="name_categorize1"></td>';
								str += '<td width="14%" style="background:#eee;">Giá bán</td>';
								str += '<td width="14%" style="background:#eee;">Giảm giá (%)</td>';
								str += '<td width="14%" style="background:#eee;">Giá vốn</td>';
								str += '<td width="14%" style="background:#eee;">SL nhập kho</td>';
								str += '<td width="14%" style="background:#eee;">SL hiện tại</td>';
							str += '</tr>';
						str += '</table>';
					$('.html-list-product').html(str);
					$('.type-1').attr('qty','0');
					$('.type-2').attr('qty','0');
					$('.item-categorizeite').html('');
					$('.check-new-product').css('display','table-row');
				}
			});
			</script>
			<script>
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
						str_list += '<td width="15%" style="background:#eee;" class="name_categorize1">'+$('.name-categorize1').val()+'</td>';
						if (type_2 >= 1) {
						str_list += '<td width="15%" style="background:#eee;" class="name_categorize2">'+$('.name-categorize2').val()+'</td>';
						}
						str_list += '<td width="14%" style="background:#eee;">Giá bán</td><td width="14%" style="background:#eee;">Giảm giá (%)</td><td width="14%" style="background:#eee;">Giá vốn</td><td width="14%" style="background:#eee;">SL nhập kho</td><td width="14%" style="background:#eee;">SL hiện tại</td></tr>';
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
									var val_sale = '0';
									if ($('.sale-'+i+'-'+j).val() != undefined) { val_sale = $('.sale-'+i+'-'+j).val(); }
									var val_cost = '0';
									if ($('.cost-'+i+'-'+j).val() != undefined) { val_cost = $('.cost-'+i+'-'+j).val(); }
									var val_qty = '0';
									if ($('.qty-'+i+'-'+j).val() != undefined) { val_qty = $('.qty-'+i+'-'+j).val(); }
									var val_total = '0';
									if ($('.total-qty-'+i+'-'+j).val() != undefined) { val_total = $('.total-qty-'+i+'-'+j).val(); }
									str_list += '<tr>';
										if (j == 1) {
										str_list += '<td width="15%" rowspan="'+type_2+'" class="item-'+i+'">'+val_name+'</td>';
										}
										str_list += '<td width="15%" class="item2-'+j+'">'+val_name2+'</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="price'+i+'[]" class="form-control price-'+i+'-'+j+'" value="'+val_price+'">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="sale'+i+'[]" class="form-control sale-'+i+'-'+j+'" value="'+val_sale+'">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="cost'+i+'[]" class="form-control cost-'+i+'-'+j+'" value="'+val_cost+'">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="qty'+i+'[]" class="form-control change-qty qty-'+i+'-'+j+'" value="'+val_qty+'">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="number" name="total_qty'+i+'[]" class="form-control total-qty-'+i+'-'+j+'" val="'+val_total+'" value="'+val_total+'">';
										str_list += '</td>';
									str_list += '</tr>';
								}
							} else {
								var val_price = '0';
								if ($('.price-'+i).val() != undefined) { val_price = $('.price-'+i).val(); }
								var val_sale = '0';
								if ($('.sale-'+i).val() != undefined) { val_sale = $('.sale-'+i).val(); }
								var val_cost = '0';
								if ($('.cost-'+i).val() != undefined) { val_cost = $('.cost-'+i).val(); }
								var val_qty = '0';
								if ($('.qty-'+i).val() != undefined) { val_qty = $('.qty-'+i).val(); }
								var val_total = '0';
								if ($('.total-qty-'+i).val() != undefined) { val_total = $('.total-qty-'+i).val(); }
								str_list += '<tr>';
									str_list += '<td width="15%" rowspan="1" class="item-'+i+'">'+val_name+'</td>';
									str_list += '<td width="14%">';
										str_list += '<input type="text" name="price'+i+'[]" class="form-control price-'+i+'" value="'+val_price+'">';
									str_list += '</td>';
									str_list += '<td width="14%">';
										str_list += '<input type="text" name="sale'+i+'[]" class="form-control sale-'+i+'" value="'+val_sale+'">';
									str_list += '</td>';
									str_list += '<td width="14%">';
										str_list += '<input type="text" name="cost'+i+'[]" class="form-control cost-'+i+'" value="'+val_cost+'">';
									str_list += '</td>';
									str_list += '<td width="14%">';
										str_list += '<input type="text" name="qty'+i+'[]" class="form-control change-qty qty-'+i+'" value="'+val_qty+'">';
									str_list += '</td>';
									str_list += '<td width="14%">';
										str_list += '<input type="number" name="total_qty'+i+'[]" class="form-control total-qty-'+i+'" val="'+val_total+'" value="'+val_total+'">';
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
				$(document).on('click','.fa-trash-o',function(){
					var typ = parseInt($(this).attr('type'));
					var stt = parseInt($(this).attr('stt'));
					var type_1 = parseInt($('.type-1').attr('qty'));
					var type_2 = parseInt($('.type-2').attr('qty'));
					var str1 = '';
					var str2 = '';

					var str_list = '<table class="table table-bordered list-product-cate"><tr>';
						str_list += '<td width="15%" style="background:#eee;" class="name_categorize1">'+$('.name-categorize1').val()+'</td>';
						if (type_2-1 > 0) {
						str_list += '<td width="15%" style="background:#eee;" class="name_categorize2">'+$('.name-categorize2').val()+'</td>';
						}
						str_list += '<td width="14%" style="background:#eee;">Giá bán</td><td width="14%" style="background:#eee;">Giảm giá (%)</td><td width="14%" style="background:#eee;">Giá vốn</td><td width="14%" style="background:#eee;">SL nhập kho</td><td width="14%" style="background:#eee;">SL hiện tại</td></tr>';
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
								var val_total = '0';

								if (i < type_1) { 
									if (i >= stt) {
										k = k + 1;
										if ($('.item-'+(i+1)).val() != undefined) { val_name = $('.item-'+(i+1)).val(); }
										if ($('.price-'+(i+1)).val() != undefined) { val_price = $('.price-'+(i+1)).val(); }
										if ($('.sale-'+(i+1)).val() != undefined) { val_sale = $('.sale-'+(i+1)).val(); }
										if ($('.cost-'+(i+1)).val() != undefined) { val_cost = $('.cost-'+(i+1)).val(); }		
										if ($('.qty-'+(i+1)).val() != undefined) { val_qty = $('.qty-'+(i+1)).val(); }
										if ($('.total-qty-'+(i+1)).val() != undefined) { val_total = $('.total-qty-'+(i+1)).val(); }
									} else {
										k = i;
										if ($('.item-'+i).val() != undefined) { val_name = $('.item-'+i).val(); }
										if ($('.price-'+i).val() != undefined) { val_price = $('.price-'+i).val(); }
										if ($('.sale-'+i).val() != undefined) { val_sale = $('.sale-'+i).val(); }	
										if ($('.cost-'+i).val() != undefined) { val_cost = $('.cost-'+i).val(); }		
										if ($('.qty-'+i).val() != undefined) { val_qty = $('.qty-'+i).val(); }
										if ($('.total-qty-'+i).val() != undefined) { val_total = $('.total-qty-'+i).val(); }
									}
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
											if (i < stt){
												if ($('.price-'+i+'-'+j).val() != undefined) { val_price = $('.price-'+i+'-'+j).val(); }
												if ($('.sale-'+i+'-'+j).val() != undefined) { val_sale = $('.sale-'+i+'-'+j).val(); }
												if ($('.cost-'+i+'-'+j).val() != undefined) { val_cost = $('.cost-'+i+'-'+j).val(); }
												if ($('.qty-'+i+'-'+j).val() != undefined) { val_qty = $('.qty-'+i+'-'+j).val(); }
												if ($('.total-qty-'+i+'-'+j).val() != undefined) { val_total = $('.total-qty-'+i+'-'+j).val(); }
											} else {
												if ($('.price-'+(i+t)+'-'+j).val() != undefined) { val_price = $('.price-'+(i+t)+'-'+j).val(); }
												if ($('.sale-'+(i+t)+'-'+j).val() != undefined) { val_sale = $('.sale-'+(i+t)+'-'+j).val(); }
												if ($('.cost-'+(i+t)+'-'+j).val() != undefined) { val_cost = $('.cost-'+(i+t)+'-'+j).val(); }
												if ($('.qty-'+(i+t)+'-'+j).val() != undefined) { val_qty = $('.qty-'+(i+t)+'-'+j).val(); }
												if ($('.total-qty-'+(i+t)+'-'+j).val() != undefined) { val_total = $('.total-qty-'+(i+t)+'-'+j).val(); }
											}
											str_list += '<tr>';
												if (j == 1) {
												str_list += '<td width="15%" rowspan="'+type_2+'" class="item-'+i+'">'+val_name+'</td>';
												}
												str_list += '<td width="15%" class="item2-'+j+'">'+val_name2+'</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="price'+i+'[]" class="form-control price-'+i+'-'+j+'" value="'+val_price+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="sale'+i+'[]" class="form-control sale-'+i+'-'+j+'" value="'+val_sale+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="cost'+i+'[]" class="form-control cost-'+i+'-'+j+'" value="'+val_cost+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="qty'+i+'[]" class="form-control change-qty qty-'+i+'-'+j+'" value="'+val_qty+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="number" name="total_qty'+i+'[]" class="form-control total-qty-'+i+'-'+j+'" val="'+val_total+'" value="'+val_total+'">';
												str_list += '</td>';
											str_list += '</tr>';
										}
									} else {
										if (i < stt){
											if ($('.price-'+i).val() != undefined) { val_price = $('.price-'+i).val(); }
											if ($('.sale-'+i).val() != undefined) { val_sale = $('.sale-'+i).val(); }
											if ($('.cost-'+i).val() != undefined) { val_cost = $('.cost-'+i).val(); }
											if ($('.qty-'+i).val() != undefined) { val_qty = $('.qty-'+i).val(); }
											if ($('.total-qty-'+i).val() != undefined) { val_total = $('.total-qty-'+i).val(); }
										} else {
											if ($('.price-'+(i+t)).val() != undefined) { val_price = $('.price-'+(i+t)).val(); }
											if ($('.sale-'+(i+t)).val() != undefined) { val_sale = $('.sale-'+(i+t)).val(); }
											if ($('.cost-'+(i+t)).val() != undefined) { val_cost = $('.cost-'+(i+t)).val(); }
											if ($('.qty-'+(i+t)).val() != undefined) { val_qty = $('.qty-'+(i+t)).val(); }
											if ($('.total-qty-'+(i+t)).val() != undefined) { val_total = $('.total-qty-'+(i+t)).val(); }
										}
										
										str_list += '<tr>';
											str_list += '<td width="15%" rowspan="1" class="item-'+i+'">'+val_name+'</td>';
											str_list += '<td width="14%">';
												str_list += '<input type="text" name="price'+i+'[]" class="form-control price-'+i+'" value="'+val_price+'">';
											str_list += '</td>';
											str_list += '<td width="14%">';
												str_list += '<input type="text" name="sale'+i+'[]" class="form-control sale-'+i+'" value="'+val_sale+'">';
											str_list += '</td>';
											str_list += '<td width="14%">';
												str_list += '<input type="text" name="cost'+i+'[]" class="form-control cost-'+i+'" value="'+val_cost+'">';
											str_list += '</td>';
											str_list += '<td width="14%">';
												str_list += '<input type="text" name="qty'+i+'[]" class="form-control change-qty qty-'+i+'" value="'+val_qty+'">';
											str_list += '</td>';
											str_list += '<td width="14%">';
												str_list += '<input type="number" name="total_qty'+i+'[]" class="form-control total-qty-'+i+'" val="'+val_total+'" value="'+val_total+'">';
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
							var val_total = '0';
							var val_price = '0';
							var val_sale = '0';
							var val_cost = '0';
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
											if ($('.item2-'+k).val() != undefined) { val_name2 = $('.item2-'+k).val(); }
											if ($('.price-'+i+'-'+k).val() != undefined) { val_price = $('.price-'+i+'-'+k).val(); }
											if ($('.sale-'+i+'-'+k).val() != undefined) { val_sale = $('.sale-'+i+'-'+k).val(); }
											if ($('.cost-'+i+'-'+k).val() != undefined) { val_cost = $('.cost-'+i+'-'+k).val(); }
											if ($('.qty-'+i+'-'+k).val() != undefined) { val_qty = $('.qty-'+i+'-'+k).val(); }
											if ($('.total-qty-'+i+'-'+k).val() != undefined) { val_total = $('.total-qty-'+i+'-'+k).val(); }
											str_list += '<tr>';
												if (j == 1) {
												str_list += '<td width="15%" rowspan="'+(type_2-1)+'" class="item-'+i+'">'+val_name+'</td>';
												}
												str_list += '<td width="15%" class="item2-'+k+'">'+val_name2+'</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="price[]" class="form-control price-'+i+'-'+j+'" value="'+val_price+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="sale[]" class="form-control sale-'+i+'-'+j+'" value="'+val_sale+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="cost[]" class="form-control cost-'+i+'-'+j+'" value="'+val_cost+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="text" name="qty[]" class="form-control change-qty qty-'+i+'-'+j+'" value="'+val_qty+'">';
												str_list += '</td>';
												str_list += '<td width="14%">';
													str_list += '<input type="number" name="total_qty[]" class="form-control total-qty-'+i+'-'+j+'" val="'+val_total+'" value="'+val_total+'">';
												str_list += '</td>';
											str_list += '</tr>';
										}
									}
								} else {
									str_list += '<tr>';
										str_list += '<td width="15%" rowspan="1" class="item-'+i+'">'+val_name+'</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="price[]" class="form-control price-'+i+'" value="0">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="sale[]" class="form-control sale-'+i+'" value="0">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="cost[]" class="form-control cost-'+i+'" value="0">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="text" name="qty[]" class="form-control change-qty qty-'+i+'" value="0">';
										str_list += '</td>';
										str_list += '<td width="14%">';
											str_list += '<input type="number" name="total_qty[]" class="form-control total-qty-'+i+'" val="0" value="0">';
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
				$(document).on('keyup','.change-qty',function(){
					let val = $(this).val();
					let qty = $(this).parent().next().find('input').attr('val');
					$(this).parent().next().find('input').val(parseInt(val)+parseInt(qty));
				});
				$(function(){
					$(document).on('click','input[type=number]',function(){ this.select(); });
				});
			</script>
			<tr class="check-new-product" <?=($action == 'add')?'style="display:table-row"':'style="display:none"'?>>
				<td class="table-head text-right" width="10%">Nội dung</td>
				<td><textarea id="content" name="content" class="tinymce form-control" rows="20"><?//=$item->content?></textarea></td>
			</tr>
		</table>
		<div class="text-right">
			<a class="btn btn-sm btn-primary btn-save">Cập nhật</a>
			<a class="btn btn-sm btn-default btn-cancel">Hủy</a>
		</div>
	</form>
</div>

<script>
$(document).on('click','.btn-save',function() {
	jQuery.noConflict();
	event.preventDefault();
	var err = 0;
	var msg = [];

	clearFormError();
	
	if ($('#category_main').val() == "") {
		$('.lv-cate-main').addClass("error");
		err++;
		msg.push("Danh mục chính không được bỏ trống");
	} else {
		$('.lv-cate-main').removeClass("error");
	}

	if ($('#title').val() == "") {
		$('#title').addClass("error");
		err++;
		msg.push("Tên sản phẩm không bỏ trống");
	} else {
		$('#title').removeClass("error");
	}
	var product_id = $('.bootstrap-select > button > .filter-option').html().split(' - ');
		product_id = product_id[product_id.length-1];
	if (product_id == '0') {
		if ($('#list_category').val() == "" || $('#list_category').val() == "[]") {
			$('#wrap-list-category').addClass("error");
			err++;
			msg.push("Danh mục không được để trống");
		} else {
			$('#wrap-list-category').removeClass("error");
		}
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
$(document).ready(function() {
	$(".btn-cancel").click(function(){
		submitButton("cancel");
	});
});
</script>
