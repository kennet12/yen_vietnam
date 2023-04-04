<? $user = $this->session->userdata('user'); $usd = $this->m_setting->load(1)->usd;?>
<div class="page-width container">
   <div id="shopify-section-nov-product-template" class="shopify-section">
      <div class="product-template__container tabdesc" itemscope itemtype="http://schema.org/Product" id="ProductSection-nov-product-template" data-section-id="nov-product-template" data-enable-history-state="true" data-type="product-template" data-wishlist-product>
         <meta itemprop="name" content="<?=$item->{$prop['title']}?>">
         <meta itemprop="url" content="<?=site_url($item->{$prop['alias']})?>">
         <meta itemprop="image" content="<?=$item->photo[0]->file_path?>">
         <div class="TopContent mb-100 pb-xs-60">
            <div class="product-single row position-static">
               <div class="col-md-5 col-xs-12 position-static">
                  <div class="product-single__photos block_img_sticky">
                     <div class="proFeaturedImage">
                        <div class="block_content d-flex">
                           <img id="ProductPhotoImg" class="img-fluid <?=(!$this->util->detect_mobile())? 'image-zoom':'' ?>  img-responsive" src="<?=$item->photo[0]->file_path?>" alt="<?=$item->{$prop['title']}?>"/>
                        </div>
                     </div>
                     <div id="productThumbs" class="mt-10">
                        <div class="thumblist" data-pswp-uid="1">
                           <div class="owl-carousel owl-theme" data-autoplay="false" data-autoplayTimeout="6000" data-items="5" data-margin="10" data-nav="false" data-dots="false" data-loop="false" data-items_tablet="4" data-items_mobile="5">
                              <? foreach($item->photo as $filepath) {
                                 $thumbnail = str_replace($item->code_product,"{$item->code_product}/thumb",$filepath->file_path) ?>
                              <div class="thumbItem">
                                 <a href="javascript:void(0)" data-image="<?=$filepath->file_path?>" data-zoom-image="<?=$filepath->file_path?>" class="product-single__thumbnail">
                                 <img class="detail-img" src="<?=$thumbnail?>" alt="<?=$item->{$prop['title']}?>">
                                 </a>
                              </div>
                              <? } ?>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
			   <? 
				$i=0;
				$check_i = -1;
				$check_j = -1;
				$find_price = 0;
				$price = 0;
				$sale = 0;
				$price_temp = 0;
				$quantity = 0;
				$str_type = '<div class="pattern">';
				$str_typename = '';
				$str_subtypename = '';

				if (!empty($item->typename))
					$str_typename .= '<div class="pattern-label">'.$item->typename.':</div><ul class="list clearfix">';
				if (!empty($item->subtypename))
					$str_subtypename .= '<div class="pattern-label">'.$item->subtypename.':</div>';
					
				foreach($item->product_type as $product_type) {
					$type_quantity 		= json_decode($product_type->quantity);
					$type_price 		= json_decode($product_type->price);
					$type_subtypename 	= json_decode($product_type->subtypename);
					$type_sale 			= json_decode($product_type->sale);
					$type_photo 		= json_decode($product_type->photo);
					$c = count($type_quantity);
					//
					$str_subtypename_child = '';
					for ($j=0;$j<$c;$j++) {
						if ($type_quantity[$j]!=0&&$find_price==0) {
							$check_i 	= $i;
							$check_j 	= $j;
							$price 		= $type_price[$j];
							$sale 		= $type_sale[$j];
							$quantity 	= $type_quantity[$j];
							$photo 		= !empty($type_photo[$j])?$type_photo[$j]:'';
							$find_price = 1;
						}
						//
						if (!empty($item->subtypename)) {
							$str_subtypename_child .= 	'<li class="item get-item typename-'.$this->util->slug($type_subtypename[$j]).'" data-src="'.$photo.'" qty="'.$type_quantity[$j].'">';
								$str_subtypename_child .= 	'<label class="radio-container">';
									$str_subtypename_child .= 	'<div class="pattern-info">';
										$str_subtypename_child .= 	'<div class="type">'.$type_subtypename[$j].'</div>';
                              $str_subtypename_child .= 	'<div class="sub-price" price="'.$type_price[$j].'"></div>';
									$str_subtypename_child .= 	'</div>';
									$checked_subtype = ($check_i==$i&&$check_j==$j)?'checked="checked"':'';
									// if ($type_quantity[$j] != 0)
									$str_subtypename_child .= 	'<input type="radio" '.$checked_subtype.' name="subtypename" sale="'.$type_sale[$j].'" value="'.$type_subtypename[$j].'">';
									$str_subtypename_child .= 	'<span class="checkmark"></span>';
								$str_subtypename_child .= 	'</label>';
							$str_subtypename_child .= 	'</li>';
						}
					}
					if (!empty($item->subtypename)) {
						$display = ($check_i==$i)?'style="display:block"':'style="display:none"';
						$str_subtypename .= '<ul '.$display.' class="list clearfix p-subtypename subtypename-'.$i.'">';
						$str_subtypename .= $str_subtypename_child;
						$str_subtypename .= '</ul>';
					}
					//
					if (!empty($item->typename)) {
						if ($quantity != 0){
							$photo 		= !empty($type_photo[0])?$type_photo[0]:'';
							if (empty($item->subtypename))
								$str_typename .= '<li class="item get-item typename-'.$this->util->slug($product_type->typename).'" data-src="'.$photo.'"  qty="'.$type_quantity[0].'">';
							else
								$str_typename .= '<li class="item get-item typename-'.$this->util->slug($product_type->typename).'" data-src=""  qty="'.$type_quantity[0].'">';
						}
						else 
						$str_typename .= 	'<li class="item get-item" data-src=""  qty="'.$type_quantity[0].'">';
							$str_typename .= 	'<label class="radio-container">';
								$str_typename .= 	'<div class="pattern-info">';
									$str_typename .= 	'<div class="type">'.$product_type->typename.'</div>';
									$checksale = "";
									if ($product_type->subtypename=='""') {
                              $str_typename .= 	'<div class="sub-price" price="'.$type_price[0].'"></div>';
										$checksale = 'sale="'.$type_sale[0].'"';
									}
								$str_typename .= 	'</div>';
								$checked = ($check_i==$i)?'checked="checked"':'';
									// if ($quantity != 0)
								$str_typename .= 	'<input stt="'.$i.'" class="p-typename" type="radio" '.$checked.' '.$checksale.'  name="typename" value="'.$product_type->typename.'">';
								$str_typename .= 	'<span class="checkmark"></span>';
							$str_typename .= 	'</label>';
						$str_typename .= 	'</li>';
					}
					//
				$i++;}
				if ($price==0) {
					$price = $type_price[0];
					$sale = $type_sale[0];
				} 
				if (!empty($item->typename)) $str_typename .= '</ul>';
				$str_type .= $str_typename.$str_subtypename.'</div>';
			?>
               <div class="block_information position-static col-md-7 col-xs-12 mt-xs-30">
                  <div class="info_content">
                  <? $price_product = $this->util->round_number($price*(1-($sale*0.01)),1000);?>
                     <h1 itemprop="name" class="product-single__title"><?=$item->{$prop['title']}?></h1>
                     <div class="product-single__meta">
                        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                           <meta itemprop="priceCurrency" content="VND">
                           <link itemprop="availability" href="http://schema.org/InStock">
                           <?
                              if ($item->rating_cmt != '0'){
                                 $point = round($item->rating_point/$item->rating_cmt,1);
                              } else {
                                 $point = 0;
                              }
                              $str_rating = "";
                              for($j=1;$j<=5;$j++) {
                                 if ($j <= $point) {
                                 $str_rating .= '<i class="start-icon zmdi zmdi-star"></i>';
                                 } else {
                                 if (($point > ($j-1)) && $point < $j) {
                                    $str_rating .= '<i class="start-icon zmdi zmdi-star"></i>';
                                 } else {
                                    $str_rating .= '<i class="start-icon zmdi zmdi-star-outline"></i>';
                                 }
                                 }
                              }
                           ?>
                           <div class="group-reviews has-border d-flex align-items-center pb-25" style="position:relative;">
                              <div class="detail-reviews">
                                 <span class="shopify-product-reviews-badge">
                                    <?=$str_rating?>
                                 </span>
                              </div>
                              <? if(!empty($user)){ ?>
                              <div class="get-affiliate copy"><i class="zmdi zmdi-link"></i> Affiliate : <?=$item->affiliate?>%</div>
                              <script>
                                 $(document).on('click','.copy',function(e){
                                    navigator.clipboard.writeText(link);
                                    $('.get-affiliate').addClass('copied');
                                    $('.get-affiliate').removeClass('copy');
                                    $(this).html('Copied'); 
                                    $.ajax({
                                       url: '<?=site_url('call-service/create-affiliate')?>',
                                       type: 'POST',
                                       dataType: 'html',
                                    })
                                 })
                              </script>
                              <? } ?>
                           </div>
                           <div class="available_product d-flex align-items-center">
                              <div class="available_name control-label"><?=$website['status']?>: </div>
                              <? if (!empty($quantity)) { ?>
                              <span class="product__available">
                                 <span class="in-stock"><?=$website['in_stock']?></span>
                              </span>
                              <? } else { ?>
                              <span class="product__available">
                                 <span class="out-stock"><?=$website['out_stock']?></span>
                              </span>
                              <? } ?>
                           </div>
                           <div class="group-single__sku has-border">
                              <p itemprop="sku" class="product-single__sku">
                                 <span class="label control-label">SKU:</span>
                                 <span class="label-sku"><?=$item->code_product?></span>
                              </p>
                              <p itemprop="cat" class="product-single__cat"><span class="label control-label"><?=$website['category']?>:</span>
                                 <? foreach($array_cate as $cate) { ?>
                                 <a href="<?=site_url("{$alias['product']}/{$cate->{$prop['alias']}}")?>"><?=$cate->{$prop['name']}?></a>,
                                 <? } ?>
                              </p>
                              <? if (!empty($item->brand)) { $brand = $this->m_brand->load($item->brand)?>
                              <p itemprop="cat" class="product-single__cat"><span class="label control-label"><?=$website['brand']?>:</span>
                                 <a href="<?=site_url("{$alias['product']}")."?constraint={$brand->alias}"?>" title="<?=$brand->name?>"><?=$brand->name?></a>
                              </p>
                              <? } ?>
                              <p itemprop="origin" class="product-single__sku">
                                 <span class="label control-label"><?=$website['origin']?>:</span>
                                 <span class="label"><?=$item->{$prop['origin']}?></span>
                              </p>
                              <p style="display:none;" itemprop="price" class="product-single__sku">
                                 <span class="label"><?=$price_product?></span>
                              </p>
                           </div>
                           <div class="product-single__shortdes mb-20" itemprop="description">
                              <p><?=$website['return_product_note']?></p>
                           </div>
                        </div>
                     </div>
                     <p class="product-single__price product-single__price-nov-product-template d-flex align-items-center" price-pitemprop="description">
                        <span class="product-price__price product-price__price-nov-product-template product-price__sale product-price__sale--single">
                           <span id="ProductPrice-nov-product-template" class="money mr-10">
                              <span class="money" itemprop="price" content="<?=number_format($price_product,0,',','.')?>"><?=number_format($price_product,0,',','.')?></span>
                           </span>
                        </span>
                        <? if (!empty($sale)) {?>
                        <s id="ComparePrice-nov-product-template"><span class="money"><?=number_format($price,0,',','.');?></span></s>
                        <? } ?>
                     </p>
                     <? if(isset($_COOKIE['nguyenanh_lang'])) { ?>
                     <div class="product__price">
                        <strong>
                        <span class="product-price__price product-price__sale">
                           <span class="money" style="color:#333;">USD: $<?=round(($price*(1-($sale*0.01)))/$usd,1)?></span>
                        </span>
                        <? if ($sale!=0) { ?>
                        <s class="product-price__price" style="margin-left:5px;color: #e0e0e0;"><span class="money">$<?=round(($price*$sale*0.01)/$usd,1)?></span></s>
                        <? } ?>
                        </strong>
                     </div>
                     <? } ?>
                     <div class="selectorVariants">
                        <?=$str_type?>
                        <div class="group-quantity">
                           <span class="control-label"><?=$website['quantity']?>:</span>
                           <div class="product-form__item product-form__item--quantity align-items-center mb-30">
                              <label for="Quantity" class="quantity-selector"></label>
                              <div class="quick_view_qty">
                                 <a class="quick_view-qty quick_view-qty-minus">-</a>
                                 <input type="number" id="Quantity" name="quantity" value="1" min="1" max=<?=$quantity?> step="1" class="quantity-selector product-form__input" pattern="[0-9]*">
                                 <a class="quick_view-qty quick_view-qty-plus">+</a>
                              </div>
                              <div class="productWishList">
                                 <?
                                 $status = 0;
                                    $cls = 'removed-wishlist';
                                    if(!empty($user)) {
                                       if (strpos($user->like_product,'"'.$item->id.'"') !== false) {
                                          $status = 2;
                                          $cls = 'added-wishlist';
                                       } else 
                                          $status = 1;
                                    }
                                 ?>
                                 <a href="#" status='<?=$status?>' id_item='<?=$item->id?>' class="<?=$cls?> wishlist btnProductWishlist btnProductWishlist-<?=$item->id?>">
                                 <i class="zmdi zmdi-favorite"></i>
                                 <span class="wishlist-text"><?=$website['add_to_wishlist']?></span>
                                 </a>
                              </div>
                           </div>
                           <? if(!empty($quantity)) { ?>
                           <div class="product_option_sub">
                              <div class="product-form__item product-form__item--submit">
                                 <button title="<?=$item->alias?>" class="enable-cart btnAddToCart btn product-form__cart-submit mb-15">
                                 <span id="AddToCartText">
                                    <?=$website['add_to_cart']?>
                                 </span>
                                 </button>
                              </div>
                           </div>
                           <script type="text/javascript">
                              addToCart('enable-cart','<?=$website['add_to_cart']?>','<?=$website['mes_add_to_cart']?>','<?=$website['btn_go_to_cart']?>','<?=$website['btn_lose']?>','<?=$website['check_out']?>');
                           </script>
                           <? } ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="product-single__tabs mt-100 mt-lg-60">
               <div class="block_nav d-flex justify-content-center">
                  <ul class="nav nav-tabs">
                  <li><a class="active" href="#proTabs1" data-toggle="tab"><?=$website['description']?></a></li>
                  <li><a href="#tabCustom-3" data-toggle="tab">Video</a></li>
                  </ul>
               </div>
               <div class="tab-content">
                  <div class="tab-pane active" id="proTabs1">
                     <article><?=$item->{$prop['content']}?></article>
                  </div>
                  <div class="tab-pane tab-video-youtube" id="tabCustom-3">
                     <? if(!empty($item->video)){ ?>
                     <div class="image-youtube">
                        <div class="bg" key="<?=$this->util->get_key_youtube($item->video)?>">
                           <i class="zmdi zmdi-youtube-play"></i>
                        </div>
                        <img  src="https://img.youtube.com/vi/<?=$this->util->get_key_youtube($item->video)?>/maxresdefault.jpg" alt="">
                     </div>
                     <? } ?>
                  </div>
               </div>
            </div>
            <div class="BottomContent ProductRelated block_margin" style="margin-top: 50px;">
               <div class="block_padding">
                  <div class="title_block mb-0">
                     <span><?=$website['related_product']?></span>
                  </div>
                  <div class="block__content">
                     <div class="grid grid--view-items">
                        <div class="owl-relatedproduct owl-carousel owl-drag" data-autoplay="false" data-autoplayTimeout="6000" data-items="5" data-nav="true" data-dots="false" data-loop="true" data-items_tablet="3" data-items_mobile="2" data-margin="30">
                        <?
                        $c = count($related_products);
                        for ($i=0;$i<$c;$i++) {
                           if(!empty($related_products[$i]->thumbnail)) {
                           $price = $related_products[$i]->price;
                           $price_sale = $price*(1-($related_products[$i]->sale*0.01));
                           $typename = !empty($related_products[$i]->typename)?' - '.$related_products[$i]->typename:'';
                           if ($related_products[$i]->rating_cmt != '0'){
                              $point = round($related_products[$i]->rating_point/$related_products[$i]->rating_cmt,1);
                           } else {
                              $point = 0;
                           }

                           $product_link = site_url($related_products[$i]->{$prop['alias']});
                           if(!empty($related_products[$i]->typename))
                              $product_link .= "?loai={$this->util->slug($related_products[$i]->typename)}";
                        ?>
                           <div class="item item-product">
                              <div class="thumbnail-container has-multiimage has_variants">
                                 <a class="w-100" href="<?=$product_link?>">
                                    <img class="w-100 img-fluid product__thumbnail" src="<?=$related_products[$i]->thumbnail?>" alt="<?=$related_products[$i]->{$prop['title']}?>">
                                 </a>
                                 <div class="badge_sale">
                                    <? if ($related_products[$i]->sale!=0) { ?>
                                       <span class="badge badge--sale-pt">-<?=$related_products[$i]->sale?>%</span>
                                    <? } ?>
                                 </div>
                              </div>
                              <div class="product__info text-center">
                                 <div class="product__title">
                                    <a class="limit-content-1-line" href="<?=$product_link?>"><?=$related_products[$i]->{$prop['title']}?></a>
                                    <div class="product__typename"><?=$related_products[$i]->typename?></div>
                                 </div>
                                 <div class="product__review">
                                    <div class="rating"><span class="shopify-product-reviews-badge" data-id="6153538011328"></span></div>
                                 </div>
                                 <div class="product__price">
                                    <span class="product-price__price product-price__sale">
                                    <span class="money"><?=number_format($this->util->round_number($price_sale,1000),0,',','.')?></span>
                                    </span>
                                    <? if ($related_products[$i]->sale!=0) { ?>
                                    <s class="product-price__price"><span class="money"><?=number_format($this->util->round_number($price,1000),0,',','.')?></span></s>
                                    <? } ?>
                                 </div>
                                 <? if(isset($_COOKIE['nguyenanh_lang'])) { ?>
                                 <div class="product__price">
                                    <span class="product-price__price product-price__sale">
                                    <span class="money">USD: $<?=round($price_sale/$usd,1)?></span>
                                    </span>
                                    <? if ($related_products[$i]->sale!=0) { ?>
                                    <s class="product-price__price"><span class="money">$<?=round($price/$usd,1)?></span></s>
                                    <? } ?>
                                 </div>
                                 <? } ?>
                                 <a class="btn btnAddToCart btnChooseVariant" href="<?=$product_link?>">
                                    <i class="zmdi zmdi-zoom-in"></i>
                                    <span><?=$website['view_detail']?></span>
                                 </a>
                              </div>
                           </div>
                           <? } } ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   addLike('.btnProductWishlist');
   <? if (!empty($_GET['loai'])) { ?>
      $(document).ready(function (e) {
         var item = $('.<?="typename-{$_GET['loai']}"?>');
         if(item[0] != undefined) {
            $(item).find('input[type=radio][name=typename]').attr('checked',true);
            let price = item.find('.sub-price').attr('price');
            let max_qty = parseInt(item.attr('qty'));
            let data_src = item.attr('data-src');
            if (data_src!=''){
               data_src = '<?=BASE_URL?>'+data_src;
               $('#ProductPhotoImg').attr('src',data_src);
               $('.zoomImg').attr('src',data_src);
            }
            if(price != undefined) { 
               let sale = parseFloat(item.find('input').attr('sale'));
               price = parseFloat(price);
               $('#Quantity').attr('max',max_qty);
               $('#Quantity').val(1);
               let = money = formatDollar(price*(1-(sale*0.01)));
               if(max_qty!=0){
                  $('.btnAddToCart').removeClass('disable-cart');
                  $('.btnAddToCart').addClass('enable-cart');
                  $('.product__available').html('<span class="in-stock"><?=$website['in_stock']?></span>');
               }else{
                  $('.btnAddToCart').removeClass('enable-cart');
                  $('.btnAddToCart').addClass('disable-cart');
                  $('.product__available').html('<span class="out-stock"><?=$website['out_stock']?></span>');
               }
               let str_price = '<span class="product-price__price product-price__price-nov-product-template product-price__sale product-price__sale--single">';
                     str_price += '<span id="ProductPrice-nov-product-template" itemprop="price" content="'+money+'" class="money mr-10">';
                        str_price += '<span class="money">'+money+'</span>';
                     str_price += '</span>';
                  str_price += '</span>';
               if (sale!=0) 
                  str_price += '<s id="ComparePrice-nov-product-template"><span class="money">'+formatDollar(price)+'</span></s>';
               $('.price-product-detail').html(str_price);
            }
         }
      });
   <? } ?>

   $(document).on('click','.quick_view-qty',function() {
      var opera = $(this).html();
      var val = parseInt($('#Quantity').val());
      let min = parseInt($('#Quantity').attr('min'));
      let max = parseInt($('#Quantity').attr('max'));
      if (opera == '+'){
         if (val < max)
         val += 1;
      } else {
         if (val > 1)
         val -= 1;
      }
      $('#Quantity').val(val);
   });

   $(document).on('change','#Quantity',function() {
      let min = parseInt($(this).attr('min'));
      let max = parseInt($(this).attr('max'));
      let val = parseInt($(this).val());
      if (val<min)$(this).val(min);else if (val>max)$(this).val(max);
   });

   $(document).on('click','.get-item',function() {
      let price = $(this).find('.sub-price').attr('price');
      let max_qty = parseInt($(this).attr('qty'));
      let data_src = $(this).attr('data-src');
      if (data_src!=''){
         data_src = '<?=BASE_URL?>'+data_src;
         $('#ProductPhotoImg').attr('src',data_src);
         $('.zoomImg').attr('src',data_src);
      }
      if(price != undefined) { 
         let sale = parseFloat($(this).find('input').attr('sale'));
         price = parseFloat(price);
         $('#Quantity').attr('max',max_qty);
         $('#Quantity').val(1);
         let = money = formatDollar(price*(1-(sale*0.01)));
         if(max_qty!=0){
            $('.btnAddToCart').removeClass('disable-cart');
            $('.btnAddToCart').addClass('enable-cart');
            $('.product__available').html('<span class="in-stock"><?=$website['in_stock']?></span>');
         }else{
            $('.btnAddToCart').removeClass('enable-cart');
            $('.btnAddToCart').addClass('disable-cart');
            $('.product__available').html('<span class="out-stock"><?=$website['out_stock']?></span>');
         }
         let str_price = '<span class="product-price__price product-price__price-nov-product-template product-price__sale product-price__sale--single">';
               str_price += '<span id="ProductPrice-nov-product-template" itemprop="price" content="'+money+'" class="money mr-10">';
                  str_price += '<span class="money">'+money+'</span>';
               str_price += '</span>';
            str_price += '</span>';
         if (sale!=0) 
            str_price += '<s id="ComparePrice-nov-product-template"><span class="money">'+formatDollar(price)+'</span></s>';
         $('.price-product-detail').html(str_price);
      }
   });

   $('.p-typename').click(function() { 
      let st = $(this).attr('stt');
      $('.p-subtypename').css('display','none');
      $('.subtypename-'+st).css('display','block');
   });

   $('.image-youtube > .bg').click(function(e){
      var key = $(this).attr('key');
      $('.image-youtube').remove();
      var str = '<div class="embed-responsive embed-responsive-16by9">';
      str += '<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/'+key+'?autoplay=1" id="video" allow="autoplay" allowfullscreen></iframe></div>';
      $('.tab-video-youtube').html(str);
   });
</script>