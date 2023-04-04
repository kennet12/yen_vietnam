<?
	$data = null;
	if ($this->session->flashdata('login')) {
		$data = $this->session->flashdata('login');
	}
	$username				= (!empty($data->username) ? $data->username : "");
	$new_gender				= (!empty($data->new_gender) ? $data->new_gender : "1");
	$new_fullname			= (!empty($data->new_fullname) ? $data->new_fullname : "");
	$new_email				= (!empty($data->new_email) ? $data->new_email : "");
	$new_username			= (!empty($data->new_username) ? $data->new_username : "");
	$new_password			= (!empty($data->new_password) ? $data->new_password : "");
	$confirm_new_password	= (!empty($data->confirm_new_password) ? $data->confirm_new_password : "");
	$new_phone				= (!empty($data->new_phone) ? $data->new_phone : "");
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="container">
<form id="frm-checkout" class="edit_checkout" action="<?=site_url("dat-hang-nhap/gui")?>" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
    <h1 class="headingPage"><?=$website['check_out']?></h1>
    <div class="row">
        <div class="col-md-7">
            <div class="order-summary__section__content">
                <h2 class="section__title" id="main-header" tabindex="-1">
					<?=$website['product']?>
                </h2>
                <p class="note-order-product"><?=$website['note_order_product']?></p>
                <div class="wrap-order-item">
                    <div class="order-item order-item-<?=date('Ymdhis')?>">
                        <div class="del"><?=$website['remove_product']?></div>
                        <div class="box">
                            <div class="box-left">
                                <div class="name"><input type="text" name="name[]" placeholder="<?=$website['enter_product_name']?>"></div>
                                <div style="display:table;width:100%;">
                                    <p class="have-link" st="<?=date('Ymdhis')?>"><?=$website['have_link']?></p>
                                    <div style="display:table-cell;">
                                        <div class="quantity">
                                            <span class="ctrl-qty">-</span><input type="number" class="qty" min=1 value="1" name="quantity[]"><span class="ctrl-qty">+</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-right">
                                <label class="custom-file-upload custom-file-upload-<?=date('Ymdhis')?>" style="background-image:url(<?=IMG_URL.'image-default.png'?>)">
                                    <input type="file" name="thumbnail_<?=date('Ymdhis')?>" file_id = "<?=date('Ymdhis')?>" class="upload-file-order"/>
                                    <input type="hidden" name="stt[]" value="<?=date('Ymdhis')?>">
                                    <input type="hidden" class="url-img" name="url_img[]" value="">
                                    <p><?=$website['attached_img']?></p>
                                </label>
                            </div>
                        </div>
                        <div class="link link-<?=date('Ymdhis')?>" st="<?=date('Ymdhis')?>">
                            <input type="text" name="url[]" value="" placeholder="<?=$website['paste_link']?>">
                            <p class="error-url">*<?=$website['not_found_content']?></p>
                        </div>
                    </div>
                </div>
                <a href="#" class="btn-addItem">+ <?=$website['order_note17']?></a>
            </div>
        </div>
        <script>
            $(document).on('click','.have-link',function(e) {
                var st = $(this).attr('st');
                $('.link-'+st).toggle('fast');
            })
        </script>
        <div class="col-md-5">
            <div class="box-info">
                <h2 class="section__title" id="main-header" tabindex="-1">
					<?=$website['order_note']?>
                </h2>
                <div class="field__input-wrapper">
                    <input type="text" id="fullname" name="fullname" value="<?=empty($order->fullname) ? '' : $order->fullname?>" placeholder="<?=$website['order_note3']?>">
                </div>
                <div class="field__input-wrapper">
                    <input type="text" id="email" name="email" value="<?=empty($order->email) ? '' : $order->email?>" placeholder="Email">
                </div>
                <div class="field__input-wrapper">
                    <input type="text" id="phone" name="phone" value="<?=empty($order->phone) ? '' : $order->phone?>" placeholder="<?=$website['order_note4']?>" >
                </div>
                <div class="field__input-wrapper">
                    <input type="text" id="address" name="address" value="<?=empty($order->address) ? '' : $order->address?>" placeholder="<?=$website['order_note5']?>">
                </div>
                <div class="field__input-wrapper">
                    <textarea name="message" id="message" name="message" class="field__input" cols="30" rows="3" placeholder="<?=$website['order_note6']?>"></textarea>
                </div>
                <div class="g-recaptcha" data-theme="light" data-sitekey="<?=RECAPTCHA_KEY?>"></div>
                <div class="step__footer" data-step-footer>
                    <a id="continue_button" class="step__footer__continue-btn btn" aria-busy="false">
                        <span class="btn__content" data-continue-button-content="true"><?=$website['order_note16']?></span>
                    </a>
                    <a class="step__footer__previous-link" href="<?=site_url('gio-hang')?>">
                        <span class="step__footer__previous-link-content"><?=$website['order_note7']?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="suggest-product">
        <h3><?=$website['order_note18']?></h3>
        <div class="row">
            <? foreach($suggest_products as $suggest_product) { ?>
            <div class="col-sm-6 col-lg-3">
                <div class="box-suggest-product">
                    <span class="tick-<?=$suggest_product->id?> tick">&#10003;</span>
                    <div class="box-image">
                        <img src="<?=$suggest_product->thumbnail?>" alt="">
                    </div>
                    <h5 class="name limit-content-2-line">
                        <?=character_limiter($suggest_product->{$prop['title']},28)?>
                    </h5>
                    <div class="name-hidden" style="display:none;">
                        <?=$suggest_product->{$prop['title']}?>
                    </div>
                    <div class="content-hidden" style="display:none;">
                        <?=$suggest_product->{$prop['content']}?>
                    </div>
                    <div class="group-btn">
                        <div class="row">
                            <div class="col-md-6">
                                <a class="add-product add-item " data-st="0" data-id="<?=$suggest_product->id?>" data-url="<?=site_url($suggest_product->{$prop['alias']})?>">
                                    <?=$website['order_note17']?>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a class="view-detail" data-toggle="modal" data-target="#view-detail-modal" data-url="<?=site_url($suggest_product->{$prop['alias']})?>">
                                    <?=$website['view_detail']?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <? } ?>
        </div>
    </div>
    <script>
        $(document).on('change','.upload-file-order',function(e){
            var file_id = $(this).attr("file_id");
            readURL(this,file_id);
        })
        function readURL(input, file_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.custom-file-upload-'+file_id).css({
                        "background-image": "url('"+e.target.result+"')"
                    });
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
<!-- Modal -->
<div class="modal fade" id="view-detail-modal" tabindex="-1" role="dialog" aria-labelledby="view-detail-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="view-detail-modalLabel"></h5>
      </div>
      <div class="modal-body">
          <img class="modal-image" src="" width="170px" alt="">
        <div class="content"></div>
        <div class="view-more"><a target="_blank" href=""><?=$website['view_detail']?></a></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=$website['lose']?></button>
      </div>
    </div>
  </div>
