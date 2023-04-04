<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<? $admin = $this->session->admin;?>
<div class="cluster statistic">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">
				<div class="pull-left" style="max-width: 345px;">
					Kho hàng<br><br>
					<div class="input-group">
						<input type="text" id="search-box" class="form-control" placeholder="Nhập tên sản phẩm" value="<?=$search_box?>">
						<span class="input-group-btn">
							<button class="btn btn-default btn-search-box" type="button">Tìm kiếm</button>
						</span>
					</div>
				</div>
				<div class="pull-right">
					<div class="clearfix">
						<div class="pull-left" style="max-width: 220px;">
							<div class="input-group input-group-sm">
								<input type="text" class="form-control daterange">
								<span class="input-group-btn">
									<button class="btn btn-default btn-report" type="button">Nhập</button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</h1>
		</div>
		<script>
			$('.btn-search-box').click(function (e){
				var search_box = $('#search-box').val();
				window.location.href = '<?=BASE_URL?>'+'/syslog/enter-box/0.html?search_box='+search_box;
			})
		</script>
		<!-- <br>
		<div class="text-center">
			<div class="btn-group">
				<a href="<?=site_url("syslog/enter-box/{$category}").'?type=week'?>" class="btn btn-info">Tuần</a>
				<a href="<?=site_url("syslog/enter-box/{$category}").'?type=month'?>" class="btn btn-info">Tháng</a>
				<a href="<?=site_url("syslog/enter-box/{$category}").'?type=year'?>" class="btn btn-info">Năm</a>
			</div>
		</div> -->
		<br>
		<div class="container">
			<div class="row">
				<div class="col-md-4"><div class="alert alert-success" role="alert">Số lượng: <?=$total_qty?></div></div>
				<div class="col-md-4"></div>
				<div class="col-md-4"><div class="alert alert-danger" role="alert">Giá vốn: <?=number_format($total_cost,0,',','.')?><sup>đ</sup></div></div>
			</div>
		</div>
	</div>
</div>
<div class="cluster">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">
				<div class="pull-right">
					<ul class="action-icon-list">
						<li><a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/{$category}/add")?>"><i class="fa fa-plus" aria-hidden="true"></i> Nhập kho</a></li>
					</ul>
				</div>
			</h1>
		</div>
		<form id="frm-admin" name="adminForm" action="" method="GET">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="fromdate" name="fromdate" value="<?=$fromdate?>" />
			<input type="hidden" id="todate" name="todate" value="<?=$todate?>" />
			<input type="hidden" id="boxchecked" name="boxchecked" value="0" />
			<table class="table table-bordered">
				<tr>
					<th class="text-center" width="30px">#</th>
					<th class="text-center" width="30px">
						<input type="checkbox" id="toggle" name="toggle" onclick="checkAll('<?=sizeof($items)?>');" />
					</th>
					<th>Tên sản phẩm</th>
					<th>Chi tiết</th>
					<th width="180px">Cập nhật</th>
				</tr>
				<?
					$i = 0;
					foreach ($items as $item) {
				?>
				<tr class="row1">
					<td class="text-center"><?=($i+1)?></td>
					<td class="text-center">
						<input type="checkbox" id="cb<?=$i?>" name="cid[]" value="<?=$item->id?>" onclick="isChecked(this.checked);">
					</td>
					<td>
						<a href="#"><?=$item->title?></a>
						<ul class="action-icon-list">
							<!-- <li><a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/{$category}/edit/{$item->id}")?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Cập nhật</a></li> -->
							<li><a class="delete-item" style="cursor:pointer;" ref="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/{$category}/delete/{$item->id}")?>"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
						</ul>
					</td>
					<td>
						<table class="table table-bordered">
							<tr>
								<?if($item->typename !="") {?>
								<th width="150px"><?=$item->typename?></th>
								<? } ?>
								<?if($item->subtypename !="") {?>
								<th width="150px"><?=$item->subtypename?></th>
								<? } ?>
								<th width="120px">SL (Bán)</th>
								<th width="120px">SL (Tồn)</th>
								<th width="120px">SL (Nhập)</th>
								<th width="100px">Bán</th>
								<th width="100px">Vốn</th>
							</tr>
							<?=$arr_box[$item->id]?>
						</table>
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
	$(".delete-item").click(function(e) {
		var cf = confirm("Are you sure!");
		if (cf) {
			var h = $(this).attr("ref");
			window.location.href = h;
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
	$(".btn-unpublish").click(function(e){
		e.preventDefault();
		if ($("#boxchecked").val() == 0) {
			messageBox("ERROR", "Error", "Please make a selection from the list to unpublish.");
		}
		else {
			submitButton("unread");
		}
	});
	$(".btn-delete").click(function(e){
		e.preventDefault();
		if ($("#boxchecked").val() == 0) {
			messageBox("ERROR", "Error", "Please make a selection from the list to delete.");
		}
		else {
			confirmBox("Delete items", "Are you sure you want to delete the selected items?", "submitButton", "delete");
		}
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
	$(".btn-report").click(function(){  console.log(123);
		if ($(".daterange").length) {
			$("#fromdate").val($(".daterange").data("daterangepicker").startDate.format('YYYY-MM-DD'));
			$("#todate").val($(".daterange").data("daterangepicker").endDate.format('YYYY-MM-DD'));
		}
		submitButton('search');
	});
});
</script>