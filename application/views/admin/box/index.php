<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<? $admin = $this->session->admin;?>
<div class="cluster statistic">
	<div class="container-fluid">
		<div class="tool-bar">
			<h1 class="page-title clearfix">
				<div class="pull-left" style="max-width: 345px;">
					Kho hàng
					<br><a data-toggle="modal" data-target="#add-booking" style="font-size: 13px;margin:0 0 10px 0;cursor:pointer; display:inline-block;"><i class="fa fa-cube" aria-hidden="true"></i> Kiểm kho nhanh</a>
					<div class="input-group">
						<input type="text" id="search-box" class="form-control" placeholder="Nhập tên sản phẩm">
						<span class="input-group-btn">
							<button class="btn btn-default btn-search-box" type="button">Tìm kiếm</button>
						</span>
					</div>
				</div>
				<div class="pull-right">
					<div class="clearfix">
						<div class="pull-left" style="max-width: 250px;">
							<div class="input-group input-group-sm">
								<input type="text" class="form-control daterange">
								<span class="input-group-btn">
									<button class="btn btn-default btn-report" type="button">Kiểm kho</button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</h1>
		</div>
		<script>
			$('.btn-search-box').click(function (e){
				var search_box = $('#search-box').val();
				window.location.href = '<?=BASE_URL?>'+'/syslog/enter-box/0.html?search_box='+search_box;
			})
		</script>
		<br>
		<!-- <div class="text-center">
			<div class="btn-group">
				<a style="cursor:pointer;" val="week" class="btn btn-info report">Tuần</a>
				<a style="cursor:pointer;" val="month" class="btn btn-info report">Tháng</a>
				<a style="cursor:pointer;" val="year" class="btn btn-info report">Năm</a>
			</div>
		</div>
		<br> -->
		<div class="row">
			<div class="col-md-12-5"><label for="">Tổng mặt hàng:</label><div class="alert alert-info" role="alert"><?=$total_boxs?></div></div>
			<div class="col-md-12-5"><label for="">Số lượng:</label><div class="alert alert-info" role="alert"><?=$qty_box?></div></div>
			<div class="col-md-12-5"><label for="">Mặt hàng (tồn):</label><div class="alert alert-danger" role="alert"><?=$inv_boxs?></div></div>
			<div class="col-md-12-5"><label for="">Số lượng (tồn): </label><div class="alert alert-danger" role="alert"><?=$qty?></div></div>
			<div class="col-md-12-5"><label for="">Tổng vốn: </label><div class="alert alert-info" role="alert"><?=number_format($capital_box,0,',','.')?><sup>đ</sup></div></div>
			<div class="col-md-12-5"><label for="">Tổng bán (ước tính): </label><div class="alert alert-info" role="alert"><?=number_format($total_price,0,',','.')?><sup>đ</sup></div></div>
			<div class="col-md-12-5"><label for="">Vốn (tồn):</label><div class="alert alert-danger" role="alert"> <?=number_format($capital,0,',','.')?><sup>đ</sup></div></div>
			<div class="col-md-12-5"><label for="">Lợi nhuận: </label><div class="alert alert-success" role="alert"><?=number_format($total_price-$capital_box,0,',','.')?><sup>đ</sup></div></div>
		</div>
	</div>
</div>
<div class="modal fade" id="add-booking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Kiểm kho nhanh</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="wrap-lvl">
					<input type="hidden" id="category_main" name="category_id" value="">
					<div class="row">
						<div class="col-md-2">
							<label style="margin: 8px 0;"> Cấp 1:</label>
						</div>
						<div class="col-md-10">
							<select lv="1" name="category_id" class="form-control frm-input lv-cate-main">
							<option value="">Chọn danh mục</option>
							<? $category_info = new stdClass();
								$category_info->parent_id = 0;
								$categories = $this->m_product_category->items($category_info,$active=null, $limit=null, $offset=null, $order_by='code', $sort_by='ASC');
								foreach($categories as $categorie) {
							?> 
							<option list_cate='["<?=$categorie->id?>"]' value="<?=$categorie->id?>"><?=$categorie->name?></option>
							<? } ?>
							</select>
						</div>
					</div>
					<div style="margin:5px 0;" class="lv-1"></div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label style="margin: 8px 0;">Sản phẩm:</label>
					</div>
					<div class="col-md-10">
						<select class="form-control selectpicker" id="select-country" data-live-search="true"></select>
					</div>
				</div>
				<div class="wrap-info-detail" productId = ""></div>
			</div>
		</div>
	</div>
