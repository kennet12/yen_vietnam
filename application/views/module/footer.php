<?
$setting = $this->m_setting->load(1);

$lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
$this->lang->load('content',$lang);
$menu				= $this->lang->line('menu');
$website			= $this->lang->line('website');
$prop				= $this->lang->line('prop');
$alias			= $this->lang->line('alias');
$posts = $this->m_post->items(null,1);

$info = new stdClass();
$info->parent_id = 0;
$product_categories = $this->m_product_category->items($info,1);
?>
<div class="footer_newsletter pt-120">
   <div class="container">
      <div class="b_news text-center pb-65">
         <div class="box-icon-news">
            <div class="bg-box-icon-news"></div>
            <img src="<?=IMG_URL?>subscribe.svg" class="icon_news" alt="img">
         </div>
         <div class="title-block mb-5">
            <?=$website['subscribe']?>
         </div>
         <div class="des mb-35"><?=$website['subscribe_note']?></div>
         <div class="section-content">
            <div class="input-group">
               <input type="hidden" name="contact[tags]" value="newsletter">
               <input type="email" name="submail" id="Email_Footer" class="input-group__field newsletter__input form-control" value="" placeholder="<?=$website['enter_email']?>">
               <div class="input-group__btn">
                  <button type="submit" class="btn newsletter__submit" name="commit" id="Subscribe_footer">
                  <span class="newsletter__submit-text--large"><?=$website['subscribe_btn']?></span>
                  </button>
               </div>
            </div>
            <script type="text/javascript">
					$(document).ready(function() {
						$('#Subscribe_footer').click(function(event) {
							var e = $('#Email_Footer').val();
							var err = 0;
							var msg = [];

							clearFormError();

							if (e == "") {
								err++;
								msg.push("<?=$website['error_note2']?>");
							} else {
								if (!isEmail(e)) {
									err++;
									msg.push("<?=$website['error_note16']?>");
								}
							}
							if (!err) {
								var p = {};
									p['email'] = e;
								$.ajax({
									url: '<?=site_url("trang-chu/subscribe-email")?>',
									type: 'POST',
									dataType: 'json',
									data: p,
									success: function (r) {
										if (r) {
                                 messageBox("INFO", "<?=$website['success']?>", "<?=$website['success_note1']?>", "<?=$website['lose']?>");
										} else {
											msg.push("<?=$website['error_note17']?>");
                                 showErrorMessage('<?=$website['error']?>','<?=$website['error_note']?>', msg, '<?=$website['lose']?>');
										}
									}
								})
								
							} else {
                        showErrorMessage('<?=$website['error']?>','<?=$website['error_note']?>', msg, '<?=$website['lose']?>');
							}
						});
					});
				</script>
         </div>
      </div>
   </div>
   <div class="footer_policy pt-80 pb-30 mt-75">
      <div class="container">
         <div class="row">
            <div class="nov-policy-item d-flex d-md-block d-lg-flex align-items-center col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
               <div class="b_icon nv-mr-20 mb-md-10">
                  <img src="<?=IMG_URL?>p-1_45x.png" alt="icon_policy">
               </div>
               <div>
                  <div class="title-policy"><?=$website['cash_on_delivery']?></div>
                  <div class="desc-policy"><?=$website['cash_on_delivery_note']?></div>
               </div>
            </div>
            <div class="nov-policy-item d-flex d-md-block d-lg-flex align-items-center col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
               <div class="b_icon nv-mr-20 mb-md-10">
                  <img src="<?=IMG_URL?>p-2_45x.png" alt="icon_policy">
               </div>
               <div>
                  <div class="title-policy"><?=$website['fast_shipping']?></div>
                  <div class="desc-policy"><?=$website['fast_shipping_note']?></div>
               </div>
            </div>
            <div class="nov-policy-item d-flex d-md-block d-lg-flex align-items-center col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
               <div class="b_icon nv-mr-20 mb-md-10">
                  <img src="<?=IMG_URL?>p-3_45x.png" alt="icon_policy">
               </div>
               <div>
                  <div class="title-policy"><?=$website['refun_money']?></div>
                  <div class="desc-policy"><?=$website['refun_money_note']?></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="footer-layout">
   <div class="container">
      <div class="row">
         <div class="footer_list_menu pt-55 pb-50 pt-lg-30 pb-lg-30 col-xl-3 col-lg-4 col-md-4 col-sm-4 col-xs-12">
         <img class="js img-fluid" src="<?=IMG_URL.'logo.jpg?v='.CACHE_TIME?>" style="width:170px" alt="Greenbee 4">
         </div>
         <div class="footer_list_menu pt-55 pb-50 pt-lg-30 pb-lg-30 col-xl-3 col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="title-block"><?=$website['about_us']?></div>
            <ul class="site-footer__linklist list-unstyled">
               <? foreach($posts as $post) { ?>
               <li class="site-footer__linklist-item">
                  <a href="<?=site_url($post->{$prop['alias']})?>"><?=$post->{$prop['title']}?></a>
               </li>
               <? } ?>
            </ul>
         </div>
         <div class="footer_list_menu pt-55 pb-50 pt-lg-30 pb-lg-30 col-xl-3 col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="title-block"><?=$website['product_us']?></div>
            <ul class="site-footer__linklist list-unstyled">
               <? foreach($product_categories as $product_category) { ?>
               <li class="site-footer__linklist-item">
                  <a href="<?=site_url("{$alias['product']}/{$product_category->{$prop['alias']}}")?>"><?=$product_category->{$prop['name']}?></a>
               </li>
               <? } ?>
               <!-- <li class="site-footer__linklist-item">
                  <a href="<?=site_url("{$alias['new']}")?>"><?=$menu['new']?></a>
               </li> -->
               <li class="site-footer__linklist-item">
                  <a href="<?=site_url("{$alias['contact']}")?>"><?=$menu['contact']?></a>
               </li>
            </ul>
         </div>
         <div class="footer-contact pt-55 pb-50 pt-lg-30 pb-lg-30 col-xl-3 col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="title-block">
               <?=$website['contact']?>
            </div>
            <div class="block-content">
               <div class="contact phone">
                  <i class="zmdi zmdi-phone-in-talk"></i><?=$setting->company_hotline?></div>
               <div class="contact email"><i class="zmdi zmdi-email"></i><?=$setting->company_email?></div>
               <div class="contact address"><i class="zmdi zmdi-pin"></i><?=$setting->company_address?></div>
               <div class="contact open"><i class="zmdi zmdi-time"></i><?=$website['open_hour']?></div>
            </div>
            <ul class="socical">
               <li class="socical-item">
                  <a target="_blank" href="<?=$setting->facebook_url?>" title="Facebook">
                  <svg style="width:30px;height:30px" version="1.0" xmlns="http://www.w3.org/2000/svg" width="309.000000pt" height="309.000000pt" viewBox="0 0 309.000000 309.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,309.000000) scale(0.100000,-0.100000)" fill="#1877f2" stroke="none"> 
                     <path d="M1400 3069 c-365 -35 -676 -183 -940 -448 -226 -227 -353 -464 -422
