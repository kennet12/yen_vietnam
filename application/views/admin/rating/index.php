<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<div class="cluster">
	<div class="container-fluid">
		<div class="tool-bar clearfix">
			<h1 class="page-title">
				Đánh giá khách hàng
				<div class="pull-right">
					<div class="clearfix">
						<div class="pull-left" style="margin-right: 5px;">
							<select style="height: 30px;padding: 4px 10px;font-size: 12px;font-weight: 500;" id="rank-select" class="form-control">
								<option value="">Tất cả</option>
								<option value="1">1 Sao</option>
								<option value="2">2 Sao</option>
								<option value="3">3 Sao</option>
								<option value="4">4 Sao</option>
								<option value="5">5 Sao</option>
							</select>
							<script>
								$('#rank-select').val(<?=$rank?>);
								$('#rank-select').change(function () {
									$('#rank').val($(this).val());
								});
							</script>
						</div>
						<div class="pull-left" style="max-width: 220px;">
							<div class="input-group input-group-sm">
								<input type="text" class="form-control daterange">
								<span class="input-group-btn">
									<button class="btn btn-default btn-report" type="button">Gửi</button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</h1>
		</div>
		<br>
		<form id="frm-admin" name="adminForm" action="" method="GET">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="item_id" name="item_id" value="0" />
			<input type="hidden" id="boxchecked" name="boxchecked" value="0" />
			<input type="hidden" id="rank" name="rank" value="<?=$rank?>">
			<input type="hidden" id="fromdate" name="fromdate" value="<?=$fromdate?>" />
			<input type="hidden" id="todate" name="todate" value="<?=$todate?>" />
			<table class="table table-bordered">
				<tr>
					<th class="text-center" width="30px">#</th>
					<th class="text-center" width="30px">
						<input type="checkbox" id="toggle" name="toggle" onclick="checkAll('<?=sizeof($items)?>');" />
					</th>
					<th width="200px">Sản phẩm</th>
					<th>Đánh giá</th>
				</tr>
				<?
					$i = 0;
					foreach ($items as $item) {
				?>
				<tr class="row1">
					<td class="text-center">
						<?=($i + 1) + (($page - 1) * ADMIN_ROW_PER_PAGE)?>
					</td>
					<td class="text-center">
						<input type="checkbox" id="cb<?=$i?>" name="cid[]" value="<?=$item->id?>" onclick="isChecked(this.checked);">
					</td>
					<td>
						<a target="_blank" href="<?=site_url($item->alias)?>#wrap-rating"><?=$item->title?></a>
					</td>
					<td>
						<div class="box-rating">
							<div><strong><?=$item->name?></strong></div>
							<p style="color:#999;margin:0;font-size:10px;"><?=date('H:i, d/m/Y',strtotime($item->created_date))?></p>
							<div class='rating'>
								<? for($i=1;$i<=5;$i++) {
									if ($i <= $item->point) {
										echo '<i class="fa fa-star" aria-hidden="true"></i>';
									} else {
										echo '<i class="fa fa-star-o" aria-hidden="true"></i>';
									}
								} ?>
							</div>
							<p style="color: green; margin:0;"><?=$item->message?></p>
							<ul class="action-icon-list">
								<li><a style="cursor:pointer" product_id="<?=$item->product_id?>" item_id="<?=$item->id?>" class="get-frm-reply"><i class="fa fa-reply" aria-hidden="true"></i> Trả lời</a></li>
								<li><a style="cursor:pointer" onclick="return deleteItem('<?=$item->id?>', 'Delete item', 'Are you sure you want to delete the selected items?');"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
								<? if ($item->active) { ?>
								<li><a style="cursor:pointer" onclick="return activeItem('<?=$item->id?>','private_item');"><i class="fa fa-eye" aria-hidden="true"></i> Ẩn</a></li>
								<? } else { ?>
								<li><a style="cursor:pointer;color: #f44336;" onclick="return activeItem('<?=$item->id?>','public_item');"><i class="fa fa-eye-slash" aria-hidden="true"></i> Hiện</a></li>
								<? } ?>
							</ul>
							<div class="wrap-reply-mes"></div>
							<?
							$info = new stdClass();
							$info->parent_id = $item->id;
							$child_items = $this->m_rating->items('*',$info);
							foreach ($child_items as $child_item) { ?>
							<div class="box-reply">
								<? if($child_item->user_type == -1) { ?>
								<div><strong style="color:red"><?=COMPANY?></strong> <strong>(<?=$child_item->name?>)</strong></div>
								<? } else { ?>
								<div><strong><?=$child_item->name?></strong></div>
								<? } ?>
								<p style="color:#999;margin:0;font-size:10px;"><?=date('H:i, d/m/Y',strtotime($child_item->created_date))?></p>
								<p style="color: green; margin:0;"><?=$child_item->message?></p>
								<ul class="action-icon-list">
									<li><a style="cursor:pointer" onclick="return deleteItem('<?=$child_item->id?>', 'Delete item', 'Are you sure you want to delete the selected items?');"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a></li>
									<? if ($child_item->active) { ?>
									<li><a style="cursor:pointer" onclick="return activeItem('<?=$child_item->id?>','private_item');"><i class="fa fa-eye" aria-hidden="true"></i> Ẩn</a></li>
									<? } else { ?>
									<li><a style="cursor:pointer;color: #f44336;" onclick="return activeItem('<?=$child_item->id?>','public_item');"><i class="fa fa-eye-slash" aria-hidden="true"></i> Hiện</a></li>
									<? } ?>
								</ul>
							</div>
							<? } ?>
						</div>
					</td>
				</tr>
				<?
						$i++;
					}
				?>
			</table>
			<div><?=$pagination?></div>
		</form>
	</div>
