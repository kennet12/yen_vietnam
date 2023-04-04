<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller {

	var $_breadcrumbs = array();
	var $_lang = '';
	var $website = array();
	var $prop = array();

	function __construct()
	{
		parent::__construct();
		$this->_lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
		$this->lang->load('content',$this->_lang);
		$this->website		= $this->lang->line('website');
		$this->menu		= $this->lang->line('menu');
		$this->prop		= $this->lang->line('prop');
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->menu['home']}" => site_url()));
	}
	public function about() {
		$item = $this->m_post->load(1);
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$item->{$this->prop['title']}}" => "#"));
		$view_data = array();
		$view_data['item'] 			= $item;
		$view_data['breadcrumb'] 	= $this->_breadcrumbs;
		$view_data['breadcrumbs'] 	= $this->_breadcrumbs;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;

		$tmpl_content = array();
		$tmpl_content["title"]   	= $item->{$this->prop['title']};
		$tmpl_content["meta_img"]	= !empty($item->thumbnail)?$item->thumbnail:null;
		$tmpl_content["meta_key"]	= $item->meta_key;
		$tmpl_content["meta_des"]	= $item->meta_des;
		$tmpl_content["content"]   = $this->load->view("post/index", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
	}
	public function refund_policy() {
		$item = $this->m_post->load(2);
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$item->{$this->prop['title']}}" => "#"));
		$view_data = array();
		$view_data['item'] 			= $item;
		$view_data['breadcrumb'] 	= $this->_breadcrumbs;
		$view_data['breadcrumbs'] 	= $this->_breadcrumbs;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;

		$tmpl_content = array();
		$tmpl_content["title"]   	= $item->{$this->prop['title']};
		$tmpl_content["meta_img"]	= !empty($item->thumbnail)?$item->thumbnail:null;
		$tmpl_content["meta_key"]	= $item->meta_key;
		$tmpl_content["meta_des"]	= $item->meta_des;
		$tmpl_content["content"]   = $this->load->view("post/index", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
	}
	public function change_policy() {
		$item = $this->m_post->load(3);
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$item->{$this->prop['title']}}" => "#"));
		$view_data = array();
		$view_data['item'] 			= $item;
		$view_data['breadcrumb'] 	= $this->_breadcrumbs;
		$view_data['breadcrumbs'] 	= $this->_breadcrumbs;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;

		$tmpl_content = array();
		$tmpl_content["title"]   	= $item->{$this->prop['title']};
		$tmpl_content["meta_img"]	= !empty($item->thumbnail)?$item->thumbnail:null;
		$tmpl_content["meta_key"]	= $item->meta_key;
		$tmpl_content["meta_des"]	= $item->meta_des;
		$tmpl_content["content"]   = $this->load->view("post/index", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
	}
	public function sales_promotion() {
		$item = $this->m_post->load(4);
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$item->{$this->prop['title']}}" => "#"));
		$view_data = array();
		$view_data['item'] 			= $item;
		$view_data['breadcrumb'] 	= $this->_breadcrumbs;
		$view_data['breadcrumbs'] 	= $this->_breadcrumbs;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;

		$tmpl_content = array();
		$tmpl_content["title"]   	= $item->{$this->prop['title']};
		$tmpl_content["meta_img"]	= !empty($item->thumbnail)?$item->thumbnail:null;
		$tmpl_content["meta_key"]	= $item->meta_key;
		$tmpl_content["meta_des"]	= $item->meta_des;
		$tmpl_content["content"]   = $this->load->view("post/index", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
	}
	public function brand_system() {
		$item = $this->m_post->load(5);
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$item->{$this->prop['title']}}" => "#"));
		$view_data = array();
		$view_data['item'] 			= $item;
		$view_data['breadcrumb'] 	= $this->_breadcrumbs;
		$view_data['breadcrumbs'] 	= $this->_breadcrumbs;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;

		$tmpl_content = array();
		$tmpl_content["title"]   	= $item->{$this->prop['title']};
		$tmpl_content["meta_img"]	= !empty($item->thumbnail)?$item->thumbnail:null;
		$tmpl_content["meta_key"]	= $item->meta_key;
		$tmpl_content["meta_des"]	= $item->meta_des;
		$tmpl_content["content"]   = $this->load->view("post/index", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
	}
	public function policy() {
		$item = $this->m_post->load(6);
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$item->{$this->prop['title']}}" => "#"));
		$view_data = array();
		$view_data['item'] 			= $item;
		$view_data['breadcrumb'] 	= $this->_breadcrumbs;
		$view_data['breadcrumbs'] 	= $this->_breadcrumbs;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;

		$tmpl_content = array();
		$tmpl_content["title"]   	= $item->{$this->prop['title']};
		$tmpl_content["meta_img"]	= !empty($item->thumbnail)?$item->thumbnail:null;
		$tmpl_content["meta_key"]	= $item->meta_key;
		$tmpl_content["meta_des"]	= $item->meta_des;
		$tmpl_content["content"]   = $this->load->view("post/index", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
	}
	public function term_use() {
		$item = $this->m_post->load(7);
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$item->{$this->prop['title']}}" => "#"));
		$view_data = array();
		$view_data['item'] 			= $item;
		$view_data['breadcrumb'] 	= $this->_breadcrumbs;
		$view_data['breadcrumbs'] 	= $this->_breadcrumbs;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;

		$tmpl_content = array();
		$tmpl_content["title"]   	= $item->{$this->prop['title']};
		$tmpl_content["meta_img"]	= !empty($item->thumbnail)?$item->thumbnail:null;
		$tmpl_content["meta_key"]	= $item->meta_key;
		$tmpl_content["meta_des"]	= $item->meta_des;
		$tmpl_content["content"]   = $this->load->view("post/index", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
	}
}
?>