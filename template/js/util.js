function clearFormError()
{
	$("form input:text").removeClass("error");
	$("form input:password").removeClass("error");
	$("form select").removeClass("error");
	$("form textarea").removeClass("error");
}
function slug(text)
{
	var slug = '';
	slug = text.toLowerCase();
 
    //Đổi ký tự có dấu thành không dấu
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');
    //Xóa các ký tự đặt biệt
    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
    //Đổi khoảng trắng thành ký tự gạch ngang
    slug = slug.replace(/ /gi, "-");
    //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
    //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
    slug = slug.replace(/\-\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-/gi, '-');
    slug = slug.replace(/\-\-/gi, '-');
    //Xóa các ký tự gạch ngang ở đầu và cuối
    slug = '@' + slug + '@';
    slug = slug.replace(/\@\-|\-\@|\@/gi, '');

    return slug;
}
function daysInMonth(m, y) // m is 0 indexed: 0-11
{
	switch (m) {
		case 1 :
			return (y % 4 == 0 && y % 100) || y % 400 == 0 ? 29 : 28;
		case 8 : case 3 : case 5 : case 10 :
			return 30;
		default :
			return 31;
	}
}

function isEmail(text)
{
	var pattern = "^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$";
	var regex = new RegExp(pattern);
	return regex.test(text);
}
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
function checkNumber(input)
{
	var pattern="0123456789";
	var len = input.value.length;
	if (len != 0) {
		var index = 0;
		while ((index < len) && (len != 0)) {
			if (pattern.indexOf(input.value.charAt(index)) == -1)
			{
				if (index == len-1) {
					input.value=input.value.substring(0,len-1);
				} else if(index == 0) {
					input.value=input.value.substring(1,len);
				} else {
					input.value=input.value.substring(0,index)+input.value.substring(index+1,len);index=0;len=input.value.length;
				}
			}
			else {
				index++;
			}
		}
	}
}

function confirm_box(title, message,api,lose)
{
	if (!$("#confirm-dialog").length) {
		$("body").append('<div id="confirm-dialog" class="modal-error modal fade" data-backdrop="static" data-keyboard="false"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Modal title</h4></div><div class="modal-body"><p>&hellip;</p></div><div class="modal-footer"><button type="button" class="btn btn-primary btn-confirm-dialog-ok" api='+api+'>Ok</button> <button type="button" class="btn btn-default" data-dismiss="modal">'+lose+'</button></div></div></div></div>');
	}
	
	$("#confirm-dialog").find(".modal-title").html(title);
	$("#confirm-dialog").find(".modal-body").html(message);
	$("#confirm-dialog").modal();
}

function confirmBox(title, message, callback, params)
{
	if (!$("#confirm-dialog").length) {
		$("body").append('<div id="confirm-dialog" class="modal-error modal fade" data-backdrop="static" data-keyboard="false"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Modal title</h4></div><div class="modal-body"><p>&hellip;</p></div><div class="modal-footer"><button type="button" class="btn btn-default btn-confirm-dialog-ok">OK</button> <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button></div></div></div></div>');
	}
	
	$("#confirm-dialog").find(".modal-title").html(title);
	$("#confirm-dialog").find(".modal-body").html(message);
	
	$("#confirm-dialog").find(".btn-confirm-dialog-ok").click(function(event) {
		var fn = window[callback];
		if (!(params instanceof Array)) {
			params = [params];
		}
		if (typeof fn === "function") {
			fn.apply(null, params);
		}
		$("#confirm-dialog").modal("hide");
	});
	
	$("#confirm-dialog").modal();
}

function messageBox(type, title, message, lose)
{ 
	if (!$("#dialog").length) {
		$("body").append('<div id="dialog" class="modal-error modal fade" data-backdrop="static" data-keyboard="false"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Modal title</h4></div><div class="modal-body"><p>&hellip;</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">'+lose+'</button></div></div></div></div>');
	}
	
	$("#dialog").removeClass("modal-error");
	$("#dialog").removeClass("modal-info");
	$("#dialog").removeClass("modal-warning");
	
	if (type == "INFO") {
		$("#dialog").addClass("modal-info");
	} else if (type == "WARNING") {
		$("#dialog").addClass("modal-warning");
	} else {
		$("#dialog").addClass("modal-error");
	}
	
	$("#dialog").find(".modal-title").html(title);
	$("#dialog").find(".modal-body").html(message);
	$("#dialog").modal();
}

