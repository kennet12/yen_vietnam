<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<? $admin = $this->session->admin;?>
<div class="cluster">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">
				Đơn hàng được tạo
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
			<ul style="margin:0;padding:0;">
				<li style="padding:0;list-style:none;"><a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/add")?>"><i class="fa fa-plus" aria-hidden="true"></i> Tạo đơn hàng</a></li>
			</ul>
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
					<th>Họ Tên</th>
					<th>Tổng đơn tạo</th>
					<th>Tổng tiền bán</th>
					<th>Tồng vốn</th>
                    <th width="100px">Chi tiết</th>
				</tr>
				<?
					$i = 0;
					foreach ($users as $user) {
				?>
				<tr class="row1">
					<td class="text-center"><?=($i+1)?></td>
					<td class="">
						<?=$user->fullname?>
					</td>
					<td class="">
						<?=$user->booking[0]->total_book?>
					</td>
					<td class="">
						<?=!empty($user->booking[0]->total)?number_format($user->booking[0]->total,0,',','.'):0?>
					</td>
					<td class="">
						<?=!empty($user->booking[0]->cost)?number_format($user->booking[0]->cost,0,',','.'):0?>
					</td>
                    <td width="100px">
						<a href="<?=site_url("syslog/booking/view-booking-detail/{$user->id}")."?fromdate={$fromdate}&todate={$todate}"?>">Xem chi tiết</a>
					</td>
				</tr>
				<?
						$i++;
					}
				?>
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