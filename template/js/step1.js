$(document).ready(function() {
	$(".passport_holder").change(function(){
		genVisaTypeOptions();
	});
	$(".group_size").change(function(){
		updatePanel();
	});
	$(".visa_type").change(function(){
		genVisitOptions();
		updatePanel();
	});
	$(".visit_purpose").change(function(){
		updatePanel();
	});
	$(".arrival_port").change(function(){
		updatePanel();
	});
	$(".arrival_year").change(function(){
		checkArrivalDate();
		checkProcessingTime();
		updatePanel();
	});
	$(".arrival_month").change(function(){
		checkArrivalDate();
		checkProcessingTime();
		updatePanel();
	});
	$(".arrival_date").change(function(){
		checkProcessingTime();
		updatePanel();
	});
	$(".exit_year").change(function(){
		checkExitDate();
	});
	$(".exit_month").change(function(){
		checkExitDate();
	});
	$(".exit_date").change(function(){
		checkExitDate();
	});
	$(".processing_time").change(function(){
		updatePanel();
	});
	$(".private_visa").change(function(){
		updatePanel();
	});
	$(".full_package").change(function(){
		updatePanel();
	});
	$(".fast_checkin").change(function(){
		updatePanel();
	});
	$(".car_pickup").change(function(){
		updatePanel();
	});
	$(".car_type").change(function(){
		updatePanel();
	});
	$(".num_seat").change(function(){
		updatePanel();
	});
	
	$(".btn-apply-code").click(function(){
		var processing_time = $("input[name='processing_time']:checked").val();
		var visa_type = $('#visa_type').val();
		var promotion_input = $('.promotion-input').val();
		
		var p = {};
		p['visa_type'] = visa_type;
		p['processing_time'] = processing_time;
	    p['code'] = promotion_input;
		
	    $('.promotion-error').hide();
	    
	    $.ajax({
			type: "POST",
			url: BASE_URL + "/apply-visa/apply-code.html",
			data: p,
			dataType: "html",
			success: function(result) {
				if (result != "") {
					$('.promotion-error').hide();
					$('#promotion-box-input').hide();
					$('#promotion-box-succed').show();
					updatePanel();
				} else {
					$('.promotion-error').show();
					$('#promotion-input').select();
					$('#promotion-box-input').show();
					$('#promotion-box-succed').hide();
				}
			}
		});
	});
	
	$(".btn-next").click(function(){
		var err = 0;
		var msg = new Array();
		
		if ($(".passport_holder :selected").val() == "") {
			$(".passport_holder").addClass("error");
			//err++;
			//msg.push("Passport holder is required.");
		} else {
			//$(".passport_holder").removeClass("error");
		}
		
		if ($(".group_size :selected").val() == "") {
			$(".group_size").addClass("error");
			err++;
			msg.push("Number of visa is required.");
		} else {
			$(".group_size").removeClass("error");
		}
		
		if ($(".visa_type :selected").val() == "") {
			$(".visa_type").addClass("error");
			err++;
			msg.push("Type of visa is required.");
		} else {
			$(".visa_type").removeClass("error");
		}
		
		if ($(".visit_purpose :selected").val() == "") {
			$(".visit_purpose").addClass("error");
			err++;
			msg.push("Purpose of visit is required.");
		} else {
			$(".visit_purpose").removeClass("error");
		}
		
		if ($(".arrival_port :selected").val() == "" || $(".arrival_port :selected").val() == "0") {
			$(".arrival_port").addClass("error");
			err++;
			msg.push("Arrival airport is required.");
		} else {
			$(".arrival_port").removeClass("error");
		}
		
		if ($(".arrival_date").val() == "") {
			$(".arrival_date").addClass("error");
			err++;
			var txt = "Arrival date is required.";
			if (msg.indexOf(txt) == -1) {
				msg.push(txt);
			}
		} else {
			$(".arrival_date").removeClass("error");
		}
		if ($(".arrival_month").val() == "") {
			$(".arrival_month").addClass("error");
			err++;
			var txt = "Arrival date is required.";
			if (msg.indexOf(txt) == -1) {
				msg.push(txt);
			}
		} else {
			$(".arrival_month").removeClass("error");
		}
		if ($(".arrival_year").val() == "") {
			$(".arrival_year").addClass("error");
			err++;
			var txt = "Arrival date is required.";
			if (msg.indexOf(txt) == -1) {
				msg.push(txt);
			}
		} else {
			$(".arrival_year").removeClass("error");
		}
		
		if ($(".exit_date").val() == "") {
			$(".exit_date").addClass("error");
			err++;
			var txt = "Exit date is required.";
			if (msg.indexOf(txt) == -1) {
				msg.push(txt);
			}
		} else {
			$(".exit_date").removeClass("error");
		}
		if ($(".exit_month").val() == "") {
			$(".exit_month").addClass("error");
			err++;
			var txt = "Exit date is required.";
			if (msg.indexOf(txt) == -1) {
				msg.push(txt);
			}
		} else {
			$(".exit_month").removeClass("error");
		}
		if ($(".exit_year").val() == "") {
			$(".exit_year").addClass("error");
			err++;
			var txt = "Exit date is required.";
			if (msg.indexOf(txt) == -1) {
				msg.push(txt);
			}
		} else {
			$(".exit_year").removeClass("error");
		}
		
		if (err == 0) {
			// Check date time
			var date		= new Date();
			var currentDate	= new Date(date.getFullYear(), date.getMonth(), date.getDate());
			var arrivalDate	= new Date($(".arrival_year").val(),$(".arrival_month").val()-1,$(".arrival_date").val());
			var exitDate	= new Date($(".exit_year").val(),$(".exit_month").val()-1,$(".exit_date").val());
			if (arrivalDate.getTime() < currentDate.getTime()) {
				$(".arrival_date").addClass("error");
				$(".arrival_month").addClass("error");
				$(".arrival_year").addClass("error");
				msg.push("Arrival date must be greater than current date!");
				err++;
			}
			if (err == 0) {
				if (arrivalDate.getTime() >= exitDate.getTime()) {
					$(".exit_date").addClass("error");
					$(".exit_month").addClass("error");
					$(".exit_year").addClass("error");
					msg.push("Exit date must be greater than Arrival date!");
					err++;
				}
			}
			if (err == 0) {
				var max_travel_days = checkTravelDays();
				if (max_travel_days) {
					$(".exit_date").addClass("error");
					$(".exit_month").addClass("error");
					$(".exit_year").addClass("error");
					msg.push("The days of stay in Vietnam is longer than "+max_travel_days+" days. Please change your type of visa or correct the exit date.");
					err++;
				}
			}
		}
		
		if (err == 0) {
			return true;
		}
		else {
			showErrorMessage(msg);
			return false;
		}
	});
	
	updatePanel();
	checkExitDate();
	checkProcessingTime();
});