</div>
    <script>
        $(document).on('click','.view-detail',function(e){
            var url = $(this).attr('data-url');
            var name = $(this).parents('.box-suggest-product').find('.name-hidden').html();
            var img = $(this).parents('.box-suggest-product').find('.box-image > img').attr('src');
            var content = $(this).parents('.box-suggest-product').find('.content-hidden').html();
            $('#view-detail-modal .modal-title').html(name);
            $('#view-detail-modal .content').html(content);
            $('#view-detail-modal .view-more > a').attr('href',url);
            $('#view-detail-modal .modal-image').attr('src',img);
        })
        $(document).on('click','.add-item',function(e){
            var st = parseInt($(this).attr('data-st'));
            var id = parseInt($(this).attr('data-id'));
            if (st == 0){
                var url = $(this).attr('data-url');
                $.ajax({
                    url: '<?=site_url("call-service/fetch-data")?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {'url':url},
                    success: function (res) {
                        if (res != 0) {
                            var str = '<div class="order-item order-item-'+id+'">';
                                str += '<div class="del"><?=$website['remove_product']?></div>';
                                str += '<div class="box">';
                                    str += '<div class="box-left">';
                                        str += '<div class="name"><input type="text" class="name-item" name="name[]" value="'+res[0]+'" placeholder="<?=$website['enter_product_name']?>"></div>';
                                        str += '<div style="display:table;width:100%;">';
                                            str += '<p class="have-link" st="'+id+'"><?=$website['have_link']?></p>';
                                            str += '<div style="display:table-cell;">';
                                                str += '<div class="quantity">';
                                                    str += '<span class="ctrl-qty">-</span><input type="number" data-lpignore="true" class="qty" min=1 value="1" name="quantity[]"><span class="ctrl-qty">+</span>';
                                                str += '</div>';
                                            str += '</div>';
                                        str += '</div>';
                                    str += '</div>';
                                    str += '<div class="box-right">';
                                        str += '<label class="custom-file-upload custom-file-upload-'+id+'" style="background-image:url('+res[1]+')">';
                                            str += '<input type="file" name="thumbnail_'+id+'" file_id = "'+id+'" class="upload-file-order"/>';
                                            str += '<input type="hidden" name="stt[]" value="'+id+'">';
                                            str += '<input type="hidden" name="url_img[]" value="'+res[1]+'">';
                                        str += '</label>';
                                    str += '</div>';
                                str += '</div>';
                                str += '<div class="link link-'+id+'" st="'+id+'">';
                                    str += '<input type="text" name="url[]" value="" placeholder="<?=$website['paste_link']?>">';
                                    str += '<p class="error-url">*<?=$website['not_found_content']?></p>';
                                str += '</div>';
                            str += '</div>';
                            $('.wrap-order-item').append(str);
                        }
                    }
                })
                $(this).attr('data-st',1);
                $(this).parents('.box-suggest-product').find('.tick').addClass('active');
                $(this).addClass('active');
                $(this).html('<?=$website['remove_product']?>');
            } else {
                $('.order-item-'+id).remove();
                $(this).attr('data-st',0);
                $(this).parents('.box-suggest-product').find('.tick').removeClass('active');
                $(this).removeClass('active');
                $(this).html('<?=$website['order_note17']?>');
            }
        });
    </script>
    </div>
