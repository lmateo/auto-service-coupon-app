$(document).ready(function() {
	// Update Coupon Title selected records	
	$(document).on('change', '#coupon_title', function(){
		
		var updateCouponTitle = $('#coupon_title').val();
		
			//alert(updateCouponTitle);
			$('.couponTitle').val(updateCouponTitle);
			
			$(".couponTitle").html(updateCouponTitle);
				
	});	
	
	// Update Expiration selected records	
	$(document).on('change', '#coupon_expiration_date', function(){
		
		var updateExDate = $('#coupon_expiration_date').val();
		var CouponExDate = $.date(updateExDate);
		
			//alert(DateCreated);
			$('.exDate').val(CouponExDate);
			
			$(".exDate").html(CouponExDate);
				
	});	
	
	$.date = function(orginaldate) { 
	    var date = new Date(orginaldate);
	    var day = date.getDate();
	    var month = date.getMonth() + 1;
	    var year = date.getFullYear();
	    if (day < 10) {
	        day = "0" + day;
	    }
	    if (month < 10) {
	        month = "0" + month;
	    }
	    var date =  month + "/" + day + "/" + year; 
	    return date;
	};
	
	
	// Update Coupon Price Show/Hide selected records	

		//alert(updatePriceDislpay);
		
		
		$('#coupon_price_show_hide').change(function(){
			if($(this).prop("checked") == true){
				
				$('.couponDisplay').val("1");
				
				$(".couponDisplay").html("DISPLAY PRICE");
                
            }
            else if($(this).prop("checked") == false){
            	
            	$('.couponDisplay').val("0");
				
				$(".couponDisplay").html("NO PRICE DISPLAY");
				
				$('.couponPriceDollarAmountStatus').val('');
				
				$(".couponPriceDollarAmountStatus").html('');
				
				$('.couponPriceCents').val('');	
				
				$(".couponPriceCents").html('');
				
            }	
			       
	    });	
		
		// Update Coupon Price Amount selected records	
		$(document).on('change', '#coupon_price_amount', function(){
			
			var updateCouponPriceAmt = $('#coupon_price_amount').val();
			
				$('.couponPriceDollarAmountStatus').val(updateCouponPriceAmt);
				
				$(".couponPriceDollarAmountStatus").html(updateCouponPriceAmt);
					
		});			
	
		// Update Coupon Cents Amount selected records	
		$(document).on('change', '#coupon_cents', function(){
			
			var updateCouponCents = $('#coupon_cents').val();
			
				$('.couponPriceCents').val(updateCouponCents);
				
				$(".couponPriceCents").html(updateCouponCents);
					
		});
		
		// Update Coupon Image Show/Hide selected records	

		//alert(updatePriceDislpay);
		
		
		$('#coupon_image_show_hide').change(function(){
			if($(this).prop("checked") == true){
				
				$('.couponImageShowHide').val("1");
				
				$(".couponImageShowHide").html("DISPLAY IMAGE");
                
            }
            else if($(this).prop("checked") == false){
            	
            	$('.couponImageShowHide').val("0");
				
				$(".couponImageShowHide").html("NO IMAGE DISPLAY");
				
				$('.couponImage').val('');
				
				$(".couponImage").html('');
				
            }	
			       
	    });	
		
		// Update Coupon Image selected records	
		$(document).on('change', '#coupon_image', function(){
			
			var updateCouponImage = $('#coupon_image').val();
			
				$('.couponImage').val(updateCouponImage);
				
				$(".couponImage").html(updateCouponImage);
					
		});	
		
		$("#year").change(function () {
			var updateCouponYear = $('#year :selected').val();
			
			//alert(updateCouponYear);
			
			$('.year').val(updateCouponYear);
			
			$(".year").html(updateCouponYear);

	    });
		
		$("#coupon_type").change(function () {
			var updatecouponType = $('#coupon_type :selected').val();
			
			//alert(updatecouponType);
			
			$('.couponType').val(updatecouponType);
			
			$(".couponType").html(updatecouponType);

	    });
		
		$("#ordering").change(function () {
			var updateCouponOrdering = $('#ordering :selected').val();
			
			//alert(updateCouponYear);
			
			$('.ordering').val(updateCouponOrdering);
			
			$(".ordering").html(updateCouponOrdering);

	    });
		
		$('#active').change(function(){
			if($(this).prop("checked") == true){
				
				$('.active_cp').val("1");
				
				$(".active_cp").html("ACTIVE");
                
            }
            else if($(this).prop("checked") == false){
            	
            	$('.active_cp').val("0");
				
				$(".active_cp").html("INACTIVE");
				
				
				
            }	
			       
	    });	
		
		// Update Coupon Info Area selected records	
		$(document).on('change', '#coupon_info_area', function(){
			
			var updateCouponInfoArea = $('#coupon_info_area').val();
			
				$('.couponInfoArea').val(updateCouponInfoArea);
				
				$(".couponInfoArea").html(updateCouponInfoArea);
					
		});
		
		// Update Coupon Disclaimer Text selected records	
		$(document).on('change', '#disclaimer_text', function(){
			
			var updateCouponDisclaimerText = $('#disclaimer_text').val();
			
				$('.disclaimer').val(updateCouponDisclaimerText);
				
				$(".disclaimer").html(updateCouponDisclaimerText);
					
		});
	
	
});	
	
				 