<?php
/**
 * Coupon Specials Manager
*
* @package Wojo Framework
* @author Lorenzo Mateo
* @copyright 2017
* @version $Id: webspecials.php, v1.00 2017-09-08 9:38:05 gewa Exp $
*/
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
  
      
    
?>
<?php require_once("init.php");?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!Auth::hasPrivileges('edit_items')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php if(!$row = $cSpecials->getCSBYID()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $cssLetter = $row->store_letter;switch($cssLetter) {case "a":$cssLetter="y";break;case "A":$cssLetter="y";break;case "b":$cssLetter="p";break;case "B":$cssLetter="p";break;default:$cssLetter = strtolower($row->store_letter);}?>

<!--  <link rel="stylesheet" type="text/css" href="http://ncs.quirkspecials.com/newplugincss/<?php echo $cssLetter;?>/specials-style.css" />-->
<link rel="stylesheet" type="text/css" href="<?php echo Url::adminUrl ( "assets", false, "css/font-face.css" )?>" />
<link rel="stylesheet" type="text/css" href="<?php echo Url::adminUrl ( "assets", false, "css/font-awesome/css/font-awesome.min.css" )?>" />
<link rel="stylesheet" type="text/css" href="<?php echo Url::adminUrl ( "assets", false, "css/ncs.css" )?>" />
<!-- script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.3/react.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.3/react-dom.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.34/browser.min.js"></script-->
<?php echo Content::getncscolorswap($cssLetter); ?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
       <strong><?php echo $row->storename_ws;?></strong> <br/>
       <img src="<?php echo ($row->storeLogo) ? $row->storeLogo : "blank.png";?>" height="30" width="30" alt="">
       <br/>
      <div class="header"> <?php echo Lang::$word->WSP_SUB1;?> <small> / <?php echo $row->coupon_title;?></small> </div>
 </div>
  </div>
   
  <form method="post" id="wojo_form" class="wojo_form" name="wojo_form">
  <div class="columns gutters">
  <div class="row screen-50 tablet-50 phone-100">
    <div class="wojo divided card qsContent">
    <div class="header">
      <div class="content ">
         <a href="">Main Service Coupon Special Information </a> 
       </div>
      </div>
       <div class="item">
        <div class="intro inputctitle">Coupon Service Title: <input type="text" value="<?php echo $row->coupon_title;?>" name="coupon_title" fieldname="cs"></div>
        <div class="intro inputexp"><strong>Coupon Service Expiration Date</strong>: <input data-datepicker="true" data-date="<?php echo $row->coupon_expiration_date;?>" type="text" value="<?php echo Utility::dodate("short_date", $row->coupon_expiration_date);?>" name="expire" fieldname="cs"></div>
      </div>
      <div class="item">
         <div class="intro inputpricedisplayhide"><span id="pricedisplayhide"></span>
           <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="coupon_price_show_hide" <?php Validator::getChecked($row->coupon_price_show_hide, 1); ?> id="coupon_price_show_hide" fieldname="cs">
              <i></i></label></div>
         </div>
          <div class="intro inputcents" id="couponPriceCentsStatus"><strong>Price Dollar Amount $: </strong><input type="text" value="<?php echo $row->coupon_price_amount;?>" name="coupon_price_amount"  fieldname="cs"></div>
          <div class="intro inputamountstatus" id="couponPriceDollarAmountStatus"><strong>Cents Amount: </strong><input type="text" value="<?php echo $row->coupon_cents;?>" name="coupon_cents" fieldname="cs"></div> 
      </div>
      <div class="item">
         <div class="intro inputmagedisplayhide"><span id="imagedisplayhide"></span>
           <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="coupon_image_show_hide" <?php Validator::getChecked($row->coupon_image_show_hide, 1); ?> id="coupon_image_show_hide" fieldname="cs">
              <i></i></label></div>
         </div>
          <div class="intro inputcsimage" id="couponImageStatus"><strong>Coupon Service Image Url: </strong><input type="text" value="<?php echo $row->coupon_image;?>" name="coupon_image"  fieldname="cs"></div>
         </div>
      <div class="item">
       <div class="intro inputyear"><strong><?php echo Lang::$word->WSP_YEAR;?>:</strong><select name="year" fieldname="csyr">
            <option value="">-- Select Year --</option>
            <?php echo Utility::loopOptionsvalue($content->getYear(), "name", $row->year);?>
          </select></div>
       <div class="intro inputcstype"><strong><?php echo Lang::$word->WSP_DEALTYPE;?>:</strong><select name="coupon_type" fieldname="cstype">
            <option value="">-- Select Coupon Type --</option>
            <?php echo Utility::loopOptionsvalue($content->getCoupontype(), "name", $row->coupon_type);?>
          </select></div>
     
      </div>
      <div class="item">
       <div class="intro inputordering"><strong><?php echo Lang::$word->WSP_NCS_ORDERING;?>:</strong><select name="ordering" fieldname="csord">
            <option value="">-- Select NCPS Ordering --</option>
            <?php echo Utility::loopOptionsSimple($content->getnumberList(), $row->ordering);?>
          </select>
          
          </div>
      <div class="intro inputactive"><strong><span id="active/inactive"><?php echo Lang::$word->ACTIVE;?>:</span></strong>
           <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="active" <?php Validator::getChecked($row->active, 1); ?> id="active" fieldname="cs">
              <i></i></label>
            </div>
         </div>
        </div>
      </div>
     </div>
    <div class="row screen-50 tablet-50 phone-100">
    <div class="wojo divided card qsContent">
    <div class="header">
      <div class="content ">
         <a href="">Service Coupon Special Description/Disclaimer </a> 
       </div>
      </div>
       <div class="item">
	       <div class="intro inputdescription">Service Coupon  Description<i class="icon pin" data-content="<?php echo Lang::$word->WSP_DO_NOT_USE_DOUBLE_QUOTES;?>"></i>: <textarea name="coupon_info_area" fieldname="cs"><?php echo htmlspecialchars($row->coupon_info_area, ENT_QUOTES, 'UTF-8');?></textarea></div>
	      </div>
	      <div class="item">
	       <div class="intro inputdisclaimer">Service Coupon Disclaimer<i class="icon pin" data-content="<?php echo Lang::$word->WSP_DO_NOT_USE_DOUBLE_QUOTES;?>"></i>: <textarea name="disclaimer_text" fieldname="cs"><?php echo htmlspecialchars($row->disclaimer_text, ENT_QUOTES, 'UTF-8');?></textarea></div>
	      </div>
        </div>
      </div>
      </div>
      
     <div class="couponspecialspreview" id="couponspecialspreview"> </div>
     
     
      
	<div class="wojo fitted divider"></div>
    <div id="footer" class="wojo footer">
   <button type="button" data-action="processCouponspecials" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->WSP_UPDATE;?></button>
   <a href="<?php echo Url::adminUrl("couponspecials",false,"?id=" . $row->store_id);?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> 
     <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
   <input name="store_letter_ws" type="hidden" id="store_letter" value="<?php echo $row->store_letter;?>">
   </div>
   </form>
  </div>
</div>

<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {
	
    togglePriceFields(); 

    $('#coupon_price_show_hide').click(function () {
   	   
    	
        var val = $("#coupon_price_show_hide").is(':checked') ? 1 : 0;

        //alert("Checkbox state (method 1) = " + val) ;
        
   	   if (val == '1') {
 
   	        $('#couponPriceDollarAmountStatus').show();
   	        $('#couponPriceCentsStatus').show();

   	        document.getElementById("pricedisplayhide").innerHTML = "Display Coupon Price";
   	        
   	        
   	   } else if (val == '0') {

   		 
   		 
   		   $('#couponPriceDollarAmountStatus').hide();
   		   $('#couponPriceCentsStatus').hide();

   		   document.getElementById("pricedisplayhide").innerHTML = "Hide Coupon Price";
 		   
   	   }
      else {
          
    	 
    	  
       $('#couponPriceDollarAmountStatus').hide();
        $('#couponPriceCentsStatus').hide();

        document.getElementById("pricedisplayhide").innerHTML = "Hide Coupon Price";
              
          }
      
      });
      
   
    toggleImageFields(); 

    $('#coupon_image_show_hide').click(function () {
   	   
    	
        var val = $("#coupon_image_show_hide").is(':checked') ? 1 : 0;

        //alert("Checkbox state (method 1) = " + val) ;
        
   	   if (val == '1') {
    
   		$('#couponImageStatus').show();
   		
      	document.getElementById("imagedisplayhide").innerHTML = "Display Coupon Main Image"; 
   	  
       } else if (val == '0'){

          $('#couponImageStatus').hide(); 

          document.getElementById("imagedisplayhide").innerHTML = "Hide Coupon Main Image"; 
            
       }
       else {

    	    
      	 
      	 $('#couponImageStatus').hide();

      	 document.getElementById("imagedisplayhide").innerHTML = "Hide Coupon Main Image"; 
      	 
      	     
           } 

      
      });
    
    toggleActiveFields(); 

    $('#active').click(function () {
   	   
    	
        var val = $("#active").is(':checked') ? 1 : 0;

        //alert("Checkbox state (method 1) = " + val) ;
        
        if (val == '1'){

        	   
            document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->ACTIVE;?>:"; 
     	  
        } else if (val == '0'){
     	  
        	
     	   document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->INACTIVE;?>:"; 
             
             
        }
        else {

       	
       	  document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->INACTIVE;?>:";  
       	    
            }

      
      });

    /* == Load Coupon Specials == */
	$(window).on('load', function() {
	   var id = "<?php echo Filter::$id;?>";
	   
	        $.ajax({
	            type: "get",
	            url: ADMINURL + '/helper.php',
	            data: {
	            	loadCouponSpecialsPreview: 1,
	            	id: id   
	            },
	           
	            success: function(res) {
	                $('#couponspecialspreview').html(res).show();
	               
	            }
	        });
	    
	    return false;
	});

	 $('input[fieldname="cs"], select[fieldname="csyr"],select[fieldname="cstype"],select[fieldname="csord"],checkbox[fieldname="cs"],textarea[fieldname="cs"]').change(function() {
		 //.wojo_form_ws
		  //#wojo_form
		  var config = {
			lang: {working: "<span style='color:green;'>Saving....</span>"}
		  }
		    var id = "<?php echo Filter::$id;?>";
		    var redirect = $(this).data('redirect');
	        var $this = $(this);
		    var action = 'processCouponspecialsUpdate_autosave';
           
		    function autoSaveMsg() {
		    	$this.text(config.lang.working).animate({
						opacity: 0.2
					}, 800);

		    	
		    }

			//alert($this.text(config.lang.working).animate({opacity: 0.2}, 800));

	        function showResponse(json) {

        	   $.sticky(decodeURIComponent(json.message), {
					autoclose: 3000,
					type: json.type,
					title: json.title
				});

               
		    	 $.ajax({
			            type: "get",
			            url: ADMINURL + '/helper.php',
			            data: {
			            	loadCouponSpecialsPreview: 1,
			            	id: id
			            },
			           
			            success: function(res) {
			            	
			                $('#couponspecialspreview').html(res).show();
			                

							$this.animate({
								opacity: 1
							}, 800);

							//setTimeout(function() {
								//$this.html(res).fadeIn("slow");
							//}, 1000);
			                
			               
			            }
			        });
		    }
		    var options = {
		        target: null,
		        //beforeSubmit: autoSaveMsg,
		        success: showResponse,
		        type: "post",
		        url: ADMINURL + "/controller.php",
				data: {action: action},
		        dataType: 'json'
		    };

		    $('#wojo_form').ajaxForm(options).submit();

		    
		   

		    
		});
 
 });    

