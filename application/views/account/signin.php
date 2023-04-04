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
      <div class="col-8 col-xs-12 overflow-hidden">
         <div id="CustomerLoginForm" class="form-vertical">
			<form id="customer_login" name="frm-login" role="form" action="" method="POST" accept-charset="UTF-8">
			<input type="hidden" id="task" name="task" value="" />
               <input type="hidden" name="form_type" value="customer_login" /><input type="hidden" name="utf8" value="✓" />
               <div class="title_block"><span class="text-bold"><?=$website['login']?></span></div>
               <div class="block-form-login">
                  <div class="title_form"><span><?=$website['note_login']?></span></div>
                  <div class="form-group novform-email">
					 <input type="text" name="username" id="username" class="form-control" value="<?=$username?>" placeholder="Tên đăng nhập hoặc email" />
                  </div>
                  <div class="form-group novform-password">
				  	<input type="password" name="password" id="password" class="form-control" placeholder="Mật khẩu"/>
                     <div class="hide_show_password" style="display: block;">
                        <span class="show"><i class="zmdi zmdi-eye-off"></i></span>
                     </div>
                  </div>
                  <div class="forgot_password">
                     <i class="zmdi zmdi-email"></i>
                     <a href="<?=site_url('tai-khoan/lay-lai-mat-khau')?>">
                     	<?=$website['forgot_your']?> <strong><?=$website['password']?> ?</strong>
                     </a>
                  </div>
                  <div class="bank_register"><?=$website['note_login1']?> 
                     <a href="<?=site_url('tai-khoan/dang-ky-tai-khoan')?>"><?=$website['note_login2']?> </a>
                  </div>
                  <div class="form_submit">
					 <button type="button" id="btn-login" class="btn"><?=$website['login']?></button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script>
$(document).ready(function() {

	$("#btn-login").click(function() {
		var err = 0;
		var msg = [];

		clearFormError();

		if ($("#username").val() == "") {
			$("#username").addClass("error");
			err++;
			msg.push("<?=$website['error_note2']?>");
		} else {
			$("#username").removeClass("error");
		}

		if ($("#password").val() == "") {
			$("#password").addClass("error");
			err++;
			msg.push("<?=$website['error_note4']?>");
		} else {
			$("#password").removeClass("error");
		}

		if (!err) {
			$("#task").val("login");
			$("#customer_login").submit();
		} else {
			showErrorMessage('<?=$website['error']?>','<?=$website['error_note']?>', msg, '<?=$website['lose']?>');
		}
	});
});
</script>

