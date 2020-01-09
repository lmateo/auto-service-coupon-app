<?php

/*
 * 
 * ncpsApi - Communication Tool for 3rd Party to connect to the Coupon Service Specials
 * 
 * # How to run script.  use the code below 
 * $arr = $_POST; (post via curl or http)
 * $aNcps = new ncpsApi();
 * $aNcps->apiDataArr = $arr;
 * $retXml = $aNcps->handleRequest();
 * print $retXml;
 * 
 * using 1 of the methods below...
 * 
 * # Do an Auth Check
 * public_key (required)
 * task = "AUTHCHECK" (required)
 * wp_sp_domain (required)
 * 
 * # Do a Coupon Service Specials Search
 * public_key (required)
 * task = "NCPS_SEARCH" (required)
 * wp_sp_domain (required)
 * specials_type (defaults to "COUPON")
 * store_letter
 * store_id
 * active  (defaults to "1")
 * coupon_type 
 * year
 * order_by 
 * order_direction 
 * limit
 * 
 * # Sync Database of IDs
 * public_key (required)
 * task = "NCPS_SYNC" (required)
 * wp_sp_domain (required)
 * wp_sp_ids (required)
 * 
 * 
 */

//modified the below class which was originally an extensions of the quirk class but now is a standalone and includes the run_qury function from quirk
class qinvApi {
	public $dbname = "quirkspe_inventory";
	public $qinvspecialsTblName = "inventory_test";
	public $syncTblName = "inventory_wp";
	public $storesTblName = "stores";
	public $errorsArr = array();
	public $apiDataArr = array();
	public $authorized = FALSE;
	public $privateKeyqinv = "fjFB9E!5h_HC015";
	public $publicKey = "";
	public $cD1 = "<![CDATA[";
	public $cD2 = "]]>";
	public $retXml = "";
	public $retInventoryXml = "";
	public $retErrorXml = "";
	public $apiSqlResult = "";	
	public $apiSqlWhere = "";
	public $apiSqlQuery = ""; 
	public $whereArr = array();
	public $apiTotalQInventoryFound = 0;

	function _run_query($query){ //internal
		$dbuser="quirkspe_ncsuser";
		$dbpass="(OTiJdM%.JUs";
		//$chandle = mysql_connect("localhost", $dbuser, $dbpass)	depreciated
		$chandle = mysqli_connect("localhost", $dbuser, $dbpass, $dbname)
		or die("Connection Failure to Database");
		//mysql_select_db($this->dbname, $chandle) or die ($dbname . " Database not found. User: " . $dbuser); depreciated
		mysqli_select_db($chandle,$this->dbname) or die ($dbname . " Database not found. User: " . $dbuser);
		//$result = mysql_query($query,$chandle) or die("Error with Query String($query): ". mysql_error()); depreciated
		$result = mysqli_query($chandle,$query) or die("Error with Query String($query): ". mysqli_error($chandle));
		//mysql_close($chandle); depreciated
		mysqli_close($chandle);
		return $result;
	}
	
	/*function ncpsApiMethod()
	{
		$this->publicKey = "";
		$this->authorized = FALSE;
	}
     */
	function authorizeMe()
	{
		if( $this->publicKey == $this->privateKeyqinv )
		{
			$this->authorized = TRUE;
		}
		else
		{
			$this->authorized = FALSE;
			$this->errorsArr[] = "Invalid Authorization [".$this->publicKey."]";
		}
		return $this->authorized;
	}

	function getAuthReponse()
	{		
		$this->retXml = "";
		$this->retXml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
		$this->retXml .= "<qinv version=\"1.0\">\r\n";
		$this->retXml .= "	<messages>\r\n";
		$this->retXml .= "	  <message>Authorized User!</message>\r\n";
		$this->retXml .= "	</messages>\r\n";
		$this->retXml .= "</qinv>\r\n";
	}

