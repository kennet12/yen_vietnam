<?
$user = $this->session->userdata('user');
$usd = $this->m_setting->load(1)->usd;
?>

<div id="shopify-section-16107024524da282a5" class="shopify-section index-section section-link-list">
   <div class="distance pt-xs-40 pb-xs-40" data-section-id="16107024524da282a5" data-section-type="nov-slick">
      <div class="container">
         <div class="section-content">
            <div class="row">
               <div class="col-md-5"></div>
               <div class="col-md-7">
                     <div class="text-center">
                        <div class="title_block" style="color:#ff9901">
                           <span><?=$website['top_brand']?></span>
                           <span class="sub_title"><?=$website['top_brand_des']?></span>
                        </div>
                     </div>
                     <div class="row nov-slick-carousel"
                     data-autoplay="true" 
                     data-autoplayTimeout="1000" 
                     data-loop="true"
                     data-dots="true"
                     data-nav="false" 
                     data-row="1"
                     data-items_xl="1"
                     data-items="1" 
                     data-items_lg_tablet="1" 
                     data-items_tablet="1" 
                     data-items_mobile="1"
                     data-items_mobile_xs="1">
                     <? foreach($brands as $brand) { ?>
                     <div class="col">
                        <div class="block_content text-center">
                           <a href="<?=site_url("{$alias['product']}")."?constraint={$brand->alias}"?>">
                              <p class="name-content d-block"><span class="left-icon">&#786; &#786;</span><?=$brand->{$prop['content']}?> <span class="right-icon">&#787; &#787;</span></p>
                           </a>
                        </div>
                     </div>
                     <? } ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <style>
      #shopify-section-16107024524da282a5 .distance {
      padding-top: 95px;
      padding-bottom: 65px;
      }
      <? if(!$this->util->detect_mobile()) {  ?>
      #shopify-section-16107024524da282a5 .section-content {
         padding-top: 85px;
         padding-bottom: 90px;
         background-image: url("<?=IMG_URL?>bg-bird-nest.jpg");
         border-radius: 26px;
         background-repeat: no-repeat;
         background-position: center top;
         background-size: cover;
         
      }
      #shopify-section-16107024524da282a5 .name-content .left-icon {
         position: absolute;
         left: 0;
         font-size: 70px;
         top: -20px;
         color: #ff9901;   
      }
      #shopify-section-16107024524da282a5 .name-content .right-icon {
         position: absolute;
         right: -45px;
         font-size: 70px;
         bottom: -60px;
         color: #ff9901;
      }
      <? } else { ?>
      #shopify-section-16107024524da282a5 .section-content {
         padding-top: 85px;
         padding-bottom: 90px;
         background-repeat: no-repeat;
         background-position: center top;
         background-size: cover;
         
      }
      #shopify-section-16107024524da282a5 .name-content .left-icon {
         position: absolute;
         left: 12px;
         font-size: 70px;
         top: -20px;
         color: #ff9901;   
      }
      #shopify-section-16107024524da282a5 .name-content .right-icon {
         position: absolute;
         right: -30px;
         font-size: 70px;
         bottom: -60px;
         color: #ff9901;
      }
      <? } ?>
      #shopify-section-16107024524da282a5 .name-content {
      color: #7b4e19;
      position: relative;
      font-size: 30px;
      max-width: 540px;
      font-weight: bold;
      }
      #shopify-section-16107024524da282a5 .desc {
      color: #666666;
      }
   </style>
