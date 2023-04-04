<div class="cluster">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">
				Affiliate
			</h1>
		</div>
		<? if (empty($items) || !sizeof($items)) { ?>
		<p class="help-block">Not found item.</p>
		<? } else { ?>
		<form id="frm-admin" name="adminForm" action="" method="POST">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="boxchecked" name="boxchecked" value="0" />
			<table class="table table-bordered">
				<tr>
					<th class="text-center" width="30px">#</th>
					<th class="text-center" width="30px">
						<input type="checkbox" id="toggle" name="toggle" onclick="checkAll('<?=sizeof($items)?>');" />
					</th>
					<th>Key</th>
					<th width="180px">Lượt tiếp cận</th>
					<th width="180px">Tổng đơn hàng</th>
					<th width="180px">Số đơn hoàn thành</th>
					<th width="180px">Đơn hoàn thành chưa thanh</th>
					<th width="180px">Người giới thiệu</th>
				</tr>
				<?
					$i = 0;
					foreach ($items as $item) {
						$info = new stdClass();
						$info->affiliate_code = $item->affiliate_code;
						$affiliates = $this->m_affiliate->items($info);

						$info->payment_status = 2;
						$payment_success = $this->m_affiliate->jion_booking_items($info);

						$info->a_payment_status = 1;
						$payment_affiliates = $this->m_affiliate->jion_count_booking_items($info);
						$c_payment_affiliates = count($payment_affiliates);
				?>
				<tr class="row1">
					<td class="text-center"><?=($i+1)?></td>
					<td class="text-center">
						<input type="checkbox" id="cb<?=$i?>" name="cid[]" value="<?=$item->id?>" onclick="isChecked(this.checked);">
					</td>
					<td>
						<a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/edit/{$item->id}")?>"><?=$item->affiliate_code?></a>
						<ul class="action-icon-list">
							<li><a href="<?=site_url("syslog/{$this->util->slug($this->router->fetch_method())}/edit/{$item->id}")?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem chi tiết</a></li>
						</ul>
					</td>
					<td>
						<?=$item->approach?>
					</td>
					<td>
						<?=count($affiliates)?>
					</td>
					<td>
						<?=count($payment_success)?>
					</td>
					<td <?=!empty($c_payment_affiliates)?'style="color:red;"':''?>>
						<?=number_format($payment_affiliates[0]->amount,0,',','.').'/'.$c_payment_affiliates.'đơn'?>
					</td>
					<td>
						<?
							$updated_by = $this->m_user->load($item->user_id);
							if (!empty($updated_by)) {
						?>
						<strong><?=$updated_by->fullname?></strong>
						<div class="action-icon-list"><span class="text-color-grey"><?=date("Y-m-d H:i:s", strtotime($item->created_date))?></span></div>
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
		<? } ?>
		<div class="col-md-12 text-center"><?=$pagination?></div>
	</div>
</div>

<script>
$(document).ready(function() {
	jQuery.noConflict();
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
});
</script>