function updatePanel()
{
	onPassportHolderChanged();
	onApplicantChanged();
	onVisaTypeChanged();
	onPurposeChanged();
	onArrivalPortChanged();
	onRushChanged();
	onPrivateLetterChanged();
	onServiceChanged();
	calServiceFees();
}

function onPassportHolderChanged()
{
	$(".passport_holder_t").html($(".passport_holder :selected").text());
}

function onApplicantChanged()
{
	$(".group_size_t").html($(".group_size :selected").text());
}

function onVisaTypeChanged()
{
	var type_of_visa = $(".visa_type :selected").val();
	
	if (type_of_visa == "1ms") {
		$(".visa_type_t").html($(".visa_type :selected").text()+"<br/>(less than 30 days stay, only 1 time entry/exit)");
	}
	if (type_of_visa == "3ms") {
		$(".visa_type_t").html($(".visa_type :selected").text()+"<br/>(less than 90 days stay, only 1 time entry/exit)");
	}
	if (type_of_visa == "1mm") {
		$(".visa_type_t").html($(".visa_type :selected").text()+"<br/>(less than 30 days stay, multiple times of entry/exit)");
	}
	if (type_of_visa == "3mm") {
		$(".visa_type_t").html($(".visa_type :selected").text()+"<br/>(less than 90 days stay, multiple times of entry/exit)");
	}
	if (type_of_visa == "6mm") {
		$(".visa_type_t").html($(".visa_type :selected").text()+"<br/>(less than 180 days stay, multiple times of entry/exit)");
	}
	if (type_of_visa == "1ym") {
		$(".visa_type_t").html($(".visa_type :selected").text()+"<br/>(less than 1 year stay, multiple times of entry/exit)");
	}
}

