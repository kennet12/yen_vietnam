<article class="page-width" itemscope itemtype="http://schema.org/Article">
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
			<div class="col-lg-9 col-md-8 flex-xs-first blog_detail ">
				<div id="shopify-section-article-template" class="shopify-section">
					<div class="post">
						<h1 class="article__title"><?=$item->{$prop['title']}?></h1>
						<div class="article__thumnail">
							<img class="img-fluid" src="<?=BASE_URL.$item->thumbnail?>" alt="<?=$item->{$prop['title']}?>"/>
						</div>
					</div>
					<div class="rte" itemprop="articleBody">
						<?=$item->{$prop['content']}?>
					</div>
					<div class="article__info d-flex align-items-center">
						<div class="blog_cs">
							<span class="article__date">
							<i class="zmdi zmdi-calendar-note"></i><time datetime="2021-01-16T04:34:57Z"><?=date($website['format_date'],strtotime($item->created_date))?></time>
							</span>
							<span class="article__author"><i class="zmdi zmdi-account"></i>Nguyên Anh Fruit</span>
						</div>
						<div class="blog_share">
							<div class="article__share btn-group">
								<a class="dropdown-toggle" href="#" id="dropdownsharebutton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="zmdi zmdi-share"></i><span>Share</span>
								</a>
								<? $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
								<div class="dropdown-menu" aria-labelledby="dropdownsharebutton">
									<a target="_blank" href="//www.facebook.com/sharer.php?u=<?=$actual_link?>" class="btn--share share-facebook" title="Share on Facebook">
									<svg aria-hidden="true" focusable="false" role="presentation" class="icon icon-facebook" viewBox="0 0 20 20">
										<path fill="#444" d="M18.05.811q.439 0 .744.305t.305.744v16.637q0 .439-.305.744t-.744.305h-4.732v-7.221h2.415l.342-2.854h-2.757v-1.83q0-.659.293-1t1.073-.342h1.488V3.762q-.976-.098-2.171-.098-1.634 0-2.635.964t-1 2.72V9.47H7.951v2.854h2.415v7.221H1.413q-.439 0-.744-.305t-.305-.744V1.859q0-.439.305-.744T1.413.81H18.05z"></path>
									</svg>
									<span class="share-title" aria-hidden="true">Facebook</span>
									<span class="visually-hidden">Share on Facebook</span>
									</a>
									<a target="_blank" href="//twitter.com/share?text=Lorem%20ipsum%20dolor%20sit%20amet%20consectetuer&amp;url=<?=$actual_link?>" class="btn--share share-twitter" title="Tweet on Twitter">
									<svg aria-hidden="true" focusable="false" role="presentation" class="icon icon-twitter" viewBox="0 0 20 20">
										<path fill="#444" d="M19.551 4.208q-.815 1.202-1.956 2.038 0 .082.02.255t.02.255q0 1.589-.469 3.179t-1.426 3.036-2.272 2.567-3.158 1.793-3.963.672q-3.301 0-6.031-1.773.571.041.937.041 2.751 0 4.911-1.671-1.284-.02-2.292-.784T2.456 11.85q.346.082.754.082.55 0 1.039-.163-1.365-.285-2.262-1.365T1.09 7.918v-.041q.774.408 1.773.448-.795-.53-1.263-1.396t-.469-1.864q0-1.019.509-1.997 1.487 1.854 3.596 2.924T9.81 7.184q-.143-.509-.143-.897 0-1.63 1.161-2.781t2.832-1.151q.815 0 1.569.326t1.284.917q1.345-.265 2.506-.958-.428 1.386-1.732 2.18 1.243-.163 2.262-.611z"></path>
									</svg>
									<span class="share-title" aria-hidden="true">Tweeter</span>
									<span class="visually-hidden">Tweet on Twitter</span>
									</a>
									<a target="_blank" href="//pinterest.com/pin/create/button/?url=<?=$actual_link?>" class="btn--share share-pinterest" title="Pin on Pinterest">
									<svg aria-hidden="true" focusable="false" role="presentation" class="icon icon-pinterest" viewBox="0 0 20 20">
										<path fill="#444" d="M9.958.811q1.903 0 3.635.744t2.988 2 2 2.988.744 3.635q0 2.537-1.256 4.696t-3.415 3.415-4.696 1.256q-1.39 0-2.659-.366.707-1.147.951-2.025l.659-2.561q.244.463.903.817t1.39.354q1.464 0 2.622-.842t1.793-2.305.634-3.293q0-2.171-1.671-3.769t-4.257-1.598q-1.586 0-2.903.537T5.298 5.897 4.066 7.775t-.427 2.037q0 1.268.476 2.22t1.427 1.342q.171.073.293.012t.171-.232q.171-.61.195-.756.098-.268-.122-.512-.634-.707-.634-1.83 0-1.854 1.281-3.183t3.354-1.329q1.83 0 2.854 1t1.025 2.61q0 1.342-.366 2.476t-1.049 1.817-1.561.683q-.732 0-1.195-.537t-.293-1.269q.098-.342.256-.878t.268-.915.207-.817.098-.732q0-.61-.317-1t-.927-.39q-.756 0-1.269.695t-.512 1.744q0 .39.061.756t.134.537l.073.171q-1 4.342-1.22 5.098-.195.927-.146 2.171-2.513-1.122-4.062-3.44T.59 10.177q0-3.879 2.744-6.623T9.957.81z"></path>
									</svg>
									<span class="share-title" aria-hidden="true">Pin it</span>
									<span class="visually-hidden">Pin on Pinterest</span>
									</a>
								</div>
							</div>
							<div class="article__print">
								<a href="javascript:window.print()"><i class="zmdi zmdi-print"></i><span>Print</span></a>
							</div>
						</div>
					</div>
				</div>
				<div class="BlogRelated">
					<div class="title_block"><?=$website['related_new']?></div>
					<div class="block__content">
					<div class="blog--list blog--grid-view">
						<div class="owl-carousel proLoading owl-drag" data-autoplay="true" data-autoplayTimeout="6000" data-items="3" data-margin="30" data-nav="false" data-dots="true" data-loop="true" data-items_tablet="2" data-items_mobile="1">
							<? foreach($related_items as $related_item) { $thumb = explode('/',$related_item->thumbnail);?>
							<div class="item">
								<div class="article--listing">
								<div class="article__image">
									<a href="<?=site_url("{$alias['new']}/{$related_item->{$prop['category_alias']}}/{$related_item->{$prop['alias']}}")?>" class="article__list-image-container">
										<img class="article__list-image img-fluid" src="<?=BASE_URL."/images/thumb/{$related_item->id}/thumbnail/".end($thumb)?>" alt="<?=$related_item->{$prop['title']}?>">
									</a>
								</div>
								<div class="article-body">
									<h2 class="article__title"><a href="<?=site_url("{$alias['new']}/{$related_item->{$prop['category_alias']}}/{$related_item->{$prop['alias']}}")?>"><?=$related_item->{$prop['title']}?></a></h2>
									<div class="article__excerpt">
									<?=character_limiter(strip_tags($related_item->{$prop['description']}),110)?>
									</div>
								</div>
								</div>
							</div>
							<? } ?>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</article>