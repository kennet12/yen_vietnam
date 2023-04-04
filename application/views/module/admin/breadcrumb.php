<? if ($this->util->slug($this->router->fetch_class()) != "home" && !empty($breadcrumb)) { ?>
<div class="breadcrumbs">
	<div class="container-fluid-1">
		<ul class="breadcrumb clearfix" itemscope itemtype="http://schema.org/BreadcrumbList">
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="<?=BASE_URL?>" class="active"><span itemprop="name"><i class="fa fa-home"></i> Trang chủ</span></a></li>
			<?
				foreach ($breadcrumb as $k => $v) {
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'.$k.'" href="'.$v.'" class="active"><span itemprop="name">'.$k.'</span></a></li>';
				}
			?>
		</ul>
	</div>
</div>
<? } ?>