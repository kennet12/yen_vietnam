<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tin_tuc extends CI_Controller {
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
		$this->lang->load('content',$this->_lang);
		$this->menu			= $this->lang->line('menu');
		$this->website		= $this->lang->line('website');
		$this->prop			= $this->lang->line('prop');
		$this->alias		= $this->lang->line('alias');
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$this->menu['home']}" => site_url()));
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$this->menu['new']}" => site_url($this->util->slug($this->router->fetch_class()))));
	}
	public function index($category=null, $id=null) {
		if (!isset($_GET['page']) || (($_GET['page']) < 1)) {
			$page = 1;
		}
		else {
			$page = $_GET['page'];
		}
		$offset   = ($page - 1) * 3;
		$info = new stdClass();
		if ($this->_lang == 'vi'){
			$category = $this->m_content_categories->load_vi($category);
		} else if ($this->_lang == 'en') {
			$category = $this->m_content_categories->load_en($category);
		}
		$categories 	= $this->m_content_categories->items(null,1);
		$recent_post = $this->m_contents->items(null,1,5,0,'RAND()','');
		if (!empty($category)) {
			
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$category->{$this->prop['name']}}" => site_url($this->util->slug($this->router->fetch_class())."/{$category->{$this->prop['alias']}}")));
			if (!empty($id)) {
				if ($this->_lang == 'vi'){
					$item	= $this->m_contents->load_vi($id);
				} else if ($this->_lang == 'en') {
					$item	= $this->m_contents->load_en($id);
				}

				$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$item->{$this->prop['title']}}" => site_url($this->util->slug($this->router->fetch_class())."/{$item->{$this->prop['alias']}}")));

				$info = new stdClass();
				$info->category_id = $item->category_id;
	
				$view_data = array();
				$view_data['breadcrumb'] 	= $this->_breadcrumb;
				$view_data["categories"] 	= $categories;
				$view_data['item'] 			= $item;
				$view_data['recent_post'] 	= $recent_post;
				$view_data["title"]   		= $item->{$this->prop['title']};
				$view_data["menu"] 			= $this->menu;
				$view_data["website"] 		= $this->website;
				$view_data["prop"] 		 	= $this->prop;
				$view_data["alias"] 		= $this->alias;
				$view_data['related_items'] = $this->m_contents->relative_items($info,array($item->id),1,6);
	
				$tmpl_content = array();
				$tmpl_content["content"]   = $this->load->view("content/detail", $view_data, TRUE);
				$tmpl_content["title"]   	= $item->{$this->prop['title']};
				$tmpl_content["meta_img"]	= !empty($item->thumbnail)?$item->thumbnail:null;
				$tmpl_content["meta_key"]	= $item->meta_key;
				$tmpl_content["meta_des"]	= $item->meta_des;
				$this->load->view("layout/view", $tmpl_content);
			} else {
				$info = new stdClass();
				$info->category_id = $category->id;
				$total = $this->m_contents->count_items($info,1);
				$items = $this->m_contents->items($info,1,3,$offset);
	
				$pagination = $this->util->pagination(site_url("{$this->alias['new']}/{$category->{$this->prop['alias']}}"), $total, 3, $this->menu['new']);
				
				$view_data = array();
				$view_data['breadcrumb'] 	= $this->_breadcrumb;
				$view_data['items'] 		= $items;
				$view_data["categories"] 	= $categories;
				$view_data["title"]   		= $category->{$this->prop['name']};
				$view_data['recent_post'] 	= $recent_post;
				$view_data['pagination'] 	= $pagination;
				$view_data["menu"] 			= $this->menu;
				$view_data["website"] 		= $this->website;
				$view_data["prop"] 		 	= $this->prop;
				$view_data["alias"] 		= $this->alias;
	
				$tmpl_content = array();
				$tmpl_content["content"]   = $this->load->view("content/index", $view_data, TRUE);
				$tmpl_content["title"]   = $category->name;
				$this->load->view("layout/view", $tmpl_content);
			}
		} else {

			$total = $this->m_contents->count_items(null,1);
			$items = $this->m_contents->items(null,1,3,$offset);

			$pagination = $this->util->pagination(site_url("{$this->alias['new']}"), $total, 3, $this->menu['new']);
			
			$view_data = array();
			$view_data['breadcrumb'] 	= $this->_breadcrumb;
			$view_data['recent_post'] 	= $recent_post;
			$view_data['items'] 		= $items;
			$view_data["categories"] 	= $categories;
			$view_data["title"]   		= $this->menu['new'];
			$view_data['pagination'] 	= $pagination;
			$view_data["menu"] 			= $this->menu;
			$view_data["website"] 		= $this->website;
			$view_data["prop"] 		 	= $this->prop;
			$view_data["alias"] 		= $this->alias;

			$tmpl_content = array();
			$tmpl_content["content"]   = $this->load->view("content/index", $view_data, TRUE);
			$tmpl_content["title"]   = $this->menu['new'];
			$this->load->view("layout/view", $tmpl_content);
		}
	}
}
?>