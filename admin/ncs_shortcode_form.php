<?php 
/*===============================================================

Produce Quirk NCS Shortcode Form

===============================================================*/
function get_quirk_ncs_shortcode_form( $valsArr, $interests, $post_id ){ 
	global $storePhone;
	global $storeLetter;
	?>
	<div class="specialsShortCodeFormContainer">		
		<form name="getSpecialForm-<?php echo $valsArr['special_id']; ?>" id="getSpecialForm-<?php echo $valsArr['special_id']; ?>" class="ncs-lead-form" rel="<?php echo $valsArr['special_id']; ?>">				
			<div class="NCSShortCodeFormOptionWrapper">
				<div class="NCSShortCodeFormLeft">
					<div class="NCSSCFormInput">
						<div class="NCSSCFormPrimaryInput">
							<div class="NCSFormIPIndividual">
								<label>First Name:</label>
								<input type="text" class="customer_first" name="customer_first" tabindex="1"/>
								<p class="NCSRequiredFormParagraph"></p>
							</div>
							<div class="NCSFormIPIndividual">
								<label>Last Name:</label>
								<input type="text" class="customer_last" name="customer_last" tabindex="2"/>
								<p class="NCSRequiredFormParagraph"></p>
							</div>
							<div class="NCSFormIPIndividual">
								<label>Email:</label>				
								<input type="email" class="customer_email" name="customer_email" tabindex="3"/>
								<p class="NCSRequiredFormParagraph"></p>
							</div>
							<div class="NCSFormIPIndividual">
								<label>Phone Number:</label>				
								<input type="text" class="customer_phone" name="customer_phone" tabindex="4"/>
								<p class="NCSRequiredFormParagraph"></p>				
								<input type="hidden" name="special_model" value="<?php echo $valsArr['model']; ?>" />
								<input type="hidden" name="from_page" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
								<input type="hidden" name="ref_post_id" value="<?php echo $post_id; ?>" />	
							</div>
						</div>			
						<?php
						$hasLease = FALSE;
						$hasSingle = FALSE;
						$hasZD = FALSE;
						$hasAPR = FALSE;
						$hasSUT = FALSE;
						$hasCustomPrice = FALSE;
						
						if( isset($valsArr['lease_price']) && $valsArr['lease_price'] != '' && $valsArr['lease_price'] != 0 && (!isset($valsArr['single_lease_price']) || $valsArr['single_lease_price'] == 0 || $valsArr['single_lease_price'] == '' )  && isset($interests['lease']) ){
							$hasLease = TRUE;
						}
						if( isset($valsArr['single_lease_price']) && $valsArr['single_lease_price'] != '' && $valsArr['single_lease_price'] != 0 && isset($interests['single_pay']) ){
							$hasSingle = TRUE;
						}
						if( isset($valsArr['zero_down_lease_price']) && $valsArr['zero_down_lease_price'] != '' && $valsArr['zero_down_lease_price'] != 0 && isset($interests['zero_down']) ){
							$hasZD = TRUE;					
						}
						if( isset($valsArr['available_apr']) && $valsArr['available_apr'] != '' && $interests['apr'] ) {
							$hasAPR = TRUE;
						}
						if( isset($valsArr['save_up_to_amount']) && $valsArr['save_up_to_amount'] != '' && $valsArr['save_up_to_amount'] != '0' && isset($interests['savings']) ){
							$hasSUT = TRUE;
						}
						if( isset($valsArr['custom_price_val']) && $valsArr['custom_price_val'] != '' && $valsArr['custom_price_val'] != '0' && isset($interests['cust_price']) ){
							$hasCustomPrice = TRUE;
						}
						if( $hasLease == TRUE || $hasSingle == TRUE || $hasZD == TRUE || $hasAPR == TRUE || $hasSUT == TRUE || $hasCustomPrice == TRUE ){
						?>				
						<div class="NCSSCFormOptions">
							<div class="NCSSCFormOptionsCenterDiv">
								<h5>I am interested in&nbsp;<span>(optional):</span></h5>
								<?php 
								if($hasLease){ ?>
									<div class="NCSIndividualOptions">
										<div class="NCSOptionChecker">
											<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_lease" tabindex="6" value="Interested in lease w/money down." />
											<label class="NCSCheckboxLabel"></label>
										</div>
										<div class="NCSOptionText">
											<span>$<?php echo number_format($valsArr['lease_price']); ?>/mo with $<?php echo number_format($valsArr['lease_down_payment']); ?> down</span>
										</div>
									</div>
								<?php 
								}
								if($hasSingle){ ?>
									<div class="NCSIndividualOptions">
										<div class="NCSOptionChecker">
											<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_single" tabindex="6" value="Interested in single pay lease." />
											<label class="NCSCheckboxLabel"></label>
										</div>
										<div class="NCSOptionText">
											<span>$<?php echo number_format($valsArr['single_lease_price']); ?> single pay lease</span>
										</div>
									</div>
								<?php 
								}
								if( $hasZD ){ ?>
									<div class="NCSIndividualOptions">
										<div class="NCSOptionChecker">
											<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_zero_down" tabindex="7" value="Interestested in zero down lease." />
											<label class="NCSCheckboxLabel"></label>
										</div>
										<div class="NCSOptionText">
											<span>$<?php echo number_format($valsArr['zero_down_lease_price']); ?>/mo with $0 down</span>
										</div>
									</div>
								<?php 
								}
								if( $hasAPR ){ ?>
									<div class="NCSIndividualOptions">
										<div class="NCSOptionChecker">
											<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_apr" tabindex="8" value="Interested in promotional APR." />
											<label class="NCSCheckboxLabel"></label>
										</div>
										<div class="NCSOptionText">
											<span><?php echo doubleval($valsArr['available_apr']); ?>% APR financing</span>
										</div>
									</div>
								<?php 
								} 
								if( $hasSUT ){ ?>
									<div class="NCSIndividualOptions">
										<div class="NCSOptionChecker">
											<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_savings" tabindex="9" value="Interested in purchase savings." />
											<label class="NCSCheckboxLabel"></label>
										</div>
										<div class="NCSOptionText">
											<span>$<?php echo number_format($valsArr['buy_price']); ?> quirk price</span>
										</div>
									</div>
								<?php 
								} 
								//if( $hasCustomPrice ){ ?>
									<!-- div class="NCSIndividualOptions">
										<div class="NCSOptionChecker">
											<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_custom" tabindex="10" value="Interested in price with <?php echo $valsArr['custom_price_label']; ?>." />
											<label class="NCSCheckboxLabel"></label>
										</div>
										<div class="NCSOptionText">
											<span>&nbsp;&nbsp;This price with <?php echo strtolower( $valsArr['custom_price_label'] ); ?></span>
										</div>
									</div-->
								<?php 
								//} ?>
							</div>
						</div>
					</div>
					<div class="NCSFormSCDisc">
						<p class="formDisclaimer"><?php echo $valsArr['default_disclaimer_text']; ?></p>
					</div>
				</div>
				<?php 
				} ?>
				<div class="NCSShortCodeFormRight">
					<div class="ncsShortCodeComments">
						<label>Comments:</label>
						<textarea id="customer_comments" name="customer_comments" tabindex="5"></textarea>
					</div>
					<div class="specialCTAs">	
					  <a class="valueTrade" href="/<?php echo get_option('value_trade_link');?>"><span>Value Trade</span></a>	              
		              <a class="viewInventory" href="<?php echo ($storeLetter === 't') ?  "/inventory/New/?search=".$valsArr['model'] : $valsArr['inventory_url']; ?>"><span>Inventory</span></a>
		              <button type="button" class="ncsFormSubmit" name="special_submit" rel="<?php echo $valsArr['special_id']; ?>">Submit</button>
		            </div>
		            <div class="NCSSCFormdiscMobileBtn">
		              <span>full disclaimer</span>
		            </div>
		            <div class="NCSFormSCDiscMobile">
						<p class="formDisclaimerMobile"><?php echo $valsArr['default_disclaimer_text']; ?></p>
					</div> 
	            </div>
			</div>			
		</form>
	</div>
<?php 
}
?>