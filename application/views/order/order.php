<!DOCTYPE html>
<html lang="en" dir="ltr" class="no-js mac chrome desktop page--no-banner page--logo-main page--show page--show card-fields">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height, minimum-scale=1.0, user-scalable=0">
      <meta name="referrer" content="origin">
      <title><?=SITE_NAME?></title>
      <meta data-browser="chrome" data-browser-major="95">
      <meta data-body-font-family="Source Sans Pro" data-body-font-type="google">
      <meta id="shopify-digital-wallet" name="shopify-digital-wallet" content="/52646772928/digital_wallets/dialog">
      <meta name="shopify-checkout-authorization-token" content="e0473b82ee8ee263acc638805defc5e7" />
      <meta id="shopify-regional-checkout-config" name="shopify-regional-checkout-config" content="{&quot;bugsnag&quot;:{&quot;checkoutJSApiKey&quot;:&quot;717bcb19cf4dd1ab6465afcec8a8de02&quot;,&quot;endpoint&quot;:&quot;https:\/\/notify.bugsnag.com&quot;}}" />
      <meta property="og:title" content="<?='ĐẶT HÀNG - '.SITE_NAME?>" />
      <link rel="SHORTCUT ICON" href="<?=BASE_URL?>/favicon.ico?v=<?=CACHE_TIME?>"/>
      <link rel="dns-prefetch" href="https://fonts.shopifycdn.com">
      <link rel="preconnect" href="https://fonts.shopifycdn.com" crossorigin>
      <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
      <style>
         @font-face {
         font-family: "Source Sans Pro";
         font-style: normal;
         font-weight: 400;
         src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.shopifycdn.com/source_sans_pro/sourcesanspro_n4.c85f91ea821d792887902daa9670754f7c64e25c.woff2?valid_until=MTYzNTkyNTk5MQ&hmac=1d6fa45fc05ddf63a08c0a7c0f368071faa5459cc049f1d19e07204ecce7bc92) format('woff2'),url(https://fonts.shopifycdn.com/source_sans_pro/sourcesanspro_n4.670bd38ea1359c9a89f826fc4fedcc275b1bfd42.woff?valid_until=MTYzNTkyNTk5MQ&hmac=cb0155fb7abb2104e4adf77f9c2947ea14e54944676f5e525c6f0c35c1a95123) format('woff');
         }
         @font-face {
         font-family: "Source Sans Pro";
         font-style: normal;
         font-weight: 600;
         src: local('Source Sans Pro SemiBold'), local('SourceSansPro-SemiBold'), url(https://fonts.shopifycdn.com/source_sans_pro/sourcesanspro_n6.91ba95a725d9bdfe4971390fba64eb8dfe38af4a.woff2?valid_until=MTYzNTkyNTk5MQ&hmac=b58f6c628a10fa6780e12573b18ef4cd220ce5640b5953e8ef663df2a3222f6a) format('woff2'),url(https://fonts.shopifycdn.com/source_sans_pro/sourcesanspro_n6.ddeecb7446fb3520bd9f1efcb28875293a40d5c0.woff?valid_until=MTYzNTkyNTk5MQ&hmac=ca859fb6ffa66da74d3a14caeed2eb43c3f0d91c8db9ae8fc09f977f04b47ff7) format('woff');
         }
         @font-face {
         font-family: "Roboto";
         font-style: normal;
         font-weight: 400;
         src: local('Roboto Regular'), local('Roboto-Regular'), url(https://fonts.shopifycdn.com/roboto/roboto_n4.da808834c2315f31dd3910e2ae6b1a895d7f73f5.woff2?valid_until=MTYzNTkyNTk5MQ&hmac=fdf30310652ea0d5a594cc026f08aa98c8f6990c45aeb38bce5efed13eedefa8) format('woff2'),url(https://fonts.shopifycdn.com/roboto/roboto_n4.a512c7b68cd7f12c72e1a5fd58e7f7315c552e93.woff?valid_until=MTYzNTkyNTk5MQ&hmac=84f86b487970d8390101f134cb860015e28efe5aade4a94f53b704381beb7bff) format('woff');
         }
         @font-face {
         font-family: "Roboto";
         font-style: normal;
         font-weight: 500;
         src: local('Roboto Medium'), local('Roboto-Medium'), url(https://fonts.shopifycdn.com/roboto/roboto_n5.126dd24093e910b23578142c0183010eb1f2b9be.woff2?valid_until=MTYzNTkyNTk5MQ&hmac=a8a510959b3fb88aa2576f3612c681041679886feab301b86e1cbd3ca90c137e) format('woff2'),url(https://fonts.shopifycdn.com/roboto/roboto_n5.ef0ac6b5ed77e19e95b9512154467a6fb9575078.woff?valid_until=MTYzNTkyNTk5MQ&hmac=9c1366008097092b45e26099323a610a7fb6770afabb442b1982bb68b66d6581) format('woff');
         }
		.book-cart-shop .payment {
			display: table;
			width: 100%;
			margin: 10px 0;
		}
		.book-cart-shop .payment .payment-item {
			display: table-cell;
			width: 50%;
			vertical-align: top;
		}
		.book-cart-shop .payment .payment-item label {
			cursor: pointer;
		}
		.book-cart-shop .payment .payment-item img {
			width: 125px;
		}
		.book-cart-shop .payment .payment-item .payment-method {
			-webkit-appearance: auto !important;
			-moz-appearance: auto !important;
			appearance: auto !important;
		}
        
		.book-cart-shop .title-payment {
			font-size: 18px;
			margin-top: 30px;
			color: #333;
			font-weight: 300;
		}
      </style>
     <link rel="stylesheet" href="<?=CSS_URL?>front-end/checkout.css?v=<?=CACHE_RAND?>" media="all" />
     <link rel="stylesheet" href="<?=CSS_URL?>front-end/grid.css?v=<?=CACHE_RAND?>" media="all" />
	  <script src="//cdn.shopify.com/s/files/1/0526/4677/2928/t/2/assets/jquery.2.2.4.min.js?v=17029281091488042083" type="text/javascript"></script>
      <script src="//cdn.shopify.com/app/services/52646772928/javascripts/checkout_countries/117867217088/en/countries-d3ebcabff8d14884e66d48af92696d667e643451-d3ebcabff8d14884e66d48af92696d667e643451-1610771139-d295b8ba1ab1e296ee2c6d377b1e79b7a1dddd34.js?version=edge" crossorigin="anonymous"></script>
      <script src="//cdn.shopify.com/shopifycloud/shopify/assets/checkout-8f06cf4d8edeedbc1c1e644b4f6506dff1805a1118f3febb8eea8754a1538cfe.js" crossorigin="anonymous"></script>
      <script id="__st">var __st={"a":52646772928,"offset":-14400,"reqid":"1731582c-cd59-49a0-86d2-fdfce241cf25","pageurl":"greenbee-4.myshopify.com\/52646772928\/checkouts\/42476d0e258606d0c7fe1ed4f87f7591","u":"4747b91ac26b","t":"checkout","offsite":1};</script>
   </head>
   <body>
       <header class="banner" data-header role="banner">
         <div class="wrap">
            <a class="logo logo--left" href="<?=BASE_URL?>"><span class="logo__text heading-1"><?=SITE_NAME?></span></a>
            <h1 class="visually-hidden">
               Information
            </h1>
         </div>
      </header>
      <aside role="complementary">
         <button class="order-summary-toggle order-summary-toggle--show shown-if-js" aria-expanded="false" aria-controls="order-summary" data-drawer-toggle="[data-order-summary]">
            <span class="wrap">
            <span class="order-summary-toggle__inner">
            <span class="order-summary-toggle__icon-wrapper">
               <svg width="20" height="19" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__icon">
                  <path d="M17.178 13.088H5.453c-.454 0-.91-.364-.91-.818L3.727 1.818H0V0h4.544c.455 0 .91.364.91.818l.09 1.272h13.45c.274 0 .547.09.73.364.18.182.27.454.18.727l-1.817 9.18c-.09.455-.455.728-.91.728zM6.27 11.27h10.09l1.454-7.362H5.634l.637 7.362zm.092 7.715c1.004 0 1.818-.813 1.818-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817zm9.18 0c1.004 0 1.817-.813 1.817-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817z" />
               </svg>
            </span>
            <span class="order-summary-toggle__text order-summary-toggle__text--show">
               <span><?=$website['order_note16']?></span>
               <svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000">
                  <path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z" />
               </svg>
            </span>
            <span class="order-summary-toggle__text order-summary-toggle__text--hide">
               <span><?=$website['order_note16']?></span>
               <svg width="11" height="7" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000">
                  <path d="M6.138.876L5.642.438l-.496.438L.504 4.972l.992 1.124L6.138 2l-.496.436 3.862 3.408.992-1.122L6.138.876z" />
               </svg>
            </span>
         </button>
      </aside>
      <div class="content" data-content>
         <div class="wrap">
            <form id="frm-checkout" class="edit_checkout" novalidate="novalidate" data-customer-information-form="true" data-email-or-phone="true" action="<?=site_url("dat-hang-nhap/gui")?>" method="POST" accept-charset="UTF-8">
            <div class="main order-main">
               <header class="main__header" role="banner">
                  <a class="logo logo--left" href="<?=BASE_URL?>"><span class="logo__text heading-1"><?=SITE_NAME?></span></a>
                  <nav aria-label="Breadcrumb">
                     <ol class="breadcrumb " role="list">
                        <li class="breadcrumb__item breadcrumb__item--completed">
                           <a class="breadcrumb__link" href="<?=BASE_URL?>"><?=$menu['home']?></a>
                           <svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
                              <use xlink:href="#chevron-right" />
                           </svg>
                        </li>
                        <li class="breadcrumb__item breadcrumb__item--current">
                           <a class="breadcrumb__link" href="#"><?=$website['check_out']?></a>
                        </li>
                     </ol>
                  </nav>
                  <div class="shown-if-js" data-alternative-payments></div>
               </header>
               <main class="main__content" role="main">
                  <div class="step" data-step="contact_information" data-last-step="false">
                        <input type="hidden" name="_method" value="patch" autocomplete="off" /><input type="hidden" name="authenticity_token" value="p6VSqlMty0Yvdbd8qieLMm4cZddAj0nT24FVSSfX9wh20wiDWN9fwns9bSxoqJWCX7iDT5wgwEtjnIfA-oiKig" autocomplete="off" />
                        <input type="hidden" name="previous_step" id="previous_step" value="contact_information" autocomplete="off" />
                        <input type="hidden" name="step" value="shipping_method" autocomplete="off" />
                        <div class="step__sections">
                           <div class="section section--contact-information">
                              <div class="section__header">
                                 <div class="layout-flex layout-flex--tight-vertical layout-flex--loose-horizontal layout-flex--wrap">
                                    <h2 class="section__title layout-flex__item layout-flex__item--stretch" id="main-header" tabindex="-1">
									                    <?=$website['order_note']?>
                                    </h2>
                                 </div>
                              </div>
                              <div class="section__content" data-section="customer-information" data-shopify-pay-validate-on-load="false">
                                 <div class="fieldset">
                                    <div class="field field field--required" data-address-field="fullname">
                                       <label class="field__label" for="checkout_shipping_address_fullname"><?=$website['order_note3']?></label>
                                       <div class="field__input-wrapper">
                                          <input value="<?=empty($order->fullname) ? '' : $order->fullname?>" placeholder="<?=$website['order_note3']?>" autocomplete="shipping family-name" autocorrect="off" data-backup="fullname" class="field__input" aria-required="true" size="30" type="text" name="fullname" required id="checkout_shipping_address_fullname" />
                                       </div>
                                       <p class="field__message field__message--error"><?=$website['error_note1']?></p>
                                    </div>
                                 </div>
                              </div>
                              <div class="fieldset">
                                 <div class="address-fields" data-address-fields>
                                 <div class=" field field--required" data-address-field="email">
                                       <label class="field__label" for="checkout_email_or_phone">Email</label>
                                       <div class="field__input-wrapper">
                                       <input value="<?=empty($order->email) ? '' : $order->email?>" placeholder="Email" autocomplete="shipping family-name" autocorrect="off" data-backup="email" class="field__input" aria-required="true" size="30" type="text" name="email" required id="checkout_email_or_phone" />
                                       </div>
									                      <p class="field__message field__message--error"><?=$website['error_note2']?></p>
                                    </div>
                                    <div class=" field field--required" data-address-field="phone">
                                       <label class="field__label" for="checkout_shipping_address_phone"><?=$website['order_note4']?></label>
                                       <div class="field__input-wrapper">
                                          <input value="<?=empty($order->phone) ? '' : $order->phone?>" placeholder="<?=$website['order_note4']?>" autocomplete="shipping family-name" autocorrect="off" data-backup="phone" class="field__input" aria-required="true" size="30" type="text" name="phone" required id="checkout_shipping_address_phone" />
                                       </div>
                                       <p class="field__message field__message--error required-error" style="display:none;"><?=$website['error_note3']?></p>
                                       <p class="field__message field__message--error validate-error" style="display:none;"><?=$website['error_note7']?></p>
                                    </div>
                                    <div data-address-field="address" data-autocomplete-field-container="true" class="field field--required">
                                       <label class="field__label" for="checkout_shipping_address_address"><?=$website['order_note5']?></label>
                                       <div class="field__input-wrapper">
                                          <input value="<?=empty($order->address) ? '' : $order->address?>" placeholder="<?=$website['order_note5']?>" autocomplete="shipping address-level2" autocorrect="off" data-backup="address" class="field__input" aria-required="true" size="30" type="text" name="address" required id="checkout_shipping_address_address" />
                                       </div>
                                       <p class="field__message field__message--error"><?=$website['error_note8']?></p>
                                    </div> 
                                    <div data-address-field="city" data-autocomplete-field-container="true" class="field field--required">
                                       <label class="field__label" for="checkout_shipping_address_city"><?=$website['order_note6']?></label>
                                       <div class="field__input-wrapper">
                                          <textarea name="message" id="message" class="field__input" cols="30" rows="3" placeholder="<?=$website['order_note6']?>"></textarea>
                                       </div>
                                       <br>
                                       <div class="g-recaptcha" data-theme="light" data-sitekey="<?=RECAPTCHA_KEY?>"></div>
                                       <p class="field__message field__message--error product-error"><?=$website['error_note18']?></p>
                                       <p class="field__message field__message--error capcha-error"><?=$website['error_note19']?></p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div><br>
                        <div class="step__footer" data-step-footer>
                           <a id="continue_button" class="step__footer__continue-btn btn" aria-busy="false">
                              <span class="btn__content" data-continue-button-content="true"><?=$website['order_note16']?></span>
                           </a>
                           <a class="step__footer__previous-link" href="<?=site_url('gio-hang')?>">
                              <span class="step__footer__previous-link-content"><?=$website['order_note7']?></span>
                           </a>
                        </div>
                  </div>
               </main>
               <footer class="main__footer" role="contentinfo">
                  <p class="copyright-text ">
                     All rights reserved Nguyên Anh Fruit
                  </p>
               </footer>
            </div>
            <aside class="sidebar order-sidebar" role="complementary">
               <div class="sidebar__header">
                  <a class="logo logo--left" href="<?=BASE_URL?>"><span class="logo__text heading-1"><?=SITE_NAME?></span></a>
                  <h1 class="visually-hidden">
                     <?=$website['order_note']?>
                  </h1>
               </div>
               <div class="sidebar__content">
                  <div id="order-summary" class="order-summary order-summary--is-expanded" data-order-summary>
                     <div class="order-summary__sections">
                        <div class="order-summary__section order-summary__section--product-list">
                           <div class="order-summary__section__content">
                            <div class="wrap-order-item">
                                <!-- <div class="order-item order-item-<?//=date('Ymdhis')?>">
                                    <div class="del">Xóa sản phẩm</div>
                                    <div class="link" st="<?//=date('Ymdhis')?>">
                                        <input type="text" value="" placeholder="Url đường dẫn sản phẩm">
                                        <p class="error-url">*Không tìm thấy nội dung</p>
                                    </div>
                                    <div class="box">
                                        <div class="box-left">
                                            <div class="name"><input type="text" name="name[]" placeholder="Nhập tên sản phẩm"></div>
                                            <div class="quantity">
                                                <span class="ctrl-qty">-</span><input type="number" class="qty" min=1 value="1" name="quantity[]"><span class="ctrl-qty">+</span>
                                            </div>
                                        </div>
                                        <div class="box-right">
                                            <label class="custom-file-upload" style="background:url(<?//=IMG_URL.'img-default.png'?>)">
                                                <input type="file" />
                                            </label>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <a href="#" class="btn-addItem">+ <?=$website['order_note17']?></a>
                           </div>
                        </div>
                        <div class="suggest-product">
                            <h3><?=$website['order_note18']?></h3>
                            <div class="row">
                                <? foreach($suggest_products as $suggest_product) { ?>
                                <div class="col-md-4">
                                    <div class="box-suggest-product" data-st="0" data-id="<?=$suggest_product->id?>" data-url="<?=site_url($suggest_product->{$prop['alias']})?>">
                                        <span class="tick-<?=$suggest_product->id?> tick">&#10003;</span>
                                        <div class="box-image">
                                            <img src="<?=$suggest_product->thumbnail?>" alt="">
                                        </div>
                                        <h5 class="name limit-content-2-line">
                                            <?=character_limiter($suggest_product->{$prop['title']},28)?>
                                        </h5>
                                    </div>
                                </div>
                                <? } ?>
                            </div>
                        </div>
                        <script>
                            $(document).on('click','.box-suggest-product',function(e){
                                var st = parseInt($(this).attr('data-st'));
                                var id = parseInt($(this).attr('data-id'));
                                if (st == 0){
                                    var url = $(this).attr('data-url');
                                    $.ajax({
                                        url: '<?=site_url("call-service/fetch-data")?>',
                                        type: 'POST',
                                        dataType: 'json',
                                        data: {'url':url},
                                        success: function (res) {
                                            if (res != 0) {
                                                var str = '<div class="order-item order-item-'+id+'">';
                                                    str += '<div class="del" st="'+id+'">Xóa sản phẩm</div>';
                                                        str += '<div class="link" st="'+id+'"><input type="text" name="url[]" value="'+url+'" placeholder="Url đường dẫn sản phẩm."></div>';
                                                        str += '<div class="box">';
                                                            str += '<div class="box-left">';
                                                                str += '<input type="hidden" name="name[]" class="name-item" value="'+res[0]+'"><div class="name">'+res[0]+'</div>';
                                                            str += '<div class="quantity">';
                                                                str += '<span class="ctrl-qty">-</span><input type="number" class="qty" min=1 value="1" name="quantity[]"><span class="ctrl-qty">+</span>';
                                                            str += '</div>';
                                                        str += '</div>';
                                                        str += '<div class="box-right">';
                                                            str += '<label class="custom-file-upload" style="background:url('+res[1]+')">';
                                                            str += '<input type="hidden" name="url_img[]" class="url-img" value="'+res[1]+'"/>';
                                                            str += '</label>';
                                                        str += '</div>';
                                                    str += '</div>';
                                                str += '</div>';
                                                $('.wrap-order-item').append(str);
                                            }
                                        }
                                    })
                                    $(this).attr('data-st',1);
                                    $(this).find('.tick').addClass('active');
                                } else {
                                    $('.order-item-'+id).remove();
                                    $(this).attr('data-st',0);
                                    $(this).find('.tick').removeClass('active');
                                }
                            });
                        </script>
                     </div>
                  </div>
               </div>
            </aside>
            </form>
         </div>
      </div>
      <!-- <div class="notify">
          <div class="box-notify">
            <img src="<?=IMG_URL?>loading.gif" alt="">
            <h5>&#10004; Thêm thành công</h5>
          </div>
      </div> -->
	  <script>
        $(document).on('change','.link > input',function(e){
            var loading = '<div class="box-loading"><img src="<?=IMG_URL?>loading.gif" alt=""></div>';
            var st = $(this).parents('.link').attr('st');
            $(this).parents('.order-item').find('.error-url').css('display','none');
            $(this).parents('.order-item').find('.box').prepend(loading);
            var str = '<div class="box-left">';
                        str += '<input type="hidden" name="name[]" class="name-item"><div class="name"></div>';
                    str += '<div class="quantity">';
                        str += '<span class="ctrl-qty">-</span><input type="number" class="qty" min=1 value="1" name="quantity[]"><span class="ctrl-qty">+</span>';
                    str += '</div>';
                str += '</div>';
                str += '<div class="box-right">';
                    str += '<label class="custom-file-upload" style="background:url(<?=IMG_URL.'img-default.png'?>)">';
                        str += '<input type="hidden" name="url_img[]" class="url-img" />';
                    str += '</label>';
                str += '</div>';
            $.ajax({
                url: '<?=site_url("call-service/fetch-data")?>',
                type: 'POST',
                dataType: 'json',
                data: {'url':$(this).val()},
                success: function (res) {
                    if (res != 0) {
                        $('.order-item-'+st).find('.name').html(res[0]);
                        $('.order-item-'+st).find('.name-item').val(res[0]);
                        $('.order-item-'+st).find('.custom-file-upload').css('background','url('+res[1]+')');
                        $('.order-item-'+st).find('.url-img').val(res[1]);
                    } else {
                        $('.order-item-'+st).find('.error-url').css('display','block');
                        $('.order-item-'+st).find('.box').html(str);
                    }
                    $('.order-item-'+st).find('.box-loading').remove();
                }
            })
        })
        $('.btn-addItem').click(function(e) {
            var date = new Date();
            var str = '<div class="order-item order-item-'+date.getTime()+'">';
                    str += '<div class="del" st="'+date.getTime()+'" >Xóa sản phẩm</div>';
                        str += '<div class="link" st="'+date.getTime()+'" ><input name="url[]" type="text" value="" placeholder="Url đường dẫn sản phẩm."></div>';
                        str += '<div class="box">';
                            str += '<div class="box-left">';
                                str += '<input type="hidden" name="name[]" class="name-item"><div class="name"></div>';
                            str += '<div class="quantity">';
                                str += '<span class="ctrl-qty">-</span><input type="number" class="qty" min=1 value="1" name="quantity[]"><span class="ctrl-qty">+</span>';
                            str += '</div>';
                        str += '</div>';
                        str += '<div class="box-right">';
                            str += '<label class="custom-file-upload" style="background:url(<?=IMG_URL.'img-default.png'?>)">';
                                str += '<input type="hidden" name="url_img[]" class="url-img" />';
                            str += '</label>';
                        str += '</div>';
                    str += '</div>';
                str += '</div>';
            $('.wrap-order-item').append(str);
        })
        $(document).on('click','.del',function(e){
            $('.tick-'+$(this).attr('st')).removeClass('active');
            $(this).parents('.order-item').remove();
        })
        $(document).on('click','.ctrl-qty',function(e){
            var st = $(this).html();
            var qty = parseInt($(this).parents('.quantity').find('.qty').val());
            if (st == '-'){
                if (qty > 1) {
                    qty -= 1;
                }
            } else {
                qty += 1;
            }
            $(this).parents('.quantity').find('.qty').val(qty);
        })
        $(document).on('change','.qty',function(e){
            var qty = parseInt($(this).val());
            if (qty < 1)
            $(this).val(1);
        })

		  $(document).ready(function() {
            $(document).on('click','#continue_button',function(e) {
				        var err = 0;
                function validatePhone(new_phone) {
                    var a = $('#'+new_phone).val();
                    var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
                    if (filter.test(a)) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                if ($("#checkout_email_or_phone").val() == "") {
                  $("#checkout_email_or_phone").parents('.field').addClass("field--error");
                  err++;
                }
                if ($("#checkout_shipping_address_fullname").val() == "") {
                  $("#checkout_shipping_address_fullname").parents('.field').addClass("field--error");
                  err++;
                }
                if ($("#checkout_shipping_address_phone").val() == "") {
                  $("#checkout_shipping_address_phone").parents('.field').find('.required-error').css('display','block');
                            $("#checkout_shipping_address_phone").parents('.field').find('.validate-error').css('display','none');
                            $("#checkout_shipping_address_phone").parents('.field').addClass("field--error");
                  err++;
                } else {
                    if (validatePhone('checkout_shipping_address_phone') == false) {
                        $("#checkout_shipping_address_phone").parents('.field').find('.required-error').css('display','none');
                        $("#checkout_shipping_address_phone").parents('.field').find('.validate-error').css('display','block');
                        $("#checkout_shipping_address_phone").parents('.field').addClass("field--error");
                        err++;
                    }
                }
                if ($("#checkout_shipping_address_address").val() == "") {
                  $("#checkout_shipping_address_address").parents('.field').addClass("field--error");
                  err++;
                }
                var ek = $('.name-item').map((_,el) => el.value).get();
                if (ek.length == 0 || (ek.length == 1 && ek[0] == '')) {
                    err++;
                    $('.product-error').show();
                }
                if ($('#g-recaptcha-response').val() == "") {
                  err++;
                  $('.capcha-error').show();
                }
                        
                if (err==0) {
                  $("#frm-checkout").submit();
                } else {
                  
                }
              });
            });
	  </script>
      <link href="https://monorail-edge.shopifysvc.com" rel="dns-prefetch">
      <script>window.ShopifyAnalytics = window.ShopifyAnalytics || {};
         window.ShopifyAnalytics.meta = window.ShopifyAnalytics.meta || {};
         window.ShopifyAnalytics.meta.currency = 'USD';
         var meta = {"page":{"path":"\/checkout\/contact_information","search":"","url":"https:\/\/greenbee-4.myshopify.com\/52646772928\/checkouts\/42476d0e258606d0c7fe1ed4f87f7591"},"evids":{"pv":"Page View","so":"Started Order","co":"Completed Order"}};
         for (var attr in meta) {
           window.ShopifyAnalytics.meta[attr] = meta[attr];
         }
      </script>
      <script>window.ShopifyAnalytics.merchantGoogleAnalytics = function() {
         };
      </script>
      <script class="analytics">(function () {
         var customDocumentWrite = function(content) {
           var jquery = null;
         
           if (window.jQuery) {
             jquery = window.jQuery;
           } else if (window.Checkout && window.Checkout.$) {
             jquery = window.Checkout.$;
           }
         
           if (jquery) {
             jquery('body').append(content);
           }
         };
         
         var hasLoggedConversion = function(token) {
           if (token) {
             return document.cookie.indexOf('loggedConversion=' + token) !== -1;
           }
           return false;
         }
         
         var setCookieIfConversion = function(token) {
           if (token) {
             var twoMonthsFromNow = new Date(Date.now());
             twoMonthsFromNow.setMonth(twoMonthsFromNow.getMonth() + 2);
         
             document.cookie = 'loggedConversion=' + token + '; expires=' + twoMonthsFromNow;
           }
         }
         
         var trekkie = window.ShopifyAnalytics.lib = window.trekkie = window.trekkie || [];
         if (trekkie.integrations) {
           return;
         }
         trekkie.methods = [
           'identify',
           'page',
           'ready',
           'track',
           'trackForm',
           'trackLink'
         ];
         trekkie.factory = function(method) {
           return function() {
             var args = Array.prototype.slice.call(arguments);
             args.unshift(method);
             trekkie.push(args);
             return trekkie;
           };
         };
         for (var i = 0; i < trekkie.methods.length; i++) {
           var key = trekkie.methods[i];
           trekkie[key] = trekkie.factory(key);
         }
         trekkie.load = function(config) {
           trekkie.config = config || {};
           trekkie.config.initialDocumentCookie = document.cookie;
           var first = document.getElementsByTagName('script')[0];
           var script = document.createElement('script');
           script.type = 'text/javascript';
           script.onerror = function(e) {
             var scriptFallback = document.createElement('script');
             scriptFallback.type = 'text/javascript';
             scriptFallback.onerror = function(error) {
                     var Monorail = {
             produce: function produce(monorailDomain, schemaId, payload) {
               var currentMs = new Date().getTime();
               var event = {
                 schema_id: schemaId,
                 payload: payload,
                 metadata: {
                   event_created_at_ms: currentMs,
                   event_sent_at_ms: currentMs
                 }
               };
               return Monorail.sendRequest("https://" + monorailDomain + "/v1/produce", JSON.stringify(event));
             },
             sendRequest: function sendRequest(endpointUrl, payload) {
               // Try the sendBeacon API
               if (window && window.navigator && typeof window.navigator.sendBeacon === 'function' && typeof window.Blob === 'function' && !Monorail.isIos12()) {
                 var blobData = new window.Blob([payload], {
                   type: 'text/plain'
                 });
           
                 if (window.navigator.sendBeacon(endpointUrl, blobData)) {
                   return true;
                 } // sendBeacon was not successful
           
               } // XHR beacon   
           
               var xhr = new XMLHttpRequest();
           
               try {
                 xhr.open('POST', endpointUrl);
                 xhr.setRequestHeader('Content-Type', 'text/plain');
                 xhr.send(payload);
               } catch (e) {
                 console.log(e);
               }
           
               return false;
             },
             isIos12: function isIos12() {
               return window.navigator.userAgent.lastIndexOf('iPhone; CPU iPhone OS 12_') !== -1 || window.navigator.userAgent.lastIndexOf('iPad; CPU OS 12_') !== -1;
             }
           };
           Monorail.produce('monorail-edge.shopifysvc.com',
             'trekkie_checkout_load_errors/1.1',
             {shop_id: 52646772928,
             theme_id: 117867217088,
             app_name: "checkout",
             context_url: window.location.href,
             source_url: "https://cdn.shopify.com/s/trekkie.storefront.14090f34a9012f8b63942ff909e7123d74670c9e.min.js"});
         
             };
             scriptFallback.async = true;
             scriptFallback.src = 'https://cdn.shopify.com/s/trekkie.storefront.14090f34a9012f8b63942ff909e7123d74670c9e.min.js';
             first.parentNode.insertBefore(scriptFallback, first);
           };
           script.async = true;
           script.src = 'https://cdn.shopify.com/s/trekkie.storefront.14090f34a9012f8b63942ff909e7123d74670c9e.min.js';
           first.parentNode.insertBefore(script, first);
         };
         trekkie.load(
           {"Trekkie":{"appName":"checkout","development":false,"defaultAttributes":{"shopId":52646772928,"isMerchantRequest":null,"themeId":117867217088,"themeCityHash":"3896954103204361030","contentLanguage":"en","currency":"USD","checkoutToken":"42476d0e258606d0c7fe1ed4f87f7591"},"isServerSideCookieWritingEnabled":true,"expectS2SEventId":true,"expectS2SEventEmit":true},"Session Attribution":{},"S2S":{"facebookCapiEnabled":false,"source":"trekkie-checkout-classic"}}
         );
         
         var loaded = false;
         trekkie.ready(function() {
           if (loaded) return;
           loaded = true;
         
           window.ShopifyAnalytics.lib = window.trekkie;
           
         
           var originalDocumentWrite = document.write;
           document.write = customDocumentWrite;
           try { window.ShopifyAnalytics.merchantGoogleAnalytics.call(this); } catch(error) {};
           document.write = originalDocumentWrite;
             (function () {
               if (window.BOOMR && (window.BOOMR.version || window.BOOMR.snippetExecuted)) {
                 return;
               }
               window.BOOMR = window.BOOMR || {};
               window.BOOMR.snippetStart = new Date().getTime();
               window.BOOMR.snippetExecuted = true;
               window.BOOMR.snippetVersion = 12;
               window.BOOMR.application = "core";
               window.BOOMR.shopId = 52646772928;
               window.BOOMR.themeId = 117867217088;
               window.BOOMR.themeName = "Vinova Minimart";
               window.BOOMR.themeVersion = "1.0.0";
               window.BOOMR.url =
                 "https://cdn.shopify.com/shopifycloud/boomerang/shopify-boomerang-1.0.0.min.js";
               var where = document.currentScript || document.getElementsByTagName("script")[0];
               var parentNode = where.parentNode;
               var promoted = false;
               var LOADER_TIMEOUT = 3000;
               function promote() {
                 if (promoted) {
                   return;
                 }
                 var script = document.createElement("script");
                 script.id = "boomr-scr-as";
                 script.src = window.BOOMR.url;
                 script.async = true;
                 parentNode.appendChild(script);
                 promoted = true;
               }
               function iframeLoader(wasFallback) {
                 promoted = true;
                 var dom, bootstrap, iframe, iframeStyle;
                 var doc = document;
                 var win = window;
                 window.BOOMR.snippetMethod = wasFallback ? "if" : "i";
                 bootstrap = function(parent, scriptId) {
                   var script = doc.createElement("script");
                   script.id = scriptId || "boomr-if-as";
                   script.src = window.BOOMR.url;
                   BOOMR_lstart = new Date().getTime();
                   parent = parent || doc.body;
                   parent.appendChild(script);
                 };
                 if (!window.addEventListener && window.attachEvent && navigator.userAgent.match(/MSIE [67]./)) {
                   window.BOOMR.snippetMethod = "s";
                   bootstrap(parentNode, "boomr-async");
                   return;
                 }
                 iframe = document.createElement("IFRAME");
                 iframe.src = "about:blank";
                 iframe.title = "";
                 iframe.role = "presentation";
                 iframe.loading = "eager";
                 iframeStyle = (iframe.frameElement || iframe).style;
                 iframeStyle.width = 0;
                 iframeStyle.height = 0;
                 iframeStyle.border = 0;
                 iframeStyle.display = "none";
                 parentNode.appendChild(iframe);
                 try {
                   win = iframe.contentWindow;
                   doc = win.document.open();
                 } catch (e) {
                   dom = document.domain;
                   iframe.src = "javascript:var d=document.open();d.domain='" + dom + "';void(0);";
                   win = iframe.contentWindow;
                   doc = win.document.open();
                 }
                 if (dom) {
                   doc._boomrl = function() {
                     this.domain = dom;
                     bootstrap();
                   };
                   doc.write("<body onload='document._boomrl();'>");
                 } else {
                   win._boomrl = function() {
                     bootstrap();
                   };
                   if (win.addEventListener) {
                     win.addEventListener("load", win._boomrl, false);
                   } else if (win.attachEvent) {
                     win.attachEvent("onload", win._boomrl);
                   }
                 }
                 doc.close();
               }
               var link = document.createElement("link");
               if (link.relList &&
                 typeof link.relList.supports === "function" &&
                 link.relList.supports("preload") &&
                 ("as" in link)) {
                 window.BOOMR.snippetMethod = "p";
                 link.href = window.BOOMR.url;
                 link.rel = "preload";
                 link.as = "script";
                 link.addEventListener("load", promote);
                 link.addEventListener("error", function() {
                   iframeLoader(true);
                 });
                 setTimeout(function() {
                   if (!promoted) {
                     iframeLoader(true);
                   }
                 }, LOADER_TIMEOUT);
                 BOOMR_lstart = new Date().getTime();
                 parentNode.appendChild(link);
               } else {
                 iframeLoader(false);
               }
               function boomerangSaveLoadTime(e) {
                 window.BOOMR_onload = (e && e.timeStamp) || new Date().getTime();
               }
               if (window.addEventListener) {
                 window.addEventListener("load", boomerangSaveLoadTime, false);
               } else if (window.attachEvent) {
                 window.attachEvent("onload", boomerangSaveLoadTime);
               }
               if (document.addEventListener) {
                 document.addEventListener("onBoomerangLoaded", function(e) {
                   e.detail.BOOMR.init({
                     producer_url: "https://monorail-edge.shopifysvc.com/v1/produce",
                     ResourceTiming: {
                       enabled: true,
                       trackedResourceTypes: ["script", "img", "css"]
                     },
                   });
                   e.detail.BOOMR.t_end = new Date().getTime();
                 });
               } else if (document.attachEvent) {
                 document.attachEvent("onpropertychange", function(e) {
                   if (!e) e=event;
                   if (e.propertyName === "onBoomerangLoaded") {
                     e.detail.BOOMR.init({
                       producer_url: "https://monorail-edge.shopifysvc.com/v1/produce",
                       ResourceTiming: {
                         enabled: true,
                         trackedResourceTypes: ["script", "img", "css"]
                       },
                     });
                     e.detail.BOOMR.t_end = new Date().getTime();
                   }
                 });
               }
             })();
           
         
           window.ShopifyAnalytics.lib.page("Checkout - Contact information",{"path":"\/checkout\/contact_information","search":"","url":"https:\/\/greenbee-4.myshopify.com\/52646772928\/checkouts\/42476d0e258606d0c7fe1ed4f87f7591"});
         
           var match = window.location.pathname.match(/checkouts\/(.+)\/(thank_you|post_purchase)/)
           var token = match? match[1]: undefined;
           if (!hasLoggedConversion(token)) {
             setCookieIfConversion(token);
             window.ShopifyAnalytics.lib.track("Started Order",{"step":1,"products":[{"variantId":37912779030720,"productId":6153538568384,"productGid":"gid:\/\/shopify\/Product\/6153538568384","category":"","sku":"e-20","name":"Ziamond Ralo Stud Farrings","price":"22.00","quantity":1,"brand":"Vinova-Greenbee","variant":""}],"currency":"USD","revenue":"22.00"});
           }
         });
         
         
             var eventsListenerScript = document.createElement('script');
             eventsListenerScript.async = true;
             eventsListenerScript.src = "//cdn.shopify.com/shopifycloud/shopify/assets/shop_events_listener-565deac0c7edc7850a7762c24c560f0a9670aa5c52a728e9dbb43d5a7887c1d4.js";
             document.getElementsByTagName('head')[0].appendChild(eventsListenerScript);
           
         })();
      </script>
   </body>
</html>