<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Au_my extends CI_Controller {
	var $_breadcrumb = array();
	var $_lang = '';
	var $menu = array();
	var $website = array();
	var $prop = array();
	var $alias = array();

	function __construct()
	{
		parent::__construct();
		$this->_lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
	}
	public function index($category_alias=null) {
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$list_category = array();
		$product_category_id = 0;
		$category = null;
		if (!empty($category_alias)){
			if (strpos($actual_link, '/product-au-usa/') !== false || strpos($actual_link, '/product-au-usa.html') !== false){
				$category	= $this->m_product_category->load_en($category_alias);
				setcookie("nguyenanh_lang", 'en', time() + (10 * 365 * 24 * 60 * 60),'/');
				$this->_lang = 'en';
			} else if (strpos($actual_link, '/san-pham-au-my/') !== false || strpos($actual_link, '/san-pham-au-my.html') !== false) {
				$category	= $this->m_product_category->load_vi($category_alias);
				setcookie('nguyenanh_lang', null, -1, '/'); 
				$this->_lang = 'vi';
			}
			if (empty($category)) {
				redirect(site_url('error404'),'back');
			}
			$product_category_id = !empty($category->parent_id)?$category->parent_id:$category->id;
			array_push($list_category,$category->id);
		}
		$this->lang->load('content',$this->_lang);
		$this->menu			= $this->lang->line('menu');
		$this->website		= $this->lang->line('website');
		$this->prop			= $this->lang->line('prop');
		$this->alias		= $this->lang->line('alias');
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$this->menu['home']}" => site_url()));
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$this->menu['product']}" => site_url($this->alias['product'])));

		if (!isset($_GET['page']) || (($_GET['page']) < 1)) {
			$page = 1;
		}
		else {
			$page = $_GET['page'];
		}
		$offset   = ($page - 1) * 12;
		
		
		$info = new stdClass();
		$info->list_category= $list_category;
		$info->constraint	= !empty($_GET['constraint'])?explode(' ',$_GET['constraint']):null;
		$info->price 		= !empty($_GET['gia'])?explode(',',$_GET['gia']):null;
		$sort 				= !empty($_GET['sort'])?explode('-',$_GET['sort']):null;
		$order_by 			= !empty($sort[0]) ? $sort[0] : null;
		$sort_by 			= !empty($sort[1]) ? $sort[1] : null;
		$select = "p.id";
		$total_items = $this->m_product->get_list_category_items($select,$info, 1);

		$info_brand = new stdClass();
		$info_brand->product_category_id = $product_category_id;
		$brands = $this->m_brand->items($info_brand);
		$select = "p.id,
		p.title,
		p.alias,
		p.title_en,
		p.alias_en,
		p.category_id,
		p.code,
		p.thumbnail,
		p.rating_point,
		p.rating_cmt,
		p.meta_title,
		p.content,
		p.content_en,
		p.meta_key,
		p.meta_des,
		CONVERT(JSON_EXTRACT(t.price, '$[0]'), DECIMAL) as price,
		CONVERT(JSON_EXTRACT(t.sale, '$[0]'), DECIMAL) as sale,
		t.typename";
		$items = $this->m_product->get_list_category_items($select,$info, 1, 12, $offset, $order_by, $sort_by);
		$total = !empty($total_items)?count($total_items):0;
		$total_page = ceil($total/12);

		$url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$url = str_replace("?page={$page}", '?', $url);
		$url = str_replace("&page={$page}", '', $url);
        
		$pagination = $this->util->pagination($url, $total, 12, $this->website['product']);
		$view_data = array();
		$view_data["brands"] 		= $brands;
		$view_data["page"] 			= $page;
		$view_data["cate"] 			= $category;
		$view_data["items"] 		= $items;
		$view_data["total_page"] 	= $total_page;

		$view_data["total"] 		= $total;
		$view_data["breadcrumb"] 	= $this->_breadcrumb;
		$view_data["pagination"] 	= $pagination;
		$view_data["menu"] 			= $this->menu;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;
		$view_data["alias"] 		= $this->alias;

		$tmpl_content = array();
		if (!empty($category)) {
		$tmpl_content["title"]		= $category->{$this->prop['name']};
		$tmpl_content["meta_title"] = $category->{$this->prop['name']};
		$tmpl_content["meta_key"]	= $category->{$this->prop['meta_key']};
		$tmpl_content["meta_des"]	= $category->{$this->prop['name']};
		} else {
		$tmpl_content["title"]   = $this->menu['order_product'];
		$tmpl_content["meta_title"] = $this->menu['order_product'];
		}
		$tmpl_content["meta_img"]	= !empty($items[0]->thumbnail)?$items[0]->thumbnail:null;
		$tmpl_content["content"]   = $this->load->view("product_order/index", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
	}
}
?>