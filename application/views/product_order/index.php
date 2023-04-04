<?
if (!empty($cate)) {
   $title_cate = $cate->{$prop['name']};
} else {
   $info_cate = new stdClass();
	$info_cate->parent_id = 0;
   $info_cate->order_category = 2;
   $categories = $this->m_product_category->items($info_cate,1,null,null,'order_num','ASC');
   $title_cate = $website['all'];
}
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
                     <a href="<?=site_url($alias['product_au_usa'])?>" class="nv-ml-auto"><i class="zmdi zmdi-delete"></i><?=$website['clear_all']?></a>
                  </div>
               </div>
               <div class="categories__sidebar sidebar-block sidebar-block__1">
                  <? if (empty($cate)) { ?>
                  <div class="title-block mb-10"><?=$website['category']?></div>
                  <ul class="list-unstyled">
                  <? foreach($categories as $category) {
                     $info = new stdClass();
                     $info->parent_id = $category->id;
                     $category_subs = $this->m_product_category->items($info,1);
                  ?>
                     <li class="item mb-10">
                        <a href="<?=site_url("{$alias['product_au_usa']}/{$category->{$prop['alias']}}")?>" title="<?=$category->{$prop['name']}?>"><?=$category->{$prop['name']}?></a>
                        <? if(!empty($category_subs)) { ?>
                        <i class="zmdi zmdi-caret-right transition" stt="0" cls="ctrl-<?=$category->id?>"></i>
                        <ul class="list-unstyled-sub ctrl-<?=$category->id?>" style="display:none;">
                           <? foreach($category_subs as $category_sub) { ?>
                           <li class="item-sub mb-10">
                              <a href="<?=site_url("{$alias['product_au_usa']}/{$category_sub->{$prop['alias']}}")?>" title="<?=$category_sub->{$prop['name']}?>"><?=$category_sub->{$prop['name']}?></a>
                              <ul class="list-unstyled-sub ctrl-<?=$category_sub->id?>">
                              <?  $info_sub = new stdClass(); 
                                  $info_sub->parent_id = $category_sub->id;
                                  $category_sub_childs = $this->m_product_category->items($info_sub,1);
                                 foreach($category_sub_childs as $category_sub_child) { ?>
                                 <li class="item-sub mb-10">
                                    <a href="<?=site_url("{$alias['product_au_usa']}/{$category_sub_child->{$prop['alias']}}")?>" title="<?=$category_sub_child->{$prop['name']}?>"><?=$category_sub_child->{$prop['name']}?></a>
                                 </li>
                                 <? } ?>
                              </ul>
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
                  <? } else {
                     $info = new stdClass();
                     if ($cate->parent_id == 0) {
                     $info->parent_id = $cate->id;
                     } else {
                     
                     $info->parent_id = $cate->parent_id;
                     $cate = $this->m_product_category->load($cate->parent_id);
                     }
                     $category_subs = $this->m_product_category->items($info,1);
                  ?>
                     <div class="title-block mb-10"><?=$cate->{$prop['name']}?></div>
                     <ul class="list-unstyled-sub">
                        <? foreach($category_subs as $category_sub) { ?>
                        <li class="item-sub mb-10">
                           <a href="<?=site_url("{$alias['product_au_usa']}/{$category_sub->{$prop['alias']}}")?>" title="<?=$category_sub->{$prop['name']}?>"><?=$category_sub->{$prop['name']}?></a>
                           <ul class="list-unstyled-sub">
                              <? $info_sub = new stdClass();
                              $info_sub->parent_id = $category_sub->id;
                              $category_sub_childs = $this->m_product_category->items($info_sub,1);
                              foreach($category_sub_childs as $category_sub_child) { ?>
                              <li class="item-sub mb-10">
                                 <a href="<?=site_url("{$alias['product_au_usa']}/{$category_sub_child->{$prop['alias']}}")?>" title="<?=$category_sub_child->{$prop['name']}?>"><?=$category_sub_child->{$prop['name']}?></a>
                              </li>
                              <? } ?>
                           </ul>
                        </li>
                        <? } ?>
                     </ul>
                  <? } ?>
               </div>
               <div id="novSidebarAjaxFilter" class="sidebar-block sidebar-block__2 ">
                  <div class="block__content">
                     <div class="list-filter">
                        <!-- <div class="filter-item filter-tags">
                           <div class="filter-item_title"><span> <?=$website['brand']?></span></div>
                           <div class="filter-item_content collapse show">
                              <ul class="brands list-unstyled facet-hasscroll">
								         <? foreach($brands as $brand) { ?>
                                 <li class="d-flex" data-tag="<?=$brand->alias?>">
                                    <span class="custom-checkbox"></span>
                                    <span class="filter-option-value"><?=$brand->name?></span>
                                 </li>
								         <? } ?>
                              </ul>
                           </div>
                        </div> -->
                        <script>
                           function removeParam(key, sourceURL) {
                              var rtn = sourceURL.split("?")[0],
                                 param,
                                 params_arr = [],
                                 queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
                              if (queryString !== "") {
                                 params_arr = queryString.split("&");
                                 for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                                    param = params_arr[i].split("=")[0];
                                    if (param === key) {
                                       params_arr.splice(i, 1);
                                    }
                                 }
                                 if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
                              }
                              return rtn;
                           }
                           $('.brands > li').click(function(e){
                              var data = $(this).attr('data-tag');
                              var url = window.location.href;
                              var urlob = new URL(url);
                              var c = urlob.searchParams.get("constraint");
                              var sort = urlob.searchParams.get("sort");
                              var page = urlob.searchParams.get("page");
                              var url = removeParam("constraint", url);
                              var para = "";
                              if (c == null){
                                 para += "constraint=";
                                 para += data;
                                 if (sort == null && page == null){
                                    url += '?'+para;
                                 } else {
                                    url += '&'+para;
                                 }
                              } else {
                                 c = c.replaceAll(' ','+');
                                 var item = c.split('+');
                                 if (c.indexOf(data) > -1) {
                                    c = c.replace(data+'+','');
                                    c = c.replace('+'+data,'');
                                    c = c.replace(data,'');
                                    para += "constraint="+c;
                                    if (item.length > 1) {
                                       if (sort == null && page == null){
                                          url += '?'+para;
                                       } else {
                                          url += '&'+para;
                                       }
                                    }
                                 } else {
                                    para += "constraint="+c;
                                    para += '+'+data;
                                    if (sort == null && page == null){
                                       url += '?'+para;
                                    } else {
                                       url += '&'+para;
                                    }
                                 }
                              }
                              window.location.href = url;
                           })
                        </script>
                        <!-- <div class="filter-item filter-size">
                           <div class="filter-item_title">
                              <span>Filter size</span>
                           </div>
                           <div class="filter-item_content collapse show">
                              <ul class="list-inline">
                                 <li class="list-inline-item" data-value="small">
                                    <span class="filter-option-value">Small</span>
                                 </li>
                                 <li class="list-inline-item" data-value="medium">
                                    <span class="filter-option-value">Medium</span>
                                 </li>
                                 <li class="list-inline-item" data-value="large">
                                    <span class="filter-option-value">Large</span>
                                 </li>
                                 <li class="list-inline-item" data-value="unltra">
                                    <span class="filter-option-value">Unltra</span>
                                 </li>
                              </ul>
                           </div>
                        </div> -->
                     </div>
                  </div>
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
                           <div class="filter_button d-lg-none h_sidebar">
                              <i class="zmdi zmdi-format-subject"></i>
                              Filter
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
                              <?
                              $control = $alias['product_au_usa'];
                              $control .= !empty($cate)?'/'.$cate->{$prop['alias']}:'';
                              $url = site_url("{$control}") ?>
                              $('.drop-item').click(function(e){
                                 let base_url = '<?=$url?>';
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
                        if(!empty($items[$i]->thumbnail)) {
                        $price = $items[$i]->price;
                        $price_sale = $price*(1-($items[$i]->sale*0.01));
                        $typename = !empty($items[$i]->typename)?' - '.$items[$i]->typename:'';
                        if ($items[$i]->rating_cmt != '0'){
                           $point = round($items[$i]->rating_point/$items[$i]->rating_cmt,1);
                        } else {
                           $point = 0;
                        }
                     ?>
                     <div class="nov-wrapper-product col" data-colors="blue,red,orange,green,pink" data-materials="" data-sizes="small,medium,large,ultra" data-tags="apple,m,pink,upsell" data-price="3.00">
                        <div class="item-product">
                           <div class="thumbnail-container has-multiimage has_variants">
                              <a href="<?=site_url($items[$i]->{$prop['alias']})?>">
                                 <img class="w-100 img-fluid product__thumbnail" src="<?=BASE_URL.str_replace('./','/',$items[$i]->thumbnail)?>" alt="<?=$items[$i]->{$prop['title']}?>">
                              </a>
                              <div class="badge_sale">
                                 <!-- <div class="badge badge--sale-rt">New</div> -->
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
                                    <a href="<?=site_url("{$alias['product_au_usa']}/{$category_product->{$prop['alias']}}")?>" title="Bread"><?=$category_product->{$prop['name']}?></a>
                                 </div>
                                 <? } ?>
                                 <div class="product__title">
								 	         <a href="<?=site_url($items[$i]->{$prop['alias']})?>" class="limit-content-1-line"><?=$items[$i]->{$prop['title']}?></a>
                                 </div>
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
                              <div class="group_buttons_bottom">
                                 <div class="group-buttons">
                                    <a class="btn btnAddToCart btnChooseVariant" href="<?=site_url($items[$i]->{$prop['alias']})?>">
                                       <i class="zmdi zmdi-zoom-in"></i>
                                       <span><?=$website['view_detail']?></span>
                                    </a>
                                 </div>
                                 <div class="productWishList">
                                    <a status='<?=$status?>' id_item='<?=$items[$i]->id?>' class="<?=$cls?> btnProductWishlist-<?=$items[$i]->id?> btn btnProduct btnProductWishlist">
                                    <i class="zmdi zmdi-favorite-outline"></i>
                                    <span class="wishlist-text"><?=$website['add_to_wishlist']?></span>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
					 <? } } ?>
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