function genVisaTypeOptions()
{
	var p = {};
	p["passport_holder"] = $(".passport_holder :selected").val();
	
	$.ajax({
		type: "POST",
		url: BASE_URL + "/apply-visa/ajax-cal-visa-types.html",
		data: p,
		dataType: "json",
		success: function(result) {
			var visaTypes = document.getElementById("visa_type");
			if (visaTypes.length > 0) {
				for (var i=(visaTypes.length-1); i>=0; i--) {
					visaTypes.remove(i);
				}
			}
			
			var _1ym_available = false;
			for (var i=0; i<result.length; i++) {
				var option = document.createElement("option");
				option.text = result[i];
				option.value = result[i].match(/\b(\w)/g).join('');
				if (option.value == "1ym") {
					_1ym_available = true;
				}
				visaTypes.add(option);
			}
			
			$(".visa_type").trigger("change");
		},
		async: false
	});
	
	var nation  = $(".passport_holder :selected").val();
	var free15d = ["United Kingdom", "Great Britain", "France", "Germany", "Spain", "Italy"];
	var isFree  = (free15d.indexOf(nation) != -1);
	
	if (isFree && ($("#visa_type").val() == "1ms")) {
		var msg = "<h3>VIETNAM VISA FOR "+nation.toUpperCase()+" NATIONALITY</h3>";
			msg += "<p>Vietnam has been exemption 15 days visa for traveler from "+nation.toUpperCase()+". If you stay in Vietnam more than 15 days or wish to get visa to Vietnam, please ignore this message.</p>";
			msg += "<p><a target='_blank' href='http://www.vietnamvisateam.com/news/vietnam-likely-to-scrap-visas-for-uk-france-australia-and-more.html'>&rarr; View more about exemption 15 days visa.</a></p>";
		messageBox("INFO", "About Vietnam visa for "+nation+" nationality", msg);
	}
}

function genVisitOptions()
{
	var p = {};
	p["passport_holder"] = $(".passport_holder :selected").val();
	p["visa_type"] = $(".visa_type :selected").val();
	
	$.ajax({
		type: "POST",
		url: BASE_URL + "/apply-visa/ajax-cal-visit-purposes.html",
		data: p,
		dataType: "json",
		success: function(result) {
			var visitPurposes = document.getElementById("visit_purpose");
			if (visitPurposes.length > 0) {
				for (var i=(visitPurposes.length-1); i>=0; i--) {
					visitPurposes.remove(i);
				}
			}
			
			for (var i=0; i<result.length; i++) {
				var option = document.createElement("option");
				if (result[i] == "") {
					option.text = "Please select...";
				} else {
					option.text = result[i];
				}
				option.value = result[i];
				visitPurposes.add(option);
			}
		},
		async: false
	});
}

function checkArrivalDate()
{
	var arrival_year = $(".arrival_year").val();
	var arrival_month = $(".arrival_month").val();
	var current_arrival_date = $(".arrival_date").val();
	
	if (arrival_year != "" && arrival_month != "") {
		var days_in_month = daysInMonth((arrival_month - 1), arrival_year);
		
		var arrival_date = document.getElementById("arrivaldate");
		if (arrival_date.length > 0) {
			for (var i=(arrival_date.length-1); i>=1; i--) {
				arrival_date.remove(i);
			}
		}
		for (var i=1; i<=days_in_month; i++) {
			var option = document.createElement("option");
			option.text = i;
			option.value = i;
			arrival_date.add(option);
		}
		
		if (current_arrival_date != "" && parseInt(current_arrival_date) <= days_in_month) {
			$(".arrival_date").val(current_arrival_date);
		}
		else {
			$(".arrival_date").val("");
		}
	}
}

