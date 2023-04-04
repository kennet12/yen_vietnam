<?
	$method = $this->util->slug($this->router->fetch_class());
	$prop_name = !isset($_COOKIE['nguyenanh_lang'])?'name':'name_en';
	$prop_desciption = !isset($_COOKIE['nguyenanh_lang'])?'description':'description_en';
	$setting = $this->m_setting->load(1);
	$info = new stdClass();
	$user_online = $this->session->userdata("user");

	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$actual_link = '?p='.$actual_link.'';

	$lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
	$this->lang->load('content',$lang);
	$menu				= $this->lang->line('menu');
	$website			= $this->lang->line('website');
	$prop				= $this->lang->line('prop');
	$alias				= $this->lang->line('alias');

	$carts		= $this->cart->contents();
	$count_cart = count($carts);
	$subtotal = 0;
	foreach ($carts as $carts) {
		$subtotal += $carts['subtotal'];
	}
	
	$info = new stdClass();
	$info->parent_id = 0;
	$product_categories = $this->m_product_category->items($info,1);
?>
<? if($this->util->detect_mobile()) {  ?>
<div class="header-mobile d-md-none">
	<div class="d-flex align-items-center">
		<div class="btn-mobile_vertical_menu">
			<a href="<?=site_url('gio-hang')?>">
				<i class="zmdi zmdi-shopping-cart"><span id="qty_cart_item" class="cart-item"><?=$count_cart?></span></i>
			</a>
		</div>
		<div class="mobile_logo">
		<a href="<?=BASE_URL?>" class="site-header__logo-image img-fluid">
			<img class="js" src="<?=IMG_URL.'logo.jpg?v='.CACHE_TIME?>" alt="">
		</a>
		</div>
		<div id="mobile_search" class="search-box">
			<form type="m" action="<?=site_url("tim-kiem")?>" method="get" class="frm-search" role="search" style="position: relative;">
				<input class="search-header__input" id="search-m"  type="search" name="search_text" value="<?=!empty($_GET['search_text'])?$_GET['search_text']:''?>" placeholder="<?=$website['search_note']?>" autocomplete="off">
				<button class="search-header__submit text-center btn--link" type="submit">
					<span class="site-header__search-icon">
					<i class="fa fa-search" aria-hidden="true"></i>
					</span>
				</button>
				<ul class="search-results has-scroll" style="position: absolute; left: 0px; top: 40px; display: none;"></ul>
			
				<div class="list-keyword" style="display:none;overflow: scroll;width: 290px;right: 0;">
					<div class="title-search">
						<strong><i class="zmdi zmdi-key"></i> <?=$website['hot_key']?></strong>
					</div>
					<? $search = $this->m_search->items(null,10,0,'num_search','desc'); 
						foreach($search as $value) { ?>
						<div class="item" data="<?=$value->keyword?>">
						<i class="zmdi zmdi-search"></i> <?=$value->keyword?>
						</div>
					<? } ?>
				</div>
			</form>
		</div>
		<div class="d-flex justify-content-end">
		<div id="show-megamenu" class="item-mobile-top"><i class="zmdi zmdi-view-headline"></i></div>
		</div>
	</div> 
</div>
<script>
	searchProduct('#search-m','<?=$website['hot_key']?>','<?=$website['not_found_key']?>','<?=$website['result_search_product']?>','<?=$website['not_found_search_product']?>','<?=$website['readmore']?>');
</script>
<? } ?>
<div class="head-lang">
	<div class="container">
		<div class="d-none d-md-block">
			<div id="_desktop_currency_selector" class="currency-selector groups-selector">	
				<ul id="currencies" class="list-inline">
					<? if (empty($user_online)) { ?>
					<li class="currency__item list-inline-item">
						<a href="<?=site_url('tai-khoan/dang-nhap')?>">	
							<span ><?=$website['login']?></span>
						</a>
					</li>
					<li class="currency__item list-inline-item">
						<a href="<?=site_url('tai-khoan/dang-ky-tai-khoan')?>">	
							<span ><?=$website['register_account']?></span>
						</a>
					</li>
					<? } else { ?>
					<li class="currency__item list-inline-item">
						<div class="site-header_myaccount dropdown nv-mr-10 nv-ml-auto">
							<div class="dropdown-toggle text-center d-flex" data-toggle="dropdown">
								<a href="#">	
									<span><?=$user_online->fullname;?></span>
								</a>
							</div>
							<div class="account-list dropdown-menu dropdown-menu-right" id="_desktop_account_list">
								<div class="nov_sideward_content">
									<div class="account-list-content">
										<div>
											<a class="check-out" href="<?=site_url('tai-khoan/lich-su-don-hang')?>" rel="nofollow" title="Check out">
												<i class="zmdi zmdi-check-circle"></i>
												<p><?=$website['check_out']?></p>
											</a>
										</div>
										<div>
											<a class="check-out" href="<?=site_url('tai-khoan/thong-tin-tai-khoan')?>" rel="nofollow" title="Profile">
												<i class="zmdi zmdi-account"></i>
												<p><?=$website['profile']?></p>
											</a>
										</div>
										<div class="link_wishlist">
											<a class="wishlist" href="<?=site_url('tai-khoan/san-pham-yeu-thich')?>" rel="nofollow" title="My Wishlist">
												<i class="zmdi zmdi-favorite"></i>
												<p><?=$website['my_wishlist']?></p>
											</a>
										</div>
										<div class="link_wishlist">
											<a class="wishlist" href="<?=site_url('tai-khoan/dang-xuat')?>" rel="nofollow" title="My Wishlist">
												<i class="zmdi zmdi-long-arrow-return"></i>
												<p><?=$website['logout']?></p>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
					<? } ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="header-top d-none d-md-block">
	<div class="container">
		<div class="row d-flex align-items-center position-relative">
			<div class="contentsticky_logo col-md-3">
				<div class="h2 site-header__logo mb-0" itemscope="" itemtype="http://schema.org/Organization">
					<a href="<?=BASE_URL?>" itemprop="url" class="site-header__logo-image">
						<img class="js img-fluid" src="<?=IMG_URL.'logo.jpg?v='.CACHE_TIME?>" style="width:115px" alt="Yen Viet Nam">
					</a>
				</div>
			</div>
			<div class="col-md-6">
				<div class="contentsticky_search search_inline">
					<div class="site_search">
						<? if(!$this->util->detect_mobile()) { ?>
						<div class="site-header-pc search-active">
							<div class="search-button"><i class="zmdi zmdi-search"></i></div>
							<div id="search_widget" class="site_header__search search-box">
								<form type="pc" action="<?=site_url("tim-kiem")?>" method="GET" class="frm-search" role="search" style="position: relative;">
									<input class="search-header__input" type="search" id="search-pc" name="search_text" placeholder="<?=$website['search_note']?>" value="<?=!empty($_GET['search_text'])?$_GET['search_text']:''?>" aria-label="Search your product" autocomplete="off">
									<button class="search-header__submit text-center btn--link" type="submit">
										<span class="site-header__search-icon">
										<?=$website['search']?>
										</span>
									</button>
									<ul class="search-results has-scroll" style="position: absolute; left: 0px; top: 44px; display: none;"></ul>
								
									<div class="list-keyword" style="display:none">
										<div class="title-search">
											<strong><i class="zmdi zmdi-key"></i> <?=$website['hot_key']?></strong>
										</div>
										<? $search = $this->m_search->items(null,10,0,'num_search','desc'); 
											foreach($search as $value) { ?>
											<div class="item" data="<?=$value->keyword?>">
												<i class="zmdi zmdi-search"></i> <?=$value->keyword?>
											</div>
										<? } ?>
									</div>
								</form>
								<script>
									searchProduct('#search-pc','<?=$website['hot_key']?>','<?=$website['not_found_key']?>','<?=$website['result_search_product']?>','<?=$website['not_found_search_product']?>','<?=$website['readmore']?>');
								</script>
							</div>
						</div>
						<? } ?>	
					</div>
				</div>
			</div>
			<script>
				$(document).on('click', '.search-more', function () {
					$('.frm-search').submit(); 
				});
				$(document).on('click','.search-box .list-keyword > .item', function () { 
					var v = $(this).attr('data');
					var frm = $(this).parents('.frm-search');
					var t = frm.attr('type');
					if (t == 'm') {
						frm.find('#search-m').val(v);
					} else {
						frm.find('#search-pc').val(v);
					}
					frm.submit();
				});
				$(document).click(function(event) { 
					var $target = $(event.target);
					if(!$target.closest('.search-box').length && 
					$('.search-box').is(":visible")) {
						$('.search-box .list-keyword').hide();
					}        
				});
			</script>
			<div class="col-md-3 d-flex align-items-center content_right">
				<div class="contentsticky_cart nv-mr-10 nv-ml-auto">
					<a href="<?=site_url("gio-hang")?>">
						<div id="cart_block">
							<div class="header-cart d-flex align-items-center">
							<div class="site-header__cart">
								<span class="site-header__cart-icon" style="background: url(<?=IMG_URL?>icon-cart-header.png) no-repeat;"></span>
								<span id="_desktop_cart_count" class="site-header__cart-count">
									<span id="CartCount"><?=!empty($carts) ? '<span>'.$count_cart.'</span>' : '<span>0</span>' ?></span>
								</span>
							</div>
							<div class="nv-ml-20 d-none d-lg-block">
								<span class="title-cart"><?=$website['my_cart']?></span>
							</div>
							</div>
							<div id="_desktop_cart"><div id="cart-info" style=""></div></div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="header-bottom d-none d-md-block ">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-3 col-md-2">
				<div class="header-verticalmenu">
					<div stt="2" class="vertical_dropdown <?=($method == 'trang-chu')?'active':''?>">
						<div class="title_vertical d-flex align-items-center">
						<i class="zmdi zmdi-menu"></i>
						<span class="nv-ml-20 nv-ml-md-10 d-none d-lg-block"><?=$website['category']?></span>
						</div>
					</div>
					<div <?=($method == 'trang-chu')?'style="display:block;"':'style="display:none;"'?> id="_desktop_vertical_menu" class="vertical_menu has-showmore" data-count_showmore="9" data-count_showmore_lg="6" data-textshowmore="See More" data-textless="See Less">
						<ul class="site-nav" id="SiteNav">
							<? foreach($product_categories as $product_category) { ?>
							<li class="site-nav--has-dropdown hasMegaMenu center">
								<a href="<?=site_url("{$alias['product']}/{$product_category->{$prop['alias']}}")?>" title="<?=$product_category->{$prop_name}?>">
									<div class="icon_nav">
										<img class="img-fluid icon-menu" src="<?=IMG_URL.$product_category->alias?>.svg" alt="image">
									</div>
									<div class="group_title">
										<?=$product_category->{$prop_name}?>
										<div class="sub_title_nav limit-content-2-line"><?=$product_category->{$prop_desciption}?>...</div>
									</div>
								</a>
							</li>
							<? } ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-lg-9 col-md-10">
				<div class="contentsticky_menu">
					<nav id="AccessibleNav">
						<ul class="site-nav list--inline " id="SiteNav">
							<? foreach($product_categories as $product_category) { ?>
							<li class="site-nav--has-dropdown menu-item" aria-controls="SiteNavLabel-cosmetic">
								<a href="<?=site_url("{$alias['product']}/{$product_category->{$prop['alias']}}")?>" class="site-nav__link site-nav__link--main">
									<span><?=$product_category->{$prop_name}?></span>
								</a>
							</li>
							<? } ?>
							<li class="site-nav--has-dropdown menu-item" aria-controls="SiteNavLabel-contact">
								<a href="<?=site_url("{$alias['contact']}")?>" class="site-nav__link site-nav__link--main">
									<span><?=$menu['contact']?></span>
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
<?
	function gen_category_menu($product_categories, $obj,$device='',$alias,$prop) {
        foreach ($product_categories as $product_category) {
            $child_category_info = new stdClass();
            $child_category_info->parent_id = $product_category->id;
            $child_categories = $obj->m_product_category->items($child_category_info,1,null,null,'order_num','ASC');
            if ($device == '-menu-mobile'){
                $href = '#';
            } else {
                $href = site_url("san-pham").'?danh_muc='.$product_category->id;
            }
            if (!empty($child_categories)) {
                echo '<li class="subchild-item">
						<a href="'.site_url("{$alias['product']}/{$product_category->{$prop['alias']}}").'" class="site-nav__link site-nav__child-link site-nav__child-link--parent show-more-child">
						<span>'.$product_category->{$prop['name']}.'</span>
						</a>
                        <ul class="subchild child">';
                        gen_category_menu($child_categories, $obj,$device,$alias,$prop);
                echo '	</ul>
                    </li>';
            } else {
                echo '<li class="subchild-item"><a href="'.site_url("{$alias['product']}/{$product_category->{$prop['alias']}}").'" class="site-nav__link site-nav__child-link site-nav__child-link--parent"><span>'.$product_category->{$prop['name']}.'</span></a></li>';
            }
        }
    }
?>