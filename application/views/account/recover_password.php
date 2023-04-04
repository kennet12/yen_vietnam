
<div class="page-width pb-30 login">
<div class="container">
   <div class="row justify-content-center">
      <div class="col-8 col-xs-12">
         <div id="RecoverPasswordForm" class="mb-30">
            <div class="title">
               <?=$website['note_login3']?></div class="title">
               <p><?=$website['note_login4']?></p>
               <div class="form-vertical">
                  <form method="post" action="<?=site_url('tai-khoan/lay-lai-mat-khau')?>" accept-charset="UTF-8">
                  <input type="hidden" id="task" name="task" value="getpass" />   
                     <div class="form-group novform-email">
                        <input type="email" value="" name="email" id="email" placeholder="Email" class="input-full">
                     </div>
					 <div class="g-recaptcha" data-theme="light" data-sitekey="<?=RECAPTCHA_KEY?>"></div>
                     <div class="d-flex groups-sub">
                        <input type="submit" class="btn" value="<?=$website['submit']?>">
                        <button type="button" id="HideRecoverPasswordLink" class="text-link"><?=$website['cancel']?></button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
	$(document).on('click','.btn-secondary',function() {
		if($('.modal-title').html()=='Gửi thành công'){
			window.location.href = '<?=BASE_URL?>';
		}
	})
</script>