	function handleRequest()
	{
		$this->apiSqlResult = "";
		$this->apiSqlWhere = "";
		$this->whereArr = array();

		if( count($this->apiDataArr) > 0 )
		{
			$this->publicKey = $this->apiDataArr['public_key'];

			if( isset($this->apiDataArr['public_key']) )
			{
				if( isset($this->apiDataArr['task']) )
				{
					switch( strtoupper($this->apiDataArr['task']) )
					{
						case "AUTHCHECK":
							$this->authorizeMe();
							$this->getAuthReponse();		
							break;
								
						case "QINV_SEARCH":
							if( $this->authorizeMe() )
							{
								$this->doQinvSearch();
							}
							break;

						case "QINV_SYNC":
							if( $this->authorizeMe() )
							{
								$this->sync_wp_quirk_inventory();
							}
							break;

						default:
							$this->errorsArr[] = "Error [invalid task]";
							break;
					}
				}
				else
				{
					$this->errorsArr[] = "Error [no task]";
				}
			}
			else
			{
				$this->errorsArr[] = "Invalid Authorization [no key]";
			}
		}
		else
		{
			$this->errorsArr[] = "Error [nothing at all Quirk Inventory]";
		}
		
		/* 
		successful return code should be built at this point if successful. 
		check for errors and overwrite returning xml code with error message	
		*/
		
		if( count($this->errorsArr) > 0 )
		{
			$this->getApiErrorReponse();
			$this->retXml = $this->retErrorXml; 
		}
	}

	function doQinvSearch()
	{
		$this->doQinvBuildSearchQuery();
		$this->getQinvSearchReponse();
	}

	function doQinvBuildSearchQuery()
	{	
		$this->apiSqlQuery = "SELECT *, 
							(select post_id from ".$this->syncTblName." WHERE 
							quirk_inventory_id=".$this->qinvspecialsTblName.".id AND domain_name='".addslashes($this->apiDataArr['wp_sp_domain'])."' ) as post_id  
							FROM ".$this->qinvspecialsTblName." [WHERE] order by [ORDER_FIELD] [ORDER_DIRECTION]";
		
		
		

		

		if( isset($this->apiDataArr['store_letter']) ) 
		{
			$this->whereArr[] = "UPPER(store_letter)='".addslashes(strtoupper($this->apiDataArr['store_letter']))."'";
		}

		if( isset($this->apiDataArr['store_id']) ) 
		{
			// these values are all uppercase.  just form value to upper
			$this->whereArr[] = "UPPER(store_letter)=(SELECT UPPER(letter) from ".$this->storesTblName." where id='".addslashes($this->apiDataArr['store_id'])."')";
		}
		
		/*if( isset($this->apiDataArr['inventory_type']) )
		{
			// these values are all uppercase.  just form value to upper
			$this->whereArr[] = "UPPER(inventory_type)='".addslashes(strtoupper($this->apiDataArr['inventory_type']))."'";
		}
		else
		{
			$this->whereArr[] = "inventory_type='QUIRK-INVENTORY'";
		}
        */

		if( isset($this->apiDataArr['Year']) ) 
		{
			$this->whereArr[] = "Year='".addslashes($this->apiDataArr['Year'])."'";
		}

		if( isset($this->apiDataArr['order_by']) )
		{
			$this->apiSqlQuery = str_replace("[ORDER_FIELD]","'".addslashes($this->apiDataArr['order_by'])."'",$this->apiSqlQuery);
			if( isset($this->apiDataArr['order_direction']) )
			{
				$this->apiSqlQuery = str_replace("[ORDER_DIRECTION]","'".addslashes($this->apiDataArr['order_direction'])."'",$this->apiSqlQuery);
			}
			else
			{
				$this->apiSqlQuery = str_replace("[ORDER_DIRECTION]","",$this->apiSqlQuery);
			}
		}
		else
		{
			$this->apiSqlQuery = str_replace("[ORDER_FIELD]","id",$this->apiSqlQuery);
			$this->apiSqlQuery = str_replace("[ORDER_DIRECTION]","",$this->apiSqlQuery);
		}
		
		if( isset($this->apiDataArr['limit']) ) {
			$this->whereArr[] = "limit '".addslashes($this->apiDataArr['limit'])."')";
		}
		
		if( count($this->whereArr) > 0 )
		{
			$this->apiSqlWhere = implode(" AND ", $this->whereArr);
		}
		
		$this->apiSqlQuery = str_replace("[WHERE]","WHERE ".$this->apiSqlWhere,$this->apiSqlQuery);
		
		$this->apiSqlResult = $this->_run_query($this->apiSqlQuery);
		
		$this->apiTotalQInventoryFound = mysqli_num_rows($this->apiSqlResult);
		
		}

