<?$path_ck = './'.PATH_CKFINDER?>
<div id="myModal" class="modal fade" role="dialog" style="z-index: 99999;">
	<div class=" container">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<input type="hidden" id="path_ck" value="<?=$path_ck?>">
				<input type="hidden" id="active_dir" value="images-ck-finder">
				<div class="modal-title"></div>
				<ul class="control-ck">
					<li class="item">
						<label class="upload-file-ck">
							<input id="file-upload" class="select-file" name="file[]" multiple="multiple" type="file"/>
							Tải ảnh lên
						</label>
					</li>
					<li class="item">
						<a href="#" class="btn btn-primary btn-add-dir">Tạo thư mục</a>
					</li>
					<li class="item">
						<a href="#" class="btn btn-danger btn-del-dir">Xóa thư mục</a>
					</li>
				</ul>
				
				<div class="add-dir" style="display:none;">
					<input type="text" id="name_dir" placeholder="Tên thư mục" value="">
					<button class="btn-add-dir-ajax">Tạo</button>
				</div>
				<script>
					$('.btn-add-dir').click(function() {
						$('.add-dir').toggle('fast');
					});
					$('.btn-add-dir-ajax').click(function() {
						var active_dir = $('#active_dir').val();
						var p = {};
						p['path'] = $('#path_ck').val();
						p['action'] = 'add';
						p['name_dir'] = $('#name_dir').val();
						$.ajax({
							url : '<?php echo site_url('syslog/ck-finder')?>',
							type : 'POST',
							data : p,
							dataType: 'json',
							success : function(data) {
								console.log(data);
								$('.add-dir').toggle('fast');
								var str = '<div class="dir '+data[1]+'" path_ck="'+data[0]+'"><a class_name="'+data[1]+'"><i class="fa fa-folder-o"></i> '+$('#name_dir').val()+'</a></div>';
								$('.'+active_dir).append(str);
							}
						});
					})
					$('.btn-del-dir').click(function() {
						var active_dir = $('#active_dir').val();
						var cf = confirm("Bạn có chắc là muốn xóa thư mục này không?");
						if (cf) {
							var p = {};
							p['path'] = $('#path_ck').val();
							p['action'] = 'del';
							p['name_dir'] = $('#name_dir').val();
							$.ajax({
								url : '<?php echo site_url('syslog/ck-finder')?>',
								type : 'POST',
								data : p,
								dataType: 'html',
								success : function(data) {
									if (data=='0') {
										alert('Vui lòng xóa tất cả các file trong thư mục.');
									} else {
										$('.'+active_dir).html('');
									}
								}
							});
						}
					})
				</script>
			</div>
			<div class="modal-body" style="padding:0;">
				<?
					function folder($path, $obj) {
						$scans = array_filter(glob($path.'/*'), 'is_dir');
						foreach($scans as $scan) {
							$scan 		= explode('/',$scan);
							$scan 		= end($scan);
							$path_ck 	= str_replace(PATH_ROOT,'.',$path."/".$scan);
							$class 		= $obj->util->slug(str_replace('./','',$path_ck));
							echo "<div class='dir {$class}' path_ck='".$path_ck."'>
									<a class_name='{$class}'><i class='fa fa-folder-o'></i> ".$scan."</a>";
									folder("$path/$scan",$obj);
							echo "</div>";
						}
					}
				?>
				<div class="image-list">
					<div class="ckfinder-box">
						<div class="box-left">
							<div class="wrap-dir">
								<div class='dir images-ck-finder' path_ck="./images/ck_finder">
									<a class_name='images-ck-finder'><i class='fa fa-folder-o'></i> Root</a>
									<? folder(PATH_ROOT.'/'.PATH_CKFINDER,$this) ?>
								</div>
							</div>
						</div>
						<script>
							$(document).on("click",".dir > a",function() {
								$('#active_dir').val($(this).attr('class_name'));
								$('#path_ck').val($('.'+$(this).attr('class_name')).attr('path_ck'));
								$('.dir > a').removeClass('active');
								$(this).addClass('active');
								var p = {};
								p['path'] = $('#path_ck').val();
								p['action'] = 'del';
								p['name_dir'] = $('#name_dir').val();
								$.ajax({
									url : '<?php echo site_url('syslog/load-file-manager')?>',
									type : 'POST',
									data : p,
									dataType: 'json',
									success : function(data) {
										$('.box-list-image').html(data);
									}
								});
							})
						</script>
						<div class="box-right box-list-image">
							<? $fileList = glob(PATH_ROOT.'/'.PATH_CKFINDER.'/*');
							$files = $this->util->sort_date_file($fileList);
							foreach($files as $file) {
								if(is_file($file)) {
								$temp = explode('images', $file);
								$file_url = str_replace($temp[0],BASE_URL.'/', $file); ?>
							<div class="item">
								<i class="fa fa-times" aria-hidden="true"></i>
								<img class="select-img" src="<?=$file_url?>">
							</div>
							<? } } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).on("click",".tox-control-wrap .tox-textfield",function() { 
		jQuery.noConflict();
		var c = $(this).parents('.tox-form__group').find(".tox-label").html();
		if (c == 'Source') {
			$('#myModal').modal('show');
		}
	});
	$(document).on('change', '.select-file', function(event) {
		var formData = new FormData();
		var fileList = $(this).prop("files");
		for (var i = 0; i < fileList.length; i++) {
			formData.append("file[]", fileList[i]);
		}
		formData.append('action', 'upload');
		formData.append('path', $('#path_ck').val());
		var d = new Date();
		var t = d.getTime();
		$.ajax({
			url : '<?php echo site_url('syslog/ck-finder')?>',
			type : 'POST',
			data : formData,
			dataType: 'json',
			processData: false,
			contentType: false,
			success : function(data) { 
				$('.box-list-image').prepend(data);
			}
		});
	});
	$(document).on('dblclick', '.select-img', function(event) {
		event.preventDefault();
		$('.tox-control-wrap > input').val($(this).attr('src'));
		$('#myModal').modal('hide');
	});
	$(document).on("click",".fa-times",function() {
		var cf = confirm("Bạn có chắc muốn xóa hình này?");
		if (cf) {
			$(this).parent('.item').remove();
			var p = {};
			p['path'] =$('#path_ck').val();
			p['src'] = $(this).parent('.item').children('.select-img').attr('src');
			p['action'] = 'delfile';
			$.ajax({
				url : '<?php echo site_url('syslog/ck-finder')?>',
				type : 'POST',
				data : p,
				dataType:'json',
			});
		}
	});
</script>