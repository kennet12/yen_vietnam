<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Check_qrcode extends CI_Controller {
	var $_breadcrumb = array();

	function __construct()
	{
		parent::__construct();

	}
	public function index($id) {
        if (empty($id)) {
            redirect(BASE_URL);
        }
        $book_id = str_replace(BOOKING_PREFIX,'',$id);
        $booking = $this->m_booking->load($book_id);
        if (!empty($booking)) {
            $info = new stdClass();
            $info->booking_id = $book_id;
            $booking->details = $this->m_booking_detail->items($info);

            $tmpl_content = array();
            $tmpl_content["code"] 	    = $id;
            $tmpl_content["booking"] 	= $booking;
            $tmpl_content["setting"] 	= $this->m_setting->load(1);
            $this->load->view("admin/booking/export", $tmpl_content,false);
        } else {
            redirect(site_url('error404'),'back');
        }
	}
}
?>