	function getQinvSearchReponse()
	{		
		$this->retXml = "";
		$this->retXml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
		$this->retXml .= "<qinv version=\"1.0\">\r\n";
		$this->retXml .= "<qinventories total=\"" . $this->apiTotalQInventoryFound . "\">\r\n";
		if( $this->apiTotalQInventoryFound > 0 ) 
		{
			$storesArr = array();
			$sql1 = "SELECT * FROM ".$this->storesTblName." WHERE hasSales='1' order by letter";
			$result1 = $this->_run_query($sql1);
			//if( mysql_num_rows($result1) > 0 ) 
			if( mysqli_num_rows($result1) > 0 )
			{
				//while( $row1 = mysql_fetch_array($result1) ) 
				while( $row1 = mysqli_fetch_array($result1) )
				{
					$storeLetter = strtolower(stripslashes($row1['letter']));
					$storesArr[$storeLetter] = $row1;
				}
			}

			//while( $row = mysql_fetch_array($this->apiSqlResult) ) 
			while( $row = mysqli_fetch_array($this->apiSqlResult) )
			{
				$vStoreLetter2 = strtolower(stripslashes($row['store_letter']));
				$this->retXml .= "<qinventory id=\"" . stripslashes($row['id']) . "\" post_id=\"" . stripslashes($row['post_id']) . "\" store_letter=\"" . stripslashes($row['store_letter']) . "\" leads=\"\">\r\n";
				$this->retXml .= " <Dealer_ID>" . $this->cD1 . stripslashes($row['Dealer_ID']) . $this->cD2 . "</Dealer_ID>\r\n";
				$this->retXml .= " <Type>" . $this->cD1 . stripslashes($row['Type']) . $this->cD2 . "</Type>\r\n";
				$this->retXml .= " <Stock>" . $this->cD1 . stripslashes($row['Stock']) . $this->cD2 . "</Stock>\r\n";
				$this->retXml .= " <VIN>" . $this->cD1 . stripslashes($row['VIN']) . $this->cD2 . "</VIN>\r\n";
				$this->retXml .= " <Year>" . $this->cD1 . stripslashes($row['Year']) . $this->cD2 . "</Year>\r\n";
				$this->retXml .= " <Make>" . $this->cD1 . stripslashes($row['Make']) . $this->cD2 . "</Make>\r\n";
				$this->retXml .= " <Model>" . $this->cD1 . stripslashes($row['Model']) . $this->cD2 . "</Model>\r\n";
				$this->retXml .= " <Body>" . $this->cD1 . stripslashes($row['Body']) . $this->cD2 . "</Body>\r\n";
				$this->retXml .= " <Trim>" . $this->cD1 . stripslashes($row['Trim']) . $this->cD2 . "</Trim>\r\n";
				$this->retXml .= " <ModelNumber>" . $this->cD1 . stripslashes($row['ModelNumber']) . $this->cD2 . "</ModelNumber>\r\n";
				$this->retXml .= " <Doors>" . $this->cD1 . stripslashes($row['Doors']) . $this->cD2 . "</Doors>\r\n";
				$this->retXml .= " <ExteriorColor>" . $this->cD1 . stripslashes($row['ExteriorColor']) . $this->cD2 . "</ExteriorColor>\r\n";
				$this->retXml .= " <InteriorColor>" . $this->cD1 . stripslashes($row['InteriorColor']) . $this->cD2 . "</InteriorColor>\r\n";
				$this->retXml .= " <EngineCylinders>" . $this->cD1 . stripslashes($row['EngineCylinders']) . $this->cD2 . "</EngineCylinders>\r\n";
				$this->retXml .= " <EngineDisplacement>" . $this->cD1 . stripslashes($row['EngineDisplacement']) . $this->cD2 . "</EngineDisplacement>\r\n";
				$this->retXml .= " <Transmission>" . $this->cD1 . stripslashes($row['Transmission']) . $this->cD2 . "</Transmission>\r\n";
				$this->retXml .= " <Miles>" . $this->cD1 . stripslashes($row['Miles']) . $this->cD2 . "</Miles>\r\n";
				$this->retXml .= " <SellingPrice>" . $this->cD1 . stripslashes($row['SellingPrice']) . $this->cD2 . "</SellingPrice>\r\n";
				$this->retXml .= " <MSRP>" . $this->cD1 . stripslashes($row['MSRP']) . $this->cD2 . "</MSRP>\r\n";
				$this->retXml .= " <BookValue>" . $this->cD1 . stripslashes($row['BookValue']) . $this->cD2 . "</BookValue>\r\n";
				$this->retXml .= " <Invoice>" . $this->cD1 . stripslashes($row['Invoice']) . $this->cD2 . "</Invoice>\r\n";
				$this->retXml .= " <Certified>" . $this->cD1 . stripslashes($row['Certified']) . $this->cD2 . "</Certified>\r\n";
				$this->retXml .= " <DateInStock>" . $this->cD1 . stripslashes($row['DateInStock']) . $this->cD2 . "</DateInStock>\r\n";
				$this->retXml .= " <Description>" . $this->cD1 . stripslashes($row['Description']) . $this->cD2 . "</Description>\r\n";
				$this->retXml .= " <Options>" . $this->cD1 . stripslashes($row['Options']) . $this->cD2 . "</Options>\r\n";
				$this->retXml .= " <Categorized_Options>" . $this->cD1 . stripslashes($row['Categorized_Options']) . $this->cD2 . "</Categorized_Options>\r\n";
				$this->retXml .= " <Dealer_Address>" . $this->cD1 . stripslashes($row['Dealer_Address']) . $this->cD2 . "</Dealer_Address>\r\n";
				$this->retXml .= " <Dealer_City>" . $this->cD1 . stripslashes($row['Dealer_City']) . $this->cD2 . "</Dealer_City>\r\n";
				$this->retXml .= " <Dealer_State>" . $this->cD1 . stripslashes($row['Dealer_State']) . $this->cD2 . "</Dealer_State>\r\n";
				$this->retXml .= " <Dealer_Zip>" . $this->cD1 . stripslashes($row['Dealer_Zip']) . $this->cD2 . "</Dealer_Zip>\r\n";
				$this->retXml .= " <Dealer_Phone>" . $this->cD1 . stripslashes($row['Dealer_Phone']) . $this->cD2 . "</Dealer_Phone>\r\n";
				$this->retXml .= " <Dealer_Fax>" . $this->cD1 . stripslashes($row['Dealer_Fax']) . $this->cD2 . "</Dealer_Fax>\r\n";
				$this->retXml .= " <Special_Field_1>" . $this->cD1 . stripslashes($row['Special_Field_1']) . $this->cD2 . "</Special_ Field_1>\r\n";
				$this->retXml .= " <Special_Field_2>" . $this->cD1 . stripslashes($row['Special_Field_2']) . $this->cD2 . "</Special_ Field_2>\r\n";
				$this->retXml .= " <Special_Field_3>" . $this->cD1 . stripslashes($row['Special_Field_3']) . $this->cD2 . "</Special_ Field_3>\r\n";
				$this->retXml .= " <Special_Field_4>" . $this->cD1 . stripslashes($row['Special_Field_4']) . $this->cD2 . "</Special_ Field_4>\r\n";
				$this->retXml .= " <Style_Description>" . $this->cD1 . stripslashes($row['Style_Description']) . $this->cD2 . "</Style_Description>\r\n";
				$this->retXml .= " <Ext_Color_Generic>" . $this->cD1 . stripslashes($row['Ext_Color_Generic']) . $this->cD2 . "</Ext_Color_Generic>\r\n";
				$this->retXml .= " <Ext_Color_Code>" . $this->cD1 . stripslashes($row['Ext_Color_Code']) . $this->cD2 . "</Ext_Color_Code>\r\n";
				$this->retXml .= " <Int_Color_Generic>" . $this->cD1 . stripslashes($row['Int_Color_Generic']) . $this->cD2 . "</Int_Color_Generic>\r\n";
				$this->retXml .= " <Int_Color_Code>" . $this->cD1 . stripslashes($row['Int_Color_Code']) . $this->cD2 . "</Int_Color_Code>\r\n";
				$this->retXml .= " <Int_Upholstery>" . $this->cD1 . stripslashes($row['Int_Upholstery']) . $this->cD2 . "</Int_Upholstery>\r\n";
				$this->retXml .= " <Engine_Block_Type>" . $this->cD1 . stripslashes($row['Engine_Block_Type']) . $this->cD2 . "</Engine_Block_Type>\r\n";
				$this->retXml .= " <Engine_Aspiration_Type>" . $this->cD1 . stripslashes($row['Engine_Aspiration_Type']) . $this->cD2 . "</Engine_Aspiration_Type>\r\n";
				$this->retXml .= " <Engine_Description>" . $this->cD1 . stripslashes($row['Engine_Description']) . $this->cD2 . "</Engine_Description>\r\n";
				$this->retXml .= " <Transmission_Speed>" . $this->cD1 . stripslashes($row['Transmission_Speed']) . $this->cD2 . "</Transmission_Speed>\r\n";
				$this->retXml .= " <Transmission_Description>" . $this->cD1 . stripslashes($row['Transmission_Description']) . $this->cD2 . "</Transmission_Description>\r\n";
				$this->retXml .= " <Drivetrain>" . $this->cD1 . stripslashes($row['Drivetrain']) . $this->cD2 . "</Drivetrain>\r\n";
				$this->retXml .= " <Fuel_Type>" . $this->cD1 . stripslashes($row['Fuel_Type']) . $this->cD2 . "</Fuel_Type>\r\n";
				$this->retXml .= " <CityMPG>" . $this->cD1 . stripslashes($row['CityMPG']) . $this->cD2 . "</CityMPG>\r\n";
				$this->retXml .= " <HighwayMPG>" . $this->cD1 . stripslashes($row['HighwayMPG']) . $this->cD2 . "</HighwayMPG>\r\n";
				$this->retXml .= " <EPAClassification>" . $this->cD1 . stripslashes($row['EPAClassification']) . $this->cD2 . "</EPAClassification>\r\n";
				$this->retXml .= " <Wheelbase_Code>" . $this->cD1 . stripslashes($row['Wheelbase_Code']) . $this->cD2 . "</Wheelbase_Code>\r\n";
				$this->retXml .= " <Internet_Price>" . $this->cD1 . stripslashes($row['Internet_Price']) . $this->cD2 . "</Internet_Price>\r\n";
				$this->retXml .= " <Misc_Price1>" . $this->cD1 . stripslashes($row['Misc_Price1']) . $this->cD2 . "</Misc_Price1>\r\n";
				$this->retXml .= " <Misc_Price2>" . $this->cD1 . stripslashes($row['Misc_Price2']) . $this->cD2 . "</Misc_Price2>\r\n";
				$this->retXml .= " <Misc_Price3>" . $this->cD1 . stripslashes($row['Misc_Price3']) . $this->cD2 . "</Misc_Price3>\r\n";
				$this->retXml .= " <Factory_Codes>" . $this->cD1 . stripslashes($row['Factory_Codes']) . $this->cD2 . "</Factory_Codes>\r\n";
				$this->retXml .= " <MarketClass>" . $this->cD1 . stripslashes($row['MarketClass']) . $this->cD2 . "</MarketClass>\r\n";
				$this->retXml .= " <PassengerCapacity>" . $this->cD1 . stripslashes($row['PassengerCapacity']) . $this->cD2 . "</PassengerCapacity>\r\n";
				$this->retXml .= " <ExtColorHexCode>" . $this->cD1 . stripslashes($row['ExtColorHexCode']) . $this->cD2 . "</ExtColorHexCode>\r\n";
				$this->retXml .= " <IntColorHexCode>" . $this->cD1 . stripslashes($row['IntColorHexCode']) . $this->cD2 . "</IntColorHexCode>\r\n";
				$this->retXml .= " <EngineDisplacementCubicInches>" . $this->cD1 . stripslashes($row['EngineDisplacementCubicInches']) . $this->cD2 . "</EngineDisplacementCubicInches>\r\n";
				$this->retXml .= " <ImageList>" . $this->cD1 . stripslashes($row['ImageList']) . $this->cD2 . "</ImageList>\r\n";
				$this->retXml .= " <VideoURL>" . $this->cD1 . stripslashes($row['VideoURL']) . $this->cD2 . "</VideoURL>\r\n";
				$this->retXml .= " <InternetSpecial>" . $this->cD1 . stripslashes($row['InternetSpecial']) . $this->cD2 . "</InternetSpecial>\r\n";
				$this->retXml .= " <ModelSeries>" . $this->cD1 . stripslashes($row['ModelSeries']) . $this->cD2 . "</ModelSeries>\r\n";
				$this->retXml .= " <inventory_type>" . $this->cD1 . stripslashes($row['inventory_type']) . $this->cD2 . "</inventory_type>\r\n";
				$this->retXml .= "	<store_id>" .$this->cD1 . stripslashes($row['store_id']) . $this->cD2 . "</store_id>\r\n";
				$this->retXml .= "	<storename_ws>" .$this->cD1 . stripslashes($row['storename_ws']) . $this->cD2 . "</storename_ws>\r\n";
				$this->retXml .= "	<store_name>" . stripslashes($storesArr[$vStoreLetter2]['storename']) . "</store_name>\r\n";
				$this->retXml .= "	<store_address>" . stripslashes($storesArr[$vStoreLetter2]['address1']) . "</store_address>\r\n";
				$this->retXml .= "	<store_city>" . stripslashes($storesArr[$vStoreLetter2]['city']) . "</store_city>\r\n";
				$this->retXml .= "	<store_state>" . stripslashes($storesArr[$vStoreLetter2]['state']) . "</store_state>\r\n";
				$this->retXml .= "	<store_zip>" . stripslashes($storesArr[$vStoreLetter2]['zip']) . "</store_zip>\r\n";
				$this->retXml .= "	<store_sales_phone>" . stripslashes($storesArr[$vStoreLetter2]['ddc_sales_tracking_phone']) . "</store_sales_phone>\r\n";			
				$this->retXml .= "	<store_service_phone>" . stripslashes($storesArr[$vStoreLetter2]['servicephone']) . "</store_service_phone>\r\n";			
				$this->retXml .= "</qinventory>\r\n";
			}
		}
		$this->retXml .= "</qinventories>\r\n";
		$this->retXml .= "</qinv>\r\n";
	}

