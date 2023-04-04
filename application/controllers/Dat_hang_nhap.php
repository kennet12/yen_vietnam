<?php
class Dat_hang_nhap extends CI_Controller{
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
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$this->menu['home']}" => site_url()));
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$this->website['check_out']}" => site_url($this->util->slug($this->router->fetch_class()))));
		//////////////////////////
	}
	public function index () {

        $info = new stdClass();
		$info->order_product = 2;
		$select = "
		p.id,
		p.title,
		p.title_en,
		p.alias,
		p.alias_en,
		p.content,
		p.content_en,
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
		$suggest_products = $this->m_product->get_list_category_items($select,$info, 1, 12,0,'RAND()','');

		$view_data = array();
		$view_data["suggest_products"] 	= $suggest_products;
        $view_data["menu"] 	= $this->menu;
		$view_data["website"] 	= $this->website;
		$view_data["prop"] 	= $this->prop;
		$view_data["alias"] 	= $this->alias;
		$view_data['breadcrumb'] 	= $this->_breadcrumb;

		$tmpl_content = array();
		$tmpl_content["title"] = $this->menu['contact'];
		$tmpl_content["content"] = $this->load->view("order/index", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
		
	}
    public function gui() {
        $fullname = $this->input->post('fullname');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
        $address = $this->input->post('address');
        $message = $this->input->post('message');
        $url = $this->input->post('url');
		$recaptcha = $this->input->post('g-recaptcha-response');
        if (empty($fullname)||
			empty($recaptcha)||
            empty($email)||
            empty($phone)||
            empty($url)||
            empty($address)) {
            redirect(site_url('dat-hang-nhap'),'back');
        }

        $order_id = $this->m_order->get_auto_incre();

        $key = md5($order_id.time());
        
        $name = $this->input->post('name');
        $quantity = $this->input->post('quantity');
        $url_img = $this->input->post('url_img');
		$stt = $this->input->post('stt');
        $i=0;
        $path = "./images/order/{$order_id}/";
		if (!file_exists($path)) {
			mkdir($path, 0775, true);
		}
		$config = array(
			"upload_path"	=> $path,
			"allowed_types"	=> "jpg|jpeg|png|JPG|PNG|JPEG",
			"max_size"		=> 2000,
			"overwrite"		=> true
		);
		$this->load->library("upload", $config);
        foreach($quantity as $value) {
			$photos = array();
			$thumbnail = '';
			if (!empty($_FILES["thumbnail_{$stt[$i]}"]["name"])) {
				if (!$this->upload->do_upload("thumbnail_{$stt[$i]}")) { 
					$this->session->set_flashdata('error',$this->upload->display_errors());
					redirect(current_url());
				}
				else {
					$image_data = $this->upload->data();
					$file_name = explode('.',$image_data['orig_name']);
					rename($image_data['full_path'],$image_data['file_path'].$this->util->slug($file_name[0]).'.'.$file_name[1]);
					$thumbnail = BASE_URL."/images/order/{$order_id}/".$this->util->slug($file_name[0]).'.'.$file_name[1];
				}
			}
			
            $data_detail = array(
                'url' => $url[$i],
                'order_id' => $order_id,
                'title' => $name[$i],
                'quantity' => !empty($value)?$value:1,
                'thumbnail' => !empty($url_img[$i])?$url_img[$i]:$thumbnail,
            );
            $this->m_order_detail->add($data_detail);
            $i++;
        }
        $data = array(
            'order_key' => $key,
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'message' => $message,
        );
        $this->m_order->add($data);
        redirect(site_url("dat-hang-nhap/hoan-thanh/{$key}"),'back');
	}
    public function hoan_thanh($key) {
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("{$this->website['mes_add_to_cart']}" => site_url($this->util->slug($this->router->fetch_class()))));
        $order = $this->m_order->load_key($key);
        $info = new stdClass();
        $info->order_id = $order->id;
        $order_detail = $this->m_order_detail->items($info);

		$view_data = array();
		$view_data['breadcrumb'] 	= $this->_breadcrumb;
        $view_data["order"] 	= $order;
		$view_data["order_detail"] 	= $order_detail;
        $view_data["menu"] 	= $this->menu;
		$view_data["website"] 	= $this->website;
		$view_data["prop"] 	= $this->prop;
		$view_data["alias"] 	= $this->alias;
		
		$tmpl_content = array();
		$tmpl_content["title"] = $this->menu['contact'];
		$tmpl_content["content"] = $this->load->view("order/completed", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
	}
}