<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mail_tpl {

	function template($content)
	{	
		$this->ci =& get_instance();
		$this->ci->load->model('m_setting');
		$setting = $this->ci->m_setting->load(1);
		return '<!DOCTYPE html>
				<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>'.SITE_NAME.'</title>
				</head>
				<body style="color: #555;font-size: 15px;font-family: Arial,Tahoma,sans-serif;background: #eee;padding: 10px;">
					<div style="width: 600px;margin: 15px auto;">
						<div style="color: #fff;display: table;width: 100%;background: #fff;padding: 15px;border-bottom: 1px solid #FFC108;">
							<div style="display:table-cell;width:30%;padding: 15px;">
							<a href="'.BASE_URL.'" target="_blank"><img style="width: 130px;" src="'.IMG_URL.'logo.jpg" alt=""></a>
							</div>
							<div style="display:table-cell;width:70%;padding: 15px;color:#555;vertical-align:top;text-align:right">
								<p style="margin:0;"><a href="tel:'.$setting->company_hotline.'" style="color: #555;text-decoration: none;font-size: 12px;">'.$setting->company_hotline.'</a></p>
								<p style="margin:0;"><a href="mailto:'.$setting->company_email.'" style="color: #555;text-decoration: none;font-size: 12px;">'.$setting->company_email.'</a></p>
								<p style="margin:0;"><a href="'.GOOGLE_MAPS_LINK.'" target="_blank" style="color: #555;text-decoration: none;font-size: 12px;">'.$setting->company_address.'</a></p>
							</div>
						</div>
						'.$content.'
					</div>
				</body>
				</html>';
	}
	
	function booking_data($booking)
	{
		$this->ci =& get_instance();
		$this->ci->load->model('m_booking_detail');
		
		// $user = $this->ci->m_user->load($booking->user_id);
		$info = new stdClass();
		$info->booking_id = $booking->id;
		$booking_details = $this->ci->m_booking_detail->items($info);

		$tpl_data = array(
			"BOOKING_ID"				=> $booking->id,
			"FULLNAME"					=> $booking->fullname,
			"PHONE"						=> $booking->phone,
			"EMAIL"						=> $booking->email,
			"ADDRESS"					=> $booking->address,
			"MESSAGE"					=> $booking->message,
			"PROMOTION"					=> $booking->code_promotion,
			"PAYMENT"					=> $booking->payment,
			"TOTAL"						=> $this->ci->util->round_number($booking->total,1000),
			"DATE"						=> date('d/m/Y',strtotime($booking->created_date)),
			"PAXS"						=> $booking_details,
			"PAY_STATUS"				=> 0,
		);
		
		return $tpl_data;
	}
	function booking($tpl_data)
	{
		$lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
		$this->ci->lang->load('content',$lang);
		$website			= $this->ci->lang->line('website');
		$prop			= $this->ci->lang->line('prop');
		$this->ci =& get_instance();
		$this->ci->load->model('m_product');
		$i=0;
		$c = count($tpl_data['PAXS']);
		$content = '<div style="padding: 15px;background: #fff;">
						<div style="display:table;width:100%; font-size:14px;">
							<div style="display:table-cell;width:35%">
								<div style="color: #FFC108;margin-bottom: 7px;font-size:12px;">'.$website['mail_to_name'].'</div>
								<strong style="font-size:15px;">'.$tpl_data['FULLNAME'].'</strong>
								<p style="margin-bottom:0">'.$tpl_data['ADDRESS'].'</p>
							</div>
							<div style="display:table-cell;width:25%;padding-left: 5%;">
								<div style="color: #999;margin-bottom: 7px;font-size:12px;">'.$website['mail_code'].'</div>
								<p style="margin-bottom:0">'.BOOKING_PREFIX.$tpl_data['BOOKING_ID'].'</p>
								<br>
								<div style="color: #999;margin-bottom: 7px;font-size:12px;">'.$website['mail_order_date'].'</div>
								<p style="margin-bottom:0">'.$tpl_data['DATE'].'</p>
							</div>
							<div style="display:table-cell;width:40%;text-align:right;">
								<div style="color: #999;margin-bottom: 7px;font-size:12px;">'.$website['total'].'</div>
								<div style="font-size: 25px;color: #2e7731;font-weight: bold;">'.number_format($tpl_data['TOTAL'],0,',','.').'<sup>₫</sup></div>
								<div style="color: #999;margin-bottom: 7px;font-size:12px;">'.$website['mail_payment_method'].'</div>';
						if ($lang == 'vi'){
							$content .='<p style="margin-bottom:0">'.$this->ci->util->note_payment($tpl_data['PAYMENT']).'</p>';
						} else {
							$content .='<p style="margin-bottom:0">'.$tpl_data['PAYMENT'].'</p>';
						}
					$content .='</div>
						</div>
						<table style="width: 100%;border-top: 1px solid #FFC108;margin-top: 40px;">
							<tr>
								<td style="width:10%;padding: 20px 0;color: #FFC108;font-weight: bold;">
								'.$website['mail_picture'].'
								</td>
								<td style="width:35%;padding: 20px 10px;color: #FFC108;font-weight: bold;">
								'.$website['mail_product_name'].'
								</td>
								<td style="width:23%;padding: 20px 10px;color: #FFC108;font-weight: bold;">
								'.$website['mail_price'].'
								</td>
								<td style="width:7%;padding: 20px 10px;color: #FFC108;font-weight: bold;">
								'.$website['mail_quantity'].'
								</td>
								<td style="width:25%;padding: 20px 10px;color: #FFC108;font-weight: bold;">
								'.$website['mail_total_free'].'
								</td>
							</tr>';
		$total = 0;
		foreach ($tpl_data['PAXS'] as $pax) {
			$photo = explode('/',$pax->thumbnail);
			$c = count($photo);
			$price_sale = $pax->price_sale;
			if (!empty($tpl_data['PROMOTION'])) {
				$price_sale = $pax->price;
			}
			$total += $price_sale*$pax->qty;
			$content .= '<tr style="border-bottom: 1px solid #eee;">
							<td style="width:10%;padding: 20px 0;">
								<img style="width: 55px;height: 55px;object-fit: contain;" src="'.BASE_URL.'/files/upload/product/'.$photo[$c-2].'/thumb/'.end($photo).'" alt="">
							</td>
							<td style="width:35%;padding: 20px 10px;">
								<div style="font-size: 14px;"><a href="" target="_blank" style="text-decoration: none;color: #555;display: block;">'.$pax->{$prop['title']}.' </div></a>
								<div style="font-size: 12px;color: #8bc34a">'.$pax->typename.' '.$pax->subtypename.'</div>
							</td>
							<td style="width:23%;padding: 20px 10px;vertical-align: top;">
								<div style="font-size: 14px;">'.number_format($price_sale,0,',','.').'<sup>₫</sup></div>
							</td>
							<td style="width:7%;padding: 20px 10px;vertical-align: top;">
								<span style="margin: 8px 0;">x'.$pax->qty.'</span>
							</td>
							<td style="width:25%;padding: 20px 10px;vertical-align: top;">
								<div style="font-size: 14px;">'.number_format($price_sale*$pax->qty,0,',','.').'<sup>₫</sup></div>
							</td>
						</tr>';
		}
		$total = $this->ci->util->round_number($total,1000);
		$content .= '</table>
					<table style="width: 100%;margin-top:30px;">';
			if (!empty($tpl_data['PROMOTION'])) {
			$content .= '<tr>
							<td style="width:75%;padding: 3px 10px;font-size: 14px;color: #FFC108;text-align:right;">
								Giảm giá:
							</td>
							<td style="width:25%;font-size: 14px;padding: 3px 10px;">
								<div style="font-size: 14px;">- '.number_format($total-$tpl_data['TOTAL'],0,',','.').'<sup>₫</sup></div>
							</td>
						</tr>';
			}
			$content .= '<tr>
							<td style="width:75%;padding: 3px 10px;font-size: 14px;color: #FFC108;text-align:right;">
							'.$website['total'].'
							</td>
							<td style="width:25%;font-size: 14px;padding: 3px 10px;">
								<div style="font-size: 14px;">'.number_format($tpl_data['TOTAL'],0,',','.').'<sup>₫</sup></div>
							</td>
						</tr>
					</table>
					<p style="margin: 10px 0;line-height: 20px;font-size: 14px;">'.$website['mail_note'].'</p>
					<div style="text-align: center;">
						<a href="'.site_url('san-pham').'" target="_blank" style="background: #FFC108;color: #fff;font-size: 22px;padding: 5px 15px;display: inline-block;margin: 8px 0;text-decoration:none">'.$website['mail_buy_now'].'</a>
					</div>';
			if (!empty($tpl_data["USERNAME"] && !empty($tpl_data["PASSWORD"]))) {
			$content .= '<p style="line-height: 20px;font-size: 14px;">Vì để thuận tiện cho khách hàng lần đầu đặt hàng tại website chúng tôi đã tạo cho bạn 1 tài khoản với thông tin:</p>
						<p style="line-height: 20px;font-size: 14px;">Tài khoản: <strong>'.$tpl_data["USERNAME"].'</strong></p>
						<p style="line-height: 20px;font-size: 14px;">Password: <strong>'.$tpl_data["PASSWORD"].'</strong></p>';
			}
		$content .= '</div>';
		return $this->template($content);
	}
	
	function check_status($tpl_data)
	{
		$content = '<fieldset style="border:1px solid #DADCE0;">
						<legend style="border:1px solid #DADCE0; background-color: #F6F6F6; padding: 4px"><strong>Request Support Details</strong></legend>
						<div>
							<div style="padding: 10 0 10px 20px;">
								<table>
									<tr><td>Primary Email</td><td> : </td><td><a href="mailto:'.$tpl_data["PRIMARY_EMAIL"].'">'.$tpl_data["PRIMARY_EMAIL"].'</a></td></tr>
									<tr><td>Secondary Email</td><td> : </td><td><a href="mailto:'.$tpl_data["SECONDARY_EMAIL"].'">'.$tpl_data["SECONDARY_EMAIL"].'</a></td></tr>
									<tr><td>Full Name</td><td> : </td><td>'.$tpl_data["FULLNAME"].'</td></tr>
									<tr><td>Passport</td><td> : </td><td>'.$tpl_data["PASSPORT"].'</td></tr>
									<tr><td>Message</td><td> : </td><td>'.$tpl_data["MESSAGE"].'</td></tr>
								</table>
							</div>
						</div>
					</fieldset>';
		return $this->template($content);
	}
	
	function forgot_password($tpl_data)
	{
		$content = '<div>
						<p>Chào <b>'.$tpl_data["FULLNAME"].'</b>,</p>
						<br>
						<p style="color:red;">*Nếu bạn không phải là chủ nhân mail : <strong style="color:#000">'.$tpl_data["EMAIL"].'</strong>. Xin vui lòng bỏ qua mail này!</p>
						<br>
						<p> Vui lòng bấm <a style="text-decoration:none;color:#fff;background:#4cd137;padding:10px;border-radius:2px" href="'.$tpl_data["LINK"].'" target="_blank" data-saferedirecturl="">vào đây</a> để lấy password</p>
						<br>
						<br>
						<p>Chỉ cần nhấp vào nút để đặt mật khẩu mới</p>
						<p>Xin vui lòng liên hệ với chúng tôi nếu bạn có bất kỳ vấn đề!</p>
						<br>
						<p>Trân trọng,</p>
						<p><b>Nguyên Anh Store.</b></p>
					</div>';
		return $this->template($content);
	}
	
	function register_successful($tpl_data)
	{
		$content = '<div>
						<p>Dear <b>'.$tpl_data["FULLNAME"].'</b>,</p>
						<br>
						<p>Thank you for your registration to <a href="'.BASE_URL.'">'.SITE_NAME.'</a>!</p>
						<p>This email to confirm that you have registered account successful with us.</p>
						<p>Please return to the <a href="'.site_url("member").'">'.SITE_NAME.'</a> website and login with the email that you have registered.</p>
						<p>Please do not hesitate to contact us if you have any problem!</p>
						<br>
						<p>Trân trọng,</p>
						<p><b>Nguyên Anh Store.</b></p>
					</div>';
		return $content;
	}
	function contact_admin($tpl_data)
	{
		$content = '<div>
						<p>Dear <b>Admin</b>,</p>
						<br>
						<p>Khách hàng <b>'.$tpl_data["FULLNAME"].'</b> với thông tin như sau: </p>
						<p><b>Điện Thoại:</b> '.$tpl_data["PHONE"].'</p>
						<p><b>Email:</b> '.$tpl_data["EMAIL"].'</p>
						<p>Với tin nhắn yêu cầu hỗ trợ như sau:</p>
						<p>'.$tpl_data["CONTENT"].'</p>
						<br>
						<p>Xin vui lòng liên hệ với chúng tôi nếu bạn có bất kỳ vấn đề!</p>
						<br>
						<p>Trân trọng,</p>
						<p><b>Nguyên Anh Store.</b></p>
					</div>';
		return $this->template($content);
	}
	function contact($tpl_data)
	{
		$content = '<div>
						<p>Dear <b>'.$tpl_data["FULLNAME"].'</b>,</p>
						<br>
						<p>Chúng tôi sẽ liên hệ lại bạn trong thời gian sớm nhất.</p>
						<p>Xin chân thành cảm ơn đã đồng hành cùng Nguyên Anh Store.</p>
						<br>
						<p>Xin vui lòng liên hệ với chúng tôi nếu bạn có bất kỳ vấn đề!</p>
						<br>
						<p>Trân trọng,</p>
						<p><b>Nguyên Anh Store.</b></p>
					</div>';
		return $this->template($content);
	}
}