	function sync_wp_quirk_inventory()
	{
		if( isset($this->apiDataArr['wp_sp_ids']) && isset($this->apiDataArr['wp_sp_domain']) ) 
		{
			$spArr = array();
			$spArr = explode("|",$this->apiDataArr['wp_sp_ids']);
			if( count($spArr) == 0 )
			{
				$this->errorsArr[] = "Error [no data to process]";
			}
			else
			{
				$this->retXml = "";
				$this->retXml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
				$this->retXml .= "<qinv version=\"1.0\">\r\n";
				$this->retXml .= "	<sync>\r\n";
				foreach($spArr AS $spLine)
				{
					$innerArr = array();
					$innerArr = explode("=",$spLine);
					$postId = $innerArr[0];			
					$qinventoryId = $innerArr[1];
					$this->apiSqlQuery = "SELECT * FROM ".$this->syncTblName." 
										  WHERE quirk_inventory_id='".addslashes($qinventoryId)."' 
										  AND domain_name='".addslashes($this->apiDataArr['wp_sp_domain'])."' 
										  LIMIT 1";
					$result = $this->_run_query($this->apiSqlQuery);
					//$tot = mysql_num_rows( $result );
					$tot = mysqli_num_rows( $result );
					if( $tot == 0 )
					{
						$this->apiSqlQuery = "INSERT INTO ".$this->syncTblName." (id, quirk_inventory_id, post_id, domain_name) VALUES 
												('', '".addslashes($qinventoryId)."', '".addslashes($postId)."', 
												'".addslashes($this->apiDataArr['wp_sp_domain'])."')";
						$result1 = $this->_run_query($this->apiSqlQuery);
						// $this->retXml .= "	<message>Added Special [post=".$postId."] [special=".$specialId."] [".$this->apiDataArr['wp_sp_domain']."]</error>\r\n";
					}
					else
					{
						//$row = mysql_fetch_array($result);
						$row = mysqli_fetch_array($result);
						if( ( $tot == 1 ) && ( $postId == stripslashes($row['post_id']) ) )
						{
							/* no need to update anything */
						}
						else
						{
							$this->apiSqlQuery = "UPDATE ".$this->syncTblName." 
												  SET post_id='".addslashes($postId)."'
												  WHERE quirk_inventory_id='".addslashes($qinventoryId)."' 
												  AND domain_name='".addslashes($this->apiDataArr['wp_sp_domain'])."' 
												  LIMIT 1";
							$result2 = $this->_run_query($this->apiSqlQuery);
							// $this->retXml .= "	<message>Updated Special [post=".$postId."] [special=".$specialId."] [".$this->apiDataArr['wp_sp_domain']."]</error>\r\n";
						}
					}
				}
				$this->retXml .= "	<message>Success!</message>\r\n";
				$this->retXml .= "	</sync>\r\n";
				$this->retXml .= "	</qinv>\r\n";
			}
		}
		else
		{
			if( !isset($this->apiDataArr['wp_sp_ids']) )
			{
				$this->errorsArr[] = "Error [no data to process]";
			}

			if ( !isset($this->apiDataArr['wp_sp_domain']) ) 
			{
				$this->errorsArr[] = "Error [no domain]";
			}
		}
	}

	function getApiErrorReponse()
	{		
		$this->retErrorXml = "";
		$this->retErrorXml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
		$this->retErrorXml .= "<qinv version=\"1.0\">\r\n";
		$this->retErrorXml .= "	<errors total=\"" . count($this->errorsArr) . "\">\r\n";
		if( $this->errorsArr > 0 ) 
		{
			foreach($this->errorsArr AS $errorMessage ) 
			{
				$this->retErrorXml .= "	<error>".$errorMessage."</error>\r\n";
			}
		}
		$this->retErrorXml .= "	</errors>\r\n";
		$this->retErrorXml .= "	</qinv>\r\n";
	}

}
?>