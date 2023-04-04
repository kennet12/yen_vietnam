<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tra_cuu_don_hang extends CI_Controller {
	var $_breadcrumb = array();
    var $website = array();

	function __construct()
	{
		parent::__construct();

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Quy chế" => site_url($this->util->slug($this->router->fetch_class()))));
	}
	public function index($type, $data) {
        $this->website		= $this->lang->line('website');
        if(!empty($type) && !empty($data)){
            if ($type == 'ma-don') {
                if (!isset($_GET['trang']) || (($_GET['trang']) < 1)) {
                    $page = 1;
                }
                else {
                    $page = $_GET['trang'];
                }
                $offset   = ($page - 1) * 5;
        
                $info = new stdClass();
                $info->code = str_replace(BOOKING_PREFIX,'',$data);
                $total = count($this->m_booking->items($info));
                $bookings = $this->m_booking->items($info, 5, $offset);
        
                $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
                $url = str_replace("?trang={$page}", '?', $url);
                $url = str_replace("&trang={$page}", '', $url);
        
                $pagination = $this->util->pagination($url, $total, 5);
        
                $view_data = array();
                $view_data["bookings"] 		= $bookings;
                $view_data["breadcrumb"] 	= $this->_breadcrumb;
                $view_data["pagination"] 	= $pagination;
                $view_data["phone"] 	    = $data;
        
                $tmpl_content = array();
                $tmpl_content["content"] = $this->load->view("account/order_list", $view_data, true);
                $tmpl_content["title"] 		= 'Lịch sử đơn hàng';
                $this->load->view("layout/account", $tmpl_content);
            } else if ($type == 'dien-thoai'){
                if (!isset($_GET['trang']) || (($_GET['trang']) < 1)) {
                    $page = 1;
                }
                else {
                    $page = $_GET['trang'];
                }
                $offset   = ($page - 1) * 5;
        
                $info = new stdClass();
                $info->phone = $data;
                $total = count($this->m_booking->items($info));
                $bookings = $this->m_booking->items($info, 5, $offset);
        
                $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
                $url = str_replace("?trang={$page}", '?', $url);
                $url = str_replace("&trang={$page}", '', $url);

                $pagination = $this->util->pagination($url, $total, 5, $this->website['product']);
        
                $view_data = array();
                $view_data["bookings"] 		= $bookings;
                $view_data["breadcrumb"] 	= $this->_breadcrumb;
                $view_data["pagination"] 	= $pagination;
                $view_data["phone"] 	    = $data;
        
                $tmpl_content = array();
                $tmpl_content["content"] = $this->load->view("check_bill/index", $view_data, true);
                $tmpl_content["title"] 		= 'Lịch sử đơn hàng';
                $this->load->view("layout/account", $tmpl_content);
            } else {
                redirect(site_url('error404'),'back'); 
            }
        } else {
            redirect(site_url('error404'),'back');
        }
	}
}
?>