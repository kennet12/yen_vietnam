<div class="cluster">
	<div class="container-fluid">
		<? if (empty($setting)) { ?>
		<h1 class="page-title">Settings</h1>
		<p class="help-block">Item not found.</p>
		<? } else { ?>
		<form id="frm-admin" name="adminForm" action="" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="">
			<h1 class="page-title">Setting website</h1>
			<table class="table table-bordered">
				<!-- <tr>
					<td class="table-head text-right" width="10%">Logo</td>
					<td><input type="text" id="logo" name="logo" class="form-control" value="<?=$setting->logo?>" onclick="openKCFinder4Textbox(this)"></td>
				</tr> -->
				<!-- <tr>
					<td class="table-head text-right" width="10%">LOGO</td>
					<td>
						<label class="wrap-upload-thumb" style="background: url('<?=BASE_URL?><?=!empty($setting->logo) ? $setting->logo : ''?>') no-repeat;width: 320px;">
							<input type="file" name="logo" id="file-upload" value="<?=!empty($setting->logo) ? $setting->logo : ''?>">
							<i class="fa fa-cloud-upload" aria-hidden="true"></i>
						</label>
					</td>
				</tr> -->
				<tr>
					<td class="table-head text-right" width="10%">Tên công ty</td>
					<td><input type="text" id="company_name" name="company_name" class="form-control" value="<?=$setting->company_name?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Tiêu đề</td>
					<td><input type="text" id="company_name" name="company_logan" class="form-control" value="<?=$setting->company_logan?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Địa chỉ</td>
					<td><input type="text" id="company_address" name="company_address" class="form-control" value="<?=$setting->company_address?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Email</td>
					<td><input type="text" id="company_email" name="company_email" class="form-control" value="<?=$setting->company_email?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Hotline</td>
					<td><input type="text" id="company_hotline" name="company_hotline" class="form-control" value="<?=$setting->company_hotline?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">SDT bàn</td>
					<td><input type="text" id="company_tollfree" name="company_tollfree" class="form-control" value="<?=$setting->company_tollfree?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Tỉ giá (VND/USD)</td>
					<td><input type="text" id="usd" name="usd" class="form-control" value="<?=$setting->usd?>"></td>
				</tr>
			</table>
			<h1 class="page-title">Social Links</h1>
			<table class="table table-bordered">
				<tr>
					<td class="table-head text-right" width="10%">Facebook</td>
					<td><input type="text" id="facebook_url" name="facebook_url" class="form-control" value="<?=$setting->facebook_url?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Youtube</td>
					<td><input type="text" id="youtube_url" name="youtube_url" class="form-control" value="<?=$setting->youtube_url?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Instagram</td>
					<td><input type="text" id="googleplus_url" name="googleplus_url" class="form-control" value="<?=$setting->googleplus_url?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Twitter</td>
					<td><input type="text" id="twitter_url" name="twitter_url" class="form-control" value="<?=$setting->twitter_url?>"></td>
				</tr>
			</table>
			<h1 class="page-title">Cloud</h1>
			<table class="table table-bordered">
				<tr>
					<td class="table-head text-right" width="10%">Tags</td>
					<td><input type="text" id="tags" name="tags" class="form-control" value="<?=$setting->tags?>" maxlength="255"></td>
				</tr>
			</table>
			<div>
				<button class="btn btn-sm btn-primary btn-save">Update</button>
				<button class="btn btn-sm btn-default btn-cancel">Cancel</button>
			</div>
		</form>
		<? } ?>
	</div>
</div>

<script>
$(document).ready(function() {
	$("#file-upload").change(function() {
		readURL(this);
	});
	
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('.wrap-upload-thumb').css({
					"background-image": "url('"+e.target.result+"')"
				});
				$('.wrap-upload-thumb > i').css({
					"color": "rgba(52, 73, 94, 0.38)"
				});
			};
			reader.readAsDataURL(input.files[0]);
		}
	}
	$(".btn-save").click(function(){
		submitButton("save");
	});
	$(".btn-cancel").click(function(){
		submitButton("cancel");
	});
});
</script>