-784 -29 -137 -31 -426 -5 -562 80 -408 292 -746 616 -979 83 -59 229 -140
321 -177 67 -26 230 -74 285 -83 l40 -7 4 533 3 534 -53 -1 c-30 0 -118 1
-196 3 l-143 2 0 225 0 225 195 0 195 0 0 205 c0 240 13 323 67 435 42 87 130
180 210 223 84 45 190 67 322 67 105 0 241 -11 314 -26 l27 -6 0 -141 c0 -78
-3 -165 -6 -194 l-7 -52 -131 1 c-147 1 -187 -9 -244 -65 -50 -49 -62 -104
-62 -294 0 -114 3 -162 11 -158 6 4 100 4 211 0 l200 -8 -7 -61 c-4 -33 -19
-133 -33 -221 l-27 -160 -176 -3 -176 -2 -6 -527 c-4 -289 -5 -528 -2 -531 8
-8 124 18 225 50 742 237 1187 987 1039 1756 -58 299 -200 564 -423 788 -263
262 -567 405 -945 443 -114 12 -156 12 -281 0z"/>
</g>
</svg>
                  </a>
               </li>
               <li class="socical-item">
                  <a target="_blank"  href="viber://add?number=0932177566" title="Viber">
                  <svg style="width: 30px;height: 35px;margin-top: 3px;" version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="317.000000pt" height="359.000000pt" viewBox="0 0 317.000000 359.000000"
 preserveAspectRatio="xMidYMid meet">
