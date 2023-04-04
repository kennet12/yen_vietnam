<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hoi_dap extends CI_Controller {
	var $_breadcrumb = array();

	function __construct()
	{
		parent::__construct();

        $this->_breadcrumb = array_merge($this->_breadcrumb, array("Hỏi - đáp" => '#'));
	}
	public function index($alias) {
        if(!empty($alias)) {
            $item = $this->m_question->load($alias);
            $this->_breadcrumb = array_merge($this->_breadcrumb, array("{$item->title}" => site_url($this->util->slug($this->router->fetch_class())."/{$alias}")));
            $related_items = $this->m_question->relative_items(null,array($item->id));

            $view_data = array();
            $view_data['item'] 			= $item;
            $view_data['related_items'] = $related_items;
            $view_data['breadcrumb'] 	= $this->_breadcrumb;

            $tmpl_content = array();
            $tmpl_content["title"]		= $item->title;
			$tmpl_content["meta_key"]	= $item->meta_key;
			$tmpl_content["meta_des"]	= $item->meta_des;
            $tmpl_content["meta_img"]	= !empty($item->thumbnail)?$item->thumbnail:null;
            $tmpl_content["content"]   = $this->load->view("question/index", $view_data, TRUE);
            $this->load->view("layout/view", $tmpl_content);
        }
		else {
            redirect(site_url('error404'),'back');
        }
	}
}
?>