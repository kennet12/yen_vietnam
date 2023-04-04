<!DOCTYPE html>
<html lang="en-US" translate="no">
	<head>
		<? require_once(APPPATH."views/module/head_html.php"); ?>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="<?=ADMIN_CSS_URL?>admin.css?cr=<?=date('Ymdhis')?>" />
		<script type="text/javascript" src="<?=ADMIN_JS_URL?>admin.js?cr=<?=date('Ymdhis')?>"></script>
	</head>
	<body>
		<? require_once(APPPATH."views/module/admin/header.php"); ?>
		<? require_once(APPPATH."views/module/admin/breadcrumb.php"); ?>
		<? require_once(APPPATH."views/module/admin/notification.php"); ?>
		<?=$content?>
		<? require_once(APPPATH."views/module/admin/upload_ckfinder.php"); ?>
		<? require_once(APPPATH."views/module/admin/footer.php"); ?>
		<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
		<script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
		<script src="https://cdn.tiny.cloud/1/kygw5r5d544vvdrv2sn3gmmteddov4syc2sgzkcrvflcrmf0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
		<script type="text/javascript">
			tinymce.init({
				selector: '.tinymce',
				relative_urls: false,
				remove_script_host: false,
				plugins: 'code table advlist autolink lists link image charmap print preview hr anchor pagebreak emoticons charmap template',
				toolbar: 'addcomment showcomments undo redo | styleselect | bold italic underline fontsizeselect | alignleft aligncenter alignright alignjustify | emoticons charmap forecolor backcolor  | link image | template code',
				toolbar_mode: 'floating',
				fontsize_formats: '8px 10px 12px 14px 16px 18px 24px 36px 48px',
				height: 600,
				content_style: "body {font-size: 14px;}",
				templates : [
					{
						title: 'Lập chỉ mục liên kết nhanh',
						description: 'Liên kết nhanh bài viết',
						content: '<div style="border: 1px solid #dcdcdc;border-radius: 4px;padding: 20px;background: #f7f8ff;margin-bottom:40px;"><h5 style="font-size:16px;">Nội dung chính</h5><ul>	<li style="font-size: 14px;list-style:none;">1. <a class="content-1" href="#content-1">Nội dung bài viết 1</a></li>	<li style="font-size: 14px;list-style:none;">2. <a class="content-2" href="#content-2">Nội dung bài viết 2</a></li>	<li style="font-size: 14px;list-style:none;">3. <a class="content-3" href="#content-3">Nội dung bài viết 3</a></li>	<li style="font-size: 14px;list-style:none;">4. <a class="content-4" href="#content-4">Nội dung bài viết 4</a></li>	<li style="font-size: 14px;list-style:none;">5. <a class="content-5" href="#content-5">Nội dung bài viết 5</a></li>	<li style="font-size: 14px;list-style:none;">6. <a class="content-6" href="#content-6">Nội dung bài viết 6</a></li>	<li style="font-size: 14px;list-style:none;">7. <a class="content-7" href="#content-7">Nội dung bài viết 7</a></li>	<li style="font-size: 14px;list-style:none;">8. <a class="content-8" href="#content-8">Nội dung bài viết 8</a></li>	<li style="font-size: 14px;list-style:none;">9. <a class="content-9" href="#content-9">Nội dung bài viết 9</a></li></ul></div><div><h5 style="font-size:16px;" id="content-1" class="title-content"><strong>Nội dung bài viết 1</strong></h5><p><span style="font-size: 14px; color: #000000;">Nếu bạn nghiên cứu kỹ hơn về giống mèo mà bạn đang nuôi, thì chắc chắn bạn sẽ biết mèo thuộc loài ăn thịt bắt buộc. Cơ thể của chúng cần protein từ thịt để sống khỏe mạnh và thậm chí còn không có bằng chứng nào cho thấy chúng cần thiết phải ăn cỏ, bạn có thể cho chúng ăn hoặc không.&nbsp;</span></p><h5 style="font-size:16px;" id="content-2" class="title-content"><strong>Nội dung bài viết 2</strong></h5><p><span style="font-size: 14px; color: #000000;">Nếu bạn nghiên cứu kỹ hơn về giống mèo mà bạn đang nuôi, thì chắc chắn bạn sẽ biết mèo thuộc loài ăn thịt bắt buộc. Cơ thể của chúng cần protein từ thịt để sống khỏe mạnh và thậm chí còn không có bằng chứng nào cho thấy chúng cần thiết phải ăn cỏ, bạn có thể cho chúng ăn hoặc không.&nbsp;</span></p><h5 style="font-size:16px;" id="content-3" class="title-content"><strong>Nội dung bài viết 3</strong></h5><p><span style="font-size: 14px; color: #000000;">Nếu bạn nghiên cứu kỹ hơn về giống mèo mà bạn đang nuôi, thì chắc chắn bạn sẽ biết mèo thuộc loài ăn thịt bắt buộc. Cơ thể của chúng cần protein từ thịt để sống khỏe mạnh và thậm chí còn không có bằng chứng nào cho thấy chúng cần thiết phải ăn cỏ, bạn có thể cho chúng ăn hoặc không.&nbsp;</span></p><h5 style="font-size:16px;" id="content-4" class="title-content"><strong>Nội dung bài viết 4</strong></h5><p><span style="font-size: 14px; color: #000000;">Nếu bạn nghiên cứu kỹ hơn về giống mèo mà bạn đang nuôi, thì chắc chắn bạn sẽ biết mèo thuộc loài ăn thịt bắt buộc. Cơ thể của chúng cần protein từ thịt để sống khỏe mạnh và thậm chí còn không có bằng chứng nào cho thấy chúng cần thiết phải ăn cỏ, bạn có thể cho chúng ăn hoặc không.&nbsp;</span></p><h5 style="font-size:16px;" id="content-5" class="title-content"><strong>Nội dung bài viết 5</strong></h5><p><span style="font-size: 14px; color: #000000;">Nếu bạn nghiên cứu kỹ hơn về giống mèo mà bạn đang nuôi, thì chắc chắn bạn sẽ biết mèo thuộc loài ăn thịt bắt buộc. Cơ thể của chúng cần protein từ thịt để sống khỏe mạnh và thậm chí còn không có bằng chứng nào cho thấy chúng cần thiết phải ăn cỏ, bạn có thể cho chúng ăn hoặc không.&nbsp;</span></p><h5 style="font-size:16px;" id="content-6" class="title-content"><strong>Nội dung bài viết 6</strong></h5><p><span style="font-size: 14px; color: #000000;">Nếu bạn nghiên cứu kỹ hơn về giống mèo mà bạn đang nuôi, thì chắc chắn bạn sẽ biết mèo thuộc loài ăn thịt bắt buộc. Cơ thể của chúng cần protein từ thịt để sống khỏe mạnh và thậm chí còn không có bằng chứng nào cho thấy chúng cần thiết phải ăn cỏ, bạn có thể cho chúng ăn hoặc không.&nbsp;</span></p><h5 style="font-size:16px;" id="content-7" class="title-content"><strong>Nội dung bài viết 7</strong></h5><p><span style="font-size: 14px; color: #000000;">Nếu bạn nghiên cứu kỹ hơn về giống mèo mà bạn đang nuôi, thì chắc chắn bạn sẽ biết mèo thuộc loài ăn thịt bắt buộc. Cơ thể của chúng cần protein từ thịt để sống khỏe mạnh và thậm chí còn không có bằng chứng nào cho thấy chúng cần thiết phải ăn cỏ, bạn có thể cho chúng ăn hoặc không.&nbsp;</span></p><h5 style="font-size:16px;" id="content-8" class="title-content"><strong>Nội dung bài viết 8</strong></h5><p><span style="font-size: 14px; color: #000000;">Nếu bạn nghiên cứu kỹ hơn về giống mèo mà bạn đang nuôi, thì chắc chắn bạn sẽ biết mèo thuộc loài ăn thịt bắt buộc. Cơ thể của chúng cần protein từ thịt để sống khỏe mạnh và thậm chí còn không có bằng chứng nào cho thấy chúng cần thiết phải ăn cỏ, bạn có thể cho chúng ăn hoặc không.&nbsp;</span></p><h5 style="font-size:16px;" id="content-9" class="title-content"><strong>Nội dung bài viết 9</strong></h5><p><span style="font-size: 14px; color: #000000;">Nếu bạn nghiên cứu kỹ hơn về giống mèo mà bạn đang nuôi, thì chắc chắn bạn sẽ biết mèo thuộc loài ăn thịt bắt buộc. Cơ thể của chúng cần protein từ thịt để sống khỏe mạnh và thậm chí còn không có bằng chứng nào cho thấy chúng cần thiết phải ăn cỏ, bạn có thể cho chúng ăn hoặc không.&nbsp;</span></p></div></div>'
					},
				],
			});
		</script>
	</body>
</html>