<metadata>
Created by potrace 1.16, written by Peter Selinger 2001-2019
</metadata>
<g transform="translate(0.000000,359.000000) scale(0.100000,-0.100000)"
fill="#953fde" stroke="none">
<path d="M1350 3492 c0 -5 -60 -14 -132 -22 -73 -7 -144 -14 -158 -15 -14 -1
-32 -6 -41 -9 -9 -4 -31 -8 -50 -10 -19 -2 -45 -8 -59 -13 -14 -6 -29 -7 -34
-3 -5 5 -16 2 -24 -7 -8 -8 -26 -13 -38 -10 -13 2 -25 0 -26 -4 -2 -5 -14 -10
-28 -11 -14 -2 -27 -6 -30 -10 -3 -4 -15 -9 -25 -11 -11 -2 -30 -8 -41 -15
-12 -6 -24 -8 -28 -5 -3 3 -6 1 -6 -5 0 -7 -9 -12 -19 -12 -11 0 -23 -5 -26
-11 -4 -6 -13 -8 -21 -5 -8 3 -14 1 -14 -4 0 -6 -7 -10 -15 -10 -8 0 -15 -3
-15 -8 0 -4 -17 -16 -37 -27 -20 -11 -58 -37 -84 -57 -31 -24 -49 -33 -52 -25
-4 8 -6 7 -6 -3 -1 -8 -18 -34 -39 -56 -123 -135 -220 -354 -257 -576 -3 -21
-11 -38 -18 -38 -8 0 -9 -3 -1 -8 7 -5 8 -26 3 -67 -5 -33 -11 -89 -13 -125
-6 -116 -6 -577 0 -594 4 -9 4 -32 1 -50 -3 -19 -2 -38 3 -42 14 -16 21 -104
7 -104 -8 0 -8 -3 2 -9 7 -5 17 -26 21 -47 43 -208 139 -395 269 -521 35 -35
65 -63 67 -63 2 0 31 -20 66 -44 35 -24 95 -56 133 -71 39 -15 75 -32 82 -37
7 -5 16 -7 21 -5 4 3 14 2 21 -2 10 -7 13 -78 12 -344 -1 -225 2 -343 9 -357
28 -52 67 -20 290 239 30 35 64 74 75 87 11 12 27 31 35 41 8 10 56 66 105
125 l89 108 51 -1 c27 0 97 -4 155 -8 68 -5 107 -5 111 2 4 6 44 8 102 5 53
-3 100 -1 104 3 5 5 43 10 85 13 42 2 81 7 86 10 6 3 30 7 54 7 23 1 48 5 53
9 20 14 74 19 78 7 2 -8 9 -6 22 6 11 12 27 17 42 14 12 -2 23 -1 23 3 0 14
63 21 72 8 5 -9 8 -8 8 5 0 9 8 18 18 19 25 2 35 4 54 12 9 4 22 9 30 10 7 2
21 6 30 10 9 4 22 5 28 1 5 -3 10 -2 10 3 0 4 26 19 58 32 31 12 66 31 77 40
11 9 36 26 55 38 20 11 46 32 59 46 13 14 28 23 32 20 5 -3 9 -1 9 4 0 5 14
25 31 43 116 130 209 342 235 537 3 28 9 52 13 52 3 0 7 35 9 77 1 42 6 81 11
87 11 14 12 647 1 661 -5 6 -10 37 -12 70 -2 33 -6 62 -9 65 -3 3 -14 46 -24
95 -39 194 -133 397 -234 507 -49 54 -178 148 -201 148 -6 0 -10 5 -10 10 0 6
-9 10 -20 10 -11 0 -20 4 -20 10 0 5 -7 7 -15 4 -8 -4 -17 1 -21 9 -3 9 -12
13 -19 10 -8 -2 -20 -1 -27 3 -7 5 -19 9 -27 11 -8 1 -17 5 -20 10 -3 4 -17 9
-31 11 -14 1 -26 6 -28 11 -2 5 -11 7 -21 4 -10 -2 -25 2 -33 11 -9 8 -24 12
-35 10 -12 -3 -28 2 -38 13 -11 10 -20 13 -22 7 -4 -11 -63 -7 -63 5 0 3 -19
7 -43 8 -23 2 -43 7 -45 13 -2 5 -13 8 -25 5 -12 -3 -44 -1 -72 4 -27 6 -106
13 -175 16 -69 3 -129 10 -135 16 -12 12 -135 12 -135 -1 0 -6 -30 -10 -70
-10 -40 0 -70 4 -70 10 0 6 -18 10 -40 10 -22 0 -40 -4 -40 -8z m375 -437
c390 -63 702 -341 800 -711 20 -75 45 -244 45 -306 0 -74 -47 -111 -88 -70
-15 15 -20 41 -25 123 -13 205 -85 406 -198 547 -63 79 -191 180 -284 226
-103 50 -234 86 -343 93 -70 5 -99 11 -118 25 -28 23 -30 35 -8 66 13 18 25
22 72 22 31 0 98 -7 147 -15z m-791 -109 c54 -22 237 -259 306 -395 18 -35 21
-56 18 -100 -5 -55 -6 -56 -87 -131 -100 -92 -106 -113 -66 -216 36 -91 107
-211 172 -289 94 -114 265 -229 398 -267 80 -23 107 -12 194 80 84 89 115 103
187 83 83 -22 398 -252 430 -313 26 -50 14 -117 -31 -185 -87 -132 -211 -223
-302 -223 -122 0 -551 218 -813 413 -187 140 -384 360 -525 587 -77 125 -226
418 -253 499 -40 120 -24 208 54 300 70 82 206 170 262 171 12 0 38 -6 56 -14z
m856 -156 c137 -33 221 -80 320 -179 106 -106 153 -191 181 -324 25 -124 25
-191 -1 -217 -25 -25 -41 -25 -68 0 -18 17 -22 32 -22 83 -1 148 -61 288 -170
391 -89 83 -170 122 -318 151 -130 26 -150 42 -122 96 14 26 88 25 200 -1z
m-9 -250 c149 -28 246 -134 268 -292 10 -73 -4 -108 -43 -108 -33 0 -52 27
-61 85 -9 68 -30 114 -68 153 -31 32 -105 62 -156 62 -74 0 -84 108 -10 110 9
0 40 -5 70 -10z"/>
</g>
</svg>
                  </a>
                  
               </li>
               <li class="socical-item">
                  <a target="_blank"  href="<?=$setting->youtube_url?>" title="Youtube">
                  <svg style="width:30px;height:30px" version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="309.000000pt" height="309.000000pt" viewBox="0 0 309.000000 309.000000"
 preserveAspectRatio="xMidYMid meet">