</div>
<div class="cluster">
	<div class="container-fluid">
		<form id="frm-admin" name="adminForm" action="" method="GET">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="boxchecked" name="boxchecked" value="0" />
			<input type="hidden" id="fromdate" name="fromdate" value="<?=$fromdate?>" />
			<input type="hidden" id="todate" name="todate" value="<?=$todate?>" />
			<input type="hidden" id="type" name="type" value="<?=$type?>" />
			<div class="wrap-box">
				<? 
				function print_categories($obj, $categories, &$i, &$arr_type,$code) {
					foreach ($categories as $category) {
						$child_category_info = new stdClass();
						$child_category_info->parent_id = $category->id;
						$child_categories = $obj->m_product_category->items($child_category_info,null,null,null,'order_num','ASC');
						$c = $code."parent{$category->parent_id}-{$category->parent_id} ";
						$cl = 'parent'.$category->parent_id;
						$i++;
						if (!empty($arr_type[$category->id]['tien_von'])) {
							$tien_von = !empty($arr_type[$category->id]['tien_von'])?$arr_type[$category->id]['tien_von']:0;
							$tien_ban = !empty($arr_type[$category->id]['tien_ban'])?$arr_type[$category->id]['tien_ban']:0;
							$von_ton = !empty($arr_type[$category->id]['von_ton'])?$arr_type[$category->id]['von_ton']:0;
							$tong_sl = !empty($arr_type[$category->id]['tong_sl'])?$arr_type[$category->id]['tong_sl']:0;
							$sl_ton = !empty($arr_type[$category->id]['sl_ton'])?$arr_type[$category->id]['sl_ton']:0;
							$loi_nhuan = $tien_ban - $tien_von;
								if ($category->parent_id == 0)
								echo '<div class="box-parent '.$c.$cl.'" style="display:block">';
								else
								echo '<div class="box-child '.$c.$cl.'" style="display:none;">';
								echo   '<div class="box">
											<div class="box-col" style="width:200px;">
												<div class="name">';
												if (!empty($child_categories)) 
												echo '<span class="ctrl-box" cate_id="'.$category->id.'" stt="1"><i class="fa fa-plus" aria-hidden="true"></i></span>';
												else
												echo '<span class="ctrl-box-sub" cate_id="'.$category->id.'" stt="2"><i class="fa fa-minus" aria-hidden="true"></i></span>';
												echo '<span>'.$category->name.'<br><a style="font-size:11px" href="'.site_url("syslog/enter-box/{$category->id}").'">Xem chi tiết</a></span></div>
												
											</div>
											<div class="box-col">
												<table class="table table-bordered">
													<tbody><tr>
														<th style="width:100px;">Tiền vốn</th>
														<th style="width:100px;">Tiền bán</th>
														<th style="width:100px;">Vốn (tồn)</th>
														<th style="width:100px;">Lợi nhuận</th>
														<th style="width:100px;">Tổng SL</th>
														<th style="width:100px;">SL (tồn)</th>
													</tr>
													<tr>
														<td style="font-weight: 400;width:100px;">'.number_format($tien_von,0,',','.').'<sup>₫</sup></td>
														<td style="font-weight: 400;width:100px;">'.number_format($tien_ban,0,',','.').'<sup>₫</sup></td>
														<td style="font-weight: 400;width:100px;">'.number_format($von_ton,0,',','.').'<sup>₫</sup></td>
														<td style="font-weight: 400;width:100px;">'.number_format($loi_nhuan,0,',','.').'<sup>₫</sup></td>
														<td style="font-weight: 400;width:100px;">'.$tong_sl.'</td>
														<td style="font-weight: 400;width:100px;">'.$sl_ton.'</td>
													</tr>		
												</tbody></table>
											</div>
										</div>';
									echo print_categories($obj, $child_categories, $i,$arr_type,$code."parent{$category->parent_id}-{$category->parent_id} ");
									echo '</div>';
								}
							}
						}
					$i = 0;
					$info = new stdClass();
					$info->parent_id = 0;
					$product_category = $this->m_product_category->items($info,null,null,null,'order_num','ASC');
					print_categories($this, $product_category, $i,$arr_type,'');
				?>
			</div>
		</form>
	</div>
