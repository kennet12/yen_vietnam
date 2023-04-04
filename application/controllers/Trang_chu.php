<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trang_chu extends CI_Controller {
	var $_lang = '';
	function __construct()
	{
		parent::__construct();

		$this->_lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
		$this->lang->load('content',$this->_lang);
	}
	public function index()
	{	
		$info = new stdClass();
		$info->show_cate = 1;
		$product_categories = $this->m_product_category->items($info,1,null,null,'order_num','ASC');

		$info = new stdClass();
		// $info->quantityEmpty	= 1;
		$info->flashDeal	= 1;
		$select = "
		p.id,
		p.title,
		p.title_en,
		p.alias,
		p.alias_en,
		p.thumbnail,
		p.category_id,
		p.code,
		p.rating_point,
		p.rating_cmt,
		p.meta_title,
		p.meta_key,
		p.meta_des,
		CONVERT(JSON_EXTRACT(t.price, '$[0]'), DECIMAL) as price,
		CONVERT(JSON_EXTRACT(t.sale, '$[0]'), DECIMAL) as sale,
		t.typename";
		$flash_deal = $this->m_product->get_list_category_items($select,$info, 1, 12,0,'RAND()','');

		$select = "
		p.id,
		p.title,
		p.title_en,
		p.alias,
		p.alias_en,
		p.thumbnail,
		p.category_id,
		p.code,
		p.rating_point,
		p.rating_cmt,
		p.meta_title,
		p.meta_key,
		p.meta_des,
		CONVERT(JSON_EXTRACT(t.price, '$[0]'), DECIMAL) as price,
		CONVERT(JSON_EXTRACT(t.sale, '$[0]'), DECIMAL) as sale,
		t.typename";
		$new_arrival = $this->m_product->get_list_category_items($select,null, 1, 16);

		$view_data = array();
		$view_data["product_categories"] 	= $product_categories;
		// $view_data["contents"]	= $this->m_contents->items(null,1,6);
		$view_data["brands"]	= $this->m_brand->items(null,1);
		$view_data["flash_deal"] 			= $flash_deal;
		$view_data["new_arrival"] 			= $new_arrival;
		$view_data["menu"]					= $this->lang->line('menu');
		$view_data["website"] 				= $this->lang->line('website');
		$view_data["prop"]					= $this->lang->line('prop');
		$view_data["alias"]					= $this->lang->line('alias');

		$tmpl_content = array();
		$tmpl_content["content"]   = $this->load->view("home", $view_data, TRUE);
		$this->load->view("layout/main", $tmpl_content);
		
	}
	function subscribe_email() {
		$email = $this->input->post('email');
		$info = new stdClass();
		$info->email = $email;
		if (empty($this->m_subscribe->items($info))) {
			$this->m_subscribe->add(array('email' => $email));
			echo 1;
		} else {
			echo 0;
		}
	}
}
?>