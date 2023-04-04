<div class="about-us cluster">
	<div class="container">
		<h2 class="title">Hỏi - đáp: <?=$item->title?></h2>
		<div class="content">
			<?=$item->content?>
		</div>
		<div class="qa-related">
			<h5 class="title-sub">Các câu hỏi khác</h5>
			<ul class="qa-related-list">
				<? foreach($related_items as $related_item) { ?>
				<li class="item">
					<a href="<?=site_url("hoi-dap/{$related_item->alias}")?>"><i class="fas fa-angle-double-right"></i> <?=$related_item->title?></a>
				</li>
				<? } ?>
			</ul>
		</div>
	</div>
</div>