</div>
<?
$s=0;
foreach($product_categories as $product_category) {
  $info 					= new stdClass();
  $info->list_category	= array($product_category->id);
//   $info->quantityEmpty	= 1;
  $select = "
  p.id,
  p.title,
  p.title_en,
  p.alias,
  p.alias_en,
  p.category_id,
  p.code,
  p.rating_point,
  p.thumbnail,
  p.rating_cmt,
  p.meta_title,
  p.meta_key,
  p.meta_des,
  CONVERT(JSON_EXTRACT(t.price, '$[0]'), DECIMAL) as price,
  CONVERT(JSON_EXTRACT(t.sale, '$[0]'), DECIMAL) as sale,
  t.typename";
  $product = $this->m_product->get_list_category_items($select,$info, 1, 15,0,'RAND()','');
  $c = count($product);
?>
<div id="shopify-section-<?=$s?>" class="shopify-section index-section section-product-slider">
   <style>
      #shopify-section-<?=$s?> .distance {
      padding-top: 30px;
      padding-bottom: 60px;
      }
   </style>
   <div class="distance pb-xs-20 container" data-section-id="<?=$s?>" data-section-type="nov-slick">
      <div class="text-left mb-55 d-flex align-items-center">
         <div class="title_block mb-0">
            <span><?=$product_category->{$prop['name']}?></span>
            <span class="sub_title"><?=$product_category->{$prop['description']}?></span>
         </div>
         <div class="nv-ml-auto">
            <span class="custombutton prev_custom d-xs-none">
            <i class="zmdi zmdi-chevron-left"></i>
            </span>
            <span class="custombutton next_custom d-xs-none">
            <i class="zmdi zmdi-chevron-right"></i>
            </span>
         </div>
      </div>
      <div class="block_padding">
         <div class="grid--view-items row nov-slick-carousel"
            data-autoplay="true" 
            data-autoplayTimeout="6000" 
            data-loop="false"
            data-margin="" 
            data-dots="false" 
            data-nav="false" 
            data-row="1" 
            data-row_mobile="2"
            data-items="5" 
            data-items_lg_tablet="4" 
            data-items_tablet="3" 
            data-items_mobile="2"
            data-items_mobile_xs="2"
            data-custombutton="true"
            >
            <? for ($i=0;$i<$c;$i++) {
            $info = new stdClass();
            $info->product_id = $product[$i]->id;
            if(!empty($product[$i]->thumbnail)) {
            $price = $product[$i]->price;
            $price_sale = $price*(1-($product[$i]->sale*0.01));
            $typename = !empty($product[$i]->typename)?' - '.$product[$i]->typename:'';
            $product_link = site_url($product[$i]->{$prop['alias']});
               if(!empty($product[$i]->typename))
                  $product_link .= "?loai={$this->util->slug($product[$i]->typename)}";
            ?>
            <div class="block">
               <div>
                  <div class="item col">
                     <div class="item-product">
                        <div class="thumbnail-container has-multiimage">
                           <a class="w-100" href="<?=$product_link?>">
                              <img class="w-100 img-fluid product__thumbnail" src="<?=$product[$i]->thumbnail?>" alt="<?=$product[$i]->{$prop['title']}?>">
                           </a>
                           <div class="badge_sale">
                              <!-- <div class="badge badge--sale-rt">New</div> -->
                              <? if ($product[$i]->sale!=0) { ?>
                              <span class="badge badge--sale-pt">-<?=$product[$i]->sale?>%</span>
                              <? } ?>
                           </div>
                           
                        </div>
                        <div class="product__info text-center">
                           <div class="product__title">
                              <a href="<?=$product_link?>" class="limit-content-1-line"><?=$product[$i]->{$prop['title']}?></a>
                           </div>
                           <div class="product__typename"><?=$product[$i]->typename?></div>
                           <div class="product__price">
                              <?=number_format($price_sale,0,',','.')?>
                              <? if ($product[$i]->sale!=0) { ?>
                              <s class="product-price__price money"><?=number_format($price,0,',','.')?></s>
                              <? } ?>
                           </div>
                           <? if(isset($_COOKIE['nguyenanh_lang'])) { ?>
                           <div class="product__price">
                              USD: $<?=round($price_sale/$usd,1)?>
                              <? if ($product[$i]->sale!=0) { ?>
                              <s class="product-price__price money">$<?=round($price/$usd,1)?></s>
                              <? } ?>
                           </div>
                           <? } ?>
                           <a class="btn btnAddToCart btnChooseVariant" href="<?=$product_link?>">
                              <i class="zmdi zmdi-zoom-in"></i>
                              <span><?=$website['view_detail']?></span>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <? } } ?>
         </div>
      </div>
   </div>
