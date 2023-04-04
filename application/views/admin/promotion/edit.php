<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title">Tạo mã giảm giá</h1>
		<form id="frm-admin" name="adminForm" action="" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="fromdate" name="fromdate" value="<?=!empty($item->fromdate)?date('Y-m-d',strtotime($item->fromdate)):date('Y-m-d')?>" />
			<input type="hidden" id="todate" name="todate" value="<?=!empty($item->todate)?date('Y-m-d',strtotime($item->todate)):date('Y-m-d')?>" />
			<table class="table table-bordered">
				<tr>
					<td class="table-head text-right" width="10%">Mã</td>
					<td><input type="text" id="code" name="code" class="form-control" value="<?=!empty($item->code) ? $item->code : ''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right">Giá trị</td>
					<td>
						<div class="row">
							<div class="col-md-4">
								<div class="input-group">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="sale-title"><?=!empty($item->sale_type) ? 'Giảm tiền' : 'Giảm %'?></span> <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a class="sale-type" value="0" href="#">Giảm %</a></li>
											<li><a class="sale-type" value="1" href="#">Giảm tiền</a></li>
										</ul>
									</div>
									<input type="hidden" id="sale_type" name="sale_type" value="<?=!empty($item->sale_type) ? $item->sale_type : '0'?>">
									<input type="text" id="sale_value" name="sale_value" class="form-control" value="<?=!empty($item->sale_value) ? $item->sale_value : '0'?>">
								</div>
								<script>
									$('.sale-type').click(function(e){
										let val = $(this).attr('value');
										let hml = $(this).html();
										$('.sale-title').html(hml);
										$('#sale_type').val(val);
										if (val == '0') {
											$('.money-limit').css('display','table-row');
											$('#money_limit').val(0);
										} else {
											$('.money-limit').css('display','none');
											$('#money_limit').val(-1);
										}
									})
								</script>
							</div>
							<div class="col-md-4">
								<div class="input-group">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button">Đơn hàng tối thiểu</button>
									</span>
									<input type="text" id="bill_money" name="bill_money" class="form-control" value="<?=!empty($item->bill_money) ? $item->bill_money : ''?>">
								</div><!-- /input-group -->
							</div>
							<div class="col-md-4">
								<div class="input-group money-limit" <?=empty($item->sale_type)?'':'style="display:none;"'?> >
									<span class="input-group-btn">
										<button class="btn btn-default" type="button">Số tiền tối đa</button>
									</span>
									<input type="text" id="money_limit" name="money_limit" class="form-control" value="<?=!empty($item->money_limit) ? $item->money_limit : ''?>">
								</div><!-- /input-group -->
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="table-head text-right">Dành cho thành viên</td>
					<td>
						<select id="user_rank" name="user_rank" class="form-control">
							<option value="0">Mới</option>
							<option value="1">Bạc</option>
							<option value="2">Vàng</option>
							<option value="3">Bạch kim</option>
							<option value="4">Kim Cương</option>
						</select>
						<script type="text/javascript">
							$("#user_rank").val("<?=!empty($item->user_rank) ? $item->user_rank : 0 ?>");
						</script>
					</td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Thời hạn</td>
					<td>
						<div class="row">
							<div class="col-md-4">
								<select id="limit_time" name="limit_time" class="form-control">
									<option value="0">Không thời hạn</option>
									<option value="1">Có thời hạn</option>
								</select>
								<script type="text/javascript">
									$("#limit_time").val("<?=!empty($item->limit_time) ? $item->limit_time : 0 ?>");
									$('#limit_time').change(function (e) {
										if($(this).val() == 0){
											$('.limit-time').css('display','none');
										} else {
											$('.limit-time').css('display','block');
										}
									});
								</script>
							</div>
							<div class="col-md-8">
								<div class="limit-time" <?=empty($item->limit_time)?'style="display:none;"':''?>>
									<input type="text" class="form-control daterange">
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="table-head text-right">Trạng thái</td>
					<td>
						<select id="active" name="active" class="form-control">
							<option value="1">Kích hoạt</option>
							<option value="0">Vô hiệu hóa</option>
						</select>
						<script type="text/javascript">
							$("#active").val("<?=!empty($item->active) ? $item->active : 1 ?>");
						</script>
					</td>
				</tr>
			</table>
			<div class="pull-right">
				<button class="btn btn-sm btn-primary btn-save">Cập nhật</button>
				<button class="btn btn-sm btn-default btn-cancel">Hủy bỏ</button>
			</div>
		</form>
	</div>
</div>

<script>
$(document).ready(function() {
	jQuery.noConflict();
	if ($(".daterange").length) {
		$(".daterange").daterangepicker({
			startDate: "<?=date('m/d/Y', strtotime((!empty($item->fromdate)?$item->fromdate:"now")))?>",
			endDate: "<?=date('m/d/Y', strtotime((!empty($item->todate)?$item->todate:"now")))?>"
		});
	}
	$(document).on('click','.applyBtn',function(e){
		if ($(".daterange").length) {
			$("#fromdate").val($(".daterange").data("daterangepicker").startDate.format('YYYY-MM-DD'));
			$("#todate").val($(".daterange").data("daterangepicker").endDate.format('YYYY-MM-DD'));
		}
	})
	$(".btn-cancel").click(function(){
		submitButton("cancel");
	});
	$(".btn-save").click(function(){
		submitButton("save");
	});
});
</script>
</script>