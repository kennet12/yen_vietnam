<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lien_he extends CI_Controller {
	var $_breadcrumbs = array();
	var $_lang = '';
	var $menu = array();
	var $website = array();

	function __construct()
	{
		parent::__construct();
		$this->_lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
		$this->lang->load('content',$this->_lang);
		$this->menu			= $this->lang->line('menu');
		$this->website		= $this->lang->line('website');
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->menu['home']}" => site_url()));
		$this->_breadcrumbs = array_merge($this->_breadcrumbs, array("{$this->menu['contact']}" => site_url($this->util->slug($this->router->fetch_class()))));
	}

	public function index ()
	{
		// var_dump('D:/Xampp/htdocs/www.fruit.vn/files/upload/duyanh');
		// rename('D:/Xampp/htdocs/www.fruit.vn/files/upload/duyanh','D:/Xampp/htdocs/www.fruit.vn/files/upload/thiennhi');
		// foreach($products as $product) {
		// 	$this->m_product->update(array('list_category'=>$product->category_id),array('id'=>$product->id));
		// }
		// var_dump($products);
		// setcookie("affiliate_code", "TNTN", time() + (10 * 365 * 24 * 60 * 60));
		// if (!empty($this->session->userdata('user')) && $this->session->userdata('user')->email=='nevermorepda@gmail.com')
		// var_dump($_COOKIE['affiliate_code']);
		// $products = $this->m_product->items();
		// foreach($products as $product) {
		// 	$cate_id = json_decode($product->category_id);
		// 	$cate = $this->m_product_category->load(end($cate_id));
		// 	$data = array(
		// 		"meta_title" => trim($product->title),
		// 		"meta_key" => trim($product->title).', '.$cate->name,
		// 		"meta_des" => 'Mua online '.trim($product->title).' tại '.SITE_NAME.' miễn phí vận chuyển, hoàn tiền 110% nếu sản phẩm kém chất lượng.',
		// 		"meta_title_en" => trim($product->title_en),
		// 		"meta_key_en" => trim($product->title_en).', '.$cate->name_en,
		// 		"meta_des_en" => 'Buy online '.trim($product->title_en).' from '.SITE_NAME.' free ship, refun money 110% if the product is of poor quality.',
		// 	);
		// 	$this->m_product->update($data,array('id'=>$product->id));
		// }
		// $this->util->codeOTP('0859322224','phan duy anh');
		//
		// $this->util->post_data(
		// 	'https://globalapi.nxcloud.com/mtsend',
		// 	'"appkey":"p16GQDjX","secretkey":"957Y50eY","phone":"0859322224","content":"12345 la ma xac thuc cua ban"',
		// 	'_gcl_aw=GCL.1623988349.Cj0KCQjw5auGBhDEARIsAFyNm9Ee1s9Ll3GUpHmBa6B-i-e-ddbOywaFAlTDgqgKaFjTfIkQa9KPPuwaApO8EALw_wcB; _gcl_au=1.1.2110535465.1623988349'
		// );
		$view_data = array();
		$view_data['breadcrumb'] 	= $this->_breadcrumbs;
		$view_data['breadcrumbs'] 	= $this->_breadcrumbs;
		$view_data["menu"] 			= $this->menu;
		$view_data["website"] 			= $this->website;

		$tmpl_content = array();
		$tmpl_content["title"] = $this->menu['contact'];
		$tmpl_content["content"] = $this->load->view("contact/index", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
	}
	public function ajax_contact () {
		$name 		= $this->input->post('name');
		$phone 		= $this->input->post('phone');
		$email 		= $this->input->post('email');
		$content 	= nl2br($this->input->post('content'));
		$recaptcha 	= $this->input->post('g-recaptcha-response');

		$result = FALSE;

		if (isset($recaptcha) && $recaptcha) {
			if((!is_null($name)) 
				&& (!is_null($phone))
				&& (!is_null($email))) {
				$data = array (
					'name'		 => $name,
					'phone'		 => $phone,
					'email'		 => $email,
					'content'	 => $content
					);
				$result = $this->m_contact->add($data);
			}
		}
		if ($result) {
			$tpl_data = array(
				"FULLNAME"	=> $name,
				"EMAIL"		=> $email,
				"PHONE"		=> $phone,
				"CONTENT"	=> $content,
			);

			$message = $this->mail_tpl->contact_admin($tpl_data);

			$mail_data = array(
				"subject"		=> "{$this->website['support']} - ".SITE_NAME,
				"from_sender"	=> $email,
				"name_sender"	=> SITE_NAME,
				"to_receiver"	=> MAIL_INFO,
				"message"		=> $message
			);
			$this->mail->config($mail_data);
			$this->mail->sendmail();

			$message = $this->mail_tpl->contact($tpl_data);

			$mail_data = array(
				"subject"		=> "{$this->website['support']} - ".SITE_NAME,
				"from_sender"	=> MAIL_INFO,
				"name_sender"	=> SITE_NAME,
				"to_receiver"	=> $email,
				"message"		=> $message
			);
			$this->mail->config($mail_data);
			$this->mail->sendmail();

			$this->session->set_flashdata("info", "{$this->website['success']}");
			redirect(site_url("lien-he"), "back");
		} else {
			$this->session->set_flashdata("error", "{$this->website['error']}");
			redirect(site_url("lien-he"), "back");
		}
	}
}
?>