</div>
<? $s++;} ?>
<? if(!empty($flash_deal)) { ?>
<div id="shopify-section-1610792388f3d700f4" class="shopify-section index-section section-product-slider">
   <style>
      #shopify-section-1610792388f3d700f4 .distance {
      padding-top: 85px;
      padding-bottom: 90px;
      background-image: url("<?=IMG_URL?>sl_122020_39050_21.jpg");
      background-repeat: no-repeat;
      background-position: center top;
      background-size: cover;
      }
   </style>
   <div class="distance grid_2" data-section-id="1610792388f3d700f4" data-section-type="nov-slick">
      <div class="container">
         <div class="text-left mb-55 d-flex align-items-center">
            <div class="title_block">
               <span style="color:#573816;text-shadow: 0px 2px 2px #fff, 0px -2px 2px white, -2px 0px 2px white, 2px 0px 2px white;"><?=$website['flash_deal']?></span>
            </div>
            <div class="nv-ml-auto">
               <span class="custombutton prev_custom d-xs-none">
               <i class="zmdi zmdi-chevron-left"></i>
               </span>
               <span class="custombutton next_custom d-xs-none">
               <i class="zmdi zmdi-chevron-right"></i>
               </span>
            </div>
         </div>
         <div class="block_margin">
            <div class="block_padding">
               <div class="grid--view-items row nov-slick-carousel"
                  data-autoplay="true" 
                  data-autoplayTimeout="6000" 
                  data-loop="false"
                  data-margin="" 
                  data-dots="false" 
                  data-nav="false" 
                  data-row="1" 
                  data-row_mobile="2"
                  data-items="4" 
                  data-items_lg_tablet="3" 
                  data-items_tablet="3" 
                  data-items_mobile="2"
                  data-items_mobile_xs="2"
                  data-custombutton="true"
                  >
                  <?
                  $c = count($flash_deal); 
                  for ($i=0;$i<$c;$i++) {

                     $info = new stdClass();
                     $info->product_id = $flash_deal[$i]->id;

                     if(!empty($flash_deal[$i]->thumbnail)) {
                        $price = $flash_deal[$i]->price;
                        $price_sale = $price*(1-($flash_deal[$i]->sale*0.01));

                        $product_link = site_url($flash_deal[$i]->{$prop['alias']});
                        if(!empty($flash_deal[$i]->typename))
                           $product_link .= "?loai={$this->util->slug($flash_deal[$i]->typename)}";
                  ?>
                  <div class="block">
                     <div>
                        <div class="item col">
                           <div class="item-product">
                              <div class="thumbnail-container has-multiimage">
                                 <a class="w-100" href="<?=$product_link?>">
                                 <img class="w-100 img-fluid product__thumbnail" src="<?=$flash_deal[$i]->thumbnail?>" alt="<?=$flash_deal[$i]->{$prop['title']}?>">
                                 </a>
                                 <div class="badge_sale">
                                    <? if ($flash_deal[$i]->sale!=0) { ?>
                                    <span class="badge badge--sale-pt">-<?=$flash_deal[$i]->sale?>%</span>
                                    <? } ?>
                                 </div>
                                 <div class="group-buttons productWishList">
                                    <?
                                       $status = 0;
                                       $cls = 'removed-wishlist';
                                       if(!empty($user)) {
                                       if (strpos($user->like_product,'"'.$flash_deal[$i]->id.'"') !== false) {
                                          $status = 2;
                                          $cls = 'added-wishlist';
                                       } else 
                                          $status = 1;
                                       }
                                    ?>
                                    <a status='<?=$status?>' id_item='<?=$flash_deal[$i]->id?>' class="<?=$cls?> btnProductWishlist-<?=$flash_deal[$i]->id?> btn btnProduct btnProductWishlist">
                                    <i class="zmdi zmdi-favorite-outline"></i>
                                    <span class="wishlist-text"><?=$website['add_to_wishlist']?></span>
                                    </a>
                                 </div>
                              </div>
                              <div class="product__info text-center">
                                 <div class="product__title">
                                    <a href="<?=$product_link?>" class="limit-content-1-line"><?=$flash_deal[$i]->{$prop['title']}?></a>
                                 </div>
                                 <div class="product__typename"><?=$flash_deal[$i]->typename?></div>
                                 <div class="product__price">
                                    <?=number_format($price_sale,0,',','.')?>
                                    <? if ($flash_deal[$i]->sale!=0) { ?>
                                    <s class="product-price__price money"><?=number_format($price,0,',','.')?></s>
                                    <? } ?>
                                 </div>
                                 <? if(isset($_COOKIE['nguyenanh_lang'])) { ?>
                                 <div class="product__price">
                                    USD: $<?=round($price_sale/$usd,1)?>
                                    <? if ($flash_deal[$i]->sale!=0) { ?>
                                    <s class="product-price__price money">$<?=round($price/$usd,1)?></s>
                                    <? } ?>
                                 </div>
                                 <? } ?>
                                 <a class="btn btnAddToCart btnChooseVariant" href="<?=$product_link?>">
                                    <i class="zmdi zmdi-zoom-in"></i>
                                    <span><?=$website['view_detail']?></span>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?} } ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<? } ?>
