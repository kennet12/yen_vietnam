<?
$setting = $this->m_setting->load(1);
$meta_info = new stdClass();
$meta_info->url = $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
$configured_metas = $this->m_meta->items($meta_info, 1);

if (empty($meta['title'])) {
	$meta['title'] = $setting->company_name;
}
if (empty($meta['keywords'])) {
	$meta['keywords'] = $setting->tags;
}
if (empty($meta['description'])) {
	$meta['description'] = "Nguyên Anh store, chuyên cung cấp hàng hóa chính hãng AU,EU";
}
if (empty($meta['author'])) {
	$meta['author'] = SITE_NAME;
}

if (!empty($configured_metas)) {
	$configured_meta = array_shift($configured_metas);
	$meta['title'] = $configured_meta->title;
	$meta['keywords'] = $configured_meta->keywords;
	$meta['description'] = $configured_meta->description;
	$meta['addition_tags'] = $configured_meta->addition_tags;
}
?>
<title><?=$meta['title']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Cache-Control" content="must-revalidate">
<meta http-equiv="Expires" content="<?=gmdate("d/m/Y - H:i:s", time() + (7*60*60))?> GMT">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?=$meta['description']?>" />
<meta name="keywords" content="<?=$meta['keywords']?>" />
<meta name="news_keywords" content="<?=$meta['keywords']?>" />
<? if (strtolower($this->router->fetch_class()) == "syslog") { ?>
<meta name="robots" content="noindex,nofollow" />
<? } else { ?>
<meta name="robots" content="index,follow" />
<meta name="googlebot" content="index,follow" />
<? } ?>
<meta name="author" content="<?=$meta['author']?>" />
<meta name="google-site-verification" content="tnncyzOGknUawEVY2xzJj69JANSPSLehgSK4IQVt79I" />

<link rel="SHORTCUT ICON" href="<?=BASE_URL?>/favicon.ico"/>
<!-- <link rel="manifest" href="<?=BASE_URL?>/manifest.json"> -->
<link rel="icon" sizes="192x192" href="<?=IMG_URL?>launcher/icon-192x192.png">
<link rel="icon" sizes="128x128" href="<?=IMG_URL?>launcher/icon-128x128.png">
<link rel="apple-touch-icon" sizes="128x128" href="<?=IMG_URL?>launcher/icon-128x128.png">
<link rel="apple-touch-icon-precomposed" sizes="128x128" href="<?=IMG_URL?>launcher/icon-128x128.png">
<link rel="stylesheet" type="text/css" media="screen,all" href="<?=CSS_URL?>bootstrap.min.css?cr=<?=CACHE_RAND?>" />
<link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="screen,all" href="<?=CSS_URL?>bootstrap.vertical-tabs.min.css?cr=<?=CACHE_RAND?>" />
<link rel="stylesheet" type="text/css" media="screen,all" href="<?=CSS_URL?>font-awesome.min.css?cr=<?=CACHE_RAND?>">
<link rel="stylesheet" type="text/css" media="screen,all" href="<?=CSS_URL?>animate.css?cr=<?=CACHE_RAND?>" />
<!-- <link rel="stylesheet" type="text/css" media="screen,all" href="<?=CSS_URL?>../flexslider/css/flexslider.css?cr=<?=CACHE_RAND?>" /> -->
<!-- <link rel="stylesheet" type="text/css" media="screen,all" href="<?=CSS_URL?>../selectpicker/css/bootstrap-select.min.css?cr=<?=CACHE_RAND?>"> -->
<link rel="stylesheet" type="text/css" media="screen,all" href="<?=CSS_URL?>owl.carousel.min.css?cr=<?=CACHE_RAND?>" />
<link rel="stylesheet" type="text/css" media="screen,all" href="<?=CSS_URL?>owl.theme.default.min.css?cr=<?=CACHE_RAND?>" />
<link rel="stylesheet" type="text/css" media="screen,all" href="<?=CSS_URL?>responsive.css?cr=<?=CACHE_RAND?>" />
<link rel="author" href="//plus.google.com/111337747674016562751/">

<script type="text/javascript" src="<?=JS_URL?>jquery.min.js?cr=<?=CACHE_RAND?>"></script>
<script type="text/javascript" src="<?=JS_URL?>jquery.lazy.min.js?cr=<?=CACHE_RAND?>"></script>
<script type="text/javascript" src="<?=JS_URL?>bootstrap.min.js?cr=<?=CACHE_RAND?>"></script>
<script type="text/javascript" src="<?=JS_URL?>wow.min.js?cr=<?=CACHE_RAND?>"></script>
<!-- <script type="text/javascript" src="<?=JS_URL?>../flexslider/js/jquery.flexslider.js?cr=<?=CACHE_RAND?>"></script> -->
<!-- <script type="text/javascript" src="<?=JS_URL?>scrolloverflow.min.js?cr=<?=CACHE_RAND?>"></script> -->
<script type="text/javascript" src="<?=JS_URL?>owl.carousel.min.js?cr=<?=CACHE_RAND?>"></script>
<!-- <script type="text/javascript" src="<?=JS_URL?>../selectpicker/js/bootstrap-select.min.js?cr=<?=CACHE_RAND?>"></script> -->
<script type="text/javascript" src="<?=JS_URL?>util.js?cr=<?=CACHE_RAND?>"></script>
<script type="text/javascript" src="//www.google.com/recaptcha/api.js?cr=<?=CACHE_RAND?>"></script>

<script type="text/javascript">
	var BASE_URL = "<?=BASE_URL?>";
	new WOW().init();
</script>