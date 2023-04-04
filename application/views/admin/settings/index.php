<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title">Setting website</h1>
		<table class="table table-bordered">
			<!-- <tr>
				<td class="table-head text-right" width="10%">Logo</td>
				<td><img style="width: 200px;" alt="" src="<?=BASE_URL.$setting->logo?>"></td>
			</tr> -->
			<tr>
				<td class="table-head text-right" width="10%">Tên công ty</td>
				<td><?=$setting->company_name?></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Tiêu đề</td>
				<td><?=$setting->company_logan?></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Địa chỉ</td>
				<td><?=$setting->company_address?></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Email</td>
				<td><?=$setting->company_email?></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Hotline</td>
				<td><?=$setting->company_hotline?></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">SDT bàn:</td>
				<td><?=$setting->company_tollfree?></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Tỉ giá (VND/USD):</td>
				<td><?=number_format($setting->usd,0,',','.')?></td>
			</tr>
		</table>
		<h1 class="page-title">Social Links</h1>
		<table class="table table-bordered">
			<tr>
				<td class="table-head text-right" width="10%">Facebook</td>
				<td><?=$setting->facebook_url?></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Youtube</td>
				<td><?=$setting->youtube_url?></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Instagram+</td>
				<td><?=$setting->googleplus_url?></td>
			</tr>
			<tr>
				<td class="table-head text-right" width="10%">Tiktok</td>
				<td><?=$setting->twitter_url?></td>
			</tr>
		</table>
		<h1 class="page-title">Cloud</h1>
		<table class="table table-bordered">
			<tr>
				<td class="table-head text-right" width="10%">Tags</td>
				<td>
					<?
						$tags = explode(",", $setting->tags);
						foreach ($tags as $tag) {
							?>
							<span class="label label-primary"><?=trim($tag)?></span>&nbsp;
							<?
						}
					?>
				</td>
			</tr>
		</table>
		<div>
			<a class="btn btn-sm btn-primary btn-edit" href="<?=site_url("syslog/settings/edit")?>">Chỉnh sửa</a>
		</div>
	</div>
</div>
