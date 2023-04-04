<?
$setting = $this->m_setting->load(1);
if (!empty($_GET['af']) && empty($_COOKIE['af'])){
  setcookie("af", $_GET['af'], time() + (10 * 365 * 24 * 60 * 60));
  $affiliate_item = $this->m_affiliate_analytic->load_item($_GET['af']);
  if(!empty($affiliate_item)){
    $data = array(
      'approach' => $affiliate_item->approach+1,
    );
    $this->m_affiliate_analytic->update($data,array("id"=>$affiliate_item->id));
  }
}
// $meta_info = new stdClass();
// $meta_info->url = $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
// $configured_metas = $this->m_meta->items($meta_info, 1);

if (empty($meta['title'])) {
	$meta['title'] = $setting->company_name;
}
if (empty($meta['keywords'])) {
	$meta['keywords'] = $setting->tags;
}
if (empty($meta['description'])) {
	$meta['description'] = SITE_NAME." chuyên cung cấp các loại thực phẩm chức năng cao cấp.";
}
if (empty($meta['author'])) {
	$meta['author'] = SITE_NAME;
}
if (!empty($meta_title)) {
	$meta['title'] = $meta_title;
}
if (!empty($meta_key)) {
	$meta['keywords'] = $meta_key;
}
if (!empty($meta_des)) {
	$meta['description'] = $meta_des;
}
// if (!empty($configured_metas)) {
// 	$configured_meta = array_shift($configured_metas);
// 	$meta['title'] = $configured_meta->title;
// 	$meta['keywords'] = $configured_meta->keywords;
// 	$meta['description'] = $configured_meta->description;
// }
$meta_img = !empty($meta_img)?BASE_URL.str_replace('./','/',$meta_img):IMG_URL.'bird-nest.jpeg'
?>
<title><?=$meta['title']?></title>
<meta name="google" content="notranslate">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Cache-Control" content="must-revalidate">
<meta http-equiv="Expires" content="<?=gmdate("d/m/Y - H:i:s", time() + (7*60*60))?> GMT">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="description" content="<?=$meta['description']?>" />
<meta name="keywords" content="<?=$meta['keywords']?>" />
<link rel="canonical" href="<?=BASE_URL.$_SERVER["REQUEST_URI"]?>">
<link rel="preload" href="<?=$meta_img?>" as="image" media="(max-width: 600px)" type="image/jpeg">
<meta property="og:title" content="<?=$meta['title']?> | <?=SITE_NAME?>">
<meta property="og:description" content="<?=$meta['description']?>">
<meta property="og:image" content="<?=$meta_img?>">
<meta property="og:url" content="<?=BASE_URL.$_SERVER["REQUEST_URI"]?>">
<meta property="og:site_name" content="<?=WEBSITE?>">
<meta property="og:image:type" content="image/jpeg"/>
<meta property="og:type" content="website">
<meta property="og:locale" content="vi_VN">
<meta name="robots" content="index,follow" />
<meta name="googlebot" content="index,follow" />
<meta name="author" content="<?=$meta['author']?>" />
<meta name="google-site-verification" content="HEh7pVJ5ODYj59f4t9NqWvMq1spvCvaIVF_UI4UMJU4" />

<link rel="SHORTCUT ICON" href="<?=BASE_URL?>/favicon.ico?v=<?=CACHE_TIME?>"/>
<link rel="manifest" href="<?=BASE_URL?>/manifest.json">
<link rel="icon" sizes="192x192" href="<?=IMG_URL?>launcher/192x192.jpg?v=<?=CACHE_TIME?>">
<link rel="icon" sizes="128x128" href="<?=IMG_URL?>launcher/128x128.jpg?v=<?=CACHE_TIME?>">
<link rel="apple-touch-icon" sizes="128x128" href="<?=IMG_URL?>launcher/128x128.jpg?v=<?=CACHE_TIME?>">
<link rel="apple-touch-icon-precomposed" sizes="128x128" href="<?=IMG_URL?>launcher/128x128.jpg?v=<?=CACHE_TIME?>">
<link href="<?=CSS_URL?>front-end/bootstrap.min.css?v=<?=CACHE_TIME?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=CSS_URL?>front-end/common.css?v=<?=CACHE_TIME?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=CSS_URL?>front-end/owl.carousel.min.css?v=<?=CACHE_TIME?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=CSS_URL?>front-end/owl.theme.default.css?v=<?=CACHE_TIME?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=CSS_URL?>front-end/slick.css?v=<?=CACHE_TIME?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=CSS_URL?>front-end/jquery.mmenu.all.css?v=<?=CACHE_TIME?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=CSS_URL?>front-end/layout.css?v=<?=CACHE_TIME?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=CSS_URL?>front-end/theme.css?v=<?=CACHE_TIME?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=CSS_URL?>front-end/responsive.css?v=<?=CACHE_TIME?>" rel="stylesheet" type="text/css" media="all">
    
<script>
  var theme = {
    strings: {
      addressNoResults: "<?=$setting->company_address?>",
    },
  }
</script>
<script src="<?=JS_URL?>front-end/jquery.min.js?v=<?=CACHE_TIME?>" type="text/javascript"></script>
<script src="<?=JS_URL?>front-end/vendor.js?v=<?=CACHE_TIME?>" defer="defer"></script>
<script src="<?=JS_URL?>front-end/history.js?v=<?=CACHE_TIME?>" type="text/javascript"></script>
<script src="<?=JS_URL?>front-end/jquery.owl.carousel.min.js?v=<?=CACHE_TIME?>" defer="defer"></script>
<script src="<?=JS_URL?>front-end/jquery.mmenu.all.min.js?v=<?=CACHE_TIME?>" defer="defer"></script>
<script src="<?=JS_URL?>util.js?v=<?=CACHE_TIME?>" type="text/javascript"></script>
<script src="<?=JS_URL?>front-end/theme.js?v=<?=CACHE_TIME?>" defer="defer"></script>
<script src="<?=JS_URL?>front-end/global.js?v=<?=CACHE_TIME?>" defer="defer"></script>


<script type="text/javascript">
	var BASE_URL = "<?=BASE_URL?>";
</script>