function checkExitDate()
{
	var exit_year = $(".exit_year").val();
	var exit_month = $(".exit_month").val();
	var current_exit_date = $(".exit_date").val();
	
	if (exit_year != "" && exit_month != "") {
		var days_in_month = daysInMonth((exit_month - 1), exit_year);
		
		var exit_date = document.getElementById("exitdate");
		if (exit_date.length > 0) {
			for (var i=(exit_date.length-1); i>=1; i--) {
				exit_date.remove(i);
			}
		}
		for (var i=1; i<=days_in_month; i++) {
			var option = document.createElement("option");
			option.text = i;
			option.value = i;
			exit_date.add(option);
		}
		
		if (current_exit_date != "" && parseInt(current_exit_date) <= days_in_month) {
			$(".exit_date").val(current_exit_date);
		}
		else {
			$(".exit_date").val("");
		}
	}
	
	if (exit_year != "" && exit_month != "" && current_exit_date != "") {
		$(".exit_date_t").html($(".exit_month :selected").text()+"/"+$(".exit_date :selected").text()+"/"+$(".exit_year :selected").text());
	}
}

function checkTravelDays()
{
	var arrival_date	= new Date($(".arrival_year").val(),$(".arrival_month").val()-1,$(".arrival_date").val());
	var exit_date		= new Date($(".exit_year").val(),$(".exit_month").val()-1,$(".exit_date").val());
	var type_of_visa	= $(".visa_type :selected").val();
	var max_travel_days = 30;
	
	if (type_of_visa == "1ms") {
		max_travel_days = 30;
	}
	if (type_of_visa == "3ms") {
		max_travel_days = 90;
	}
	if (type_of_visa == "1mm") {
		max_travel_days = 30;
	}
	if (type_of_visa == "3mm") {
		max_travel_days = 90;
	}
	if (type_of_visa == "6mm") {
		max_travel_days = 180;
	}
	if (type_of_visa == "1ym") {
		max_travel_days = 365;
	}
	
	var day_diff = Math.round((exit_date-arrival_date)/(1000*60*60*24));
	
	return ((day_diff > max_travel_days) ? max_travel_days : 0);
}

