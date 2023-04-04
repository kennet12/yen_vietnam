<div class="booked">
	<h4>Thông tin người đặt hàng</h4>
	<div style="display: table;width: 100%" >
		<div style="display: table-cell;width: 20%;font-size: 14px;font-weight: bold;">Họ và tên:</div>
		<div style="display: table-cell;width: 80%;font-size: 14px;"><?=$booking->fullname?></div>
	</div>
	<div style="display: table;width: 100%" >
		<div style="display: table-cell;width: 20%;font-size: 14px;font-weight: bold;">Số điện thoại:</div>
		<div style="display: table-cell;width: 80%;font-size: 14px;"><?=$booking->phone?></div>
	</div>
	<div style="display: table;width: 100%" >
		<div style="display: table-cell;width: 20%;font-size: 14px;font-weight: bold;">Email:</div>
		<div style="display: table-cell;width: 80%;font-size: 14px;"><?=$booking->email?></div>
	</div>
	<div style="display: table;width: 100%" >
		<div style="display: table-cell;width: 20%;font-size: 14px;font-weight: bold;">Địa chỉ:</div>
		<div style="display: table-cell;width: 80%;font-size: 14px;"><?=$booking->address?></div>
	</div>
	<h4 style="margin-top: 15px;">Thông tin sản phẩm</h4>
	<table class="property-sale table table-bordered">
		<thead>
			<tr>
				<th>Tên sản phẩm</th>
				<th>Giá</th>
				<th>Size</th>
				<th>Số lượng</th>
				<th>Thành tiền</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($booking_details as $booking_detail) {
				$sizes = explode(',', $booking_detail->opt_size);
				$size_qtys = explode(',', $booking_detail->opt_size_qty);
			?>
			<tr>
				<td class="text-center"><?=$booking_detail->name?></td>
				<td class="text-center">
					<? if(!empty($booking_detail->price)) { ?>
					<div style="text-decoration: line-through;color: #7d7d7d;"><?=number_format($booking_detail->price)?>₫</div>
					<? } ?>
					<div style="color: red;"><?=number_format($booking_detail->price_sale)?>₫</div>
				</td>
				<td class="text-center">
					<? foreach ($sizes as $size) { ?>
					<div style="background: #f1f1f1;margin: 2px 0;text-transform: uppercase;"><?=$size?></div>
					<? } ?>
				</td>
				<td class="text-center">
					<? foreach ($size_qtys as $size_qty) { ?>
					<div style="background: #f1f1f1;margin: 2px 0;"><?=$size_qty?></div>
					<? } ?>
				</td>
				<td class="text-center">
					<? foreach ($size_qtys as $size_qty) { ?>
					<div style="background: #f1f1f1;margin: 2px 0;"><?=number_format($booking_detail->price_sale*$size_qty)?>₫</div>
					<? } ?>
				</td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>
