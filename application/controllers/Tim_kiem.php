<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tim_kiem extends CI_Controller {
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
		$this->lang->load('content',$this->_lang);
		$this->menu			= $this->lang->line('menu');
		$this->website		= $this->lang->line('website');
		$this->prop			= $this->lang->line('prop');
		$this->alias		= $this->lang->line('alias');
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->menu['home']}" => site_url()));
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->menu['search']}" => site_url($this->util->slug($this->router->fetch_class()))));
	}
	public function index($category_alias=null)
	{
		$search_text = !empty($_GET['search_text']) ? trim($_GET['search_text']," ") : '';
		$product_category_id = 0;
		$category = null;
		$list_category = array();
		if (!empty($category_alias)){
			$category	= $this->m_product_category->load_vi($category_alias);
			if (empty($category)){
				$category	= $this->m_product_category->load_en($category_alias);
				setcookie("nguyenanh_lang", 'en', time() + (10 * 365 * 24 * 60 * 60),'/');
				$this->_lang = 'en';
			} else {
				setcookie('nguyenanh_lang', null, -1, '/'); 
				$this->_lang = 'vi';
			}
			if (empty($category)) {
				redirect(site_url('error404'),'back');
			}

			$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$category->{$this->prop['name']}}" => '#'));
			$product_category_id = !empty($category->parent_id)?$category->parent_id:$category->id;
			array_push($list_category,$category->id);
		}
		if (!empty($search_text)) {
			if (!isset($_GET['page']) || (($_GET['page']) < 1)) {
				$page = 1;
			}
			else {
				$page = $_GET['page'];
			}
			$offset   = ($page - 1) * 12;

			$info_search = new stdClass();
			$info_search->search_text 		= $search_text;
			$info_search->list_category		= $list_category;
			$info_search->price = !empty($_GET['gia'])?explode(',',$_GET['gia']):null;
			$info_search->constraint	= !empty($_GET['constraint'])?explode(' ',$_GET['constraint']):null;
			$sort 				= !empty($_GET['sort'])?explode('-',$_GET['sort']):null;
			$order_by 			= !empty($sort[0]) ? $sort[0] : null;
			$sort_by 			= !empty($sort[1]) ? $sort[1] : null;

			$info_brand = new stdClass();
			$info_brand->product_category_id = $product_category_id;
			$brands = $this->m_brand->items($info_brand);

			$select = "p.id,
			p.title,
			p.title_en,
			p.alias,
			p.alias_en,
			p.content,
			p.content_en,
			p.category_id,
			p.code,
			p.rating_cmt,
			p.meta_title,
			p.thumbnail,
			p.meta_key,
			p.meta_des,
			CONVERT(JSON_EXTRACT(t.price, '$[0]'), DECIMAL) as price,
			CONVERT(JSON_EXTRACT(t.sale, '$[0]'), DECIMAL) as sale,
			t.typename";
			$total = $this->m_product->get_list_category_items($select,$info_search, 1, null, null, $order_by, $sort_by);
			$total = count($total);
			$total_page = ceil($total/12);
			$items = $this->m_product->get_list_category_items($select,$info_search, 1, 12, $offset, $order_by, $sort_by);
			// add keyword
			if (!empty($total) && (strlen($search_text)>2)) {
				$search_item = $this->m_search->load($search_text);
				if (!empty($search_item)) {
					$data_search = array(
						'point'		=>	$total,
						'num_search'=>	$search_item->num_search+1
					);
					$this->m_search->update($data_search,array("id" => $search_item->id));
				} else {
					$data_search = array(
						'keyword' 	=> $search_text,
						'point'		=>	$total,
						'num_search'=>	1
					);
					$this->m_search->add($data_search);
				}
			}
			//

			$url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
			$url = str_replace("?trang={$page}", '?', $url);
			$url = str_replace("&trang={$page}", '', $url);

			$pagination = $this->util->pagination($url, $total, 12, $this->website['product']);

			$view_data = array();
			$view_data["brands"] 		= $brands;
			$view_data["page"] 			= $page;
			$view_data["items"] 		= $items;
			$view_data["total_page"] 	= $total_page;

			$view_data["total"] 		= $total;
			$view_data['search_text'] 	= $search_text;
			$view_data["cate"] 			= $category;
			$view_data["total_items"] 	= $total;
			$view_data["pagination"] 	= $pagination;
			$view_data["menu"] 			= $this->menu;
			$view_data["website"] 		= $this->website;
			$view_data["prop"] 			= $this->prop;
			$view_data["alias"] 		= $this->alias;
			$view_data["breadcrumb"] 	= $this->_breadcrumbs;
			$view_data["breadcrumbs"] 	= $this->_breadcrumbs;

			$tmpl_content = array();
			$tmpl_content["content"]   = $this->load->view("search/index", $view_data, TRUE);
			$tmpl_content["title"]   = $this->menu['search'];
			$this->load->view("layout/view", $tmpl_content);
		} else {
			redirect(BASE_URL,'back');
		}
	}
	function ajax_search () {
		sleep(0.01);
		$text = $_GET['search_text'];
		$info = new stdClass();
		$info->keyword = trim($text," ");
		$search = $this->m_search->items($info,5);

		$info_product = new stdClass();
		$info_product->search_text = trim($text," ");
		$select = "
		p.title,
		p.title_en,
		p.alias,
		p.alias_en,
		p.code,
		CONVERT(JSON_EXTRACT(t.price, '$[0]'), DECIMAL) as price,
		CONVERT(JSON_EXTRACT(t.sale, '$[0]'), DECIMAL) as sale,
		p.thumbnail,
		t.typename";
		$product = $this->m_product->get_search_list_items($select,$info_product,1,6);
		$result = array($search,$product,$this->_lang);

		echo json_encode($result);
	}
}

?>