function togglePriceFields() {

	 var val = $("#coupon_price_show_hide").is(':checked') ? 1 : 0;

	//alert("Checkbox state (method 1) = " + val) ;

         if (val == '1'){

        	     

	    	    $('#couponPriceDollarAmountStatus').show();
		        $('#couponPriceCentsStatus').show();

		        document.getElementById("pricedisplayhide").innerHTML = "Display Coupon Price";
	  	  

	     } else if (val == '0'){

	    	 
	  	  
	    	   $('#couponPriceDollarAmountStatus').hide();
	    	   $('#couponPriceCentsStatus').hide()
	    	  
	    	  document.getElementById("pricedisplayhide").innerHTML = "Hide Coupon Price";
	    	  
	     }
	     else {

	    	 

	    	 $('#couponPriceDollarAmountStatus').hide();
	         $('#couponPriceCentsStatus').hide();

	         document.getElementById("pricedisplayhide").innerHTML = "Hide Coupon Price";
	    	     
	         }
	  }

function toggleImageFields() {

	 var val = $("#coupon_image_show_hide").is(':checked') ? 1 : 0;

    if (val == '1'){

    	$('#couponImageStatus').show();
        
   	 	document.getElementById("imagedisplayhide").innerHTML = "Display Coupon Main Image"; 
 	  
    } else if (val == '0'){
 	  
    	
         $('#couponImageStatus').hide(); 

         document.getElementById("imagedisplayhide").innerHTML = "Hide Coupon Main Image"; 
         
         
    }
    else {

   	 $('#couponImageStatus').hide();
   	 
   	 document.getElementById("imagedisplayhide").innerHTML = "Hide Coupon Main Image";  
   	    
        }
 }


