<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<? $admin = $this->session->admin;?>
<div class="cluster">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">
				Đơn hàng (<?=$affiliate_item->affiliate_code?>)
				<div class="pull-right">
					<div class="clearfix">
						<div class="pull-left" style="margin-right: 5px;">
							<div class="input-group input-group-sm">
								<input type="text" id="report_text" name="report_text" class="form-control" value="<?=$search_text?>" placeholder="Search for (Booking id, Email, Fullname, Phone)"  style="width: 300px;">
							</div>
						</div>
						<? if ($admin->user_type == USR_ADMIN || $admin->user_type == USR_SUPPER_ADMIN) { ?>
						<div class="pull-left" style="max-width: 220px;">
							<div class="input-group input-group-sm">
								<input type="text" class="form-control daterange">
								<span class="input-group-btn">
									<button class="btn btn-default btn-report" type="button">Gửi</button>
								</span>
							</div>
						</div>
						<? } else { ?>
						<div class="pull-left">
							<button class="btn btn-sm btn-default btn-report" type="button">Gửi</button>
						</div>
						<? } ?>
					</div>
				</div>
			</h1>
		</div>
		<br>
		<form id="frm-admin" name="adminForm" action="" method="GET">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="boxchecked" name="boxchecked" value="0" />
			<input type="hidden" id="search_text" name="search_text" value="<?=$search_text?>">
			<input type="hidden" id="fromdate" name="fromdate" value="<?=$fromdate?>" />
			<input type="hidden" id="todate" name="todate" value="<?=$todate?>" />
			<table class="table table-bordered">
				<tr>
					<th class="text-center" width="30px">#</th>
					<th class="text-center" width="30px">
						<input type="checkbox" id="toggle" name="toggle" onclick="checkAll('<?=sizeof($items)?>');" />
					</th>
					<th width="100px">Booking ID</th>
					<th>Họ Tên</th>
					<th>Email</th>
					<th>Điện thoại</th>
					<th>Tổng tiền</th>
					<th>Huê hồng</th>
					<th class="text-center" width="120px">Trạng thái đơn</th>
					<th class="text-center" width="120px">Trạng thái huê hồng</th>
					<th width="180px">Cập nhật</th>
				</tr>
				<?
					$i = 0;
					foreach ($items as $item) {
				?>
				<? $str=''; $total_affiliate=0; $total=0; foreach ($item->detail as $value) { 
					$photo = explode('/',$value->thumbnail);
					$product = $this->m_product->load($value->product_id);
					$price_sale = $value->price_sale;
					if (!empty($item->code_promotion)) {
						$price_sale = $value->price;
					}
					$affiliate = (($value->qty*$value->price_sale)*$product->affiliate)/100;
					$total_affiliate += $affiliate;
					$total += $value->qty*$price_sale;
					$typename = $value->typename;
					$typename .= !empty($value->subtypename)?" - ".$value->subtypename:"";
					$str .= '<div class="item">';
					$str .= '<div class="item-child" style="width:20% !important;">';
					$str .= '<img src="'.BASE_URL."/files/upload/product/{$product->code}/thumb/".end($photo).'" alt="">';
					$str .= '</div>';
					$str .= '<div class="item-child" style="width:30% !important;">';
					$str .= '<h5 class="name">'.$value->title.'</h5>';
					$str .= '<div class="sku">'.$typename.'</div>';
					$str .= '<div class="price">'.number_format($price_sale,0,',','.').' (1 sp)</div>';
					$str .= '</div>';
					$str .= '<div class="item-child" style="width:10% !important;">'.$value->qty.'</div>';
					$str .= '<div class="item-child" style="width:20% !important;">';
					$str .= '<div class="total-price">'.number_format($value->qty*$price_sale,0,',','.').'</div>';
					$str .= '</div>';
					$str .= '<div class="item-child" style="width:20% !important;">';
					$str .= '<h5 style="font-size: 14px;">'.$product->affiliate.'% ('.number_format($affiliate,0,',','.').')</h5>';
					$str .= '</div>';
					$str .= '</div>';
				} ?>

				<tr class="row1">
					<td class="text-center"><?=($i+1)?></td>
					<td class="text-center">
						<input type="checkbox" id="cb<?=$i?>" name="cid[]" value="<?=$item->id?>" onclick="isChecked(this.checked);">
					</td>
					<td>
						<a book_id="<?=$item->id?>" class="booking-id" style="cursor: pointer;"><?=BOOKING_PREFIX.$item->id?></a>
					</td>
					<td class="">
						<?=$item->fullname?>
					</td>
					<td class="">
						<?=$item->email?>
					</td>
					<td class="">
						<?=$item->phone?>
					</td>
					<td class="">
						<?=number_format($item->total,0,',','.')?>
					</td>
					<td class="cost-price-<?=$item->id?>">
						<?=number_format($total_affiliate,0,',','.')?>
					</td>
					<td class="text-center">
						<div class="btn-group btn-processing-status">
							<a class="btn btn-xs dropdown-toggle dropdown-toggle-payment-status-<?=$item->id?>" status="<?=$item->payment_status?>" data-toggle="dropdown">
								<? if ($item->payment_status == 2) { ?>
								<span class="label label-success">Đã thanh toán</span> <i class="fa fa-caret-down"></i>
								<? } else if ($item->payment_status == 1) { ?>
								<span class="label label-danger">Chưa thanh toán</span> <i class="fa fa-caret-down"></i>
								<? } ?>
							</a>
						</div>
					</td>
					<td class="text-center">
						<div class="btn-group btn-processing-status">
							<a class="btn btn-xs dropdown-toggle dropdown-toggle-status-<?=$item->id?>" status="<?=$item->status?>" data-toggle="dropdown">
								<? if ($item->a_payment_status == 1) { ?>
								<span class="label label-danger">Chưa thanh toán</span> <i class="fa fa-caret-down"></i>
								<? } else if ($item->a_payment_status == 2) { ?>
								<span class="label label-success">Đã thanh toán</span> <i class="fa fa-caret-down"></i>
								<? } ?>
							</a>
						</div>
					</td>
					<td>
						<?
							$updated_by = $this->m_user->load($item->updated_by);
							if (!empty($updated_by)) {
						?>
						<strong><?=$updated_by->fullname?></strong>
						<div class="action-icon-list"><span class="text-color-grey"><?=date("Y-m-d H:i:s", strtotime($item->updated_date))?></span></div>
						<?
							}
						?>
					</td>
				</tr>
				<tr>
					<td colspan="14" class="booking-detail booking-detail-<?=$item->id?>">
						<div class="row">
							<div class="col-md-7">
								<div class="list">
									<h4>A. Cart shop</h4>
									<div class="item">
										<div class="item-child" style="width:20% !important;">
											<div class="head-table">Ảnh</div>
										</div>
										<div class="item-child" style="width:30% !important;">
											<div class="head-table">Chi tiết</div>
										</div>
										<div class="item-child" style="width:10% !important;">
											<div class="head-table">Số lượng</div>
										</div>
										<div class="item-child" style="width:20% !important;">
											<div class="head-table">Tổng tiền</div>
										</div>
										<div class="item-child" style="width:20% !important;">
											<div class="head-table">Huê hồng</div>
										</div>
									</div>
									<?=$str?>
									<div class="item">
										<div class="item-child">
											<h5 style="font-size: 16px;font-weight: 400;margin: 7px;">Thành tiền</h5>
										</div>
										<div class="item-child"></div>
										<div class="item-child"></div>
										<div class="item-child">
											<h5 id="total_price" total="<?=$item->total?>" style="font-size: 16px;color: #FF5722;font-weight: 400;margin: 7px;"><?=number_format($item->total+$item->ship_money,0,',','.')?></h5>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="list">
									<h4>B. Information Customer</h4>
									<div class="info-booking">
										<table width="100%">
											<tr class="item-info">
												<td width="100px">Họ tên:</td>
												<td><input class="info-customer" disabled id_item="<?=$item->id?>" prop="fullname" type="text" value="<?=$item->fullname?>"></td>
											</tr>
											<tr class="item-info">
												<td width="100px">Email:</td>
												<td><input class="info-customer" disabled id_item="<?=$item->id?>" prop="email" type="text" value="<?=$item->email?>"></td>
											</tr>
											<tr class="item-info">
												<td width="100px">Điện thoại:</td>
												<td><input class="info-customer" disabled id_item="<?=$item->id?>" prop="phone" type="text" value="<?=$item->phone?>"></td>
											</tr>
											<tr class="item-info">
												<td width="100px">Địa chỉ:</td>
												<td><input class="info-customer" disabled id_item="<?=$item->id?>" prop="address" type="text" value="<?=$item->address?>"></td>
											</tr>
											<tr class="item-info">
												<td width="100px" style="vertical-align: top;">Tin nhắn:</td>
												<td><textarea class="info-customer" disabled id_item="<?=$item->id?>" prop="message" name="" rows="5"><?=$item->message?></textarea></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<?
						$i++;
					}
				?>
			</table>
		</form>
		<div class="col-md-12 text-center"><?=$pagination?></div>
	</div>