</div>
<script>
	$(document).on('click','.btn-send-reply',function () {
		var item_id = $(this).attr("item-id");
		var p = {};
		p["message"] = $('#reply-message').val();
		p["item_id"] = $('#reply-message').attr('item_id');
		p["product_id"] = $('#reply-message').attr('product_id');
		
		$.ajax({
			type: "POST",
			url: "<?=site_url("syslog/rating-send-reply")?>",
			data: p,
			dataType: "json",
			success: function(result) {
				if(result) {
					location.reload();
				} else {
					alert('Error!!!');
				}
				
			}
		});
	})
	$('.get-frm-reply').click(function () {
		$('.wrap-reply-mes').html('');
		let str = '<div class="frm-reply"><textarea class="form-control" product_id="'+$(this).attr('product_id')+'" item_id="'+$(this).attr('item_id')+'" name="" id="reply-message" rows="3" placeholder="Trả lời tin nhắn tại đây"></textarea><br><button class="btn btn-success btn-send-reply">Send Message</button> <button class="btn btn-default btn-cancel-reply">Cancel</button></div>';
		$(this).parents('.action-icon-list').next('.wrap-reply-mes').html(str);
	})
	$(document).on('click','.btn-cancel-reply',function () {
		$('.wrap-reply-mes').html('');
	})
</script>
<div id="dialog-send-mail" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Compose Email</h4>
			</div>
			<div class="modal-body">
				<table class="table table-bordered">
					<tr>
						<td class="text-right active" width="80px">
							<label class="form-label right">To <span class="required">*</span></label>
						</td>
						<td>
							<input type="text" id="to_receiver" name="to_receiver" value="" class="form-control" required>
						</td>
					</tr>
					<tr>
						<td class="text-right active">
							<label class="form-label right">Subject <span class="required">*</span></label>
						</td>
						<td>
							<input type="text" id="subject" name="subject" value="" class="form-control" required>
						</td>
					</tr>
					<tr>
						<td class="text-right active">
							<label class="form-label right">Message <span class="required">*</span></label>
						</td>
						<td>
							<textarea id="message" name="message" class="form-control tinymce" rows="20" style="height: 300px;" required></textarea>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary btn-send">Send</button>
			</div>
		</div>
	</div>
</div>
<? require_once(APPPATH."views/module/admin/upload_ckfinder.php"); ?>
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
	$(".btn-delete-all").click(function(e){
		e.preventDefault();
		confirmBox("Delete all items", "Are you sure you want to delete all the e-mail in your box?", "submitButton", "delete-all");
	});
	$(".btn-forward").click(function() {
		var item_id = $(this).attr("item-id");
		var p = {};
		p["id"] = item_id;
		
		$.ajax({
			type: "POST",
			url: "<?=site_url("syslog/ajax-mail-forward/compose")?>",
			data: p,
			dataType: "json",
			success: function(result) {
				$("#dialog-send-mail").find("#to_receiver").val(result[0]);
				$("#dialog-send-mail").find("#subject").val(result[1]);
				tinymce.get('message').setContent(result[2]);
				$("#dialog-send-mail").modal();
			}
		});
	});
	
	$(".btn-send").click(function() {
		var p = {};
		p["to_receiver"] = $("#dialog-send-mail").find("#to_receiver").val();
		p["subject"] = $("#dialog-send-mail").find("#subject").val();
		p["message"] = tinymce.get('message').getContent();
		
		$.ajax({
			type: "POST",
			url: "<?=site_url("syslog/ajax-mail-forward/send")?>",
			data: p,
			success: function(result) {
				messageBox("INFO", "Compose Email", result);
				$("#dialog-send-mail").modal("hide");
			}
		});
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
});
</script>