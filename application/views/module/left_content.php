<?
	$products = $this->m_product->items(null,1);
?>
<div style="margin-bottom: 40px ">
	<div class="wrap-box">
		<div class="box-title">
			<div class="title">
				Tìm trên Facebook
			</div>
		</div>
		<div class="box">
			<div class="fb-page" data-href="<?=$setting->facebook_url?>" data-height="300" data-width="360" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
			<blockquote cite="https://www.facebook.com/facebook" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote>
			</div>
		</div>
	</div>
	<div class="wrap-box">
		<div class="box-title">
			<div class="title">
				Danh sách sản phẩm
			</div>
		</div>
		<div class="box">
			<? foreach ($products as $product) { ?>
			<div class="box-item limit-content-1-line">
				<a href="<?=site_url("san-pham/{$this->m_product_categories->load($product->category_id)->alias}/{$product->alias}")?>" class="transition"><?=$product->title?></a>
			</div>
			<? } ?>
		</div>
	</div>
	<div class="wrap-box">
		<div class="box-title">
			<div class="title">
				Danh mục sản phẩm
			</div>
		</div>
		<div class="box">
			<? foreach ($product_categories as $product_categorie) { ?>
			<div class="box-item limit-content-1-line">
				<a href="<?=site_url("san-pham/{$product_categorie->alias}")?>" class="transition"><?=$product_categorie->name?></a>
			</div>
			<? } ?>
		</div>
	</div>
</div>