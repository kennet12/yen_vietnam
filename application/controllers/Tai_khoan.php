<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tai_khoan extends CI_Controller {

	var $_breadcrumbs = array();
	var $_lang = '';
	var $menu = array();
	var $website = array();
	var $prop = array();
	var $alias = array();

	function __construct()
	{
		parent::__construct();

		$method = $this->util->slug($this->router->fetch_method());
		$this->_lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
		$this->lang->load('content',$this->_lang);
		$this->menu			= $this->lang->line('menu');
		$this->website		= $this->lang->line('website');
		$this->prop			= $this->lang->line('prop');
		$this->alias		= $this->lang->line('alias');
		$this->load->library("cart");

		if (!in_array($method, array("dang-ky", "dang-nhap", "lay-lai-mat-khau", "dang-xuat","dang-ky-tai-khoan"))) {
			$this->util->require_user_login();
			$user = $this->session->userdata("user");
			if (!$user->active) {
				$this->session->set_flashdata("error", "Tài khoản của bạn đang được xem xét.");
				redirect(site_url("tai-khoan/dang-nhap"));
			}
			else if ($user->deleted) {
				$this->session->set_flashdata("error", "Tài khoản của bạn không có hoặc đã xoá.");
				redirect(site_url("tai-khoan/dang-nhap"));
			}
		}

		if (isset($this->session->user->signin_date)) {
			if (($this->session->user->event_signin_everyday == 0) && ($this->session->user->signin_date == date("Y/m/d"))) {
				$this->session->user->event_signin_everyday = 1;
				$this->event->event_signin_everyday($this->session->user->id);
			}
			if (($this->session->user->event_signin_everyday == 1) && ($this->session->user->signin_date < date("Y/m/d"))) {
				$this->session->user->signin_date = date("Y/m/d");
				$this->event->event_signin_everyday($this->session->user->id);
			}
			if ($this->session->user->signin_date > date("Y/m/d")) {
				$this->session->user->signin_date = date("Y/m/d");

			}
		}
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->menu['home']}" => site_url()));
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->website['account']}" => site_url($this->util->slug($this->router->fetch_class()))));
	}

	public function index()
	{
		redirect(site_url("tai-khoan/lich-su-don-hang"));
		// $info = new stdClass();
		// $info->user_id = $this->session->user->id;
		// // $bookings = $this->m_booking->items($info);

		// $view_data = array();
		// // $view_data["bookings"] 		= $bookings;
		// $view_data["breadcrumb"] 	= $this->_breadcrumbs;
		// // $view_data['cart'] 			= $this->cart->contents();

		// $tmpl_content = array();
		// $tmpl_content["content"] = $this->load->view("account/index", $view_data, true);
		// $tmpl_content["title"] 		= 'Tài khoản';
		// $this->load->view("layout/account", $tmpl_content);
	}
	public function lich_su_don_hang()
	{
		if (!isset($_GET['page']) || (($_GET['page']) < 1)) {
			$page = 1;
		}
		else {
			$page = $_GET['page'];
		}
		$offset   = ($page - 1) * 5;

		$info = new stdClass();
		$info->user_id = $this->session->user->id;
		$total = count($this->m_booking->items($info));
		$bookings = $this->m_booking->items($info, 5, $offset);

		$url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$url = str_replace("?page={$page}", '?', $url);
		$url = str_replace("&page={$page}", '', $url);

		$pagination = $this->util->pagination($url, $total, 5,$this->website['product']);

		$view_data = array();
		$view_data["menu"] 			= $this->menu;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;
		$view_data["alias"] 		= $this->alias;
		$view_data["bookings"] 		= $bookings;
		$view_data["breadcrum"] 	= $this->_breadcrumbs;
		$view_data["breadcrumb"] 	= $this->_breadcrumbs;
		$view_data["pagination"] 	= $pagination;

		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("account/order_list", $view_data, true);
		$tmpl_content["title"] 	= $this->website['order_list'];
		$this->load->view("layout/account", $tmpl_content);
	}

	public function dang_nhap()
	{
		$this->load->library('session');
		if ($_SERVER['HTTP_REFERER'] != site_url('tai-khoan/dang-nhap')) {
			$this->session->set_userdata('current_url', $_SERVER['HTTP_REFERER']);
		}
		$current_url = $this->session->userdata('current_url');
		$task = $this->input->post("task");
		if (!empty($task)) {
			
			$this->load->library('form_validation');

			if ($task == "login") {
				$uid = $this->input->post("username");
				$password = $this->input->post("password");

				$info = new stdClass();
				if ($this->util->classify_userid_type($uid) == 'email') {
					$info->email = $uid;
				} else {
					$info->username = $uid;
				}
				$info->password = $password;
				$user = $this->m_user->user($info, 1,1);

				$data = new stdClass();
				$data->username = $user->email;
				$this->session->set_flashdata("login", $data);

				if ($user != null) {
					if ($this->m_user->login($uid, $password)) {
						
						$info_cart = new stdClass();
						$info_cart->user_id = $this->session->user->id;
						$carts = $this->m_cart->items($info_cart);
						$carts_session = $this->cart->contents();
						$array_update = array();
						foreach ($carts as $cart) {
							foreach($carts_session as $cart_session) {
								if ($cart_session['id'] == $cart->product_id_temp) {
									$data_cart = array(
										"rowid" 	=> $cart_session['rowid'],
										"qty" 		=> $cart_session['qty'],
										"price_sale"=> $cart_session['price_sale'],
										"price" 	=> $cart_session['price'],
									);
									$this->cart->product_name_rules = '[:print:]';
									$this->cart->update($data_cart);
									array_push($array_update, $cart_session['id']);
									break;
								}
							}
							if (!in_array($cart->product_id_temp,$array_update)) {
								$data_cart = array(
									"id" 			=> (int)($cart->product_id_temp),
									"product_id" 	=> $cart->product_id,
									"name" 			=> $cart->title,
									"qty" 			=> $cart->qty,
									"price_sale" 	=> $cart->price_sale,
									"price" 		=> $cart->price,
									"key_i" 		=> $cart->key_i,
									"key_j" 		=> $cart->key_j,
									"typename" 		=> $cart->typename,
									"subtypename" 	=> $cart->subtypename,
									"thumbnail" 	=> $cart->thumbnail,
								);
								$this->cart->product_name_rules = '[:print:]';
								$this->cart->insert($data_cart);
							}
							$this->m_cart->delete(array("id" => $cart->id));
						}
						$carts_session = $this->cart->contents();
						foreach ($carts_session as $cart_session) {
							$data = array(
								"title" 			=> $cart_session['name'],
								"product_id" 		=> $cart_session['product_id'],
								"product_id_temp"	=> $cart_session['id'],
								"user_id" 			=> $this->session->user->id,
								"qty" 				=> $cart_session['qty'],
								"price_sale" 		=> $cart_session['price_sale'],
								"price" 			=> $cart_session['price'],
								"key_i" 			=> $cart_session['key_i'],
								"key_j" 			=> $cart_session['key_j'],
								"typename" 			=> $cart_session['typename'],
								"subtypename" 		=> $cart_session['subtypename'],
								"thumbnail" 		=> $cart_session['thumbnail'],
							);
							$this->m_cart->add($data);
						}

						if (empty($this->session->user->fullname) || empty($this->session->user->email) || empty($this->session->user->phone)) {
							$this->session->set_flashdata("error", "Vui lòng cập nhật thêm thông tin ở các trường có viền đỏ dưới đây.");
							redirect(site_url("tai-khoan/thong-tin-ca-nhan"), "back");
						} else {
							redirect($current_url,'back');
						}
					}
				}
				else {
					$this->session->set_flashdata("error",$this->website['error_login']);
					redirect(site_url("tai-khoan/dang-nhap"), "back");
				}
			}
		}
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->website['login']}" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));

		$view_data = array();
		$view_data['cart'] 			= $this->cart->contents();
		$view_data["menu"] 			= $this->menu;
		$view_data["website"] 		= $this->website;

		$tmpl_content = array();
		$tmpl_content["content"] 	= $this->load->view("account/signin", $view_data, true);
		$tmpl_content["title"] 		= 'Tài khoản';
		$this->load->view("layout/login", $tmpl_content);
	}

	public function affiliate()
	{
		if (!isset($_GET['page']) || (($_GET['page']) < 1)) {
			$page = 1;
		}
		else {
			$page = $_GET['page'];
		}
		$offset   = ($page - 1) * 10;

		$affiliate_analytic = $this->m_affiliate_analytic->load_item($this->session->userdata('user')->affiliate_code);
		$info = new stdClass();
		$info->affiliate_code = $this->session->userdata('user')->affiliate_code;
		$total_affiliates = count($this->m_affiliate->jion_booking_items($info));
		$affiliates = $this->m_affiliate->jion_booking_items($info, 10, $offset);
		$info->payment_status = 2;
		$info->a_payment_status = 2;
		$paymented = $this->m_affiliate->jion_booking_items($info);
		$info->payment_status = 2;
		$info->a_payment_status = 1;
		$payment = $this->m_affiliate->jion_booking_items($info);

		$url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$url = str_replace("?page={$page}", '?', $url);
		$url = str_replace("&page={$page}", '', $url);
		
		$pagination = $this->util->pagination($url, $total_affiliates, 10,$this->website['bill']);
		
		$view_data = array();
		$view_data["affiliate_analytic"] = $affiliate_analytic;
		$view_data["affiliates"] = $affiliates;
		$view_data["pagination"] = $pagination;
		$view_data["offset"] = $offset;
		$view_data["total_affiliates"] = $total_affiliates;
		$view_data["paymented"] = $paymented;
		$view_data["payment"] = $payment;

		$tmpl_content = array();
		$tmpl_content["content"] 	= $this->load->view("account/affiliate", $view_data, true);
		$tmpl_content["title"] 		= 'Affiliate';
		$this->load->view("layout/account", $tmpl_content);
	}

	public function dang_ky_tai_khoan()
	{
		if (!empty($this->session->userdata("user"))){
			redirect(site_url('tai-khoan/thong-tin-tai-khoan'),'back');
		}
		// $this->load->library('session');
		// if ($_SERVER['HTTP_REFERER'] != site_url('tai-khoan/dang-nhap')) {
		// 	$this->session->set_userdata('current_url', $_SERVER['HTTP_REFERER']);
		// }
		// $current_url = $this->session->userdata('current_url');
		$task = $this->input->post("task");
		if (!empty($task)) {
			$this->load->library('form_validation');

			$new_fullname			= $this->input->post("new_fullname");
			$new_email				= $this->input->post("new_email");
			$new_phone				= $this->input->post("new_phone");
			$new_password			= $this->input->post("new_password");
			$confirm_new_password	= $this->input->post("confirm_new_password");
			
			$count_email = 0;
			$count_phone = 0;
			$this->form_validation->set_rules('new_email', 'Email', 'valid_email');
			if ($this->form_validation->run() == FALSE){
				$count_email +=1;
			}

			if (preg_match('/^0(1\d{9}|1\d{8}|2\d{8}|3\d{8}|4\d{8}|5\d{8}|6\d{8}|7\d{8}|8\d{8}|9\d{8})$/', $new_phone) == 0){
				$count_phone +=1;
			}

			$data = new stdClass();
			$data->new_fullname			= $new_fullname;
			$data->new_email			= $new_email;
			$data->new_phone			= $new_phone;
			$data->new_password			= $new_password;
			$data->confirm_new_password	= $confirm_new_password;
			$this->session->set_flashdata("login", $data);

			if (empty($new_fullname)) {
				$this->session->set_flashdata("error", "Họ và Tên không được trống.");
				redirect(site_url("tai-khoan/dang-nhap"), "back");
			}
			else if (empty($new_email)) {
				$this->session->set_flashdata("error", "Email không được trống.");
				redirect(site_url("tai-khoan/dang-nhap"), "back");
			}
			else if (empty($new_phone)) {
				$this->session->set_flashdata("error", "Số điện thoại không được trống.");
				redirect(site_url("tai-khoan/dang-nhap"), "back");
			}
			else if ($count_email != 0)
			{
				$this->session->set_flashdata("error", "Email này không hợp lệ. Vui lòng nhập email khác.");
				redirect(site_url("tai-khoan/dang-nhap"), "back");
			}
			else if ($count_phone != 0)
			{
				$this->session->set_flashdata("error", "Số điện thoại không hợp lệ. Vui lòng nhập  khác.");
				redirect(site_url("tai-khoan/dang-nhap"), "back");
			}
			else if ($this->util->get_block_phone($new_phone))
			{
				$this->session->set_flashdata("error", "Số điện thoại này đã bị chặn.");
				redirect(site_url("tai-khoan/dang-nhap"), "back");
			}
			else if ($this->m_user->get_user_by_email($new_email)) {
				$this->session->set_flashdata("error", "Email này đã được đăng ký. Vui lòng nhập email khác.");
				redirect(site_url("tai-khoan/dang-nhap"), "back");
			}
			else if (strlen(trim($new_password)) < 6) {
				$this->session->set_flashdata("error", "Mật khẩu phải có ít nhất 6 ký tự.");
				redirect(site_url("tai-khoan/dang-nhap"), "back");
			}
			else {
				$affiliate_code = substr(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, rand(1,4)).$this->m_user->get_auto_incre().substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 9),0,10);
				$data = array(
					"fullname"			=> $new_fullname,
					"email"				=> $new_email,
					"password"			=> md5($new_password),
					"password_text"		=> $new_password,
					"phone"				=> $new_phone,
					"affiliate_code"	=> $affiliate_code 
				);
				$this->m_user->add($data);
				// Auto Login
				$info = new stdClass();
				$info->email 	= $new_email;
				$info->password = $new_password;

				$user = $this->m_user->user($info);

				if ($user != null) {
					$this->session->set_flashdata("info", "Đăng ký thành công, vui lòng đăng nhập lại");
					redirect(site_url("tai-khoan/dang-nhap"), "back");
				}
				else {
					$this->session->set_flashdata("error", "Tên đăng nhập hoặc mật khẩu không đúng.");
					redirect(site_url("tai-khoan/dang-nhap"), "back");
				}
			}

			if (empty($this->session->user->fullname) || empty($this->session->user->email) || empty($this->session->user->phone)) {
				redirect(site_url("tai-khoan/thong-tin-ca-nhan"), "back");
			} else {
				redirect(site_url("tai-khoan"), "back");
			}
		}
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->website['register_account']}" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));

		$view_data = array();
		// $view_data["breadcrumb"] 	= $this->_breadcrumbs;
		$view_data['cart'] 			= $this->cart->contents();
		$view_data["menu"] 			= $this->menu;
		$view_data["website"] 		= $this->website;

		$tmpl_content = array();
		$tmpl_content["content"] 	= $this->load->view("account/register", $view_data, true);
		$tmpl_content["title"] 		= 'Tài khoản';
		$this->load->view("layout/login", $tmpl_content);
	}

	public function dang_xuat()
	{
		$this->m_user->logout();
		redirect(site_url("tai-khoan"));
	}

	public function lay_lai_mat_khau()
	{
		$task = $this->input->post("task");
		if (!empty($task)) {
			if ($task == "getpass") {
				$email = $this->input->post("email");
				$recaptcha 	= $this->input->post('g-recaptcha-response');
				if (isset($recaptcha) && $recaptcha) {
					$user		= $this->m_user->get_user_by_email($email);
			
					if (!empty($user)) {
						$code_confirm = str_replace('=', '', base64_encode($user->email).'_'.md5(rand(0, 99).date('U')));

						$data = array('code_confirm' => $code_confirm);
						$where = array('id' => $user->id);
						$this->m_user->update($data, $where);

						$tpl_data = array(
								"FULLNAME"	=> $user->fullname,
								"EMAIL"		=> $user->email,
								"LINK"		=> site_url('quen-mat-khau/'.$code_confirm)
						);

						$message = $this->mail_tpl->forgot_password($tpl_data);

						$mail_data = array(
											"subject"		=> $this->website['forgot_your']." ".$this->website['password']." - ".SITE_NAME,
											"from_sender"	=> MAIL_INFO,
											"name_sender"	=> SITE_NAME,
											"to_receiver"	=> $user->email,
											"message"		=> $message
						);
						$this->mail->config($mail_data);
						$this->mail->sendmail();

						$this->session->set_flashdata("success", $this->website['note_login5']);
						redirect(site_url("tai-khoan/lay-lai-mat-khau"), "location");
					}
					else {
						$this->session->set_flashdata("error", $this->website['note_login6']);
						redirect(site_url("tai-khoan/lay-lai-mat-khau"), "back");
					}
				} else {
					$this->session->set_flashdata("error", $this->website['error_note10']);
					redirect(site_url("tai-khoan/lay-lai-mat-khau"), "back");
				}
			}
		}

		$view_data = array();
		$view_data["menu"] 			= $this->menu;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;
		$view_data["alias"] 		= $this->alias;
		$view_data['cart'] 			= $this->cart->contents();

		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("account/recover_password", $view_data, true);
		$this->load->view("layout/view", $tmpl_content);
	}

	public function doi_mat_khau()
	{
		$task = $this->input->post("task");
		if (!empty($task)) {
			if ($task == "save") {
				$password				= $this->input->post("password");
				$new_password			= $this->input->post("new_password");
				$confirm_new_password	= $this->input->post("confirm_new_password");

				$data = new stdClass();
				$data->password				= $password;
				$data->new_password			= $new_password;
				$data->confirm_new_password	= $confirm_new_password;
				$this->session->set_flashdata("login", $data);
				$user = $this->m_user->load($this->session->user->id);
				if (empty($password)) {
					$this->session->set_flashdata("error", $this->website['error_note4']);
					redirect(current_url(), "back");
				}
				else if (md5($password) != $user->password) {
					$this->session->set_flashdata("error", $this->website['error_note15']);
					redirect(current_url(), "back");
				}
				else if (strlen(trim($new_password)) < 6) {
					$this->session->set_flashdata("error", $this->website['error_note6']);
					redirect(current_url(), "back");
				}
				else {
					$data = array(
						"password"		=> md5($new_password),
						"password_text"	=> $new_password
					);
					$where = array(
						"id" => $this->session->user->id
					);
					$this->m_user->update($data, $where);

					// Re-Login
					$this->m_user->cp_login($this->session->user->id);

					$this->session->set_flashdata("success", $this->website['error_note14']);
					redirect(current_url(), "back");
				}
			}
		}

		$view_data = array();
		$view_data["menu"] 			= $this->menu;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;
		$view_data["alias"] 		= $this->alias;

		$tmpl_content = array();
		$tmpl_content["title"] = $this->website['change_password'];
		$tmpl_content["content"] = $this->load->view("account/change_password", $view_data, true);
		$this->load->view("layout/account", $tmpl_content);
	}

	public function thong_tin_tai_khoan()
	{
		$task = $this->input->post("task");
		if (!empty($task)) {
			if ($task == "save") {
				$fullname 		= $this->input->post("fullname");
				$gender			= $this->input->post("gender");
				$birthday		= $this->input->post("birthday");
				$phone			= $this->input->post("phone");
				$address		= $this->input->post("address");

				$data = array(
					"fullname"		=> $fullname,
					"gender"		=> $gender,
					"birthday"		=> date("Y-m-d", strtotime($birthday)),
					"phone"			=> $phone,
					"address"		=> $address,
				);

				$where = array("id" => $this->session->user->id);
				$this->m_user->update($data, $where);

				$this->session->set_flashdata("success", $this->website['success']);
				redirect(current_url());
			}
		}

		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("Thông Tin Cá Nhân" => site_url("{$this->util->slug($this->router->fetch_class())}/{$this->util->slug($this->router->fetch_method())}")));

		$view_data = array();
		// $view_data["breadcrumb"] = $this->_breadcrumbs;
		$view_data['user'] 			= $this->m_user->load($this->session->user->id);
		$view_data["menu"] 			= $this->menu;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;
		$view_data["alias"] 		= $this->alias;

		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("account/profile", $view_data, true);
		$tmpl_content["title"] = $this->website['profile'];
		$this->load->view("layout/account", $tmpl_content);
	}
	public function chi_tiet_mua_hang()
	{
		$booking_id		= $this->input->post("booking_id");

		$booking = $this->m_booking->load($booking_id);

		$info = new stdClass();
		$info->booking_id = $booking->id;
		$booking_details = $this->m_booking_detail->items($info);

		$view_data = array();
		$view_data['booking'] 			= $booking;
		$view_data['booking_details']	= $booking_details;

		echo $this->load->view('account/booking/booking_details', $view_data, true);
	}
	public function san_pham_yeu_thich()
	{
		$user = $this->session->userdata("user");
		$like_product = str_replace('"','',$user->like_product);
		$info = new stdClass();
		$items = array();
		if (!empty($like_product)) {
		$info->like_item = $like_product;
		$select = "
		p.id,
		p.title,
		p.title_en,
		p.alias,
		p.alias_en,
		p.content,
		p.content_en,
		p.category_id,
		p.code,
		p.rating_point,
		p.rating_cmt,
		p.meta_title,
		p.meta_key,
		p.meta_des,
		CONVERT(JSON_EXTRACT(t.price, '$[0]'), DECIMAL) as price,
		CONVERT(JSON_EXTRACT(t.sale, '$[0]'), DECIMAL) as sale";
		$items = $this->m_product->get_list_category_items($select, $info, 1);
		}

		$view_data = array();
		// $view_data["breadcrumb"] 	= $this->_breadcrumbs;
		$view_data['user'] 			= $user;
		$view_data['items'] 		= $items;
		$view_data["menu"] 			= $this->menu;
		$view_data["website"] 		= $this->website;
		$view_data["prop"] 			= $this->prop;
		$view_data["alias"] 		= $this->alias;

		$tmpl_content = array();
		$tmpl_content["content"] = $this->load->view("account/like_product", $view_data, true);
		$tmpl_content["title"] = $this->website['like_list'];
		$this->load->view("layout/account", $tmpl_content);
	}
}
