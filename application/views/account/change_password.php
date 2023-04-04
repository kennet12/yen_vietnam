<?
	$data = null;
	if ($this->session->flashdata('login')) {
		$data = $this->session->flashdata('login');
	}
	$password				= (!empty($data->password) ? $data->password : "");
	$new_password			= (!empty($data->new_password) ? $data->new_password : "");
	$confirm_new_password	= (!empty($data->confirm_new_password) ? $data->confirm_new_password : "");
?>
<div class="page-width pb-30">
    <div class="container">
		<div style="background: #fff;padding: 20px 0;">
			<div class="row justify-content-center">
				<div class="col-8 col-xs-12">
					<div class="form-vertical">
						<form method="post" action="" id="create_customer" accept-charset="UTF-8">
						<div class="title_block"><span><?=$this->website['change_password']?></span></div>
						<div class="block-form-login">
							<div class="form-group novform-password">
								<input type="password" name="password" id="password"  placeholder="<?=$website['error_note12']?>">
							</div>
							<div class="form-group novform-password">
								<input type="password" name="new_password" id="new_password" class="" placeholder="<?=$website['error_note13']?>">
							</div>
							<div class="form-group novform-password">
								<input type="password" name="confirm_new_password" id="confirm_new_password" class="" placeholder="<?=$website['confirm_password']?>">
							</div>
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
	
	$('#btn-save').click(function() {
		var err = 0;
		var msg = [];

		clearFormError();

		if ($("#password").val() == "") {
			$("#password").addClass("error");
			err++;
			msg.push("<?=$website['error_note4']?>");
		} else {
			$("#password").removeClass("error");
		}

		if ($("#new_password").val() == "") {
			$("#new_password").addClass("error");
			err++;
			msg.push("<?=$website['error_note11']?>");
		} else if ($("#new_password").val().length < 6) {
			$("#new_password").addClass("error");
			err++;
			msg.push("<?=$website['error_note6']?>");
		} else {
			$("#new_password").removeClass("error");
		}

		if ($("#new_password").val().length && $("#confirm_new_password").val() != $("#new_password").val()) {
			$("#confirm_new_password").addClass("error");
			err++;
			msg.push("<?=$website['error_note5']?>");
		} else {
			$("#confirm_new_password").removeClass("error");
		}

		if (!err) {
			$('#btn-signup').prop("disabled", true);
			$("#task").val("save");
			$("#create_customer").submit();
		} else {
			showErrorMessage('<?=$website['error']?>','<?=$website['error_note']?>', msg, '<?=$website['lose']?>');
		}
	});
});
</script>