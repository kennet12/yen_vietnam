<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quy_che extends CI_Controller {
	var $_breadcrumb = array();

	function __construct()
	{
		parent::__construct();

		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Quy chế" => site_url($this->util->slug($this->router->fetch_class()))));
	}
	public function index() {
		$item = $this->m_post->load(2);
		$view_data = array();
		$view_data['item'] 			= $item;
		$view_data['breadcrumb'] 	= $this->_breadcrumb;

		$tmpl_content = array();
		$tmpl_content["title"]   	= $item->title;
		$tmpl_content["meta_img"]	= !empty($item->thumbnail)?$item->thumbnail:null;
		$tmpl_content["meta_key"]	= $item->meta_key;
		$tmpl_content["meta_des"]	= $item->meta_des;
		$tmpl_content["content"]   = $this->load->view("post/index", $view_data, TRUE);
		$this->load->view("layout/view", $tmpl_content);
	}
}
?>