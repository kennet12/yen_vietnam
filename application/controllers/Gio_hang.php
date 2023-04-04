<?php
class Gio_hang extends CI_Controller{

	var $_breadcrumbs = array();
	var $_lang = '';
	var $menu = array();
	var $website = array();
	var $prop = array();
	var $alias = array();
	
	function __construct(){
		parent::__construct();
		$this->_lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
		$this->lang->load('content',$this->_lang);
		$this->menu			= $this->lang->line('menu');
		$this->website		= $this->lang->line('website');
		$this->prop			= $this->lang->line('prop');
		$this->alias		= $this->lang->line('alias');
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->menu['home']}" => site_url()));
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->website['my_cart']}" => "#"));
	}

	public function index (){
		
		$view_data = array();
		$view_data["breadcrumb"] 	= $this->_breadcrumbs;
		$view_data["breadcrumbs"] 	= $this->_breadcrumbs;
		$view_data['items'] 		= $this->cart->contents();
		$view_data["menu"] 			= $this->menu;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;
		$view_data["alias"] 		= $this->alias;

		$tmpl_content				= array();
		$tmpl_content['title'] 		= $this->website['my_cart'];
		$tmpl_content["content"]   = $this->load->view("cart/index", $view_data, TRUE);
		$this->load->view('layout/view', $tmpl_content);
	}
	public function ajax_add_cart () {
		$typename 		= $this->input->post('typename');
		$subtypename 	= $this->input->post('subtypename');
		$quantity 		= $this->input->post('quantity');

		$item = $this->m_product->load($this->input->post('id'));
		$info = new stdClass();
		$info->product_id = $item->id;
		$item->photo = $this->m_product_gallery->items($info);
		$item->product_type = $this->m_product_type->items($info);
		$i = 0;
		$key_i = -1;
		$key_j = -1;
		foreach($item->product_type as $product_type) {
			if ($product_type->typename == $typename) {
				$key_i = $i;
				if ($subtypename != '')
					$key_j = array_search($subtypename, json_decode($product_type->subtypename));
				break;
			}
			$i++;
		}
		
		$price = json_decode($item->product_type[0]->price)[0];
		$sale = json_decode($item->product_type[0]->sale)[0];
		$cost = json_decode($item->product_type[0]->cost)[0];
		$quantity_item = json_decode($item->product_type[0]->quantity)[0];
		$product_id = $item->id;
		if ($key_i != -1) {
			$product_id .= $key_i;
			$price = json_decode($item->product_type[$key_i]->price)[0];
			$sale = json_decode($item->product_type[$key_i]->sale)[0];
			$cost = json_decode($item->product_type[$key_i]->cost)[0];
			$quantity_item = json_decode($item->product_type[$key_i]->quantity)[0];
		}
		if ($key_j != -1) {
			$product_id .= $key_j;
			$price  = json_decode($item->product_type[$key_i]->price)[$key_j];
			$sale  = json_decode($item->product_type[$key_i]->sale)[$key_j];
			$cost  = json_decode($item->product_type[$key_i]->cost)[$key_j];
			$quantity_item = json_decode($item->product_type[$key_i]->quantity)[$key_j];
		}

		$product_id = (int)($product_id);
		$carts		= $this->cart->contents();
		$qty_item_cur = 0;
		foreach ($carts as $cart) {
			if ($cart['id'] == $product_id) {
				$qty_item_cur = $cart['qty'];
			}
		}
		$price_sale = $price*(1 - $sale*0.01);
		$cost = !empty($cost)?$cost:$price_sale;
		$data = array(
			"id" 			=> $product_id,
			"product_id" 	=> $item->id,
			"name" 			=> $item->title,
			"qty" 			=> $quantity,
			"price_sale" 	=> $price_sale,
			"price" 		=> $price,
			"sale" 			=> $sale,
			"cost" 			=> $cost,
			"key_i" 		=> $key_i,
			"key_j" 		=> $key_j,
			"typename" 		=> $typename,
			"subtypename" 	=> $subtypename,
			"thumbnail" 	=> !empty($item->photo[0]) ? $item->photo[0]->file_path : '',
		);
		if (($qty_item_cur+$quantity) <= $quantity_item) {
			$this->cart->product_name_rules = '[:print:]';
			$this->cart->insert($data);
		}

		$carts = $this->cart->contents();

		if (!empty($this->session->user)) {
			$info = new stdClass();
			$info->product_id_temp 	= $product_id;
			$info->user_id 		= $this->session->user->id;
			$cart_items = $this->m_cart->items($info);
			if (!empty($cart_items)) {
				foreach ($cart_items as $cart_item) {
					$data = array(
						"qty" => $cart_item->qty+$quantity,
					);
					$this->m_cart->update($data,array('id' => $cart_item->id));
				}
			} else {
				$data = array(
					"title" 			=> $item->title,
					"product_id" 		=> $item->id,
					"product_id_temp"	=> $product_id,
					"user_id" 			=> $this->session->user->id,
					"qty" 				=> $quantity,
					"price_sale" 		=> $price_sale,
					"price" 			=> $price,
					"sale" 				=> $sale,
					"cost" 				=> $cost,
					"key_i" 			=> $key_i,
					"key_j" 			=> $key_j,
					"typename" 			=> $typename,
					"subtypename" 		=> $subtypename,
					"thumbnail" 		=> !empty($item->photo[0]) ? $item->photo[0]->file_path : '',
				);
				$this->m_cart->add($data);
			}
		}
		echo json_encode($carts);
	}
	public function ajax_update_cart () {
		$id 		= $this->input->post('id');
		$qty 		= $this->input->post('qty');
		$items 		= $this->cart->contents();
		foreach ($items as $item) {
			if ($item['id'] == $id) {
				$update = array(
					"rowid" => $item['rowid'],
					"qty" => $qty,
				);
			}
		}
		$this->cart->update($update);
		if (!empty($this->session->user)) {
			$info = new stdClass();
			$info->product_id_temp 	= $id;
			$info->user_id 		= $this->session->user->id;
			$cart_items = $this->m_cart->items($info);
			foreach ($cart_items as $cart_item) {
				$data = array(
					"qty" => $qty,
				);
				$this->m_cart->update($data,array('id' => $cart_item->id));
			}
		}
	}
	public function ajax_delete_cart () {
		$id = $this->input->post('id');
		$rowid = $this->input->post('rowid');
		$data = array(
			'rowid'	=> $rowid,
			'qty'	=> 0
		);
		if (!empty($this->session->user)) {
			$info = new stdClass();
			$info->product_id 	= $id;
			$info->user_id 		= $this->session->user->id;
			$cart_items = $this->m_cart->items($info);
			if (!empty($cart_items)) {
				foreach ($cart_items as $cart_item) {
					$this->m_cart->delete(array('id' => $cart_item->id));
				}
			}
		}
		echo json_encode($this->cart->update($data));
	}
	public function promotion(){
		$promotion = $this->input->post('promotion');
		$carts		= $this->cart->contents();
		$subtotal = 0;
		foreach($carts as $cart) {
			$subtotal += $cart['subtotal'];
		}
		$info = new stdClass();
		$info->code = $promotion;
		$items = $this->m_promotion->items($info,1);
		$result = null;
		$bill_cost = null;
		$status = 0; // mã hết hạn
		foreach ($items as $item){
			if ($this->session->userdata('user')->user_rank == $item->user_rank){
				if (!empty($item->limit_time)){
					if (date('Y-m-d',strtotime($item->fromdate ))<= date('Y-m-d') && date('Y-m-d',strtotime($item->todate ))>= date('Y-m-d')){
						$result = $item;
						$status = 1;
						break;
					}
				} else {
					$result = $item;
				}
			}
		}
		if (!empty($result)) {
			if ($subtotal < $result->bill_money){
				$bill_cost = $result->bill_money;
				$status = -1; // don hang khong du
				$result = null;
			}
		}
		echo json_encode(array($status,$result,$bill_cost));
	}
}