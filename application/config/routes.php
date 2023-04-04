<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$method = str_replace(BASE_URL.'/','',$url);
$method = str_replace(BASE_URL,'',$method);
$method = str_replace('.html','',$method);
$method = explode('/',$method)[0];

$route['default_controller'] = 'trang_chu';
$route['404_override'] = 'error404';
$route['translate_uri_dashes'] = TRUE;

$array_route = array(
    'san-pham',
    'tim-kiem',
    'gioi-thieu',
    'lien-he',
    'gio-hang',
    'dat-hang',
    'tai-khoan',
    'quen-mat-khau',
    'dat-hang-nhap',
    'syslog',
    'error404',
    'call-service',
    'check-qrcode',
    'tra-cuu-don-hang',
    'tin-tuc',
    'new',
    'product',
    'contact',
    'about-us',
    'chinh-sach-hoan-tien',
    'refund-policy',
    'chinh-sach-doi-hang',
    'change-policy',
    'khuyen-mai-giam-gia',
    'sales-promotion',
    'he-thong-chi-nhanh',
    'brand-system',
    'chinh-sach',
    'policy',
    'dieu-khoan-su-dung',
    'term-use',
    'product-au-usa',		
    'san-pham-au-my',			    
);
$route['san-pham-au-my'] 					= "au-my/index";
$route['san-pham-au-my/(:any)'] 			= "au-my/index/$1";
$route['product-au-usa'] 					= "au-my/index";
$route['product-au-usa/(:any)'] 			= "au-my/index/$1";

$route['san-pham'] 					        = "san-pham/index";
$route['san-pham/(:any)'] 					= "san-pham/index/$1";
$route['product'] 					        = "san-pham/index";
$route['product/(:any)'] 					= "san-pham/index/$1";

$route['tim-kiem'] 				            = "tim-kiem/index";
$route['tim-kiem/(:any)'] 					= "tim-kiem/index/$1";
$route['tim-kiem-ajax'] 				    = "tim-kiem/ajax-search";

$route['gioi-thieu'] 					    = "post/about";
$route['about-us'] 					        = "post/about";

$route['chinh-sach-hoan-tien'] 			    = "post/refund-policy";
$route['refund-policy'] 					= "post/refund-policy";

$route['chinh-sach-doi-hang'] 			     = "post/change_policy";
$route['change-policy'] 					= "post/change_policy";

$route['khuyen-mai-giam-gia'] 			    = "post/sales_promotion";
$route['sales-promotion'] 					= "post/sales_promotion";

$route['he-thong-chi-nhanh'] 		        = "post/brand_system";
$route['brand-system'] 					    = "post/brand_system";

$route['chinh-sach'] 					    = "post/policy";
$route['policy'] 					        = "post/policy";

$route['dieu-khoan-su-dung'] 				 = "post/term_use";
$route['term-use'] 					        = "post/term_use";

$route['tin-tuc'] 		    		        = "tin-tuc/index";
$route['tin-tuc/(:any)'] 		    		= "tin-tuc/index/$1";
$route['tin-tuc/(:any)/(:any)'] 		    = "tin-tuc/index/$1/$2";
$route['new'] 		    		            = "tin-tuc/index";
$route['new/(:any)'] 		    		    = "tin-tuc/index/$1";
$route['new/(:any)/(:any)'] 		        = "tin-tuc/index/$1/$2";

$route['quen-mat-khau/(:any)'] 		        = "quen-mat-khau/index/$1";

$route['lien-he'] 					        = "lien-he/index";
$route['contact'] 					        = "lien-he/index";

$route['check-qrcode/(:any)'] 			    = "check-qrcode/index/$1";
$route['lien-he/ajax-contact']				= 'lien-he/ajax-contact';
$route['tra-cuu-don-hang/(:any)/(:any)']    = 'tra-cuu-don-hang/index/$1/$2';
$route['call-service/send-rating']			= 'call-service/send-rating';
$route['call-service/load-rating']			= 'call-service/load-rating';
$route['call-service/send-reply-rating']	= 'call-service/send-reply-rating';
$route['call-service/optimize-image']	    = 'call-service/optimize-image';
$route['call-service/qrcode']	            = 'call-service/qrcode';
$route['call-service/like-product']	        = 'call-service/like-product';
$route['call-service/set-lang/(:any)']	    = 'call-service/set-lang/$1';
$route['call-service/create-affiliate']	    = 'call-service/create-affiliate';
$route['call-service/fetch-data']	        = 'call-service/fetch-data';

if(!in_array($method, $array_route)) {
    $route['(:any)'] 						= "chi-tiet-san-pham/index/$1";
}





