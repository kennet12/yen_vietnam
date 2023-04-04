<?
if (!empty($cate)) {
   $title_cate = $cate->{$prop['name']};
} else {
   $title_cate = $website['all'];
}
$info_cate = new stdClass();
$info_cate->parent_id = 0;
$categories = $this->m_product_category->items($info_cate,1,null,null,'order_num','ASC');
$user = $this->session->userdata('user');
$usd = $this->m_setting->load(1)->usd;
?>
<div class="page-width">
   <div class="container">
      <div class="row">
         <div class="sidebar sidebar-collection col-lg-3 col-md-4 flex-xs-unordered">
            <div class="collection_vn pt-md-30 mb-md-40">
               <div class="collection_title"><?=$title_cate?></div>
            </div>
            <div id="shopify-section-nov-sidebar" class="shopify-section">
               <div class="close-filter"><i class="zmdi zmdi-close"></i></div>
               <div class="list-filter-selected">
                  <div class="filter-item_title align-items-center">
                     <a href="<?=site_url("tim-kiem")?>?search_text=<?=$_GET['search_text']?>" class="nv-ml-auto"><i class="zmdi zmdi-delete"></i><?=$website['clear_all']?></a>
                  </div>
               </div>
               <div class="categories__sidebar sidebar-block sidebar-block__1">
                  <div class="title-block mb-10"><?=$website['category']?></div>
                  <ul class="list-unstyled">
                  <? foreach($categories as $category) {
                     $info = new stdClass();
                     $info->parent_id = $category->id;
                     $category_subs = $this->m_product_category->items($info,1);
                  ?>
                     <li class="item mb-10">
                        <a href="<?=site_url("tim-kiem/{$category->{$prop['alias']}}")."?search_text={$_GET['search_text']}"?>" title="<?=$category->{$prop['name']}?>"><?=$category->{$prop['name']}?></a>
                        <? if(!empty($category_subs)) { ?>
                        <i class="zmdi zmdi-caret-right transition" stt="0" cls="ctrl-<?=$category->id?>"></i>
                        <ul class="list-unstyled-sub ctrl-<?=$category->id?>" style="display:none;">
                           <? foreach($category_subs as $category_sub) { ?>
                           <li class="item-sub mb-10">
                              <a href="<?=site_url("tim-kiem/{$category_sub->{$prop['alias']}}")."?search_text={$_GET['search_text']}"?>" title="<?=$category_sub->{$prop['name']}?>"><?=$category_sub->{$prop['name']}?></a>
                           </li>
                           <? } ?>
                        </ul>
						      <? } ?>
                     </li>
					    <? } ?>
                  </ul>
                  <script>
                     $('.zmdi-caret-right').click(function(e){
                        var stt = $(this).attr('stt');
                        var cls = $(this).attr('cls');
                        if (stt == '0'){
                           $(this).addClass('active');
                           $(this).attr('stt','1');
                        } else {
                           $(this).removeClass('active');
                           $(this).attr('stt','0');
                        }
                        $('.'+cls).toggle('fast');
                     })
                  </script>
               </div>
            </div>
         </div>
         <div class="col-lg-9 col-md-12 flex-xs-first">
            <div id="shopify-section-collection-template" class="shopify-section">
               <div data-section-id="collection-template" data-section-type="collection-template" data-panigation="12">
                  <div class="row collection-view-items view_3 grid--view-items">
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sortPagiBar d-md-flex align-items-center">
                           <div class="filter_button d-lg-none h_sidebar" style="font-size: 25px;margin-bottom: 10px;">
                              <i class="zmdi zmdi-format-subject"></i>
                              Menu
                           </div>
                           <div class="filters-toolbar__item d-flex align-items-center">
                              <div class="pagination__viewing">
                                 <?=$website['page']?> <?=$page?> - <?=$total_page?> | <?=$total?> <?=$website['product']?>
                              </div>
                              <div class="gridlist-toggle">
                                 <a href="#" id="grid-3" title="Grid View 3" data-type="view_3" class="active"><i class="zmdi zmdi-apps"></i></a>
                                 <a href="#" id="grid-2" title="Grid View 2" data-type="view_2"><i style="transform: rotate(90deg);" class="zmdi zmdi-view-module"></i></a>
                                 <a href="#" id="list" title="List View" data-type="list"><i class="zmdi zmdi-view-list"></i></a>
                              </div>
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" value="a" aria-expanded="true">
                              <? 
                              if(!empty($_GET['sort'])) {
                                 if ($_GET['sort'] == 'price-desc') {
                                    echo $website['price_desc'];
                                 } else if ($_GET['sort'] == 'price-asc') {
                                    echo $website['price_asc'];
                                 }
                              } else {
                                 echo $website['sort_by'];
                              }
                              ?>
                              </button>
                              <div class="dropdown-menu dropdown-menu-right text-right">
                                 <div class="drop-item active" status="sort" data-value=""><?=$website['sort_by']?></div>
                                 <div class="drop-item" status="sort" data-value="price-desc"><?=$website['price_desc']?></div>
                                 <div class="drop-item" status="sort" data-value="price-asc"><?=$website['price_asc']?></div>
                              </div>
                           </div>
                           <script>
                              $('.drop-item').click(function(e){
                                 let base_url = BASE_URL+'/tim-kiem.html';
                                 let st = $(this).attr('status');
                                 let v = $(this).attr('data-value');
                                 let ob = getParams(window.location.href);

                                 let i = 0;
                                 cleanObject(ob);
                                 ob[st] = v;
                                 let page_key = '';
                                 let page_val = '';
                                 Object.entries(ob).forEach(([key,value]) => {
                                    if (key != 'trang') {
                                       if (ob[key] != '') {
                                          if (i == 0){
                                             if (ob[key] != '') base_url += '?'+key+'='+ob[key];
                                          } else {
                                             if (ob[key] != '') base_url += '&'+key+'='+ob[key];
                                          }
                                          i++;
                                       }
                                    } else {
                                       page_key = key; page_val = value;
                                    }
                                 });
                                 
                                 if (page_key != "") {
                                    if (page_val <= total_page) base_url += '&trang='+page_val;
                                    else base_url += '&trang='+total_page;
                                 }
                                 // base_url += '#list-product';
                                 window.location.href = base_url;
                              })
                           </script>
                        </div>
                     </div>
                     <?
                     $c = count($items);
                     for ($i=0;$i<$c;$i++) { 
                        $info = new stdClass();
                        $info->product_id = $items[$i]->id;
                        $product_gallery = $this->m_product_gallery->get_one_thumb($info);
                        if(!empty($product_gallery)) {
                        $photo = explode('/',$product_gallery->file_path);
                        $price = $items[$i]->price;
                        $price_sale = $price*(1-($items[$i]->sale*0.01));
                        $typename = !empty($items[$i]->typename)?' - '.$items[$i]->typename:'';
                        if ($items[$i]->rating_cmt != '0'){
                           $point = round($items[$i]->rating_point/$items[$i]->rating_cmt,1);
                        } else {
                           $point = 0;
                        }

                        $product_link = site_url($items[$i]->{$prop['alias']});
                        if(!empty($items[$i]->typename))
                           $product_link .= "?loai={$this->util->slug($items[$i]->typename)}";

                        $str_rating = "";
                        for($j=1;$j<=5;$j++) {
                           if ($j <= $point) {
                           $str_rating .= "<i class='spr-icon spr-icon-star'></i>";
                           } else {
                           if (($point > ($j-1)) && $point < $j) {
                              $str_rating .= "<i class='spr-icon spr-icon-star'></i>";
                           } else {
                              $str_rating .= "<i class='spr-icon spr-icon-star-empty'></i>";
                           }
                           }
                        }
                     ?>
                     <div class="nov-wrapper-product col" data-colors="blue,red,orange,green,pink" data-materials="" data-sizes="small,medium,large,ultra" data-tags="apple,m,pink,upsell" data-price="3.00">
                        <div class="item-product">
                           <div class="thumbnail-container has-multiimage has_variants">
                              <a href="<?=$product_link?>">
                                 <img class="w-100 img-fluid product__thumbnail" src="<?=BASE_URL."/files/upload/product/".$items[$i]->code."/".end($photo)?>" alt="<?=$items[$i]->{$prop['title']}?>">
                              </a>
                              <div class="badge_sale">
								         <? if ($items[$i]->sale!=0) { ?>
                                	<span class="badge badge--sale-pt">-<?=$items[$i]->sale?>%</span>
                                <? } ?>
                              </div>
                              <div class="group-buttons">
                                 <?
                                    $status = 0;
                                    $cls = 'removed-wishlist';
                                    if(!empty($user)) {
                                    if (strpos($user->like_product,'"'.$items[$i]->id.'"') !== false) {
                                       $status = 2;
                                       $cls = 'added-wishlist';
                                    } else 
                                       $status = 1;
                                    }
                                 ?>
                                 <div class="productWishList">
                                    <a status='<?=$status?>' id_item='<?=$items[$i]->id?>' class="<?=$cls?> btnProductWishlist-<?=$items[$i]->id?> btn btnProduct btnProductWishlist">
                                    <i class="zmdi zmdi-favorite-outline"></i>
                                    <span class="wishlist-text"><?=$website['add_to_wishlist']?></span>
                                    </a>
                                 </div>
                              </div>
                           </div>
                           <div class="product__info">
                              <div class="block_product_info">
                                 <?
                                 $category_product = json_decode($items[$i]->category_id);
                                 $category_product = $this->m_product_category->load(end($category_product));
                                 if (!empty($category_product)) {
                                 ?>
                                 <div class="cate">
                                    <a href="<?=site_url("tim-kiem")."?search_text={$_GET['search_text']}&danh_muc={$category_product->id}"?>" title="Bread"><?=$category_product->{$prop['name']}?></a>
                                 </div>
                                 <? } ?>
                                 <div class="product__title">
								 	         <a href="<?=$product_link?>" class="limit-content-1-line"><?=$items[$i]->{$prop['title']}?></a>
                                 </div>
                                 <div class="product__typename"><?=$items[$i]->typename?></div>
                                 <div class="product__price">
								 	         <span class="product-price__price product-price__sale">
                                      <span class="money"><?=number_format($this->util->round_number($price_sale,1000),0,',','.')?></span>
                                    </span>
                                    <? if ($items[$i]->sale!=0) { ?>
                                    <s class="product-price__price"><span class="money"><?=number_format($this->util->round_number($price,1000),0,',','.')?></span></s>
                                    <? } ?>
                                 </div>
                                 <? if(isset($_COOKIE['nguyenanh_lang'])) { ?>
                                 <div class="product__price">
                                    <span class="product-price__price product-price__sale">
                                    <span class="money">USD: $<?=round($price_sale/$usd,1)?></span>
                                    </span>
                                    <? if ($items[$i]->sale!=0) { ?>
                                    <s class="product-price__price"><span class="money">$<?=round($price/$usd,1)?></span></s>
                                    <? } ?>
                                 </div>
                                 <? } ?>
                                 <div class="desc mt-15"><?=character_limiter(strip_tags($items[$i]->{$prop['content']}),250)?></div>
                              </div>
                              <a class="btn btnAddToCart btnChooseVariant" href="<?=$product_link?>">
                                 <i class="zmdi zmdi-zoom-in"></i>
                                 <span><?=$website['view_detail']?></span>
                              </a>
                           </div>
                        </div>
                     </div>
					 <? }} ?>
                     <!------------------------------------------------------>
                  </div>
                  <!-----pagination---->
                  <?=$pagination?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
	addLike('.btnProductWishlist');
</script>