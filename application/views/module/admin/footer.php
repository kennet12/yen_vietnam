<div class="footer">
</div>

<a title="UP" class="scroll-top hidden-xs" href="#"></a>

<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.scroll-top').fadeIn();
		} else {
			$('.scroll-top').fadeOut();
    	}
	});
	$('.scroll-top').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});
	$('[data-toggle="tooltip"]').tooltip();
	$.post("<?=BASE_URL?>/files/browse.php",{"syslog":"<?=(in_array($this->session->admin->user_type, array(USR_ADMIN, USR_SUPPER_ADMIN)))?>"});
});
</script>