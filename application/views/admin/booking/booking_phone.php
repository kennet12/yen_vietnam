<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title">
			Tra cứu số điện thoại
			<br><br>
			<div class="pull-right">
				<div class="clearfix">
					<div class="pull-left" style="margin-right: 5px;">
						<div class="input-group input-group-sm">
							<input type="text" id="report_text" name="report_text" class="form-control" value="<?=$search_text?>" placeholder="Nhập số điện thoại muốn tra cứu"  style="width: 250px;">
						</div>
					</div>
					<div class="pull-left">
						<button class="btn btn-sm btn-default btn-report" type="button">Gửi</button>
					</div>
				</div>
			</div>
		</h1>
		<br>
		<br>
		<form id="frm-admin" name="adminForm" action="" method="GET">
			<input type="hidden" id="search_text" name="search_text" value="<?=$search_text?>">
			<div style="padding: 1px;">
				<div class="row">
				<div class="col-md-3"></div>
					<div class="col-md-6">
					<div style="display:block;background: #fff;border: 1px solid #e8e8e8;padding: 10px;font-size: 14px;color: #444;">
						Có <span style="color:red;"><?=$total?></span> đơn hàng đặt từ số điện thoai <?=$search_text?>.
					</div>
					<? 
					$setting = $this->m_setting->load(1);
					foreach ($bookings as $booking) { ?>
					<div style="width: 100%;margin: 15px auto;    border: 1px solid #e8e8e8;">
						<div style="color: #fc5722;display: table;width: 100%;background: #FFF;padding: 15px;">
							<div style="display:table-cell;width:30%;">
							<a href="<?=BASE_URL?>" target="_blank"><img style="width: 130px;" src="<?=BASE_URL?>/images/logo/logo.png" alt=""></a>
							</div>
							<div style="display:table-cell;width:70%;color:#fc5722;vertical-align:top;text-align:right">
								<p style="margin:0;"><a href="tel:<?=$setting->company_hotline?>" style="color: #fc5722;text-decoration: none;font-size: 12px;"><?=$setting->company_hotline?></a></p>
								<p style="margin:0;"><a href="mailto:<?=$setting->company_email?>" style="color: #fc5722;text-decoration: none;font-size: 12px;"><?=$setting->company_email?></a></p>
								<p style="margin:0;"><a href="<?=GOOGLE_MAPS_LINK?>" target="_blank" style="color: #fc5722;text-decoration: none;font-size: 12px;"><?=$setting->company_address?></a></p>
							</div>
						</div>
						<div style="padding:0 15px 15px;background: #fff;">
							<div style="display:table;width:100%; font-size:14px;border-top: 2px solid #fc5722;padding-top: 20px;">
								<div style="display:table-cell;width:35%">
									<div style="color: #fc5722;margin-bottom: 7px;font-size:12px;">Người nhận</div>
									<strong style="font-size:15px;"><?=$booking->fullname?></strong>
									<p style="margin-bottom:0"><?=$booking->address?></p>
								</div>
								<div style="display:table-cell;width:25%;padding-left: 5%;">
									<div style="color: #999;margin-bottom: 7px;font-size:12px;">Mã đơn hàng</div>
									<p style="margin-bottom:0"><?=BOOKING_PREFIX.$booking->id?></p>
									<br>
									<div style="color: #999;margin-bottom: 7px;font-size:12px;">Ngày đặt hàng</div>
									<p style="margin-bottom:0"><?=date('d/m/Y',strtotime($booking->created_date))?></p>
								</div>
								<div style="display:table-cell;width:40%;text-align:right;">
									<div style="color: #999;margin-bottom: 7px;font-size:12px;">Tổng tiền</div>
									<div style="font-size: 20px;color: #2e7731;font-weight: bold;"><?=number_format($booking->total+$booking->ship_money,0,',','.')?><sup>₫</sup></div>
									<div style="color: #999;margin-bottom: 7px;font-size: 12px;margin-top: 11px;">Hình thức thanh toán</div>
									<p style="margin-bottom: 0;margin-top: 7px;"><?=$this->util->note_payment($booking->payment)?></p>
								</div>
							</div>
							<div class="text-center">
								<a class="click-more" id-item="<?=$booking->id?>" status="0">Xem chi tiết <i class="fa fa-angle-down" aria-hidden="true"></i></a>
							</div>
							<div class="detail-<?=$booking->id?>" style="display:none;">
								<table style="width: 100%;border-top: 1px solid #fc5722;margin-top: 40px;">
									<tr>
										<td style="width:10%;padding: 20px 0;color: #fc5722;font-weight: bold;">
											Hình
										</td>
										<td style="width:35%;padding: 20px 5px;color: #fc5722;font-weight: bold;">
											Tên sản phẩm
										</td>
										<td style="width:23%;padding: 20px 0px;color: #fc5722;font-weight: bold;">
											Giá
										</td>
										<td style="width:7%;padding: 20px 0px;color: #fc5722;font-weight: bold;">
											SL
										</td>
										<td style="width:25%;padding: 20px 0px;color: #fc5722;font-weight: bold;text-align:right;">
											Thành tiền
										</td>
									</tr>
									<?  
									$total = 0;
									foreach ($booking->details as $pax) {
										$price_sale = $pax->price_sale;
										if (!empty($booking->code_promotion)) {
											$price_sale = $pax->price;
										}
										$total += $price_sale*$pax->qty; 
									?>
									<tr style="border-bottom: 1px solid #eee;">
										<td style="width:10%;padding: 20px 0;">
											<img style="width: 55px;height: 55px;object-fit: contain;" src="<?=$pax->thumbnail?>" alt="">
										</td>
										<td style="width:35%;padding: 20px 5px;">
											<div style="font-size: 14px;"><a href="" target="_blank" style="text-decoration: none;color: #555;display: block;"><?=$pax->title?> </div></a>
											<div style="font-size: 12px;color: #8bc34a"><?=$pax->typename?> <?=$pax->subtypename?></div>
										</td>
										<td style="width:23%;padding: 20px 0px;vertical-align: top;">
											<div style="font-size: 14px;"><?=number_format($price_sale,0,',','.')?><sup>₫</sup></div>
										</td>
										<td style="width:7%;padding: 20px 0px;vertical-align: top;">
											<span style="margin: 8px 0;">x<?=$pax->qty?></span>
										</td>
										<td style="width:25%;padding: 20px 0px;vertical-align: top;text-align:right;">
											<div style="font-size: 14px;"><?=number_format($price_sale*$pax->qty,0,',','.')?><sup>₫</sup></div>
										</td>
									</tr>
									<? } $total = $this->util->round_number($total,1000); ?>
								</table>
								<div class="row">
									<div class="col-md-6"></div>
									<div class="col-md-6">
										<table style="width:100%;margin-top:30px;padding-top: 15px;">
											<tr>
												<td>
													<table style="width: 100%;">
														<? if (!empty($booking->code_promotion)) { ?>
														<tr>
															<td style="width:50%;padding: 3px 10px;font-size: 14px;color: #fc5722;text-align:right;">
																Giảm giá:
															</td>
															<td style="width:50%;font-size: 14px;padding: 3px 10px;text-align:right;">
																<div style="font-size: 18px;">- <?=number_format($total-$booking->total,0,',','.')?><sup>₫</sup></div>
															</td>
														</tr>
														<? } ?>
														<tr>
															<td style="width:50%;padding: 3px 10px;font-size: 14px;color: #fc5722;text-align:right;">
																Phí vận chuyển
															</td>
															<td style="width:50%;font-size: 14px;padding: 3px 10px;text-align:right;">
																<div style="font-size: 18px;"><?=number_format($booking->ship_money,0,',','.')?><sup>₫</sup></div>
															</td>
														</tr>
														<tr>
															<td style="width:50%;padding: 3px 10px;font-size: 14px;color: #fc5722;text-align:right;">
																Tổng tiền:
															</td>
															<td style="width:50%;font-size: 14px;padding: 3px 10px;text-align:right;">
																<div style="font-size: 18px;"><?=number_format($booking->total+$booking->ship_money,0,',','.')?><sup>₫</sup></div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<? } ?>
				</div>
				<div class="col-md-3"></div>
				</div>
				<div class="text-center"><?=$pagination?></div>
			</div>
		</form>
	</div>
</div>
<script>
	$('.click-more').click(function(e){
		var id = $(this).attr('id-item');
		var status = $(this).attr('status');
		if (status == '0') {
			$('.detail-'+id).show();
			$(this).attr('status',1);
			$(this).find('.fa').removeClass('fa-angle-down');
			$(this).find('.fa').addClass('fa-angle-up');
		} else {
			$('.detail-'+id).hide();
			$(this).attr('status',0);
			$(this).find('.fa').removeClass('fa-angle-up');
			$(this).find('.fa').addClass('fa-angle-down');
		}
	})
	$(".btn-report").click(function(){
		$("#search_text").val($("#report_text").val());
		submitButton();
	});
	$(".btn-save").click(function(){
		submitButton("save");
	});
	$(".btn-cancel").click(function(){
		submitButton("cancel");
	});
</script>