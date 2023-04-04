<!DOCTYPE html>
<html lang="<?=!isset($_COOKIE['nguyenanh_lang'])?'vi-VN':'en-US';?>" translate="no">
	<head>
		<? require_once(APPPATH."views/module/head_cdn.php"); ?>
	</head>
	<?
		$fetch_class = $this->util->slug($this->router->fetch_class());
	?>
	<body class="home-template has-fixed-navbar">	
		<!--header-->
		<div id="shopify-section-nov-header" class="shopify-section">
			<div data-section-id="nov-header" data-section-type="header-section">
				<header class="site-header sticky-menu" style="height: auto;">
				<? require_once(APPPATH."views/module/header.php"); ?>	
				</header>
				<div id="header-sticky" class="d-none d-md-block" style="background-color: #ffffff;">
					<div class="container">
						<div class="row align-items-center justify-content-between">
							<div class="contentstickynew_logo col-xl-2 col-lg-2 col-md-2"></div>
							<div class="contentstickynew_menu col-xl-8 col-lg-8 col-md-8 text-center"></div>
							<div class="contentstickynew_cart col-xl-2 col-lg-2 col-md-2 d-flex justify-content-end"></div>
						</div>
					</div>
				</div>
			</div>
			<style>
			.header-top {
				background-color: #ffffff;
			}
			</style>
		</div>
		<? require_once(APPPATH."views/module/notification.php"); ?>
		<div id="<?=$this->util->slug($this->router->fetch_class())?>">
			<main class="main-content" id="MainContent">
      			<section class="page-container drawer-page-content" id="PageContainer">
				  	<?
					if (!in_array($fetch_class,array('tra-cuu-don-hang'))) 
					require_once(APPPATH."views/module/nav_account.php"); 
					?>
					<?=$content?>
				</section>
			</main>
		</div>
		<div id="shopify-section-nov-footer" class="shopify-section nov-footer">
			<footer>
				<? require_once(APPPATH."views/module/footer.php"); ?>
			</footer>
		</div>
		<? require_once(APPPATH."views/module/menu_mobile.php"); ?>
	</body>
</html>

