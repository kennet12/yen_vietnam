<div class="page-width pb-30">
    <div class="container">
		<div style="background: #fff;padding: 20px 0;">
			<div class="row justify-content-center">
				<div class="col-8 col-xs-12">
					<div class="form-vertical">
						<form method="post" action="" id="create_customer" accept-charset="UTF-8">
						<div class="title_block"><span><?=$this->website['forgot_your'].' '.$this->website['password']?></span></div>
						<div class="block-form-login">
							<div class="form-group novform-password">
								<input type="password" name="new_password" id="new_password" class="" placeholder="<?=$website['error_note13']?>">
							</div>
							<div class="form-group novform-password">
								<input type="password" name="confirm_new_password" id="confirm_new_password" class="" placeholder="<?=$website['confirm_password']?>">
							</div>
							<div class="form_submit">
								<input id="btn-save" type="submit" value="<?=$website['submit']?>" class="btn">
							</div>
						</div>
						<input type="hidden" id="task" name="task" value="save" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>