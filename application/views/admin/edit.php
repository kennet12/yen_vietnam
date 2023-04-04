<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title">Tạo đơn hàng</h1>
		<form id="frm-admin" name="adminForm" action="" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="">
			<div class="row">
				<div class="col-md-7 booking-detail" style="display:block;">
					<div class="list">
						<h4>A. Giỏ hàng</h4>
						<a data-toggle="modal" data-target="#add-booking" style="margin:0 0 10px 0;cursor:pointer; display:inline-block;">+Thêm sản phẩm</a>
						<div class="modal fade" id="add-booking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Thêm sản phẩm</h5>
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
											<select class="form-control selectpicker" id="select-country" data-live-search="true">
												
											</select>
										</div>
									</div>
									<div class="wrap-info-detail" productId = ""></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
									<button type="button" class="btn btn-primary btn-add-product" data-dismiss="modal">Thêm</button>
								</div>
								</div>
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
							$(document).on('click', '.selected > a', function (e) {
								var val = $(this).attr('data-tokens');
								val = val.split(' - ');
								$.ajax({
									url: '<?=site_url('syslog/load-product')?>',
									type: 'POST',
									dataType: 'json',
									data: {productId:val[val.length-1]},
									success: function (res) { 
										$('.wrap-info-detail').attr('productId',val[val.length-1]);
										$('.wrap-info-detail').html(res[0]);
										if (res[1] == 0)
											$(".btn-add-product").attr("disabled", true);
										else 
											$(".btn-add-product").attr("disabled", false);
									}
								});
							});
							$(document).on('click', '.p-typename', function (e) {
								let st = $(this).attr('stt');
								$('.p-subtypename').css('display','none');
								$('.subtypename-'+st).css('display','block');
							});
							$(document).on('click', '.get-item', function (e) {
								let price = $(this).find('.sub-price').attr('price');
								let max_qty = parseInt($(this).attr('qty'));
								if(price != undefined) { 
									let sale = parseFloat($(this).find('input').attr('sale'));
									let str = '';
									for(var i=1;i<=max_qty;i++){
										str += '<option value="'+i+'">'+i+'</option>';
									}
									$('.qty-product').html('Còn '+max_qty+' sản phẩm');
									$('.price').attr('price',price*(1-(sale*0.01)));
									$('.price').attr('price_o',price);
									$('.price').attr('sale',sale);
									$('.price').html(formatDollar(price*(1-(sale*0.01)))+'<sup>₫</sup>');
									$('.quantity-admin').attr('max_qty',max_qty);
									$('.quantity-admin').html(str);
								}
							});
							$(document).on('click', '.btn-add-product', function (e) {
								var date = new Date();
  								var tme = date.getTime();
								let product_id = $('.wrap-info-detail').attr('productid');
								let typename 	= $("input[name='typename']:checked").val();
								let subtypename = $("input[name='subtypename']:checked").val();
								let qty = $('.quantity-admin').val();
								let max_qty = $('.quantity-admin').attr('max_qty');
								let price = $('.price').attr('price');
								let sale = $('.price').attr('sale');
								let price_o = $('.price').attr('price_o');
								let total_final = parseFloat($('.total-final').attr('total_final'));
								let str_type = typename;
								//
								let btn_list_product = $('.btn-add-promotion').attr('tme_list');
								if (btn_list_product != ''){
									btn_list_product += ','
								}
								btn_list_product += tme;
								$('.btn-add-promotion').attr('tme_list',btn_list_product);
								//
								if (subtypename == undefined){
									subtypename = '';
								} else {
									str_type += ' - '+subtypename;
								}
								let str = '';
								str += '<div class="item item-'+tme+'">'
									+'<i product_id='+tme+' class="fa fa-trash-o" aria-hidden="true"></i>'
									+'<input type="hidden" class="product-id" name="product_id[]" value="'+product_id+'">'
									+'<input type="hidden" name="typename[]" value="'+typename+'">'
									+'<input type="hidden" name="subtypename[]" value="'+subtypename+'">'
									+'<input type="hidden" name="thumbnail[]" value="'+$('.image-product > img').attr('src')+'">';
									str += '<div class="item-child">';
										str += '<img src="'+$('.image-product > img').attr('src')+'" alt="">';
									str += '</div>';
									str += '<div class="item-child">';
										str += '<h5 class="name">'+$('.filter-option').html()+'</h5>';
										str += '<div class="sku">'+str_type+'</div>';
										str += '<div class="price-note">'+$('.price').html()+' (1 item)</div>';
									str += '</div>';
									str += '<div class="item-child"><select id="select-'+tme+'" price_f="'+price+'" product_id="'+tme+'" class="form-control change-qty" name="qty[]" value="2">';
										for (var i=1;i<=max_qty;i++) {
											str += '<option value="'+i+'">'+i+'</option>';
										}
									str += '</select></div>';
									str += '<div class="item-child">';
										str += '<div price="'+qty*price+'" sale="'+sale+'" price_o="'+price_o+'" class="total-price total-price-'+tme+'">'+formatDollar(qty*price)+'<sup>đ</sup></div>';
									str += '</div>';
								str += '</div>';
								$('.total-final').attr('total_final',total_final+(qty*price));
								var ship = parseFloat($('#ship_money').val()); console.log(ship);
								$('.total-final').html(formatDollar(total_final+(qty*price)+ship)+'<sup>đ</sup>');
								$('.wrap-list-item').append(str);
								$('#select-'+tme).val(qty);
							});
							$(document).on('change','.change-qty',function (e){
								let productId = $(this).attr('product_id');
								let price = parseFloat($('.total-price-'+productId).attr('price'));
								let total_final = parseFloat($('.total-final').attr('total_final'));
									total_final -= price;

								let qty = parseFloat($(this).val());
								let price_f = parseFloat($(this).attr('price_f'));
								total_final += qty*price_f;

								$('.error-promotion').html('Vui lòng nhập lại mã');
								$('.error-promotion').show();
								$('.price-promotion').hide();
								
								$('.total-price-'+productId).html(formatDollar(qty*price_f)+'<sup>đ</sup>');
								$('.total-price-'+productId).attr('price',qty*price_f);
								$('.total-final').attr('total_final',total_final);
								var ship = parseFloat($('#ship_money').val());
								$('.total-final').html(formatDollar(total_final+ship)+'<sup>đ</sup>');
							});
							$(document).on('click','.fa-trash-o',function (e){
								let product_id = $(this).attr('product_id');
								let btn_list_product = $('.btn-add-promotion').attr('tme_list');
								let tme = product_id.toString();
								btn_list_product = btn_list_product.replace(tme+',','');
								btn_list_product = btn_list_product.replace(','+tme,'');
								btn_list_product = btn_list_product.replace(tme,'');
								$('.btn-add-promotion').attr('tme_list',btn_list_product);

								let price = parseFloat($('.total-price-'+product_id).attr('price'));
								let total_final = parseFloat($('.total-final').attr('total_final'));
								total_final -= price;
								$('.item-'+product_id).remove();
								$('.total-final').attr('total_final',total_final);
								var ship = parseFloat($('#ship_money').val());
								$('.total-final').html(formatDollar(total_final+ship)+'<sup>đ</sup>');
							})
						</script>
						<div class="item">
							<div class="item-child">
								<div class="head-table">Ảnh</div>
							</div>
							<div class="item-child">
								<div class="head-table">Chi tiết</div>
							</div>
							<div class="item-child">
								<div class="head-table">Số lượng</div>
							</div>
							<div class="item-child">
								<div class="head-table">Tổng tiền</div>
							</div>
						</div>
						<div class="wrap-list-item"></div>
						<div class="item clearfix" style="padding: 0 20px;">
							<div class="pull-left">
								<h5 style="font-size: 16px;">Khuyến mãi</h5>
							</div>
							<div class="pull-right">
								<div class="input-group">
									<input type="text" id="promotion" name="promotion" class="form-control" placeholder="Nhập mã khuyến mãi">
									<span class="input-group-btn">
										<button class="btn btn-default btn-add-promotion" tme_list="" type="button">Nhập</button>
									</span>
								</div>
								<div class="error-promotion">Vui lòng nhập mã</div>
								<div class="price-promotion"></div>
							</div>
						</div>
						<script>
							$('.btn-add-promotion').click(function(e){
								if ($("#promotion").val() == "") {
									$('.error-promotion').show();
								} else {
									let tme_list = $(this).attr('tme_list').split(',');
									var p = {};
									p["promotion"] = $("#promotion").val();
									p["total_final"] = $(".total-final").attr('total_final');
									$.ajax({
										url: '<?=site_url('syslog/add-promotion')?>',
										type: 'POST',
										dataType: 'json',
										data: p,
										success: function(res) {
											var total_price = 0;
											var total_sale_price = 0;
											if (res[1] != null){
												var total_sale_product_price = 0;
												var sale_value = parseFloat(res[1].sale_value);
												for (var i = 0; i < tme_list.length; i++) {
													var price_o = parseFloat($('.total-price-'+tme_list[i]).attr('price_o'));
													var price_cost = price_o*parseFloat($('#select-'+tme_list[i]).val());
													var sale_product = parseFloat($('.total-price-'+tme_list[i]).attr('sale'));
													total_price += price_cost;
													if (sale_product != 0){
														total_sale_product_price += price_cost*sale_product*0.01;
														$('.total-price-'+tme_list[i]).html(formatDollar(price_cost)+'<sup>đ</sup>');
														$('.item-'+tme_list[i]).find('.price-note').html(formatDollar(price_cost));
													}
													if (sale_product>sale_value){
														sale_value = sale_product;
													}
													if (res[1].sale_type=="0"){
														total_sale_price += price_cost*sale_value*0.01;
													} else {
														total_sale_price = sale_value
													}
												}
												if (res[1].sale_type=="0"){
													if (total_sale_price > res[1].money_limit){
														total_sale_price = parseFloat(res[1].money_limit);
													}
													if (total_sale_product_price > res[1].money_limit){
														total_sale_price = total_sale_product_price;
													}
												} else {
													if (total_sale_product_price > sale_value){
														total_sale_price = total_sale_product_price;
													}
												}
												total_price -= total_sale_price;
												$('.price-promotion').html('-'+formatDollar(total_sale_price)+'<sup>đ</sup>');
												$('.price-promotion').show();
												$('.error-promotion').hide();
											} else {
												for (var i = 0; i < tme_list.length; i++) {
													var price_o = parseFloat($('.total-price-'+tme_list[i]).attr('price_o'));
													var price_cost = price_o*parseFloat($('#select-'+tme_list[i]).val());
													total_price += price_cost;
												}
												if (res[0] == 0){
													$('.error-promotion').html('Mã không hợp lệ');
												} else if (res[0] == -1){
													$('.error-promotion').html('Đơn hàng chưa đủ');
												}
												$('.error-promotion').show();
												$('.price-promotion').hide();
											}
											var ship = parseFloat($('#ship_money').val());
											$('.total-final').html(formatDollar(total_price+ship)+'<sup>đ</sup>');
										}
									});
								}
							})
						</script>
						<div class="item clearfix" style="padding: 0 20px;">
							<div class="pull-left">
								<h5 style="font-size: 16px;">Phí vận chuyển</h5>
							</div>
							<div class="pull-right">
								<input type="number" id="ship_money" name="ship_money" class="form-control full-width" value="0">
							</div>
							<script>
								$('#ship_money').change(function(e){
									var total = parseFloat($('.total-final').attr('total_final'));
									var ship = parseFloat($(this).val());
									$('.total-final').html(formatDollar(total+ship)+'<sup>đ</sup>');
								})
							</script>
						</div>
						<div class="item clearfix" style="padding: 0 20px;">
							<div class="pull-left">
								<h5 style="font-size: 16px;">Thành tiền</h5>
							</div>
							<div class="pull-right">
								<h5 class="total-final" total_final="0" style="font-size: 16px;color: #FF5722;font-weight: 600;">0</h5>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-5 booking-detail" style="display:block;">
					<div class="list">
						<h4>B. Thông tin khách hàng</h4>
						<table class="table table-bordered">
							<tr>
								<td class="table-head text-right" width="100px">Họ tên khách</td>
								<td><input type="text" id="fullname" name="fullname" class="form-control" value=""></td>
							</tr>
							<tr>
								<td class="table-head text-right" width="100px">Email</td>
								<td><input type="text" id="email" name="email" class="form-control" value=""></td>
							</tr>
							<tr>
								<td class="table-head text-right" width="100px">Điện thoại</td>
								<td><input type="text" id="phone" name="phone" class="form-control" value=""></td>
							</tr>
							<tr>
								<td class="table-head text-right" width="100px">Địa chỉ</td>
								<td><input type="text" id="address" name="address" class="form-control" value=""></td>
							</tr>
							<tr>
								<td class="table-head text-right" width="100px">Tin nhắn</td>
								<td><textarea id="message" name="message" class=" form-control" rows="5"></textarea></td>
							</tr>
						</table>
					</div>
					<div class="list">
						<h4>C. Phương thức thanh toán</h4>
						<label class="radio-inline">
							<input type="radio" name="payment" value="Shopee" checked>Shopee
						</label>
						<label class="radio-inline">
							<input type="radio" name="payment" value="Tiki"> Tiki
						</label>
						<label class="radio-inline">
							<input type="radio" name="payment" value="Lazada"> Lazada
						</label>
						<label class="radio-inline">
							<input type="radio" name="payment" value="Banking" >Chuyển khoản
						</label>
						<label class="radio-inline">
							<input type="radio" name="payment" value="Cash"> Thành toán nhận hàng
						</label>
					</div>
				</div>
			</div>
			<div class="text-right">
				<button class="btn btn-sm btn-primary btn-save">Tạo đơn</button>
				<button class="btn btn-sm btn-default btn-cancel">Hủy bỏ</button>
			</div>
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