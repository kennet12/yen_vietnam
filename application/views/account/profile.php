<div class="page-width pb-30 ">
    <div class="container">
	<div style="background: #fff;padding: 20px 0;">
    <div class="row justify-content-center">
        <div class="col-8 col-xs-12">
            <div class="form-vertical">
                <form method="post" action="" id="create_customer" accept-charset="UTF-8">
					<div class="title_block"><span><?=$this->website['profile']?></span></div>
					<div class="block-form-login">
						<div class="form-group novform-firstname">
							<input type="text" name="fullname" id="fullname"  placeholder="<?=$website['fullname']?>" value="<?=$user->fullname?>">
						</div>
						<div>
							<div class="radio" style="display:inline-block">
								<label style="font-size: 13px">
									<input type="radio" name="gender" style="margin:7px 0 14px 0;" value="1" <?=($user->gender?"checked":"")?>/>
									<?=$website['male']?>
								</label>
							</div>
							<div class="radio" style="margin-left: 12px;display:inline-block">
								<label style="font-size: 13px">
									<input type="radio" name="gender" style="margin:7px 0 14px 0;" value="0" <?=($user->gender?"":"checked")?>/>
									<?=$website['female']?>
								</label>
							</div>
						</div>
						<div class="form-group novform-password">
							<input type="date" name="birthday" id="birthday" class="" placeholder="<?=$website['birthday']?>" value="<?=date('Y-m-d',strtotime($user->birthday))?>">
						</div>
						<div class="form-group novform-phone">
							<input type="text" name="phone" id="phone"  placeholder="<?=$website['phone']?>" value="<?=$user->phone?>">
						</div>
						<div class="form-group novform-password">
							<input type="text" name="address" id="address" class="" placeholder="<?=$website['order_note5']?>" value="<?=$user->address?>">
						</div>
						<br>
						<div class="form_submit">
							<input id="btn-save" value="<?=$website['submit']?>" class="btn">
						</div>
					</div>
					<input type="hidden" id="task" name="task" value="" />
                </form>
            </div>
        </div>
    </div>
    </div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('#btn-save').click(function(e) {
		$("#task").val("save");
		$("#create_customer").submit();
	});
});
</script>

