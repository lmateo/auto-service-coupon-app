$(document).ready(function() {
	togglePriceFields(); 

    $('#coupon_price_show_hide').click(function () {
   	   
    	
        var val = $("#coupon_price_show_hide").is(':checked') ? 1 : 0;

        //alert("Checkbox state (method 1) = " + val) ;
        
   	   if (val == '1') {
 
   	        $('#couponPriceDollarAmountStatus').show();
   	        $('#couponPriceCentsStatus').show();

   	       
   	        
   	     $("#pricedisplayhide").html("Display Coupon Price");
   	        
   	        
   	   } else if (val == '0') {

   		 
   		 
   		   $('#couponPriceDollarAmountStatus').hide();
   		   $('#couponPriceCentsStatus').hide();

   		  
   		   $("#pricedisplayhide").html("Hide Coupon Price");
 		   
   	   }
      else {
          
    	 
    	  
       $('#couponPriceDollarAmountStatus').hide();
        $('#couponPriceCentsStatus').hide();

       
        $("#pricedisplayhide").html("Hide Coupon Price");
              
          }
      
      });
      
   
    toggleImageFields(); 

    $('#coupon_image_show_hide').click(function () {
   	   
    	
        var val = $("#coupon_image_show_hide").is(':checked') ? 1 : 0;

        //alert("Checkbox state (method 1) = " + val) ;
        
   	   if (val == '1') {
    
   		$('#couponImageStatus').show();
   		
        $("#imagedisplayhide").html("Display Coupon Main Image");
   	  
       } else if (val == '0'){

          $('#couponImageStatus').hide(); 

          $("#imagedisplayhide").html("Hide Coupon Main Image");
            
       }
       else {

    	    
      	 
      	 $('#couponImageStatus').hide();

      	 
      	 $("#imagedisplayhide").html("Hide Coupon Main Image");
      	 
      	     
           } 

      
      });
    
    toggleActiveFields(); 

    $('#active').click(function () {
   	   
    	
        var val = $("#active").is(':checked') ? 1 : 0;

        //alert("Checkbox state (method 1) = " + val) ;
        
        if (val == '1'){

        	   
              $("#active/inactive").html("ACTIVE:");
     	  
        } else if (val == '0'){
     	  
        	 
     	  $("#active/inactive").html("INACTIVE:");
             
             
        }
        else {

       	 
       	$("#active/inactive").html("INACTIVE:");
       	    
            }

      
      });
	
	


function togglePriceFields() {

	 var val = $("#coupon_price_show_hide").is(':checked') ? 1 : 0;

	//alert("Checkbox state (method 1) = " + val) ;

        if (val == '1'){

       	     

	    	    $('#couponPriceDollarAmountStatus').show();
		        $('#couponPriceCentsStatus').show();

		       
		        
		        $("#pricedisplayhide").html("Display Coupon Price");
	  	  

	     } else if (val == '0'){

	    	 
	  	  
	    	   $('#couponPriceDollarAmountStatus').hide();
	    	   $('#couponPriceCentsStatus').hide()
	    	  
	    	
	    	    $("#pricedisplayhide").html("Hide Coupon Price");
	    	  
	     }
	     else {

	    	 

	    	 $('#couponPriceDollarAmountStatus').hide();
	         $('#couponPriceCentsStatus').hide();

	       
	          $("#pricedisplayhide").html("Hide Coupon Price");
	    	     
	         }
	  }

function toggleImageFields() {

	 var val = $("#coupon_image_show_hide").is(':checked') ? 1 : 0;

   if (val == '1'){

   	$('#couponImageStatus').show();
       
  	 	
  	 	$("#imagedisplayhide").html("Display Coupon Main Image");
	  
   } else if (val == '0'){
	  
   	
        $('#couponImageStatus').hide(); 

       
        $("#imagedisplayhide").html("Hide Coupon Main Image");
        
        
   }
   else {

  	 $('#couponImageStatus').hide();
  	 
  	 $("#imagedisplayhide").html("Hide Coupon Main Image");
  	    
       }
}


function toggleActiveFields() {

	 var val = $("#active").is(':checked') ? 1 : 0;

  if (val == '1'){

  
     
      
      $("#active/inactive").html("ACTIVE:");
	  
  } else if (val == '0'){
	  
  	
	  
	   
	   $("#active/inactive").html("INACTIVE:");
       
       
  }
  else {

 	 $("#active/inactive").html("INACTIVE:");
 	    
      }
}
});	
				 