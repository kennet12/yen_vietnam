<div id="shopify-section-cart-template" class="shopify-section cart-section">
   <div class="page-width book-cart-shop" data-section-id="cart-template" data-section-type="cart-template">
      <div class="container">
         <div class="section-header">
            <div class="title-block"><?=$website['my_cart']?></div>
         </div>
         <form action="/cart" method="post" novalidate class="cart">
            <div class="row data-sticky_parent">
               <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12">
                  <div class="cart__layout_left">
                     <div class="cart__header d-xs-none">
                        <div class="row spacing-0">
                           <div class="col-md-1 label_remove"></div>
                           <div class="col-md-5 label_product"><?=$website['cart_note1']?></div>
                           <div class="col-md-2 label_price"><?=$website['cart_note2']?></div>
                           <div class="col-md-2 label_quantity"><?=$website['cart_note3']?></div>
                           <div class="col-md-2 label_total"><?=$website['cart_note4']?></div>
                        </div>
                     </div>
                     <div class="cart__body">
                        <? 
                        $i=0; 
                        $c=count($items); 
                        $totalprice = 0;
                        $totalqty = 0;
                        foreach ($items as $item) { 
                           $price = $item['qty']*$item['price_sale']; 
                           $totalprice += $price;
                           $totalqty 	+= $item['qty'];
      
                           $product = $this->m_product->load($item['product_id']);
                           $info = new stdClass();
                           $info->product_id = $item['product_id'];
                           $product->product_type = $this->m_product_type->items($info);
                           $key_i = ($item['key_i']!='-1') ? $item['key_i'] : '0';
                           $key_j = ($item['key_j']!='-1') ? $item['key_j'] : '0';
                           $photo = explode('/',$item['thumbnail']);
                           $type_quantity = json_decode($product->product_type[$key_i]->quantity)[$key_j];
                        ?>
                        <div class="row spacing-0 align-items-center line2 cart-flex cart_item nv-pl-xs-15 nv-pr-xs-15">
                           <div class="col-md-1 cart__remove-wrapper text-center mb-xs-20">
                              <a href="#" class="cart__remove">
                                 <!-- <i class="fa fa-trash-o" aria-hidden="true" rowid_item="<?=$item['rowid']?>" id_item="<?=$item['id']?>"></i> -->
                                 <i class="zmdi zmdi-delete" rowid_item="<?=$item['rowid']?>" id_item="<?=$item['id']?>"></i>
                              </a>
                           </div>
                           <div class="col-md-5 cart__image-wrapper d-flex align-items-center mb-xs-20">
                              <a href="#">
                              <img class="cart__image" src="<?=$product->thumbnail?>">
                              </a>
                              <div class="cart__meta cart-flex-item">
                                 <div class="content-left">
                                    <div class="list-view-item__title">
                                       <a target="_blank" href="<?=site_url("{$product->{$prop['alias']}}")?>">
                                          <?=$product->{$prop['title']}?>
                                       </a>
                                       <div style="font-size: 11px;color: #2fb94c;"><?=$item['typename']?> <?=!empty($item['subtypename'])?" - ".$item['subtypename']:""?></div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-2 cart__price-wrapper mb-xs-20">
                              <? if(!empty($item["sale"])) { ?>
                              <div>
                                 <span class="money">
                                    <?=number_format($this->util->round_number($item['price_sale'],1000),0,',','.')?>
                                    <sup style="color:red">-<?=$item["sale"]?>%</sup>
                                 </span>
                              </div>
                              <div>
                                 <s style="color: #c1c1c1;">
                                    <?=number_format($this->util->round_number($item['price'],1000),0,',','.')?>
                                 </s>
                              </div>
                              <? } else { ?>
                              <div>
                                 <span class="money">
                                    <?=number_format($this->util->round_number($item['price_sale'],1000),0,',','.')?>
                                 </span>
                              </div>
                              <? } ?>
                           </div>
                           <div class="col-md-2 cart__update-wrapper mb-xs-20">
                              <div class="cart__qty">
                                 <input type="button" class="js-qty__adjust minus js-qty__adjust--minus" value="-" max="<?=$type_quantity?>" cost="<?=$item['price']?>" price="<?=$item['price_sale']?>" id_item="<?=$item['id']?>" sale="<?=!empty($item['sale'])?$item['sale']:0?>" stt="<?=$i?>"/>
                                    <input class="cart__qty-input qty quantity quantity-<?=$i?> quantity-<?=$item['id']?>" value="<?=$item['qty']?>" cost="<?=$item['price']?>" price="<?=$item['price_sale']?>" id_item="<?=$item['id']?>" sale="<?=!empty($item['sale'])?$item['sale']:0?>" stt="<?=$i?>">
                                 <input type="button" class="js-qty__adjust plus js-qty__adjust--plus" value="+" max="<?=$type_quantity?>" cost="<?=$item['price']?>" price="<?=$item['price_sale']?>" id_item="<?=$item['id']?>" sale="<?=!empty($item['sale'])?$item['sale']:0?>" stt="<?=$i?>"/> 
                              </div>
                           </div>
                           <div class="col-md-2 total">
                              <div class="product-subtotal">
                                 <span class="money price-<?=$i?>"><?=number_format($this->util->round_number($price,1000),0,',','.')?></span>
                              </div>
                           </div>
                        </div>
                        <? $i++;} ?>
                     </div>
                  </div>
                  <!-- <div class="cart__layout_related">
                     <div class="product-related">
                        <h3 class="cart__popup-related-title">You may also like these products</h3>
                        <div class="product-related-inner row">
                           <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6 0 mb-xs-30">
                              <div class="popup__cart-product mb-sm-20">
                                 <img class="sp-post-image" src="//cdn.shopify.com/s/files/1/0526/4677/2928/products/35_e6a44d15-d2cf-4f22-bfe0-a808d0e98f7f_270x270.jpg?v=1610772240" alt="Liamond Ualo Stud Garrings" />
                                 <a class="related_product-title" href="/products/liamond-ualo-stud-garrings">Liamond Ualo Stud Garrings</a>
                                 <div class="product__price">
                                    <span class="visually-hidden">Regular price</span>
                                    <span class="product-price__price product-price__sale">
                                    <span class="money">$5.00</span>
                                    </span>
                                    <s class="product-price__price"><span class="money">$14.00</span></s>
                                 </div>
                                 <form class="formAddToCart" action="/cart/add" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="37912784502976"/>
                                    <a class="btn btnAddToCart btnChooseVariant" href="javascript:void(0);" data-url="/products/liamond-ualo-stud-garrings?view=json"
                                       data-toggle="tooltip" data-placement="top"  title="" tabindex="0">
                                    <span>Select Options</span>
                                    </a>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div> -->
               </div>
               <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 data-sticky_column mt-md-40">
                  <div class="cart__layout_right">
                     <div class="cart__heading">
                        <div><span class="total-qty"><?=$totalqty?></span> <?=$website['cart_note5']?></div>
                     </div>
                     <div class="grid">
                        <div class="grid__item">
                           <div class="cart__total d-flex align-items-center justify-content-between">
                              <span class="cart__subtotal-title"><?=$website['total']?>:</span>
                              <span class="cart__subtotal"><span class="total-money money"><?=number_format($this->util->round_number($totalprice,1000),0,',','.')?></span></span>
                           </div>
                           <br>
                           <!-- <div class="cart__shipping d-flex align-items-xl-center justify-content-between">
                              <div class="cart__shipping-title">Shipping :</div>
                              <div class="cart__shipping-sub">Shipping &amp; taxes calculated at checkout</div>
                           </div> -->
                          <div id="threshold_bar_popup">
                              <div class="threshold_it">
                                 <div class="ic_threshold_bar">
                                    <i class="zmdi zmdi-truck"></i>
                                 </div>
                                 <div class="threshold_bar">
                                    <span class="animate" style="width:25%!important">25%</span>
                                 </div>
                              </div>
                              <!-- <div class="threshold_spend">Spend <span class="money">$149.00</span> for free shipping</div> -->
                           </div>
                           <!--  <div class="cart-notice-total">
                              Free shipping for any orders above <span><span class="money">$200.00</span></span>
                           </div> -->
                        </div>
                        <div class="box-confirm-cart">
                           <input type="checkbox" class="confirm-cart" name="<?=$website['cart_note6']?>" value="confirm">
                           <label for="<?=$website['cart_note6']?>"><?=$website['cart_note6']?></label>
                        </div>
                        <!-- <div class="grid__item cart-note">
                           <div class="cart-note_label">Add a note to your order :</div>
                           <textarea rows="12" name="note" id="CartSpecialInstructions" class="cart-note__input" placeholder="Add your note here"></textarea>
                        </div> -->
                     </div>
                  </div>
                  <a href="<?=site_url('dat-hang')?>" class="btn-get-cart" ><?=$website['check_out']?></a>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<div class="wrap-delete-cart" style="display:none">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<div class="box-delete">
				<div class="box-title">
					<?=$website['cart_note7']?>
				</div>
				<div class="box-content">
               <?=$website['cart_note8']?>
				</div>
				<div class="confirm-box">
					<button type="button" class="btn btn-danger btn-sure-delete" rowid_item="">OK</button>
					<button type="button" class="btn btn-secondary btn-cancel" data-dismiss="modal"><?=$website['lose']?></button>
				</div>
			</div>				
		</div>
		<div class="col-md-4"></div>
	</div>
