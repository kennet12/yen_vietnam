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
<div class="container">
	<div class="section">
		<form id="frm-login" name="frm-login" class="frm-login form-horizontal" role="form" action="<?=site_url("dat-hang/dang-nhap")?>" method="POST">
			<div class="row">
				<div class="col-md-5 col-sm-5 col-xs-12">
					<h4 class="title">Tôi đã có tài khoản</h4>
					<div class="">
						<div class="form-group">
							<div class="row">
								<div class="col-md-5"></div>
								<div class="col-md-7">
									<h3>Đăng Nhập</h3>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-5">
									<label class="control-label" for="email">Tên đăng nhập / email <span class="text-color-red">*</span></label>
								</div>
								<div class="col-md-7">
									<input type="text" name="username" id="username" class="form-control" value="<?=$username?>" placeholder="Tên đăng nhập hoặc email"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-5">
									<label class="control-label" for="password">Mật khẩu <span class="text-color-red">*</span></label>
								</div>
								<div class="col-md-7">
									<input type="password" name="password" id="password" class="form-control" placeholder="Mật khẩu"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-5"></div>
								<div class="col-md-7 clearfix">
									<button type="button" id="btn-login" class="btn btn-red">Đăng nhập</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="switcher col-md-2 col-sm-2">
					<div class="display-desktop">
						<div class="switch-line"></div>
					</div>
				</div>
				<div class="col-md-5 col-sm-5 col-xs-12">
					<h4 class="title">Tôi chưa có tài khoản</h4>
					<div class="">
						<div class="form-group">
							<div class="row">
								<div class="col-md-4"></div>
								<div class="col-md-8">
									<h3>Thông tin khách hàng</h3>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="control-label">Tên khách hàng <span class="text-color-red">*</span></label>
								</div>
								<div class="col-md-8">
									<input type="text" class="form-control" id="new_fullname" name="new_fullname" value="<?=$new_fullname?>" placeholder="Họ và Tên" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="control-label">Email <span class="text-color-red">*</span></label>
								</div>
								<div class="col-md-8">
									<input type="text" class="form-control" id="new_email" name="new_email" value="<?=$new_email?>" placeholder="Email" />
									<i style="font-size: 14px;" class="text-color-red">Chú ý* : Vui lòng nhập đúng địa chỉ email của bạn.</i>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="control-label">Số điện thoại <span class="text-color-red">*</span></label>
								</div>
								<div class="col-md-8">
									<input type="text" id="new_phone" name="new_phone" class="form-control" value="<?=$new_phone?>" placeholder="Số điện thoại" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4"></div>
								<div class="col-md-8">
									<button type="button" class="btn btn-red" id="btn-signup" value="CONTINUE">Tiếp tục thanh toán</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="task" name="task" value="" />
		</form>
	</div>
</div>
<!-- <script type="text/javascript" src="<?=JS_URL?>facebook.js"></script> -->
<!-- <script type="text/javascript" src="<?=JS_URL?>google-plus.js"></script> -->

<script>
	
$(document).ready(function() {
	// jQuery.noConflict();
	<? if ($this->session->flashdata("error")) { ?>
	messageBox("ERROR", "Xảy ra lỗi", "<?=$this->session->flashdata("error")?>");
	<? } ?>

	$("#btn-login").click(function() {
		var err = 0;
		var msg = [];

		clearFormError();

		if ($("#username").val() == "") {
			$("#username").addClass("error");
			err++;
			msg.push("Tên đăng nhập không được trống.");
		} else {
			$("#username").removeClass("error");
		}

		if ($("#password").val() == "") {
			$("#password").addClass("error");
			err++;
			msg.push("Mật khẩu không được trống.");
		} else {
			$("#password").removeClass("error");
		}

		if (!err) {
			$("#task").val("login");
			$("#frm-login").submit();
		} else {
			showErrorMessage(msg);
		}
	});

	$('#btn-signup').click(function() {
		var err = 0;
		var msg = [];

		clearFormError();

		if ($("#new_fullname").val() == "") {
			$("#new_fullname").addClass("error");
			err++;
			msg.push("Họ và Tên không được trống.");
		} else {
			$("#new_fullname").removeClass("error");
		}

		if ($("#new_email").val() == "") {
			$("#new_email").addClass("error");
			err++;
			msg.push("Email không được trống.");
		} else {
			$("#new_email").removeClass("error");
		}

		if ($("#new_phone").val() == "") {
			$("#new_phone").addClass("error");
			err++;
			msg.push("Số điện thoại không được trống.");
		} else {
			if (validatePhone('new_phone') == false) {
				$("#new_phone").addClass("error");
				err++;
				msg.push("Số điện thoại không hợp lệ");
			}
			else {
				$("#new_phone").removeClass("error");
			}

		}

		if ($("#new_username").val() == "") {
			$("#new_username").addClass("error");
			err++;
			msg.push("Tên đăng nhập không được trống.");
		} else {
			$("#new_username").removeClass("error");
		}

		if (!err) {
			$('#btn-signup').prop("disabled", true);
			$("#task").val("infomation");
			$("#frm-login").submit();
		} else {
			showErrorMessage(msg);
		}
	});
});
</script>

