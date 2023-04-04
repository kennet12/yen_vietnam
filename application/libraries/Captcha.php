<?php
class Captcha {

	function Captcha() {
		$this->ci = &get_instance();
	}
	
	function create()
	{
		if ($this->ci->session->userdata("captcha")) {
			return $this->ci->session->userdata("captcha");
		} else {
			$captcha = strtoupper(substr(md5(date($this->ci->config->item("log_date_format")).$this->ci->config->item("encryption_key")), 0, 6));
			$this->ci->session->set_userdata("captcha", $captcha);
			return $captcha;
		}
	}
	
	function test($input)
	{
		return (strtoupper(trim($input)) == strtoupper(trim($this->create())));
	}
	function test_recaptcha($str, $ip_adress, $user_agent)
	{
		$url = "https://www.google.com/recaptcha/api/siteverify?secret=".RECAPTCHA_SECRET."&response=".$str."&remoteip=".$ip_adress;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$data = curl_exec($ch);
		curl_close($ch);
		
		$status= json_decode($data, true);

		if($status['success']) {
			return true;
		} else {
			return false;
		}
	}
}
?>