</div>
<script>
    // $(document).on('change','.link > input',function(e){
    //     var loading = '<div class="box-loading"><img src="<?=IMG_URL?>loading.gif" alt=""></div>';
    //     var st = $(this).parents('.link').attr('st');
    //     $(this).parents('.order-item').find('.error-url').css('display','none');
    //     $(this).parents('.order-item').find('.box').prepend(loading);
    //     $.ajax({
    //         url: '<?=site_url("call-service/fetch-data")?>',
    //         type: 'POST',
    //         dataType: 'json',
    //         data: {'url':$(this).val()},
    //         success: function (res) {
    //             if (res != 0) {
    //             // var str = '<div class="del"><?=$website['remove_product']?></div>';
    //             //     str += '<div class="box">';
    //             //         str += '<div class="box-left">';
    //             //             str += '<div class="name"><input type="text" class="name-item" name="name[]" placeholder="<?=$website['enter_product_name']?>"></div>';
    //             //             str += '<div class="quantity">';
    //             //                 str += '<span class="ctrl-qty">-</span><input type="number" data-lpignore="true" class="qty" min=1 value="1" name="quantity[]"><span class="ctrl-qty">+</span>';
    //             //             str += '</div>';
    //             //         str += '</div>';
    //             //         str += '<div class="box-right">';
    //             //             str += '<label class="custom-file-upload custom-file-upload-'+st+'" style="background-image:url(<?=IMG_URL.'image-default.png'?>)">';
    //             //                 str += '<input type="file" name="thumbnail_'+st+'" file_id = "'+st+'" class="upload-file-order"/>';
    //             //                 str += '<input type="hidden" name="stt[]" value="'+st+'">';
    //             //                 str += '<input type="hidden" class="url-img" name="url_img[]" value="">';
    //             //             str += '</label>';
    //             //         str += '</div>';
    //             //     str += '</div>';
    //                 // str += '<p class="have-link" st="'+st+'"><?=$website['have_link']?></p>';
    //                 // str += '<div class="link link-'+st+'" st="'+st+'">';
    //                 //     str += '<input type="text" name="url[]" value="" placeholder="<?=$website['paste_link']?>">';
    //                 //     str += '<p class="error-url">*<?=$website['not_found_content']?></p>';
    //                 // str += '</div>';
    //                 $('.order-item-'+st).find('.name > input').val(res[0]);
    //                 $('.order-item-'+st).find('.custom-file-upload').css('background','url('+res[1]+')');
    //                 $('.order-item-'+st).find('.url-img').val(res[1]);
    //             } else {
    //                 $('.order-item-'+st).find('.error-url').css('display','block');
    //             }
    //             $('.order-item-'+st).find('.box-loading').remove();
    //         }
    //     })
    // })
    $('.btn-addItem').click(function(e) {
        var date = new Date();
        var str = '<div class="order-item order-item-'+date.getTime()+'">';
                str += '<div class="del"><?=$website['remove_product']?></div>';
                str += '<div class="box">';
                    str += '<div class="box-left">';
                        str += '<div class="name"><input type="text" class="name-item" name="name[]" placeholder="<?=$website['enter_product_name']?>"></div>';
                        str += '<div style="display:table;width:100%;">';
                            str += '<p class="have-link" st="'+date.getTime()+'"><?=$website['have_link']?></p>';
                            str += '<div style="display:table-cell;">';
                                str += '<div class="quantity">';
                                    str += '<span class="ctrl-qty">-</span><input type="number" data-lpignore="true" class="qty" min=1 value="1" name="quantity[]"><span class="ctrl-qty">+</span>';
                                str += '</div>';
                            str += '</div>';
                        str += '</div>';
                    str += '</div>';
                    str += '<div class="box-right">';
                        str += '<label class="custom-file-upload custom-file-upload-'+date.getTime()+'" style="background-image:url(<?=IMG_URL.'image-default.png'?>)">';
                            str += '<input type="file" name="thumbnail_'+date.getTime()+'" file_id = "'+date.getTime()+'" class="upload-file-order"/>';
                            str += '<input type="hidden" name="stt[]" value="'+date.getTime()+'">';
                            str += '<input type="hidden" class="url-img" name="url_img[]" value="">';
                            str += '<p><?=$website['attached_img']?></p>';
                        str += '</label>';
                    str += '</div>';
                str += '</div>';
                str += '<div class="link link-'+date.getTime()+'" st="'+date.getTime()+'">';
                    str += '<input type="text" name="url[]" value="" placeholder="<?=$website['paste_link']?>">';
                    str += '<p class="error-url">*<?=$website['not_found_content']?></p>';
                str += '</div>';
            str += '</div>';
        $('.wrap-order-item').append(str);
    })
    $(document).on('click','.del',function(e){
        var st = $(this).parents('.order-item').find('.link').attr('st');
        $('.tick-'+st).removeClass('active');
        $('.tick-'+st).parents('.box-suggest-product').find('.add-item').removeClass('active');
        $('.tick-'+st).parents('.box-suggest-product').find('.add-item').html('<?=$website['order_note17']?>');
        $(this).parents('.order-item').remove();
    })
    $(document).on('click','.ctrl-qty',function(e){
        var st = $(this).html();
        var qty = parseInt($(this).parents('.quantity').find('.qty').val());
        if (st == '-'){
            if (qty > 1) {
                qty -= 1;
            }
        } else {
            qty += 1;
        }
        $(this).parents('.quantity').find('.qty').val(qty);
    })
    $(document).on('change','.qty',function(e){
        var qty = parseInt($(this).val());
        if (qty < 1)
        $(this).val(1);
    })
