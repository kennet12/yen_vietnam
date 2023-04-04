<?php
// WEB ROOT URI 
define("PROTOCOL",			"https://www.yen-vietnam.com");
define("BASE_URL",			PROTOCOL);
define("TPL_URL",			BASE_URL."/template/");
define("IMG_URL",			TPL_URL."images/");
define("CSS_URL",			TPL_URL."css/");
define("JS_URL",			TPL_URL."js/");

define("USR_URL",			BASE_URL."/tai-khoan/dang-nhap.html");
define("USR_TPL_URL",		BASE_URL."/template/account/");
define("USR_IMG_URL",		USR_TPL_URL."images/");
define("USR_CSS_URL",		USR_TPL_URL."css/");
define("USR_JS_URL",		USR_TPL_URL."js/");

define("USER_DEFAULT",		IMG_URL."avatar-default.png");
define("IMAGES_DEFAULT",	IMG_URL."images-default.jpg");

define("ADMIN_URL",			BASE_URL."/syslog/login.html");
define("ADMIN_TPL_URL",		BASE_URL."/template/admin/");
define("ADMIN_IMG_URL",		ADMIN_TPL_URL."images/");
define("ADMIN_CSS_URL",		ADMIN_TPL_URL."css/");
define("ADMIN_JS_URL",		ADMIN_TPL_URL."js/");
define("ADMIN_AGENT_ID",	"SHOP");
define("ADMIN_ROW_PER_PAGE",10);
define("PATH_ROOT",			$_SERVER['DOCUMENT_ROOT']);
define("PATH_CKFINDER",		'images/ck_finder');
define("GOOGLE_MAPS_LINK",	"https://www.google.com/maps/place/S4+Ba+V%C3%AC,+Ph%C6%B0%E1%BB%9Dng+15,+Qu%E1%BA%ADn+10,+Th%C3%A0nh+ph%E1%BB%91+H%E1%BB%93+Ch%C3%AD+Minh/@10.7802451,106.6624241,19z/data=!4m5!3m4!1s0x31752ec563d43459:0x18923029e1754f84!8m2!3d10.780136!4d106.662646");

define("CACHE_TIME",		55);
define("CACHE_RAND",		date("Ymdhi"));
define("ROW_PER_PAGE",		12);
define("MAIL_INFO",			'sales@yensao.vn');
define("COMPANY",			'Yến Việt Nam');

// REMOTE FILE MANAGER
define("CDN_URL",				"https://yensao");
define("CDN_AGENT_ID",			"CDN_LEGO_NA_SYSLOG");
define("CDN_MAIL_NOREPLY_USER",	"noreply@vietnamnvisa.com");
define("CDN_MAIL_NOREPLY_PASS",	"KsFXwvSS8265@");

// WEB DATABASE
define("HOSTNAME",			"localhost");
define("USERNAME",			"yenvietnam1501_db");
define("PASSWORD",			"Duyanh12#");
define("DATABASE",			"yenvietnam1501_db");
define("DRIVER",			"mysqli");

// USER TYPES
define("USR_USER",			1);
define("USR_MOD",			0);
define("USR_ADMIN",			-1);
define("USR_SUPPER_ADMIN",	-2);


// USER DEFINE
// --------------------------------------------
define("SITE_NAME", 	"Yến Việt Nam");
define("WEBSITE", 	"yenvietnam.com");
define("BOOKING_PREFIX","NAF");
define("ORDER_PREFIX","NAO");
define("BOOKING_PREFIX_PO","NAF_PO");

// Paypal
define("PAYPAL", "ON");
define("PAYPAL_PAYMENT",		"paypal@vietnamvisateam.com");
define("PAYPAL_USER",			"paypal_api1.vietnamvisateam.com");
define("PAYPAL_PWD",			"HDS26K886MQQ3NUE");
define("PAYPAL_SIGNATURE",		"AUkau1FwogE3kL3qo1vGTARqlijQAQR00eHJqe8eQoUOI2ZS3MeX.UVE");
//define("PAYPAL_PAYMENT",		"paypal@travelovietnam.com");
//define("PAYPAL_USER",			"paypal_api1.travelovietnam.com");
//define("PAYPAL_PWD",			"4REQ9BC4D649RVTY");
//define("PAYPAL_SIGNATURE",	"AmsFCmptjBSFmThGONrqqaczQzYbA4bNvtQdHEJCcNXlUxIRiAYn1qa0");
define("PAYPAL_VERSION",		"93");
define("PAYPAL_CURRENCY",		"USD");
define("PAYPAL_CANCEL_URL",		BASE_URL."/apply-visa.html");
define("PAYPAL_RETURN_URL",		BASE_URL."/apply-visa.html");

define("PAYPAL_E_CANCEL_URL",		BASE_URL."/apply-e-visa.html");
define("PAYPAL_E_RETURN_URL",		BASE_URL."/apply-e-visa.html");

// Gate2Shop
define("G2S", "OFF");
define("G2S_SECRET_KEY",		"OXdboh2JpHmGxdQbiq89Hi9JyG4pFuPiAZgGnC0TyWmngeFfNfxwnimj8K4yx6QM");
define("G2S_MERCHANT_ID",		"3486823679499290452");
define("G2S_MERCHANT_SITE_ID",	"86891");
define("G2S_CURRENTCY",			"USD");
define("G2S_VERSION",			"3.0.0");

// OnePay
define("OP", "ON");
define("OP_PAYMENT_URL",		"https://onepay.vn/vpcpay/vpcpay.op?");
define("OP_QUERY_URL",			"https://migs.mastercard.com.au/vpcdps");
define("OP_RETURN_URL",			BASE_URL."/dat-hang.html");
define("OP_SECURE_SECRET",		"11001D5C3C99C0721E7B73D682FD5B01");
define("OP_MERCHANT",			"OP_THEONEVN1");
define("OP_ACCESSCODE",			"E1001B01");

// define('OP_SECURE_SECRET',		'DD9F0B7DB5DFA307A067F17F6E1576E6');
// define('OP_MERCHANT',			'OP_VNEVISA08');
// define('OP_ACCESSCODE',			'E1ACF954');

// Google reCaptcha
define('RECAPTCHA_KEY',			'6Lc7oEskAAAAAMkzIGQwUd1ZPoAGXEb9m0AzNmbB');
define('RECAPTCHA_SECRET',		'6Lc7oEskAAAAAGunO8bsju4ez5bBjUFFFFOV7eq_');

// Google Plus API key
define('GOOGLE_KEY',			'724539714112-fpe7i8pgia4tg37gna48e94dan9ijpk2.apps.googleusercontent.com');

// Facebook API key
define('FB_KEY',				'762751813859314');
define('OPEN_CONVERT_CAST',		'CAST(');
define('CLOSE_CONVERT_CAST',	' AS UNSIGNED)');
?>