</div>
<!-- <div class="modal fade" id="delete-cart" tabindex="-1" role="dialog" aria-labelledby="delete-cartLabel" aria-hidden="true"><div class="modal-dialog modal-warning" id="dialog" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="delete-cartLabel">Xóa sản phẩm</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body">Bạn chắc chắn muốn xóa sản phẩm này?</div><div class="modal-footer"></div></div></div></div> -->
<!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->
<script type="text/javascript">
$(document).ready(function() {
   $('.js-qty__adjust').click(function(e){
      let id 					= $(this).attr('id_item');
      let max = parseInt($(this).attr("max"));
      let t = $(this).val();
      let qty = parseInt($(this).parents('.cart__qty').find('.quantity').val());
      let stt 				= $(this).attr('stt');
		let price 				= parseFloat($(this).attr('price'));
		let sale 				= parseFloat($(this).attr('sale'));
		let cost 				= parseFloat($(this).attr('cost'));
      if (t == '+'){
         if (qty < max)
         qty += 1;
      } else {
         if (qty > 1)
         qty -= 1;
      }
      $(this).parents('.cart__qty').find('.quantity').val(qty);

      let total 				= 0;
		let total_qty 			= 0;
		$('.price-'+stt).html(formatDollar(qty*price));
		for (var i = 0; i < <?=$c?>; i++) {
			total_qty += parseInt($('.quantity-'+i).val());
			total += parseFloat($('.price-'+i).html().replaceAll('.',''));
		}
		$('.total-qty').html(total_qty);
		$('.total-money').html(formatDollar(total));
      let p = {};
			p["id"] = id;
			p["qty"] = qty;
		$.ajax({
			url: BASE_URL+'/gio-hang/ajax-update-cart.html',
			type: 'POST',
			dataType: 'json',
			data: p
		});
   })
	$('.book-cart-shop .quantity').change(function(event) {
		let id 					= $(this).attr('id_item');
		let qty 				= parseInt($(this).val());
		let stt 				= $(this).attr('stt');
		let price 				= parseFloat($(this).attr('price'));
		let sale 				= parseFloat($(this).attr('sale'));
		let cost 				= parseFloat($(this).attr('cost'));
		let total 				= 0;
		let total_qty 			= 0;
		$('.price-'+stt).html(formatDollar(qty*price));
		for (var i = 0; i < <?=$c?>; i++) {
			total_qty += parseInt($('.quantity-'+i).val());
			total += parseFloat($('.price-'+i).html().replaceAll('.',''));
		}
		$('.total-qty').html(total_qty);
		$('.total-money').html(formatDollar(total));
		// $('.note-sale-'+stt).html('( -'+formatDollar(qty*(sale*cost*0.01))+'<sup>₫</sup> )');
		// $('#promotion').val('');
		// $('.total-price-sale').hide();
		// $('.error-promotion').html('Vui lòng nhập mã lại');
		// $('.error-promotion').show();
		let p = {};
			p["id"] = id;
			p["qty"] = qty;
		$.ajax({
			url: BASE_URL+'/gio-hang/ajax-update-cart.html',
			type: 'POST',
			dataType: 'json',
			data: p
		});
	});
	$('.zmdi-delete').click(function(event) {
		$('.wrap-delete-cart').css('display','block');
		$('.btn-sure-delete').attr('rowid_item',$(this).attr('rowid_item'));
		$('.btn-sure-delete').attr('id_item',$(this).attr('id_item'));
	});
	$('.btn-cancel').click(function(event) {
		$('.wrap-delete-cart').css('display','none');
	});
	$('.btn-sure-delete').click(function(event) {
		let p = {};
			p["rowid"] = $(this).attr('rowid_item');
			p["id"] = $(this).attr('id_item');
		$.ajax({
			url: BASE_URL+'/gio-hang/ajax-delete-cart.html',
			type: 'POST',
			dataType: 'json',
			data: p,
			success: function(r) {
				if (r) {
					location.reload();
				}
			}
		});
	});
});
</script>