<metadata>
Created by potrace 1.16, written by Peter Selinger 2001-2019
</metadata>
<g transform="translate(0.000000,309.000000) scale(0.100000,-0.100000)"
fill="#d30f1b" stroke="none">
<path d="M1358 3085 c-2 -3 -21 -7 -41 -9 -20 -3 -52 -8 -69 -11 -18 -3 -42
-8 -53 -10 -11 -2 -20 -4 -20 -5 0 -1 -9 -3 -20 -4 -18 -2 -124 -38 -210 -71
-49 -18 -177 -85 -222 -117 -24 -16 -43 -25 -43 -21 0 4 -4 3 -8 -2 -4 -6 -25
-23 -47 -40 -22 -16 -45 -34 -51 -40 -6 -5 -38 -32 -70 -60 -31 -27 -67 -61
-78 -75 -12 -13 -32 -36 -46 -50 -71 -75 -161 -213 -225 -345 -74 -152 -104
-247 -141 -440 -10 -49 -10 -407 -1 -455 4 -19 9 -48 12 -65 16 -99 102 -339
153 -430 19 -33 40 -69 46 -80 10 -19 95 -141 121 -173 5 -8 51 -53 100 -102
79 -77 110 -106 162 -150 7 -5 17 -10 23 -10 5 0 10 -4 10 -8 0 -4 15 -16 32
-27 18 -10 35 -21 38 -24 5 -6 61 -37 125 -70 16 -9 33 -20 37 -26 5 -6 8 -6
8 -1 0 6 5 5 12 -2 7 -7 20 -12 30 -12 10 0 18 -5 18 -10 0 -6 3 -9 8 -8 4 1
21 -3 37 -8 17 -6 32 -11 35 -12 3 0 30 -10 60 -22 30 -12 56 -21 58 -21 2 1
7 0 27 -3 6 -1 36 -8 68 -15 93 -20 122 -23 302 -23 178 0 229 4 298 23 23 6
52 11 65 11 12 0 22 5 22 10 0 5 4 7 9 3 5 -3 32 4 59 14 27 11 53 17 56 14 3
-4 6 -2 6 4 0 9 54 28 73 24 4 0 7 3 7 7 0 5 19 15 43 22 23 8 49 19 57 26 8
6 41 27 73 45 116 67 150 90 160 106 4 7 12 13 17 13 5 0 51 39 101 88 50 48
97 91 105 95 8 4 14 13 14 20 0 7 16 29 35 50 19 21 35 40 35 42 0 2 21 34 47
72 38 56 143 252 143 267 0 3 5 17 12 33 6 15 12 30 13 33 1 3 7 25 14 50 7
25 16 52 18 60 3 8 7 24 8 36 2 11 8 39 15 62 19 74 22 113 22 302 0 163 -4
233 -12 250 -1 3 -3 10 -4 15 -1 6 -6 30 -11 55 -5 25 -8 49 -6 54 1 5 -2 12
-6 15 -5 3 -15 31 -22 61 -8 30 -17 62 -22 70 -4 8 -29 62 -55 120 -54 116
-117 221 -175 291 -22 26 -39 49 -39 52 0 3 -43 47 -96 98 -126 123 -132 129
-144 129 -5 0 -13 6 -17 13 -4 7 -37 31 -73 52 -36 21 -75 47 -86 58 -12 10
-28 19 -35 21 -8 1 -45 17 -83 36 -38 19 -74 35 -80 37 -6 1 -45 14 -86 28
-41 14 -84 27 -95 30 -11 2 -20 4 -20 5 0 1 -9 3 -20 4 -11 2 -33 6 -50 10
-16 3 -46 9 -65 13 -41 7 -396 14 -402 8z m873 -951 c67 -31 124 -87 156 -152
l28 -57 0 -390 c0 -389 0 -390 -24 -437 -31 -64 -95 -126 -161 -157 l-55 -26
-640 0 -640 0 -55 26 c-74 34 -120 76 -156 141 l-29 53 -3 384 c-3 349 -1 389
15 434 20 56 89 139 137 164 80 42 86 42 746 40 625 -2 636 -2 681 -23z"/>
<path d="M1348 1541 c-2 -133 0 -241 3 -241 8 0 441 229 451 238 4 4 -96 60
-222 125 l-229 118 -3 -240z"/>
</g>
</svg>
                  </a>
               </li>
               <li class="socical-item">
                  <a target="_blank"  href="https://zalo.me/0932177566" title="Zalo">
                  <svg style="width:30px;height:30px" version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="309.000000pt" height="309.000000pt" viewBox="0 0 309.000000 309.000000"
 preserveAspectRatio="xMidYMid meet">