<div id="shopify-section-16107913992949ad3f" class="shopify-section index-section section-product-slider">
   <style>
      #shopify-section-16107913992949ad3f .distance {
      padding-top: 100px;
      padding-bottom: 30px;
      }
      #shopify-section-16107913992949ad3f .nov-sh-image-1 img {
         height: 374px;
         object-fit: cover;
         border-radius: 25px;
      }
   </style>
   <div class="distance container" data-section-id="16107913992949ad3f" data-section-type="nov-slick">
      <div class="row">
         <? if(!$this->util->detect_mobile()) {  ?>
         <div class="col-xl-4 col-lg-5 mb-md-60">
            <div class="row spacing-10">
               <div class="nov-sh-image-1 col-md-4 col-lg-12">
                  <a class="w-100" href="#">
                  <img class="w-100 mb-20" src="<?=IMG_URL.'banner-bird-nest.jpeg'?>">
                  </a>
               </div>
               <div class="nov-sh-image-1 col-md-4 col-lg-12">
                  <a class="w-100" href="#">
                  <img class="w-100" src="<?=IMG_URL.'banner-cordyceps.jpg'?>">
                  </a>
               </div>
            </div>
         </div>
         <? } ?>
         <div class="col-xl-8 col-lg-7">
            <div class="text-left mb-55 d-flex align-items-center">
               <div class="title_block mb-0">
                  <span><?=$website['new_arrival']?></span>
               </div>
               <div class="nv-ml-auto">
                  <span class="custombutton prev_custom d-xs-none">
                  <i class="zmdi zmdi-chevron-left"></i>
                  </span>
                  <span class="custombutton next_custom d-xs-none">
                  <i class="zmdi zmdi-chevron-right"></i>
                  </span>
               </div>
            </div>
            <div class="block_padding">
               <div class="list--view-items row nov-slick-carousel"
                  data-autoplay="true" 
                  data-autoplayTimeout="6000" 
                  data-loop="false"
                  data-margin="" 
                  data-dots="false" 
                  data-nav="false" 
                  data-row="3" 
                  data-row_mobile="3"
                  data-items="2" 
                  data-items_lg_tablet="1" 
                  data-items_tablet="1" 
                  data-items_mobile="1"
                  data-items_mobile_xs="1"
                  data-custombutton="true"
                  >
                  <?
                  $c = count($new_arrival); 
                  for ($i=0;$i<$c;$i++) {
                  $info = new stdClass();
                  $info->product_id = $new_arrival[$i]->id;
                  if(!empty($new_arrival[$i]->thumbnail)) {
                  $price = $new_arrival[$i]->price;
                  $price_sale = $price*(1-($new_arrival[$i]->sale*0.01));
                  $typename = !empty($new_arrival[$i]->typename)?' - '.$new_arrival[$i]->typename:'';
                  $product_link = site_url($new_arrival[$i]->{$prop['alias']});
                     if(!empty($new_arrival[$i]->typename))
                        $product_link .= "?loai={$this->util->slug($new_arrival[$i]->typename)}";
                  ?>
                  <div class="item col">
                     <div class="item-product align-items-center">
                        <div class="row">
                           <div class="col-6">
                              <div class="thumbnail-container has-multiimage">
                                 <a href="<?=$product_link?>">
                                 <img class="img-fluid product__thumbnail" src="<?=$new_arrival[$i]->thumbnail?>" alt="<?=$new_arrival[$i]->{$prop['title']}?>">
                                 </a>
                              </div>
                           </div>
                           <div class="col-6 d-flex align-items-center">
                              <div class="product__info">
                                 <div class="block_product_info">
                                    <a class="limit-content-2-line product__title" href="<?=$product_link?>"><?=$new_arrival[$i]->{$prop['title']}?></a>
                                    <div class="product__typename"><?=$new_arrival[$i]->typename?></div>
                                    <div class="product__price">
                                       <?=number_format($price_sale,0,',','.')?>
                                    <? if ($new_arrival[$i]->sale!=0) { ?>
                                       <s class="product-price__price">
                                          <?=number_format($price,0,',','.')?>
                                       </s>
                                    <? } ?>
                                    </div>
                                    <? if(isset($_COOKIE['nguyenanh_lang'])) { ?>
                                    <div class="product__price">
                                       USD: $<?=round($price_sale/$usd,1)?>
                                       <? if ($new_arrival[$i]->sale!=0) { ?>
                                       <s class="product-price__price">
                                          $<?=round($price/$usd,1)?>
                                       </s>
                                       <? } ?>
                                    </div>
                                    <? } ?>
                                 </div>
                                 <div class="d-flex group-btn mt-20 productWishList">
                                    <?
                                       $status = 0;
                                       $cls = 'removed-wishlist';
                                       if(!empty($user)) {
                                       if (strpos($user->like_product,'"'.$new_arrival[$i]->id.'"') !== false) {
                                          $status = 2;
                                          $cls = 'added-wishlist';
                                       } else 
                                          $status = 1;
                                       }
                                    ?>
                                    <a status='<?=$status?>' id_item='<?=$new_arrival[$i]->id?>' class="<?=$cls?>  btnProductWishlist-<?=$new_arrival[$i]->id?> btn btnProduct btnProductWishlist">
                                    <i class="zmdi zmdi-favorite-outline"></i>
                                    <span class="wishlist-text"><?=$website['add_to_wishlist']?></span>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <? } } ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
	addLike('.btnProductWishlist');
</script>