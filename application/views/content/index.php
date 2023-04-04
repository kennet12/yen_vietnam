<div class="page-width">
	<div class="container">
		<div class="row">
			<div class="sidebar sidebar-article col-lg-3 col-md-4 flex-xs-unordered">
				<div id="shopify-section-nov-sidebar-article" class="shopify-section">
					<div class="sidebar-block categories__sidebar sidebar-block__1 ">
						<div class="title-block"><span><?=$menu['categories']?></span></div>
						<div class="block__content">
							<? foreach($categories as $cate) { ?>
							<div class="cateTitle">
								<a class="cateItem " href="<?=site_url("{$alias['new']}/{$cate->{$prop['alias']}}")?>"><?=$cate->{$prop['name']}?></a>
							</div>
							<? } ?>
						</div>
					</div>
					<div class="sidebar-block recentpost__sidebar sidebar-block__2 ">
						<div class="title-block">
							<span><?=$website['recent_post']?></span>
						</div>
						<div class="block__content">
							<div>
								<? foreach($recent_post as $post) { $thumb = explode('/',$post->thumbnail);?>
								<div class="post_groups d-flex">
									<div class="post__image">
										<a href="<?=site_url("{$alias['new']}/{$post->{$prop['category_alias']}}/{$post->{$prop['alias']}}")?>" class="article__list-image-container">
											<img class="article__list-image" src="<?=BASE_URL."/images/thumb/{$post->id}/thumbnail/".end($thumb)?>" alt="<?=$post->{$prop['title']}?>">
										</a>
									</div>
									<div class="post-item">
										<div class="post__title limit-content-2-line"><a href="<?=site_url("{$alias['new']}/{$post->{$prop['category_alias']}}/{$post->{$prop['alias']}}")?>" title="<?=$post->{$prop['title']}?>"><?=$post->{$prop['title']}?></a></div>
									<div class="post__info">
										<span class="post__date">
										<i class="zmdi zmdi-calendar-note"></i><time datetime="2021-01-16T04:34:57Z"><?=date($website['format_date'],strtotime($post->created_date))?></time>
										</span>
									</div>
									</div>
								</div>
								<? } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="blog_groups col-lg-9 col-md-8 flex-xs-first">
				<div id="shopify-section-blog-template" class="shopify-section">
					<div class="blog--list blog--grid-view">
						<div class="title_block"><?=$title?></div>
						<div class="row">
							<? foreach($items as $item) {?>
							<div class="article--listing col-lg-12 mb-100">
								<div class="article__image">
									<a href="<?=site_url("{$alias['new']}/{$item->{$prop['category_alias']}}/{$item->{$prop['alias']}}")?>" class="article__list-image-container w-100">
										<img class="article__list-image w-100" src="<?=BASE_URL.$item->thumbnail?>" alt="<?=$item->{$prop['title']}?>">
									</a>
								</div>
								<div class="article-body">
									<h2 class="article__title"><a href="<?=site_url("{$alias['new']}/{$item->{$prop['category_alias']}}/{$item->{$prop['alias']}}")?>"><?=$item->{$prop['title']}?></a></h2>
									<div class="article__info">
										<span class="article__date">
											<i class="zmdi zmdi-calendar-note"></i><time datetime="2021-01-16T04:34:57Z"><?=date($website['format_date'],strtotime($item->created_date))?></time>
										</span>
										<span class="article__author">
											<i class="zmdi zmdi-account"></i> Nguyên Anh Fruit
										</span>
									</div>
									<div class="article__excerpt">
										<?=character_limiter(strip_tags($item->{$prop['description']}),300)?>
									</div>
									<a href="<?=site_url("{$alias['new']}/{$item->{$prop['category_alias']}}/{$item->{$prop['alias']}}")?>" class="article__readmore text-uppercase"><?=$website['readmore']?></a>
								</div>
							</div>
							<? } ?>
						</div>
						<?=$pagination?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>