function toggleActiveFields() {

	 var val = $("#active").is(':checked') ? 1 : 0;

   if (val == '1'){

   
       document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->ACTIVE;?>:"; 
	  
   } else if (val == '0'){
	  
   	
	   document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->INACTIVE;?>:"; 
        
        
   }
   else {

  	
  	  document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->INACTIVE;?>:";  
  	    
       }
}

// ]]>
</script>
<?php break;?>
<?php case"add": ?>
<?php if(!Auth::hasPrivileges('add_items')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="check icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->WSP_SUB2;?></div>
      <p><?php echo Lang::$word->WSP_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
   </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <link rel="stylesheet" type="text/css" href="http://ncs.quirkspecials.com/newplugincss/y/specials-style.css" />		      
   <div class="columns gutters">
   <div class="row screen-50 tablet-50 phone-100">
    <div class="wojo divided card qsContent">
    <div class="header">
      <div class="content ">
         <a href="">Main Service Coupon Special Information </a> 
       </div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->WSP_ROOM;?>: <select name="location">
            <option value="">-- <?php echo Lang::$word->WSP_ROOM_S;?> --</option>
            <?php echo Utility::loopOptions($content->getLocations(), "id", "name");?>
          </select>
          </div>
        <div class="data inputctitle"><strong>Coupon Service Title:</strong> <input type="text" name="coupon_title" fieldname="cs"></div>
       <div class="data inputexp"><strong>Expiration Date:</strong> <input data-datepicker="true"  type="text"  name="expire" fieldname="cs"></div>
      </div>
       <div class="item">
         <div class="intro inputactive"><span id="pricedisplayhide"></span>
           <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="coupon_price_show_hide" id="coupon_price_show_hide" fieldname="cs">
              <i></i></label></div>
         </div>
         <div class="data inputvinnumber" id="couponPriceCentsStatus"><strong>Price Dollar Amount $: </strong><input type="text" name="coupon_price_amount"  fieldname="cs"></div>
          <div class="data inputtagline" id="couponPriceDollarAmountStatus"><strong>Cents Amount: </strong><input type="text" name="coupon_cents" fieldname="cs"></div> 
         </div>
         <div class="item">
         <div class="intro inputactive"><span id="imagedisplayhide"></span>
           <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="coupon_image_show_hide" id="coupon_image_show_hide" fieldname="cs">
              <i></i></label></div>
         </div>
          <div class="data inputvinnumber" id="couponImageStatus"><strong>Coupon Service Image Url: </strong><input type="text"  name="coupon_image"  fieldname="cs"></div>
         </div>
      <div class="item">
       <div class="intro inputyear"><strong><?php echo Lang::$word->WSP_YEAR;?>:</strong><select name="year" fieldname="csyr">
            <option value="">-- Select Year --</option>
            <?php echo Utility::loopOptionsvalue($content->getYear(), "name");?>
          </select></div>
       <div class="data inputcstype"><strong><?php echo Lang::$word->WSP_DEALTYPE;?>:</strong><select name="coupon_type" fieldname="cstype">
            <option value="">-- Select Coupon Type --</option>
            <?php echo Utility::loopOptionsvalue($content->getCoupontype(), "name");?>
          </select></div>
     
      </div>
      <div class="item">
       <div class="intro inputordering"><strong><?php echo Lang::$word->WSP_NCS_ORDERING;?>:</strong><select name="ordering" fieldname="csyr">
            <option value="">-- Select NCPS Ordering --</option>
            <?php echo Utility::loopOptionsSimple($content->getnumberList());?>
          </select>
          
          </div>
      <div class="intro inputactive"><strong><span id="active/inactive"><?php echo Lang::$word->ACTIVE;?>:</span></strong>
           <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="active" id="active" fieldname="cs">
              <i></i></label>
              
              </div>
         </div>
         
      </div>
     
      </div>
      </div>
      
      
      <div class="row screen-50 tablet-50 phone-100">
    <div class="wojo divided card qsContent">
    <div class="header">
      <div class="content ">
         <a href="">Service Coupon Special Description/Disclaimer </a> 
       </div>
      </div>
      <div class="item">
        <div class="item">
	       <div class="intro inputdescription">Service Coupon  Description<i class="icon pin" data-content="<?php echo Lang::$word->WSP_DO_NOT_USE_DOUBLE_QUOTES;?>"></i>: <textarea name="coupon_info_area" fieldname="cs"></textarea></div>
	      </div>
	      <div class="item">
	       <div class="intro inputdisclaimer">Service Coupon Disclaimer<i class="icon pin" data-content="<?php echo Lang::$word->WSP_DO_NOT_USE_DOUBLE_QUOTES;?>"></i>: <textarea name="disclaimer_text" fieldname="cs"></textarea></div>
	      </div>
      </div>
      </div>
      </div>
      </div>
   <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processCouponspecials" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->WSP_ADD;?></button>
      <a href="<?php echo Url::adminUrl("couponspecials");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <!--  <input name="is_owner" type="hidden" value="1">-->
</form>
 </div>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {
	
    togglePriceFields(); 

    $('#coupon_price_show_hide').click(function () {
   	   
    	
        var val = $("#coupon_price_show_hide").is(':checked') ? 1 : 0;

        //alert("Checkbox state (method 1) = " + val) ;
        
   	   if (val == '1') {
 
   	        $('#couponPriceDollarAmountStatus').show();
   	        $('#couponPriceCentsStatus').show();

   	        document.getElementById("pricedisplayhide").innerHTML = "Display Coupon Price";
   	        
   	        
   	   } else if (val == '0') {

   		 
   		 
   		   $('#couponPriceDollarAmountStatus').hide();
   		   $('#couponPriceCentsStatus').hide();

   		   document.getElementById("pricedisplayhide").innerHTML = "Hide Coupon Price";
 		   
   	   }
      else {
          
    	 
    	  
       $('#couponPriceDollarAmountStatus').hide();
        $('#couponPriceCentsStatus').hide();

        document.getElementById("pricedisplayhide").innerHTML = "Hide Coupon Price";
              
          }
      
      });
      
   
    toggleImageFields(); 

    $('#coupon_image_show_hide').click(function () {
   	   
    	
        var val = $("#coupon_image_show_hide").is(':checked') ? 1 : 0;

        //alert("Checkbox state (method 1) = " + val) ;
        
   	   if (val == '1') {
    
   		$('#couponImageStatus').show();
   		
      	document.getElementById("imagedisplayhide").innerHTML = "Display Coupon Main Image"; 
   	  
       } else if (val == '0'){

          $('#couponImageStatus').hide(); 

          document.getElementById("imagedisplayhide").innerHTML = "Hide Coupon Main Image"; 
            
       }
       else {

    	    
      	 
      	 $('#couponImageStatus').hide();

      	 document.getElementById("imagedisplayhide").innerHTML = "Hide Coupon Main Image"; 
      	 
      	     
           } 

      
      });
    
    toggleActiveFields(); 

    $('#active').click(function () {
   	   
    	
        var val = $("#active").is(':checked') ? 1 : 0;

        //alert("Checkbox state (method 1) = " + val) ;
        
        if (val == '1'){

        	   
            document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->ACTIVE;?>:"; 
     	  
        } else if (val == '0'){
     	  
        	
     	   document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->INACTIVE;?>:"; 
             
             
        }
        else {

       	
       	  document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->INACTIVE;?>:";  
       	    
            }

      
      });

    
 
 });    