function checkProcessingTime()
{
	var arrival_year = $(".arrival_year").val();
	var arrival_month = $(".arrival_month").val();
	var arrival_date = $(".arrival_date").val();
	
	if (arrival_year != "" && arrival_month != "" && arrival_date != "") {
		var date			= new Date();
		var current_date	= new Date(date.getFullYear(), date.getMonth(), date.getDate());
		var arrival_date	= new Date($(".arrival_year").val(),$(".arrival_month").val()-1,$(".arrival_date").val());
		if (arrival_date.getTime() < current_date.getTime()) {
			if (!$("#processing_time_normal").is(':disabled')) {
				$("#processing_time_normal").attr("disabled", true);
				$("#processing_time_normal").parent().find("strong").css("color", "#ccc");
			}
			if (!$("#processing_time_urgent").is(':disabled')) {
				$("#processing_time_urgent").attr("disabled", true);
				$("#processing_time_urgent").parent().find("strong").css("color", "#ccc");
			}
			if (!$("#processing_time_emergency").is(':disabled')) {
				$("#processing_time_emergency").attr("disabled", true);
				$("#processing_time_emergency").parent().find("strong").css("color", "#ccc");
			}
			if (!$("#processing_time_holiday").is(':disabled')) {
				$("#processing_time_holiday").attr("disabled", true);
				$("#processing_time_holiday").parent().find("strong").css("color", "#ccc");
			}
		}
		else {
			$.ajax({
				type: "POST",
				url: BASE_URL + "/apply-visa/ajax-detect-rush-case.html",
				data: {
					arrival_year: $(".arrival_year").val(),
					arrival_month: $(".arrival_month").val(),
					arrival_date: $(".arrival_date").val()
				},
				success: function(result) {
					if (result == 2) {
						if (!$("#processing_time_normal").is(':disabled')) {
							$("#processing_time_normal").attr("disabled", true);
							$("#processing_time_normal").parent().find("strong").css("color", "#ccc");
						}
						if (!$("#processing_time_urgent").is(':disabled')) {
							$("#processing_time_urgent").attr("disabled", true);
							$("#processing_time_urgent").parent().find("strong").css("color", "#ccc");
						}
						if (!$("#processing_time_emergency").is(':disabled')) {
							$("#processing_time_emergency").attr("disabled", true);
							$("#processing_time_emergency").parent().find("strong").css("color", "#ccc");
						}
						if ($("#processing_time_holiday").is(':disabled')) {
							$("#processing_time_holiday").attr("disabled", false);
							$("#processing_time_holiday").parent().find("strong").css("color", "inherit");
						}
						if ($("#processing_time_holiday").parent().parent().is(':hidden')) {
							$("#processing_time_holiday").parent().parent().show();
						}
						if (!$("#processing_time_holiday").is(':checked')) {
							$("#processing_time_holiday").prop("checked", true);
						}
					}
					else if (result == 1) {
						if (!$("#processing_time_normal").is(':disabled')) {
							$("#processing_time_normal").attr("disabled", true);
							$("#processing_time_normal").parent().find("strong").css("color", "#ccc");
						}
						if (!$("#processing_time_urgent").is(':disabled')) {
							$("#processing_time_urgent").attr("disabled", true);
							$("#processing_time_urgent").parent().find("strong").css("color", "#ccc");
						}
						if ($("#processing_time_emergency").is(':disabled')) {
							$("#processing_time_emergency").attr("disabled", false);
							$("#processing_time_emergency").parent().find("strong").css("color", "inherit");
						}
						if (!$("#processing_time_emergency").is(':checked')) {
							$("#processing_time_emergency").prop("checked", true);
						}
						if (!$("#processing_time_holiday").is(':disabled')) {
							$("#processing_time_holiday").attr("disabled", true);
							$("#processing_time_holiday").parent().find("strong").css("color", "#ccc");
						}
						if (!$("#processing_time_holiday").parent().parent().is(':hidden')) {
							$("#processing_time_holiday").parent().parent().hide();
						}
					} else {
						if ($("#processing_time_normal").is(':disabled')) {
							$("#processing_time_normal").attr("disabled", false);
							$("#processing_time_normal").parent().find("strong").css("color", "inherit");
						}
						if ($("#processing_time_urgent").is(':disabled')) {
							$("#processing_time_urgent").attr("disabled", false);
							$("#processing_time_urgent").parent().find("strong").css("color", "inherit");
						}
						if ($("#processing_time_emergency").is(':disabled')) {
							$("#processing_time_emergency").attr("disabled", false);
							$("#processing_time_emergency").parent().find("strong").css("color", "inherit");
						}
						if (!$("#processing_time_holiday").is(':disabled')) {
							$("#processing_time_holiday").attr("disabled", true);
							$("#processing_time_holiday").parent().find("strong").css("color", "#ccc");
						}
						if (!$("#processing_time_holiday").parent().parent().is(':hidden')) {
							$("#processing_time_holiday").parent().parent().hide();
						}
						if ($("#processing_time_holiday").is(':checked')) {
							if ($("#processing_time_normal").length) {
								$("#processing_time_normal").prop("checked", true);
							} else {
								$("#processing_time_urgent").prop("checked", true);
							}
						}
					}
				},
				async:false
			});
		}
		
		$(".arrival_date_t").html($(".arrival_month :selected").text()+"/"+$(".arrival_date :selected").text()+"/"+$(".arrival_year :selected").text());
	}
	else {
		if (!$("#processing_time_holiday").parent().parent().is(':hidden')) {
			$("#processing_time_holiday").parent().parent().hide();
		}
	}
}

function detectHolidayCase()
{
	var isHoliday = false;
	
	$.ajax({
		type: "POST",
		url: BASE_URL + "/apply-visa/ajax-detect-holiday.html",
		data: {
			arrival_year: $(".arrival_year").val(),
			arrival_month: $(".arrival_month").val(),
			arrival_date: $(".arrival_date").val()
		},
		success: function(result) {
			isHoliday = result;
		},
		async:false
	});
	
	return isHoliday;
}