</script>
<script>
$(document).ready(function() {
    $(document).on('click','#continue_button',function(e) {
        var err = 0;
        var msg = [];
        function validatePhone(new_phone) {
            var a = $('#'+new_phone).val();
            var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
            if (filter.test(a)) {
                return true;
            }
            else {
                return false;
            }
        }
        if ($("#fullname").val() == "") {
            $("#fullname").addClass("error");
			err++;
			msg.push("<?=$website['error_note1']?>");
        }
        if ($("#email").val() == "") {
            $("#email").addClass("error");
			err++;
			msg.push("<?=$website['error_note2']?>");
        }
        if ($("#phone").val() == "") {
            $("#phone").addClass("error");
			err++;
			msg.push("<?=$website['error_note3']?>");
        } else {
            if (validatePhone('phone') == false) {
                $("#phone").addClass("error");
                err++;
                msg.push("<?=$website['error_note7']?>");
            }
        }
        if ($("#address").val() == "") {
            $("#address").addClass("error");
			err++;
			msg.push("<?=$website['error_note8']?>");
        }
        var ek = $('.name-item').map((_,el) => el.value).get();
        if (ek.length == 0 || (ek.length == 1 && ek[0] == '')) {
            err++;
			msg.push("<?=$website['error_note18']?>");
        }
        if ($('#g-recaptcha-response').val() == "") {
            err++;
            msg.push("<?=$website['error_note19']?>");
        }
                
        if (err==0) {
            $("#frm-checkout").submit();
        } else {
            showErrorMessage('<?=$website['error']?>','<?=$website['error_note']?>', msg, '<?=$website['lose']?>');
        }
    });
});
</script>

