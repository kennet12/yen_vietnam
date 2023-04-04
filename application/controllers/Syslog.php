<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syslog extends CI_Controller {

	var $_breadcrumb = array();
	
	public function __construct()
	{
		parent::__construct();
		
		$method = $this->util->slug($this->router->fetch_method());
		
		if (!in_array($method, array("login", "logout"))) {
			$this->util->require_admin_login();
			$user = $this->session->userdata("admin");
			if (!$user->active) {
				$this->session->set_flashdata("error", "Your account is under review.");
				redirect(ADMIN_URL);
			}
			else if ($user->deleted) {
				$this->session->set_flashdata("error", "Account non-existent or deleted");
				redirect(ADMIN_URL);
			}
		}

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Quản trị" => site_url($this->util->slug($this->router->fetch_class()))));
	}
	
	public function index()
	{
		$view_data = array();
		$view_data["breadcrumb"] = $this->_breadcrumb;
		
		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("admin/index", $view_data, true);
		$this->load->view("layout/admin/main", $tmpl_content);
	}
	
	//------------------------------------------------------------------------------
	// Login
	//------------------------------------------------------------------------------
	
	public function login()
	{
		if (!empty($_POST))
		{
			$agent_id = trim($this->util->value($this->input->post("agent_id"), ""));
			$email = trim($this->util->value($this->input->post("email"), ""));
			$password = trim($this->util->value($this->input->post("password"), ""));
			
			if (strtoupper($agent_id) == ADMIN_AGENT_ID) {
				if ($this->m_user->login($email, $password, "admin") == false) {
					$this->session->set_flashdata("error", "Invalid email or password.");
					redirect(site_url("syslog/login"), "back");
				} else {
					redirect(site_url("syslog"));
				}
			} else {
				$this->session->set_flashdata("error", "Invalid Agent ID.");
				redirect(site_url("syslog/login"), "back");
			}
		}
		
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Login" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		
		$view_data = array();
		$view_data["breadcrumb"] = $this->_breadcrumb;
		
		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("admin/login", $view_data, true);
		$this->load->view("layout/admin/login", $tmpl_content);
	}

	public function logout()
	{
		$this->m_user->logout();
		redirect(site_url("syslog"));
	}
	
	//------------------------------------------------------------------------------
	// Sitemap
	//------------------------------------------------------------------------------
	
	public function create_sitemap()
	{
		$sitename = strtolower(SITE_NAME);
		
		$url80 = array();
		$url64 = array();
		
		$xmlstr  = '<?xml version="1.0" encoding="UTF-8"?>';
		$xmlstr .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">';
		
		$url80[] = BASE_URL;
		$url80[] = site_url("gioi-thieu");
		// $url80[] = site_url("about-us");
		$url80[] = site_url("san-pham");
		$url80[] = site_url("tim-kiem");
		$url80[] = site_url("chinh-sach-hoan-tien");
		$url80[] = site_url("chinh-sach-doi-hang");
		$url80[] = site_url("khuyen-mai-giam-gia");
		$url80[] = site_url("he-thong-chi-nhanh");
		$url80[] = site_url("chinh-sach");
		$url80[] = site_url("dieu-khoan-su-dung");
		// $url80[] = site_url("product");
		// $url80[] = site_url("tin-tuc");
		// $url80[] = site_url("new");
		$url80[] = site_url("lien-he");
		// $url80[] = site_url("contact");
		
		$category_products = $this->m_product_category->items(null,1);
		$products = $this->m_product->items(null,1);
		// $contents = $this->m_contents->items(null,1);

		foreach ($products as $product) {
			$url80[] =  site_url("{$product->alias}");
			// if (!empty($product->alias_en))
			// $url80[] =  site_url("{$product->alias_en}");
		}
		
		foreach ($category_products as $category_product) {
			$url64[] =  site_url("san-pham/{$category_product->alias}");
			$url64[] =  site_url("tim-kiem/{$category_product->alias}");
			// if (!empty($category_product->alias_en))
			// $url64[] =  site_url("product/{$category_product->alias_en}");
		}
		
		foreach ($url80 as $url) {
			$xmlstr .= '<url>';
			$xmlstr .= '<loc>'.$url.'</loc>';
			$xmlstr .= '<changefreq>daily</changefreq>';
			$xmlstr .= '<priority>0.80</priority>';
			$xmlstr .= '</url>';
		}
		 
		foreach ($url64 as $url) { 
			$xmlstr .= '<url>';
			$xmlstr .= '<loc>'.$url.'</loc>';
			$xmlstr .= '<changefreq>daily</changefreq>';
			$xmlstr .= '<priority>0.64</priority>';
			$xmlstr .= '</url>';
		}
		$xmlstr .= '</urlset>';
		chmod('sitemap.xml', 0777);
		
		$fp = fopen('sitemap.xml', 'w');
		fwrite($fp, $xmlstr);
		fclose($fp);
		
		chmod('sitemap.xml', 0664);
	}

	//------------------------------------------------------------------------------
	// History
	//------------------------------------------------------------------------------
	
	public function history($action=null, $id=null)
	{
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("History" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		$task = $this->util->value($this->input->post("task"), "");

		$fromdate				= !empty($_POST["fromdate"]) ? date("Y-m-d", strtotime($_POST["fromdate"])) : date("Y-m-d", strtotime(" -1 days"));
		$todate					= !empty($_POST["todate"]) ? date("Y-m-d", strtotime($_POST["todate"])) : date("Y-m-d");
		$page = (!empty($_GET["page"]) ? max($_GET["page"], 1) : 1);
		$info = new stdClass();
		$info->table_name = 'm_product';

		$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"), $this->m_history->count($info), 20);
		$items = $this->m_history->items($info, 20, ($page - 1) * 20);

		if (!empty($task)) {
			if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_history->delete($where);
				}
				redirect(site_url("syslog/history"));
			}
			else if ($task == "delete-all") {
				$this->m_history->delete_all();
				redirect(site_url("syslog/history"));
			} else if ($task = 'csv'){
				$info->fromdate = $fromdate;
				$info->todate = $todate;
				$total = $this->m_history->items($info);
	
				$i=1;
				$data[0] = array('user name','action','created_date','link');
				foreach ($total as $value) {
					$product = $this->m_product->load($value->item_id);
					$link = 'Đã xóa link';
					if (!empty($product)) {
						$link = site_url($product->alias);
					}
					$data[$i] = array(
						'user_name'		=> $value->user_name,
						'action' 		=> $value->action,
						'created_date' 	=> $value->created_date,
						'product_id' 	=> $link,						
					);
					$i++;
				}
				$filename = 'report-'.$fromdate.'-'.$todate;
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"{$filename}".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');

				foreach ($data as $data_array) {
					fputcsv($handle, $data_array);
				}
					fclose($handle);
				exit;
			}
		}

		$view_data = array();
		$view_data["items"]			= $items;
		$view_data["breadcrumb"]	= $this->_breadcrumb;
		$view_data["page"]			= $page;
		$view_data["pagination"]	= $pagination;
		$view_data["fromdate"]		= $fromdate;
		$view_data["todate"]		= $todate;
		
		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("admin/history/index", $view_data, true);
		$this->load->view("layout/admin/main", $tmpl_content);
	}
	
	public function ajax_history()
	{
		$id = $this->util->value($this->input->post("id"), 0);
		
		$view_data = array();
		$view_data["item"] = $this->m_history->load($id);
		echo $this->load->view("admin/history/ajax/detail", $view_data, true);
	}
	
	//------------------------------------------------------------------------------
	// Users
	//------------------------------------------------------------------------------
	
	public function users($action=null, $id=null)
	{
		$config_row_page = ADMIN_ROW_PER_PAGE;
		$pagi		= (isset($_GET["pagi"]) ? $_GET["pagi"] : $config_row_page);
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
			$page = 1;
		}
		else {
			$page = $_GET['page'];
		}
		$offset = ($page - 1) * $pagi;
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Users" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$fullname		= $this->input->post("fullname");
				// $email			= $this->input->post("email");
				// $password_text	= $this->input->post("password_text");
				$user_type		= $this->input->post("user_type");
				$user_rank		= $this->input->post("user_rank");
				// $avatar			= $this->input->post("avatar");
				
				$data = array (
					"fullname"		=> $fullname,
					// "email"			=> $email,
					// "password_text"	=> $password_text,
					// "password" 		=> md5($password_text),
					"user_rank"		=> $user_rank,
					"user_type"		=> $user_type,
					// "avatar"		=> $avatar
				);
				
				if ($action == "add") {
					$count_email = 0;
					if (empty($fullname)) {
						$this->session->set_flashdata("error", "Fullname is require.");
						redirect(site_url("syslog/users/add"), "back");
					}
					// else if (empty($email)) {
					// 	$this->session->set_flashdata("error", "Email is require.");
					// 	redirect(site_url("syslog/users/add"), "back");
					// }
					else if (empty($password_text)) {
						$this->session->set_flashdata("error", "Password is require.");
						redirect(site_url("syslog/users/add"), "back");
					}
					else if ($count_email != 0)
					{
						$this->session->set_flashdata("error", "Email error");
						redirect(site_url("syslog/users/add"), "back");
					}
					// else if ($this->m_user->get_user_email($email)) {
					// 	$this->session->set_flashdata("error", "Email is exist.");
					// 	redirect(site_url("syslog/users/add"), "back");
					// }
					// else if (strlen(trim($password_text)) < 6) {
					// 	$this->session->set_flashdata("error", "Password lenght 6");
					// 	redirect(site_url("syslog/users/add"), "back");
					// } else {
					// 	$this->m_user->add($data);
					// 	$this->session->set_flashdata("success", "Register successfull");
					// }
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_user->update($data, $where);
					$this->session->set_flashdata("success", "Update successfull");
				}
				redirect(site_url("syslog/users"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/users"));
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_user->update($data, $where);
				}
				redirect(site_url("syslog/users"));
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_user->update($data, $where);
				}
				$user = $this->m_user->load($ids[0]);
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_user->remove($where);
				}
				$this->session->set_flashdata("success", "Delete successfull");
				redirect(site_url("syslog/users"));
			}
		}
		
		if ($action == "add") {
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Add user" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/add")));

			$view_data = array();
			$view_data["user"] = $this->m_user->instance();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/account/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Update profile" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/edit/{$id}")));

			$view_data = array();
			$view_data["user"] = $this->m_user->load($id);
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/account/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		} else {
			$search_text = $this->util->value($this->input->post("search_text"), "");
			
			$info = new stdClass();
			if (!empty($search_text)) {
				$info->search_text = $search_text;
			}
			$total = count($this->m_user->users($info));
			$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"). "?$_SERVER[QUERY_STRING]", $total, $pagi);
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["users"]			= $this->m_user->users($info, null, $pagi, $offset);
			$view_data["offset"]		= $offset;
			$view_data["pagination"]	= $pagination;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/account/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function block_users($action=null, $id=null)
	{
		$config_row_page = ADMIN_ROW_PER_PAGE;
		$pagi		= (isset($_GET["pagi"]) ? $_GET["pagi"] : $config_row_page);
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
			$page = 1;
		}
		else {
			$page = $_GET['page'];
		}
		$offset = ($page - 1) * $pagi;
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Block Users" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$phone		= $this->input->post("phone");
				
				$data = array (
					"phone"		=> $phone,
				);
				
				if ($action == "add") {
					$this->m_block->add($data);
				}
				redirect(site_url("syslog/block-users"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/block-users"));
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_block->remove($where);
				}
				$this->session->set_flashdata("success", "Delete successfull");
				redirect(site_url("syslog/block-users"));
			}
		}
		
		if ($action == "add") {
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Thêm số điện thoại" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/add")));

			$view_data = array();
			$view_data["user"] = $this->m_block->instance();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/account/block_user/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		} else {
			$search_text = $this->util->value($this->input->post("search_text"), "");
			
			$info = new stdClass();
			if (!empty($search_text)) {
				$info->search_text = $search_text;
			}
			$total = count($this->m_block->users($info));
			$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"). "?$_SERVER[QUERY_STRING]", $total, $pagi);
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["users"]			= $this->m_block->users($info, null, $pagi, $offset);
			$view_data["offset"]		= $offset;
			$view_data["pagination"]	= $pagination;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/account/block_user/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function statistic ($action=null, $id=null) {

		$fromdate				= !empty($_GET["fromdate"]) ? date("Y-m-d", strtotime($_GET["fromdate"])) : date("Y-m-d");
		$todate					= !empty($_GET["todate"]) ? date("Y-m-d", strtotime($_GET["todate"])) : date("Y-m-d");
		$type					= !empty($_GET["type"]) ? $_GET["type"] : '';

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Thống kê" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		if (!empty($type)) {
			if ($type != 'year') {
				$result = $this->util->get_day_statistic($type);
				$fromdate 	= $result[0];
				$todate 	= $result[1];
			} else {
				$fromdate 	= date('Y').'-01-01';
				$todate 	= date('Y').'-12-31';
			}
		}

		$info = new stdClass();
		$info->from_paid_date = $fromdate;
		$info->to_paid_date = $todate;
		$info->payment_status = 2;
		$items = $this->m_booking->items($info);

		foreach ($items as $item) {
			$info = new stdClass();
			$info->booking_id = $item->id;
			$item->detail = $this->m_booking_detail->items($info);
		}

		$view_data = array();
		$view_data["breadcrumb"] 			= $this->_breadcrumb;
		$view_data["type"] 					= $type;
		$view_data["items"] 				= $items;
		$view_data["fromdate"]				= $fromdate;
		$view_data["todate"]				= $todate;
		
		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("admin/statistic/index", $view_data, true);
		$this->load->view("layout/admin/main", $tmpl_content);
	}
	public function booking ($action=null, $id=null) { 
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
				$page = 1;
		}
		else {
				$page = $_GET['page'];
		}
		$offset = ($page - 1) * ADMIN_ROW_PER_PAGE;

		$original_search_text	= !empty($_GET["search_text"]) ? $_GET["search_text"] : '';
		$fromdate				= !empty($_GET["fromdate"]) ? $_GET["fromdate"] : date("Y-m-d",strtotime("-7days"));
		$todate					= !empty($_GET["todate"]) ? $_GET["todate"] : date("Y-m-d");
		
		$search_text = strtoupper(trim($original_search_text));
		$search_text = str_replace(array(BOOKING_PREFIX), "", $search_text);
		
		if (!empty($search_text)) {
			$fromdate = "";
			$todate = "";
		}
		
		if (!empty($fromdate)) {
			$fromdate = date("Y-m-d", strtotime($fromdate));
		}
		if (!empty($todate)) {
			$todate = date("Y-m-d", strtotime($todate));
		}
		$info = new stdClass();
		$info->search_text		= $search_text;
		$info->fromdate			= $fromdate;
		$info->todate			= $todate;

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Đơn hàng" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$product_id			= $this->util->value($this->input->post("product_id"), "");
				$typename			= $this->util->value($this->input->post("typename"), "");
				$subtypename		= $this->util->value($this->input->post("subtypename"), "");
				$thumbnail			= $this->util->value($this->input->post("thumbnail"), "");
				$qty				= $this->util->value($this->input->post("qty"), "");
				$discount_detail	= $this->util->value($this->input->post("discount_detail"), "");
				$ship_money			= $this->util->value($this->input->post("ship_money"), "");
				$discount			= $this->util->value($this->input->post("discount"), "");
				$fullname			= $this->util->value($this->input->post("fullname"), "");
				$email				= $this->util->value($this->input->post("email"), "");
				$phone				= $this->util->value($this->input->post("phone"), "");
				$booking_type		= $this->util->value($this->input->post("booking_type"), "");
				$address			= $this->util->value($this->input->post("address"), "");
				$message			= $this->util->value($this->input->post("message"), "");
				$payment			= $this->util->value($this->input->post("payment"), "");
				$promotion			= $this->util->value($this->input->post("promotion"), "");

				$code_promotion = '';
				$item = $this->m_promotion->load($promotion);
				$promotion_item = null;
				if (!empty($item)){
					if (!empty($item->limit_time)){
						if (date('Y-m-d',strtotime($item->fromdate ))<= date('Y-m-d') && date('Y-m-d',strtotime($item->todate ))>= date('Y-m-d')){
							$promotion_item = $item;
						}
					} else {
						$promotion_item = $item;
					}
				}
				
				$total_sale_price = 0;
				$total_sale_product_price = 0;
				$total_price = 0;

				$c = count($product_id);
				$capital = 0;
				$total = 0;
				$discount = 0;

				if ($action == "add") {
					for ($i=0;$i<$c;$i++) {
						$product = $this->m_product->load($product_id[$i]);
						$info = new stdClass();
						$info->product_id = $product->id;
						$product->product_type = $this->m_product_type->items($info);
						$j = 0;
						$key_i = -1;
						$key_j = -1;
						$product_type_temp = 0;
						foreach($product->product_type as $product_type) {
							if ($product_type->typename == $typename[$i]) {
								$key_i = $j;
								$product_type_temp = $product_type->id;
								if ($subtypename[$i] != '')
									$key_j = array_search($subtypename[$i], json_decode($product_type->subtypename));
								break;
							}
							$j++;
						}
						$product_type_temp = $product->product_type[0];
						$price = json_decode($product->product_type[0]->price)[0];
						$sale = json_decode($product->product_type[0]->sale)[0];
						$cost = json_decode($product->product_type[0]->cost)[0];
						$product_id_temp = $product->id;
						if ($key_i != -1) {
							$product_id_temp .= $key_i;
							$product_type_temp = $product->product_type[$key_i];
							$price = json_decode($product->product_type[$key_i]->price)[0];
							$sale = json_decode($product->product_type[$key_i]->sale)[0];
							$cost = json_decode($product->product_type[$key_i]->cost)[0];
							$quantity = json_decode($product_type_temp->quantity);
							$quantity[0] = (string)(json_decode($product_type_temp->quantity)[0] - $qty[$i]);
						}
						if ($key_j != -1) {
							$product_id_temp .= $key_j;
							$price  = json_decode($product->product_type[$key_i]->price)[$key_j];
							$sale  = json_decode($product->product_type[$key_i]->sale)[$key_j];
							$cost  = json_decode($product->product_type[$key_i]->cost)[$key_j];
							$quantity = json_decode($product_type_temp->quantity);
							$quantity[$key_j] = (string)(json_decode($product_type_temp->quantity)[$key_j] - $qty[$i]);
						}
						//
						$data_product_type = array(
							"quantity" => json_encode($quantity),
						);
						$this->m_product_type->update($data_product_type,array('id'=>$product_type_temp->id));
						//
						$price_sale = $price*(1 - $sale*0.01);
						$booking_id = $this->m_booking->get_next_value();
						$data_detail = array(
							'booking_id' 		=> $booking_id,
							'title' 			=> $product->title,
							'product_id' 		=> $product->id,
							'product_id_temp'	=> $product_id_temp,
							'qty' 				=> $qty[$i],
							'discount' 			=> $discount_detail[$i],
							'typename' 			=> $typename[$i],
							'subtypename' 		=> $subtypename[$i],
							"price_sale" 		=> $price_sale,
							"price" 			=> $price,
							'key_i' 			=> $key_i,
							'key_j' 			=> $key_j,
							'thumbnail' 		=> $thumbnail[$i],
						);
						$this->m_booking_detail->add($data_detail);
	
						if (!empty($promotion_item)){
							$sale_value = $promotion_item->sale_value;
							if ($sale != 0){
								$total_sale_product_price += ($qty[$i]*$cost)*$sale*0.01;
							}
							if ($sale>$sale_value){
								$sale_value = $sale;
							}
							if ($promotion_item->sale_type == 0){
								$total_sale_price += ($qty[$i]*$cost)*$promotion_item->sale_value*0.01;
							} else {
								$total_sale_price = $sale_value;
							}
						}
						$discount 	+= $qty[$i]*$discount_detail[$i];
						$total 		+= $qty[$i]*$price_sale;
						$total_price+= $qty[$i]*$price;
						$capital 	+= $qty[$i]*$cost;
					}
					if (!empty($promotion_item)){
						if ($promotion_item->sale_type == 0){
							if ($total_sale_price > $promotion_item->money_limit){
								$total_sale_price = $promotion_item->money_limit;
							}
							if ($total_sale_product_price > $promotion_item->money_limit){
								$total_sale_price = $total_sale_product_price;
							}
						} else {
							if ($total_sale_product_price > $promotion_item->sale_value){
								$total_sale_price = $total_sale_product_price;
							}
						}
						if ($promotion_item->bill_money < $total_price){
							$total = $total_price - $total_sale_price;
							$code_promotion = $promotion;
						}
					}
					$key = md5($booking_id.time());
					$data = array(
						"booking_key"	=> $key,
						"fullname"		=> $fullname,
						"phone"			=> $phone,
						"email"			=> $email,
						"user_id"		=> $this->session->userdata("admin")->id,
						"address"		=> $address,
						"booking_type"	=> $booking_type,
						"ship_money"	=> $ship_money,
						"discount"		=> $discount,
						"code_promotion"=> $code_promotion,
						"message"		=> $message,
						"payment"		=> $payment,
						"total"			=> $total - $discount,
						"cost"			=> $capital,
						"user_id"		=> 0,
					);
					$this->m_booking->add($data);
					$this->session->set_flashdata("success", "Tạo thành công");
				} else if ($action == 'edit') {
					$info = new stdClass();
					$info->booking_id = $id;
					$details = $this->m_booking_detail->items($info);
					for ($i=0;$i<$c;$i++) {
						$product = $this->m_product->load($product_id[$i]);
						$info = new stdClass();
						$info->product_id = $product->id;
						$product->product_type = $this->m_product_type->items($info);
						$j = 0;
						$key_i = -1;
						$key_j = -1;
						$product_type_temp = 0;
						foreach($product->product_type as $product_type) {
							if ($product_type->typename == $typename[$i]) {
								$key_i = $j;
								$product_type_temp = $product_type->id;
								if ($subtypename[$i] != '')
									$key_j = array_search($subtypename[$i], json_decode($product_type->subtypename));
								break;
							}
							$j++;
						}
						$product_type_temp = $product->product_type[0];
						$price = json_decode($product->product_type[0]->price)[0];
						$sale = json_decode($product->product_type[0]->sale)[0];
						$cost = json_decode($product->product_type[0]->cost)[0];
						$product_id_temp = $product->id;

						$qty_temp = $qty[$i] - $details[$i]->qty;

						if ($key_i != -1) {
							$product_id_temp .= $key_i;
							$product_type_temp = $product->product_type[$key_i];
							$price = json_decode($product->product_type[$key_i]->price)[0];
							$sale = json_decode($product->product_type[$key_i]->sale)[0];
							$cost = json_decode($product->product_type[$key_i]->cost)[0];
							$quantity = json_decode($product_type_temp->quantity);
							$quantity[0] = (string)(json_decode($product_type_temp->quantity)[0] - $qty_temp);
						}
						if ($key_j != -1) {
							$product_id_temp .= $key_j;
							$price  = json_decode($product->product_type[$key_i]->price)[$key_j];
							$sale  = json_decode($product->product_type[$key_i]->sale)[$key_j];
							$cost  = json_decode($product->product_type[$key_i]->cost)[$key_j];
							$quantity = json_decode($product_type_temp->quantity);
							$quantity[$key_j] = (string)(json_decode($product_type_temp->quantity)[$key_j] - $qty_temp);
						}
						
						//
						$data_product_type = array(
							"quantity" => json_encode($quantity),
						);
						$this->m_product_type->update($data_product_type,array('id'=>$product_type_temp->id));
						$price_sale = $price*(1 - $sale*0.01);
						$data_detail = array(
							'title' 			=> $product->title,
							'product_id' 		=> $product->id,
							'product_id_temp'	=> $product_id_temp,
							'qty' 				=> $qty[$i],
							'discount' 			=> $discount_detail[$i],
							'typename' 			=> $typename[$i],
							'subtypename' 		=> $subtypename[$i],
							"price_sale" 		=> $price_sale,
							"price" 			=> $price,
							'key_i' 			=> $key_i,
							'key_j' 			=> $key_j,
							'thumbnail' 		=> $thumbnail[$i],
						);
						$this->m_booking_detail->update($data_detail,array('id'=>$details[$i]->id));
	
						if (!empty($promotion_item)){
							$sale_value = $promotion_item->sale_value;
							if ($sale != 0){
								$total_sale_product_price += ($qty[$i]*$cost)*$sale*0.01;
							}
							if ($sale>$sale_value){
								$sale_value = $sale;
							}
							if ($promotion_item->sale_type == 0){
								$total_sale_price += ($qty[$i]*$cost)*$promotion_item->sale_value*0.01;
							} else {
								$total_sale_price = $sale_value;
							}
						}
						$discount 	+= $qty[$i]*$discount_detail[$i];
						$total 		+= $qty[$i]*$price_sale;
						$total_price+= $qty[$i]*$price;
						$capital 	+= $qty[$i]*$cost;
					}
					if (!empty($promotion_item)){
						if ($promotion_item->sale_type == 0){
							if ($total_sale_price > $promotion_item->money_limit){
								$total_sale_price = $promotion_item->money_limit;
							}
							if ($total_sale_product_price > $promotion_item->money_limit){
								$total_sale_price = $total_sale_product_price;
							}
						} else {
							if ($total_sale_product_price > $promotion_item->sale_value){
								$total_sale_price = $total_sale_product_price;
							}
						}
						if ($promotion_item->bill_money < $total_price){
							$total = $total_price - $total_sale_price;
							$code_promotion = $promotion;
						}
					}
					$data = array(
						"fullname"		=> $fullname,
						"phone"			=> $phone,
						"email"			=> $email,
						"user_id"		=> $this->session->userdata("admin")->id,
						"address"		=> $address,
						"ship_money"	=> $ship_money,
						"booking_type"	=> $booking_type,
						"discount"		=> $discount,
						"code_promotion"=> $code_promotion,
						"message"		=> $message,
						"payment"		=> $payment,
						"total"			=> $total - $discount,
						"cost"			=> $capital,
						"user_id"		=> 0,
					);
					$this->m_booking->update($data,array("id"=>$id));
				}
				redirect(site_url("syslog/booking")."?fromdate={$fromdate}&todate={$todate}");
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/booking")."?fromdate={$fromdate}&todate={$todate}");
			}
		}

		if ($action == "add") {
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Tạo đơn hàng" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/booking/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Edit đơn hàng" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			$booking = $this->m_booking->load($id);
			$info = new stdClass();
			$info->booking_id = $id;
			$booking->details = $this->m_booking_detail->items($info);
			$view_data = array();
			$view_data["booking"] 		= $booking;
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/booking/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "delete") {
			$info = new stdClass();
			$info->booking_id = $id;
			$details = $this->m_booking_detail->items($info);
			foreach($details as $detail) {
				$info = new stdClass();
				$info->product_id = $detail->product_id;
				$info->typename = $detail->typename;
				$product_types = $this->m_product_type->items($info);
				$key_j = ($detail->key_j != -1)?$detail->key_j:0;
				foreach($product_types as $product_type){
					$product_qty = json_decode($product_type->quantity);
					$product_qty[$key_j] = $product_qty[$key_j] + $detail->qty;
					$this->m_product_type->update(array("quantity"=>json_encode($product_qty)),array("id"=>$product_type->id));
				}
				$this->m_booking_detail->delete(array("id"=>$detail->id));
			}
			$this->m_booking->delete(array("id"=>$id));
			redirect(site_url("syslog/booking")."?fromdate={$fromdate}&todate={$todate}");
		}
		else if ($action == "export") {
			$info = new stdClass();
			$info->booking_id = $id;
			$booking = $this->m_booking->load($id);
			$booking->details = $this->m_booking_detail->items($info);

			$tmpl_content = array();
			$tmpl_content["code"] 		= BOOKING_PREFIX.$id;
			$tmpl_content["booking"] 	= $booking;
			$tmpl_content["setting"] 	= $this->m_setting->load(1);
			$this->load->view("admin/booking/export", $tmpl_content,false);
		}
		else if ($action == "export-bill") {
			$info = new stdClass();
			$info->booking_id = $id;
			$booking = $this->m_booking->load($id);
			$booking->details = $this->m_booking_detail->items($info);

			$tmpl_content = array();
			$tmpl_content["code"] 		= BOOKING_PREFIX.$id;
			$tmpl_content["booking"] 	= $booking;
			$tmpl_content["setting"] 	= $this->m_setting->load(1);
			$this->load->view("admin/booking/export_bill", $tmpl_content,false);
		}
		else if ($action == "view-booking") {
			$info_user = new stdClass();
			$info_user->user_type = -1;
			$users = $this->m_user->users($info_user);
			foreach($users as $user){
				$info->user_id = $user->id;
				$info->payment_status = 2;
				$user->booking = $this->m_booking->view_booking($info);
			}
			$view_data = array();
			$view_data["breadcrumb"] 			= $this->_breadcrumb;
			$view_data["users"] 				= $users;
			$view_data["search_text"]			= $original_search_text;
			$view_data["edited_search_text"]	= $search_text;
			$view_data["fromdate"]				= $fromdate;
			$view_data["todate"]				= $todate;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/booking/view_booking", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "view-booking-detail") {
			$info->user_id = $id;
			$total = count($this->m_booking->items($info));
			$items = $this->m_booking->items($info, ADMIN_ROW_PER_PAGE, $offset);
			foreach ($items as $item) {
				$info_detail = new stdClass();
				$info_detail->booking_id = $item->id;
				$item->detail = $this->m_booking_detail->items($info_detail);
			}

			$pagination = $this->util->pagination_admin(site_url('syslog/booking')."?task=search&boxchecked=0&search_text={$search_text}&fromdate={$fromdate}&todate={$todate}", $total, ADMIN_ROW_PER_PAGE);

			$view_data = array();
			$view_data["breadcrumb"] 			= $this->_breadcrumb;
			$view_data["items"] 				= $items;
			$view_data["pagination"] 			= $pagination;
			$view_data["search_text"]			= $original_search_text;
			$view_data["edited_search_text"]	= $search_text;
			$view_data["fromdate"]				= $fromdate;
			$view_data["todate"]				= $todate;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/booking/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {
			$total = count($this->m_booking->items($info));
			$items = $this->m_booking->items($info, ADMIN_ROW_PER_PAGE, $offset);
			foreach ($items as $item) {
				$info_detail = new stdClass();
				$info_detail->booking_id = $item->id;
				$item->detail = $this->m_booking_detail->items($info_detail);
			}

			$pagination = $this->util->pagination_admin(site_url('syslog/booking')."?task=search&boxchecked=0&search_text={$search_text}&fromdate={$fromdate}&todate={$todate}", $total, ADMIN_ROW_PER_PAGE);

			$view_data = array();
			$view_data["breadcrumb"] 			= $this->_breadcrumb;
			$view_data["items"] 				= $items;
			$view_data["pagination"] 			= $pagination;
			$view_data["search_text"]			= $original_search_text;
			$view_data["edited_search_text"]	= $search_text;
			$view_data["fromdate"]				= $fromdate;
			$view_data["todate"]				= $todate;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/booking/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function order ($action=null, $id=null) { 
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
				$page = 1;
		}
		else {
				$page = $_GET['page'];
		}
		$offset = ($page - 1) * ADMIN_ROW_PER_PAGE;

		$original_search_text	= !empty($_GET["search_text"]) ? $_GET["search_text"] : '';
		$fromdate				= !empty($_GET["fromdate"]) ? $_GET["fromdate"] : date("Y-m-d",strtotime("-7days"));
		$todate					= !empty($_GET["todate"]) ? $_GET["todate"] : date("Y-m-d");
		
		$search_text = strtoupper(trim($original_search_text));
		$search_text = str_replace(array(BOOKING_PREFIX), "", $search_text);
		
		if (!empty($search_text)) {
			$fromdate = "";
			$todate = "";
		}
		
		if (!empty($fromdate)) {
			$fromdate = date("Y-m-d", strtotime($fromdate));
		}
		if (!empty($todate)) {
			$todate = date("Y-m-d", strtotime($todate));
		}
		$info = new stdClass();
		$info->search_text		= $search_text;
		$info->fromdate			= $fromdate;
		$info->todate			= $todate;

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Hàng order" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		
		$total = count($this->m_order->items($info));
		$items = $this->m_order->items($info, ADMIN_ROW_PER_PAGE, $offset);
		foreach ($items as $item) {
			$info_detail = new stdClass();
			$info_detail->order_id = $item->id;
			$item->detail = $this->m_order_detail->items($info_detail);
		}
		if ($action == "delete") {
			$info = new stdClass();
			$info->order_id = $id;
			$details = $this->m_order_detail->items($info);
			foreach($details as $detail) {
				$this->m_order_detail->delete(array("id"=>$detail->id));
			}
			$this->m_order->delete(array("id"=>$id));
			redirect(site_url("syslog/order")."?fromdate={$fromdate}&todate={$todate}");
		}
		$pagination = $this->util->pagination_admin(site_url('syslog/booking')."?task=search&boxchecked=0&search_text={$search_text}&fromdate={$fromdate}&todate={$todate}", $total, ADMIN_ROW_PER_PAGE);

		$view_data = array();
		$view_data["breadcrumb"] 			= $this->_breadcrumb;
		$view_data["items"] 				= $items;
		$view_data["pagination"] 			= $pagination;
		$view_data["search_text"]			= $original_search_text;
		$view_data["edited_search_text"]	= $search_text;
		$view_data["fromdate"]				= $fromdate;
		$view_data["todate"]				= $todate;
		
		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("admin/order/index", $view_data, true);
		$this->load->view("layout/admin/main", $tmpl_content);
	}
	public function booking_phone ($action=null, $id=null) {
		$config_row_page = 5;
		$pagi		= (isset($_GET["pagi"]) ? $_GET["pagi"] : $config_row_page);
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
			$page = 1;
		}
		else {
			$page = $_GET['page'];
		}
		$offset = ($page - 1) * $pagi;

		$total = 0;
		$bookings = array();
		$search_text = '';

		if (!empty($_GET['search_text'])) {
			$search_text = $_GET['search_text'];
			$temp = substr($search_text, 0, 1);
			$search_data = $search_text;
			if ($temp == '+') {
				$search_data = ltrim($search_text, '+84');
			} else if ($temp == '0') {
				$search_data = ltrim($search_text, '0');
			}
			$info = new stdClass();
			$info->phone = $search_data;
			$total = count($this->m_booking->items($info));
			$bookings = $this->m_booking->items($info, 5, $offset);
			foreach ($bookings as $booking){
				$info = new stdClass();
				$info->booking_id = $booking->id;
				$booking->details = $this->m_booking_detail->items($info);
			}
		}
		
		$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"). "?$_SERVER[QUERY_STRING]", $total, $pagi);

		$view_data = array();
		$view_data["breadcrumb"] 			= $this->_breadcrumb;
		$view_data["total"] 				= $total;
		$view_data["bookings"] 				= $bookings;
		$view_data["pagination"] 			= $pagination;
		$view_data["search_text"]			= $search_text;

		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("admin/booking/booking_phone", $view_data, true);
		$this->load->view("layout/admin/main", $tmpl_content);
	}
	public function slide ($action=null, $id=null) {
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
				$page = 1;
		}
		else {
				$page = $_GET['page'];
		}
		$offset = ($page - 1) * ADMIN_ROW_PER_PAGE;
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Slide" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$title			= $this->util->value($this->input->post("title"), "");
				$thumbnail 		= !empty($_FILES['thumbnail']['name']) ? explode('.',$_FILES['thumbnail']['name']) : $this->m_slide->load($id)->thumbnail;
				$link			= $this->util->value($this->input->post("link"), "");
				$active			= $this->util->value($this->input->post("active"), 1);
				$description	= $this->util->value($this->input->post("description"), "");
				if (empty($alias)) {
					$alias = $this->util->slug($description);
				}
				if (empty($id)) {
					$id = $this->m_slide->get_next_value();
				}
				$data = array (
					"title"			=> $title,
					"link"			=> $link,
					"description"	=> $description,
					"active"		=> $active
				);

				if (!empty($_FILES['thumbnail']['name'])){
					$file_name = explode('.',$_FILES['thumbnail']['name']);
					if (end($file_name) == 'png' || end($file_name) == 'PNG'){
						$data['thumbnail'] = "/images/slide/{$id}/thumb/{$this->util->slug($thumbnail[0])}.jpg";
					} else {
						$data['thumbnail'] = "/images/slide/{$id}/thumb/{$this->util->slug($thumbnail[0])}.".end($file_name);
					}
				}
			
				if ($action == "add") {
					$this->m_slide->add($data);
					$this->session->set_flashdata("success", "Add successfull");
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_slide->update($data, $where);
					$this->session->set_flashdata("success", "Update successfull");
				}
				$path = "./images/slide/{$id}/";
				if (!empty($_FILES['thumbnail']['name'])){
					$allow_type = 'JPG|PNG|jpg|jpeg|png';
					$this->util->upload_file($path,'thumbnail','',$allow_type,1200);
				}
			}
			else if ($task == "cancel") {
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_slide->update($data, $where);
				}
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_slide->update($data, $where);
				}
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_slide->delete($where);
				}
				$this->session->set_flashdata("success", "Delete successfull");
			}
			redirect(site_url("syslog/slide"));
		}

		if ($action == "add") {
			$item = $this->m_slide->instance();
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Add Item" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			
			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/slide/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_slide->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Update Item" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));

			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["item"] 			= $item;

			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/slide/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {

			$total = count($this->m_slide->items());
			$items = $this->m_slide->items(null,null, ADMIN_ROW_PER_PAGE, $offset);

			$pagination = $this->util->pagination_admin(site_url('syslog/slide/'), $total, ADMIN_ROW_PER_PAGE);
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["items"] 		= $items;
			$view_data["pagination"] 	= $pagination;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/slide/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function brand ($action=null, $id=null) {
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
				$page = 1;
		}
		else {
				$page = $_GET['page'];
		}
		$offset = ($page - 1) * ADMIN_ROW_PER_PAGE;
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Nhãn hiệu" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$name			= $this->util->value($this->input->post("name"), "");
				$alias			= $this->util->value($this->input->post("alias"), "");
				$content			= $this->util->value($this->input->post("content"), "");
				$content_en			= $this->util->value($this->input->post("content_en"), "");
				// $thumbnail 		= !empty($_FILES['thumbnail']['name']) ? explode('.',$_FILES['thumbnail']['name']) : $this->m_brand->load($id)->thumbnail;
				// $product_category_id			= $this->util->value($this->input->post("product_category_id"), 1);
				$active			= $this->util->value($this->input->post("active"), 1);
				if (empty($alias)) {
					$alias = $this->util->slug($name);
				}
				if (empty($id)) {
					$id = $this->m_slide->get_next_value();
				}
				$data = array (
					"name"			=> $name,
					"alias"			=> $alias,
					"content"			=> $content,
					"content_en"			=> $content_en,
					// "product_category_id"=> $product_category_id,
					"active"		=> $active
				);
				// if (!empty($_FILES['thumbnail']['name'])){
				// 	$file_name = explode('.',$_FILES['thumbnail']['name']);
				// 	if (end($file_name) == 'png' || end($file_name) == 'PNG'){
				// 		$data['thumbnail'] = "/images/brand/{$id}/thumb/{$this->util->slug($thumbnail[0])}.jpg";
				// 	} else {
				// 		$data['thumbnail'] = "/images/brand/{$id}/thumb/{$this->util->slug($thumbnail[0])}.".end($file_name);
				// 	}
				// }

				if ($action == "add") {
					$this->m_brand->add($data);
					$this->session->set_flashdata("success", "Add successfull");
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_brand->update($data, $where);
					$this->session->set_flashdata("success", "Update successfull");
				}
				// $path = "./images/brand/{$id}/";
				// if (!empty($_FILES['thumbnail']['name'])){
				// 	$allow_type = 'JPG|PNG|jpg|jpeg|png';
				// 	$this->util->upload_file($path,'thumbnail','',$allow_type,200);
				// }
				redirect(site_url("syslog/brand"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/brand"));
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_brand->update($data, $where);
				}
				redirect(site_url("syslog/brand"));
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_brand->update($data, $where);
				}
				redirect(site_url("syslog/brand"));
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_brand->delete($where);
				}
				$this->session->set_flashdata("success", "Delete successfull");
				redirect(site_url("syslog/brand"));
			}
		}
		$info = new stdClass();
		$info->parent_id = 0;
		$categories = $this->m_product_category->items($info,1);
		if ($action == "add") {
			$item = $this->m_brand->instance();
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Thêm" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			
			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["categories"] = $categories;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/brand/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_brand->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Chỉnh sửa" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));

			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["categories"] = $categories;
			$view_data["item"] 			= $item;

			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/brand/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {

			$total = count($this->m_brand->items());
			$items = $this->m_brand->items(null,null, ADMIN_ROW_PER_PAGE, $offset);

			$pagination = $this->util->pagination_admin(site_url('syslog/brand/'), $total, ADMIN_ROW_PER_PAGE);
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["categories"] = $categories;
			$view_data["items"] 		= $items;
			$view_data["pagination"] 	= $pagination;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/brand/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	//------------------------------------------------------------------------------
	// product
	//------------------------------------------------------------------------------
	public function product_category ($action=null, $id=null) {

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Product Categories" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		$task = $this->util->value($this->input->post("task"), "");

		if (!empty($task)) {
			if ($task == "save") {
				$name			= $this->util->value($this->input->post("name"), "");
				$name_en		= $this->util->value($this->input->post("name_en"), "");
				$description	= $this->util->value($this->input->post("description"), "");
				$description_en	= $this->util->value($this->input->post("description_en"), "");
				$code			= $this->util->value($this->input->post("code"), "");
				$order_category	= $this->util->value($this->input->post("order_category"), "");
				$alias			= $this->util->value($this->input->post("alias"), "");
				$alias_en		= $this->util->value($this->input->post("alias_en"), "");
				$parent_id		= $this->util->value($this->input->post("parent_id"), "");
				$active			= $this->util->value($this->input->post("active"), 1);
				$meta_key		= $this->util->value($this->input->post("meta_key"), "");
				$description	= $this->util->value($this->input->post("description"), "");
				if (empty($alias)) {
					$alias = $this->util->slug($name);
				}
				if (empty($alias_en)) {
					$alias_en = $this->util->slug($name_en);
				}
				if (empty($id)) {
					$id = $this->m_product_category->get_next_value();
				}
				$data = array (
					"name"			=> $name,
					"name_en"		=> $name_en,
					"description"	=> $description,
					"description_en"=> $description_en,
					"order_category"=> $order_category,
					"alias"			=> $alias,
					"alias_en"		=> $alias_en,
					"code"			=> $code,
					"parent_id"		=> $parent_id,
					"description"	=> $description,
					"meta_key"		=> $meta_key,
					"active"		=> $active
				);
				
				if ($action == "add") {
					$this->m_product_category->add($data);
					$this->session->set_flashdata("success", "Add successfull");
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_product_category->update($data, $where);
					$this->session->set_flashdata("success", "Update successfull");
				}
				redirect(site_url("syslog/product-category"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/product-category"));
			}
			else if ($task == "orderup") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$this->m_product_category->order_up($id);
				}
				redirect(site_url("syslog/product-category"));
			}
			else if ($task == "orderdown") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$this->m_product_category->order_down($id);
				}
				redirect(site_url("syslog/product-category"));
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_product_category->update($data, $where);
				}
				redirect(site_url("syslog/product-category"));
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_product_category->update($data, $where);
				}
				redirect(site_url("syslog/product-category"));
			}
			else if ($task == "highlight") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("show_cate" => 1);
					$where = array("id" => $id);
					$this->m_product_category->update($data, $where);
				}
				redirect(site_url("syslog/product-category"));
			}
			else if ($task == "normal") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("show_cate" => 0);
					$where = array("id" => $id);
					$this->m_product_category->update($data, $where);
				}
				redirect(site_url("syslog/product-category"));
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_product_category->delete($where);
				}
				$this->session->set_flashdata("success", "Delete successfull");
				redirect(site_url("syslog/product-category"));
			}
		}

		if ($action == "add") {
			$item = $this->m_product_category->instance();
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Add Item" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			
			$view_data = array();
			$view_data["categories"] 			= $this->m_product_category->items();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/product/category/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_product_category->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Update Item" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));

			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["item"] 			= $item;
			$view_data["categories"] 			= $this->m_product_category->items();

			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/product/category/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {
			$category_info = new stdClass();
			$category_info->parent_id = 0;
			$category_info->lang = 'vi';
			$items = $this->m_product_category->items($category_info,null,null,null,'order_num','ASC');
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["items"] 		= $items;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/product/category/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function product($category_id=null, $action=null, $id=null)
	{
		$config_row_page = ADMIN_ROW_PER_PAGE;
		$pagi		= (isset($_GET["pagi"]) ? $_GET["pagi"] : $config_row_page);
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
				$page = 1;
		}
		else {
				$page = $_GET['page'];
		}
		$offset = ($page - 1) * $pagi;
		$category_name = '';
		$category = $this->m_product_category->load($category_id);
		if (!empty($category)){
			$category_name = "/{$category->name}";
		}

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Danh sách sản phẩm{$category_name}" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$category_id}")));

		$search_text = $this->util->value($this->input->post("search_text"), "");
		$task = $this->util->value($this->input->post("task"), "");
		$info_type = new stdClass();
		$info_type->product_id = !empty($id) ? $id : $this->m_product->get_next_value();
		$product_types = $this->m_product_type->items($info_type);
		if (!empty($category)){
			$product_category_id = !empty($category->parent_id)?$category->parent_id:$category->id;
		} else {
			$product_category_id = 0;
		}
		$info_brand = new stdClass();
		$info_brand->product_category_id = $product_category_id;
		$brands = $this->m_brand->items($info_brand);
		if (!empty($task)) {
			if ($task == "save") {
				if (empty($id)) {
					$id = $this->m_product->get_next_value();
				} else {
					$info = new stdClass();
					$info->product_id = $id;
					$old_photos = $this->m_product_gallery->items($info);
				}
				$category_main		= '['.$this->util->value($this->input->post("category_main"), "").']';
				$title				= $this->util->value($this->input->post("title"), "");
				$alias				= $this->util->value($this->input->post("alias"), "");
				$title_en			= $this->util->value($this->input->post("title_en"), "");
				$alias_en			= $this->util->value($this->input->post("alias_en"), "");
				$code				= $this->util->value($this->input->post("code"), "");
				$code_product		= $this->util->value($this->input->post("code_product"), "");
				$order_product		= $this->util->value($this->input->post("order_product"), 1);
				$ship_des			= $this->util->value($this->input->post("ship_des"), "");
				$content			= $this->util->value($this->input->post("content"), "");
				$content_en			= $this->util->value($this->input->post("content_en"), "");
				$brand				= $this->input->post("product_brand");
				$origin				= $this->util->value($this->input->post("origin"), "");
				$video				= $this->util->value($this->input->post("video"), "");
				// if ($action == 'add'){
				// 	$cate_main_id = str_replace('[','',$category_main);
				// 	$cate_main_id = str_replace(']','',$cate_main_id);
				// 	$list_category		= '['.$this->util->value($cate_main_id.','.$this->input->post("list_category"), "").']';
				// } else if ($action == 'edit') {
				// 	$list_category		= '['.$this->util->value($this->input->post("list_category"), "").']';
				// }
				$list_category		= '['.$this->util->value($this->input->post("list_category"), "").']';
				$affiliate			= $this->util->value($this->input->post("affiliate"), "");
				$name_categorize1	= $this->util->value($this->input->post("name_categorize1"), "");
				$name_categorize2	= $this->util->value($this->input->post("name_categorize2"), "");
				$typename			= $this->util->value($this->input->post("typename"), "");
				$origin_en			= $this->util->value($this->input->post("origin_en"), 1);
				$active				= $this->util->value($this->input->post("active"), 1);
				$meta_title			= $this->util->value($this->input->post("meta_title"), "");
				$meta_key			= $this->util->value($this->input->post("meta_key"), "");
				$meta_des			= $this->util->value($this->input->post("meta_des"), "");
				$meta_title_en		= $this->util->value($this->input->post("meta_title_en"), "");
				$meta_key_en		= $this->util->value($this->input->post("meta_key_en"), "");
				$meta_des_en		= $this->util->value($this->input->post("meta_des_en"), "");
				if (empty($alias)) {
					$alias = $this->util->slug($title);
				}
				if (empty($alias_en)) {
					$alias_en = $this->util->slug($title_en);
				}
				if (empty($meta_title)){
					$meta_title = substr($title,0,69);
				}
				if (empty($meta_des)){
					$meta_des = substr(strip_tags($ship_des),0,159);
				}

				$photos = array();
				for ($i=0; $i<6; $i++) {
					if (!empty($this->input->post("hidden-userfile-{$i}"))) {
						$photos[] = $this->input->post("hidden-userfile-{$i}");
					}
				}



				$data = array (
					"title"				=> $title,
					"alias"				=> $alias,
					"title_en"			=> $title_en,
					"alias_en"			=> $alias_en,
					"brand"				=> $brand,
					"code"				=> $code,
					"code_product"		=> $code_product,
					"order_product"		=> $order_product,
					"origin"			=> $origin,
					"video"				=> $video,
					"affiliate"			=> $affiliate,
					"ship_des"			=> $ship_des,
					"category_id"		=> $category_main,
					"list_category"		=> $list_category,
					"content"			=> $content,
					"content_en"		=> $content_en,
					"active"			=> $active,
					"origin_en"			=> $origin_en,
					"typename"			=> $name_categorize1,
					"subtypename"		=> $name_categorize2,
					"meta_title"		=> $meta_title,
					"meta_key"			=> $meta_key,
					"meta_des"			=> $meta_des,
					"meta_title_en"		=> $meta_title_en,
					"meta_key_en"		=> $meta_key_en,
					"meta_des_en"		=> $meta_des_en
				);
				if (!empty($photos)){
					$photo = explode('/',$photos[0]);
				}

				$data['thumbnail'] = './files/upload/product/'.$code_product.'/thumb/'.end($photo);
				if ($action == "add") {
					$i = 1;
					foreach ($typename as $type) {

						$data_type = array(
							"product_id" 	=> $id,
							"typename" 		=> $type,
							"subtypename" 	=> json_encode($this->util->value($this->input->post("subtypename"),"")),
							"quantity" 		=> json_encode($this->util->value($this->input->post("qty{$i}"),"")),
							"price" 		=> json_encode($this->util->value($this->input->post("price{$i}"),"")),
							"sale" 			=> json_encode($this->util->value($this->input->post("sale{$i}"),"")),
							"cost" 			=> json_encode($this->util->value($this->input->post("cost{$i}"),"")),
							"photo" 		=> json_encode($this->util->value($this->input->post("photo{$i}"),"")),
						);
						$this->m_product_type->add($data_type);
						$i++;
					}
					$this->m_product->add($data);
					foreach ($photos as $photo) {
						
						$photo_data = array(
							"product_id"	=> $id,
							"file_path"		=> $photo
						);
						$this-> m_product_gallery->add($photo_data);
					}
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_product->update($data, $where);

					// Delete old selected photos
					foreach ($old_photos as $photo) {
						$this->m_product_gallery->delete(array("id"=>$photo->id));
					}
					foreach ($photos as $photo) {
						$photo_data = array(
							"product_id"	=> $id,
							"file_path"		=> $photo
						);
						$this->m_product_gallery->add($photo_data);
					}
					// type 
					$i = 1;
					foreach ($typename as $type) {

						$data_type = array(
							"product_id" 	=> $id,
							"typename" 		=> $type,
							"subtypename" 	=> json_encode($this->util->value($this->input->post("subtypename"),"")),
							"quantity" 		=> json_encode($this->util->value($this->input->post("qty{$i}"),"")),
							"price" 		=> json_encode($this->util->value($this->input->post("price{$i}"),"")),
							"sale" 			=> json_encode($this->util->value($this->input->post("sale{$i}"),"")),
							"cost" 			=> json_encode($this->util->value($this->input->post("cost{$i}"),"")),
							"photo" 		=> json_encode($this->util->value($this->input->post("photo{$i}"),"")),
						);
						$this->m_product_type->add($data_type);
						$i++;
					}
					//
					foreach ($product_types as $product_type) {
						$this->m_product_type->delete(array("id" => $product_type->id));
					}
				}
				$this->create_sitemap();
				$this->session->set_flashdata("success", "Add successfull");
				redirect(site_url("syslog/product/{$category_id}")."?page={$page}");
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/product/{$category_id}")."?page={$page}");
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_product->update($data, $where);
				}
				$this->create_sitemap();
				$this->session->set_flashdata("success", "Show successfull");
				redirect(site_url("syslog/product/{$category_id}")."?page={$page}");
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_product->update($data, $where);
				}
				$this->create_sitemap();
				$this->session->set_flashdata("success", "Hide successfull");
				redirect(site_url("syslog/product/{$category_id}")."?page={$page}");
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$this->m_product->delete(array("id" => $id));
					$info = new stdClass();
					$info->product_id = $id;
					$gallery_items = $this->m_product_gallery->items($info);
					foreach ($gallery_items as $gallery_item) {
						$this->m_product_gallery->delete(array("id" => $gallery_item->id));

						$file_delete = str_replace(BASE_URL, '.', $gallery_item->file_path);
						unlink($file_delete);
						$file_delete_thumb = str_replace(BASE_URL.'/files/upload/product/'.$id, './files/upload/product/'.$id.'/thumbnail', $gallery_item->file_path);
						unlink($file_delete_thumb);
					}
					rmdir('./files/upload/product/'.$id.'/thumbnail');
					rmdir('./files/upload/product/'.$id);
					//
					$product_types = $this->m_product_type->items($info);
					foreach ($product_types as $product_type) {
						$this->m_product_type->delete(array("id" => $product_type->id));
					}
				}
				$this->create_sitemap();
				$this->session->set_flashdata("success", "Delete successfull");
				redirect(site_url("syslog/product/{$category_id}")."?page={$page}");
			}
		}
		if ($action == "add") {
			$item = $this->m_product->instance();
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Thêm" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));

			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["item"] = $item;
			$view_data["category_item"] = $category;
			$view_data["categories"] = $this->m_product_category->items(null,null,null,null,'order_num','ASC');
			$view_data["brands"] = $brands;
			$view_data["product_types"] = $product_types;

			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/product/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_product->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Cập nhật" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}/{$id}")));

			$info = new stdClass();
			$info->product_id = $id;
			$old_photos = $this->m_product_gallery->items($info);

			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["item"] = $item;
			$view_data["brands"] = $brands;
			$view_data["category_item"] = $category;
			$view_data["categories"] = $this->m_product_category->items(null,null,null,null,'order_num','ASC');
			$view_data["product_id"] = $id;
			$view_data["product_types"] = $product_types;

			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/product/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {
			$info = new stdClass();
			$info->search_text = str_replace('NA','',$search_text);
			if (!empty($category_id)){
			$info->list_category	= array($category_id);
			}

			$total = count($this->m_product->get_list_category_admin('p.*',$info));
			$items = $this->m_product->get_list_category_admin('p.*',$info, null, $pagi, $offset);

			$list_product_id="";
			$c = count($items);
			for($i=0;$i<$c;$i++) {
				$list_product_id .= "{$items[$i]->id}";
				if($i < ($c-1))
				$list_product_id .= ',';
			}
			
			$info = new stdClass();
			$info->list_product_id = $list_product_id;
			$products_type = $this->m_product_type->items($info);
			$arr_type = array();
			
			foreach($products_type as $product_type) {
				$str = "";
				$subtypename 	= json_decode($product_type->subtypename);
				$quantity 		= json_decode($product_type->quantity);
				$sale 			= json_decode($product_type->sale);
				$price 			= json_decode($product_type->price);
				$count_quantity = count($quantity);
				
				if (!isset($arr_type[$product_type->product_id]))
					$str .= "<tr>";
				else
					$str = $arr_type[$product_type->product_id];

				if (!empty($product_type->typename))
					$str .= "<td rowspan='{$count_quantity}' style='vertical-align: middle;'>{$product_type->typename}</td>";
					
				if ($subtypename != '') {
					for ($i=0;$i<$count_quantity;$i++){
						$fee = number_format($price[$i],'0',',','.');
						$str .= "<td>{$subtypename[$i]}</td>";
						$str .= "<td>{$quantity[$i]}</td>";
						$str .= "<td>{$sale[$i]}%</td>";
						$str .= "<td>{$fee}<sup>₫</sup></td>";
						$str .= "</tr>";
					}
				} else {
					$fee = number_format($price[0],'0',',','.');
					$str .= "<td>{$quantity[0]}</td>";
					$str .= "<td>{$sale[0]}%</td>";
					$str .= "<td>{$fee}<sup>₫</sup></td>";
					$str .= "</tr>";
				}
				$arr_type[$product_type->product_id] = $str;
			}
			

			$pagination = $this->util->pagination_admin(site_url("syslog/product/{$category_id}"), $total, $pagi);

			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["offset"]		= $offset;
			$view_data["search_text"]	= $search_text;
			$view_data["pagination"]	= $pagination;
			$view_data["items"]			= $items;
			$view_data["pagi"]			= $pagi;
			$view_data["category"] 		= $category;
			$view_data["arr_type"] 		= $arr_type;

			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/product/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function affiliate ($action=null, $id=null)
	{
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Affiliate" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$url			= $this->util->value($this->input->post("url"), "");
				$title			= $this->util->value($this->input->post("title"), "");
				$keywords		= $this->util->value($this->input->post("keywords"), "");
				$description	= $this->util->value($this->input->post("description"), "");
				$active			= $this->util->value($this->input->post("active"), 1);
				
				$data = array (
					"url"			=> $url,
					"title"			=> $title,
					"keywords"		=> $keywords,
					"description"	=> $description,
					"active"		=> $active,
				);
	
				if ($action == "add") {
					$this->m_meta->add($data);
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_meta->update($data, $where);
				}
				redirect(site_url("syslog/affiliate"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/affiliate"));
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_meta->update($data, $where);
				}
				redirect(site_url("syslog/affiliate"));
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_meta->update($data, $where);
				}
				redirect(site_url("syslog/affiliate"));
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_meta->delete($where);
				}
				redirect(site_url("syslog/affiliate"));
			}
		}
		
		if ($action == "edit") {
			if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
				$page = 1;
			}
			else {
					$page = $_GET['page'];
			}
			$offset = ($page - 1) * ADMIN_ROW_PER_PAGE;

			$original_search_text	= !empty($_GET["search_text"]) ? $_GET["search_text"] : '';
			$fromdate				= !empty($_GET["fromdate"]) ? $_GET["fromdate"] : date("Y-m-d",strtotime("-7days"));
			$todate					= !empty($_GET["todate"]) ? $_GET["todate"] : date("Y-m-d");
			
			$search_text = strtoupper(trim($original_search_text));
			$search_text = str_replace(array(BOOKING_PREFIX), "", $search_text);
			
			if (!empty($search_text)) {
				$fromdate = "";
				$todate = "";
			}
			
			if (!empty($fromdate)) {
				$fromdate = date("Y-m-d", strtotime($fromdate));
			}
			if (!empty($todate)) {
				$todate = date("Y-m-d", strtotime($todate));
			}
			$info = new stdClass();
			$info->search_text		= $search_text;
			$info->fromdate			= $fromdate;
			$info->todate			= $todate;
			
			$affiliate_item = $this->m_affiliate_analytic->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$affiliate_item->affiliate_code}" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}/{$id}")));

			$total = count($this->m_affiliate->jion_booking_items($info));
			$items = $this->m_affiliate->jion_booking_items($info, ADMIN_ROW_PER_PAGE, $offset);
			foreach ($items as $item) {
				$info_detail = new stdClass();
				$info_detail->booking_id = $item->id;
				$item->detail = $this->m_booking_detail->items($info_detail);
			}

			$pagination = $this->util->pagination_admin(site_url('syslog/booking')."?task=search&boxchecked=0&search_text={$search_text}&fromdate={$fromdate}&todate={$todate}", $total, ADMIN_ROW_PER_PAGE);

			$view_data = array();
			$view_data["breadcrumb"] 			= $this->_breadcrumb;
			$view_data["items"] 				= $items;
			$view_data["pagination"] 			= $pagination;
			$view_data["search_text"]			= $original_search_text;
			$view_data["edited_search_text"]	= $search_text;
			$view_data["fromdate"]				= $fromdate;
			$view_data["todate"]				= $todate;
			
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["affiliate_item"] = $affiliate_item;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/affiliate/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {
			$page = (!empty($_GET["page"]) ? max($_GET["page"], 1) : 1);
			$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"), $this->m_affiliate_analytic->count_items(), ADMIN_ROW_PER_PAGE);
			
			$view_data = array();
			$view_data["breadcrumb"]	= $this->_breadcrumb;
			$view_data["items"]			= $this->m_affiliate_analytic->items(null, ADMIN_ROW_PER_PAGE, ($page - 1) * ADMIN_ROW_PER_PAGE);
			$view_data["page"]			= $page;
			$view_data["pagination"]	= $pagination;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/affiliate/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function ajax_get_product() {
		$id 	= $this->util->value($this->input->post("id"), "");
		$product = $this->m_product->load($id);
		$info = new stdClass();
		$info->product_id = $id;
		$product->type = $this->m_product_type->items($info);
		
		echo json_encode($product);
	}

	public function enter_box($category,$action=null, $id=null)
	{
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Kiểm kho" => site_url("{$this->util->slug($this->router->fetch_class())}/box")));
		$info_detail = new stdClass();
		$info_detail->box_id = !empty($id) ? $id : $this->m_box->get_next_value();

		$box_details = $this->m_box_detail->items($info_detail);

		$task = $this->util->value($this->input->post("task"), "");

		if (!empty($task)) {
			if ($task == "save") {
				$title				= $this->util->value($this->input->post("title"), "");
				$content			= $this->util->value($this->input->post("content"), "");
				$category_id		= $this->util->value($this->input->post("category_id"), "");
				$code_cate			= $this->util->value($this->input->post("code_cate"), "");
				$typename			= $this->util->value($this->input->post("typename"), "");
				$name_categorize1	= $this->util->value($this->input->post("name_categorize1"), "");
				$name_categorize2	= $this->util->value($this->input->post("name_categorize2"), "");
				$product_id			= $this->util->value($this->input->post("product_id"), "");
				$product_company	= $this->util->value($this->input->post("product_company"), "");
				$origin				= $this->util->value($this->input->post("origin"), "");
				$list_category		= $this->util->value($this->input->post("list_category"), "");

				if (empty($id)) {
					$id = $this->m_box->get_next_value();
				}
				$check_product_new = 0;
				if (empty($product_id)) {
					$product_id = $this->m_product->get_next_value();
					$check_product_new++;
				} else {
					$list_category = $this->m_product->load($product_id)->list_category;
				}
				$data = array (
					"title"				=> $title,
					"product_id"		=> $product_id,
					"category_id"		=> $category_id,
					"typename"			=> $name_categorize1,
					"subtypename"		=> $name_categorize2,
					'list_category'		=> $list_category
				);
				if ($check_product_new > 0){
					$data['product_company'] 	= $product_company;
					$data['content'] 			= $content;
					$data['origin'] 			= $origin;
				}
				
				if ($action == "add") {
					$i = 1;
					$info = new stdClass();
					$info->product_id = $product_id;
					$product_types = $this->m_product_type->items($info);
					foreach ($product_types as $product_type) {
						$this->m_product_type->delete(array("id" => $product_type->id));
					}
					foreach ($typename as $type) {
						$data_detail = array(
							"box_id" 		=> $id,
							"typename" 		=> $type,
							"subtypename" 	=> json_encode($this->util->value($this->input->post("subtypename"),"")),
							"quantity" 		=> json_encode($this->util->value($this->input->post("qty{$i}"),"")),
							"quantity_box"  => json_encode($this->util->value($this->input->post("qty{$i}"),"")),
							"price" 		=> json_encode($this->util->value($this->input->post("price{$i}"),"")),
							"sale" 			=> json_encode($this->util->value($this->input->post("sale{$i}"),"")),
							"cost" 			=> json_encode($this->util->value($this->input->post("cost{$i}"),"")),
							"total"			=> json_encode($this->util->value($this->input->post("total_qty{$i}"),"")),
						);
						$this->m_box_detail->add($data_detail);
						$data_detail = array(
							"product_id" 	=> $product_id,
							"typename" 		=> $type,
							"subtypename" 	=> json_encode($this->util->value($this->input->post("subtypename"),"")),
							"price" 		=> json_encode($this->util->value($this->input->post("price{$i}"),"")),
							"sale" 			=> json_encode($this->util->value($this->input->post("sale{$i}"),"")),
							"cost" 			=> json_encode($this->util->value($this->input->post("cost{$i}"),"")),
							"quantity"		=> json_encode($this->util->value($this->input->post("total_qty{$i}"),"")),
						);
						$this->m_product_type->add($data_detail);
						$i++;
					}
					$this->m_box->add($data);
					if (!empty($check_product_new)) {
						$code_cate .= 1000+$product_id.rand(1,9).rand(1,9);
						$data_product = array (
							"code_product"		=> $code_cate,
							"title"				=> $title,
							"alias"				=> $this->util->slug($title),
							"code"				=> $product_id,
							"product_company"	=> $product_company,
							"category_id"		=> $category_id,
							"content"			=> $content,
							"typename"			=> $name_categorize1,
							"subtypename"		=> $name_categorize2,
							"origin"			=> $origin,
							"list_category"		=> $list_category,
						);
						$this->m_product->add($data_product);
					}
					$this->session->set_flashdata("success", "Add successfull");
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_box->update($data, $where);

					// type
					$i = 1;
					foreach ($typename as $type) {

						$data_detail = array(
							"box_id" 		=> $id,
							"typename" 		=> $type,
							"subtypename" 	=> json_encode($this->util->value($this->input->post("subtypename"),"")),
							"quantity" 		=> json_encode($this->util->value($this->input->post("qty{$i}"),"")),
							"quantity_box"  => json_encode($this->util->value($this->input->post("qty{$i}"),"")),
							"price" 		=> json_encode($this->util->value($this->input->post("price{$i}"),"")),
							"sale" 			=> json_encode($this->util->value($this->input->post("sale{$i}"),"")),
							"cost" 			=> json_encode($this->util->value($this->input->post("cost{$i}"),"")),
						);
						$this->m_box_detail->add($data_detail);
						$i++;
					}
					//
					foreach ($box_details as $box_detail) {
						$this->m_box_detail->delete(array("id" => $box_detail->id));
					}

					$this->session->set_flashdata("success", "Update successfull");
				}
				redirect(site_url("syslog/enter-box/{$category}"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/enter-box/{$category}"));
			}
		}

		if ($action == "add") {
			$item = $this->m_box->instance();
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Nhập kho" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			
			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["box_details"] = $box_details;
			$view_data["action"] = $action;
			$view_data["product_categories"] = $this->m_product_category->items(null,1);
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/box/enter_box/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_box->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Sửa kho" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));

			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["item"] 			= $item;
			$view_data["box_details"] = $box_details;
			$view_data["action"] = $action;
			$view_data["product_categories"] = $this->m_product_category->items(null,1);

			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/box/enter_box/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "detail") {
			$fromdate				= !empty($_GET["fromdate"]) ? date("Y-m-d", strtotime($_GET["fromdate"])) : null;
			$todate					= !empty($_GET["todate"]) ? date("Y-m-d", strtotime($_GET["todate"])) : null;
			$type					= !empty($_GET["type"]) ? $_GET["type"] : '';
			$info = new stdClass();
			$info->product_id = $id;
			$items = $this->m_box->items($info);
			$list_box_id = '';
			foreach ($items as $item){
				if (!empty($list_box_id)){
					$list_box_id .= ',';
				}
				$list_box_id .= $item->id;
			}
			$info_detail = new stdClass();
			$info_detail->list_box_id = $list_box_id;
			$box_items = $this->m_box_detail->join_box($info_detail);

			$arr_box = array();
			$arr_qty = array();
			$arr_cost = array();
			$total_qty = 0;
			$total_cost = 0;
			foreach($box_items as $box_item) {
				$subtypename 	= json_decode($box_item->subtypename);
				$quantity 		= json_decode($box_item->quantity);
				$quantity_box 	= json_decode($box_item->quantity_box);
				$price 			= json_decode($box_item->price);
				$cost 			= json_decode($box_item->cost);
				$count_quantity = count($quantity_box);
				//
				if ($type == 'year') {
					if (!isset($arr_qty[date('Y-m',strtotime($box_item->created_date))])){
						$qty = 0;
						$cost_temp = 0;
					} else {
						$qty = $arr_qty[date('Y-m',strtotime($box_item->created_date))];
						$cost_temp = $arr_cost[date('Y-m',strtotime($box_item->created_date))];
					}
				} else {
					if (!isset($arr_qty[date('Y-m-d',strtotime($box_item->created_date))])){
						$qty = 0;
						$cost_temp = 0;
					} else {
						$qty = $arr_qty[date('Y-m-d',strtotime($box_item->created_date))];
						$cost_temp = $arr_cost[date('Y-m-d',strtotime($box_item->created_date))];
					}
				}
				
				//
				if (!isset($arr_box[$box_item->box_id]))
					$str = "<tr>";
				else
					$str = $arr_box[$box_item->box_id];

				if (!empty($box_item->typename))
					$str .= "<td rowspan='{$count_quantity}' style='vertical-align: middle;'>{$box_item->typename}</td>";

				if ($subtypename != '') {
					for ($i=0;$i<$count_quantity;$i++){
						$price_format = number_format((float)$price[$i],0,',','.');
						$cost_format = number_format((float)$cost[$i],0,',','.');
						$buy = $quantity_box[$i] - $quantity[$i];
						$str .= "<td>{$subtypename[$i]}</td>";
						$str .= "<td>{$buy}</td>";
						$str .= "<td>{$quantity[$i]}</td>";
						$str .= "<td>{$quantity_box[$i]}</td>";
						$str .= "<td>{$price_format}</td>";
						$str .= "<td>{$cost_format}</td>";
					$str .= "</tr>";
						//
						$qty += $quantity[$i];
						//
						$cost_temp += (float)$cost[$i];
						$total_qty += (float)$quantity_box[$i];
						$total_cost += (float)$cost[$i]*$quantity_box[$i];
					}
				} else {
					$price_format = number_format((float)$price[0],0,',','.');
					$cost_format = number_format((float)$cost[0],0,',','.');
					$buy = $quantity_box[0] - $quantity[0];
					$str .= "<td>{$buy}</td>";
					$str .= "<td>{$quantity[0]}</td>";
					$str .= "<td>{$quantity_box[0]}</td>";
					$str .= "<td>{$price_format}</td>";
					$str .= "<td>{$cost_format}</td>";
					$str .= "</tr>";
					$qty += $quantity[0];
					$cost_temp += (float)$cost[0];
					$total_qty += (float)$quantity_box[0];
					$total_cost += (float)$cost[0]*$quantity_box[0];
				}
				$arr_box[$box_item->box_id] = $str;
				if ($type == 'year') {
					$arr_qty[date('Y-m',strtotime($box_item->created_date))] = $qty;
					$arr_cost[date('Y-m',strtotime($box_item->created_date))] = $cost_temp;
				} else {
					$arr_qty[date('Y-m-d',strtotime($box_item->created_date))] = $qty;
					$arr_cost[date('Y-m-d',strtotime($box_item->created_date))] = $cost_temp;
				}
			}


			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["fromdate"]		= $fromdate;
			$view_data["todate"]		= $todate;
			$view_data["items"] 		= $items;
			$view_data["box_items"] 	= $box_items;
			$view_data["total_qty"] 	= $total_qty;
			$view_data["total_cost"] 	= $total_cost;
			$view_data["category"] 		= $category;
			$view_data["arr_box"] 		= $arr_box;
			$view_data["arr_qty"] 		= $arr_qty;
			$view_data["type"] 			= $type;
			$view_data["arr_cost"] 		= $arr_cost;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/box/enter_box/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}  else if ($action == "delete") {
			$item = $this->m_box->load($id);
			$info = new stdClass();
			$info->product_id = $item->product_id;
			$product_types = $this->m_product_type->items($info);
			$info = new stdClass();
			$info->box_id = $id;
			$box_details = $this->m_box_detail->items($info);

			foreach($product_types as $product_type) {
				foreach($box_details as $box_detail) {
					if ($product_type->typename == $box_detail->typename){
						$type_product = json_decode($product_type->quantity);
						$box_quantity = json_decode($box_detail->quantity);
						$c = count($type_product);
						$arr_qty = array();
						for($i=0;$i<$c;$i++) {
							$qty_temp = $type_product[$i] - $box_quantity[$i];
							array_push($arr_qty,$qty_temp);
						}
						$this->m_product_type->update(array("quantity"=>json_encode($arr_qty)),array('id'=>$product_type->id));
						$this->m_box_detail->delete(array('id'=>$box_detail->id));
					}
				}
			}
			$this->m_box->delete(array('id'=>$id));
			redirect(site_url("syslog/enter-box/{$category}"));
		} else {
			$fromdate				= !empty($_GET["fromdate"]) ? date("Y-m-d", strtotime($_GET["fromdate"])) : null;
			$todate					= !empty($_GET["todate"]) ? date("Y-m-d", strtotime($_GET["todate"])) : null;
			$type					= !empty($_GET["type"]) ? $_GET["type"] : '';
			$info = new stdClass();
			$info->fromdate = $fromdate;
			$info->todate = $todate;
			$info->category_id = $category;
			$search_box = '';
			if(!empty($_GET['search_box'])) {
				$info->search_box = $_GET['search_box'];
				$search_box = $_GET['search_box'];
			}
			$items = $this->m_box->items($info);
		
			$box_items = $this->m_box_detail->join_box($info);

			$arr_box = array();
			$arr_qty = array();
			$arr_cost = array();
			$total_qty = 0;
			$total_cost = 0;
			foreach($box_items as $box_item) {
				$subtypename 	= json_decode($box_item->subtypename);
				$quantity 		= json_decode($box_item->quantity);
				$quantity_box 	= json_decode($box_item->quantity_box);
				$price 			= json_decode($box_item->price);
				$cost 			= json_decode($box_item->cost);
				$count_quantity = count($quantity_box);
				//
				if ($type == 'year') {
					if (!isset($arr_qty[date('Y-m',strtotime($box_item->created_date))])){
						$qty = 0;
						$cost_temp = 0;
					} else {
						$qty = $arr_qty[date('Y-m',strtotime($box_item->created_date))];
						$cost_temp = $arr_cost[date('Y-m',strtotime($box_item->created_date))];
					}
				} else {
					if (!isset($arr_qty[date('Y-m-d',strtotime($box_item->created_date))])){
						$qty = 0;
						$cost_temp = 0;
					} else {
						$qty = $arr_qty[date('Y-m-d',strtotime($box_item->created_date))];
						$cost_temp = $arr_cost[date('Y-m-d',strtotime($box_item->created_date))];
					}
				}
				
				//
				if (!isset($arr_box[$box_item->box_id]))
					$str = "<tr>";
				else
					$str = $arr_box[$box_item->box_id];

				if (!empty($box_item->typename))
					$str .= "<td rowspan='{$count_quantity}' style='vertical-align: middle;'>{$box_item->typename}</td>";

				if ($subtypename != '') {
					for ($i=0;$i<$count_quantity;$i++){
						$price_format = number_format((float)$price[$i],0,',','.');
						$cost_format = number_format((float)$cost[$i],0,',','.');
						$buy = $quantity_box[$i] - $quantity[$i];
						$str .= "<td>{$subtypename[$i]}</td>";
						$str .= "<td>{$buy}</td>";
						$str .= "<td>{$quantity[$i]}</td>";
						$str .= "<td>{$quantity_box[$i]}</td>";
						$str .= "<td>{$price_format}</td>";
						$str .= "<td>{$cost_format}</td>";
					$str .= "</tr>";
						//
						$qty += $quantity[$i];
						//
						$cost_temp += (float)$cost[$i];
						$total_qty += (float)$quantity_box[$i];
						$total_cost += (float)$cost[$i]*$quantity_box[$i];
					}
				} else {
					$price_format = number_format((float)$price[0],0,',','.');
					$cost_format = number_format((float)$cost[0],0,',','.');
					$buy = $quantity_box[0] - $quantity[0];
					$str .= "<td>{$buy}</td>";
					$str .= "<td>{$quantity[0]}</td>";
					$str .= "<td>{$quantity_box[0]}</td>";
					$str .= "<td>{$price_format}</td>";
					$str .= "<td>{$cost_format}</td>";
					$str .= "</tr>";
					$qty += $quantity[0];
					$cost_temp += (float)$cost[0];
					$total_qty += (float)$quantity_box[0];
					$total_cost += (float)$cost[0]*$quantity_box[0];
				}
				$arr_box[$box_item->box_id] = $str;
				if ($type == 'year') {
					$arr_qty[date('Y-m',strtotime($box_item->created_date))] = $qty;
					$arr_cost[date('Y-m',strtotime($box_item->created_date))] = $cost_temp;
				} else {
					$arr_qty[date('Y-m-d',strtotime($box_item->created_date))] = $qty;
					$arr_cost[date('Y-m-d',strtotime($box_item->created_date))] = $cost_temp;
				}
			}


			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["fromdate"]		= $fromdate;
			$view_data["todate"]		= $todate;
			$view_data["items"] 		= $items;
			$view_data["box_items"] 	= $box_items;
			$view_data["total_qty"] 	= $total_qty;
			$view_data["total_cost"] 	= $total_cost;
			$view_data["category"] 		= $category;
			$view_data["arr_box"] 		= $arr_box;
			$view_data["arr_qty"] 		= $arr_qty;
			$view_data["type"] 			= $type;
			$view_data["search_box"] 	= $search_box;
			$view_data["arr_cost"] 		= $arr_cost;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/box/enter_box/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function box($category=null,$action=null, $id=null)
	{
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
				$page = 1;
		}
		else {
				$page = $_GET['page'];
		}
		$offset = ($page - 1) * ADMIN_ROW_PER_PAGE;

		$fromdate				= !empty($_GET["fromdate"]) ? date("Y-m-d", strtotime($_GET["fromdate"])) : null;
		$todate					= !empty($_GET["todate"]) ? date("Y-m-d", strtotime($_GET["todate"])) : null;
		$type					= !empty($_GET["type"]) ? $_GET["type"] : '';
		if (!empty($type)) {
			if ($type != 'year') {
				$result = $this->util->get_day_statistic($type);
				$fromdate 	= $result[0];
				$todate 	= $result[1];
			} else {
				$fromdate 	= date('Y').'-01-01';
				$todate 	= date('Y').'-12-31';
			}
		}
		
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Kiểm kho" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		$info_detail = new stdClass();
		$info_detail->box_id = !empty($id) ? $id : $this->m_box->get_next_value();
		$box_details = $this->m_box_detail->items($info_detail);

		$task = $this->util->value($this->input->post("task"), "");

		if (!empty($task)) {
			if ($task == "save") {
				$title				= $this->util->value($this->input->post("title"), "");
				$content			= $this->util->value($this->input->post("content"), "");
				$category_id		= $this->util->value($this->input->post("category_id"), "");
				$typename			= $this->util->value($this->input->post("typename"), "");
				$name_categorize1	= $this->util->value($this->input->post("name_categorize1"), "");
				$name_categorize2	= $this->util->value($this->input->post("name_categorize2"), "");
				$product_id			= $this->util->value($this->input->post("code"), "");
				$product_company	= $this->util->value($this->input->post("product_company"), "");
				$origin				= $this->util->value($this->input->post("origin"), "");
				$list_category		= $this->util->value($this->input->post("list_category"), "");

				if (empty($id)) {
					$id = $this->m_box->get_next_value();
				}
				$check_product_new = 0;
				if (empty($product_id)) {
					$product_id = $this->m_product->get_next_value();
					$check_product_new++;
				}
				$data = array (
					"title"				=> $title,
					"product_id"		=> $product_id,
					"category_id"		=> $category_id,
					"typename"			=> $name_categorize1,
					"subtypename"		=> $name_categorize2,
				);
				if ($check_product_new > 0){
					$data['product_company'] 	= $product_company;
					$data['content'] 			= $content;
					$data['origin'] 			= $origin;
					$data['list_category'] 		= $list_category;
				}
				
				if ($action == "add") {
					$i = 1;
					$info = new stdClass();
					$info->product_id = $product_id;
					$product_types = $this->m_product_type->items($info);
					foreach ($product_types as $product_type) {
						$this->m_product_type->delete(array("id" => $product_type->id));
					}
					foreach ($typename as $type) {
						$data_detail = array(
							"box_id" 		=> $id,
							"typename" 		=> $type,
							"subtypename" 	=> json_encode($this->util->value($this->input->post("subtypename"),"")),
							"quantity" 		=> json_encode($this->util->value($this->input->post("qty{$i}"),"")),
							"quantity_box"  => json_encode($this->util->value($this->input->post("qty{$i}"),"")),
							"price" 		=> json_encode($this->util->value($this->input->post("price{$i}"),"")),
							"sale" 			=> json_encode($this->util->value($this->input->post("sale{$i}"),"")),
							"cost" 			=> json_encode($this->util->value($this->input->post("cost{$i}"),"")),
							"total"			=> json_encode($this->util->value($this->input->post("total_qty{$i}"),"")),
						);
						$this->m_box_detail->add($data_detail);
						$data_detail = array(
							"product_id" 	=> $product_id,
							"typename" 		=> $type,
							"subtypename" 	=> json_encode($this->util->value($this->input->post("subtypename"),"")),
							"price" 		=> json_encode($this->util->value($this->input->post("price{$i}"),"")),
							"sale" 			=> json_encode($this->util->value($this->input->post("sale{$i}"),"")),
							"cost" 			=> json_encode($this->util->value($this->input->post("cost{$i}"),"")),
							"quantity"		=> json_encode($this->util->value($this->input->post("total_qty{$i}"),"")),
						);
						$this->m_product_type->add($data_detail);
						$i++;
					}
					$this->m_box->add($data);
					if (!empty($check_product_new)) {
						$data_product = array (
							"title"				=> $title,
							"code"				=> $product_id,
							"product_company"	=> $product_company,
							"category_id"		=> $category_id,
							"content"			=> $content,
							"typename"			=> $name_categorize1,
							"subtypename"		=> $name_categorize2,
							"origin"			=> $origin,
							"list_category"		=> $list_category,
						);
						$this->m_product->add($data_product);
					}
					$this->session->set_flashdata("success", "Add successfull");
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_box->update($data, $where);

					// type
					$i = 1;
					foreach ($typename as $type) {

						$data_detail = array(
							"box_id" 		=> $id,
							"typename" 		=> $type,
							"subtypename" 	=> json_encode($this->util->value($this->input->post("subtypename"),"")),
							"quantity" 		=> json_encode($this->util->value($this->input->post("qty{$i}"),"")),
							"quantity_box"  => json_encode($this->util->value($this->input->post("qty{$i}"),"")),
							"price" 		=> json_encode($this->util->value($this->input->post("price{$i}"),"")),
							"sale" 			=> json_encode($this->util->value($this->input->post("sale{$i}"),"")),
							"cost" 			=> json_encode($this->util->value($this->input->post("cost{$i}"),"")),
						);
						$this->m_box_detail->add($data_detail);
						$i++;
					}
					//
					foreach ($box_details as $box_detail) {
						$this->m_box_detail->delete(array("id" => $box_detail->id));
					}

					$this->session->set_flashdata("success", "Update successfull");
				}
				redirect(site_url("syslog/box"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/box"));
			}
		}

		if ($action == "add") {
			$item = $this->m_box->instance();
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Add Item" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			
			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["box_details"] = $box_details;
			$view_data["action"] = $action;
			$view_data["product_categories"] = $this->m_product_category->items(null,1);
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/box/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_box->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Update Item" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));

			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["item"] 			= $item;
			$view_data["box_details"] = $box_details;
			$view_data["action"] = $action;
			$view_data["product_categories"] = $this->m_product_category->items(null,1);

			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/box/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "delete") {
			$where = array("id" => $id);
			$this->m_box->delete($where);
			$info_detail = new stdClass();
			$info_detail->box_id = $id;
			$box_details = $this->m_box_detail->items($info_detail);
			foreach ($box_details as $box_detail) {
				$this->m_box_detail->delete(array("id" => $box_detail->id));
			}
			$this->session->set_flashdata("success", "Delete successfull");
			redirect(site_url("syslog/box"));
		}
		else {
			$arr_cate = array();
			if (!empty($category)) array_push($arr_cate,$category);
			$info 				= new stdClass();
			$info->fromdate 	= $fromdate;
			$info->todate 		= $todate;
			$info->list_category= $arr_cate;
			$boxs			= $this->m_box->items($info);
			$inv_boxs			= count($this->m_box->count_item_int($info));
			$box_details 		= $this->m_box_detail->join_box($info);
			$arr_type 	= array();
			$qty 		= 0;
			$capital 	= 0;
			$qty_box 	= 0;
			$capital_box= 0;
			$total_price= 0;
			foreach($box_details as $box_detail) {

				$category_id 	= json_decode($box_detail->category_id);
				$subtypename 	= json_decode($box_detail->subtypename);
				$quantity_box 	= json_decode($box_detail->quantity_box);
				$quantity 		= json_decode($box_detail->quantity);
				$price 			= json_decode($box_detail->price);
				$cost 			= json_decode($box_detail->cost);
				$count_category = count($category_id);
				$count_quantity = count($quantity_box);
				
				
				$capital_box_temp 	= 0;
				$total_price_temp 	= 0;
				$capital_temp 		= 0;
				$qty_temp 			= 0;
				$qty_box_temp 		= 0;
				if ($subtypename != '') {
					for ($i=0;$i<$count_quantity;$i++){
						// if ((int)$cost[$i] != 0 && (int)$price[$i] != 0) {
							$capital_box+= (int)$quantity_box[$i]*(int)$cost[$i];
							$total_price+= (int)$quantity_box[$i]*(int)$price[$i];
							$capital	+= (int)$quantity[$i]*(int)$cost[$i];
							//
							$capital_box_temp+= (int)$quantity_box[$i]*(int)$cost[$i];
							$total_price_temp+= (int)$quantity_box[$i]*(int)$price[$i];
							$capital_temp	+= (int)$quantity[$i]*(int)$cost[$i];
						// }
						$qty 			+= $quantity[$i];
						$qty_temp 		+= $quantity[$i];
						$qty_box 		+= $quantity_box[$i];
						$qty_box_temp 	+= $quantity_box[$i];
						//
						
					}
				} else {
					// if ((int)$cost[0] != 0 && (int)$price[0] != 0) {
						$capital_box+= (int)$quantity_box[0]*(int)$cost[0];
						$total_price+= (int)$quantity_box[0]*(int)$price[0];
						$capital	+= (int)$quantity[0]*(int)$cost[0];
						//
						$capital_box_temp+= (int)$quantity_box[0]*(int)$cost[0];
						$total_price_temp+= (int)$quantity_box[0]*(int)$price[0];
						$capital_temp	+= (int)$quantity[0]*(int)$cost[0];
					// }
					$qty 			+= $quantity[0];
					$qty_temp 		+= $quantity[0];
					$qty_box 		+= $quantity_box[0];
					$qty_box_temp 	+= $quantity_box[0];
				}
				for ($i=0;$i<$count_category;$i++){
					if (isset($arr_type[$category_id[$i]])){
						$arr_type[$category_id[$i]] = array(
							'tien_von' =>  $arr_type[$category_id[$i]]['tien_von'] + $capital_box_temp,
							'tien_ban' =>  $arr_type[$category_id[$i]]['tien_ban'] + $total_price_temp,
							'von_ton' => $arr_type[$category_id[$i]]['von_ton'] + $capital_temp,
							'tong_sl' => $arr_type[$category_id[$i]]['tong_sl'] + $qty_box_temp,
							'sl_ton' => $arr_type[$category_id[$i]]['sl_ton'] + $qty_temp,
						);
					} else{
						$arr_type[$category_id[$i]] = array(
							'tien_von' => $capital_box_temp,
							'tien_ban' => $total_price_temp,
							'von_ton' => $capital_temp,
							'tong_sl' => $qty_box_temp,
							'sl_ton' => $qty_temp,
						);
					}
				}
			}

			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["fromdate"]		= $fromdate;
			$view_data["todate"]		= $todate;
			$view_data["inv_boxs"]		= $inv_boxs;
			$view_data["total_boxs"]	= count($boxs);
			$view_data["items"] 		= $boxs;
			$view_data["box_items"] 	= $box_details;
			$view_data["arr_type"] 		= $arr_type;
			$view_data["qty"] 			= $qty;
			$view_data["qty_box"] 		= $qty_box;
			$view_data["type"] 			= $type;
			$view_data["total_price"] 	= $total_price;
			$view_data["capital"] 		= $capital;
			$view_data["capital_box"] 	= $capital_box;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/box/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	
	//------------------------------------------------------------------------------
	// settings
	//------------------------------------------------------------------------------
	public function settings($action=null)
	{
		$settings = $this->m_setting->items();
		
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$logo 		= !empty($_FILES['logo']['name']) ? explode('.',$_FILES['logo']['name']) : '';
				$company_name		= $this->util->value($this->input->post("company_name"), "");
				$company_logan		= $this->util->value($this->input->post("company_logan"), "");
				$company_address	= $this->util->value($this->input->post("company_address"), "");
				$company_email		= $this->util->value($this->input->post("company_email"), "");
				$company_hotline	= $this->util->value($this->input->post("company_hotline"), "");
				$company_tollfree	= $this->util->value($this->input->post("company_tollfree"), "");
				$facebook_url		= $this->util->value($this->input->post("facebook_url"), "");
				$googleplus_url		= $this->util->value($this->input->post("googleplus_url"), "");
				$twitter_url		= $this->util->value($this->input->post("twitter_url"), "");
				$youtube_url		= $this->util->value($this->input->post("youtube_url"), "");
				$tags				= $this->util->value($this->input->post("tags"), "");
				$usd				= $this->util->value($this->input->post("usd"), "");
				
				$data = array (
					"company_name"		=> $company_name,
					"company_logan"		=> $company_logan,
					"company_address"	=> $company_address,
					"company_email"		=> $company_email,
					"company_hotline"	=> $company_hotline,
					"company_tollfree"	=> $company_tollfree,
					"facebook_url"		=> $facebook_url,
					"googleplus_url"	=> $googleplus_url,
					"twitter_url"		=> $twitter_url,
					"youtube_url"		=> $youtube_url,
					"tags"				=> $tags,
					"usd"				=> $usd,
				);
				// if (!empty($_FILES['logo']['name'])){
				// 	$data['logo'] = "/images/logo/{$this->util->slug($logo[0])}.{$logo[1]}";
				// }
				// $file_deleted = '';
				// $path = "./images/logo";
				// if (!file_exists($path)) {
				// 	mkdir($path, 0755, true);
				// }
				// $allow_type = 'JPG|PNG|jpg|jpeg|png';
				// $this->util->upload_file($path,'logo',$file_deleted,$allow_type,$this->util->slug($logo[0]).'.'.$logo[1]);

				if (!is_null($settings) && sizeof($settings)) {
					$setting = $settings[0];
					$where = array("id" => $setting->id);
					$this->m_setting->update($data, $where);
				} else {
					$this->m_setting->add($data);
				}
			}
			
			redirect(site_url("syslog/settings"));
		}
		
		$action = !is_null($action) ? $action : "index";
		
		if (!is_null($settings) && sizeof($settings)) {
			$setting = $settings[0];
		} else {
			$setting = $this->m_setting->instance();
		}
		
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Settings" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		
		$view_data = array();
		$view_data["setting"] = $setting;
		$view_data["breadcrumb"] = $this->_breadcrumb;
		
		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("admin/settings/{$action}", $view_data, true);
		$this->load->view("layout/admin/main", $tmpl_content);
	}

	public function page_meta_tags($action=null, $id=null)
	{
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Page Meta Tags" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$url			= $this->util->value($this->input->post("url"), "");
				$title			= $this->util->value($this->input->post("title"), "");
				$keywords		= $this->util->value($this->input->post("keywords"), "");
				$description	= $this->util->value($this->input->post("description"), "");
				$active			= $this->util->value($this->input->post("active"), 1);
				
				$data = array (
					"url"			=> $url,
					"title"			=> $title,
					"keywords"		=> $keywords,
					"description"	=> $description,
					"active"		=> $active,
				);
	
				if ($action == "add") {
					$this->m_meta->add($data);
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_meta->update($data, $where);
				}
				redirect(site_url("syslog/page-meta-tags"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/page-meta-tags"));
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_meta->update($data, $where);
				}
				redirect(site_url("syslog/page-meta-tags"));
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_meta->update($data, $where);
				}
				redirect(site_url("syslog/page-meta-tags"));
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_meta->delete($where);
				}
				redirect(site_url("syslog/page-meta-tags"));
			}
		}
		
		if ($action == "add") {
			$item = $this->m_meta->instance();
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Add Meta Tags" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			
			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["item"] = $item;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/meta/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_meta->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$item->title}" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}/{$id}")));
			
			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["item"] = $item;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/meta/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {
			$page = (!empty($_GET["page"]) ? max($_GET["page"], 1) : 1);
			$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"), $this->m_meta->count(), ADMIN_ROW_PER_PAGE);
			
			$view_data = array();
			$view_data["breadcrumb"]	= $this->_breadcrumb;
			$view_data["items"]			= $this->m_meta->items(null, null, ADMIN_ROW_PER_PAGE, ($page - 1) * ADMIN_ROW_PER_PAGE);
			$view_data["page"]			= $page;
			$view_data["pagination"]	= $pagination;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/meta/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	
	//------------------------------------------------------------------------------
	// Redirects
	//------------------------------------------------------------------------------
	
	public function page_redirects($action=null, $id=null)
	{
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Page Redirects" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$from_url	= $this->util->value($this->input->post("from_url"), "");
				$to_url		= $this->util->value($this->input->post("to_url"), "");
				$active		= $this->util->value($this->input->post("active"), 1);
				
				$data = array (
					"from_url"	=> $from_url,
					"to_url"	=> $to_url,
					"active"	=> $active,
				);
	
				if ($action == "add") {
					$this->m_redirect->add($data);
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_redirect->update($data, $where);
				}
				redirect(site_url("syslog/page-redirects"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/page-redirects"));
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_redirect->update($data, $where);
				}
				redirect(site_url("syslog/page-redirects"));
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_redirect->update($data, $where);
				}
				redirect(site_url("syslog/page-redirects"));
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_redirect->delete($where);
				}
				redirect(site_url("syslog/page-redirects"));
			}
		}
		
		if ($action == "add") {
			$item = $this->m_redirect->instance();
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Add Page Redirect" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			
			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["item"] = $item;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/redirect/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_redirect->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$item->from_url}" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}/{$id}")));
			
			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["item"] = $item;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/redirect/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {
			$page = (!empty($_GET["page"]) ? max($_GET["page"], 1) : 1);
			$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"), $this->m_redirect->count(), ADMIN_ROW_PER_PAGE);
			
			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["items"]			= $this->m_redirect->items(null, null, ADMIN_ROW_PER_PAGE, ($page - 1) * ADMIN_ROW_PER_PAGE);
			$view_data["page"]			= $page;
			$view_data["pagination"]	= $pagination;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/redirect/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function post ($id=null) {
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Giới thiệu" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->router->fetch_method()}")));
		$item = $this->m_post->load($id);
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$title			= $this->util->value($this->input->post("title"), "");
				$title_en			= $this->util->value($this->input->post("title_en"), "");
				$content		= $this->util->value($this->input->post("content"), "");
				$content_en		= $this->util->value($this->input->post("content_en"), "");
				$active			= $this->util->value($this->input->post("active"), 1);
				$meta_title			= $this->util->value($this->input->post("meta_title"), "");
				$meta_key			= $this->util->value($this->input->post("meta_key"), "");
				$meta_des			= $this->util->value($this->input->post("meta_des"), "");

				if (empty($meta_title)){
					$meta_title = substr($title,0,69);
				}
				$data = array (
					"title"			=> $title,
					"title_en"		=> $title_en,
					"content"		=> $content,
					"content_en"	=> $content_en,
					"active"		=> $active,
					"meta_title"	=> $meta_title,
					"meta_key"		=> $meta_key,
					"meta_des"		=> $meta_des
				);
				$where = array("id" => $item->id);
				$this->m_post->update($data, $where);

				$this->session->set_flashdata("success", "Update successfull");
				redirect(site_url("syslog/post/{$id}"));
			}
		} else {
			$view_data = array();
			$view_data["breadcrumb"]	= $this->_breadcrumb;
			$view_data["item"]			= $item;
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/post/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	//------------------------------------------------------------------------------
	// content
	//------------------------------------------------------------------------------
	public function content_category ($category_id,$action=null, $id=null){
		$config_row_page = ADMIN_ROW_PER_PAGE;
		$pagi		= (isset($_GET["pagi"]) ? $_GET["pagi"] : $config_row_page);
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
			$page = 1;
		}
		else {
			$page = $_GET['page'];
		}
		$offset = ($page - 1) * $pagi;
		$title = 'Tin tức';
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$title}/ Danh mục" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$category_id}")));
		$category = $this->m_content_categories->load($category_id);
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$name			= $this->util->value($this->input->post("name"), "");
				$name_en		= $this->util->value($this->input->post("name_en"), "");
				$alias			= $this->util->value($this->input->post("alias"), "");
				$alias_en		= $this->util->value($this->input->post("alias_en"), "");
				$active			= $this->util->value($this->input->post("active"), 1);
				
				if (empty($alias)) {
					$alias = $this->util->slug($name);
				}
				if (empty($alias_en)) {
					$alias_en = $this->util->slug($name_en);
				}
				
				$data = array (
					"name"		=> $name,
					"name_en"	=> $name_en,
					"alias"		=> $alias,
					"alias_en"	=> $alias_en,
					"parent_id"	=> $category->id,
					"active"	=> $active
				);
				
				if ($action == "add") {
					$this->m_content_categories->add($data);
					$this->session->set_flashdata("success", "Thêm thành công");
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_content_categories->update($data, $where);
					$this->session->set_flashdata("success", "Cập nhật thành công");
				}
				redirect(site_url("syslog/content-category/{$category_id}"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/content-category/{$category_id}"));
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_content_categories->update($data, $where);
				}
				redirect(site_url("syslog/content-category/{$category_id}"));
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_content_categories->update($data, $where);
				}
				redirect(site_url("syslog/content-category/{$category_id}"));
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_content_categories->delete($where);
				}
				$this->session->set_flashdata("success", "Xóa thành công");
				redirect(site_url("syslog/content-category/{$category_id}"));
			}
		}
		
		if ($action == "add") {
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Thêm" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["title"]			= $title;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/content/category/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_content_categories->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Cập nhật" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}/{$id}")));
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["item"] 			= $item;
			$view_data["title"]			= $title;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/content/category/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {
			$total = count($this->m_content_categories->items());
			if (!isset($_GET['pagi'])){
				$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"). "?pagi=$config_row_page"."$_SERVER[QUERY_STRING]", $total, $pagi);
			}else{
				$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"). "?$_SERVER[QUERY_STRING]", $total, $pagi);
			}
			$info = new stdClass();
			$info->parent_id = 5;
			$items = $this->m_content_categories->items($info,null,$pagi,$offset);
			
			$view_data = array();
			$view_data["breadcrumb"]	= $this->_breadcrumb;
			$view_data["offset"]		= $offset;
			$view_data["pagination"]	= $pagination;
			$view_data["totalitems"]	= sizeof($this->m_content_categories->items());
			$view_data["items"]			= $items;
			$view_data["title"]			= $title;
			$view_data["category_id"]	= $category_id;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/content/category/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function content ($category_id, $action=null, $id=null) {
		$config_row_page = ADMIN_ROW_PER_PAGE;
		$pagi		= (isset($_GET["pagi"]) ? $_GET["pagi"] : $config_row_page);
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
				$page = 1;
		}
		else {
				$page = $_GET['page'];
		}
		$offset = ($page - 1) * $pagi;
		$category = $this->m_content_categories->load($category_id);

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Tin tức/ Danh mục" => site_url("{$this->util->slug($this->router->fetch_class())}/content-category/{$category->parent_id}")));
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$category->name}" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$category_id}")));
		$search_text	= $this->util->value($this->input->post("search_text"), "");
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$title			= $this->util->value($this->input->post("title"), "");
				$title_en		= $this->util->value($this->input->post("title_en"), "");
				$alias			= $this->util->value($this->input->post("alias"), "");
				$alias_en		= $this->util->value($this->input->post("alias_en"), "");
				$thumbnail 		= !empty($_FILES['thumbnail']['name']) ? explode('.',$_FILES['thumbnail']['name']) : $this->m_contents->load($id)->thumbnail;
				$parent_id		= $this->util->value($this->input->post("parent_id"), "");
				$description	= $this->util->value($this->input->post("description"), "");
				$description_en	= $this->util->value($this->input->post("description_en"), "");
				$content		= $this->util->value($this->input->post("content"), "");
				$content_en		= $this->util->value($this->input->post("content_en"), "");
				$active			= $this->util->value($this->input->post("active"), 1);
				$meta_title		= $this->util->value($this->input->post("meta_title"), "");
				$meta_key		= $this->util->value($this->input->post("meta_key"), "");
				$meta_des		= $this->util->value($this->input->post("meta_des"), "");
				if (empty($id)) {
					$id = $this->m_contents->get_next_value();
				}
				if (empty($alias)) {
					$alias = $this->util->slug($title).'-'.str_replace('=','',strtolower(base64_encode($id)));
				}
				if (empty($alias_en)) {
					$alias_en = $this->util->slug($title_en).'-'.str_replace('=','',strtolower(base64_encode($id)));
				}
				if (empty($meta_title)){
					$meta_title = substr($title,0,69);
				}
				if (empty($meta_des)){
					$meta_des = substr(strip_tags($description),0,159);
				}
				$data = array (
					"title"			=> $title,
					"title_en"		=> $title_en,
					"alias"			=> $alias,
					"alias_en"		=> $alias_en,
					"description"	=> $description,
					"description_en"=> $description_en,
					"content"		=> $content,
					"content_en"	=> $content_en,
					"active"		=> $active,
					"category_id"	=> $parent_id,
					"meta_title"	=> $meta_title,
					"meta_key"		=> $meta_key,
					"meta_des"		=> $meta_des
				);
				if (!empty($_FILES['thumbnail']['name'])){
					$file_name = explode('.',$_FILES['thumbnail']['name']);
					if (end($file_name) == 'png' || end($file_name) == 'PNG'){
						$data['thumbnail'] = "/images/thumb/{$id}/thumb/{$this->util->slug($thumbnail[0])}.jpg";
					} else {
						$data['thumbnail'] = "/images/thumb/{$id}/thumb/{$this->util->slug($thumbnail[0])}.".end($file_name);
					}
				}
				if ($action == "add") {
					$this->m_contents->add($data);
					$this->session->set_flashdata("success", "Thêm thành cộng");
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_contents->update($data, $where);
					$this->session->set_flashdata("success", "Cập nhật thành công");
				}
				$path = "./images/thumb/{$id}/";
				if (!empty($_FILES['thumbnail']['name'])){
					$allow_type = 'JPG|PNG|jpg|jpeg|png';
					$this->util->upload_file($path,'thumbnail','',$allow_type,400);
				}
				$this->create_sitemap();
				redirect(site_url("syslog/content/{$category->id}"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/content/{$category->id}"));
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_contents->update($data, $where);
				}
				$this->create_sitemap();
				redirect(site_url("syslog/content/{$category->id}"));
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_contents->update($data, $where);
				}
				$this->create_sitemap();
				redirect(site_url("syslog/content/{$category->id}"));
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_contents->delete($where);
				}
				$this->create_sitemap();
				$this->session->set_flashdata("success", "Xóa thành công");
				redirect(site_url("syslog/content/{$category->id}"));
			}
		}
		
		if ($action == "add") {
			$item = $this->m_contents->instance();
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Thêm" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$category_id}/{$action}")));
			
			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["item"] = $item;
			$view_data["category"] = $category;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/content/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_contents->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Cập nhật" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$category_id}/{$action}/{$id}")));

			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["item"] = $item;
			$view_data["category"] = $category;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/content/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {
			$info = new stdClass();
			$info->category_id = $category->id;
			if(!empty($search_text))
			$info->search_text = $search_text;

			$total = count($this->m_contents->items($info));
			$items = $this->m_contents->items($info, null, $pagi, $offset);
			if (!isset($_GET['pagi'])){
				$pagination = $this->util->pagination_admin(site_url('syslog/content/'.$category->alias). "?pagi=$config_row_page"."$_SERVER[QUERY_STRING]", $total, $pagi);
			}else{
				$pagination = $this->util->pagination_admin(site_url('syslog/content/'.$category->alias). "?$_SERVER[QUERY_STRING]", $total, $pagi);
			}

			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["offset"]		= $offset;
			$view_data["pagination"]	= $pagination;
			$view_data["items"]			= $items;
			$view_data["search_text"]	= $search_text;
			$view_data["pagi"]			= $pagi;
			$view_data["category"]		= $category;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/content/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function promotion ($action=null, $id=null){
		$config_row_page = ADMIN_ROW_PER_PAGE;
		$pagi		= (isset($_GET["pagi"]) ? $_GET["pagi"] : $config_row_page);
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
			$page = 1;
		}
		else {
			$page = $_GET['page'];
		}
		$offset = ($page - 1) * $pagi;
		$title = 'Mã khuyến mãi';
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$title}" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$code			= $this->util->value($this->input->post("code"), "");
				$sale_type		= $this->util->value($this->input->post("sale_type"), "");
				$sale_value		= $this->util->value($this->input->post("sale_value"), "");
				$bill_money		= $this->util->value($this->input->post("bill_money"), "");
				$money_limit	= $this->util->value($this->input->post("money_limit"), "");
				$user_rank		= $this->util->value($this->input->post("user_rank"), "");
				$limit_time		= $this->util->value($this->input->post("limit_time"), "");
				$fromdate		= $this->util->value($this->input->post("fromdate"), 1);
				$todate			= $this->util->value($this->input->post("todate"), 1);
				$active			= $this->util->value($this->input->post("active"), 1);
				
				$data = array (
					"code"			=> $code,
					"sale_type"		=> $sale_type,
					"sale_value"	=> $sale_value,
					"bill_money"	=> $bill_money,
					"money_limit"	=> $money_limit,
					"user_rank"		=> $user_rank,
					"limit_time"	=> $limit_time,
					"fromdate"		=> $fromdate,
					"todate"		=> $todate,
					"active"		=> $active
				);
				
				if ($action == "add") {
					$this->m_promotion->add($data);
					$this->session->set_flashdata("success", "Thêm thành công");
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_promotion->update($data, $where);
					$this->session->set_flashdata("success", "Cập nhật thành công");
				}
				redirect(site_url("syslog/promotion"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/promotion"));
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_promotion->update($data, $where);
				}
				redirect(site_url("syslog/promotion"));
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_promotion->update($data, $where);
				}
				redirect(site_url("syslog/promotion"));
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_promotion->delete($where);
				}
				$this->session->set_flashdata("success", "Xóa thành công");
				redirect(site_url("syslog/promotion"));
			}
		}
		
		if ($action == "add") {
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Thêm" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/promotion/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_promotion->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Cập nhật" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}/{$id}")));
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["item"] 			= $item;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/promotion/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {
			$total = count($this->m_promotion->items());
			if (!isset($_GET['pagi'])){
				$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"). "?pagi=$config_row_page"."$_SERVER[QUERY_STRING]", $total, $pagi);
			}else{
				$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"). "?$_SERVER[QUERY_STRING]", $total, $pagi);
			}
			$items = $this->m_promotion->items(null,null,$pagi,$offset);
			
			$view_data = array();
			$view_data["breadcrumb"]	= $this->_breadcrumb;
			$view_data["offset"]		= $offset;
			$view_data["pagination"]	= $pagination;
			$view_data["items"]			= $items;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/promotion/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function q_and_a ($action=null, $id=null) {
		$config_row_page = ADMIN_ROW_PER_PAGE;
		$pagi		= (isset($_GET["pagi"]) ? $_GET["pagi"] : $config_row_page);
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
				$page = 1;
		}
		else {
				$page = $_GET['page'];
		}
		$offset = ($page - 1) * $pagi;

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Q&A" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$title			= $this->util->value($this->input->post("title"), "");
				$alias			= $this->util->value($this->input->post("alias"), "");
				$thumbnail 		= !empty($_FILES['thumbnail']['name']) ? explode('.',$_FILES['thumbnail']['name']) : $this->m_contents->load($id)->thumbnail;
				$content		= $this->util->value($this->input->post("content"), "");
				$icon			= $this->util->value($this->input->post("icon"), "");
				$active			= $this->util->value($this->input->post("active"), 1);
				$meta_title		= $this->util->value($this->input->post("meta_title"), "");
				$meta_key		= $this->util->value($this->input->post("meta_key"), "");
				$meta_des		= $this->util->value($this->input->post("meta_des"), "");
				if (empty($id)) {
					$id = $this->m_question->get_next_value();
				}
				if (empty($alias)) {
					$alias = $this->util->slug($title);
				}
				if (empty($meta_title)){
					$meta_title = substr($title,0,69);
				}
				$data = array (
					"title"			=> $title,
					"alias"			=> $alias,
					"icon"			=> $icon,
					"content"		=> $content,
					"active"		=> $active,
					"meta_title"	=> $meta_title,
					"meta_key"		=> $meta_key,
					"meta_des"		=> $meta_des
				);
				if (!empty($_FILES['thumbnail']['name'])){
					$format = end($thumbnail);
					$data['thumbnail'] = "/images/question/{$id}/{$this->util->slug($thumbnail[0])}.{$format}";
				}
				$file_deleted = '';
				
				if ($action == "add") {
					$this->m_question->add($data);
					$this->session->set_flashdata("success", "Add successfull");
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_question->update($data, $where);
					$this->session->set_flashdata("success", "Update successfull");
				}
				$path = "./images/question/{$id}";
				if (!file_exists($path)) {
					mkdir($path, 0755, true);
				}
				if (!empty($_FILES['thumbnail']['name'])){
					$allow_type = 'JPG|PNG|jpg|jpeg|png';
					$format = end($thumbnail);
					$this->util->upload_file($path,'thumbnail',$file_deleted,$allow_type,$this->util->slug($thumbnail[0]).'.'.$format);
				}
				$this->create_sitemap();
				redirect(site_url("syslog/q-and-a"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/q-and-a"));
			}
			else if ($task == "publish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_question->update($data, $where);
				}
				$this->create_sitemap();
				redirect(site_url("syslog/q-and-a"));
			}
			else if ($task == "unpublish") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_question->update($data, $where);
				}
				$this->create_sitemap();
				redirect(site_url("syslog/q-and-a"));
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_question->delete($where);
				}
				$this->create_sitemap();
				$this->session->set_flashdata("success", "Delete successfull");
				redirect(site_url("syslog/q-and-a"));
			}
		}
		
		if ($action == "add") {
			$item = $this->m_question->instance();
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Add Item" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}")));
			
			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["item"] = $item;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/question/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "edit") {
			$item = $this->m_question->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("Update Item" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}/{$id}")));

			$view_data = array();
			$view_data["breadcrumb"] = $this->_breadcrumb;
			$view_data["item"] = $item;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/question/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {

			$total = count($this->m_question->items(null));
			$items = $this->m_question->items(null, null, $pagi, $offset);
			if (!isset($_GET['pagi'])){
				$pagination = $this->util->pagination_admin(site_url('syslog/q-and-a'). "?pagi=$config_row_page"."$_SERVER[QUERY_STRING]", $total, $pagi);
			}else{
				$pagination = $this->util->pagination_admin(site_url('syslog/q-and-a'). "?$_SERVER[QUERY_STRING]", $total, $pagi);
			}
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["offset"]		= $offset;
			$view_data["pagination"]	= $pagination;
			$view_data["items"]			= $items;
			$view_data["pagi"]			= $pagi;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/question/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function contact ($id=null) {
		if (!isset($_GET['page']) || (($_GET['page']) < 1) ) {
				$page = 1;
		}
		else {
				$page = $_GET['page'];
		}
		$offset = ($page - 1) * ADMIN_ROW_PER_PAGE;
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Contacts" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == 'delete') {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_contact->delete($where);
				}
				$this->session->set_flashdata("success", "Delete successfull");
			}
			redirect(site_url("syslog/contact"));
		}
		if (!empty($id)) {
			$item = $this->m_contact->load($id);
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$item->name}" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$id}")));
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["item"]			= $item;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/contact/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		} else {
			$total = count($this->m_contact->items());
			$items = $this->m_contact->items(null, ADMIN_ROW_PER_PAGE, $offset);

			$pagination = $this->util->pagination_admin(site_url('syslog/contact'), $total, ADMIN_ROW_PER_PAGE);
			
			$view_data = array();
			$view_data["breadcrumb"] 	= $this->_breadcrumb;
			$view_data["offset"]		= $offset;
			$view_data["pagination"]	= $pagination;
			$view_data["items"]			= $items;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/contact/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function mail($action=null, $id=null)
	{
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Mail" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "save") {
				$sender			= $this->util->value($this->input->post("sender"), "");
				$receiver		= $this->util->value($this->input->post("receiver"), "");
				$subject		= $this->util->value($this->input->post("subject"), "");
				$message		= $this->util->value($this->input->post("message"), "");
				$reply_id		= $this->util->value($this->input->post("reply_id"), 0);
				$read			= $this->util->value($this->input->post("read"), 0);
				
				$data = array (
					"sender"	=> $sender,
					"receiver"	=> $receiver,
					"subject"	=> $subject,
					"message"	=> $message,
					"reply_id"	=> $reply_id,
					"read"		=> $read
				);
				
				if ($action == "add") {
					$this->m_mail->add($data);
				}
				else if ($action == "edit") {
					$where = array("id" => $id);
					$this->m_mail->update($data, $where);
				}
				redirect(site_url("syslog/mail"));
			}
			else if ($task == "cancel") {
				redirect(site_url("syslog/mail"));
			}
			else if ($task == "read") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("read" => 1);
					$where = array("id" => $id);
					$this->m_mail->update($data, $where);
				}
				redirect(site_url("syslog/mail"));
			}
			else if ($task == "unread") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("read" => 0);
					$where = array("id" => $id);
					$this->m_mail->update($data, $where);
				}
				redirect(site_url("syslog/mail"));
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_mail->delete($where);
				}
				redirect(site_url("syslog/mail"));
			}
			else if ($task == "delete-all") {
				$this->m_mail->delete_all();
				redirect(site_url("syslog/mail"));
			}
		}
		
		if ($action == "add") {
			$view_data = array();
			$view_data["item"] = $this->m_mail->instance();
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/mail/edit", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else if ($action == "detail") {
			$item = $this->m_mail->load($id);
			
			$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$item->title}" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}/{$action}/{$id}")));
			
			$view_data = array();
			$view_data["item"]			= $item;
			$view_data["breadcrumb"]	= $this->_breadcrumb;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/mail/detail", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
		else {
			$page = (!empty($_GET["page"]) ? max($_GET["page"], 1) : 1);
			$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"), $this->m_mail->count(), ADMIN_ROW_PER_PAGE);
			
			$view_data = array();
			$view_data["items"]			= $this->m_mail->items(null, null, ADMIN_ROW_PER_PAGE, ($page - 1) * ADMIN_ROW_PER_PAGE);
			$view_data["breadcrumb"]	= $this->_breadcrumb;
			$view_data["page"]			= $page;
			$view_data["pagination"]	= $pagination;
			
			$tmpl_content = array();
			$tmpl_content["content"] = $this->load->view("admin/mail/index", $view_data, true);
			$this->load->view("layout/admin/main", $tmpl_content);
		}
	}
	public function sub_mail($action=null, $id=null)
	{
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Đăng ký mail" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		
		$task = $this->util->value($this->input->post("task"), "");
		if (!empty($task)) {
			if ($task == "public") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 1);
					$where = array("id" => $id);
					$this->m_subscribe->update($data, $where);
				}
				redirect(site_url("syslog/sub-mail"));
			}
			else if ($task == "unpublic") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$data = array("active" => 0);
					$where = array("id" => $id);
					$this->m_subscribe->update($data, $where);
				}
				redirect(site_url("syslog/sub-mail"));
			}
			else if ($task == "export") {
				$filename = 'submail-'.date('Y-m-d');
				$info = new stdClass();
				$info->active = 1;
				$this->m_subscribe->export_csv($filename,'email as Email',$info);
			}
			else if ($task == "delete") {
				$ids = $this->util->value($this->input->post("cid"), array());
				foreach ($ids as $id) {
					$where = array("id" => $id);
					$this->m_subscribe->delete($where);
				}
				redirect(site_url("syslog/sub-mail"));
			}
		}

		$page = (!empty($_GET["page"]) ? max($_GET["page"], 1) : 1);
		$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"), $this->m_subscribe->count(), ADMIN_ROW_PER_PAGE);
		
		$view_data = array();
		$view_data["items"]			= $this->m_subscribe->items(null, null, ADMIN_ROW_PER_PAGE, ($page - 1) * ADMIN_ROW_PER_PAGE);
		$view_data["breadcrumb"]	= $this->_breadcrumb;
		$view_data["page"]			= $page;
		$view_data["pagination"]	= $pagination;
		
		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("admin/sub_mail/index", $view_data, true);
		$this->load->view("layout/admin/main", $tmpl_content);
	}
	public function rating()
	{
		$rank					= !empty($_GET["rank"]) ? $_GET["rank"] : '';
		$fromdate				= !empty($_GET["fromdate"]) ? $_GET["fromdate"] : '';
		$todate					= !empty($_GET["todate"]) ? $_GET["todate"] : '';

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Rating" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));
		$task = '';
		if (!empty($_GET['task'])) {
			$task = $this->util->value($_GET['task'], "");
		}
		
		if (!empty($task)) {
			if ($task == "public_item") {
				$id = $_GET['item_id'];
				$rat = $this->m_rating->load($id);
				$product = $this->m_product->load($rat->product_id);
				if ($rat->parent_id == 0) {
					$data = array(
						'rating_point' 	=> $product->rating_point+$rat->point,
						'rating_cmt' 	=> $product->rating_cmt+1,
					);
					$this->m_product->update($data,array("id"=>$product->id));
				}
				$data = array("active" => 1);
				$where = array("id" => $id);
				$this->m_rating->update($data, $where);
				redirect(site_url("syslog/rating"));
			}
			else if ($task == "private_item") {
				$id = $_GET['item_id'];
				$rat = $this->m_rating->load($id);
				$product = $this->m_product->load($rat->product_id);
				if ($rat->parent_id == 0) {
					$data = array(
						'rating_point' 	=> $product->rating_point-$rat->point,
						'rating_cmt' 	=> $product->rating_cmt-1,
					);
					$this->m_product->update($data,array("id"=>$product->id));
				}
				$data = array("active" => 0);
				$where = array("id" => $id);
				$this->m_rating->update($data, $where);
				redirect(site_url("syslog/rating"));
			}
			else if ($task == "delete_item") {
				$id = $_GET['item_id'];
				$where = array("id" => $id);
				$this->m_rating->delete($where);
				redirect(site_url("syslog/rating"));
			}
		}
		$info = new stdClass();
		$info->parent_id = 0;
		$info->fromdate = $fromdate;
		$info->todate = $todate;
		$info->point = $rank;
		$total = $this->m_rating->items('COUNT(id) as total',$info);
		$total = !empty($total[0]->total)?$total[0]->total:0;
		$page = (!empty($_GET["page"]) ? max($_GET["page"], 1) : 1);
		$pagination = $this->util->pagination_admin(site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}"), $total, ADMIN_ROW_PER_PAGE);
		
		$view_data = array();
		$view_data["items"]			= $this->m_rating->product_items('p.title, p.alias, r.*',$info, null, ADMIN_ROW_PER_PAGE, ($page - 1) * ADMIN_ROW_PER_PAGE);
		$view_data["breadcrumb"]	= $this->_breadcrumb;
		$view_data["page"]			= $page;
		$view_data["pagination"]	= $pagination;
		$view_data["rank"]			= $rank;
		$view_data["fromdate"]		= $fromdate;
		$view_data["todate"]		= $todate;
		
		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("admin/rating/index", $view_data, true);
		$this->load->view("layout/admin/main", $tmpl_content);
	}
	public function rating_send_reply() {
		$message = $this->input->post("message");
		$item_id = $this->input->post("item_id");
		$product_id = $this->input->post("product_id");
		$admin = $this->session->userdata("admin");
		$data = array (
			'name' 	  	=> $admin->fullname,
			'parent_id' => $item_id,
			'product_id'=> $product_id,
			'message' 	=> $message,
			'user_id' 	=> $admin->id,
			'user_type' => -1,
			'active' 	=> 1,
		);
		if($this->m_rating->add($data)) {
			echo json_encode(1);
		} else {
			echo json_encode(0);
		}
	}
	function ajax_mail_forward($action)
	{
		if ($action == "send") {
			$to_receiver = $this->input->post("to_receiver");
			$subject = $this->input->post("subject");
			$message = $this->input->post("message");
			
			$mail_data = array(
				"subject"		=> $subject,
				"from_sender"	=> MAIL_INFO,
				"name_sender"	=> SITE_NAME,
				"to_receiver"   => explode(";", $to_receiver),
				"bcc"   		=> "nevermorepda1@gmail.com",
				"message"       => $message
			);
			$this->mail->config($mail_data);
			$this->mail->sendmail();
			echo "Mail is sent.";
		}
		else if ($action == "compose") {
			$id = $this->input->post("id");
			$item = $this->m_mail->load($id);
			echo json_encode(array($item->sender, $item->title, $item->message));
		}
	}
	function ajax_mkdir()
	{
		$user_path = $_POST["user_path"];
		$path = "./files/upload/user/".$user_path;
		
		if (!file_exists($path)) {
		   mkdir($path, 0775, TRUE);
		}
		echo "";
	}
	
	function remove_empty_dir()
	{
		$path = "./files/upload/user/";
		
		if (file_exists($path)) {
			$user_files = scandir($path);
			
			$cnt = 0;
			foreach ($user_files as $user_file) {
				if (in_array($user_file, array(".","..")) || !is_dir($path.$user_file)) continue;
				
				$nfile = 0;
				
				$approval_path = $path.$user_file."/approval/";
				$bookings = scandir($approval_path);
				
				foreach ($bookings as $booking) {
					if (in_array($booking, array(".","..")) || !is_dir($approval_path.$booking)) continue;
					
					$booking_path = $path.$user_file."/approval/".$booking;
					$files = scandir($booking_path);
					
					foreach ($files as $file) {
						if (in_array($file, array(".",".."))) continue;
						$nfile++;
					}
				}
				
				if (!$nfile && !empty($user_file)) {
					echo ++$cnt .". ". $user_file."<br/>";
				}
			}
		}
	}
	
	function remove_all_user_dir()
	{
		$path = "./files/upload/user/";
		
		if (file_exists($path)) {
			$user_files = scandir($path);
			
			$cnt = 0;
			foreach ($user_files as $user_file) {
				if (in_array($user_file, array(".","..")) || !is_dir($path.$user_file)) continue;
				if (!empty($user_file)) {
					$this->rrmdir($path.$user_file);
					echo ++$cnt .". ". $user_file."<br/>";
				}
			}
		}
	}
	
	function rrmdir($dir)
	{
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}
	function ajax_check_read()
	{
		$book_id = $this->input->post("book_id");

		$data  = array(
			"check_read" => 1
		);
		
		$this->m_booking->update($data, array("id" => $book_id));
		echo "";
	}
	function ajax_payment_status()
	{
		$booking_id = $this->input->post("booking_id");
		$status_id = (int)$this->input->post("status_id");

		$info = new stdClass();
		$info->booking_id = $booking_id;
		$details = $this->m_booking_detail->items($info);
		$cost_total = 0;
		$amount = 0;
		foreach ($details as $detail) {
			$product = $this->m_product->load($detail->product_id);
			
			$key_i = ($detail->key_i!=-1) ? $detail->key_i : 0;
			$key_j = ($detail->key_j!=-1) ? $detail->key_j : 0;
			$info = new stdClass();
			$info->product_id = $detail->product_id;
			$product_type = $this->m_product_type->items($info);
			$list_cost = json_decode($product_type[$key_i]->cost);
			
			$qty = $detail->qty;
			if ($status_id == 2) {
				$info->typename = $detail->typename;
				$boxs = $this->m_box_detail->join_items($info,$key_j);
				$arr_box_detail_id = array();
				$arr_box_detail_qty = array();
				foreach ($boxs as $box) {
					$arr_box_qty = json_decode($box->quantity);
					$arr_box_cost = json_decode($box->cost);
					array_push($arr_box_detail_id,(string)$box->id);
					if ($qty <= $arr_box_qty[$key_j]) {
						array_push($arr_box_detail_qty,(string)$qty);
						$cost_total += $arr_box_cost[$key_j]*$qty;
						$arr_box_qty[$key_j] = (string)($arr_box_qty[$key_j] - $qty);
						$this->m_box_detail->update(array('quantity'=>json_encode($arr_box_qty)),array('id' => $box->id));
						$qty = 0;
						break;
					} else {
						$qty = $qty - $arr_box_qty[$key_j];
						array_push($arr_box_detail_qty,(string)$arr_box_qty[$key_j]);
						$cost_total += $arr_box_cost[$key_j]*$arr_box_qty[$key_j];
						$arr_box_qty[$key_j] = "0";
						$this->m_box_detail->update(array('quantity'=>json_encode($arr_box_qty)),array('id' => $box->id));
					}
				}
				if ($qty > 0){
					$cost_total += $qty*$list_cost[$key_j];
				}

				$data_booing_detail = array(
					"box_detail_id"	=>	json_encode($arr_box_detail_id),
					"box_detail_qty"=>	json_encode($arr_box_detail_qty)
				);
			} else {
				$box_detail_id = json_decode($detail->box_detail_id);
				$box_detail_qty = json_decode($detail->box_detail_qty);
				$i=0;
				foreach($box_detail_id as $detail_id) {
					$box_item = $this->m_box_detail->load($detail_id);
					$box_item_qty = json_decode($box_item->quantity);
					$box_item_qty[$key_j] = (string)($box_item_qty[$key_j] + $box_detail_qty[$i]);
					$this->m_box_detail->update(array('quantity'=>json_encode($box_item_qty)),array('id' => $detail_id));
				$i++;}
				$data_booing_detail = array(
					"box_detail_id"	=> 	NULL,
					"box_detail_qty"=>	NULL
				);
			}
			
			$this->m_booking_detail->update($data_booing_detail,array("id"=>$detail->id));
			
			$amount += (($detail->price_sale*$detail->qty)*$product->affiliate)/100;
		}
		$data_affiliate = array(
			"amount" => $amount
		);
		$this->m_affiliate->update($data_affiliate,array("booking_id"=>$booking_id));
		$data  = array(
			"payment_status" => $status_id,
			"paid_date" => date($this->config->item("log_date_format"))
		);
		$data['cost'] = $cost_total;
		$this->m_booking->update($data, array("id" => $booking_id));

		echo json_encode($cost_total);
	}
	function ajax_booking_status() {
		$booking_id = $this->input->post("booking_id");
		$status_id = (int)$this->input->post("status_id");
		$data  = array(
			"status" => $status_id,
		);
		$this->m_booking->update($data, array("id" => $booking_id));
		echo json_encode(1);
	}
	function ajax_order_status() {
		$booking_id = $this->input->post("booking_id");
		$status_id = (int)$this->input->post("status_id");
		$data  = array(
			"status" => $status_id,
		);
		$this->m_order->update($data, array("id" => $booking_id));
		echo json_encode(1);
	}
	function ajax_info_order_detail()
	{
		$id_item = $this->input->post("id_item");
		$prop = $this->input->post("prop");
		$val = $this->input->post("val");
		
		$data  = array(
			"{$prop}" => $val
		);
		$where = array(
			"id" => $id_item
		);
		
		$this->m_order_detail->update($data, $where);
		
		echo "";
	}
	function ajax_info_order()
	{
		$id_item = $this->input->post("id_item");
		$prop = $this->input->post("prop");
		$val = $this->input->post("val");
		
		$data  = array(
			"{$prop}" => $val
		);
		$where = array(
			"id" => $id_item
		);
		
		$this->m_order->update($data, $where);
		
		echo "";
	}
	function ajax_info_book()
	{
		$id_item = $this->input->post("id_item");
		$prop = $this->input->post("prop");
		$val = $this->input->post("val");
		
		$data  = array(
			"{$prop}" => $val
		);
		$where = array(
			"id" => $id_item
		);
		
		$this->m_booking->update($data, $where);
		
		echo "";
	}
	function ajax_info_book_cart()
	{
		$id_item = $this->input->post("id_item");
		$prop = $this->input->post("prop");
		$val = $this->input->post("val");
		
		$data  = array(
			"{$prop}" => $val
		);
		$where = array(
			"id" => $id_item
		);
		
		$this->m_booking->update($data, $where);
		
		echo "";
	}
	function optimize_image($id) {
		$product = $this->m_product->load($id);
		$fileList = glob(PATH_ROOT.'/files/upload/product/'.$product->code.'/thumbnail/*');
		$path = "./files/upload/product/{$product->code}/thumbnail/";
		chmod("{$path}", 0777);
		foreach($fileList as $file) {
			
			$width_thumb = 180;
			$image_name =  str_replace(PATH_ROOT,'.',$file);
			chmod("{$image_name}", 0777);
			list($width, $height) = getimagesize($image_name);
			$percent = ($width_thumb*100)/$width;
            $new_width = $width_thumb;
            $new_height = $height * $percent * 0.01;

			$format = explode('.',$file);
			$format = end($format);
			$resized = imagecreatetruecolor($new_width, $new_height);

			if ($format == 'png'||$format == 'PNG') {
				imagealphablending($resized, false);
				imagesavealpha($resized, true);
				imagefilledrectangle(
					$resized, 0, 0, 225, 300, 
					imagecolorallocatealpha($resized, 255, 255, 255, 127)
				);
				$src = imagecreatefrompng($image_name);
				imagecopyresampled($resized, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				unlink($file);
				imagepng($resized,$image_name);
				imagedestroy($src);
				imagedestroy($resized);
			} else {
				$src = imagecreatefromjpeg($image_name);
				
				imagecopyresampled($resized, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				unlink($file);
				imagejpeg($resized,$image_name);
			}
			chmod("{$image_name}", 0776);
		}
		chmod("{$path}", 0777);
	}
	function upload_image_product($code)
	{
		$file = key($_FILES);
		// var_dump($_FILES[$file]["tmp_name"]);
		// die;
		// $width_thumb = 200;
		// $image_info = getimagesize($_FILES[$file]["tmp_name"]);
		// $image_width = $image_info[0];
		
		// $quality_thumb = round(100 - $width_thumb/($image_width*0.01));

		$old_file_name = $_POST['old_photo'];
		$new_file_name = $_POST['new_photo'];
		
		$path = "./files/upload/product/{$code}/";
		$path_thumb = $path.'thumb/';
		$format = explode('.',$_FILES[$file]["name"]);
		$format = end($format);
		$old_file_name = explode($code.'/',$old_file_name);

		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}

		$config = array(
			"upload_path"	=> $path,
			"allowed_types"	=> "*",
			"max_size"		=> 20000,
			"file_name" 	=> $new_file_name.'.'.$format
		);
		
		$this->load->library("upload", $config);

		if ($this->upload->do_upload($file)) {

			$image_data = $this->upload->data();

			if (!file_exists($path_thumb)) {
				mkdir($path_thumb, 0775, true);
			}
			
			$source_path = $path. $image_data['file_name'];
			$target_path = $path_thumb;
			$config_manip = array(
				'image_library' 	=> 'gd2',
				'source_image' 		=> $source_path,
				'new_image' 		=> $target_path,
				'quality' 			=> 85,
				'maintain_ratio' 	=> TRUE,
				'create_thumb' 		=> TRUE,
				'thumb_marker' 		=> '',
				'width' 			=> 300,
				"file_name" 		=> $new_file_name.'.'.$format
			);
			$this->load->library('image_lib', $config_manip);
			$this->image_lib->initialize($config_manip);
			$this->image_lib->resize();
			$this->image_lib->clear();

			if ($format == 'png' || $format == 'PNG') {
				$image = imagecreatefromstring(file_get_contents($_FILES[$file]["tmp_name"]));
				$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
				imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
				imagealphablending($bg, TRUE);
				imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
				imagedestroy($image);
				imagejpeg($bg, $path.$new_file_name.".jpg");
				imagedestroy($bg);

				$image_thumb = imagecreatefrompng($path_thumb."/".$new_file_name.".png");
				$bg_thumb = imagecreatetruecolor(imagesx($image_thumb), imagesy($image_thumb));
				imagefill($bg_thumb, 0, 0, imagecolorallocate($bg_thumb, 255, 255, 255));
				imagealphablending($bg_thumb, TRUE);
				imagecopy($bg_thumb, $image_thumb, 0, 0, 0, 0, imagesx($image_thumb), imagesy($image_thumb));
				imagedestroy($image_thumb);
				imagejpeg($bg_thumb, $path_thumb.$new_file_name.".jpg");
				imagedestroy($bg_thumb);
				
				unlink($path.$new_file_name.".".$format);
				unlink($path_thumb.$new_file_name.".".$format);
				$format = 'jpg';
			}
		}
		chmod("{$path}", 0775);
		$res = "./files/upload/product/{$code}/".$new_file_name.'.'.$format;
		echo json_encode($res);
	}
	function ck_finder() {
		$path = $this->input->post('path');
		$action = $this->input->post('action');
		$name_dir = $this->input->post('name_dir');
		chmod("{$path}", 0777);
		if ($action == 'add') {
			$path_new = "{$path}/{$name_dir}";
			if (!file_exists($path_new)) {
				mkdir($path_new, 0777, true);
				$path_dir = $path.'/'.$name_dir;
				echo json_encode(array($path_dir,$this->util->slug($path_dir)));
			}
		} else if ($action == 'del') {
			$scan = scandir($path);
			$result = 0;
			if (count($scan) <= 2) {
				rmdir($path);
				$result = 1;
			}
			echo json_encode($result);
		} else if ($action == 'upload') {
			$config = array(
				"upload_path" 		=> $path,
				"allowed_types" 	=> '*',
				'overwrite'     	=> 1,  
			);
			$this->load->library('upload',$config);
			
			$files = $_FILES['file'];
			$str = '';
			foreach ($files['name'] as $key => $image) {
				$file_url = BASE_URL.str_replace('./','/',$path).'/';

				$_FILES['file[]']['name']= $files['name'][$key];
				$_FILES['file[]']['type']= $files['type'][$key];
				$_FILES['file[]']['tmp_name']= $files['tmp_name'][$key];
				$_FILES['file[]']['error']= $files['error'][$key];
				$_FILES['file[]']['size']= $files['size'][$key];
	
				$temp = explode('.',$files['name'][$key]);
				$filename = $this->util->slug($temp[0]).'.'.end($temp);
	
				$config['file_name'] = $filename;
	
				$this->upload->initialize($config);
	
				if ($this->upload->do_upload('file[]')) {
					$this->upload->data();
					$file_url .= $filename;
					$str .= '<div class="item">
						<i class="fa fa-times" aria-hidden="true"></i>
						<img class="select-img" src="'.$file_url.'">
					</div>';
				} else {
					return false;
				}
			}
			echo json_encode($str);
		} else if ($action == 'delfile') {
			$src = $this->input->post('src');
			$src = explode('/', $src);
			$filename = end($src);
			$path = str_replace('./','/',$path);
			chmod("./{$path}/{$filename}", 0777);
			unlink(PATH_ROOT.$path.'/'.$filename);
		}
	}
	function load_file_manager() {
		$path = $this->input->post('path');
		chmod("{$path}", 0777);
		$path = str_replace('./','/',$path);
		$files = array_filter(glob(PATH_ROOT.$path.'/*'), 'is_file');
		$str = '';
		foreach($files as $file) {
			$file_url = str_replace(PATH_ROOT,BASE_URL,$file);
			$str .= '<div class="item">
					<i class="fa fa-times" aria-hidden="true"></i>
					<img class="select-img" src="'.$file_url.'">
				</div>';
		}
		echo json_encode($str);
	}
	function load_product() {
		$id = $this->input->post('productId');
		$product = $this->m_product->load($id);
		$info = new stdClass();
		$info->product_id = $id;
		$photo = $this->m_product_gallery->items($info);
		$product->photo = $photo[0]->file_path;
		$product->product_type = $this->m_product_type->items($info);

		$i=0;
		$check_i = -1;
		$check_j = -1;
		$find_price = 0;
		$price = 0;
		$sale = 0;
		$price_temp = 0;
		$quantity = 0;
		$str_type = '<div class="pattern">';
		$str_typename = '';
		$str_subtypename = '';

		if (!empty($product->typename))
			$str_typename .= '<div class="pattern-label">'.$product->typename.':</div><ul class="list clearfix">';
		if (!empty($product->subtypename))
			$str_subtypename .= '<div class="pattern-label">'.$product->subtypename.':</div>';
			
		foreach($product->product_type as $product_type) {
			$type_quantity 		= json_decode($product_type->quantity);
			$type_price 		= json_decode($product_type->price);
			$type_subtypename 	= json_decode($product_type->subtypename);
			$type_sale 			= json_decode($product_type->sale);
			$c = count($type_quantity);
			//
			$str_subtypename_child = '';
			for ($j=0;$j<$c;$j++) {
				if ($type_quantity[$j]!=0&&$find_price==0) {
					$check_i 	= $i;
					$check_j 	= $j;
					$price 		= $type_price[$j];
					$sale 		= $type_sale[$j];
					$quantity 	= $type_quantity[$j];
					$find_price = 1;
				}
				//
				if (!empty($product->subtypename)) {
					if ($type_quantity[$j] != 0)
					$str_subtypename_child .= 	'<li class="item get-item" qty="'.$type_quantity[$j].'">';
					else
					$str_subtypename_child .= 	'<li class="item disabled">';
						$str_subtypename_child .= 	'<label class="radio-container">';
							$str_subtypename_child .= 	'<div class="pattern-info">';
								$str_subtypename_child .= 	'<div class="type">'.$type_subtypename[$j].'</div>';
								$str_subtypename_child .= 	'<div class="sub-price" price="'.$type_price[$j].'">'.number_format($type_price[$j],0,',','.').'<sup>₫</sup></div>';
							$str_subtypename_child .= 	'</div>';
							$checked_subtype = ($check_i==$i&&$check_j==$j)?'checked="checked"':'';
							if ($type_quantity[$j] != 0)
							$str_subtypename_child .= 	'<input type="radio" '.$checked_subtype.' name="subtypename" sale="'.$type_sale[$j].'" value="'.$type_subtypename[$j].'">';
							$str_subtypename_child .= 	'<span class="checkmark"></span>';
						$str_subtypename_child .= 	'</label>';
					$str_subtypename_child .= 	'</li>';
				}
			}
			if (!empty($product->subtypename)) {
				$display = ($check_i==$i)?'style="display:block"':'style="display:none"';
				$str_subtypename .= '<ul '.$display.' class="list clearfix p-subtypename subtypename-'.$i.'">';
				$str_subtypename .= $str_subtypename_child;
				$str_subtypename .= '</ul>';
			}
			//
			if (!empty($product->typename)) {
				if ($quantity != 0)
				$str_typename .= 	'<li class="item get-item" qty="'.$type_quantity[0].'">';
				else 
				$str_typename .= 	'<li class="item disabled">';
					$str_typename .= 	'<label class="radio-container">';
						$str_typename .= 	'<div class="pattern-info">';
							$str_typename .= 	'<div class="type">'.$product_type->typename.'</div>';
							$checksale = "";
							if ($product_type->subtypename=='""') {
								$str_typename .= 	'<div class="sub-price" price="'.$type_price[0].'">'.number_format($type_price[0],0,',','.').'<sup>₫</sup></div>';
								$checksale = 'sale="'.$type_sale[0].'"';
							}
						$str_typename .= 	'</div>';
						$checked = ($check_i==$i)?'checked="checked"':'';
							if ($quantity != 0)
						$str_typename .= 	'<input stt="'.$i.'" class="p-typename" type="radio" '.$checked.' '.$checksale.'  name="typename" value="'.$product_type->typename.'">';
						$str_typename .= 	'<span class="checkmark"></span>';
					$str_typename .= 	'</label>';
				$str_typename .= 	'</li>';
			}
			//
		$i++;}
		if ($price==0) {
			$price = $type_price[0];
			$sale = $type_sale[0];
		} 
		if (!empty($product->typename)) $str_typename .= '</ul>';
		$str_type .= $str_typename.$str_subtypename.
		'<div class="pattern-label">Số lượng:</div>
		<select class="form-control quantity-admin" max_qty="'.$quantity.'" name="" id="">';
		for ($i=1;$i<=$quantity;$i++){
			$str_type .= '<option value="'.$i.'">'.$i.'</option>';
		}
		$str_type .= '</select></div>';
		$photo = explode('/', $product->photo);
		$str = ' <div class="wrap-info-product">
			<div class="image-product">
				<img src="'.BASE_URL.'/files/upload/product/'.$product->code.'/thumb/'.end($photo).'" alt="">
			</div>
			<div class="info-product">
				<table class="info">
					<tbody>
						<tr>
							<td class="label">Tình trạng:</td>
							<td><span class="qty-product" style="color:green">Còn '.$quantity.' sản phẩm</span></td>
						</tr>
						<tr>
							<td class="label">Giá:</td>
							<td><span price_o="'.$price.'" sale="'.$sale.'" price="'.$price*(1-($sale*0.01)).'" class="price">'.number_format($price*(1-($sale*0.01)),0,',','.').'<sup>₫</sup></span></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>';
		$result = array($str.$str_type, $quantity);
		echo json_encode($result);
	}
	function get_product_in_cate (){
		$cate_id = $this->input->post('cate_id');
		$info = new stdClass();
		$info->parent_id = $cate_id;
		$categories = $this->m_product_category->items($info,$active=null, $limit=null, $offset=null, $order_by='code', $sort_by='ASC');
		if (!empty($categories)) {
			$result = array('cate',$categories);
			echo json_encode($result);
		} else {
			$info = new stdClass();
			$info->category_id = array($cate_id);
			$product = $this->m_product->items($info);
			$result = array('product',$product);
			echo json_encode($result);
		}
	}
	function load_img_product_detail (){
		$code = $this->input->post('code');
		$path = PATH_ROOT.'/files/upload/product/'.$code;
		$scans = array_filter(glob($path.'/*'), 'is_file');
		$arr = array();
		foreach($scans as $scan) {
			$scan = explode($code,$scan);
			$value = '/files/upload/product/'.$code.end($scan);
			array_push($arr,$value);
		}
		echo json_encode($arr);
	}
	public function add_promotion(){
		$promotion = $this->input->post('promotion');
		$total_final = $this->input->post('total_final');

		$info = new stdClass();
		$info->code = $promotion;
		$items = $this->m_promotion->items($info,1);
		$result = null;
		$status = 0; // mã hết hạn
		foreach ($items as $item){
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
		if (!empty($result)) {
			if ($total_final < $result->bill_money){
				$result = null;
				$status = -1; // don hang khong du
			}
		}
		echo json_encode(array($status,$result));
	}
}

?>