function togglePriceFields() {

	 var val = $("#coupon_price_show_hide").is(':checked') ? 1 : 0;

	//alert("Checkbox state (method 1) = " + val) ;

         if (val == '1'){

        	     

	    	    $('#couponPriceDollarAmountStatus').show();
		        $('#couponPriceCentsStatus').show();

		        document.getElementById("pricedisplayhide").innerHTML = "Display Coupon Price";
	  	  

	     } else if (val == '0'){

	    	 
	  	  
	    	   $('#couponPriceDollarAmountStatus').hide();
	    	   $('#couponPriceCentsStatus').hide()
	    	  
	    	  document.getElementById("pricedisplayhide").innerHTML = "Hide Coupon Price";
	    	  
	     }
	     else {

	    	 

	    	 $('#couponPriceDollarAmountStatus').hide();
	         $('#couponPriceCentsStatus').hide();

	         document.getElementById("pricedisplayhide").innerHTML = "Hide Coupon Price";
	    	     
	         }
	  }

function toggleImageFields() {

	 var val = $("#coupon_image_show_hide").is(':checked') ? 1 : 0;

    if (val == '1'){

    	$('#couponImageStatus').show();
        
   	 	document.getElementById("imagedisplayhide").innerHTML = "Display Coupon Main Image"; 
 	  
    } else if (val == '0'){
 	  
    	
         $('#couponImageStatus').hide(); 

         document.getElementById("imagedisplayhide").innerHTML = "Hide Coupon Main Image"; 
         
         
    }
    else {

   	 $('#couponImageStatus').hide();
   	 
   	 document.getElementById("imagedisplayhide").innerHTML = "Hide Coupon Main Image";  
   	    
        }
 }


function toggleActiveFields() {

	 var val = $("#active").is(':checked') ? 1 : 0;

   if (val == '1'){

   
       document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->ACTIVE;?>:"; 
	  
   } else if (val == '0'){
	  
   	
	   document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->INACTIVE;?>:"; 
        
        
   }
   else {

  	
  	  document.getElementById("active/inactive").innerHTML = "<?php echo Lang::$word->INACTIVE;?>:";  
  	    
       }
}

