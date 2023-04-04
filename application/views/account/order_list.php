<div class="container">
	<? if(empty($bookings)) { ?>
	<div class="order"><?=$website['note_checkout']?></div>
	<? } ?>
	<? 
	if(!$this->util->detect_mobile()) {
	foreach ($bookings as $booking) { ?>
	<div class="order">
		<div class="status">
			<div class="orderid"><label><b>ĐH #<?=BOOKING_PREFIX.$booking->id?></b></label></div>
			<div class="orderstatus" data-id="<?=BOOKING_PREFIX.$booking->id?>">
				<label class="complete" status="0">
					<?  if ($booking->status == 1) {
							echo '<i class="fas-process zmdi zmdi-refresh-alt"></i> '.$website['waiting'];
						} else if ($booking->status == 2) {
							echo '<i class="fas-process zmdi zmdi-assignment-check"></i> '.$website['confirm'];
						} else if ($booking->status == 3) {
							echo '<i class="fas-process zmdi zmdi-truck"></i> '.$website['shipping'];
						} else if ($booking->status == 4) {
							echo '<i class="fas-process zmdi zmdi-check"></i> '.$website['completed'];
						} 
					?>
				<i class="zmdi zmdi-chevron-down"></i></label>
				<div class="list-status" style="display:none">
					<?
						$arr = array(
							'<i class="zmdi zmdi-refresh-alt"></i> <span>'.$website['waiting'].'</span>',
							'<i class="zmdi zmdi-assignment-check"></i> <span>'.$website['confirm'].'</span>',
							'<i class="zmdi zmdi-truck"></i> <span>'.$website['shipping'].'</span>',
							'<i class="zmdi zmdi-check"></i> <span>'.$website['completed'].'</span>',
						);
					?>
					<div class="process">
						<? for ($i=1;$i<=4;$i++) { ?>
						<div class="<?=($i == $booking->status)?'actived':'active'?>"><?=$arr[$i-1]?></div>
						<? } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="payment-method"><i class="zmdi zmdi-money fa-donate"></i>&nbsp 
		<?
			if ($booking->payment == 'Banking') {
				echo $website['banking'];
			} else {
				echo $website['ship_cod'];
			}
		?> 
		<!-- &nbsp&nbsp<a>Xem thêm <i class="up-down fas fa-chevron-down"></i></a> -->
		</div>
		<div class="text-para"><?=$website['note_checkout1']?>: <span><?=$booking->fullname?> (<?=$booking->phone?>)</span></div>
		<div class="text-para">Email: <span><?=$booking->email?></span></div>
		<div class="text-para"><?=$website['order_note2']?>: <span><?=$booking->address?></span></div>
		<div class="text-para"><?=$website['order_note15']?>: <span><?=date($website['format_date'],strtotime($booking->created_date))?></span></div>
		<div class="payment-method"><?=$booking->message?></div>
		<div class="order-cart">
			<? 
			$info = new stdClass();
			$info->booking_id = $booking->id;
			$details = $this->m_booking_detail->items($info);
			$total = 0;
			foreach ($details as $detail) { 
				$total += $detail->qty*$detail->price_sale;
				$sale = round((1- ($detail->price_sale/$detail->price))*100);
				$note = $detail->typename;
				if(!empty($detail->subtypename)) {
					$note .= ' - '.$detail->subtypename;
				}
				$product = $this->m_product->load($detail->product_id);
			?>
			<div class="item">
				<div class="image">
					<img src="<?=BASE_URL.str_replace('./','/',$product->thumbnail)?>" alt="">
				</div>
				<div class="info">
					<div class="limit-content-2-line"><?=$product->{$prop['title']}?></div>
					<div class="property"><?=$note?></div>
					<div class="para"><?=$website['quantity']?>: <?=$detail->qty?> x <?=number_format($detail->price_sale,0,',','.')?><sup>đ</sup></div>
				</div>
				<div class="price">
					<div><?=number_format($detail->qty*$detail->price_sale,0,',','.')?><sup>đ</sup></div>
					<? if (!empty($sale)) { ?>
					<div style="color: darkgrey;text-decoration: line-through;"><?=number_format($detail->qty*$detail->price,0,',','.')?><sup>đ</sup></div>
					<? } ?>
				</div>
			</div>
			<? } ?>
		</div>
		<hr width="50%" style="margin:20px auto;">
		<div class="total">
			<div class="row">
				<div class="col-md-6"></div>
				<div class="col-md-6">
				<table style="width: 100%;">
					<tr>
						<td style="width:70%;padding: 3px 10px;text-align:right;">
						<?=$website['total_price']?>:
						</td>
						<td style="width:30%;font-size: 14px;padding: 3px 10px;text-align:right;">
							<div><?=number_format($total,0,',','.')?><sup>₫</sup></div>
						</td>
					</tr>
					<? if (!empty($booking->code_promotion)) { ?>
					<tr>
						<td style="width:70%;padding: 3px 10px;text-align:right;">
							Mã (<?=$booking->code_promotion?>):
						</td>
						<td style="width:30%;font-size: 14px;padding: 3px 10px;text-align:right;">
							<div>-<?=number_format($total - $booking->total - $booking->discount,0,',','.')?><sup>₫</sup></div>
						</td>
					</tr>
					<? } ?>
					<? if (!empty($booking->discount)) { ?>
					<tr>
						<td style="width:70%;padding: 3px 10px;text-align:right;">
							Trợ giá:
						</td>
						<td style="width:30%;font-size: 14px;padding: 3px 10px;text-align:right;">
							<div>-<?=number_format($booking->discount,0,',','.')?><sup>₫</sup></div>
						</td>
					</tr>
					<? } ?>
                    <tr>
						<td style="width:70%;padding: 3px 10px;text-align:right;">
							<?=$website['fee_ship']?>:
						</td>
						<td style="width:30%;font-size: 14px;padding: 3px 10px;text-align:right;">
							<div><?=number_format($booking->ship_money,0,',','.')?><sup>₫</sup></div>
						</td>
					</tr>
					<tr>
						<td style="width:70%;padding: 3px 10px;text-align:right;">
						<?=$website['total']?>:
						</td>
						<td style="width:30%;font-size: 14px;padding: 3px 10px;text-align:right;">
							<div><strong><?=number_format($booking->total+$booking->ship_money,0,',','.')?><sup>₫</sup></strong></div>
						</td>
					</tr>
					</table>
				</div>
			</div>
		</div>
		<br>
		<div class="text-center">
			<? if($booking->status == 4) { ?>
			<a href="<?=site_url("dat-hang/thanh-toan-lai/{$booking->booking_key}")?>" class="btn-booking"> <?=$website['buy_now']?></a>
			<? } if ($booking->status == 1) { ?>
			<a href="#" ref="<?=site_url("dat-hang/huy-don/{$booking->booking_key}")?>" class="btn-cancel"> <?=$website['cancel_bill']?></a>
			<? } ?>
		</div>
		<br><br>
	</div>
	<? } } else { 
	foreach ($bookings as $booking) { 
		$info = new stdClass();
		$info->booking_id = $booking->id;
		$details = $this->m_booking_detail->items($info);
		$total = 0;
		$arr_photo = array();
		$str = '';
		foreach ($details as $detail) {
			$product = $this->m_product->load($detail->product_id);
			array_push($arr_photo,BASE_URL.str_replace('./','/',$product->thumbnail));
			// 
			$total += $detail->qty*$detail->price_sale;
			$sale = round((1- ($detail->price_sale/$detail->price))*100);
			$note = $detail->typename;
			
			if(!empty($detail->subtypename)) {
				$note .= ' - '.$detail->subtypename;
			}
			$str .='<div class="item">';
				$str .='<div class="image">';
					$str .='<img src="'.BASE_URL.str_replace('./','/',$product->thumbnail).'" alt="">';
				$str .='</div>';
				$str .='<div class="info">';
					$str .='<div class="limit-content-2-line">'.$detail->title.'</div>';
					$str .='<div class="property limit-content-1-line">'.$note.'</div>	';
				$str .='</div>';
				$str .='<div class="price">';
					$str .='<div>'.number_format($detail->qty*$detail->price_sale,0,',','.').'<sup>đ</sup></div>';
					if (!empty($sale))
					$str .='<div style="color: darkgrey;text-decoration: line-through;">'.number_format($detail->qty*$detail->price,0,',','.').'<sup>đ</sup></div>';
					$str .='<div class="para">SL: '.$detail->qty.'</div>';
				$str .='</div>';
			$str .='</div>';
		}
	?>
	<div class="order-mobile">
		<div class="status">
			<div class="orderid">
				<div><?=$website['bill']?></div>
				<label>#<?=BOOKING_PREFIX.$booking->id?></label>
			</div>
			<div class="total-bill">
				<div><?=$website['total']?></div>
				<div><?=number_format($booking->total+$booking->ship_money,0,',','.')?><sup>đ</sup></div>
			</div>
			<div class="orderstatus">
				<label class="complete" status="0">
					<?  if ($booking->status == 1) {
							echo '<i class="fas-process zmdi zmdi-refresh-alt"></i> '.$website['waiting'];
						} else if ($booking->status == 2) {
							echo '<i class="fas-process zmdi zmdi-assignment-check"></i> '.$website['confirm'];
						} else if ($booking->status == 3) {
							echo '<i class="fas-process zmdi zmdi-truck"></i> '.$website['shipping'];
						} else if ($booking->status == 4) {
							echo '<i class="fas-process zmdi zmdi-check"></i> '.$website['completed'];
						} 
					?>
					
				<i class="up-down fas fa-chevron-down"></i></label>
				<div class="list-status" style="display:none">
					<?
						$arr = array(
							'<i class="zmdi zmdi-refresh-alt"></i> <span>'.$website['waiting'].'</span>',
							'<i class="zmdi zmdi-assignment-check"></i> <span>'.$website['confirm'].'</span>',
							'<i class="zmdi zmdi-truck"></i> <span>'.$website['shipping'].'</span>',
							'<i class="zmdi zmdi-check"></i> <span>'.$website['completed'].'</span>',
						);
					?>
					<div class="process">
						<? for ($i=1;$i<=4;$i++) { ?>
						<div class="<?=($i == $booking->status)?'actived':'active'?>"><?=$arr[$i-1]?></div>
						<? } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="status">
			<div class="orderid">
				<? $i=1; $c = count($arr_photo); foreach ($arr_photo as $photo) { ?>
				<div class="image-review-item">
					<? if($c>3 && $i==3){ ?>
					<div class="more-image">+<?=$c-3?></div>
					<? } ?>
					<img src="<?=$photo?>" class="image-review-item" alt="">
				</div>
				<? if($i==3) break; $i++;} ?>
			</div>
			<div class="total-bill click-more-mobile" status="0" data-id="<?=BOOKING_PREFIX.$booking->id?>">
				<div class="more-detail">
					<?=$website['view_detail']?> <i class="up-down fas fa-chevron-down"></i>
				</div>
			</div>
			<div class="orderstatus">
				<? if($booking->status == 4) { ?>
					<a href="<?=site_url("dat-hang/thanh-toan-lai/{$booking->booking_key}")?>" class="btn-booking" style="color:#2196f3;"> <?=$website['buy_now']?> <i class="fas fa-arrow-right"></i></a>
				<? } if ($booking->status == 1) { ?>
					<a href="#" ref="<?=site_url("dat-hang/huy-don/{$booking->booking_key}")?>" class="btn-cancel text-color-red"><i class="far fa-times-circle"></i> <?=$website['cancel_bill']?></a>
				<? } ?>
			</div>
		</div>
		<div class="wrap-more wrap-more-<?=BOOKING_PREFIX.$booking->id?>" style="display:none;">
			<div class="payment-method"><i class="fas fa-donate"></i>&nbsp 
			<?
				if ($booking->payment == 'Banking') {
					echo $website['banking'];
				} else {
					echo $website['ship_cod'];
				}
			?> 
			<!-- &nbsp&nbsp<a>Xem thêm <i class="up-down fas fa-chevron-down"></i></a> -->
			</div>
			<div class="text-para"><?=$website['note_checkout1']?>: <span><?=$booking->fullname?> (<?=$booking->phone?>)</span></div>
			<div class="text-para">Email: <span><?=$booking->email?></span></div>
			<div class="text-para"><?=$website['order_note2']?>: <span><?=$booking->address?></span></div>
			<div class="text-para"><?=$website['order_note15']?>: <span><?=date($website['format_date'],strtotime($booking->created_date))?></span></div>
			<div class="payment-method"><?=$booking->message?></div>
			<div class="order-cart">
				<?=$str?>
			</div>
			<div class="total">
				<table style="width: 100%;font-size: 12px;">
					<tr>
						<td style="width:70%;padding: 3px 10px;text-align:right;">
						<?=$website['total']?>:
						</td>
						<td style="width:30%;padding: 3px 10px;text-align:right;">
							<div><?=number_format($total,0,',','.')?><sup>₫</sup></div>
						</td>
					</tr>
					<? if (!empty($booking->code_promotion)) { ?>
					<tr>
						<td style="width:70%;padding: 3px 10px;text-align:right;">
							Mã (<?=$booking->code_promotion?>):
						</td>
						<td style="width:30%;padding: 3px 10px;text-align:right;">
							<div>-<?=number_format($total - $booking->total - $booking->discount,0,',','.')?><sup>₫</sup></div>
						</td>
					</tr>
					<? } ?>
					<? if (!empty($booking->discount)) { ?>
					<tr>
						<td style="width:70%;padding: 3px 10px;text-align:right;">
							Trợ giá:
						</td>
						<td style="width:30%;padding: 3px 10px;text-align:right;">
							<div>-<?=number_format($booking->discount,0,',','.')?><sup>₫</sup></div>
						</td>
					</tr>
					<? } ?>
					<tr>
						<td style="width:70%;padding: 3px 10px;text-align:right;">
						<?=$website['fee_ship']?>:
						</td>
						<td style="width:30%;padding: 3px 10px;text-align:right;">
							<div><?=number_format($booking->ship_money,0,',','.')?><sup>₫</sup></div>
						</td>
					</tr>
					<tr>
						<td style="width:70%;padding: 3px 10px;text-align:right;">
						<?=$website['total']?>:
						</td>
						<td style="width:30%;padding: 3px 10px;text-align:right;">
							<div><strong><?=number_format($booking->total+$booking->ship_money,0,',','.')?><sup>₫</sup></strong></div>
						</td>
					</tr>
				</table>
			</div>
			<br>
			<div class="text-center">
				<? if($booking->status == 4) { ?>
				<a href="<?=site_url("dat-hang/thanh-toan-lai/{$booking->booking_key}")?>" class="btn-booking"> <?=$website['buy_now']?></a>
				<? } if ($booking->status == 1) { ?>
				<a href="#" ref="<?=site_url("dat-hang/huy-don/{$booking->booking_key}")?>" class="btn-cancel"> <?=$website['cancel_bill']?></a>
				<? } ?>
			</div>
			<br><br>
		</div>
	</div>
	<? } } ?>
	<div style="background: #fff;padding: 20px;">
	<?=$pagination?>
	</div>
