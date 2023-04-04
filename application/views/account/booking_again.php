<div class="container">
	<div class="book-cart-shop">
		<form id="frm-checkout" action="<?=site_url("dat-hang/thanh-toan-lai-api/{$booking->booking_key}")?>" method="POST" role="form">
			<div class="row">
				<div class="col-md-5">
					<h5 class="title">Giỏ Hàng Của Bạn</h5>
					<div class="list">
					<? $i=0; 
						$c=count($booking_detail); 
						$totalprice = 0;
						$totalqty = 0;
						foreach ($booking_detail as $item) { 
							$price = $item->qty*$item->price_sale; 
							$totalprice += $price;
							$totalqty 	+= $item->qty;

							$product = $this->m_product->load($item->product_id);
							$photo = explode('/',$item->thumbnail);

						?>
						<div class="item display-table">
							<div class="display-table-cell text-center image">
								<img src="<?=BASE_URL."/files/upload/product/{$product->code}/thumbnail/".end($photo);?>" class="img-responsive" alt="<?=$item->title?>">
							</div>
							<div class="display-table-cell info">
								<a href="<?=site_url($product->alias)?>"><div class="name transition"><?=$item->title?></div></a>
								<div class="sub-detail"><?=$item->typename?> <?=!empty($item->subtypename)?" - ".$item->subtypename:""?></div>
								<div class="clearfix"></div>
								<div class="price-detail"><?=number_format($item->price,0,',','.')?><sup>₫ 
									<? if(!empty($item->sale)) { ?>
									<span class="text-color-red">-<?=$item->sale?>%</span>
									<? } ?>
									</sup> x <?=$item->qty?>
								</div>
								<div class="fee">
									<? if(!empty($item->sale)) { ?>
									<div class="note-sale note-sale-<?=$i?>">( -<?=number_format($item->qty*($item->sale*$item->price*0.01),0,',','.')?><sup>₫</sup> )</div>
									<? } ?>
									<div class="price price-<?=$i?>" ><?=number_format($price,0,',','.')?><sup>₫</sup></div>
								</div>	
							</div>
						</div>
					<? $i++;} ?>
						<div class="review-box">
							<div class="clearfix" style="border-top:1px solid #e1e1e1;padding-top: 20px;">
								<div class="float-left">
									<span class="total-qty"><?=$totalqty?></span> sản phẩm
								</div>
								<div class="float-right">
									<div class="price total"><?=number_format($totalprice,0,',','.')?><sup>₫</sup></div>
								</div>
							</div>
							<!-- <div class="clearfix" style="padding-bottom: 20px;">
								<div class="float-left">
									Phí vận chuyển
								</div>
								<div class="float-right">
									<div class="price">20.000<sup>đ</sup></div>
								</div>
							</div> -->
							<div class="clearfix" style="border-top:1px solid #e1e1e1;padding-top: 20px;border-bottom: 1px solid #e1e1e1; padding: 15px 0;">
								<div class="float-left">
									Tổng tiền (VND)
								</div>
								<div class="float-right">
									<div class="total-price total"><?=number_format($totalprice,0,',','.')?><sup>₫</sup></div>
								</div>
							</div>
							<p class="time-ship">Thời gian giao hàng: <strong>Trong vòng 8-10 giờ (từ khi xuất hàng)</strong></p>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="info-box">
						<div class="item">
							<div class="row">
								<div class="col-md-6">
									<label class="text-color-gray">Họ và Tên</label><span class="text-color-red"> *</span>
									<input type="text" id="fullname" name="fullname" class="contact-input" value="<?=empty($booking->fullname) ? '' : $booking->fullname?>">
								</div>
								<div class="col-md-6">
									<label class="text-color-gray">Email</label><span class="text-color-red"> *</span>
									<input type="text" id="email" name="email" class="contact-input" value="<?=empty($booking->email) ? '' : $booking->email?>">
								</div>
							</div>
						</div>
						<div class="item">
							<div class="row">
								<div class="col-md-6">
									<label class="text-color-gray">Điện thoại người nhận</label><span class="text-color-red"> *</span>
									<input type="text" id="phone" name="phone" class="contact-input" value="<?=empty($booking->phone) ? '' : $booking->phone?>">
								</div>
								<div class="col-md-6">
									<label class="text-color-gray">Điện thoại 2</label>
									<input type="text" id="phone_temp" name="phone_temp" class="contact-input" value="<?=empty($booking->phone_temp) ? '' : $booking->phone_temp?>">
								</div>
							</div>
						</div>
						<div class="item">
							<label class="text-color-gray">Địa chỉ</label><span class="text-color-red"> *</span>
							<input type="text" id="address" name="address" class="contact-input" value="<?=empty($booking->address) ? '' : $booking->address?>">
						</div>
						<div class="item">
							<div class="row">
								<div class="col-md-6">
									<label class="text-color-gray">Ghi chú giao hàng</label>
									<select class="form-control" name="note" id="note">
										<option value="Giao giờ hành chính">Giao giờ hành chính</option>
										<option value="Đặt hàng hộ người thân">Đặt hàng hộ người thân</option>
										<option value="">Ghi chú thêm</option>
									</select>
								</div>
							</div>
							<br>
							<textarea style="display:none" name="message" id="message" rows="5"></textarea>
							<script>
								$('#note').change(function () {
									let val = $(this).val();
									if (val == ''){
										$('#message').css('display','block');
									} else {
										$('#message').css('display','none');
										$('#message').val('');
									}
								})
							</script>
							<h5 class="title-payment">Hình thức thanh toán</h5>
							<div class="payment">
								<div class="payment-item">
									<div class="radio">
										<label class="full-width text-center">
											<img src="<?=IMG_URL.'banking.png'?>" alt="">
											<input type="radio" name="payment" class="payment-method" value="Banking" <?=($booking->payment == 'Banking') ? 'checked' : ''?>>Chuyển khoản
										</label>
									</div>
								</div>
								<div class="payment-item">
									<div class="radio">
										<label class="full-width text-center">
											<img src="<?=IMG_URL.'thanh-toan-khi-nhan-hang.png'?>" alt="">
											<input type="radio" name="payment" class="payment-method" value="Cash" <?=($booking->payment == 'Cash') ? 'checked' : ''?>>Thành toán khi nhận hàng
										</label>
									</div>
								</div>
							</div>
							<br><br>
							<div class="g-recaptcha" data-theme="light" data-sitekey="<?=RECAPTCHA_KEY?>"></div>
						</div>
					</div>
					<div class="item text-center">
						<a href="#" class="btn btn-red btn-checkout transition">THANH TOÁN</a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="wrap-loading-checkout" style="display:none">
	<div class="box-loading-checkout">
		<h2>Hệ thống đang xử lý ...</h2>
		<img src="<?=IMG_URL.'shipper.gif'?>" alt="">
	</div>
