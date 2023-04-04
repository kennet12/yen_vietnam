<?php

class Util {

    function __construct() {
        $this->ci = &get_instance();
    }

    public function real_ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    function realIP()
    {
        // Check ip from share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        // Check ip is pass from proxy
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        // Check X4b proxy
        else if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }
        // Natural
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        $pieces = explode(",", $ip);
        $ip = trim($pieces[0]);
        if (!empty($pieces[1])) {
            $ip = trim($pieces[1]);
        }
        
        return $ip;
    }
    public function slug($str)
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '-', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        $str = str_replace('--', '-', $str);
        return $str;
    }
    public function note_payment($text)
    {
        if ($text=='Banking'){
            $str = 'Chuyển khoản ngân hàng';
        } else if ($text=='Cash') {
            $str = 'Thánh toán nhận hàng';
        } else {
            $str = $text;
        }
        return $str;
    }
    public function value($value, $default, $mode='null')
    {
        if ($mode == 'null') {
            return (isset($value) ? $value : $default);
        } elseif ($mode == 'empty') {
            return (!empty($value) ? $value : $default);
        } else {
            return (isset($value) ? $value : $default);
        }
    }

    public function object2array($objects)
    {
        $array = array();
        foreach ($objects as $object) {
            $a = array();
            foreach ($object as $key => $val) {
                $a[$key] = $val;
            }
            $array[] = $a;
        }
        return ((sizeof($array) > 1) ? $array : $array[0]);
    }

     function post_data($site, $data, $cookie)
    {
        $datapost = curl_init();
        $headers = array("Expect:");
        $headers[] = 'Content-Type:application/json';
        curl_setopt($datapost, CURLOPT_URL, $site);
        curl_setopt($datapost, CURLOPT_TIMEOUT, 40000);
        curl_setopt($datapost, CURLOPT_HEADER, TRUE);
        curl_setopt($datapost, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($datapost, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
        curl_setopt($datapost, CURLOPT_POST, TRUE);
        curl_setopt($datapost, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($datapost, CURLOPT_COOKIE, $cookie);
        ob_start();
        return curl_exec($datapost);
        //    ob_end_clean();
        //    curl_close ($datapost);
        //    unset($datapost);
    }
    function load($url){
        $ch =  curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $duy = curl_exec($ch);
        return $duy;
        curl_close($ch);
    }
    function round_number($number,$unit){
        $number = round($number/$unit);
        return $number*$unit;
    }
    function pagination($base_url, $total_rows=1, $per_page=10,$type_title)
    {
        $lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
        $this->ci->lang->load('content',$lang);
        $website				= $this->ci->lang->line('website');
        $this->ci->load->library('pagination');
        if (preg_match('/&page=.*/', $base_url)) {
            $base_url = preg_replace('/&page=.*/', NULL, $base_url);
        }
        $total_page = ceil($total_rows / $per_page);
        $page_cur = !empty($_GET['page'])?$_GET['page']:'1';

        $config = array();
        $config['base_url']             = $base_url;
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers']     = TRUE;

        $config['full_tag_open']        = '<div class="d-flex align-items-center"><div class="pagination__viewing">'.$website['page'].' '.$page_cur.' - '.$total_page.' | '.$total_rows.' '.$type_title.'</div><ul class="pagination d-flex justify-content-end align-items-center">';
        $config['full_tag_close']       = '</ul></div>';

        $config['first_link']           = $website['first_page'];
        $config['first_tag_open']       = '<li class="d-none d-sm-inline"><div class="pagination__btn"><span class="icon__fallback-text">';
        $config['first_tag_close']      = '</span></div></li>';

        $config['prev_link']            = '<i class="zmdi zmdi-chevron-left"></i>';
        $config['prev_tag_open']        = '<li class="pagination__text">';
        $config['prev_tag_close']       = '</li>';

        $config['cur_tag_open']         = '<li class="pagination__text active"><span>';
        $config['cur_tag_close']        = '</span></li>';

        $config['num_tag_open']         = '<li class="pagination__text">';
        $config['num_tag_close']        = '</li>';

        $config['next_link']            = '<i class="zmdi zmdi-chevron-right"></i>';
        $config['next_tag_open']        = '<li class="pagination__text">';
        $config['next_tag_close']       = '</li>';

        $config['last_link']            = $website['last_page'];
        $config['last_tag_open']       = '<li class="d-none d-sm-inline"><div class="pagination__btn"><span class="icon__fallback-text">';
        $config['last_tag_close']      = '</span></div></li>';

        $this->ci->pagination->initialize($config);

        return $this->ci->pagination->create_links();
    }
    function pagination_admin($base_url, $total_rows=1, $per_page=10)
    {
        $this->ci->load->library('pagination');
        if (preg_match('/&page=.*/', $base_url)) {
            $base_url = preg_replace('/&page=.*/', NULL, $base_url);
        }
        
        $config = array();
        $config['base_url']             = $base_url;
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = "page";
        $config['use_page_numbers']     = TRUE;
        
        $config['full_tag_open']        = '<ul class="pagination">';
        $config['full_tag_close']       = '</ul>';
        
        $config['first_link']           = 'First';
        $config['first_tag_open']       = '<li>';
        $config['first_tag_close']      = '</li>';
        
        $config['prev_link']            = '&lt;';
        $config['prev_tag_open']        = '<li>';
        $config['prev_tag_close']       = '</li>';
        
        $config['cur_tag_open']         = '<li class="active"><a href="#">';
        $config['cur_tag_close']        = '<span class="sr-only">(current)</span></a></li>';
        
        $config['num_tag_open']         = '<li>';
        $config['num_tag_close']        = '</li>';
        
        $config['next_link']            = '&gt;';
        $config['next_tag_open']        = '<li>';
        $config['next_tag_close']       = '</li>';
        
        $config['last_link']            = 'Last';
        $config['last_tag_open']        = '<li>';
        $config['last_tag_close']       = '</li>';
        
        $this->ci->pagination->initialize($config);
        
        return $this->ci->pagination->create_links();
    }

    function watermark_main($path, $text = null){
        $this->ci->load->library('image_lib');

        $config = array();
        $config['image_library']    = 'GD2';
        $config['source_image']     = $path;
        $config['quality']          = 90;
        $config['wm_type']          = 'overlay';
        $config['wm_overlay_path']  = './template/images/watermark.png';
        $config['wm_opacity']       = 50;
        $config['width']            = '825';
        $config['height']           = '510';
        $config['wm_vrt_alignment'] = 'middle';
        $config['wm_hor_alignment'] = 'center';
        $this->ci->image_lib->initialize($config);
        $this->ci->image_lib->resize();
        $this->ci->image_lib->watermark();
        $this->ci->image_lib->clear();
    }

    function create_thumb($source_path, $thumb_marker) {
        $this->ci->load->library('image_lib');

        $config['image_library']    = 'gd2';
        $config['source_image']     = $source_path;
        $config['create_thumb']     = TRUE;
        $config['maintain_ratio']   = TRUE;
        $config['thumb_marker']     = $thumb_marker;
        $config['quality']          = 90;

        if($thumb_marker == '_middle') {
            $config['width']    = MIDDLE_WIDTH;
        }
        elseif($thumb_marker == '_small') {
            $config['width']    = SMALL_WIDTH;
        }
        $this->ci->image_lib->initialize($config);
        $this->ci->image_lib->resize();
        $this->ci->image_lib->clear();
    }

    function resize_origin_img($source_path, $width=ORIGIN_WIDTH) {
        $this->ci->load->library('image_lib');
        list($old_width, $old_height) = getimagesize($source_path);
        if($old_width > $width) {
            $config['image_library']    = 'gd2';
            $config['source_image']     = $source_path;
            $config['quality']          = 90;
            $config['width']            = $width;

            $this->ci->image_lib->initialize($config);
            $this->ci->image_lib->resize();
            $this->ci->image_lib->clear();
        }
    }

    function get_thumb($source_path, $thumb_type='_middle') {

        $ext = pathinfo($source_path, PATHINFO_EXTENSION );
        return substr_replace($source_path, $thumb_type, -(strlen($ext) + 1 ), 0);
    }

    function get_kc_thumb($source_path) {
        return preg_replace('/\/files\/upload\/image/', '/files/upload/.thumbs/image', $source_path);
    }

    function require_user_login($login_page=null)
    {
        if(!$this->ci->session->userdata("user")){
            $this->ci->session->set_userdata("return_url", current_url());
            if (!is_null($login_page)) {
                redirect($login_page);
            } else {
                redirect(USR_URL);
            }
        }
    }

    function require_admin_login($login_page=null)
    {
        if ($this->ci->session->userdata("agent_id") != ADMIN_AGENT_ID
            || !$this->ci->session->userdata("admin")
            || (!in_array($this->ci->session->userdata("admin")->user_type, array(USR_ADMIN, USR_SUPPER_ADMIN)))) {
            $this->ci->session->set_userdata("return_url", current_url());
            if (!is_null($login_page)) {
                redirect($login_page);
            } else {
                redirect(ADMIN_URL);
            }
        }
    }

    function require_supper_admin_login($login_page=null)
    {
        if ($this->ci->session->userdata("agent_id") != ADMIN_AGENT_ID
            || !$this->ci->session->userdata("admin")
            || ($this->ci->session->userdata("admin")->user_type != USR_SUPPER_ADMIN)) {
            $this->ci->session->set_userdata("return_url", current_url());
            if (!is_null($login_page)) {
                redirect($login_page);
            } else {
                redirect(ADMIN_URL);
            }
        }
    }

    public function validate_search_string($str)
    {
        $priority_str = preg_replace("/\//", " " , $str);
        $tmp = preg_replace('/[^A-ZÁÀẢÃẠÂẤẦẨẪẬẮẰẲẴẶÉÈẺẼẸÊẾỀỂỄỆÍÌỈĨỊÓÒỎÕỌỐỒỔỖỘƠỚỜỞỠỢÚÙỦŨỤỨỪỬỮỰÝỲỶỸỴĐa-záàảãạâấầẩẫậăắằẳẵặéèẻẽẹêếềểễệiíìỉĩịóòỏõọốồổỗộớờởỡợúùủũụứừửữựýỳỷỹỵđ0-9\ ](.-)/', "", $priority_str);

        if(!empty($tmp) && (strlen($tmp) > 100)) {
            $result = substr($tmp, 0, 100);
        }
        $result = $tmp;
        return $result;
    }

    public function regex_vietnamese($str)
    {
        $patterns = array(
            '/(.*?)(A|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ặ|Ẵ|a|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ.*)(.*?)/',
            '/(.*?)(E|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|e|è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)(.*?)/',
            '/(.*?)(I|Ì|Í|Ị|Ỉ|Ĩ|i|ì|í|ị|ỉ|ĩ)(.*?)/',
            '/(.*?)(O|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|o|ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)(.*?)/',
            '/(.*?)(U|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|u|ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)(.*?)/',
            '/(.*?)(D|Đ|d|đ)(.*?)/',
            '/(.*?)(Y|Ỳ|Ý|Ỵ|Ỷ|Ỹ|y|ỳ|ý|ỵ|ỷ|ỹ)(.*?)/'
            );
        $replacements = array(
            '${1}(A|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ặ|Ẵ|a|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)${3}',
            '${1}(E|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|e|è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)${3}',
            '${1}(I|Ì|Í|Ị|Ỉ|Ĩ|i|ì|í|ị|ỉ|ĩ)${3}',
            '${1}(O|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)${3}',
            '${1}(U|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|u|ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)${3}',
            '${1}(D|Đ|d|đ)${3}',
            '${1}(Y|Ỳ|Ý|Ỵ|Ỷ|Ỹ|y|ỳ|ý|ỵ|ỷ|ỹ)${3}'
            );
        $result = array();
        if (is_array($str)) {
            foreach ($str as $key => $value) {
                array_push($result, preg_replace($patterns, $replacements, $value));
            }
        }
        return $result;
    }

    function sort_date_file($fileList) {
        $count = count($fileList);
        for ($i = 0; $i < ($count - 1); $i++)
        {
            $idate = date ("Y-m-d H:i:s", filectime($fileList[$i]));
            for ($j = $i + 1; $j < $count; $j++)
            {
                $jdate = date ("Y-m-d H:i:s", filectime($fileList[$j]));
                if ($idate < $jdate)
                {
                    $tmp = $fileList[$j];
                    $fileList[$j] = $fileList[$i];
                    $fileList[$i] = $tmp;
                }
            }
        }
        return $fileList;
    }
    public function classify_userid_type($userid)
    {
        if(preg_match('/^[A-Za-z0-9\._]*@[A-Za-z0-9]*\.[A-Za-z]*/', $userid, $matches)) {
            return 'email';
        } else {
            return 'username';
        }
        // elseif (preg_match('/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/', $userid, $matches)) {
        //     return 'phone';
        // }
        // elseif (preg_match('/^[A-Za-z0-9][A-Za-z0-9\._]+/', $userid, $matches)) {
        //     return 'username';
        // }
    }
    public function to_vn_date($date) {
        $date_time = strtotime($date);
        $day = date('N', $date_time);
        $vn_days = array('Hôm nay','Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN');
        $result = ((date('Y-m-d', $date_time) == date('Y-m-d')) ? 'Hôm nay' : $vn_days[($day)]).' - '.date('H:i d/m/Y', $date_time);
        return $result;
    }

    public function gen_category_menu($product_categories, $obj,$device='') {
        foreach ($product_categories as $product_category) {
            $child_category_info = new stdClass();
            $child_category_info->parent_id = $product_category->id;
            $child_categories = $obj->m_product_category->items($child_category_info,1,null,null,'order_num','ASC');
            if ($device == '-menu-mobile'){
                $href = '#';
            } else {
                $href = site_url("san-pham").'?danh_muc='.$product_category->id;
            }
            if (!empty($child_categories)) {
                echo '<li class="sub-item item">
                        <a class="icon'.$device.'" href="'.$href.'">'.$product_category->name.'</a>
                        <ul class="sub-list'.$device.'">';
                        $this->gen_category_menu($child_categories, $obj,$device);
                echo '	</ul>
                    </li>';
            } else {
                echo '<li class="sub-item item"><a href="'.site_url("san-pham").'?danh_muc='.$product_category->id.'">'.$product_category->name.'</a></li>';
            }
        }
    }
    // public function print_categories($obj, $categories,$str,$id_child,$product_id) {
    //     foreach ($categories as $category) {
    //         $id = '"'.$category->id.'"';
    //         $r = "{$id}".$str;
    //         if ($category->parent_id != 0) {
    //             $parent_category_info = new stdClass();
    //             $parent_category_info->id = $category->parent_id;
    //             $parent_categories = $obj->m_product_category->items($parent_category_info);
    //             $this->print_categories($obj, $parent_categories,",{$id}".$str,$id_child,$product_id);
    //         } else {
    //             $this->ci->m_product->update(array('category_id'=>'['.$r.']'),array("id"=>$product_id));
    //         }
    //     }
    // }
    public function upload_file($path=null,$name=null,$file_deleted=null,$allow_type='rar|zip|pdf|doc|docx|PDF|DOC|DOCX|xls|xlsx|csv',$width_thumb=null)
    {
        $path_thumb = $path.'thumb/';
        $temp = explode('.',$_FILES[$name]["name"]);
        $format = end($temp);
        $new_file_name = $this->slug($temp[0]);

		if (!file_exists($path)) {
			mkdir($path, 0775, true);
		}
        if (!file_exists($path_thumb)) {
            mkdir($path_thumb, 0775, true);
        }
        if (!empty($file_deleted)){
            unlink($file_deleted);
        }
        $config = array(
            'upload_path'   => $path,
            'upload_url'    => base_url().str_replace('./','',$path),
            'allowed_types' => $allow_type,
            'overwrite'     => TRUE,
            'quality'       => 80,
            'max_size'      => 8000,
            "file_name" 	=> $new_file_name.'.'.end($temp),
            );
        $this->ci->load->library("upload", $config);
        if (!empty($name)){
            if($this->ci->upload->do_upload($name)){
                if (!empty($width_thumb)){
                    $image_data = $this->ci->upload->data();
                
                    $source_path = $path. $image_data['file_name'];
                    $target_path = $path_thumb;
                    $config_manip = array(
                        'image_library' 	=> 'gd2',
                        'source_image' 		=> $source_path,
                        'new_image' 		=> $target_path,
                        'quality' 			=> 85,
                        'maintain_ratio' 	=> TRUE,
                        'create_thumb' 		=> TRUE,
                        'thumb_marker' 		=> '',
                        'width' 			=> $width_thumb,
                        "file_name" 		=> $new_file_name.'.'.$format
                    );
                    $this->ci->load->library('image_lib', $config_manip);
                    $this->ci->image_lib->initialize($config_manip);
                    $this->ci->image_lib->resize();
                    $this->ci->image_lib->clear();

                    if ($format == 'png' || $format == 'PNG') {
                        $image = imagecreatefromstring(file_get_contents($_FILES[$name]["tmp_name"]));
                        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
                        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
                        imagealphablending($bg, TRUE);
                        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                        imagedestroy($image);
                        imagejpeg($bg, $path."/".$new_file_name.".jpg");
                        imagedestroy($bg);

                        $image_thumb = imagecreatefrompng($path_thumb."/".$new_file_name.".png");
                        $bg_thumb = imagecreatetruecolor(imagesx($image_thumb), imagesy($image_thumb));
                        imagefill($bg_thumb, 0, 0, imagecolorallocate($bg_thumb, 255, 255, 255));
                        imagealphablending($bg_thumb, TRUE);
                        imagecopy($bg_thumb, $image_thumb, 0, 0, 0, 0, imagesx($image_thumb), imagesy($image_thumb));
                        imagedestroy($image_thumb);
                        imagejpeg($bg_thumb, $path_thumb."/".$new_file_name.".jpg");
                        imagedestroy($bg_thumb);

                        unlink($path."/".$new_file_name.".".$format);
                        unlink($path_thumb."/".$new_file_name.".".$format);
                    }
                } else {
                    if ($format == 'png' || $format == 'PNG') {
                        $image = imagecreatefromstring(file_get_contents($_FILES[$name]["tmp_name"]));
                        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
                        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
                        imagealphablending($bg, TRUE);
                        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                        imagedestroy($image);
                        imagejpeg($bg, $path."/".$new_file_name.".jpg");
                        imagedestroy($bg);
                        
                        unlink($path."/".$new_file_name.".".$format);
                    }    
                }
            }
        }else{
            return false;
        }
    }
    public function download_file($file_name=null,$file_path=null){
        $this->ci->load->helper('download');
        force_download($file_name, $file_path);
    }
    public function font_file($allow_type=null){
        $arr_font = array('fa-file-archive-o', 'fa-file-word-o', 'fa-file-excel-o', 'fa-file-pdf-o');
        $arr_allow_type = array(
            "rar" => 0,
            "zip" => 0,
            "doc" => 1,
            "docx" => 1,
            "xls" => 2,
            "xlsx" => 2,
            "csv" => 2,
            "pdf" => 3
        );
        $result = !empty($allow_type) ? $arr_font[$arr_allow_type[$allow_type]] : 'fa-file-o';
        return $result;
    }
    public function get_key_youtube($link){
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $link, $matches);
        return $matches[0];
    }
    public function detect_mobile(){
        if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) { 
            return true;
        } else {
            return false;
        }
    }
    function sanitize_output($buffer) {
        $search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s','/<!--(.|\s)*?-->/');
        $replace = array('>','<','\\1','');
        return preg_replace($search, $replace, $buffer);
    }

    function get_language() {
        $this->ci->load->helper('language');
        $languages = $this->ci->m_language->items();
        $actual_link = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        $count = strlen(BASE_URL);
        $lang_code = substr($actual_link,$count+1,2);
        $arr = array();
        foreach ($languages as $language) {
            array_push($arr, $language->code);
        }
        if (!in_array($lang_code, $arr)) {
            $this->ci->session->set_userdata("lang", $this->ci->m_language->lang_default());
        } else {
            foreach ($languages as $language) {
                if ($lang_code == $language->code) {
                    $this->ci->session->set_userdata("lang", $language);
                    break;
                }
            }
        }
        
        $lang = $this->ci->session->userdata('lang');
        $this->ci->lang->load($lang->code,$lang->name);
        return $lang;
    }
    function get_module_cur() {
        $this->ci->load->helper('language');
        $lang_default = $this->ci->m_language->lang_default();
        $lang_cur = $this->ci->session->userdata('lang');
        $actual_link = HTTP."{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        $count = strlen(BASE_URL);
        if ($lang_cur->code == $lang_default->code){
            $url_after_domain = substr($actual_link,$count+1);
        } else {
            $url_after_domain = substr($actual_link,$count+4);
        }
        $lang = explode('/',$url_after_domain);
        $lang = explode('.',$lang[0]);
        return $lang[0];
    }
    function get_info_youtube($key) {
        $json = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$key.'&key=AIzaSyDy_pAZXRJmL17N9MTcUnwkXh2gxBN-sTo&part=snippet,statistics');
        $jsonData = json_decode($json);
        $views = $jsonData->items[0];
        return $views;
    }
    function get_url_lang() {
        $this->ci->load->helper('language');
        $lang = $this->get_language();
        $lang_default = $this->ci->m_language->lang_default();
        $url_lang = '';
        if ($lang->code != $lang_default->code) {
            $url_lang .= "{$lang->code}/";
        }
        return $url_lang;
    }
    function get_day_statistic($type) {
        $fromdate = '';
        $todate = '';
        if ($type == 'week') {
             $from = date('N') - 1;
             $fromdate = date('Y-m-d',strtotime("-{$from}days"));
             $to = 7 - date('N');
             $todate = date('Y-m-d',strtotime("+{$to}days"));
        } else if ($type == 'month') {
             $from = date('d') - 1;
             $fromdate = date('Y-m-d',strtotime("-{$from}days"));
             $to = date('t') - date('d');
             $todate = date('Y-m-d',strtotime("+{$to}days"));
        }
        return array($fromdate, $todate);
    }
    function get_block_phone($phone){
        $info = new stdClass();
        $info->phone = $phone;
        $items = $this->ci->m_block->users($info);
        if(!empty($items)){
            return true;
        } else {
            return false;
        }
    }
    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    function codeOTP($phone,$conten){
        $APIKey="3EE128F352BC480F086FD57954DD0F";
        $SecretKey="048D8AD292850460EA05CF80D0E86B";
        $YourPhone="0859322224";

        $Brandname = "Baotrixemay";
        $Content = "321321 la ma xac minh dang ky Baotrixemay cua ban";
        
        $SendContent=urlencode($Content);
       
        $data="http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=$YourPhone&ApiKey=$APIKey&SecretKey=$SecretKey&Content=$SendContent&Brandname=$Brandname&SmsType=2";
        //De dang ky brandname rieng vui long lien he hotline 0901.888.484 hoac nhan vien kinh Doanh cua ban
        var_dump($data);
        $curl = curl_init($data); 
        curl_setopt($curl, CURLOPT_FAILONERROR, true); 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        $result = curl_exec($curl); 
            
        $obj = json_decode($result,true);
        if($obj['CodeResult']==100)
        {
            print "<br>";
            print "CodeResult:".$obj['CodeResult'];
            print "<br>";
            print "CountRegenerate:".$obj['CountRegenerate'];
            print "<br>";     
            print "SMSID:".$obj['SMSID'];
            print "<br>";
        }
        else
        {
            print "ErrorMessage:".$obj['ErrorMessage'];
        } 
    }
}
