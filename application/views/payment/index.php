<div class="container">
	<h2 class="text-center">Thanh Toán Online</h2>
	<div class="booking-list">
		<div class="box-detail cart-shop" style="display: block;">
			<form action="<?=site_url("thanh-toan-online/xu-ly")?>" method="post" accept-charset="utf-8">
			<input type="hidden" name="book_id" value="<?=$booking->id?>">
			<div class="row">
				<div class="col-md-7">
					<h5 class="text-color-gray">A. Thông tin đơn hàng</h5>
					<div class="booking-detail">
						<div class="list">
							<div class="booking-info" style="border: none">
								<table class="info-list">
									<tbody>
										<tr>
											<td class="title-info">Mã đơn hàng:</td>
											<td>
												<?=BOOKING_PREFIX.$booking->id?>
											</td>
										</tr>
										<tr>
											<td class="title-info">Họ và Tên:</td>
											<td>
												<input type="text" class="val-info" name="fullname" value="<?=$booking->fullname?>">
											</td>
										</tr>
										<tr>
											<td class="title-info">Email:</td>
											<td>
												<input type="text" class="val-info" name="email" value="<?=$booking->email?>">
											</td>
										</tr>
										<tr>
											<td class="title-info">Số điện thoại:</td>
											<td>
												<input type="text" class="val-info" name="phone" value="<?=$booking->phone?>">
											</td>
										</tr>
										<tr>
											<td class="title-info">Địa chỉ:</td>
											<td>
												<input type="text" class="val-info" name="address" value="<?=$booking->address?>">
											</td>
										</tr>
										<tr>
											<td class="title-info" style="vertical-align: top;">Nội dung:</td>
											<td>
												<textarea class="val-info" name="message" rows="3" value=""><?=$booking->message?></textarea>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="item display-table">
								<div class="display-table-cell text-center" style="width: 35%;">
									<div class="title-total">Tổng tiền (VND)</div>
								</div>
								<div class="display-table-cell" style="width: 15%;">
									
								</div>
								<div class="display-table-cell text-center" style="width: 15%;">

								</div>
								<div class="display-table-cell text-center" style="width: 35%;">
									<div class="total-price"><?=number_format($booking->total,0,',','.')?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<h5 class="text-color-gray">B. Phương thức thanh toán</h5>
					<div class="payment">
						<div class="display-table">
							<!-- <div class="payment-item display-table-cell" style="width:50%;">
								<div class="radio">
									<label class="full-width text-center">
										<img src="<?=IMG_URL.'paypal.png'?>" alt="">
										<input type="radio" name="payment" value="Paypal"><br>Paypal
									</label>
								</div>
							</div> -->
							<div class="payment-item display-table-cell" style="width:50%;">
								<div class="radio">
									<label class="full-width text-center">
										<img src="<?=IMG_URL.'onepay.png'?>" alt="">
										<input type="radio" name="payment" value="Onepay" checked><br>Onepay
									</label>
								</div>
							</div>
						</div>
						<div class="text-center">
							<button type="submit" class="btn-payment-online">Thanh toán</button>
						</div>
					</div>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>