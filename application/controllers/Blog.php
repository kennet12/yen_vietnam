<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller {
	var $_breadcrumb = array();

	function __construct()
	{
		parent::__construct();

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Tin tức" => site_url($this->util->slug($this->router->fetch_class()))));
	}
	public function index() {
        $tmpl_content				= array();
		$this->load->view("blog/index", $tmpl_content);
	}
    public function chi_tiet() {
        $tmpl_content				= array();
		$this->load->view("blog/detail", $tmpl_content);
	}
}
?>