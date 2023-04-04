<?
	$setting = $this->m_setting->load(1);
?>
<div class="page-width">
	<div class="container">
	<h1 class="headingPage"><?=$menu['contact']?></h1>
	<div id="shopify-section-nov-page-contact" class="shopify-section index-section page-contact">
		<div class="boxInformationImage">
			<div class="row">
				<div class="col-md-4 d-flex mb-sm-30">
				<div class="content">
					<div class="tt_i_contact"><?=$website['order_note4']?> :</div>
					<div class="sub_i_contact sub_bold"><a href="tel:<?=$setting->company_hotline?>"><?=$setting->company_hotline?></a></div>
				</div>
				</div>
				<div class="col-md-4 d-flex mb-sm-30 align-items-lg-center">
				<div class="content">
					<div class="tt_i_contact"><?=$website['order_note5']?> :</div>
					<div class="sub_i_contact"><a target="_blank" href="https://www.google.com/maps/place/S4+Ba+V%C3%AC,+Ph%C6%B0%E1%BB%9Dng+15,+Qu%E1%BA%ADn+10,+Th%C3%A0nh+ph%E1%BB%91+H%E1%BB%93+Ch%C3%AD+Minh,+Vietnam/@10.7801413,106.6620988,19z/data=!4m5!3m4!1s0x31752ec563d43459:0x18923029e1754f84!8m2!3d10.780136!4d106.662646"><?=$setting->company_address?></a></div>
				</div>
				</div>
				<div class="col-md-4 d-flex align-items-lg-center">
				<div class="content">
					<div class="tt_i_contact">Email :</div>
					<div class="sub_i_contact"><a href="mailto:<?=$setting->company_email?>"><?=$setting->company_email?></a></div>
				</div>
				</div>
			</div>
		</div>
		<div class="formContactUs">
			<div class="row">
				<div class="col-xl-4 col-lg-4 col-md-12 col-xs-12">
				<div class="contact_message">
					<div class="block_social">
						<ul class="list-inline mb-0">
							<li class="list-inline-item">
							<a  target="_blank" href="<?=$setting->facebook_url?>" title="Facebook">
							<i class="zmdi zmdi-facebook"></i>
							</a>
							</li>
							<li class="list-inline-item">
							<a target="_blank"  href="<?=$setting->twitter_url?>" title="Twitter">
							<i class="zmdi zmdi-twitter"></i>
							</a>
							</li>
							<li class="list-inline-item">
							<a target="_blank"  href="<?=$setting->youtube_url?>" title="Youtube">
							<i class="fa fa-youtube" aria-hidden="true"></i>
							</a>
							</li>
							<li class="list-inline-item">
							<a target="_blank"  href="<?=$setting->googleplus_url?>" title="Instagram">
							<i class="fa fa-instagram" aria-hidden="true"></i>
							</a>
							</li>
						</ul>
					</div>
				</div>
				</div>
				<div class="col-xl-8 col-lg-8 col-md-12 col-xs-12">
				<div class="contact-form form-vertical">
					<form method="post" action="/contact#contact_form" id="contact_form" accept-charset="UTF-8" class="contact-form">
						<input type="hidden" name="form_type" value="contact" /><input type="hidden" name="utf8" value="✓" />
						<div class="form-group row spacing-10">
							<div class="col-md-6 mb-sm-10">
							<input type="text" id="name" name="name" value="" class="" placeholder="<?=$website['order_note3']?>" required>
							</div>
							<div class="col-md-6">
							<input type="email" id="email" name="email" value="" class="" placeholder="Email" required>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-12">
							<input type="tel" id="phone" name="phone" pattern="[0-9\-]*" value="" class="" placeholder="<?=$website['order_note4']?>">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-12">
							<textarea rows="15" cols="50" id="content" name="content" placeholder="<?=$website['order_note14']?>"></textarea>
							</div>
						</div>
						<div class="g-recaptcha" data-theme="light" data-sitekey="<?=RECAPTCHA_KEY?>"></div>
						<button class="btn btn_message" type="submit">
							<i class="icon-message"></i><span><?=$website['submit']?></span>
						</button>
					</form>
				</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript">
	$(document).ready(function() {
		$(".btn_message").click(function(event) {
			event.preventDefault();
			var err = 0;
			var msg = [];

			clearFormError();

			if ($('#name').val() == "") {
				$('#name').addClass("error");
				err++;
				msg.push("<?=$website['error_note1']?>");
			} else {
				$('#name').removeClass("error");
			}

			if ($('#phone').val() == "") {
				$('#phone').addClass("error");
				err++;
				msg.push("<?=$website['error_note3']?>");
			} else {
				$('#phone').removeClass("error");
			}

			if ($('#email').val() == "") {
				$('#email').addClass("error");
				err++;
				msg.push("<?=$website['error_note2']?>");
			}
			else {
				$('#email').removeClass("error");
			}

			if ($('#content').val() == "") {
				$('#content').addClass('error');
				err++;
				msg.push('<?=$website['error_note9']?>');
			}
			else {
				$('#content').removeClass('error');
			}
			if ($('#g-recaptcha-response').val() == "") {
				err++;
				msg.push('<?=$website['error_note10']?>');
			}

			if (!err) {
				$('#frm-contact').submit();
			} else {
				showErrorMessage('<?=$website['error']?>','<?=$website['error_note']?>', msg, '<?=$website['lose']?>');
			}
		});
	});
</script>