<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quen_mat_khau extends CI_Controller {
	var $_breadcrumb = array();
	var $_lang = '';
	var $menu = array();
	var $website = array();
	var $prop = array();
	var $alias = array();


	function __construct()
	{
		parent::__construct();
		$this->_lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
		$this->lang->load('content',$this->_lang);
		$this->menu			= $this->lang->line('menu');
		$this->website		= $this->lang->line('website');
		$this->prop			= $this->lang->line('prop');
		$this->alias		= $this->lang->line('alias');
		$this->_breadcrumb = array_merge($this->_breadcrumb, array("Quên mật khẩu" => site_url("quen-mat-khau")));
	}
	public function index($code_confirm) {
		$user = $this->m_user->forgot_password($code_confirm,1);
        
		if (empty($user)) {
			redirect(site_url(""));
		}
		$task = $this->input->post("task");
		if (!empty($task)) {
			if ($task == "save") {
				$new_password			= $this->input->post("new_password");
				$confirm_new_password	= $this->input->post("confirm_new_password");


				if (empty($new_password)) {
					$this->session->set_flashdata("error", $this->website['error_note4']);
					redirect(current_url(), "back");
				}
				else if ($new_password != $confirm_new_password) {
					$this->session->set_flashdata("error", $this->website['error_note5']);
					redirect(current_url(), "back");
				}
				else if (strlen(trim($new_password)) < 6) {
					$this->session->set_flashdata("error", $this->website['error_note6']);
					redirect(current_url(), "back");
				}
				else {
					$data = array(
						"password"		=> md5($new_password),
						"password_text"	=> $new_password,
                        "code_confirm"	=> NULL
					);
					$where = array(
						"id" => $user->id
					);
					$this->m_user->update($data, $where);

					$this->session->set_flashdata("success", $this->website['success']);
					redirect(current_url(), "back");
				}
			}
		}

		$view_data = array();
		$view_data["website"] = $this->website;

		$tmpl_content = array();
		$tmpl_content["title"] = $this->website['forgot_your'].' '.$this->website['password'];
		$tmpl_content["content"] = $this->load->view("account/forgot_password", $view_data, true);
		$this->load->view("layout/view", $tmpl_content);
	}
}
?>