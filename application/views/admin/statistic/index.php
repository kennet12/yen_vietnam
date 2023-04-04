<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<? $admin = $this->session->admin;?>
<div class="cluster statistic">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">
				Statistics
				<div class="pull-right">
					<div class="clearfix">
						<div class="pull-left" style="max-width: 220px;">
							<div class="input-group input-group-sm">
								<input type="text" class="form-control daterange">
								<span class="input-group-btn">
									<button class="btn btn-default btn-report" type="button">Report</button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</h1>
		</div>
		<br>
		<div class="text-center">
			<div class="btn-group">
				<a href="<?=site_url("syslog/statistic").'?type=week'?>" class="btn btn-info">Tuần</a>
				<a href="<?=site_url("syslog/statistic").'?type=month'?>" class="btn btn-info">Tháng</a>
				<a href="<?=site_url("syslog/statistic").'?type=year'?>" class="btn btn-info">Năm</a>
			</div>
		</div>
		<br>
		<?
			$i = 0;
			$str = '';
			$total_qty = 0;
			$total_price = 0;
			$total_cost = 0;
			foreach ($items as $item) {
				$str .= '<tr class="row1">';
				$str .= '<td class="text-center">'.($i+1).'</td>';
				$str .= '<td>';
				$str .= '<a href="#">'.$item->fullname.'</a>';
				$str .= '</td>';
				$str .= '<td>';
				$str .= '<table class="table table-bordered">';
				$str .= '<tr>';
				$str .= '<th width="100px">Loại</th>';
				$str .= '<th width="60px">SL (Bán)</th>';
				$str .= '<th width="60px">Trợ giá/1sp</th>';
				$str .= '<th width="60px">Tiền bán/1sp</th>';
					$str .= '</tr>';
							$qty=0; $total = 0;foreach ($item->detail as $detail) { 
							$total += $detail->qty*($detail->price_sale - $detail->discount); 
							$qty += $detail->qty;
							$discount = !empty($detail->discount)?'-'.number_format($detail->discount,0,',','.'):$detail->discount;
							$subtypename = !empty($detail->subtypename)?' -> '.$detail->subtypename:'';
							$str .= '<tr>';
								$str .= '<td width="100px">'.$detail->typename.$subtypename.'</td>';
								$str .= '<td width="60px">'.$detail->qty.'</td>';
								$str .= '<td width="60px">'.$discount.'</td>';
								$str .= '<td width="60px">'.number_format($detail->price_sale,0,',','.').'</td>';
							$str .= '</tr>';
							}
					$str .= '</table>';
				$str .= '</td>';
				$str .= '<td class="text-center">'.$qty.'</td>';
				$str .= '<td class="text-center">'.number_format($total,0,',','.').'</td>';
				$str .= '<td class="text-center">'.number_format($item->total-$total,0,',','.').'</td>';
				$str .= '<td class="text-center"><strong>'.number_format($item->total,0,',','.').'</strong></td>';
				$str .= '<td>';
							$updated_by = $this->m_user->load($item->updated_by);
							if (!empty($updated_by)) {
								$str .= '<strong>'.$updated_by->fullname.'</strong>';
								$str .= '<div class="action-icon-list"><span class="text-color-grey">'.date("Y-m-d H:i:s", strtotime($item->paid_date)).'</span></div>';
							}
				$str .= '</td>';
				$str .= '</tr>';
				$i++;
				$total_qty += $qty;
				$total_price += $item->total;
				$total_cost += $item->cost;
			}
		?>
		<form id="frm-admin" name="adminForm" action="" method="GET">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="fromdate" name="fromdate" value="<?=$fromdate?>" />
			<input type="hidden" id="todate" name="todate" value="<?=$todate?>" />
			<div class="row">
				<div class="col-md-12-5"><label for="">Tổng số lượng:</label><div class="alert alert-info" role="alert"><?=$total_qty?></div></div>
				<div class="col-md-12-5"><label for="">Tổng Doanh thu:</label><div class="alert alert-info" role="alert"><?=number_format($total_price,0,',','.')?><sup>đ</sup></div></div>
				<div class="col-md-12-5"><label for="">Tổng Vốn:</label><div class="alert alert-info" role="alert"><?=number_format($total_cost,0,',','.')?><sup>đ</sup></div></div>
				<div class="col-md-12-5"><label for="">Lợi nhuận: </label><div class="alert alert-success" role="alert"><?=number_format($total_price-$total_cost,0,',','.')?><sup>đ</sup></div></div>
			</div>
			<table class="table table-bordered">
				<tr>
					<th class="text-center" width="30px">#</th>
					<th>Khách hàng</th>
					<th>Chi tiết</th>
					<th class="text-center" width="80px">Số lượng</th>
					<th class="text-center" width="80px">Tổng bán</th>
					<th class="text-center" width="80px">Giảm giá</th>
					<th class="text-center" width="80px">Doanh thu</th>
					<th width="180px">Người duyệt</th>
				</tr>
				<?=$str;?>
			</table>
		</form>
	</div>
</div>
<script>
$(document).ready(function() {
	jQuery.noConflict();
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
});
</script>