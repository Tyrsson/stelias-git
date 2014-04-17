function checkSelectVals() {
	var deliveryType = $("#type").val();
	return deliveryType;
}
function getAddressForm() {
	if(checkSelectVals() == 'delivery') {
		$.post("/festival/addaddress",{format:"html"}).done(function(data){
			$("#address-info").replaceWith(data);
		});
	}
	else {
		$.post("/festival/removeaddress",{format:"html"}).done(function(data){
			$("#address-info").replaceWith(data);
		});
	}
}
// style all admin links with class= button
$(function(){
    $( "a.button" ).button();
});
$(function(){
    $( ".buttonset" ).buttonset();
});
// create the admin info tab container see admin.phtml
$(function(){
    $( "#ui-tabs" ).tabs({});
});
$(function () {
	var start = new Date("2013, 04, 12");
	var end = new Date("2013, 04, 13");
	$("#day").datepicker({
		dateFormat: 'yy/mm/dd',
		minDate: start,
		maxDate: end
	});
});
// setup the timepicker for the ordering form
$(function () {
    $("#time").timepicker({
                showPeriod: true,
                showPeriodLabels: true,
                showLeadingZero: false,
                amPmText: ["AM", "PM"],
                hours: {
                    starts:11,
                    ends:18
                },

                });
});


// ordering form calculations
$(document).ready(function() {
	// update the plug-in version
	$("#idPluginVersion").text($.Calculation.version);

	// bind the recalc function to the quantity fields
	$("input[name^=qty_item_]").bind("keyup", recalc);
	// run the calculation function now
	recalc();
	// automatically update the "#totalSum" field every time
	// the values are changes via the keyup event
	$("input[name^=sum]").sum("keyup", "#totalSum");
	// automatically update the "#totalAvg" field every time
	// the values are changes via the keyup event
	$("input[name^=avg]").avg({
		bind:"keyup",
		selector: "#totalAvg",
		onParseError: function(){ this.css("backgroundColor", "#cc0000"); },
	    onParseClear: function (){ this.css("backgroundColor", ""); }
	});
	// automatically update the "#minNumber" field every time
	// the values are changes via the keyup event
	$("input[name^=min]").min("keyup", "#numberMin");
	// automatically update the "#minNumber" field every time
	// the values are changes via the keyup event
	$("input[name^=max]").max("keyup", { selector: "#numberMax", oncalc: function (value, options) {
																			// you can use this to format the value
																		    $(options.selector).val(value);
																		 }
						    });
	// this calculates the sum for some text nodes
	$("#idTotalTextSum").click(function (){
							    // get the sum of the elements
								var sum = $(".textSum").sum();
								// update the total
								$("#totalTextSum").text("$" + sum.toString());
							   }
							  );
	// this calculates the average for some text nodes
	$("#idTotalTextAvg").click(function (){
								// get the average of the elements
								var avg = $(".textAvg").avg();
								// update the total
								$("#totalTextAvg").text(avg.toString());
							   });
	});

	// function recalc *
	// the equation to use for the calculation,
	// define the variables used in the equation, these can be a jQuery object
	// define the formatting callback, the results of the calculation are passed to this function
	// return the number as a dollar amount
	function recalc(){
		$("[id^=total_item]").calc("qty * price", { qty: $("input[name^=qty_item_]"), price: $("[id^=price_item_]") }, function (s){ return "$" + s.toFixed(2); },
				// define the finish callback, this runs after the calculation has been complete
				function ($this){
				// sum the total of the $("[id^=total_item]") selector
				var sum = $this.sum();
				// round the results to 2 digits
				$("#grandTotal").text("$" + sum.toFixed(2));
	});
}

$(document).ready(function() {
	    // bind form using ajaxForm
	
	   // $('#order-form').ajaxForm( { beforeSubmit: validate, success: success } );
});

function validate(formData, jqForm, options) {

	var required = ["email", "phone", "name", "day", "time"]; // required fields
	var skip = ["street2", "street3", "apt"];


	$("form#order-form input[type=text], select").each(function(){
		var elem = $(this);
		var id = elem.attr("id");
		//console.log(id);

		if(id == 'type' && elem.val() == 'delivery') {
			//alert("found the delivery select");

			$("#address-info input[type=text], textarea, select").each(function() {
				var addrElem = $(this);
				//console.log(addrElem);
				var addrId = addrElem.attr("id");
				//console.log(addrId);
				if(jQuery.inArray(addrId, skip) == -1) {
					required.push(addrId);
				}
			});

		}

		if(jQuery.inArray(id, required) != -1) {
    			//console.log(id);
			if(elem.val().length == 0) {
    			elem.val("Required");
    			$("#validation-dialog").dialog({modal: true });
    			$("#dialog-text").text("Please complete the required fields to continue.");
    			return false;
			}
    	}
	});
	//return false;
}
function success() {
	alert("Thank you for your order. A copy of your order has been sent to the email address you provided.");
	//$("#validation-dialog").dialog({modal: true });
	//$("#dialog-text").text("Thank you for your order. A confirmation has been sent to the email address you entered above.");
}

$("#system-message").fadeOut(1200, function() {
    // Animation complete.
});

