<?
	$data = null;
	if ($this->session->flashdata('login')) {
		$data = $this->session->flashdata('login');
	}
	$username				= (!empty($data->username) ? $data->username : "");
	$new_gender				= (!empty($data->new_gender) ? $data->new_gender : "1");
	$new_fullname			= (!empty($data->new_fullname) ? $data->new_fullname : "");
	$new_email				= (!empty($data->new_email) ? $data->new_email : "");
	$new_username			= (!empty($data->new_username) ? $data->new_username : "");
	$new_password			= (!empty($data->new_password) ? $data->new_password : "");
	$confirm_new_password	= (!empty($data->confirm_new_password) ? $data->confirm_new_password : "");
	$new_phone				= (!empty($data->new_phone) ? $data->new_phone : "");
?>
<div class="page-width pb-30 login">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-8 col-xs-12">
            <div class="form-vertical">
                <form method="post" action="" id="create_customer" accept-charset="UTF-8">
                <input type="hidden" name="form_type" value="create_customer" /><input type="hidden" name="utf8" value="✓" />
                <div class="title_block"><span><?=$this->website['register_account']?></span></div>
                <div class="block-form-login">
					<div class="form-group novform-firstname">
                        <input type="text" name="new_fullname" id="new_fullname"  placeholder="<?=$website['fullname']?>" value="<?=$new_fullname?>">
                    </div>
                    <div class="form-group novform-email">
                        <input type="email" name="new_email" id="new_email" class=""  placeholder="Email" value="<?=$new_email?>" >
                    </div>
                    <div class="form-group novform-phone">
                        <input type="text" name="new_phone" id="new_phone"  placeholder="<?=$website['phone']?>" value="<?=$new_phone?>">
                    </div>
                    <div class="form-group novform-password">
                        <input type="password" name="new_password" id="new_password" class="" placeholder="<?=$website['password']?>">
                    </div>
                    <div class="form-group novform-password">
                        <input type="password" name="confirm_new_password" id="confirm_new_password" class="" placeholder="<?=$website['confirm_password']?>">
                    </div>
                    <div class="form-checkbox novform-newsletter">
                        <label id="form-checkbox" class="custom_checkbox d-inline-flex">
                        <span class="custom-checkbox">
                        <input type="checkbox" name="newsletter" value="1">
                        </span>
                        <span class="text"><?=$website['note_register']?></span>
                        </label>
                        <div class="bank_login"><?=$website['note_register1']?>
                            <a href="<?=site_url("tai-khoan/dang-nhap")?>"><?=$website['note_register2']?></a>
                        </div>
                    </div>
                    <div class="form_submit">
                        <input id="btn-signup" value="<?=$website['submit']?>" class="btn">
                    </div>
                </div>
				<input type="hidden" id="task" name="task" value="" />
                </form>
            </div>
        </div>
    </div>
    </div>
</div>

<script>
$(document).ready(function() {

	$('#btn-signup').click(function() {
		var err = 0;
		var msg = [];

		clearFormError();

		function validatePhone(new_phone) {
			var a = $('#new_phone').val();
			var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
			if (filter.test(a)) {
				return true;
			}
			else {
				return false;
			}
		}

		if ($("#new_fullname").val() == "") {
			$("#new_fullname").addClass("error");
			err++;
			msg.push("<?=$website['error_note1']?>");
		} else {
			$("#new_fullname").removeClass("error");
		}

		if ($("#new_email").val() == "") {
			$("#new_email").addClass("error");
			err++;
			msg.push("<?=$website['error_note2']?>");
		} else {
			$("#new_email").removeClass("error");
		}

		if ($("#new_phone").val() == "") {
			$("#new_phone").addClass("error");
			err++;
			msg.push("<?=$website['error_note3']?>");
		} else {
			if (validatePhone('new_phone') == false) {
				$("#new_phone").addClass("error");
				err++;
				msg.push("<?=$website['error_note7']?>");
			}
			else {
				$("#new_phone").removeClass("error");
			}

		}

		if ($("#new_password").val() == "") {
			$("#new_password").addClass("error");
			err++;
			msg.push("<?=$website['error_note4']?>");
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
			$("#task").val("register");
			$("#create_customer").submit();
		} else {
			showErrorMessage('<?=$website['error']?>','<?=$website['error_note']?>', msg, '<?=$website['lose']?>');
		}
	});
});
</script>