function detectEmergencyCase()
{
	var isEmergency = false;
	
	$.ajax({
		type: "POST",
		url: BASE_URL + "/apply-visa/ajax-detect-emergency.html",
		data: {
			arrival_year: $(".arrival_year").val(),
			arrival_month: $(".arrival_month").val(),
			arrival_date: $(".arrival_date").val()
		},
		success: function(result) {
			isEmergency = result;
		},
		async:false
	});
	
	return isEmergency;
}

function onPurposeChanged()
{
	$(".visit_purpose_t").html($(".visit_purpose :selected").text());
}

function onArrivalPortChanged()
{
	$(".arrival_port_t").html($(".arrival_port :selected").text());
}

function onRushChanged()
{
	var sf_included = false;
	var is_holiday = false;
	
	$(".processing-option").hide();
	$(".processing_time").each(function(index) {
		if ($(this).is(":checked") && $(this).val() == "Normal") {
			$(".processing_note_t").html("Normal (2 working days)");
		}
		if ($(this).is(":checked") && $(this).val() == "Urgent") {
			$(".processing_note_t").html("Urgent (4 working hours)");
		}
		if ($(this).is(":checked") && $(this).val() == "Emergency") {
			$(".processing_note_t").html("Emergency (maximum 30 minutes)");
		}
		if ($(this).is(":checked") && $(this).val() == "Holiday") {
			$(".processing_note_t").html("Holiday Vietnam visa");
			$("#full_package").prop("checked", false);
			$(".full_package_group").hide();
			is_holiday = true;
		} else {
			$(".full_package_group").show();
		}
		if ($(this).is(":checked")) {
			$("#"+$(this).attr("note-id")).show();
		}
	});
	
	sf_included = (is_holiday || $("#full_package").is(":checked"));
	
	if (sf_included) {
		$(".stamping_fee_included").show();
		$(".stamping_fee_excluded").hide();
	} else {
		$(".stamping_fee_included").hide();
		$(".stamping_fee_excluded").show();
	}
	
	$(".full_package_group_none").hide();
}

function onPrivateLetterChanged()
{
	if ($("#private_visa").is(":checked")) {
		$("#private_visa_li").show();
	} else {
		$("#private_visa_li").hide();
	}
}

function onServiceChanged()
{
	if ($("#full_package").is(":checked")) {
		$("#fast_checkin").prop("checked", false);
		$(".cb_fast_checkin").hide();
	} else {
		$(".cb_fast_checkin").show();
	}
	if ($("#car_pickup").is(":checked")) {
		$(".car-select").show();
	} else {
		$(".car-select").hide();
	}
}

