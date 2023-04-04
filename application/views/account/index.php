<div class="container">
	<div class="page-title">
		<h2>Đơn đặt hàng</h2>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="page-title">
				<h3 class="text-color-red">Danh sách đơn hàng</h3>
			</div>
			<div class="booked">
				<table class="property-sale table table-bordered">
					<thead>
						<tr>
							<th width="5%">Stt</th>
							<th>Mã đơn hàng</th>
							<th>Số lượng</th>
							<th>Tổng tiền</th>
						</tr>
					</thead>
					<tbody>
						<?// $i=0; foreach ($bookings as $booking) {  ?>
						<tr>
							<td class="text-center"><?//=$i+1?></td>
							<td class="text-center"><a style="cursor: pointer;" class="detail-booking" booking_id="<?//=$booking->id?>">CFO<?//=$booking->id?></a></td>
							<td class="text-center"><?//=$booking->qty?></td>
							<td class="text-center"><?//=number_format($booking->total)?>₫</td>
						</tr>
						<?// $i++;} ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-6">
			<div class="page-title">
				<h3 class="text-color-red">Chi tiết đơn hàng</h3>
			</div>
			<div class="review"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.detail-booking').click(function(event) {
		var booking_id = $(this).attr('booking_id');
		var p ={};
		p['booking_id']		= booking_id;

		$.ajax({
			type: 'POST',
			url: '<?=site_url('tai-khoan/chi-tiet-mua-hang')?>',
			data: p,
			dataType: 'html',
			success: function(result) {
				$('.review').html(result);
			}
		});
	});
</script>
