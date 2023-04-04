<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Call_service extends CI_Controller {
	var $_breadcrumb = array();

	function __construct()
	{
		parent::__construct();

	}
	public function send_rating() {
        $point      = $this->input->post('point');
        $name       = $this->input->post('name');
        $email      = $this->input->post('email');
        $phone      = $this->input->post('phone');
        $message    = $this->input->post('message');
        $product_id = $this->input->post('item_id');
        $user       = $this->session->userdata("user");
        $recaptcha 	= $this->input->post('g-recaptcha-response');
        $result = 0;
        if (!empty($user)) {
            if (!empty($point)
                &&!empty($name)
                &&!empty($email)
                &&!empty($phone)
                &&!empty($message)
                &&!empty($product_id)
                &&(isset($recaptcha)&&$recaptcha)) {
                $data = array(
                    'point'     => $point,
                    'name'      => $name,
                    'email'     => $email,
                    'phone'     => $phone,
                    'message'   => $message,
                    'product_id'=> $product_id,
                    'user_id'   => $user->id,
                );
                $this->m_rating->add($data);
                $result = 1;
            }
        }
        echo json_encode($result);
	}
    public function load_rating() {
        $product_id = $this->input->post('item_id');
        $result = array();
        if (!empty($product_id)) {
            $info = new stdClass();
            $info->parent_id = 0;
            $info->product_id = $product_id;
            $rats = $this->m_rating->items('*',$info,1);
            // $cmt = $this->m_rating->items('*',$info,1);
            $arr_rat = array(
                '1' => 0,
                '2' => 0,
                '3' => 0,
                '4' => 0,
                '5' => 0,
            );
            $count_item = 0;
            $count_point = 0;
            foreach($rats as $rat) {
                $count_item++;
                $count_point += $rat->point;
                $arr_rat[$rat->point] += 1;

                $info = new stdClass();
                $info->parent_id = $rat->id;
                $rat->reply = $this->m_rating->items('*',$info,1);
            }
            // foreach($cmt as $cm) {
            //     $info = new stdClass();
            //     $info->parent_id = $cm->id;
            //     $cm->reply = $this->m_rating->items('*',$info,1);
            // }

            $result['count_item'] = $count_item;
            $result['avg_point'] = round($count_point/$count_item,1);
            $result['arr_rat'] = $arr_rat;
            // $result['cmt'] = $cmt;
            $result['cmt'] = $rats;
        }
        echo json_encode($result);
	}
    public function send_reply_rating() {
        $parent_id  = $this->input->post('item_id');
        $name       = $this->input->post('name');
        $email      = $this->input->post('email');
        $phone      = $this->input->post('phone');
        $message    = $this->input->post('message');
        $product_id = $this->input->post('product_id');
        $user       = $this->session->userdata("user");
        $result = 0;
        if (!empty($user)) {
            if (!empty($parent_id)
                &&!empty($name)
                &&!empty($email)
                &&!empty($phone)
                &&!empty($message)
                &&!empty($product_id)) {
                $data = array(
                    'parent_id' => $parent_id,
                    'name'      => $name,
                    'email'     => $email,
                    'phone'     => $phone,
                    'message'   => $message,
                    'product_id'=> $product_id,
                    'user_id'   => $user->id,
                );
                $this->m_rating->add($data);
                $result = 1;
            }
        }
        echo json_encode($result);
	}
    public function optimize_image() {
        if (!empty($_GET['url'])&&!empty($_GET['w'])) {
            $url = $_GET['url'];
            $width_thumb = $_GET['w'];
            $path = str_replace(BASE_URL,'.',$url);
            $format = explode('.',$url);
            $format = end($format);
            chmod("{$path}", 0777);
            
            // Get new dimensions
            list($width, $height) = getimagesize($url);
            $percent = ($width_thumb*100)/$width;
            $new_width = $width_thumb;
            $new_height = $height * $percent * 0.01;

            // Resample
            $image_p = imagecreatetruecolor($new_width, $new_height);
            if ($format == 'png'||$format == 'PNG'){
                header('Content-type: image/png');
                $image = imagecreatefrompng($url);
            } else {
                header('Content-type: image/jpeg');
                $image = imagecreatefromjpeg($url);
            }
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            // Output
            imagejpeg($image_p, null, 100);
            chmod("{$path}", 0664);
        } else {
            redirect(site_url('error404'),'back');
        }
	}
    public function qrcode($id){
        $this->load->library('ciqrcode');
		header("Content-Type: image/png");
		$params['data'] = site_url("check-qrcode/{$id}");
		$this->ciqrcode->generate($params);
    }
    public function like_product(){
        sleep(0.01);
        $user = $this->session->userdata("user");
        if (!empty($user)){
            // set session
            $user_data = $user;
            $this->session->unset_userdata('user');
            //
            $status     = (int)$this->input->post('status');
            $product_id = $this->input->post('product_id');
            $like_list = $user_data->like_product;
            if ($status == 1){
                if (!empty($like_list)) $like_list .= ',';
                $like_list .= '"'.$product_id.'"';
            } else {
                //unlike
                $like_list = str_replace('"'.$product_id.'",','',$like_list);
                $like_list = str_replace(',"'.$product_id.'"','',$like_list);
                $like_list = str_replace('"'.$product_id.'"','',$like_list);
            }
            $user_data->like_product = $like_list;
            $this->m_user->update(array("like_product"=>$like_list),array("id"=>$user->id));
            $this->session->set_userdata('user', $user_data);
        }
    }
    public function set_lang($lang='vi'){
        $lang = str_replace('.html','',$lang);
        $this->lang->load('content',$lang);
        $prop				= $this->lang->line('prop');
        $url = !empty($_GET['p'])?$_GET['p']:'';
        $para = str_replace(BASE_URL.'/','',$url);
        
        
        if ($lang == 'vi') {
            setcookie('nguyenanh_lang', null, -1, '/');
            $funtion = 'load_en';
        } else if ($lang == 'en') {
            setcookie("nguyenanh_lang", $lang, time() + (10 * 365 * 24 * 60 * 60),'/');
            $funtion = 'load_vi';
        }
        $base_url = BASE_URL; 

        if (empty($para)){
            redirect($base_url); 
        }
        $para = explode('.html',$para);
        $para = explode('/',$para[0]);
        if ($para[0] == 'product' || $para[0] == 'san-pham'){
            if ($para[0] == 'product'){
                $base_url .= '/san-pham';
            } else {
                $base_url .= '/product';
            }
            if (!empty($para[1])){
                $item = $this->m_product_category->{$funtion}($para[1]);
                $base_url .= '/'.$item->{$prop['alias']};
            }
            $base_url .= '.html';
        } else if ($para[0] == 'new') {
            if ($para[0] == 'new'){
                $base_url .= '/tin-tuc';
            } else {
                $base_url .= '/new';
            }
            if (!empty($para[1])){
                $item = $this->m_content_categories->{$funtion}($para[1]);
                $base_url .= '/'.$item->{$prop['alias']};
            }
            if (!empty($para[2])){
                $item = $this->m_contents->{$funtion}($para[2]);
                $base_url .= '/'.$item->{$prop['alias']};
            }
            $base_url .= '.html';
        } else if ($para[0] == 'about-us') {
            if ($para[0] == 'about-us'){
                $base_url .= '/gioi-thieu';
            } else {
                $base_url .= '/about-us';
            }
            $base_url .= '.html';
        } else if ($para[0] == 'contact' || $para[0] == 'lien-he') {
            if ($para[0] == 'contact'){
                $base_url .= '/lien-he';
            } else {
                $base_url .= '/contact';
            }
            $base_url .= '.html';
        } else if ($para[0] == 'term-use' || $para[0] == 'dieu-khoan-su-dung') {
            if ($para[0] == 'term-use'){
                $base_url .= '/dieu-khoan-su-dung';
            } else {
                $base_url .= '/term-use';
            }
            $base_url .= '.html';
        } else if ($para[0] == 'policy' || $para[0] == 'chinh-sach') {
            if ($para[0] == 'policy'){
                $base_url .= '/chinh-sach';
            } else {
                $base_url .= '/policy';
            }
            $base_url .= '.html';
        } else if ($para[0] == 'brand-system' || $para[0] == 'he-thong-chi-nhanh') {
            if ($para[0] == 'brand-system'){
                $base_url .= '/he-thong-chi-nhanh';
            } else {
                $base_url .= '/brand-system';
            }
            $base_url .= '.html';
        } else if ($para[0] == 'sales-promotion' || $para[0] == 'khuyen-mai-giam-gia') {
            if ($para[0] == 'sales-promotion'){
                $base_url .= '/khuyen-mai-giam-gia';
            } else {
                $base_url .= '/sales-promotion';
            }
            $base_url .= '.html';
        } else if ($para[0] == 'change-policy' || $para[0] == 'chinh-sach-doi-hang') {
            if ($para[0] == 'change-policy'){
                $base_url .= '/chinh-sach-doi-hang';
            } else {
                $base_url .= '/change-policy';
            }
            $base_url .= '.html';
        } else if ($para[0] == 'refund-policy' || $para[0] == 'chinh-sach-hoan-tien') {
            if ($para[0] == 'refund-policy'){
                $base_url .= '/chinh-sach-hoan-tien';
            } else {
                $base_url .= '/refund-policy';
            }
            $base_url .= '.html';
        } else if ($para[0] == 'tim-kiem') {
            $base_url .= '/tim-kiem.html';
        } else if ($para[0] == "tai-khoan") {
            $base_url .= '/tai-khoan.html';
        }  else {
            if (!empty($para[0])){
                $item = $this->m_product->{$funtion}($para[0]);
                $base_url .= '/'.$item->{$prop['alias']};
                $base_url .= '.html';
            }
        }
        
        redirect($base_url);
    }
    public function create_affiliate(){
        $affiliate = $this->m_affiliate_analytic->load_item($this->session->userdata('user')->affiliate_code);
        if (empty($affiliate)) {
            $data = array(
                'affiliate_code' => $this->session->userdata('user')->affiliate_code,
                'user_id' => $this->session->userdata('user')->id
            );
            $this->m_affiliate_analytic->add($data);
        }
    }
    public function fetch_data (){
        sleep(0.01);
        $url = $this->input->post('url');
        $parse = parse_url($url);
        $site_html = '';
        $site_html=  @file_get_contents($url);
        if($site_html) {
            if(strpos($parse['host'],"amazon") != false){
                $title = explode('<title>',$site_html);
                $title = explode('</title>',$title[1]);
                $img = explode('data-old-hires="',$site_html);
                $img = explode('"',$img[1]);
                $image = $img[0];
                $title = $title[0];
            } else {
                $doc = new DOMDocument();
                libxml_use_internal_errors(true);
                $doc->loadHTML(mb_convert_encoding($site_html, 'HTML-ENTITIES', 'UTF-8'));
                libxml_clear_errors();
                $title='';
                $image='';
                // preg_match_all('~<\s*meta\s+property="(og:[^"]+)"\s+content="([^"]*)~i',$doc->saveHTML(),$matches);
                preg_match_all('~<\s*meta\s+property="(og:title)"\s+content="([^"]*)~i',$doc->saveHTML(),$title);
                preg_match_all('~<\s*meta\s+property="(og:image)"\s+content="([^"]*)~i',$doc->saveHTML(),$image);
                if (empty($image[2])){
                    preg_match_all('~<\s*meta\s+name="(og:image)"\s+content="([^"]*)~i',$doc->saveHTML(),$title); 
                }
                $image = $image[2][0];
                $title = $title[2][0];
            }
           
            if (!empty($title) && !empty($image))
                echo json_encode(array($title,$image), JSON_UNESCAPED_UNICODE);
            else
                echo json_encode(0);
        } else {
            echo json_encode(0);
        }
                
    }   
}
?>