</div>
<div class="wrap-delete-cart" style="display:none">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<div class="box-delete">
				<div class="box-title">
					Xóa sản phẩm
				</div>
				<div class="box-content">
					Bạn có chắc là muốn xóa sản phẩm này không?
				</div>
				<div class="confirm-box">
					<button type="button" class="btn btn-danger btn-sure-delete" rowid_item="">OK</button>
					<button type="button" class="btn btn-secondary btn-cancel" data-dismiss="modal">Đóng</button>
				</div>
			</div>				
		</div>
		<div class="col-md-4"></div>
	</div>
</div>
<!-- <div class="modal fade" id="delete-cart" tabindex="-1" role="dialog" aria-labelledby="delete-cartLabel" aria-hidden="true"><div class="modal-dialog modal-warning" id="dialog" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="delete-cartLabel">Xóa sản phẩm</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body">Bạn chắc chắn muốn xóa sản phẩm này?</div><div class="modal-footer"></div></div></div></div> -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.btn-checkout').click(function() {
		var err = 0;
		var msg = [];

		clearFormError();

		if ($("#fullname").val() == "") {
			$("#fullname").addClass("error");
			err++;
			msg.push("Họ và Tên không được trống.");
		} else {
			$("#fullname").removeClass("error");
		}

		if ($("#email").val() == "") {
			$("#email").addClass("error");
			err++;
			msg.push("Email không được trống.");
		} else {
			$("#email").removeClass("error");
		}
		if ($("#phone").val() == "") {
			$("#phone").addClass("error");
			err++;
			msg.push("Số điện thoại không được trống.");
		} else {
			if (validatePhone("phone") == false) {
				$("#phone").addClass("error");
				err++;
				msg.push("Số điện thoại không hợp lệ");
			}
			else {
				$("#phone").removeClass("error");
			}
		}
		if ($("#address").val() == "") {
			$("#address").addClass("error");
			err++;
			msg.push("Địa chỉ không được trống.");
		} else {
			$("#address").removeClass("error");
		}
		if ($('#g-recaptcha-response').val() == "") {
			err++;
			msg.push('Xác nhận tôi không phải là robot');
		}


		if (!err) {
			$('.wrap-loading-checkout').css('display','block');
			$("#frm-checkout").submit();
		} else {
			showErrorMessage(msg);
		}
	});
});
</script>