</div>
<script>
	$(document).on('change','.lv-cate-main',function(){
		let lv = $(this).attr('lv');
		let lv_next = parseInt(lv)+1;
		let cate_id = $('option:selected', this).val();
		let list_cate = $('option:selected', this).attr('list_cate');
		$('#category_main').val(list_cate);
			list_cate = list_cate.replace('[','');
			list_cate = list_cate.replace(']','');
		$.ajax({
			url: '<?=site_url('syslog/get-product-in-cate');?>',
			type: 'POST',
			dataType: 'json',
			data: {cate_id: cate_id},
			success: (res) => {
				let str = '<select class="form-control selectpicker" id="product_id" data-live-search="true" require></select>';
				if(res[0]=='cate'){
					let str_cate = '<div class="row">';
							str_cate += '<div class="col-md-2"><label style="margin: 8px 0;"> Cấp '+lv_next+':</label></div>';
							str_cate +='<div class="col-md-10">';
								str_cate +='<select lv="'+lv_next+'" name="category_id" class="form-control frm-input lv-cate-main">';
								str_cate +='<option value="">Chọn danh mục</option>';
								for (var i=0;i<res[1].length;i++) {
									var list_cate_temp = '['+list_cate;
										list_cate_temp += ',"'+res[1][i].id+'"'+']';
									str_cate +='<option list_cate='+list_cate_temp+' value="'+res[1][i].id+'">'+res[1][i].name+'</option>';
								}
								str_cate +='</select>';
							str_cate +='</div>';
						str_cate +='</div>';
						str_cate += '<div style="margin:5px 0;" class="lv-'+lv_next+'"></div>';
					$('.lv-'+lv).html(str_cate);
				} else {
					str = '<select class="form-control selectpicker" id="product_id" data-live-search="true" require> <option data-tokens="">Chọn sản phẩm</option>';
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
	$(document).on('click', '.selected > a', function (e) {
		var productid = $(this).attr('data-tokens').split(' - ');
		productid = productid[productid.length-1]
		var hf = '<?=BASE_URL.'/syslog/enter-box/0/detail/'?>'+productid+'.html';
		window.location.href = hf;
	});
	$('.ctrl-box').click(function(e){
		var stt = $(this).attr('stt');
		var cate_id = $(this).attr('cate_id');
		if (stt == '1'){
			$('.parent'+cate_id).css('display','block');
			$(this).attr('stt','2');
			$(this).html('<i class="fa fa-minus" aria-hidden="true"></i>');
		} else {
			$('.parent'+cate_id+'-'+cate_id).css('display','none');
			$('.parent'+cate_id+'-'+cate_id).find('.ctrl-box').attr('stt','1');
			$('.parent'+cate_id+'-'+cate_id).find('.ctrl-box').html('<i class="fa fa-plus" aria-hidden="true"></i>');
			$(this).attr('stt','1');
			$(this).html('<i class="fa fa-plus" aria-hidden="true"></i>');
		}
	})
</script>
<script>
$(document).ready(function() {
	$(".datepicker").daterangepicker({
		singleDatePicker: true
	});
	if ($(".daterange").length) {
		$(".daterange").daterangepicker({
			startDate: "<?=date('m/d/Y', strtotime((!empty($fromdate)?$fromdate:"now")))?>",
			endDate: "<?=date('m/d/Y', strtotime((!empty($todate)?$todate:"now")))?>"
		});
	}
	$(".btn-report").click(function(){ 
		if ($(".daterange").length) {
			$("#fromdate").val($(".daterange").data("daterangepicker").startDate.format('YYYY-MM-DD'));
			$("#todate").val($(".daterange").data("daterangepicker").endDate.format('YYYY-MM-DD'));
		}
		submitButton('search');
	});
	$(".report").click(function(){
		var type = $(this).attr('val');
		$("#type").val(type);
		submitButton('search');
	});
});
</script>