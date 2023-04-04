<? 
$fetch_method = $this->util->slug($this->router->fetch_method());
$user=$this->session->userdata("user");
$lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
$this->lang->load('content',$lang);
$menu				= $this->lang->line('menu');
$website			= $this->lang->line('website');
$prop				= $this->lang->line('prop');
$alias				= $this->lang->line('alias');
?>
<div class="nav-account">
	<div class="container">
		<div class="info-account">
			<span><?=$user->fullname?></span>
			<div class="rank"><?=$website['rank']?>: <i class="fas fa-award fa-award-<?=$user->user_rank?>"></i><span class="note-rank"><?
												if ($user->user_rank == 0) {
													echo $website['rank_new'];
												} else if ($user->user_rank == 1) {
													echo $website['rank_silver'];
												} else if ($user->user_rank == 2) {
													echo $website['rank_gold'];
												} else if ($user->user_rank == 3) {
													echo $website['rank_platinum'];
												} else if ($user->user_rank == 4) {
													echo $website['rank_diamond'];
												}
												?></span>
			</div>
			<div class="edit-line">
				<a href="<?=site_url("tai-khoan/thong-tin-tai-khoan")?>" class="editinfo"><i class="zmdi zmdi-border-color"></i> <?=$website['edit']?></a>
				<a href="<?=site_url("tai-khoan/dang-xuat")?>" class="editinfo"><i class="zmdi zmdi-long-arrow-return"></i> <?=$website['logout']?></a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="list clearfix">
			<div class="item <?=($fetch_method == 'affiliate')?'active':''?>">
				<a href="<?=site_url("tai-khoan/affiliate")?>">Affiliate</a>
			</div>
			<div class="item <?=($fetch_method == 'lich-su-don-hang')?'active':''?>">
				<a href="<?=site_url("tai-khoan/lich-su-don-hang")?>"><?=$website['order_list']?></a>
			</div>
			<div class="item <?=($fetch_method == 'san-pham-yeu-thich')?'active':''?>">
				<a href="<?=site_url("tai-khoan/san-pham-yeu-thich")?>"><?=$website['like_list']?></a>
			</div>
			<div class="item <?=($fetch_method == 'doi-mat-khau')?'active':''?>">
				<a href="<?=site_url("tai-khoan/doi-mat-khau")?>"><?=$website['change_password']?></a>
			</div>
		</div>
	</div>
</div>