</div>

<script>
	$(document).on("click",".click-edit",function() {
		var id_item = $(this).attr("id_item");
		var prop = $(this).attr("prop");
		var val = $(this).val();
		
		var p = {};
		p["id_item"] = id_item;
		p["prop"] = prop;
		p["val"] = val;
		
		$.ajax({
			type: "POST",
			url: "<?=site_url("syslog/ajax-info-book")?>",
			data: p,
		});
	});
	$(document).on('change','.ship_money', function () {
		var total = $('#total_price').attr('total');
		var val = $(this).val();
		$('#ship_cost').html(formatDollar(parseFloat(val)));
		$('#total_price').html(formatDollar(parseFloat(val)+parseFloat(total)));
	})
	$(document).on("change",".edit",function() {
		var id_item = $(this).attr("id_item");
		var prop = $(this).attr("prop");
		var val = $(this).val();
		
		var p = {};
		p["id_item"] = id_item;
		p["prop"] = prop;
		p["val"] = val;
		
		$.ajax({
			type: "POST",
			url: "<?=site_url("syslog/ajax-info-book")?>",
			data: p,
		});
	});
$(document).ready(function() {
	jQuery.noConflict();
	$('.btn-edit').click(function(event) {
		var stt = $(this).attr('stt');
		if (stt == 'off') {
			$('.info-booking .info-customer').addClass('edit');
			$('.info-booking .info-customer').prop("disabled", false);
			$('.info-booking .payment-edit').addClass('click-edit');
			$(this).attr('stt','on');
			$(this).html('Cập nhật');
		} else {
			$('.info-booking .info-customer').removeClass('edit');
			$('.info-booking .info-customer').prop("disabled", true);
			$('.info-booking .payment-edit').removeClass('click-edit');
			$(this).attr('stt','off');
			$(this).html('Chỉnh sửa');
		}
		
	});
	$(".btn-publish").click(function(e){
		e.preventDefault();
		if ($("#boxchecked").val() == 0) {
			messageBox("ERROR", "Error", "Please make a selection from the list to publish.");
		}
		else {
			submitButton("read");
		}
	});
	$(".payment-status").click(function() {
		var booking_id = $(this).attr("booking-id");
		var status = $('.dropdown-toggle-payment-status-'+booking_id).attr('status');
		var status_id = $(this).attr("status-id");
		var status_label = $(this).html();
		if (status != status_id) {
			$('.dropdown-toggle-payment-status-'+booking_id).attr('status',status_id);
			if (status_id == 2) {
				$('.btn-cancel-order-'+booking_id).hide();
				$('.btn-edit-'+booking_id).hide();
			} else {
				$('.btn-cancel-order-'+booking_id).show();
				$('.btn-edit-'+booking_id).show();
			}
			var p = {};
			p["booking_id"] = booking_id;
			p["status_id"] = parseInt(status_id);
			
			$.ajax({
				type: "POST",
				url: "<?=site_url("syslog/ajax-payment-status")?>",
				dataType: "json",
				data: p,
				success: function(result) {
					$('.cost-price-'+booking_id).html(formatDollar(parseFloat(result)));
					$(".dropdown-toggle-payment-status-" + booking_id).html(status_label + " <i class=\"fa fa-caret-down\"></i>");
				}
			});
		}
	});
	$(".status").click(function() {
		var booking_id = $(this).attr("booking-id");
		var status = $('.dropdown-toggle-status-'+booking_id).attr('status');
		var status_id = $(this).attr("status-id");
		var status_label = $(this).html();
		if (status != status_id) {
			$('.dropdown-toggle-status-'+booking_id).attr('status',status_id);
			var p = {};
			p["booking_id"] = booking_id;
			p["status_id"] = parseInt(status_id);

			$.ajax({
				type: "POST",
				url: "<?=site_url("syslog/ajax-booking-status")?>",
				dataType: "json",
				data: p,
				success: function(result) {
					$(".dropdown-toggle-status-" + booking_id).html(status_label + " <i class=\"fa fa-caret-down\"></i>");
				}
			});
		}
	});
	$('.booking-id').click(function(event) {
		var book_id = $(this).attr("book_id");
		$('.booking-detail-'+book_id).toggle('fast');
		$(this).parents('.row0').addClass('row1');
		$(this).parents('.row0').removeClass('row0');
	});
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
		$("#search_text").val($("#report_text").val());
		if ($(".daterange").length) {
			$("#fromdate").val($(".daterange").data("daterangepicker").startDate.format('YYYY-MM-DD'));
			$("#todate").val($(".daterange").data("daterangepicker").endDate.format('YYYY-MM-DD'));
		}
		submitButton("search");
	});
	$(document).on('click','.btn-cancel-order', function(e) {
		var cf = confirm('Có chắc muốn hủy đơn này');
		if (cf) {
			window.location.href = $(this).attr('ref');
		}
	})
});
</script>