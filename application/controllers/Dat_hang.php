<?php
class Dat_hang extends CI_Controller{

	var $_breadcrumb = array();
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
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$this->website['check_out']}" => site_url($this->util->slug($this->router->fetch_class()))));

		$key = "";
		$paymentOk = false;
		
		// OnePay
		if (isset($_GET['vpc_TxnResponseCode']))
		{
			$vpc_Txn_Secure_Hash = $_GET["vpc_SecureHash"];
			$vpc_MerchTxnRef = $_GET["vpc_MerchTxnRef"];
			$vpc_AcqResponseCode = $_GET["vpc_AcqResponseCode"];
			unset($_GET["vpc_SecureHash"]);
			
			$key = $vpc_MerchTxnRef;
			
			// set a flag to indicate if hash has been validated
			$errorExists = false;
			
			if (strlen(OP_SECURE_SECRET) > 0 && $_GET["vpc_TxnResponseCode"] != "7" && $_GET["vpc_TxnResponseCode"] != "No Value Returned")
			{
			    ksort($_GET);
			    
			    $md5HashData = "";
			    
			    // sort all the incoming vpc response fields and leave out any with no value
			    foreach ($_GET as $k => $v) {
			        if ($k != "vpc_SecureHash" && (strlen($v) > 0) && ((substr($k, 0,4)=="vpc_") || (substr($k,0,5) =="user_"))) {
					    $md5HashData .= $k . "=" . $v . "&";
					}
			    }
			    
			    $md5HashData = rtrim($md5HashData, "&");
			
				if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*',OP_SECURE_SECRET)))) {
			        // Secure Hash validation succeeded, add a data field to be displayed later.
			        $hashValidated = "CORRECT";
			    } else {
			        // Secure Hash validation failed, add a data field to be displayed later.
			        $hashValidated = "INVALID HASH";
			    }
			} else {
			    // Secure Hash was not validated, add a data field to be displayed later.
			    $hashValidated = "INVALID HASH";
			}
			
			if($hashValidated=="CORRECT" && $_GET["vpc_TxnResponseCode"]=="0") {
				// Transaction successful
				$paymentOk = true;
			} else if ($hashValidated=="INVALID HASH" && $_GET["vpc_TxnResponseCode"]=="0") {
				// Transaction is pendding
				$paymentOk = false;
			} else {
				// Transaction is failed
				$paymentOk = false;
			}
		}

		if (!empty($key))
		{
			if ($paymentOk)
			{
				if (substr($key, 0 , 3) == "po_") {
					header('Location: '.BASE_URL."/thanh-toan-online/thanh-cong.html?key=".$key);
					die();
				} else {
					header('Location: '.BASE_URL."/dat-hang/thanh-cong.html?key=".$key);
					die();
				}
				
			}
			else
			{
				if (substr($key, 0 , 3) == "po_") {
					header('Location: '.BASE_URL."/thanh-toan-online/that-bai.html?key=".$key);
					die();
				} else {
					header('Location: '.BASE_URL."/dat-hang/that-bai.html?key=".$key);
				die();
				}
				
			}
		}

		//////////////////////////
	}
	public function index (){
		if (empty($this->cart->contents())) {
			redirect(site_url(""),'back');
		}
		$booking = $this->session->userdata("booking");
		$user_login = $this->session->userdata("user");
		if (!empty($user_login)) {
			$booking = new stdClass();
			$booking->fullname		= !empty($user_login->fullname)?$user_login->fullname:'';
			$booking->phone			= !empty($user_login->phone)?$user_login->phone:'';
			$booking->email			= !empty($user_login->email)?$user_login->email:'';
			$booking->address		= !empty($user_login->address)?$user_login->address:'';
			$booking->message		= '';
			$booking->total			= 0;
		}

		$view_data = array();
		$view_data["booking"] 		= $booking;
		$view_data["breadcrumb"] 	= $this->_breadcrumb;
		$view_data["menu"] 	= $this->menu;
		$view_data["website"] 	= $this->website;
		$view_data["prop"] 	= $this->prop;
		$view_data["alias"] 	= $this->alias;
		$view_data['items'] 		= $this->cart->contents();
		$this->load->view("booking/index", $view_data);
		
	}
	public function test_don_hang (){
		$tmpl_content = array();
		$tmpl_content['meta']['title'] = "Thanh toán phương thức  hoàn thành của ".SITE_NAME;
		$tmpl_content['meta']['description'] = "Thanh toán phương thức  hoàn thành của ".SITE_NAME.". Đơn hàng của bạn đã hoàn tất";
		$tmpl_content['title'] = "Hoàn tất mua hàng";
		$tmpl_content['content'] = $this->load->view("booking/completed", null, TRUE);
		$this->load->view('layout/view', $tmpl_content);
	}
	public function thong_tin_khach_hang (){
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Thông Tin Khách Hàng" => site_url("{$this->util->slug($this->router->fetch_class())}/dang-nhap")));

		$view_data = array();
		$view_data["breadcrumb"] 	= $this->_breadcrumb;

		$tmpl_content				= array();
		$tmpl_content['title'] 		= 'Thông Tin Khách Hàng';
		$tmpl_content['content'] 	= $this->load->view("booking/signin", $view_data, true);
		$this->load->view('layout/view', $tmpl_content);
	}
	public function dang_nhap()
	{
		$task = $this->input->post("task");
		if (!empty($task)) {
			if ($task == "login") {
				$uid = $this->input->post("username");
				$password = $this->input->post("password");

				$info = new stdClass();
				$info->email = $uid;
				$info->password = $password;
				$user = $this->m_user->user($info, 1,1);

				if ($user != null) {
					if ($this->m_user->login($uid, $password)) {
						$user_login = $this->session->userdata("user");
						$this->session->unset_userdata("booking");
						$booking = new stdClass();
						$booking->fullname		= !empty($user_login->fullname)?$user_login->fullname:'';
						$booking->phone			= !empty($user_login->phone)?$user_login->phone:'';
						$booking->email			= !empty($user_login->email)?$user_login->email:'';
						$booking->address		= !empty($user_login->address)?$user_login->address:'';
						$booking->message		= '';
						$booking->total			= 0;

						$this->session->set_userdata("booking", $booking);
						
						redirect(site_url("dat-hang"));
					}
				} else {
					$this->session->set_flashdata("error", "Tên đăng nhập hoặc mật khẩu không đúng.");
					redirect(site_url("dat-hang/thong-tin-khach-hang"), "back");
				}
			} else if ($task == "infomation") {

				$new_fullname			= $this->input->post("new_fullname");
				$new_email				= $this->input->post("new_email");
				$new_phone				= $this->input->post("new_phone");

				$count_email = 0;
				$count_phone = 0;
				$this->load->library('form_validation');
				$this->form_validation->set_rules('new_email', 'Email', 'valid_email');
				if ($this->form_validation->run() == FALSE){
					$count_email +=1;
				}

				if (preg_match('/^0(1\d{9}|1\d{8}|2\d{8}|3\d{8}|4\d{8}|5\d{8}|6\d{8}|7\d{8}|8\d{8}|9\d{8})$/', $new_phone) == 0){
					$count_phone +=1;
				}
				if (empty($new_fullname)) {
					$this->session->set_flashdata("error", "Họ và Tên không được trống.");
					redirect(site_url("dat-hang/thong-tin-khach-hang"), "back");
				}
				else if (empty($new_email)) {
					$this->session->set_flashdata("error", "Email không được trống.");
					redirect(site_url("dat-hang/thong-tin-khach-hang"), "back");
				}
				else if (empty($new_phone)) {
					$this->session->set_flashdata("error", "Số điện thoại không được trống.");
					redirect(site_url("dat-hang/thong-tin-khach-hang"), "back");
				}
				else if ($this->util->get_block_phone($new_phone))
				{
					$this->session->set_flashdata("error", "Số điện thoại này đã bị chặn.");
					redirect(site_url("tai-khoan/dang-nhap"), "back");
				}
				else if ($count_email != 0)
				{
					$this->session->set_flashdata("error", "Email này không hợp lệ. Vui lòng nhập email khác.");
					redirect(site_url("dat-hang/thong-tin-khach-hang"), "back");
				}
				else if ($count_phone != 0)
				{
					$this->session->set_flashdata("error", "Số điện thoại không hợp lệ. Vui lòng nhập  khác.");
					redirect(site_url("dat-hang/thong-tin-khach-hang"), "back");
				}
				else {
					$this->session->unset_userdata("booking");
					$booking = new stdClass();
					$booking->fullname		= $this->input->post("new_fullname");
					$booking->phone			= $this->input->post("new_phone");
					$booking->email			= $this->input->post("new_email");
					$booking->address		= '';
					$booking->message		= '';
					$booking->total			= 0;

					$this->session->set_userdata("booking", $booking);
					redirect(site_url("dat-hang"));
				}
			}
		}
	}
	public function thanh_toan () {

		$fullname 		= $this->input->post('fullname');
		$phone 			= $this->input->post('phone');
		$email 			= $this->input->post('email');
		$address 		= $this->input->post('address');
		$payment 		= $this->input->post('payment');
		$note 			= $this->input->post('note');
		$promotion 		= $this->input->post('promotion');
		// $recaptcha 		= $this->input->post('g-recaptcha-response');
		if ($this->util->get_block_phone($phone))
		{
			$this->session->set_flashdata("error", "Số điện thoại này đã bị chặn.");
			redirect(site_url("gio-hang"), "back");
		}
		if (empty($phone)||empty($fullname)||empty($email)||empty($address)) {
			redirect(BASE_URL);
		}
		
		$code_promotion = '';
		$item = $this->m_promotion->load($promotion);
		$promotion_item = null;
		if (!empty($item)){
			if ($this->session->userdata('user')->user_rank == $item->user_rank){
				if (!empty($item->limit_time)){
					if (date('Y-m-d',strtotime($item->fromdate ))<= date('Y-m-d') && date('Y-m-d',strtotime($item->todate ))>= date('Y-m-d')){
						$promotion_item = $item;
					}
				} else {
					$promotion_item = $item;
				}
			}
		}
		/////////////////////////////////////////////////////////////////
		$message = !empty($note)?$note:$this->input->post('message');
		$carts 			= $this->cart->contents();
	
		if (empty($carts)) {
			redirect(BASE_URL);
		}

		$total 	= 0;
		$qty = 0;
		$cost = 0;

		// Booking key
		$key = md5(time());

		$user_id = 0;
		$user_add = array();
		if (empty($this->session->userdata('user'))){
			if (empty($this->m_user->get_user_by_email($email)) && empty($this->m_user->get_user_by_phone($phone))){
				$pw = $this->util->randomPassword();
				$affiliate_code = substr(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, rand(1,4)).$this->m_user->get_auto_incre().substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 9),0,10);
				$data = array(
					"fullname"			=> $fullname,
					"email"				=> $email,
					"password"			=> md5($pw),
					"password_text"		=> $pw,
					"affiliate_code"	=> $affiliate_code,
					"phone"				=> $phone,
				);
				$user_id = $this->m_user->get_auto_incre();
				$this->m_user->add($data);
				$user_add = $data;
			} else if (!empty($this->m_user->get_user_by_email($email)) && !empty($this->m_user->get_user_by_phone($phone))){
				$user_id = $this->m_user->get_user_email($email)->id;
			}
		}

		$data = array(
			"booking_key"	=> $key,
			"fullname"		=> $fullname,
			"phone"			=> $phone,
			"email"			=> $email,
			"code_promotion"=> $code_promotion,
			"address"		=> $address,
			"message"		=> $message,
			"payment"		=> $payment,
			"total"			=> $this->util->round_number($total,1000),
			"cost"			=> $this->util->round_number($cost,1000),
			"user_id"		=> !empty($this->session->user->id) ? $this->session->user->id : $user_id,
		);
		$this->m_booking->add($data);
		$booking = $this->m_booking->booking($key);

		$total_sale_price = 0;
		$total_sale_product_price = 0;
		$total_price = 0;
		foreach ($carts as $cart) {
			$total 	+= $cart['qty']*$cart['price_sale'];
			$cost 	+= $cart['qty']*$cart['cost'];
			$total_price += $cart['qty']*$cart['price'];
			$qty 	+= $cart['qty'];
			$data_detail = array(
				"booking_id"		=> $booking->id,
				"title"				=> $cart['name'],
				"product_id_temp"	=> $cart['id'],
				"product_id"		=> $cart['product_id'],
				"qty"				=> $cart['qty'],
				"price_sale"		=> $this->util->round_number($cart['price_sale'],1000),
				"price"				=> $this->util->round_number($cart['price'],1000),
				"key_i"				=> $cart['key_i'],
				"key_j"				=> $cart['key_j'],
				"typename"			=> $cart['typename'],
				"subtypename"		=> $cart['subtypename'],
				"user_id"			=> !empty($this->session->user->id) ? $this->session->user->id : 0,
				"thumbnail"			=> $cart['thumbnail'],
			);
			$this->m_booking_detail->add($data_detail);
			//
			$key_i = $cart['key_i'];
			$key_j = $cart['key_j'];
			$info = new stdClass();
			$info->product_id = $cart['product_id'];
			$product_type = $this->m_product_type->items($info);
			$product_type_temp = $product_type[0];
			if ($key_i != -1) {
				$product_type_temp = $product_type[$key_i];
				$quantity = json_decode($product_type_temp->quantity);
				$quantity[0] = (string)(json_decode($product_type_temp->quantity)[0] - $cart['qty']);
			}
			if ($key_j != -1) {
				$quantity = json_decode($product_type_temp->quantity);
				$quantity[$key_j] = (string)(json_decode($product_type_temp->quantity)[$key_j] - $cart['qty']);
			}
			
			$data_product_type = array(
				"quantity" => json_encode($quantity),
			);
			$this->m_product_type->update($data_product_type,array('id'=>$product_type_temp->id));
			//
			if (!empty($promotion_item)){
				$sale_value = $promotion_item->sale_value;
				if ($cart['sale'] != 0){
					$total_sale_product_price += ($cart['qty']*$cart['cost'])*$cart['sale']*0.01;
				}
				if ($cart['sale']>$sale_value){
					$sale_value = $cart['sale'];
				}
				if ($promotion_item->sale_type == 0){
					$total_sale_price += ($cart['qty']*$cart['cost'])*$promotion_item->sale_value*0.01;
				} else {
					$total_sale_price = $sale_value;
				}
			}
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
			$total = $total_price - $total_sale_price;
			$code_promotion = $promotion;
		}

		$phone_exist_affiliate = $this->m_user->phone_exist_affiliate($phone);
		if (!empty($_COOKIE['af'])) {
			$data = array(
				"affiliate_code"	=> $_COOKIE['af'],
				"booking_id"		=> $booking->id,
			);
			$this->m_affiliate->add($data);
			if (empty($phone_exist_affiliate)) {
				$user = $this->m_user->load_item($_COOKIE['af']);
				if(!empty($user) && strpos($user->affiliate_partner,$phone) == false){
					$this->m_user->update(array("affiliate_partner"=>$user->affiliate_partner.','.$phone),array("id"=>$user->id));
				}
			}
		} else {
			if (!empty($phone_exist_affiliate)) {
				$data = array(
					"affiliate_code"	=> $phone_exist_affiliate->affiliate_code,
					"booking_id"		=> $booking->id,
				);
				$this->m_affiliate->add($data);
			}
		}

		if(!empty($this->session->user->id)) {
			$info = new stdClass();
			$info->user_id = $this->session->user->id;
			$carts = $this->m_cart->items($info);
			foreach($carts as $cart) {
				$this->m_cart->delete(array("id"=>$cart->id));
			}
		}
		$this->cart->destroy();
		///////////////////////////////////////////////////////////////////
		
		$info = new stdClass();
		$info->booking_id = $booking->id;
		$booking_paxs = $this->m_booking_detail->items($info);
		if ($booking != null)
		{
			// if ($payment == 'Onepay')
			// {
			// 	//Redirect to OnePay
			// 	$vpcURL = OP_PAYMENT_URL;
				
			// 	$vpcOpt['Title']				= "Thánh toán sản phẩm Lego ".SITE_NAME;
			// 	$vpcOpt['AgainLink']			= urlencode($_SERVER['HTTP_REFERER']);
			// 	$vpcOpt['vpc_Merchant']			= OP_MERCHANT;
			// 	$vpcOpt['vpc_AccessCode']		= OP_ACCESSCODE;
			// 	$vpcOpt['vpc_MerchTxnRef']		= $key;
			// 	$vpcOpt['vpc_OrderInfo']		= '';
			// 	$vpcOpt['vpc_Amount']			= $total*100;
			// 	// $vpcOpt['vpc_Amount']			= 1000*100;
			// 	$vpcOpt['vpc_ReturnURL']		= OP_RETURN_URL;
			// 	$vpcOpt['vpc_Version']			= "2";
			// 	$vpcOpt['vpc_Command']			= "pay";
			// 	$vpcOpt['vpc_Locale']			= "vn";
			// 	$vpcOpt['vpc_TicketNo']			= $this->util->realIP();
			// 	$vpcOpt['vpc_Customer_Email']	= $email;
			// 	$vpcOpt['vpc_Customer_Id']		= !empty($this->session->user->id) ? $this->session->user->id : 0;
				
			// 	$md5HashData = "";
				
			// 	ksort($vpcOpt);
				
			// 	$appendAmp = 0;
				
			// 	foreach($vpcOpt as $k => $v) {
			// 		// create the md5 input and URL leaving out any fields that have no value
			// 		if (strlen($v) > 0) {
			// 			// this ensures the first paramter of the URL is preceded by the '?' char
			// 			if ($appendAmp == 0) {
			// 				$vpcURL .= urlencode($k) . '=' . urlencode($v);
			// 				$appendAmp = 1;
			// 			} else {
			// 				$vpcURL .= '&' . urlencode($k) . "=" . urlencode($v);
			// 			}
			// 			if ((strlen($v) > 0) && ((substr($k, 0,4)=="vpc_") || (substr($k,0,5) =="user_"))) {
			// 				$md5HashData .= $k . "=" . $v . "&";
			// 			}
			// 		}
			// 	}
				
			// 	$md5HashData = rtrim($md5HashData, "&");
			// 	$md5HashData = strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*',OP_SECURE_SECRET)));
				
			// 	$vpcURL .= "&vpc_SecureHash=" . $md5HashData;
			// 	header("Location: ".$vpcURL);
			// 	die();
			// }
			$this->thanh_cong($key,$user_add);
		}
		else {
			redirect(BASE_URL);
			die();
		}

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Hoàn tất mua hàng" => '#'));
		$client_name = $booking->fullname;
		$view_data = array();
		$view_data["booking"] 				= $booking;
		$view_data["booking_paxs"] 			= $booking_paxs;
		$view_data["pay_method"] 			= $booking->payment;
		$view_data["phone"] 				= $booking->phone;
		$view_data["pay_status"] 			= 1;
		$view_data["client_name"] 			= $client_name;
		$view_data["breadcrumb"] 			= $this->_breadcrumb;
		$view_data["transaction_id"]		= BOOKING_PREFIX.$booking->id;
		// $view_data["transaction_fee"]		= $total;

		$view_data["transaction_sku"]		= $booking->id;
		$view_data["menu"] 	= $this->menu;
		$view_data["website"] 	= $this->website;
		$view_data["prop"] 	= $this->prop;
		$view_data["alias"] 	= $this->alias;
		// $view_data["transaction_name"]		= $fullname;
		// $view_data["transaction_category"]	= $payment;
		// $view_data["transaction_quantity"]	= $qty;
		
		// $tmpl_content = array();
		// $tmpl_content['meta']['title'] = "Thanh toán phương thức (".$payment.") hoàn thành của ".SITE_NAME;
		// $tmpl_content['meta']['description'] = "Thanh toán phương thức (".$payment.") hoàn thành của ".SITE_NAME.". Đơn hàng của bạn đã hoàn tất";
		// $tmpl_content['title'] = "Hoàn tất mua hàng";
		$this->load->view("booking/completed", $view_data);
	}
	public function huy_don($key) {
		$booking = $this->m_booking->booking($key);
		$user = $this->session->userdata('user');
		if (!empty($user) && ($user->phone == $booking->phone)) { 
			if (!empty($booking)) {
				if ($booking->status == 1) {
					$info = new stdClass();
					$info->booking_id = $booking->id;
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
					$this->m_booking->delete(array("id"=>$booking->id));
				}
			}
			redirect(site_url("tai-khoan/lich-su-don-hang"),'back');
		} else {
			redirect(site_url('error404'),'back');
		}
	}
	public function thanh_toan_lai($key) {
		$booking = $this->m_booking->booking($key);
		$this->cart->destroy();
		$info = new stdClass();
		$info->booking_id = $booking->id;
		$booking_detail = $this->m_booking_detail->items($info);
		$close_product = '';
		foreach($booking_detail as $detail) {

			$typename 		= $detail->typename;
			$subtypename 	= $detail->subtypename;
			$quantity 		= $detail->qty;

			$item = $this->m_product->load($detail->product_id);
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
			if (($quantity > $quantity_item)) {
				$quantity = 1;
			}
			$product_id = (int)($product_id);
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
			if (($quantity <= $quantity_item)) {
				$this->cart->product_name_rules = '[:print:]';
				$this->cart->insert($data);
			} else {
				$title = '<div style="font-size:14px">'.$item->title;
				if(!empty($typename)){
					$title .= ' - '.$typename;
				}
				if(!empty($subtypename)){
					$title .= ' - '.$subtypename;
				}
				$title .= '</div>';
				$close_product .= $title;
			}
		}
		if (!empty($close_product)){
			$this->session->set_flashdata("error", $close_product.'<span style="color:red;font-size:14px">=> Đã hết hàng</span>');
		}
		redirect(site_url("gio-hang"),'back');
	}
	public function thanh_toan_lai_api($key) {
		$fullname 		= $this->input->post('fullname');
		$phone 			= $this->input->post('phone');
		$phone_temp 	= $this->input->post('phone_temp');
		$email 			= $this->input->post('email');
		$address 		= $this->input->post('address');
		$payment 		= $this->input->post('payment');
		$note 			= $this->input->post('note');
		$recaptcha 		= $this->input->post('g-recaptcha-response');

		$message = !empty($note)?$note:$this->input->post('message');
		
		$booking = $this->m_booking->booking($key);
		if (!isset($recaptcha)) {
			redirect(BASE_URL);
		}

		if (empty($booking)) {
			redirect(BASE_URL);
		}
		
		$data = array(
			"fullname"		=> $fullname,
			"phone"			=> $phone,
			"phone_temp"	=> $phone_temp,
			"email"			=> $email,
			"address"		=> $address,
			"message"		=> $message,
			"payment"		=> $payment,
			"active"		=> 0,
		);
		$this->m_booking->update($data,array("id"=>$booking->id));


		$this->thanh_cong($key);

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Hoàn tất mua hàng" => '#'));
		$client_name = $booking->fullname;
		$view_data = array();
		$view_data["pay_method"] 			= $booking->payment;
		$view_data["pay_status"] 			= 1;
		$view_data["client_name"] 			= $client_name;
		$view_data["breadcrumb"] 			= $this->_breadcrumb;
		$view_data["transaction_id"]		= BOOKING_PREFIX.$booking->id;
		$view_data["transaction_fee"]		= $booking->total;

		$view_data["transaction_sku"]		= $booking->id;
		$view_data["transaction_name"]		= $fullname;
		$view_data["transaction_category"]	= $payment;
		$view_data["transaction_quantity"]	= 1;
		
		$tmpl_content = array();
		$tmpl_content['meta']['title'] = "Thanh toán phương thức (".$payment.") hoàn thành của ".SITE_NAME;
		$tmpl_content['meta']['description'] = "Thanh toán phương thức (".$payment.") hoàn thành của ".SITE_NAME.". Đơn hàng của bạn đã hoàn tất";
		$tmpl_content['title'] = "Hoàn tất mua hàng";
		$tmpl_content['content'] = $this->load->view("booking/completed", $view_data, TRUE);
		$this->load->view('layout/view', $tmpl_content);
	}

	// function finish()
	// {
	// 	redirect(BASE_URL);
	// }
	
	// function paypal_success($key="")
	// {
	// 	$this->thanh_cong($key);
	// }
	
	// function paypal_failure($key="")
	// {
	// 	$this->that_bai($key);
	// }

	function thanh_cong($key="",$user_add=array())
	{
		if (empty($key)) {
			$key = !empty($_GET["key"]) ? $_GET["key"] : "";
		}
		
		if (!empty($key)) {
			$key = str_ireplace(".html", "", $key);
		}
		
		// Redirect if this booking is not found or succed
		$booking = $this->m_booking->booking($key);

		if ($booking == null || $booking->active) {
			redirect(BASE_URL);
			die();
		}
		// 
		$data  = array(
			'active' => 1
		);
		$where = array('booking_key' => $key );
		
		$this->m_booking->update($data, $where);
		if ($booking != null)
		{
			// Send mail
			$tpl_data = $this->mail_tpl->booking_data($booking);
			$tpl_data["USERNAME"] = '';
			$tpl_data["PASSWORD"] = '';	
			if (!empty($user_add)){
				$tpl_data["USERNAME"] = $user_add['email'];
				$tpl_data["PASSWORD"] = $user_add['password_text'];
			}
			
			$subject = "ĐƠn hàng #".BOOKING_PREFIX.$booking->id.": đã đặt hàng thành công với phương thức (".$booking->payment.")";
			$message  = $this->mail_tpl->booking($tpl_data);
			// Send to SALE Department
			$mail = array(
				"subject"		=> $subject." - ".$booking->fullname,
				"from_sender"	=> $booking->email,
				"name_sender"	=> $booking->fullname,
				"to_receiver"	=> MAIL_INFO,
				"message"		=> $message
			);
			$this->mail->config($mail);
			$this->mail->sendmail();
			
			// Send confirmation to SENDER
			$mail = array(
				"subject"		=> $subject,
				"from_sender"	=> MAIL_INFO,
				"name_sender"	=> SITE_NAME,
				"to_receiver"	=> $booking->email,
				"message"		=> $message
			);
			$this->mail->config($mail);
			$this->mail->sendmail();
			
		}
		else {
			redirect(BASE_URL);
			die();
		}
		
		// $this->_breadcrumb = array_merge($this->_breadcrumb, array("Thanh toán thành công" => '#'));
		
		// $view_data = array();
		// $view_data["pay_method"] 			= $booking->payment;
		// $view_data["pay_status"] 			= 1;
		// $view_data["client_name"] 			= $booking->fullname;
		// $view_data["breadcrumb"] 			= $this->_breadcrumb;
		// $view_data["transaction_id"]		= BOOKING_PREFIX.$booking->id;
		// $view_data["transaction_fee"]		= $booking->total;

		// $view_data["transaction_sku"]		= $booking->id;
		// $view_data["transaction_name"]		= $booking->fullname;
		// $view_data["transaction_category"]	= $booking->payment;
		// $view_data["transaction_quantity"]	= 1;
		
		// $tmpl_content = array();
		// $tmpl_content['meta']['title'] = "Thanh toán phương thức (".$booking->payment.") hoàn thành của ".SITE_NAME;
		// $tmpl_content['meta']['description'] = "Thanh toán phương thức (".$booking->payment.") hoàn thành của ".SITE_NAME.". Đơn hàng của bạn đã hoàn tất";
		// $tmpl_content['title'] = "Hoàn tất mua hàng";
		// $tmpl_content['content'] = $this->load->view("booking/completed", $view_data, TRUE);
		// $this->load->view('layout/view', $tmpl_content);
	}
	
	function that_bai($key="")
	{
		if (empty($key)) {
			$key = !empty($_GET["key"]) ? $_GET["key"] : "";
		}
		
		if (!empty($key)) {
			$key = str_ireplace(".html", "", $key);
		}
		
		// Redirect if this booking is not found or succed
		$booking = $this->m_booking->booking($key);
		if ($booking == null || $booking->active) {
			redirect(BASE_URL);
			die();
		}
		
		$data  = array(
			'active' 			=> 1,
			'payment_status' 	=> 1,
			'paid_date' => date($this->config->item("log_date_format"))
		);
		$where = array( 'booking_key' => $key );
		
		$this->m_booking->update($data, $where);
		if ($booking != null)
		{
			$user = $this->m_user->load($booking->user_id);
			// Send mail
			$tpl_data = $this->mail_tpl->book_data($booking);
			
			
			$subject = "Thanh toán thất bại - Đơn hàng #".BOOKING_PREFIX.$booking->id." với phương thức (".$booking->payment.")";
			$tpl_data['PAY_STATUS'] = 1;
			$message  = $this->mail_tpl->booking($tpl_data);
			
			// Send to SALE Department
			$mail = array(
				"subject"		=> $subject." - ".$booking->fullname,
				"from_sender"	=> $booking->email,
				"name_sender"	=> $user->fullname,
				"to_receiver"	=> MAIL_INFO,
				"message"		=> $message
			);
			$this->mail->config($mail);
			$this->mail->sendmail();
			
			// Send confirmation to SENDER
			$mail = array(
				"subject"		=> $subject,
				"from_sender"	=> MAIL_INFO,
				"name_sender"	=> SITE_NAME,
				"to_receiver"	=> $booking->email,
				"message"		=> $message
			);
			$this->mail->config($mail);
			$this->mail->sendmail();
		}
		else {
			redirect(BASE_URL);
			die();
		}
		
		// $this->_breadcrumb = array_merge($this->_breadcrumb, array("Thanh toán thất bại" => '#'));
		
		// $view_data = array();
		// $view_data["pay_method"] 			= $booking->payment;
		// $view_data["pay_status"] 			= $tpl_data['PAY_STATUS'];
		// $view_data["client_name"] 			= $booking->fullname;
		// $view_data["breadcrumb"] 			= $this->_breadcrumb;
		// $view_data["transaction_id"]		= BOOKING_PREFIX.$booking->id;
		// $view_data["transaction_fee"]		= $booking->total;
		// $view_data["transaction_sku"]		= $booking->id;
		// $view_data["transaction_name"]		= $booking->fullname;
		// $view_data["transaction_category"]	= $booking->payment;
		// $view_data["transaction_quantity"]	= count($tpl_data['PAXS']);
		
		// $tmpl_content = array();
		// $tmpl_content['meta']['title'] = "Thanh toán phương thức (".$booking->payment.") hoàn thành của ".SITE_NAME;
		// $tmpl_content['meta']['description'] = "Thanh toán phương thức (".$booking->payment.") hoàn thành của ".SITE_NAME.". Đơn hàng của bạn đã hoàn tất";
		// $tmpl_content['title'] = "Thanh toán";
		// $tmpl_content['content'] = $this->load->view("booking/completed", $view_data, TRUE);
		// $this->load->view('layout/view', $tmpl_content);
	}
}