</div>
<script type="text/javascript">
	$('.orderstatus > .complete').click(function(e){
		var status = $(this).attr('status');
		if(status === '0'){
			$(this).find('.up-down').removeClass('fa-chevron-down');
			$(this).find('.up-down').addClass('fa-chevron-up');
			$(this).attr('status',1);
			$(this).parent('.orderstatus').find('.list-status').show();
		} else {
			$(this).find('.up-down').removeClass('fa-chevron-up');
			$(this).find('.up-down').addClass('fa-chevron-down');
			$(this).attr('status',0);
			$(this).parent('.orderstatus').find('.list-status').hide();
		}
	});
	$('.click-more-mobile').click(function(e){
		var status = $(this).attr('status');
		var data_id = $(this).attr('data-id');
		if(status === '0'){
			$(this).find('.up-down').removeClass('fa-chevron-down');
			$(this).find('.up-down').addClass('fa-chevron-up');
			$(this).attr('status',1);
			$('.wrap-more-'+data_id).show();
		} else {
			$(this).find('.up-down').removeClass('fa-chevron-up');
			$(this).find('.up-down').addClass('fa-chevron-down');
			$(this).attr('status',0);
			$('.wrap-more-'+data_id).hide();
		}
	})
	$('.btn-cancel').click(function(e){
		var hf = $(this).attr('ref');
		confirm_box('<?=$website['cancel_bill']?>','<?=$website['note_cancel_bill']?>',hf,'<?=$website['lose']?>');
	})
	$(document).on('click','#confirm-dialog .btn-confirm-dialog-ok',function(e){
		var api = $(this).attr('api');
		window.location.href = api;
		$("#confirm-dialog").modal("hide");
	})
	$('.show-detail').click(function(event) {
		var val = $(this).attr('id_item');
		$('.box-detail-'+val).toggle('fast');
	});
	// $('.detail-booking').click(function(event) {
	// 	var booking_id = $(this).attr('booking_id');
	// 	var p ={};
	// 	p['booking_id']		= booking_id;

	// 	$.ajax({
	// 		type: 'POST',
	// 		url: '<?=site_url('tai-khoan/chi-tiet-mua-hang')?>',
	// 		data: p,
	// 		dataType: 'html',
	// 		success: function(result) {
	// 			$('.review').html(result);
	// 		}
	// 	});
	// });
</script>