<metadata>
Created by potrace 1.16, written by Peter Selinger 2001-2019
</metadata>
<g transform="translate(0.000000,309.000000) scale(0.100000,-0.100000)"
fill="#005be0" stroke="none">
<path d="M629 3079 c-181 -19 -300 -72 -422 -188 -64 -61 -86 -90 -122 -165
-85 -172 -79 -95 -82 -1141 -3 -1082 -5 -1053 87 -1236 42 -83 171 -212 258
-258 170 -89 231 -93 1277 -89 850 4 861 4 944 26 46 12 116 39 155 60 86 45
203 153 250 231 66 110 106 264 106 413 l0 60 -67 -62 c-219 -201 -548 -332
-927 -369 -402 -40 -836 40 -1127 208 l-72 41 -52 -19 c-114 -43 -333 -68
-350 -41 -4 6 7 28 24 49 57 71 86 148 86 226 -1 67 -3 74 -52 152 -203 327
-273 816 -182 1268 66 329 236 627 447 786 31 24 61 47 66 51 12 10 -142 8
-245 -3z"/>
<path d="M2040 1717 c0 -269 3 -366 12 -375 7 -7 34 -12 60 -12 l48 0 0 375 0
375 -60 0 -60 0 0 -363z"/>
<path d="M730 2008 l0 -63 196 3 c108 2 200 0 205 -5 4 -4 -87 -126 -203 -270
-176 -221 -211 -269 -215 -303 l-6 -40 296 0 c283 0 297 1 307 19 5 11 10 36
10 55 l0 36 -215 0 c-118 0 -215 2 -215 5 0 3 92 122 205 265 203 257 225 289
225 336 l0 24 -295 0 -295 0 0 -62z"/>
<path d="M1559 1887 c-92 -35 -146 -87 -180 -177 -89 -233 156 -462 383 -358
l54 25 10 -21 c7 -16 19 -22 57 -24 l47 -3 0 278 0 278 -55 0 c-47 0 -55 -3
-55 -18 0 -10 -3 -17 -7 -15 -89 46 -188 59 -254 35z m161 -119 c105 -53 131
-182 54 -267 -44 -50 -82 -65 -145 -59 -145 14 -207 189 -104 293 57 57 125
68 195 33z"/>
<path d="M2425 1876 c-219 -102 -226 -416 -11 -521 147 -72 309 -20 383 124
61 117 40 254 -53 343 -84 80 -216 103 -319 54z m195 -108 c131 -66 130 -246
-1 -314 -40 -20 -113 -18 -159 6 -99 51 -120 202 -39 277 63 57 127 67 199 31z"/>
</g>
</svg>
                  </a>
               </li>
            </ul>
         </div>
      </div>
   </div>
</div>
<div class="footer_copyright pt-20 pb-20 pb-sm-80">
   <div class="container">
      <div class="row align-items-center">
         <div class="copyright col-md-6 mb-sm-15" style="color: #666666;">Copyright © <?=date('Y')?> <?=SITE_NAME?>. All rights reserved.</div>
         <div class="col-md-6 d-flex justify-content-center justify-content-lg-end"><img class="img-fluid" src="<?=IMG_URL?>payment_500x.png"></div>
      </div>
   </div>
</div>
<style>
   .footer-layout {
   background-color: #fff;
   color: #7a5935;
   }
   .footer_newsletter {
   background-image: url("<?=IMG_URL?>img-4_1920x.jpg");
   background-size: cover;
   background-repeat: no-repeat;
   }
   .footer-layout .title-block {
   color: #5a4023;
   }
   .footer_menu li a, .footer_blog .article__title a, .footer_list_menu li a {
   color: #7a5935;
   }
   .footer_copyright {
   background-color: #ffffff;
   }
   .footer_policy {
   background: url("<?=IMG_URL?>img-10_1920x.png") no-repeat;
   background-size: cover;
   }
   .footer_policy .b_icon {background: url("<?=IMG_URL?>bicon_policy_85x.png") no-repeat;}
</style>