// ]]>
</script>
<?php break;?>
<?php case"couponspecialsalert": ?>
<?php if(!$row = $cSpecials->getCouponspecialsPreview()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $data = $cSpecials->getCouponSpecialsAlert();?>
<div class="wojo secondary icon message"><i class="car icon"></i></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->WSALERT_TITLE;?> <small> / <?php echo $row->coupon_title;?></small> </div>
    <p><?php echo Lang::$word->WSALERT_INFO;?></p>
  </div>
</div>
<div class="clearfix bottom-space"> <a class="wojo right labeled icon secondary button push-right" onclick="javascript:void window.open('<?php echo ADMINURL;?>/printcpch.php?id=<?php echo Filter::$id;?>','printer','width=880,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0'); return false;"><i class="icon printer"></i><?php echo Lang::$word->PRINT;?></a> </div>
<!--  <div class="clearfix bottom-space"> <a class="wojo right labeled icon secondary button push-right" onclick="javascript:void window.open('<?php echo ADMINURL;?>/printTest.php','printer','width=880,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0'); return false;"><i class="icon printer"></i><?php echo Lang::$word->PRINT;?></a> </div>-->
 <div class="columns double-gutters" id="printArea">
  <div class="screen-50 tablet-100 phone-100">
  <div class="couponspecialspreview" id="couponspecialspreview"> </div>
   <div class="wojo divider"></div>
  </div>
  <div class="screen-50 tablet-100 phone-100">
    <table class="wojo grid table">
      <thead>
        <tr>
	        <th align="left"></th>
	        <th><?php echo Lang::$word->WSALERT_FIELD;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_NEW;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_OLD;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_BY;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_ON;?></th>
        </tr>
      </thead>
      </tr>
       <?php if(!$data):?>
		<tr>
	    <td colspan="7"><?php echo Message::msgSingleAlert("No Coupon Specials Alerts at this Time.");?></td>
	    </tr>
	    <?php else:?>
	    <?php foreach ($data as $row2):?>
	    <?php if (Utility::dodate("short_date", $row2->modified) == Utility::dodate("short_date",Utility::today())):?>
			      <tr>
			        <td  align="left" style="background-color:yellow">(New Update)</td>
			        <td style="background-color:yellow"><?php echo $row2->col;?></td>
			        <td style="background-color:yellow"><?php echo $row2->changeFrom;?></td>
			        <td style="background-color:yellow"><?php echo $row2->changeTo;?></td>
			        <td style="background-color:yellow"><?php echo $row2->username;?></td>
			        <td style="background-color:yellow"><?php echo Utility::dodate("short_date", $row2->modified);?></td>
			      </tr>
			       <?php else:?>
			       <tr>
			       <td align="left"></td>
			        <td><?php echo $row2->col;?></td>
			        <td><?php echo $row2->changeFrom;?></td>
			        <td><?php echo $row2->changeTo;?></td>
			        <td><?php echo $row2->username;?></td>
			        <td><?php echo Utility::dodate("short_date", $row2->modified);?></td>
			      </tr>
			      <?php endif;?>
			      <?php endforeach;?>
			      <?php unset($row);?>
			      <?php unset($row2);?>
			      <?php endif;?>
     			 <tr> 
     			   
    </table>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {

	/* == Load Coupon Specials == */
	$(window).on('load', function() {
	   var id = "<?php echo Filter::$id;?>";
	   
	        $.ajax({
	            type: "get",
	            url: ADMINURL + '/helper.php',
	            data: {
	            	loadCouponSpecialsPreview: 1,
	            	id: id   
	            },
	           
	            success: function(res) {
	                $('#couponspecialspreview').html(res).show();
	               
	            }
	        });
	    
	    return false;
	});	
	
}); 
//]]>
</script>
<?php break;?>
<?php case"printws": ?>
<?php if(!$row = $cSpecials->getCouponspecialsPreview()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $locdata = $row->storename_ws;?>
<div class="wojo secondary icon message"> <i class="printer icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->LPR_SUB;?> <small> / <?php echo $row->coupon_title;?></small> </div>
    <p><?php echo Lang::$word->LPR_INFO;?></p>
  </div>
</div>
<div class="clearfix bottom-space"> <a class="wojo right labeled icon secondary button push-right" onclick="javascript:void window.open('<?php echo ADMINURL;?>/printws.php?id=<?php echo Filter::$id;?>','printer','width=880,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0'); return false;"><i class="icon printer"></i><?php echo Lang::$word->PRINT;?></a> </div>
<div class="columns double-gutters" id="printArea">
  <div class="screen-50 tablet-100 phone-100">
    <div class="couponspecialspreview" id="couponspecialspreview"> </div>
   <div class="wojo divider"></div>
   
  </div>
  <div class="screen-50 tablet-100 phone-100">
    <table class="wojo grid table">
      <thead>
        <tr>
          <th colspan="2"><?php echo $row->new_used_ws . ' ' . '(' .$row->year .') '. $row->nice_title_ws .' '. $row->trim_level;?></th>
        </tr>
      </thead>
       <tr>
        <td><?php echo Lang::$word->WSP_STOCK;?></td>
        <td><?php echo Validator::has($row->stock_number);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_COND;?></td>
        <td><?php echo $row->new_used_ws;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_PRICE;?></td>
        <td><?php echo Validator::has(Utility::formatMoney($row->buy_price));?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_ROOM;?></td>
        <td><?php echo $row->storename_ws;?></td>
      </tr>
       <tr>
        <td><?php echo Lang::$word->EMAIL;?></td>
        <td><?php echo $row->email;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->CF_PHONE;?></td>
        <td><?php echo Validator::has($row->phone);?></td>
      </tr>
    </table>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {

	/* == Load Coupon Specials == */
	$(window).on('load', function() {
	   var id = "<?php echo Filter::$id;?>";
	   
	        $.ajax({
	            type: "get",
	            url: ADMINURL + '/helper.php',
	            data: {
	            	loadCouponSpecialsPreview: 1,
	            	id: id   
	            },
	           
	            success: function(res) {
	                $('#couponspecialspreview').html(res).show();
	               
	            }
	        });
	    
	    return false;
	});	
	
}); 
//]]>
</script>
<?php break;?>
<?php case "updatemulticoupons": ?>
<?php $data = $cSpecials->getCouponspecials(false, "");?>
<?php $storedata = $content->getLocations();?>
<div class="wojo secondary icon message"> <i class="tag icon"></i>
  <div class="content">
    <div class="header"> Manage Multiple Service Coupons </div>
    <p><?php echo Lang::$word->WSP_INFO;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->FILTER;?></div>
  <div class="content">
    <div class="wojo form">
      <div class="three fields">
        <div class="field">
         <label><?php echo Lang::$word->WSP_ROOM_S;?></label>
            <select name="sid" data-links="true">
              <option value="<?php echo Url::adminUrl("couponspecials", "updatemulticoupons");?>">--- <?php echo Lang::$word->TRX_RESET_FILTER;?> ---</option>
              <?php if($storedata):?>
              <?php foreach($storedata  as $srow):?>
              <?php $selected = ($srow->store_id == $_GET['id']) ? ' selected="selected"' : null;?>
              <?php $storename = $db->getValueByStoreId(Content::lcTable, "name", $_GET['id']);
              $active = 'AND cs.active = 1';
              $counter = $db->count(false, false, "SELECT COUNT(*) FROM `" . cSpecials::csTable . "` AS cs WHERE store_id = '" . $srow->store_id . "' $active ");
              ?>
              <option value="<?php echo Url::adminUrl("couponspecials", "updatemulticoupons", false,"?id=" . $srow->store_id);?>"<?php echo $selected;?>><?php echo $srow->name;?> (<?php echo $counter;?>)</option>
              <?php endforeach;?>
              <?php endif;?>
            </select>
          </div>
          <?php if (!$storename):?>
          <div class="field">
		   <label><?php echo Lang::$word->CURPAGE;?></label>
          <?php echo $pager->jump_menu();?>
          </div>
          <div class="field">
          <label><?php echo Lang::$word->IPP;?></label>
          <?php echo $pager->items_per_page();?>
          </div>
           <?php else:?>
		  <div class="field"> 
          <label><?php echo Lang::$word->CURPAGE;?></label>
          <?php echo $pager->jump_menubyid($_GET['id']);?>
          </div>
        <div class="field">
          <label><?php echo Lang::$word->IPP;?></label>
          <?php echo $pager->items_per_pagebyid($_GET['id']);?>
          </div>
          <?php endif;?>
         </div>
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("couponspecials", "updatemulticoupons");?>" name="wojo_form">
        <div class="three fields">
          <div class="field">
            <div class="wojo input"> <i class="icon-prepend icon calendar"></i>
              <input name="fromdate" type="text" id="fromdate" placeholder="<?php echo Lang::$word->FROM;?>" readonly data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
            </div>
          </div>
         <div class="field">
            <div class="wojo action input"> <i class="icon-prepend icon calendar"></i>
              <input name="enddate" type="text" id="enddate" placeholder="<?php echo Lang::$word->TO;?>" readonly data-date-autoclose="true" data-min-view="2" data-start-view="2" data-date-today-btn="true" data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
              <a id="doDates" class="wojo primary icon button"><?php echo Lang::$word->FIND;?></a> </div>
          </div>
          <div class="field">
            <div class="wojo icon input">
              <input type="text" name="webspecialssearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
              <i class="find icon"></i>
              <div id="suggestions"> </div>
            </div>
          </div>
         
         </div>
        </form>
        <!--  
        <div class="content-center">
      <div class="wojo divided horizontal link list">
        <div class="disabled item"> <?php echo Lang::$word->SORTING_O;?> </div>
        <a href="<?php echo Url::adminUrl("couponspecials");?>" class="item<?php echo Url::setActive("order", false);?>"> <?php echo Lang::$word->DEFAULT;?> </a> <a href="<?php echo Url::adminUrl("couponspecials", false, false, "?order=coupon_title/DESC");?>" class="item<?php echo Url::setActive("order", "coupon_title");?>"> <?php echo Lang::$word->WSP_NAME;?> </a> <a href="<?php echo Url::adminUrl("couponspecials", false,false, "?order=coupon_type/DESC");?>" class="item<?php echo Url::setActive("order", "coupon_type");?>"> <?php echo Lang::$word->WSP_DEALTYPE;?> </a> <a href="<?php echo Url::adminUrl("couponspecials", false,false, "?order=year/DESC");?>" class="item<?php echo Url::setActive("order", "year");?>"> #<?php echo Lang::$word->WSP_YEAR;?> </a>
        <div class="item" data-content="ASC/DESC"><a href="<?php echo Url::sortItems(Url::adminUrl("couponspecials"), "order");?>"><i class="icon unfold more link"></i></a> </div>
      </div>
    </div>  
          -->
    </div>
  </div>
  <div class="footer">
    <div class="content-center"> <?php echo Validator::alphaBits(Url::adminUrl("couponspecials", "updatemulticoupons"), "letter", "basic pagination menu");?> </div>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix">
  <?php if (!$storename):?>
   <span><?php echo Lang::$word->WSP_SUB;?></span>
   <?php else:?>
   <span>Viewing (<?php echo $storename;?>) Coupon Specials</span>
    <?php endif;?>
    <?php if(Auth::hasPrivileges('edit_items')):?>
    <?php $editmcoupon = "Edit Multiple Coupon";?>
    <a class="wojo large top right detached action label"  data-content="<?php echo $editmcoupon;?>"><i class="icon pencil link"></i></a>
   <?php endif;?>
  </div>
  <br>
  <br>
  <?php if(!$data):?>
    <table class="wojo table" id="editable">
        <tr>
          <td colspan="9"><?php Message::msgSingleAlert(Lang::$word->WSP_NOLIST);?></td>
        </tr>
   </table>
    <?php else:?>
    <form method="post" id="wojo_forml" name="wojo_forml">
    <table class="wojo sortable table">
      <thead>
        <tr>
        
          <th class="disabled"> <label class="fitted small checkbox">
              <input type="checkbox" name="masterCheckbox" data-parent="#listtable" id="masterCheckbox">
              <i></i></label>
          </th>
          <th class="disabled">Brand Logo</th>
          <th data-sort="string">SERVICE COUPON <?php echo Lang::$word->DESC;?></th>
          <th class="disabled"><?php echo Lang::$word->WSP_ROOM;?></th>
          <!--  <th data-sort="string"><?php echo Lang::$word->LST_CAT;?></th>
          <th class="disabled">Alerts</th>
          <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>-->
        </tr>
      </thead>
      <tbody id="listtable">
        <?php if(!$data):?>
        <tr>
          <td colspan="9"><?php Message::msgSingleAlert(Lang::$word->WSP_NOLIST);?></td>
        </tr>
        <?php else:?>
        <?php foreach($data as $row):?>
        <tr data-id="<?php echo $row->csid;?>">
          <td><label class="fitted small checkbox">
              <input class="source" name="type"  type="checkbox" value="<?php echo $row->csid;?>">
              <i></i></label></td>
          <td><a data-lightbox="true" data-title="(<?php echo $row->year;?>)  <?php echo $row->coupon_title;?>" href="<?php echo $row->storeLogo;?>" href="<?php echo $row->storeLogo;?>"><img data-storeLogoImage="true" src="<?php echo $row->storeLogo;?>" alt="" class="wojo normal grid"></a></td>
          <td><b>(<?php echo $row->year;?>)  <?php echo $row->coupon_title;?> </b>  <br />
           Expiration: <b><span class="exp"><?php echo Utility::dodate("short_date", $row->coupon_expiration_date);?></span></b> <br />
            <?php if($row->coupon_price_amount == 0 && $row->coupon_cents == 0):?>
            <?php else:?>
             <?php echo Lang::$word->WSP_PRICE;?>: ($<?php echo $row->coupon_price_amount;?>.<?php echo $row->coupon_cents;?>) </small><br />
           <?php endif;?>
          <?php if($row->created > 0 && $row->modified == 0):?>
          <?php echo Lang::$word->CREATED;?>: <b><?php echo Utility::dodate("short_date", $row->created);?></b><br />
          <!-- <?php echo Lang::$word->CREATED;?> <?php echo Lang::$word->BY;?>: <?php echo $row->username;?> -->
          <?php else:?>
          <?php echo Lang::$word->MODIFIED;?>: <b><?php echo ($row->modified <> 0) ? Utility::dodate("short_date", $row->modified): ''?></b><br />
          <?php echo Lang::$word->MODIFIED;?> <?php echo Lang::$word->BY;?>: <?php echo $row->username;?>
          <?php endif;?>
         <td><?php echo $row->storename_ws;?></td>
        </tr>
        <?php endforeach;?>
         <script type="text/javascript"> 
		// <![CDATA[  
		$(document).ready(function () {
			$("[data-content]").on('click', function () {
				var id = $(this).data('content');
				var selected_value = []; // initialize empty array 
				$(".source:checked").each(function(){
			        selected_value.push($(this).val());
			    });
			    //console.log(selected_value); //Press F12 to see all selected values
				var listid = selected_value;
				//alert(listid)
				Messi.load(ADMINURL + '/helper.php', {
					loadMultipleCoupon: 1,
					id: id,
					listid: listid
					}, '',{
					title: id,
		            buttons: [{
		                id: 0,
						class: 'positive dosubmit',
		                label: "<?php echo Lang::$word->SUBMIT;?>",
		                val: 'action'
		            }],
					callback: function (val) {}
				});
			});
		});
		// ]]>
		</script>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
      <?php if($data):?>
      <tfoot>
        <tr>
          <td colspan="6"><button name="mdelete" type="button" data-form="#wojo_forml" class="wojo negative button"><i class="icon trash alt"></i><?php echo Lang::$word->WSP_DELETES;?></button>
            <input name="delete" type="hidden" value="deleteMultiWebspecials"></td>
        </tr>
      </tfoot>
      <?php endif;?>
    </table>
  </form>