function showErrorMessage(title,note,arrMsg,lose)
{
	var errmsg = "<p>"+note+"</p>";
	errmsg += "<ul>";
	for (var i=0; i<arrMsg.length; i++) {
		errmsg += "<li>"+arrMsg[i]+"</li>";
	}
	errmsg += "</ul>";
	messageBox("ERROR", title, errmsg,lose);
}
function slide(selector, item, margin) {
	var owl = $(selector).find('.owl-carousel');

	owl.owlCarousel({
		loop:true,
		margin:margin,
		nav:false,
		dots:false,
		lazyLoad:true,
		responsive:{
			0:{
				items:2
			},
			600:{
				items:3
			},
			1000:{
				items:item
			}
		}
	})
	
	owl.owlCarousel();
	$(selector+' .ctrl-slide .fa-chevron-right').click(function() {
		owl.trigger('next.owl.carousel');
	})
	$(selector+' .ctrl-slide .fa-chevron-left').click(function() {
		owl.trigger('prev.owl.carousel');
	})

}
function getParams(url) {
	var params = {};
	var parser = document.createElement('a');
	parser.href = url;
	var query = parser.search.substring(1);
	var vars = query.split('&');
	for (var i = 0; i < vars.length; i++) {
		var pair = vars[i].split('=');
		params[pair[0]] = decodeURIComponent(pair[1]);
	}
	return params;
};
function cleanObject(obj) {
	for (var propName in obj) {
		if (obj[propName] === null || obj[propName] === undefined || obj[propName] === "undefined") {
			delete obj[propName];
		}
	}
	return obj
}
function removeArr(arr) {
	var what, a = arguments, L = a.length, ax;
	while (L > 1 && arr.length) {
	what = a[--L];
	while ((ax= arr.indexOf(what)) !== -1) {
			arr.splice(ax, 1);
		}
	}
	return arr;
}
function formatDollar(num) {
	var p = num.toFixed(2).split(".");
	return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
		return  num=="-" ? acc : num + (i && !(i % 3) ? "." : "") + acc;
	}, "");
}
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
function addToCart(class_selector,title,message,gotocart,lose,checkout) {
	$(document).on('click','.'+class_selector,function(){
		let id 			= $(this).attr('title');
		let typename 	= $("input[name='typename']:checked").val();
		let subtypename = $("input[name='subtypename']:checked").val();
		let quantity 	= $("#Quantity").val();
		if (typename == undefined) { typename = null; }
		if (subtypename == undefined) { subtypename = null; }
		let p = {};
			p["id"] 			= id;
			p["typename"] 		= typename;
			p["subtypename"] 	= subtypename;
			p["quantity"] 		= quantity;
		if (quantity > 0) {
			$.ajax({
				url: BASE_URL+'/gio-hang/ajax-add-cart.html',
				type: 'POST',
				dataType: 'json',
				data: p,
				success: function (result) {
					messageBoxCart(title,message,gotocart,lose,checkout);
					$('#_desktop_cart_count').html('<span id="CartCount">'+Object.keys(result).length+'</span>');
					$('#qty_cart_item').html(Object.keys(result).length);
				}
			});
		}
	});
}
function messageBoxCart(title, message, gotocart, lose, checkout)
{
	if (!$("#dialog").length) {
		$("body").append('<div id="dialog" class="modal-info modal fade" data-backdrop="static" data-keyboard="false"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">'+title+'</h4></div><div class="modal-body"><p>&hellip;</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">'+lose+'</button><a class="btn btn-secondary" href="'+BASE_URL+'/gio-hang.html'+'">'+checkout+'</a></div></div></div></div>');
	}
	let str 	= '<div class="cart-message"><i class="zmdi zmdi-notifications-active" style="margin-right: 5px;"></i> <strong> '+message+'</strong> - '+gotocart+'</div>';
	$("#dialog").find(".modal-body").html(str);
	$("#dialog").modal();
}
function rating({selected,items,position,note,noteDefault}){
	for (var i = 1; i <= items; i++) {
		if (i > position)
		$(selected).append('<i style="color: #fec109;padding: 0 5px;font-size: 20px;" class="far fa-star rating-star-popup stars-popup-'+i+'" stars-id="'+i+'"></i>');
		else
		$(selected).append('<i style="color: #fec109;padding: 0 5px;font-size: 20px;" class="fas fa-star rating-star-popup stars-popup-'+i+'" stars-id="'+i+'"></i>');
	}
	$(selected).prepend('<input type="hidden" id="position-star" value="'+position+'"><input type="hidden" id="note-star" value="'+position+'">');
	var note_star = $('#position-star').val();
	if (note_star > 0)
		$(selected).append('<span style="font-size: 16px;color: #fec109;" class="feel-popup"> - '+note[note_star-1]+'</span>');
	else
		$(selected).append('<span style="font-size: 16px;color: #fec109;" class="feel-popup"> - '+noteDefault+'</span>');
	$('.rating-star-popup').hover(function() {
		var stars_id = $(this).attr("stars-id");
		for (var i = 1; i <= items; i++) {
			if (i <= stars_id){
				$('.stars-popup-'+i).removeClass('far');
				$('.stars-popup-'+i).addClass('fas');
			} else {
				$('.stars-popup-'+i).removeClass('fas');
				$('.stars-popup-'+i).addClass('far');
			}
		}
		$('.feel-popup').html(' - '+note[stars_id-1]);
	},function() {
		var stars_id = $('#position-star').val();
		for (var i = 1; i <= items; i++) {
			if (i <= stars_id){
				$('.stars-popup-'+i).removeClass('far');
				$('.stars-popup-'+i).addClass('fas');
			} else {
				$('.stars-popup-'+i).removeClass('fas');
				$('.stars-popup-'+i).addClass('far');
			}
		}
		if (stars_id > 0)
		$('.feel-popup').html(' - '+note[stars_id-1]);
		else
		$('.feel-popup').html(' - '+noteDefault);
	});
	$('.rating-star-popup').click(function(event) {
		var stars_id = $(this).attr("stars-id");
		$('#position-star').val(stars_id);
		$('#note-star').val(stars_id);
	});
}
function format_date(full_date) {
	full_date = full_date.split(' ');
	let time = full_date[1];
	let date = full_date[0].split('-');
	time = time.substring(0,5);
	date = date[2]+'/'+date[1]+'/'+date[0];
	return time+', '+date;
}
function short_name(full_name) {
	full_name = full_name.split(' ');
	if (full_name.length > 1){
		full_name = full_name[0].substring(0,1)+full_name[full_name.length-1].substring(0,1);
	} else {
		full_name = full_name[full_name.length-1].substring(0,2);
	}
	return full_name;
}
function searchProduct(select,hot_key,not_found_key,result_search_product,not_found_search_product,readmore) {
	var box = $(select).parents('.search-box').find('.list-keyword');
	$(select).click(function (){
		box.css('display','block');
	})
	$(select).keyup(function(){
		let text = $(this).val();
		$.ajax({
			url: BASE_URL+'/tim-kiem-ajax.html',
			type: 'GET',
			dataType: 'json',
			data: {search_text:text},
			success: function (res) {
				let str = '<div class="title-search"><strong><i class="zmdi zmdi-key"></i> '+not_found_key+'</strong></div>';
				if (res[0] !='') {
					str = '<div class="title-search"><strong><i class="zmdi zmdi-key"></i> '+hot_key+'</strong></div>';
					for(let i=0;i<res[0].length;i++) {
						str += '<div class="item" data="'+res[0][i].keyword+'">';
							str += '<i class="zmdi zmdi-search"></i> '+res[0][i].keyword;
						str += '</div>';
					}
				}
				let str_p = '<div class="title-search"><strong><i class="zmdi zmdi-dropbox"></i> '+not_found_search_product+'</strong></div>'
				if (res[1] !='') {
					str_p 	= '<div class="title-search"><strong><i class="zmdi zmdi-dropbox"></i> '+result_search_product+'</strong></div>';
					str_p  += '<div class="row">';
					for(let i=0;i<res[1].length;i++) {
						let price = res[1][i].price;
						let price_sale = price*(1-(res[1][i].sale*0.01));
						if (res[2] == 'en'){
							str_p += '<div class="col-md-6"><a href="'+BASE_URL+'/'+res[1][i].alias_en+'.html?loai='+slug(res[1][i].typename)+'"><div class="search-product">';
						} else {
							str_p += '<div class="col-md-6"><a href="'+BASE_URL+'/'+res[1][i].alias+'.html?loai='+slug(res[1][i].typename)+'"><div class="search-product">';
						}
							str_p += '<div class="img-product"><img src="'+BASE_URL+res[1][i].thumbnail.replace('./','/')+'"></div>';
							str_p += '<div class="info-product">';
								if (res[2] == 'en') 
								str_p += '<h6 class="name limit-content-2-line">'+res[1][i].title_en+'</h6>';
								else
								str_p += '<h6 class="name limit-content-2-line">'+res[1][i].title+'</h6>';
								str_p += '<span class="typename">'+res[1][i].typename+'</span>';
								str_p += '<div class="wrap-price">';
									str_p += '<span class="price-sale">'+formatDollar(parseInt(price_sale))+'</span>';
									if (res[1][i].sale !=0)
									str_p += '<span class="price">'+formatDollar(parseInt(price))+'</span>';
								str_p += '</div>';
							str_p += '</div>';
						str_p += '</div></a></div>';
					}
					str_p  += '</div>';
					if (res[1].length==6){
						str_p  += '<div class="text-center"><a class="search-more" href="#" style="cursor:pointer">'+readmore+'</a></div>';
					}
				}
				str += str_p;
				box.html(str);
			}
		});
	})
}
function addLike(selector) {
	$(document).on('click',selector,function() {
		var st = $(this).attr('status');
		if (st != "0"){
			var product_id = $(this).attr('id_item');
			var p = {};
			if (parseInt(st) == 1){ 
				$(selector+'-'+product_id).removeClass('removed-wishlist');
				$(selector+'-'+product_id).addClass('added-wishlist');
				$(selector+'-'+product_id).attr('status',2);
			} else if (parseInt(st) == 2){
				$(selector+'-'+product_id).removeClass('added-wishlist');
				$(selector+'-'+product_id).addClass('removed-wishlist');
				$(selector+'-'+product_id).attr('status',1);
			}
			p['status'] 	= st;
			p['product_id'] = product_id;
			$.ajax({
				url: BASE_URL+'/call-service/like-product.html',
				type: 'POST',
				dataType: 'json',
				data: p,
				success: function (res) { 
					
				}
			});
		} else {
			window.location.href= BASE_URL+'/tai-khoan.html';
		}
	})
}