function calServiceFees()
{
	var passport_holder		= $(".passport_holder").val();
	var group_size			= parseInt($(".group_size").val());
	var visa_type			= $(".visa_type").val();
	var visit_purpose		= $(".visit_purpose").val();
	var processing_time 	= $("input[name='processing_time']:checked").val();
	var service_fee  		= 0;
	var rush_fee     		= 0;
	var private_fee			= 0;
	var checkin_fee			= 0;
	var package_checkin_fee	= 0;
	var car_fee				= 0;
	var car_type			= $(".car_type").val();
	var num_seat			= parseInt($(".num_seat").val());
	var service_type		= 0;
	var arrival_port		= "Ha Noi";
	
	if ($("#fast_checkin").is(":checked")) {
		service_type = 1;
	}
	if ($(".arrival_port").val() != "") {
		arrival_port = $(".arrival_port").val();
	}
	
	var p = {};
	p['passport_holder']= passport_holder;
	p['group_size']		= group_size;
	p['visa_type']		= visa_type;
	p['visit_purpose']	= visit_purpose;
	p['arrival_port']	= arrival_port;
	p['processing_time']= processing_time;
	p['service_type']	= service_type;
	p['car_type']		= car_type;
	p['num_seat']		= num_seat;
	
	$.ajax({
		type: "POST",
		url: BASE_URL + "/apply-visa/ajax-cal-service-fees.html",
		data: p,
		dataType: "json",
		success: function(result) {
			private_fee			= parseInt(result[0]);
			package_checkin_fee	= parseInt(result[1]);
			checkin_fee			= parseInt(result[2]);
			car_fee				= parseInt(result[3]);
			service_fee			= parseInt(result[4]);
			rush_fee			= parseInt(result[5]);
			stamp_fee			= parseInt(result[6]);
			discount			= parseInt(result[7]);
			discount_unit		= result[8];
			
			var total = 0;
			
			if ($("#private_visa").is(":checked")) {
				$(".private_visa_t").html(private_fee+" USD");
				total += private_fee;
			}
			
			var serviceList = "";
			var serviceCnt  = 1;
			if ($("#fast_checkin").is(":checked")) {
				serviceList += "<div class='clearfix'><label>"+(serviceCnt++)+". Fast check-in</label><span class='price'>"+checkin_fee+" USD x "+group_size+" "+((group_size>1)?"people":"person")+" = "+(checkin_fee*group_size)+" USD</span></div>";
				total += checkin_fee * group_size;
			}
			if ($("#car_pickup").is(":checked")) {
				serviceList += "<div class='clearfix'><label>"+(serviceCnt++)+". Car pick-up</label><span class='price'>("+car_type+", "+num_seat+" seats)"+" = "+car_fee+" USD</span></div>";
				total += car_fee;
			}
			$(".extra_services").html(serviceList);
			if (serviceList != "") {
				$("#extra_service_li").show();
			} else {
				$("#extra_service_li").hide();
			}
			
			var serviceList = "";
			var serviceCnt  = 1;
			if ($("#full_package").is(":checked")) {
				serviceList += "<div class='clearfix'><label>"+(serviceCnt++)+". Government fee</label><span class='price'>"+stamp_fee+" USD x "+group_size+" "+((group_size>1)?"people":"person")+" = "+(stamp_fee*group_size)+" USD</span></div>";
				serviceList += "<div class='clearfix'><label>"+(serviceCnt++)+". Fast check-in</label><span class='price'>"+package_checkin_fee+" USD x "+group_size+" "+((group_size>1)?"people":"person")+" = "+(package_checkin_fee*group_size)+" USD</span></div>";
				total += (stamp_fee + package_checkin_fee) * group_size;
			}
			$(".full_package_services").html(serviceList);
			if (serviceList != "") {
				$("#full_package_li").show();
			} else {
				$("#full_package_li").hide();
			}
			
			var total_visa_price_txt = service_fee+" USD x "+group_size+" "+((group_size>1)?"people":"person")+" = "+(service_fee*group_size)+" USD";
			
			$("#processing_time_li").hide();
			$(".processing_time").each(function(index) {
				if($(this).is(":checked") && $(this).val() == "Urgent") {
					$(".processing_t").html(rush_fee+" USD x "+group_size+" "+((group_size>1)?"people":"person")+" = "+(rush_fee*group_size)+" USD");
					$("#processing_time_li").show();
				}
				if($(this).is(":checked") && $(this).val() == "Emergency") {
					$(".processing_t").html(rush_fee+" USD x "+group_size+" "+((group_size>1)?"people":"person")+" = "+(rush_fee*group_size)+" USD");
					$("#processing_time_li").show();
				}
				if($(this).is(":checked") && $(this).val() == "Holiday") {
					$(".processing_t").html(rush_fee+" USD x "+group_size+" "+((group_size>1)?"people":"person")+" = "+(rush_fee*group_size)+" USD");
					$("#processing_time_li").show();
				}
			});
			
			total += (service_fee + rush_fee) * group_size;
			
			if (visa_type == "6mm" || visa_type == "1ym") {
				$("#promotion_li").hide();
			} else {
				discount_fee = 0;
				if (discount_unit == "USD") {
					discount_fee = parseInt(discount);
				} else {
					discount_fee = Math.round((service_fee*group_size) * parseInt(discount)/100);
				}
				total = total - discount_fee;
				$("#promotion_li").show();
				$(".promotion_t").html("- "+discount_fee+" USD");
			}
			
			if (service_fee > 0) {
				$(".total_visa_price").html(total_visa_price_txt);
				$(".total_price").html(total+" USD");
			} else {
				$(".total_visa_price").html("...");
				$(".total_price").html("...");
			}
		}
	});
}