<div class="footer">
    <div class="wojo tabular segment">
      <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
      <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
    </div>
  </div>
 </div>
</div>
<?php endif;?> 
<?php break;?>
<?php default: ?>
<?php $data = $cSpecials->getCouponspecials(false, "");?>
<?php $storedata = $content->getLocations();?>

<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->WSP_TITLE;?></div>
    <p><?php echo Lang::$word->WSP_INFO;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->FILTER;?></div>
  <div class="content">
    <div class="wojo form">
      <div class="three fields">
        <div class="field">
         <label><?php echo Lang::$word->WSP_ROOM_S;?></label>
            <select name="sid" data-links="true">
              <option value="<?php echo Url::adminUrl("couponspecials");?>">--- <?php echo Lang::$word->TRX_RESET_FILTER;?> ---</option>
              <?php if($storedata):?>
              <?php foreach($storedata  as $srow):?>
              <?php $selected = ($srow->store_id == $_GET['id']) ? ' selected="selected"' : null;?>
              <?php $storename = $db->getValueByStoreId(Content::lcTable, "name", $_GET['id']);
              $active = 'AND cs.active = 1';
              $counter = $db->count(false, false, "SELECT COUNT(*) FROM `" . cSpecials::csTable . "` AS cs WHERE store_id = '" . $srow->store_id . "' $active ");
              ?>
              <option value="<?php echo Url::adminUrl("couponspecials", false, false,"?id=" . $srow->store_id);?>"<?php echo $selected;?>><?php echo $srow->name;?> (<?php echo $counter;?>)</option>
              <?php endforeach;?>
              <?php endif;?>
            </select>
          </div>
          <?php if (!$storename):?>
          <div class="field">
		   <label><?php echo Lang::$word->CURPAGE;?></label>
          <?php echo $pager->jump_menu();?>
          </div>
          <div class="field">
          <label><?php echo Lang::$word->IPP;?></label>
          <?php echo $pager->items_per_page();?>
          </div>
           <?php else:?>
		  <div class="field"> 
          <label><?php echo Lang::$word->CURPAGE;?></label>
          <?php echo $pager->jump_menubyid($_GET['id']);?>
          </div>
        <div class="field">
          <label><?php echo Lang::$word->IPP;?></label>
          <?php echo $pager->items_per_pagebyid($_GET['id']);?>
          </div>
          <?php endif;?>
         </div>
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("couponspecials");?>" name="wojo_form">
        <div class="three fields">
          <div class="field">
            <div class="wojo input"> <i class="icon-prepend icon calendar"></i>
              <input name="fromdate" type="text" id="fromdate" placeholder="<?php echo Lang::$word->FROM;?>" readonly data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
            </div>
          </div>
         <div class="field">
            <div class="wojo action input"> <i class="icon-prepend icon calendar"></i>
              <input name="enddate" type="text" id="enddate" placeholder="<?php echo Lang::$word->TO;?>" readonly data-date-autoclose="true" data-min-view="2" data-start-view="2" data-date-today-btn="true" data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
              <a id="doDates" class="wojo primary icon button"><?php echo Lang::$word->FIND;?></a> </div>
          </div>
          <div class="field">
            <div class="wojo icon input">
              <input type="text" name="webspecialssearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
              <i class="find icon"></i>
              <div id="suggestions"> </div>
            </div>
          </div>
         
         </div>
        </form>
        <!--  
        <div class="content-center">
      <div class="wojo divided horizontal link list">
        <div class="disabled item"> <?php echo Lang::$word->SORTING_O;?> </div>
        <a href="<?php echo Url::adminUrl("couponspecials");?>" class="item<?php echo Url::setActive("order", false);?>"> <?php echo Lang::$word->DEFAULT;?> </a> <a href="<?php echo Url::adminUrl("couponspecials", false, false, "?order=coupon_title/DESC");?>" class="item<?php echo Url::setActive("order", "coupon_title");?>"> <?php echo Lang::$word->WSP_NAME;?> </a> <a href="<?php echo Url::adminUrl("couponspecials", false,false, "?order=coupon_type/DESC");?>" class="item<?php echo Url::setActive("order", "coupon_type");?>"> <?php echo Lang::$word->WSP_DEALTYPE;?> </a> <a href="<?php echo Url::adminUrl("couponspecials", false,false, "?order=year/DESC");?>" class="item<?php echo Url::setActive("order", "year");?>"> #<?php echo Lang::$word->WSP_YEAR;?> </a>
        <div class="item" data-content="ASC/DESC"><a href="<?php echo Url::sortItems(Url::adminUrl("couponspecials"), "order");?>"><i class="icon unfold more link"></i></a> </div>
      </div>
    </div>  
          -->
    </div>
  </div>
  <div class="footer">
    <div class="content-center"> <?php echo Validator::alphaBits(Url::adminUrl("couponspecials"), "letter", "basic pagination menu");?> </div>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix">
  <?php if (!$storename):?>
   <span><?php echo Lang::$word->WSP_SUB;?></span>
   <?php else:?>
   <span>Viewing (<?php echo $storename;?>) Coupon Specials</span>
    <?php endif;?>
    <?php if(Auth::hasPrivileges('add_items')):?>
    <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->WSP_ADD;?>" href="<?php echo Url::adminUrl("couponspecials", "add");?>" ><i class="icon plus"></i></a>
    <?php endif;?>
  </div>
  <br>
  <br>
  <?php if(!$data):?>
    <table class="wojo table" id="editable">
        <tr>
          <td colspan="9"><?php Message::msgSingleAlert(Lang::$word->WSP_NOLIST);?></td>
        </tr>
   </table>
    <?php else:?>
    <div id="ncsMain" data-ncsid="5b292108944c8" class="qsNCPSPage">
   <div class="NCPS">
  <?php foreach($data as $row):?>
           
           <?php 
           $phonenumber = $row->servicephone;
           	
           $phonenumformated = "(".substr($phonenumber,0,3).") ".substr($phonenumber,3,3)."-".substr($phonenumber,6);
           	
           $mainImageHide = $row->coupon_image_show_hide;
           
           $imageDivStyle = '';
           
           if($mainImageHide == '0'){
           	$imageDivStyle = 'style="display:none;"'; //hide div
           }
           	
           $priceHide = $row->coupon_price_show_hide;
           	
           $priceDivStyle = '';
           	
           if($priceHide == '0'){
           	$priceDivStyle = 'style="display:none;"'; //hide div
           }
           	
           	
           $priceCentsHide = $row->coupon_cents;
           
           $priceCentsDivStyle = '';
           
           if($priceCentsHide == '0'|| $priceCentsHide == ''){
           	$priceCentsDivStyle = 'style="display:none;"'; //hide div
           }
           
           $couponTitle = $row->coupon_title;
           
           $dealerPanelTheme = $row->panel_class;
           
           $dealerBtnTheme = $row->btn_class;
           
           $dealerLogo = $row->storeLogo;
           
           $expireDate = date('m/d/Y', strtotime($row->coupon_expiration_date));
           
           $couponInfoArea =  $row->coupon_info_area;
           
           $couponPriceAmount = $row->coupon_price_amount;
          
           $couponCents = $row->coupon_cents;
           
           $couponImage = $row->coupon_image;
           
           $disclaimerText = $row->disclaimer_text;
           
           $dealerName = $row->storename;
           
           $dealerAddress = $row->address1;
           
           $dealerCity = $row->city;
           
           $dealerState = $row->state;
           
           ?>
           
           <div class="IndivSpecial qsNCPSContent" id="<?php echo $row->csid;?>">
               <div class="ncpspanel ncps<?php echo $dealerPanelTheme;?> coupon">
           <div class="ncpspanel-heading" id="head">
                <div class="ncpspanel-title ncps_title_wrap" id="title">
                    <div class="ncps_title_left">
                        <span><?php echo $couponTitle;?></span>
                    </div>
                    <div class="ncps_title_right">
                    <img src="<?php echo $dealerLogo;?>">
                     </div>
                </div>
              </div>
              <div class="ncpspanel-body">
                <div class="ncpsparbdy">
                        <span class="exp">Expires: <?php echo $expireDate;?></span>                        
                        <div class="ncps_coupon_info_wrap">
                        <?php echo $couponInfoArea;?>
                        </div> 
                </div>
                <div class="ncps_cost_right"> 
                    <div class="offer"<?php echo $priceDivStyle;?>>
                        <span class="usd">$</span>
                        <span class="number"><?php echo $couponPriceAmount;?></span>
                        <span class="cents" <?php echo $priceCentsDivStyle;?>><?php echo $couponCents;?></span>
                    </div>
                </div>
                <div class="ncpsclearfix"></div>
                <div class="ncpsdiscwrap">
                 <img src="<?php echo $couponImage;?>" class="coupon-img img-rounded" <?php echo $imageDivStyle;?>>
                    <p class="disclosure">
                  <?php echo $disclaimerText;?>
                    </p>
                </div>
                <div class="ncpswell">
                  <div id="business-info">
                 <div class="ncps_storename"><div class="ncps_store_name_wrap"><?php echo $dealerName;?></div></div>
                        <div class="ncps_store_phone"><div class="ncps_store_phone_wrap"> <i class="fa fa-phone"></i><?php echo $phonenumformated;?></div></div>
                        <div class="ncps_store_address"><span><i class="fa fa-map-marker"><?php echo $dealerAddress;?></i>, <?php echo $dealerCity;?>, <?php echo $dealerState;?></span></div>                      
                  </div>
               </div>
               <div class="ncpspanel-footer ncps_footer_flex">
                <div class="coupon-code">
                    <span class="print">
                       <a href="/contact-us/" class="ncpsbtn ncps<?php echo  $dealerBtnTheme;?>"><i class="fa fa-lg fa-address-card"></i> Contact Us</a>
                       <a href="/service/schedule-service/" class="ncpsbtn ncps<?php echo  $dealerBtnTheme;?>"><i class="fa fa-lg fa fa-car"></i> Schedule Service</a>
                       <!-- <a href="#cpspecialsPrint_1621" class="btn ncpsbtn-warning hidden-touch print-button"><i class="fa fa-lg fa-print"></i> Print Coupon</a> -->
                       <button type="button" class="ncpsbtn ncps<?php echo  $dealerBtnTheme;?> ncpsprintbtn" data-target="#cpspecialsPrints_1621">Print Coupon</button>
                    </span>
               
               
                </div>
            
             
             
             
             </div>
             <div class="ncpspanel-footer ncps_footer_flex">
               <div class="coupon-code">
             <div class="data"> <a class="doStatus" data-set='{"field": "status", "table": "Webspecials", "toggle": "check ban", "togglealt": "positive purple", "id": <?php echo $row->csid;?>, "value": "<?php echo $row->active;?>"}' data-content="<?php echo Lang::$word->ACTIVE;?>"><i class="rounded inverted <?php echo ($row->active) ? "check positive" : "ban purple";?> icon link"></i></a>  
             <?php if ($storename):?>
            <a id="dosubmit" data-id="<?php echo $row->csid;?>" data-content="Copy Coupon Special"><i class="rounded outline purple icon copy link"></i></a> 
             <?php endif;?>
             <!-- <a href="<?php echo Url::adminUrl("couponspecials", "printws", false,"?id=" . $row->csid);?>"><i class="rounded outline purple icon printer link"></i></a> -->
            <?php if(Auth::hasPrivileges('edit_items')):?>
            <a href="<?php echo Url::adminUrl("couponspecials", "edit", false,"?id=" . $row->csid);?>" data-content="Edit Coupon Special"><i class="rounded outline positive icon pencil link"></i></a>
            <?php endif;?>
            <?php if(Utility::dodate("short_date", $row->csmodified) == Utility::dodate("short_date",Utility::today())&& $row->update_flag == 1):?>
       		<a href="<?php echo Url::adminUrl("couponspecials", "couponspecialsalert", false,"?id=" . $row->csid);?>"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt=""  height="30" width="30"> </a>
      		<?php else:?>
        	<a href="<?php echo Url::adminUrl("couponspecials", "couponspecialsalert", false,"?id=" . $row->csid);?>" style="display: none"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt=""  height="30" width="30"> </a>
        	<?php endif;?>
            <?php if(Auth::hasPrivileges('delete_items')):?>
            <a class="delete" data-set='{"title": "<?php echo Lang::$word->WSP_DELETE;?>", "parent": "tr", "option": "deleteCouponspecials", "id": <?php echo $row->csid;?>, "name": "<?php echo $row->coupon_title;?>"}'data-content="Delete Coupon Special"><i class="rounded outline icon negative trash link"></i></a>
            <?php endif;?>
          </div>
          </div>
             </div>
             </div>                 
             </div> 
            </div> 
  <?php endforeach;?>
   <script type="text/javascript"> 
			// <![CDATA[
			$(document).ready(function() {
				$('a#dosubmit').on('click', function() {
                    var id = $(this).data('id');
			        var values = "id="+id;
			        values+= "&processCouponspecialsDubs=1";
			        values += "&id="+id;
			        values += "&action=processCouponspecialsDubs";
			        $.ajax({
			            type: 'post',
			            url: ADMINURL + "/controller.php",
			            dataType: 'json',
			            data: values,
			           success: function(json) {
			                if (json.type == "success") {
			                    //alert(data);
			                    $(".wojo.info.message").remove();
			                    
			                   setTimeout("window.location.href=window.location.href;",5000);
			                }
			                $.sticky(decodeURIComponent(json.message), {
			                    type: json.type,
			                    title: json.title
			                    
			                });
			            } 
			        });
			        
			    });
				 
			});    

// ]]>
</script>
  <?php unset($row);?>
  <?php endif;?>
 </div>
</div>
<?php if(!$data):?>
<div class="footer">
    
  </div>
<?php else:?>
<div class="footer">
    <div class="wojo tabular segment">
      <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
      <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
    </div>
  </div>
 </div>
</div>
<?php endif;?> 
<?php endswitch;?>