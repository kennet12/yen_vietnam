<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chi_tiet_san_pham extends CI_Controller {
	var $_breadcrumb = array();
	var $_breadcrumbs = array();
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
	public function index($alias) {
		$item	= $this->m_product->load_vi($alias);
		if (empty($item)){
			$item	= $this->m_product->load_en($alias);
            setcookie("nguyenanh_lang", 'en', time() + (10 * 365 * 24 * 60 * 60),'/');
			$this->_lang = 'en';
		} else {
			setcookie('nguyenanh_lang', null, -1, '/');
			$this->_lang = 'vi';
		}
		
		$this->lang->load('content',$this->_lang);
		$this->menu			= $this->lang->line('menu');
		$this->website		= $this->lang->line('website');
		$this->prop			= $this->lang->line('prop');
		$this->alias		= $this->lang->line('alias');
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$this->menu['home']}" => site_url()));
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->menu['home']}" => site_url()));
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->website['product']}" => site_url($this->alias['product'])));	

		if (!empty($item)) {
			$list_cateid = json_decode($item->category_id);
			$array_cate = array();
			foreach ($list_cateid as $cateid) {
				$category = $this->m_product_category->load($cateid);
				array_push($array_cate,$category);
				$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$category->{$this->prop['name']}}" => site_url("{$this->alias['product']}/{$category->{$this->prop['alias']}}")));
			}
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$item->{$this->prop['title']}}" => "#"));
			$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$item->{$this->prop['title']}}" => "#"));
			
			$info 				= new stdClass();
			$info->product_id 	= $item->id;
			$item->photo		= $this->m_product_gallery->items($info);
			$item->product_type	= $this->m_product_type->items($info);

			$info 					= new stdClass();
			$info->category_id 		= $category->id;
			$select = "p.id,
			p.title,
			p.title_en,
			p.alias,
			p.alias_en,
			p.category_id,
			p.thumbnail,
			p.code,
			p.rating_point,
			p.rating_cmt,
			p.meta_title,
			p.meta_key,
			p.meta_des,
			CONVERT(JSON_EXTRACT(t.price, '$[0]'), DECIMAL) as price,
			CONVERT(JSON_EXTRACT(t.sale, '$[0]'), DECIMAL) as sale,
			t.typename";
			$related_products		= $this->m_product->relative_items($select,$info,$item->id,1,8);
			
			$view_data["item"] 				= $item;
			$view_data["related_products"] 	= $related_products;
			$view_data["breadcrumb"] 		= $this->_breadcrumb;
			$view_data["breadcrumbs"] 		= $this->_breadcrumbs;
			$view_data["menu"] 				= $this->menu;
			$view_data["website"] 			= $this->website;
			$view_data["prop"] 				= $this->prop;
			$view_data["alias"] 			= $this->alias;
			$view_data["array_cate"] 		= $array_cate;

			$tmpl_content = array();
			$tmpl_content["content"]   = $this->load->view("product/detail", $view_data, TRUE);
			$tmpl_content["title"]		= $item->{$this->prop['title']};
			$tmpl_content["meta_title"] = $item->{$this->prop['meta_title']};
			$tmpl_content["meta_key"]	= $item->{$this->prop['meta_key']};
			$tmpl_content["meta_img"]	= !empty($item->photo[0]->file_path)?$item->photo[0]->file_path:null;
			$tmpl_content["meta_des"]	= $item->{$this->prop['meta_des']};
			$this->load->view("layout/view", $tmpl_content);
		} else {
			redirect(site_url('error404'),'back');
		}
	}
}
?>