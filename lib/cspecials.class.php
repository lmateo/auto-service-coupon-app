<?php
/**
 * Quirk Coupon Specials Class
 *
 * @package Wojo Framework
 * @author Lorenzo Mateo
 * @copyright 2017
 * @version $Id: cspecials.class.php, v1.00 2017-08-25 9:12:05 gewa Exp $
 */



  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  class cSpecials
  {

  	
  	const csTable = "coupon_specials";
  	const cpschTable = "couponspecials_changes";
  	const csCssTable = "coupon_specials_css";
  	const acTable = "activity";
  	const gTable = "gallery";
  	const xTable = "cart";

      private static $db;
      
      

      /**
       * cSpecials::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          self::$db = Db::run();
          
          

      }

       /**
       * cSpecials::getCouponspecials()
       *
       * @param string $from
       * @param bool $page
       * @param bool $status
       * @return
       */
      public function getCouponspecials($from = false, $page, $status = true)
      {
      	$active = $status ? 'AND cs.active = 1' : 'AND cs.active = 0';
      	 
      	 
      	if (isset($_GET['letter']) and (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '')) {
      		$enddate = date("m/d/Y");
      		$letter = Validator::sanitize($_GET['letter'], 'default', 2);
      		$fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
      		if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
      			$enddate = Validator::sanitize($_POST['enddate_submit']);
      		}
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::csTable . "` AS cs  WHERE created BETWEEN '" . $fromdate . "' AND '" . $enddate . " 23:59:59' AND coupon_title REGEXP '^" . $letter . "' $active");
      		$where = "WHERE cs.created BETWEEN '" . $fromdate . "' AND '" . $enddate . " 23:59:59' AND cs.coupon_title REGEXP '^" . $letter . "' $active";
      
      	} elseif (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
      		$enddate = date("m/d/Y");
      		$fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
      		if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
      			$enddate = Validator::sanitize($_POST['enddate_submit']);
      		}
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::csTable . "` AS cs WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . $enddate . " 23:59:59' $active");
      		$where = "WHERE cs.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' $active";
      
      	} elseif (isset($_GET['letter'])) {
              $letter = Validator::sanitize($_GET['letter'], 'default', 2);
              $where = "WHERE cs.coupon_title REGEXP '^" . $letter . "' $active";
              $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::csTable . "` AS cs WHERE coupon_title REGEXP '^" . $letter . "' $active LIMIT 1");
        
      	}  elseif (isset($_GET['id'])) {
          	$store_id = Validator::sanitize($_GET['id']);
          	$where = "WHERE cs.store_id = '" . $store_id . "' $active";
          	$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::csTable . "` AS cs WHERE store_id = '" . $store_id . "' $active LIMIT 1");
      	}  
      	
      	elseif (isset($_GET['coupon_type'])) {
      		$coupon_type = Validator::sanitize($_GET['coupon_type']);
      		$where = "WHERE cs.coupon_type = '" . $coupon_type . "' $active";
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::csTable . "` AS cs WHERE coupon_type = '" . $coupon_type . "' $active LIMIT 1"); 		 
      	}
      	    elseif(isset($_POST['id']) and (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '')) {
      		$enddate = date("m/d/Y");
      		$id = Validator::sanitize($_POST['id']);
      		$fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
      		if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
      			$enddate = Validator::sanitize($_POST['enddate_submit']);
      		}
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::csTable . "` AS cs  WHERE created BETWEEN '" . $fromdate . "' AND '" . $enddate . " 23:59:59' AND coupon_title REGEXP '^" . $id . "' $active");
      		$where = "WHERE cs.created BETWEEN '" . $fromdate . "' AND '" . $enddate . " 23:59:59' AND cs.coupon_title REGEXP '^" . $id . "' $active";
      
      	} else {
      		$active = $status ? 'WHERE cs.active = 1' : 'WHERE cs.active = 0 AND ';
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::csTable . "` AS cs $active LIMIT 1");
      		$where = $active;
      	}
      
      	if (isset($_GET['order'])) {
      		list($sort, $order) = explode("/", $_GET['order']);
      		$sort = Validator::sanitize($sort, "default", 10);
      		$order = Validator::sanitize($order, "default", 4);
      		if (in_array($sort, array(
      				"coupon_title",
      				"coupon_type",
      				"year"))) {
      				$ord = ($order == 'DESC') ? " DESC" : " ASC";
      				$sorting = $sort . $ord;
      		} else {
      			$sorting = "cs.coupon_title DESC, cs.coupon_type DESC, cs.year DESC, cs.id DESC";
      		}
      	} else {
      		$sorting = "cs.coupon_title DESC, cs.coupon_type DESC, cs.year DESC, cs.id DESC";
      	}
      
      	$pager = Paginator::instance();
      	$pager->items_total = $counter;
      	$pager->default_ipp = App::get("Core")->perpage;
      	$pager->paginate();
      
      	$sql = "
		  SELECT
			cs.*,
      		cs.id as csid,
      		cs.modified as csmodified,
      		s.*,
      		cpcss.*,
          	CONCAT(u.fname, ' ',u.lname) AS username
      
          FROM
			`" . self::csTable . "` AS cs
			LEFT JOIN `" . Users::aTable . "` AS u
      				ON u.id = cs.modified_id
      		LEFT JOIN 
					" . Content::sTable . " s
					ON cs.store_id = s.id 
			LEFT JOIN 
					 " .  self::csCssTable . " cpcss
					 ON cpcss.coupon_store_id = cs.store_id
      			$where
      			AND
      			cs.specials_type IN ('COUPONS')
      
      			ORDER BY $sorting{$pager->limit};";
      			$row = self::$db->pdoQuery($sql)->results();
      
      			return ($row) ? $row : 0;
      			/* */
      }

      
      /**
       * cSpecials::getCouponspecialsPreview()
       *
       * @return
       */
      public function getCouponspecialsPreview()
      {
      	$where = "WHERE cs.id = " . Filter::$id;
      	$sql = "
		  SELECT
			cs.*,
          	s.*,
			cs.id AS id
      
		  FROM
			`" . self::csTable . "` AS cs
			LEFT JOIN " . Content::sTable . " s
					ON cs.store_id = s.id 
		  $where;";
      
      	$row = self::$db->pdoQuery($sql)->result();
      	return ($row) ? $row : 0;
      }
      
      
      /**
       * cSpecials::getCouponspecialsDate()
       *
       * @return
       */
      public  static function getCouponspecialsDate()
      {
      	 
      	$currentdate = date("m-d-Y");
      	$where = "WHERE DATE(modified) = CURDATE()";
      	$sql = "
		  SELECT
			cs.*,
          	s.*,
      		cs.update_flag AS id
     
		  FROM
			`" . self::csTable . "` AS cs
			LEFT JOIN 
					" . Content::sTable . " s
					ON cs.store_id = s.id 
      			$where
      			AND
      			cs.update_flag = 1
      			AND
      			cs.active = 1
      			AND
      			cs.specials_type IN ('COUPONS')
      			";
      
      			$row = self::$db->pdoQuery($sql)->results();
      			return ($row) ? $row : 0;
      }
      
    
      
      
      /**
       * cSpecials::getCSBYID()
       *
       * @return
       */
      public function getCSBYID()
      {
      
      	$where = "WHERE cs.id = " . Filter::$id;
      	$sql = "
		  SELECT
			cs.*,
      		cs.id as csid,
      		s.*,
      		cpcss.*
		  FROM
             `" . self::csTable . "` AS cs
          LEFT JOIN
               " . Content::sTable . " s
                	ON cs.store_id = s.id
          LEFT JOIN
                " .  self::csCssTable . " cpcss
                	ON cpcss.coupon_store_id = cs.store_id
                	$where
                	AND
                	cs.specials_type IN ('COUPONS')";
                	
                	$row = self::$db->pdoQuery($sql)->result();
                	
                	return ($row) ? $row : 0;
      }
      
      /**
       * cSpecials::getCSBYSTORE()
       *
       * @return
       */
      public function getCSBYSTORE($storeid)
      {
      
      	$where = "WHERE cs.store_id = $storeid";
      	$sql = "
		  SELECT
			 cs.*,
      		 lc.logo
		  
      			FROM
          	`" . self::csTable . "` AS cs
          	LEFT JOIN `" . Content::lcTable . "` AS lc
                	ON lc.store_id = cs.store_id
                	$where";
      
                	$row = self::$db->pdoQuery($sql)->results();
                	return ($row) ? $row : 0;
      }

     /**
       * cSpecials::processCouponspecials()
       *
       * @return
       */
      public function processCouponspecials()
      {
      
      	$validate = Validator::instance();
      	$validate->addSource($_POST);
      	
      	
      	$validate->addRule('year','numeric',  true, 1, 4, Lang::$word->WSP_YEAR);
      	$validate->addRule('coupon_title', 'string', true, 1, 50, Lang::$word->WSP_CT);
      	$validate->addRule('coupon_type','string', false);
      	$validate->addRule('expire', 'string', false);
      	$validate->addRule('expire_submit', 'string', false);
      	$validate->addRule('coupon_price_amount','numeric', false);
      	$validate->addRule('coupon_cents','numeric', false);
      	$validate->addRule('ordering','numeric', false);
      	$validate->addRule('coupon_image','string', false);
      	
      	 
      	$validate->run();
      
      	if (empty(Message::$msgs)) {
      		$csrow = self::$db->select(self::csTable, "null", array('id' => Filter::$id))->result();
      		$cdata = array(
      				
      				'year' => $validate->safe->year,
      				'coupon_title' => $validate->safe->coupon_title,
      				'coupon_type' => $validate->safe->coupon_type,
      				'coupon_expiration_date' => Db::toDate($validate->safe->expire). ' 23:59:59',
      				'coupon_price_show_hide' => isset($_POST['coupon_price_show_hide']) ? $_POST['coupon_price_show_hide'] : 0,
      				'coupon_price_amount' => $validate->safe->coupon_price_amount,
      				'coupon_cents' => $validate->safe->coupon_cents,
      				'coupon_image_show_hide' => isset($_POST['coupon_image_show_hide']) ? $_POST['coupon_image_show_hide'] : 0,
      				'coupon_image' => $validate->safe->coupon_image,
      				'coupon_info_area' => Utility::clean_specialchar($_POST['coupon_info_area']),
      				'disclaimer_text' => Utility::clean_specialchar($_POST['disclaimer_text']),
      				'ordering' => $validate->safe->ordering,
      				'active' => isset($_POST['active']) ? $_POST['active'] : 0
      		);
      		
      		
      
      		 
      		if (!Filter::$id) {
      			 
      			$cdata['specials_type'] = 'COUPONS';
      			$cdata['coupon_expiration_date'] = empty($validate->safe->expire_submit) ? Db::toDate() : $validate->safe->expire_submit;
      			$cdata['store_id'] = strtoupper(self::$db->getValueById(Content::lcTable, "store_id", $_POST['location']));
      			$cdata['store_letter'] = strtoupper(self::$db->getValueById(Content::lcTable, "letter", $_POST['location']));
      			$cdata['storename_ws'] = strtoupper(self::$db->getValueById(Content::lcTable, "name", $_POST['location']));
      			$cdata['created'] = Db::toDate();
      			$cdata['created_id'] = App::get('Auth')->uid;
      			
      			
      		} else {
      			 
      			$cdata['store_id'] = $csrow->store_id;
      			$cdata['specials_type'] = $csrow->specials_type;
      			$cdata['store_letter'] = strtoupper($csrow->store_letter);
      			$cdata['storename_ws'] = strtoupper($csrow->storename_ws);
      			$cdata['coupon_expiration_date'] = $validate->safe->expire_submit;
      			$cdata['update_flag'] = 1;
      			$cdata['modified'] = Db::toDate();
      			$cdata['modified_id'] = App::get('Auth')->uid;
      			
      			
      
      		}
      
      
      		(Filter::$id) ? self::$db->update(self::csTable, $cdata, array('id' => Filter::$id)) : self::$db->insert(self::csTable, $cdata);
      		
      		$storeidcs = strtoupper($cdata['store_id']);
      		$dealershipAdminUrl = Url::adminUrl("couponspecials","dealership", false, "?id=$storeidcs");
      		$storenamecs = strtoupper($cdata['storename_ws']);
      		$couponspecialslinkUpdate = "<br/> <a href= $dealershipAdminUrl>BACK TO $storenamecs</a>";
      		$couponspecialslinkADDED = "<br/> <a href= $dealershipAdminUrl>GO TO $storenamecs</a>";
      		$message = (Filter::$id) ? Lang::$word->WSP_UPDATED .$couponspecialslinkUpdate : Lang::$word->WSP_ADDED .$couponspecialslinkADDED;
      		Message::msgReply(self::$db->affected(), 'success', $message);
      			
      
      	} else {
      		Message::msgSingleStatus();
      	}
      
      }
      
       /**
       * cSpecials::processCouponspecialsDubs()
       *
       * @return
       */
      public function processCouponspecialsDubs()
      {
      
      	$cbsp = self::$db->select(self::csTable, "null", array('id' => $_POST['id']))->result();
      
      	if (empty(Message::$msgs)) {
      		
      		$csid = $cbsp->id;
      		$html = '';
      		$cdupdata = array(
      				
      				'store_id'=> $cbsp->store_id,
      				'specials_type' => $cbsp->specials_type,
      				'store_letter' => $cbsp->store_letter,
      				'year' => $cbsp->year,
      				'storename_ws' => $cbsp->storename_ws,
      				'year' => $cbsp->year,
      				'coupon_title' => $cbsp->coupon_title,
      				'coupon_type' => $cbsp->coupon_type,
      				'coupon_expiration_date' => $cbsp->coupon_expiration_date,
      				'coupon_price_show_hide' => $cbsp->coupon_price_show_hide,
      				'coupon_price_amount' => $cbsp->coupon_price_amount,
      				'coupon_cents' => $cbsp->coupon_cents,
      				'coupon_image_show_hide' => $cbsp->coupon_image_show_hide,
      				'coupon_image' => $cbsp->coupon_image,
      				'coupon_info_area' => $cbsp->coupon_info_area,
      				'disclaimer_text' => $cbsp->disclaimer_text,
      				'active' => $cbsp->active
      				
      		);
      		 
      		
      		$cdupdata['created'] = Db::toDate();
      		$cdupdata['created_id'] = App::get('Auth')->uid;
      		
      		$nicetitle = $cdupdata['coupon_title'];
      		
      		$json = array(
      				'type' => 'success',
      				'title' => Lang::$word->SUCCESS,
      				'data' => $html,
      				'message' => "$nicetitle Coupon Special Dublicated Successfully!!"
      		);
      		 
      		 
      		print json_encode($json);
      		
      		self::$db->insert(self::csTable, $cdupdata);
      	
      	} else {
      		$json['type'] = 'error';
      		$json['title'] = Lang::$word->ERROR;
      		$json['message'] = $err;
      		print json_encode($json);
      		/*Message::msgSingleStatus();*/
      	}
      
      }
      
            
      /**
       * cSpecials::processCouponspecialsUpdate_autosave()
       *
       * @return
       */
      public function processCouponspecialsUpdate_autosave()
      {
      	$validate = Validator::instance();
      	$validate->addSource($_POST);
      	
      	$validate->addRule('year','numeric',  true, 1, 4, Lang::$word->WSP_YEAR);
      	$validate->addRule('coupon_title', 'string', true, 1, 50, Lang::$word->WSP_CT);
      	$validate->addRule('coupon_type','string', false);
      	$validate->addRule('expire', 'string', false);
      	$validate->addRule('expire_submit', 'string', false);
      	$validate->addRule('coupon_price_amount','numeric', false);
      	$validate->addRule('coupon_cents','numeric', false);
      	$validate->addRule('ordering','numeric', false);
      	$validate->addRule('coupon_image','string', false);
      	 
      	
      	$validate->run();
      	
      	if (empty(Message::$msgs)) {
      		$csrow = self::$db->select(self::csTable, "null", array('id' => Filter::$id))->result();
      		$cdata = array(
      	
      				'year' => $validate->safe->year,
      				'coupon_title' => $validate->safe->coupon_title,
      				'coupon_type' => $validate->safe->coupon_type,
      				'coupon_expiration_date' => Db::toDate($validate->safe->expire). ' 23:59:59',
      				'coupon_price_show_hide' => isset($_POST['coupon_price_show_hide']) ? $_POST['coupon_price_show_hide'] : 0,
      				'coupon_price_amount' => $validate->safe->coupon_price_amount,
      				'coupon_cents' => $validate->safe->coupon_cents,
      				'coupon_image_show_hide' => isset($_POST['coupon_image_show_hide']) ? $_POST['coupon_image_show_hide'] : 0,
      				'coupon_image' => $validate->safe->coupon_image,
      				'coupon_info_area' => Utility::clean_specialchar($_POST['coupon_info_area']),
      				'disclaimer_text' => Utility::clean_specialchar($_POST['disclaimer_text']),
      				'ordering' => $validate->safe->ordering,
      				'active' => isset($_POST['active']) ? $_POST['active'] : 0
      		);
      	
      	
      	
      		 
      		if (!Filter::$id) {
      	
      			$cdata['specials_type'] = 'COUPONS';
      			$cdata['coupon_expiration_date'] = empty($validate->safe->expire_submit) ? Db::toDate() : $validate->safe->expire_submit;
      			$cdata['store_id'] = strtoupper(self::$db->getValueById(Content::lcTable, "store_id", $_POST['location']));
      			$cdata['store_letter'] = strtoupper(self::$db->getValueById(Content::lcTable, "letter", $_POST['location']));
      			$cdata['storename_ws'] = strtoupper(self::$db->getValueById(Content::lcTable, "name", $_POST['location']));
      			$cdata['created'] = Db::toDate();
      			$cdata['created_id'] = App::get('Auth')->uid;
      			 
      			 
      		} else {
      	
      			$cdata['store_id'] = $csrow->store_id;
      			$cdata['specials_type'] = $csrow->specials_type;
      			$cdata['store_letter'] = strtoupper($csrow->store_letter);
      			$cdata['storename_ws'] = strtoupper($csrow->storename_ws);
      			$cdata['coupon_expiration_date'] = $validate->safe->expire_submit;
      			$cdata['update_flag'] = 1;
      			$cdata['modified'] = Db::toDate();
      			$cdata['modified_id'] = App::get('Auth')->uid;
      			 
      			 
      	
      		}
      	
      
      
      		(Filter::$id) ? self::$db->update(self::csTable, $cdata, array('id' => Filter::$id)) : self::$db->insert(self::csTable, $cdata);
      		$autosaveText = "Coupon Special is Auto Saving.....";
      		$message = $autosaveText;
      		Message::msgReply(self::$db->affected(), 'success', $message);
      		
      		 
      
      	} else {
      		Message::msgSingleStatus();
      	}
      
      }
      
      /**
       * cSpecials::getCouponSpecialsAlert(()
       *
       * @return
       */
      
      public function getCouponSpecialsAlert()
      {
      
      	 
      	$where = "WHERE couponspecials_id = " . Filter::$id;
      	$sql = "
		  SELECT
      			cpsch.*,
       		    CASE WHEN cpsch.col ='Coupon Status' AND cpsch.OldValue = 1
				THEN 'Active'
				WHEN cpsch.col ='Coupon Status' AND cpsch.OldValue = 0
				THEN 'Not Active'
				ELSE cpsch.OldValue
				END AS changeFrom ,
				CASE
				WHEN cpsch.col ='Coupon Status' AND cpsch.NewValue = 1
				THEN 'Active'
				WHEN cpsch.col ='Coupon Status' AND cpsch.NewValue = 0
				THEN 'Not Active'
				ELSE cpsch.NewValue
				END AS changeTo,
      			CONCAT( u.fname, ' ',u.lname) AS username
      			FROM `" . self::cpschTable . "` AS cpsch
      			LEFT JOIN `" . Users::aTable . "` AS u
            			ON u.id = cpsch.modified_id
            			$where
            			ORDER BY cpsch.id DESC
            			";
            			$row = self::$db->pdoQuery($sql)->results();
      
            			return ($row) ? $row : 0;
      }
      
      /**
       * cSpecials::processMultiServiceCouponAdded()
       *
       * @return
       */
      public function processMultiServiceCouponAdded()
      {
      	  
      		if (empty(Message::$msgs)) {
      			
      			/*$id = $_POST['id'];
      			$couponDisplay = isset($_POST['coupon_price_show_hide']) ? $_POST['coupon_price_show_hide'] : 0;
      			$couponImageShowHide = isset($_POST['coupon_image_show_hide']) ? $_POST['coupon_image_show_hide'] : 0;
      			$active = isset($_POST['active']) ? $_POST['active'] : 0;*/
      			$couponInfoArea = isset($_POST['coupon_info_area_update']) ? $_POST['coupon_info_area_update']: '';
      			$disclaimer = isset($_POST['disclaimer_update']) ? $_POST['disclaimer_update']: '';
    
      			foreach ($_POST['coupon_title_update'] as $key => $val) {
      				$cudata = array('coupon_title' => Validator::sanitize($_POST['coupon_title_update'][$key])
      						,'year' => Validator::sanitize($_POST['year_update'][$key])
      						,'store_letter' => Validator::sanitize($_POST['storeletter'][$key])
      						,'specials_type' => Validator::sanitize($_POST['specialstype'][$key])
      						,'store_id' => Validator::sanitize($_POST['storeid'][$key])
      						,'storename_ws' => Validator::sanitize($_POST['storename'][$key])
      					    ,'ordering' => Validator::sanitize($_POST['ordering_update'][$key])
      						,'active' => Validator::sanitize($_POST['active_update'][$key])
      						, 'coupon_type' => Validator::sanitize($_POST['coupon_type_update'][$key])
      						, 'coupon_expiration_date' => $_POST['coupon_expiration_date_update'][$key]
      						, 'coupon_price_show_hide' => Validator::sanitize($_POST['coupon_price_show_hide_update'][$key])
      						, 'coupon_price_amount' => Validator::sanitize($_POST['couponPriceDollarAmountStatus_update'][$key])
      						, 'coupon_cents' => Validator::sanitize($_POST['couponPriceCentsAmount_update'][$key])
      						, 'coupon_image_show_hide' => Validator::sanitize($_POST['coupon_image_show_hide_update'][$key])
      						, 'coupon_image' => Validator::sanitize($_POST['coupon_image_update'][$key])
      						, 'coupon_info_area' => Utility::clean_specialchar($couponInfoArea[$key])
      						, 'disclaimer_text' => Utility::clean_specialchar($disclaimer[$key])
      						, 'created' =>Db::toDate()
      						, 'created_id' => App::get('Auth')->uid);
      				/*print_r($cudata);
      				var_dump($id[$key]);*/
      			   self::$db->insert(self::csTable, $cudata);
      				
      			}
      
      			$message = Lang::$word->WSP_ADDED;
      			Message::msgReply(self::$db->affected(), 'success', $message);
      			
      			
      
      		} else {
      			Message::msgSingleStatus();
      		}
      		 
      }
      
      /**
       * cSpecials::processMultiServiceCouponUpdate()
       *
       * @return
       */
      public function processMultiServiceCouponUpdate()
      {
      	 
      	if (empty(Message::$msgs)) {
      		 
      		$id = $_POST['id'];
      		/*$couponDisplay = isset($_POST['coupon_price_show_hide']) ? $_POST['coupon_price_show_hide'] : 0;
      		 $couponImageShowHide = isset($_POST['coupon_image_show_hide']) ? $_POST['coupon_image_show_hide'] : 0;
      		 $active = isset($_POST['active']) ? $_POST['active'] : 0;*/
      		$couponInfoArea = isset($_POST['coupon_info_area_update']) ? $_POST['coupon_info_area_update']: '';
      		$disclaimer = isset($_POST['disclaimer_update']) ? $_POST['disclaimer_update']: '';
      
      		foreach ($_POST['coupon_title_update'] as $key => $val) {
      			$cudata = array('coupon_title' => Validator::sanitize($_POST['coupon_title_update'][$key])
      					,'year' => Validator::sanitize($_POST['year_update'][$key])
      					,'ordering' => Validator::sanitize($_POST['ordering_update'][$key])
      					,'active' => Validator::sanitize($_POST['active_update'][$key])
      					, 'coupon_type' => Validator::sanitize($_POST['coupon_type_update'][$key])
      					, 'coupon_expiration_date' => $_POST['coupon_expiration_date_update'][$key]
      					, 'coupon_price_show_hide' => Validator::sanitize($_POST['coupon_price_show_hide_update'][$key])
      					, 'coupon_price_amount' => Validator::sanitize($_POST['couponPriceDollarAmountStatus_update'][$key])
      					, 'coupon_cents' => Validator::sanitize($_POST['couponPriceCentsAmount_update'][$key])
      					, 'coupon_image_show_hide' => Validator::sanitize($_POST['coupon_image_show_hide_update'][$key])
      					, 'coupon_image' => Validator::sanitize($_POST['coupon_image_update'][$key])
      					, 'coupon_info_area' => Utility::clean_specialchar($couponInfoArea[$key])
      					, 'disclaimer_text' => Utility::clean_specialchar($disclaimer[$key])
      					, 'update_flag' => 1
      					, 'modified' =>Db::toDate()
      					, 'modified_id' => App::get('Auth')->uid);
      			/*print_r($cudata);
      			 var_dump($id[$key]);*/
      			self::$db->update(self::csTable, $cudata, array('id' => $id[$key]));
      
      		}
      
      		$message = Lang::$word->WSP_UPDATED;
      		Message::msgReply(self::$db->affected(), 'success', $message);
      		 
      		 
      
      	} else {
      		Message::msgSingleStatus();
      	}
      	 
      }
      
      /**
       * cSpecials::processPriceDiscounts()
       *
       * @return
       */
      public function processPriceDiscounts()
      {
      	
      	$name = array_filter($_POST['name'], 'strlen');
      		if (empty($name))
      			$err = Message::$msgs['answer'] = "Please enter at least one Pricing Discount.";
      	
      	if (empty(Message::$msgs)) {
      		$active = isset($_POST['active1']) ? $_POST['active1'] : 0;
      		$htmlPD = '';
      		foreach ($_POST['name'] as $key => $val) {
      					$data = array('name' => Validator::sanitize($_POST['name'][$key])
      							,'price' => Validator::sanitize($_POST['price'][$key])
      							,'ordering' => Validator::sanitize($_POST['ordering1'][$key])
      							,'active' => $active[$key]
      							, 'special_id' => Filter::$id
      							, 'stock_id_ws' => Validator::sanitize($_POST['stock_id'])
      							, 'store_letter_ws' => Validator::sanitize($_POST['store_letter'])
      							, 'created' =>Db::toDate()
      							, 'created_id' => App::get('Auth')->uid);
      					$last_id = self::$db->insert(self::wspTable, $data)->getLastInsertId();
      	                $activeIcon =  $data['active']   ? 'check positive' : 'ban purple';
      	                $npd = self::$db->select(self::wspTable, "null", array('id' => $last_id))->result();
      		            $htmlPD .= '
					    <tr>
      		            <tr data-id=" ' . $last_id . '">
					    <td class="sorter"><i class="icon reorder"></i></td>
					    <td><small> ' . $last_id . '.</small></td>		
      		            <td data-editablews="true" data-set=\'{"type": "pricediscount", "id": ' . $last_id . ' ,"key":"name", "path":""}\'>' . $npd->name . '</td>
      		            <td data-editablews="true" data-set=\'{"type": "pricediscount", "id": ' . $last_id . ' ,"key":"price", "path":""}\'>' . $npd->price . '</td>
      		            <td data-editablews="true" data-set=\'{"type": "pricediscount", "id": ' . $last_id . ' ,"key":"ordering", "path":""}\'>' . $npd->ordering . '</td>
                        <td <div class="data"> <a class="doStatus" data-set=\'{"field": "status", "table": "PriceDiscounts", "toggle": "check ban", "togglealt": "positive purple", "id":  ' . $last_id . ', "value": "' .  $npd->active . '"}\' data-content="'.Lang::$word->STATUS.'"><i class="rounded inverted  '.$activeIcon.' icon link"></i></a> </td>
      		            <td><a class="delete" data-set=\'{"title": "Delete Pricing Discount", "parent": "tr", "option": "deletePricingDiscount", "id": ' . $last_id . ', "name": "' .  $npd->name . '"}\'><i class="rounded outline icon negative trash link"></i></a></td> 
						</tr>';
      		            
      		            
      		            
      		            
      				}
      				
      				$json = array(
      						'type' => 'success',
      						'title' => Lang::$word->SUCCESS,
      						'data' => $htmlPD,
      						'message' => "Pricing Discounts ADDED!"
      						
      				);
      				print json_encode($json);
      				
      				} else {
      					$json['type'] = 'error';
      					$json['title'] = Lang::$word->ERROR;
      					$json['message'] = $err;
      					print json_encode($json);
      				}
      			
      	}
      	
      	/**
      	 * cSpecials::coupon_widget_parse_csv()
      	 *
      	 * @return
      	 */
      	 
      	public static function coupon_widget_parse_csv() {
      		$coupon_widget_csv_file = BASEPATH.'csv/coupon_widget_store.csv';
      		 
      		$coupon_widget_csv_data = Utility::csv_to_array($coupon_widget_csv_file);
      		$coupon_widget_data_count = count($coupon_widget_csv_data);
      	
      		//print_r($coupon_widget_csv_data);
      		 
      		if ($coupon_widget_csv_data){
      			foreach($coupon_widget_csv_data as $coupon_widget_data){
      				 
      				$datacpcsv = array(
      						'coupon_widget_id' => Validator::sanitize($coupon_widget_data['coupon_widget']),
      						'modified' => Validator::sanitize(Utility::today()),
      						'modified_id' => App::get('Auth')->uid
      				);
      	
      	
      	
      				self::$db->update(Content::sTable, $datacpcsv, array('store_id_loc' => $coupon_widget_data['store_id_loc']));
      				 
      				$tbletot = self::$db->count(Content::sTable, "car_dealership = 1");
      				$tablename = Content::sTable;
      	
      			}
      			echo 'You have just Updated '.$coupon_widget_data_count. ' Coupon Widget records to database table '.$tablename;
      			 
      		}
      		else {
      			 
      			echo 'Error Updating records to database table '.$tablename;
      		}
      		 
      	}
      	
      	
      /**
       * Items::approveListing()
       * 
       * @return
       */
	  public function approveListing()
	  {
		  if ($item = self::$db->select(self::lTable, "null", array('id' => Filter::$id))->result()) {
			  $row = self::$db->select(Users::mTable, array('email','membership_id','CONCAT(fname," ",lname) as name'), array('id' => $item->user_id))->result();
			  $data = array(
					'status' => 1,
					'rejected' => 0,
					'expire' => Users::calculateDays($row->membership_id)
					);
			  self::$db->update(self::lTable, $data, array('id' => Filter::$id));
			  
			  $count = self::$db->count(self::lTable, "user_id = " . $item->user_id . " AND status = 1");
			  self::$db->update(Users::mTable, array("listings" => $count), array("id" => $item->user_id));
			  self::$db->update(self::liTable, array("lstatus" => 1), array("id" => Filter::$id));
			  
			  //Add to core
			  self::doCalc();
			  
			  $numSent = 0;
			  $mailer = Mailer::sendMail();
	
			  if ($row) {
				  ob_start();
				  require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Listing_Approve.tpl.php');
				  $html_message = ob_get_contents();
				  ob_end_clean();
	
				  $newbody = str_replace(array(
					  '[COMPANY]',
					  '[LOGO]',
					  '[FULLNAME]',
					  '[ID]',
					  '[URL]',
					  '[LURL]',
					  '[TITLE]',
					  '[DATE]'), array(
					  App::get("Core")->company,
					  Utility::getLogo(),
					  $row->name,
					  $item->idx,
					  SITEURL,
					  Url::doUrl(URL_ITEM, $item->idx . '/' . $item->slug),
					  $item->title,
					  date('Y')), $html_message);
	
				  $message = Swift_Message::newInstance()
							->setSubject(Lang::$word->WSP_APPROVED . ' ' . App::get("Core")->company)
							->setTo(array($row->email => $row->name))
							->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
							->setBody($newbody, 'text/html');
	
				  $numSent++;
				  $mailer->send($message);
			  }
			  Message::msgReply($numSent, 'success', Lang::$word->EMN_SENT, Lang::$word->EMN_ALERT);
		  } else {
			  Message::msgReply(true, 'error', Lang::$word->SYSTEM_ERR1);
		  }
	  }

      /**
       * Items::rejectListing()
       * 
       * @return
       */
	  public function rejectListing()
	  {
		  if ($item = self::$db->select(self::lTable, "null", array('id' => Filter::$id))->result()) {
			  $row = self::$db->select(Users::mTable, array('email','membership_id','CONCAT(fname," ",lname) as name'), array('id' => $item->user_id))->result();
			  $data = array('rejected' => 1);
			  self::$db->update(self::lTable, $data, array('id' => Filter::$id));
	
			  $numSent = 0;
			  $mailer = Mailer::sendMail();
	
			  if ($row) {
				  ob_start();
				  require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Listing_Reject.tpl.php');
				  $html_message = ob_get_contents();
				  ob_end_clean();
	
				  $newbody = str_replace(array(
					  '[COMPANY]',
					  '[LOGO]',
					  '[FULLNAME]',
					  '[ID]',
					  '[URL]',
					  '[REASON]',
					  '[TITLE]',
					  '[DATE]'), array(
					  App::get("Core")->company,
					  Utility::getLogo(),
					  $row->name,
					  $item->idx,
					  SITEURL,
					  Validator::sanitize($_POST['notes']),
					  $item->title,
					  date('Y')), $html_message);
	
				  $message = Swift_Message::newInstance()
							->setSubject(Lang::$word->WSP_REJECTED . ' ' . App::get("Core")->company)
							->setTo(array($row->email => $row->name))
							->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
							->setBody($newbody, 'text/html');
	
				  $numSent++;
				  $mailer->send($message);
			  }
			  Message::msgReply($numSent, 'success', Lang::$word->EMN_SENT, Lang::$word->EMN_ALERT);
		  } else {
			  Message::msgReply(true, 'error', Lang::$word->SYSTEM_ERR1);
		  }
	  }

      /**
       * Items::doCalc()
       * 
       * @return
       */
      public static function doCalc()
      {
		  $sql = array(
			  "MIN(price) AS minprice",
			  "MAX(price) AS maxprice",
			  "MIN(price_sale) AS minsprice",
			  "MAX(price_sale) AS maxsprice",
			  "MIN(YEAR) AS minyear",
			  "MAX(YEAR) AS maxyear",
			  "MIN(mileage) AS minkm",
			  "MAX(mileage) AS maxkm");
		  $val = self::$db->first(self::lTable, $sql, array("status" => 1));

		  $make = self::$db->select(self::liTable, array("make_name", "COUNT(id) as total"), array("lstatus" => 1), 'GROUP BY make_name')->results('json');
		  $category = self::$db->select(self::liTable, array("category_name", "COUNT(id) as total"), array("lstatus" => 1), 'GROUP BY category_name')->results('json');
		  $condition = self::$db->select(self::liTable, array("condition_name", "COUNT(id) as total"), null, 'GROUP BY condition_name')->results('json');
		  $color = self::$db->select(self::lTable, array("color_e", "COUNT(id) as total"), array("status" => 1), 'GROUP BY color_e')->results('json');
		  $year_list = self::$db->select(self::lTable, array("year", "COUNT(id) as total"), array("status" => 1), 'GROUP BY year')->results('json');
		  
		  $ids = self::$db->select(Items::lTable, array("GROUP_CONCAT(make_id) as mkids, GROUP_CONCAT(model_id) as mdids"))->result();
		  $makes = self::$db->pdoQuery("SELECT id, name FROM `" . Content::mkTable . "` WHERE id IN(" . $ids->mkids.") GROUP BY id")->results('json');
		  $models = self::$db->pdoQuery("SELECT id, name FROM `" . Content::mdTable . "` WHERE id IN(" . $ids->mdids.") GROUP BY id")->results('json');
		  
		  // Add to core
		  $odata = array(
			  'minprice' => $val->minprice,
			  'maxprice' => $val->maxprice,
			  'minsprice' => $val->minsprice,
			  'maxsprice' => $val->maxsprice,
			  'minyear' => $val->minyear,
			  'maxyear' => $val->maxyear,
			  'minkm' => $val->minkm,
			  'maxkm' => $val->maxkm,
			  'color' => $color,
			  'makes' => $make,
			  'year_list' => $year_list,
			  'cond_list' => $condition,
			  'category_list' => $category,
			  'make_list' => $makes,
			  'model_list' => $models,
			  );
		  self::$db->update(Core::sTable, $odata, array('id' => 1));
      }	  
      /**
       * Items::getFeatured()
       * 
       * @return
       */
      public function getFeatured()
      {
		  $row = self::$db->select(self::lTable, "*", array("status" => 1), 'ORDER BY created DESC LIMIT ' . App::get('Core')->featured)->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::getBrands()
       * 
       * @return
       */
      public function getBrands()
      {
		  $sql = "
		  SELECT 
			m.name,
			m.id,
			COUNT(m.id) as items
		  FROM
			`" . Content::mkTable . "` AS m 
			INNER JOIN `" . self::lTable . "` AS l 
			  ON l.make_id = m.id 
		  WHERE l.status = ?
		  GROUP BY m.id
		  ORDER BY items DESC
		  LIMIT 10;";
		  
		  $row = self::$db->pdoQuery($sql, array(1))->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::renderBrands()
       * 
       * @return
       */
      public function renderBrands($no = 5)
      {
		  $sql = "
		  SELECT 
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.thumb,
			l.sold, l.created, x.make_name, x.model_name, x.category_name, x.condition_name, x.trans_name
		  FROM
			(SELECT 
			  li.category_name, li.make_name, li.condition_name,
			  li.model_name, li.trans_name, li.listing_id,
			  CASE
				WHEN li.make_name = @brand 
				THEN @rownum := @rownum + 1 
				ELSE @rownum := 1 
			  END AS rank,
			  @brand := li.make_name 
			FROM
			  `" . self::liTable . "` li 
			  JOIN 
				(SELECT 
				  @rownum := 0,
				  @brand := NULL) r 
			ORDER BY li.make_name, li.listing_id) x
			INNER JOIN `" . self::lTable . "` AS l 
			  ON l.id = x.listing_id 
		  WHERE l.status = ?
		  AND x.rank <= $no
		  ORDER BY l.created DESC;";
		  
		  $row = self::$db->pdoQuery($sql, array(1))->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::getBrand()
       * 
       * @return
       */
      public function getBrand()
      {

		  $row = self::$db->first(Content::mkTable, array("name", "name_slug", "id"), array("name_slug" => App::get('Core')->_url[1]));
          return ($row) ? $row : 0;
      }

      /**
       * Items::renderByBrand()
       * 
       * @return
       */
      public function renderByBrand($make_id)
      {
		  $and = null;
		  $parts = parse_url($_SERVER['REQUEST_URI']);
		  isset($parts['query']) ? parse_str($parts['query'], $qs) : $qs = array();
		  $required = array(
			  "model_id" => 0,
			  "transmission" => 1,
			  "price" => 2,
			  "miles" => 3,
			  "year" => 4
			  );
		  if (array_intersect_key($qs, $required)) { 
              if (Validator::notEmptyGet('model_id') and $model_id = Validator::sanitize($_GET['model_id'], "digits", 11)) {
				  $and .= " AND model_id = {$model_id}";
			  }  
              if (Validator::notEmptyGet('transmission') and $transmission = Validator::sanitize($_GET['transmission'], "digits", 2)) {
				  $and .= " AND transmission = {$transmission}";
			  }   
              if (Validator::notEmptyGet('year') and $year = Validator::sanitize($_GET['year'], "digits", 4)) {
				  $and .= " AND year = {$year}";
			  }     
              if (Validator::notEmptyGet('price') and $price = Validator::sanitize($_GET['price'], "digits", 12)) {
				  switch($price) {
					  case 10 :
					    $and .= " AND price BETWEEN 0 AND 5000";
					  break;
					  case 20 :
					    $and .= " AND price BETWEEN 5000 AND 10000";
					  break;
					  case 30 :
					    $and .= " AND price BETWEEN 10000 AND 20000";
					  break;
					  case 40 :
					    $and .= " AND price BETWEEN 20000 AND 30000";
					  break;
					  case 50 :
					    $and .= " AND price BETWEEN 30000 AND 50000";
					  break;
					  case 60 :
					    $and .= " AND price BETWEEN 50000 AND 75000";
					  break;
					  case 70 :
					    $and .= " AND price BETWEEN 75000 AND 100000";
					  break;
					  case 80 :
					    $and .= " AND price BETWEEN 100000 AND 9999999";
					  break;
				  }
			  } 
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1 AND make_id = {$make_id}{$and}");
		  } else {
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1 AND make_id = {$make_id}");
		  }
		  
          $pager = Paginator::instance();
          $pager->items_total = $total;
          $pager->default_ipp = App::get("Core")->sperpage;
          $pager->paginate();
		  
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.fuel_name,
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.sold, l.thumb, l.created, l.featured, l.body, l.mileage
		  FROM
			`" . self::liTable . "` AS li 
			LEFT JOIN `" . self::lTable . "` AS l 
			  ON l.id = li.listing_id 
		  WHERE l.status = 1
		  AND l.make_id = {$make_id}
		  $and
		  ORDER BY l.featured DESC, l.created DESC
		  {$pager->limit};";
		  
		  $row = self::$db->pdoQuery($sql, array(1, $make_id))->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::getBodyType()
       * 
       * @return
       */
      public function getBodyType()
      {

		  $sql = "
		  SELECT 
			name,
			slug,
			id
		  FROM
			`" . Content::ctTable . "`
			WHERE slug = ? 
		  LIMIT 1;";

		  $row = self::$db->pdoQuery($sql, array(App::get('Core')->_url[1]))->result();
          return ($row) ? $row : 0;
      }

      /**
       * Items::renderByCategory()
       * 
       * @return
       */
      public function renderByCategory($cat_id)
      {
		  $and = null;
		  $parts = parse_url($_SERVER['REQUEST_URI']);
		  isset($parts['query']) ? parse_str($parts['query'], $qs) : $qs = array();
		  $required = array(
			  "make_id" => 0,
			  "model_id" => 1,
			  "transmission" => 2,
			  "price" => 3,
			  "miles" => 4,
			  "year" => 5
			  );
		  if (array_intersect_key($qs, $required)) {
			  if (Validator::notEmptyGet('make_id') and $make_id = Validator::sanitize($_GET['make_id'], "digits", 11)) {
				  $and .= " AND make_id = {$make_id}";
			  }  
              if (Validator::notEmptyGet('model_id') and $model_id = Validator::sanitize($_GET['model_id'], "digits", 11)) {
				  $and .= " AND model_id = {$model_id}";
			  }  
              if (Validator::notEmptyGet('transmission') and $transmission = Validator::sanitize($_GET['transmission'], "digits", 2)) {
				  $and .= " AND transmission = {$transmission}";
			  }   
              if (Validator::notEmptyGet('year') and $year = Validator::sanitize($_GET['year'], "digits", 4)) {
				  $and .= " AND year = {$year}";
			  }     
              if (Validator::notEmptyGet('price') and $price = Validator::sanitize($_GET['price'], "digits", 12)) {
				  switch($price) {
					  case 10 :
					    $and .= " AND price BETWEEN 0 AND 5000";
					  break;
					  case 20 :
					    $and .= " AND price BETWEEN 5000 AND 10000";
					  break;
					  case 30 :
					    $and .= " AND price BETWEEN 10000 AND 20000";
					  break;
					  case 40 :
					    $and .= " AND price BETWEEN 20000 AND 30000";
					  break;
					  case 50 :
					    $and .= " AND price BETWEEN 30000 AND 50000";
					  break;
					  case 60 :
					    $and .= " AND price BETWEEN 50000 AND 75000";
					  break;
					  case 70 :
					    $and .= " AND price BETWEEN 75000 AND 100000";
					  break;
					  case 80 :
					    $and .= " AND price BETWEEN 100000 AND 9999999";
					  break;
				  }
			  } 
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1 AND category = {$cat_id}{$and}");
		  } else {
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1 AND category = {$cat_id}");
		  }
		  
          $pager = Paginator::instance();
          $pager->items_total = $total;
          $pager->default_ipp = App::get("Core")->sperpage;
          $pager->paginate();
		  
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.fuel_name,
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.sold, l.thumb, l.featured, l.created, l.featured
		  FROM
			`" . self::liTable . "` AS li 
			LEFT JOIN `" . self::lTable . "` AS l 
			  ON l.id = li.listing_id 
		  WHERE l.status = 1
		  AND l.category = {$cat_id}
		  $and
		  ORDER BY l.featured DESC, l.created DESC
		  {$pager->limit};";
		  
		  $row = self::$db->pdoQuery($sql)->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::getSeller()
       * 
       * @return
       */
      public function getSeller()
      {
		  
		  $row = self::$db->first(Content::lcTable, null, array("name_slug" => App::get('Core')->_url[1]));
          return ($row) ? $row : 0;
      }

      /**
       * Items::renderBySeller()
       * 
       * @return
       */
      public function renderBySeller($location)
      {
		  
		  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1 AND location = {$location}");
          $pager = Paginator::instance();
          $pager->items_total = $total;
          $pager->default_ipp = App::get("Core")->sperpage;
          $pager->paginate();
		  
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.fuel_name,
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.sold, l.thumb, l.featured, l.created, l.featured
		  FROM
			`" . self::liTable . "` AS li 
			LEFT JOIN `" . self::lTable . "` AS l 
			  ON l.id = li.listing_id 
		  WHERE l.status = ?
		  AND l.location = ?
		  ORDER BY l.featured DESC, l.created DESC
		  {$pager->limit};";
		  
		  $row = self::$db->pdoQuery($sql, array(1, $location))->results();
          return ($row) ? $row : 0;
      }
	  
      /**
       * Items::getFooterBits()
       * 
       * @return
       */
      public function getFooterBits()
      {
		  
		  $row = self::$db->select(self::liTable, array("make_name", "category_name"), null, "ORDER BY make_name")->results();
          return ($row) ? $row : 0;
      }
	  
      /**
       * Items::renderListings()
       * 
       * @return
       */
	  public function renderListings()
	  {
	
		  if (isset($_GET['order'])) {
			  list($sort, $order) = explode("/", $_GET['order']);
			  $sort = Validator::sanitize($sort, "default", 10);
			  $order = Validator::sanitize($order, "default", 4);
			  $array = array(
				  "year",
				  "price",
				  "make",
				  "model",
				  "mileage"
				  );
			  if (in_array($sort, $array)) {
				  $ord = ($order == 'DESC') ? " DESC" : " ASC";
				  switch ($sort) {
					  case "year":
						  $sortorder = "l.year";
						  break;
					  case "price":
						  $sortorder = "l.price";
						  break;
					  case "make":
						  $sortorder = "l.make_id";
						  break;
					  case "model":
						  $sortorder = "l.model_id";
						  break;
					  case "mileage":
						  $sortorder = "l.mileage";
						  break;
					  default:
						  $sortorder = "l.featured DESC, l.created DESC";
						  break;
				  }
				  $sorting = $sortorder . $ord;
			  } else {
				  $sorting = " l.featured DESC, l.created DESC";
			  }
		  } else {
			  $sorting = " l.featured DESC, l.created DESC";
		  }
	
		  $and = null;
		  $parts = parse_url($_SERVER['REQUEST_URI']);
		  isset($parts['query']) ? parse_str($parts['query'], $qs) : $qs = array();
		  $required = array(
			  "condition" => 0,
			  "make_name" => 1,
			  "color" => 2,
			  "body" => 3,
			  "sale" => 4
			  );
		  if (array_intersect_key($qs, $required)) {
			  if (!empty($_GET['condition']) and $condition = Validator::sanitize($_GET['condition'], "db", 6)) {
				  $and .= " AND li.condition_name = '{$condition}'";
			  } 
			  if (!empty($_GET['make_name']) and $make_name = Validator::sanitize($_GET['make_name'], "db", 30)) {
				  $and .= " AND li.make_slug = '{$make_name}'";
			  } 
			  if (!empty($_GET['color']) and $color = Validator::sanitize($_GET['color'], "alpha", 20)) {
				  $and .= " AND li.color_name = '{$color}'";
			  } 
			  if (!empty($_GET['body']) and $body = Validator::sanitize($_GET['body'], "db", 30)) {
				  $and .= " AND li.category_slug = '{$body}'";
			  } 
			  if (isset($_GET['sale'])) {
				  $and .= " AND li.special = 1";
			  }
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::liTable . "` as li WHERE lstatus = 1{$and}");
		  } else {
			  if (isset($_GET['range_search'])) {
				  if (!empty($_GET['year_range']) and $year = Validator::sanitize($_GET['year_range'], "string", 9)) {
					  $ydata = explode(";", $year);
					  if (count($ydata) == 2 and intval($ydata[0]) and intval($ydata[1])) {
						  $and .= " AND year BETWEEN " . Validator::sanitize($ydata[0], "digits") . " AND " . Validator::sanitize($ydata[1], "digits");
					  }
				  }
		
				  if (!empty($_GET['price_range']) and $price = Validator::sanitize($_GET['price_range'], "string", 16)) {
					  $pdata = explode(";", $price);
					  if (count($pdata) == 2 and intval($pdata[0]) and intval($pdata[1])) {
						  $and .= " AND price BETWEEN " . Validator::sanitize($pdata[0], "digits") . " AND " . Validator::sanitize($pdata[1], "digits");
					  }
				  }
				  if (!empty($_GET['km_range']) and $kms = Validator::sanitize($_GET['km_range'], "string", 20)) {
					  $kdata = explode(";", $kms);
					  if (count($kdata) == 2 and intval($kdata[0]) and intval($kdata[1])) {
						  $and .= " AND mileage BETWEEN " . Validator::sanitize($kdata[0], "digits") . " AND " . Validator::sanitize($kdata[1], "digits");
					  }
				  }
				
				  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1{$and}");
			  } else {
				  $total = self::$db->count(self::lTable, "status = 1");
			  }
		  }
	
		  $pager = Paginator::instance();
		  $pager->items_total = $total;
		  $pager->default_ipp = App::get("Core")->perpage;
		  $pager->paginate();
	
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.fuel_name, l.thumb,
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.sold, l.created, l.mileage, l.body, l.featured
		  FROM
			`" . self::liTable . "` AS li 
			LEFT JOIN `" . self::lTable . "` AS l 
			  ON l.id = li.listing_id 
		  WHERE l.status = ?
		  $and
		  ORDER BY $sorting{$pager->limit};";
	
		  $row = self::$db->pdoQuery($sql, array(1))->results();
		  return ($row) ? $row : 0;
	  }

      /**
       * Items::getSingleListing()
       * 
       * @return
       */
      public function getSingleListing()
      {
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.location_name, li.fuel_name, li.fuel_name, 
			u.username, u.avatar, l.*
		  FROM
			`" . self::lTable . "` AS l
			LEFT JOIN `" . self::liTable . "` AS li
			  ON li.listing_id = l.id 
			LEFT JOIN `" . Users::mTable . "` AS u
			  ON u.id = l.user_id
		  WHERE l.status = ?
		  AND l.idx = ?
		  AND l.slug = ?;";

		  $row = self::$db->pdoQuery($sql, array(1, App::get('Core')->_url[1], App::get('Core')->_url[2]))->result();
		  return ($row) ? $row : 0;

      }

      /**
       * Items::fullSearch()
       * 
       * @return
       */
      public function fullSearch()
      {
		  if (isset($_GET['order'])) {
			  list($sort, $order) = explode("/", $_GET['order']);
			  $sort = Validator::sanitize($sort, "default", 10);
			  $order = Validator::sanitize($order, "default", 4);
			  $array = array(
				  "year",
				  "price",
				  "make",
				  "model",
				  "mileage"
				  );
			  if (in_array($sort, $array)) {
				  $ord = ($order == 'DESC') ? " DESC" : " ASC";
				  switch ($sort) {
					  case "year":
						  $sortorder = "l.year";
						  break;
					  case "price":
						  $sortorder = "l.price";
						  break;
					  case "make":
						  $sortorder = "l.make_id";
						  break;
					  case "model":
						  $sortorder = "l.model_id";
						  break;
					  case "mileage":
						  $sortorder = "l.mileage";
						  break;
					  default:
						  $sortorder = "l.featured DESC, l.created DESC";
						  break;
				  }
				  $sorting = $sortorder . $ord;
			  } else {
				  $sorting = " l.featured DESC, l.created DESC";
			  }
		  } else {
			  $sorting = " l.featured DESC, l.created DESC";
		  }
	
		  $and = null;
		  $parts = parse_url($_SERVER['REQUEST_URI']);
		  isset($parts['query']) ? parse_str($parts['query'], $qs) : $qs = array();
		  $required = array(
			  "make_id" => 0,
			  "model_id" => 1,
			  "transmission" => 2,
			  "color_e" => 3,
			  "category" => 4,
			  "vcondition" => 5,
			  "doors" => 6,
			  "fuel" => 6
			  );
		  if (array_intersect_key($qs, $required)) {
			  if (!empty($_GET['make_id']) and $make_id = Validator::sanitize($_GET['make_id'], "digits", 11)) {
				  $and .= " AND l.make_id = {$make_id}";
			  }  
              if (!empty($_GET['model_id']) and $model_id = Validator::sanitize($_GET['model_id'], "digits", 11)) {
				  $and .= " AND l.model_id = {$model_id}";
			  }  
              if (!empty($_GET['transmission']) and $transmission = Validator::sanitize($_GET['transmission'], "digits", 2)) {
				  $and .= " AND l.transmission = {$transmission}";
			  }   
              if (!empty($_GET['color']) and $color = Validator::sanitize($_GET['color'], "alpha", 20)) {
				  $and .= " AND l.color_e = '{$color}'";
			  } 
              if (!empty($_GET['category']) and $category = Validator::sanitize($_GET['category'], "digits", 6)) {
				  $and .= " AND l.category = '{$category}'";
			  }  
              if (!empty($_GET['condition']) and $condition = Validator::sanitize($_GET['condition'], "digits", 1)) {
				  $and .= " AND l.vcondition = {$condition}";
			  } 
              if (!empty($_GET['doors']) and $doors = Validator::sanitize($_GET['doors'], "digits", 1)) {
				  $and .= " AND l.doors = {$doors}";
			  }
              if (!empty($_GET['fuel']) and $fuel = Validator::sanitize($_GET['fuel'], "digits", 2)) {
				  $and .= " AND l.fuel = {$fuel}";
			  } 
			  
			  if (!empty($_GET['price']) and $price = Validator::sanitize($_GET['price'], "digits", 12)) {
				  switch($price) {
					  case 10 :
						$and .= " AND price BETWEEN 0 AND 5000";
					  break;
					  case 20 :
						$and .= " AND price BETWEEN 5000 AND 10000";
					  break;
					  case 30 :
						$and .= " AND price BETWEEN 10000 AND 20000";
					  break;
					  case 40 :
						$and .= " AND price BETWEEN 20000 AND 30000";
					  break;
					  case 50 :
						$and .= " AND price BETWEEN 30000 AND 50000";
					  break;
					  case 60 :
						$and .= " AND price BETWEEN 50000 AND 75000";
					  break;
					  case 70 :
						$and .= " AND price BETWEEN 75000 AND 100000";
					  break;
					  case 80 :
						$and .= " AND price BETWEEN 100000 AND 9999999";
					  break;
				  }
			  } 

			  if (!empty($_GET['year_range']) and $year = Validator::sanitize($_GET['year_range'], "string", 9)) {
				  $ydata = explode(";", $year);
				  if (count($ydata) == 2 and intval($ydata[0]) and intval($ydata[1])) {
					  $and .= " AND year BETWEEN " . Validator::sanitize($ydata[0], "digits") . " AND " . Validator::sanitize($ydata[1], "digits");
				  }
			  }
			  if (!empty($_GET['price_range']) and $price = Validator::sanitize($_GET['price_range'], "string", 16)) {
				  $pdata = explode(";", $price);
				  if (count($pdata) == 2 and intval($pdata[0]) and intval($pdata[1])) {
					  $and .= " AND price BETWEEN " . Validator::sanitize($pdata[0], "digits") . " AND " . Validator::sanitize($pdata[1], "digits");
				  }
			  }
			  if (!empty($_GET['km_range']) and $kms = Validator::sanitize($_GET['km_range'], "string", 20)) {
				  $kdata = explode(";", $kms);
				  if (count($kdata) == 2 and intval($kdata[0]) and intval($kdata[1])) {
					  $and .= " AND mileage BETWEEN " . Validator::sanitize($kdata[0], "digits") . " AND " . Validator::sanitize($kdata[1], "digits");
				  }
			  }
				  
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` as l WHERE status = 1{$and}");
		  } else {
			  if (!empty($_GET['keyword']) and $keyword = Validator::sanitize($_GET['keyword'], "string", 20)) {
				  $and .= " AND l.nice_title LIKE '%{$keyword}%'";
				  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` as l WHERE status = 1{$and}");
			  } else {
				  $total = self::$db->count(self::lTable, "status = 1");
			  }
		  }

		  $pager = Paginator::instance();
		  $pager->items_total = $total;
		  $pager->default_ipp = App::get("Core")->sperpage;
		  $pager->paginate();
	
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.fuel_name, l.thumb,
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.sold, l.created, l.mileage, l.body, l.featured
		  FROM
			`" . self::liTable . "` AS li 
			LEFT JOIN `" . self::lTable . "` AS l 
			  ON l.id = li.listing_id 
		  WHERE l.status = ?
		  $and
		  ORDER BY $sorting{$pager->limit};";
	
		  $row = self::$db->pdoQuery($sql, array(1))->results();
		  return ($row) ? $row : 0;

      }
	  
      /**
       * Items::getUserActivity()
       * 
       * @param bool $id
       * @return
       */
      public function getUserActivity($id = false)
      {
          $uid = ($id) ? $id : Filter::$id;
          $row = self::$db->select(self::acTable, null, array("user_id" => $uid), 'ORDER BY created DESC')->results();

          return ($row) ? $row : 0;

      }

      /**
       * Items::getUserItems()
       * 
       * @param bool $id
       * @return
       */
      public function getUserItems($id = false)
      {
          $uid = ($id) ? $id : Filter::$id;
          $row = self::$db->select(self::lTable, null, array("user_id" => $uid), 'ORDER BY created DESC')->results();

          return ($row) ? $row : 0;

      }

      /**
       * Items::getUserListing()
       * 
       * @return
       */
      public function getUserListing()
      {
          $row = self::$db->first(self::lTable, null, array("user_id" => App::get('Auth')->uid, "id" => Filter::$id));

          return ($row) ? $row : 0;

      }
	  
      /**
       * Items::getGalleryImages()
       * 
       * @param bool $lid
       * @return
       */
      public static function getGalleryImages($lid = false)
      {
          $id = ($lid) ? $lid : Filter::$id;

          $row = self::$db->select(self::gTable, "*", array("listing_id" => $id), 'ORDER BY sorting')->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::processGaleryImage()
       * 
       * @param str $filename
	   * @param int $id
       * @return
       */
      public static function processGaleryImage($filename, $id)
      {
		  $path = UPLOADS . 'listings/pics' . $id . '/';
		  $maxsize = 6291456;
		  
          if (isset($_FILES[$filename]) && $_FILES[$filename]['error'] == 0) {
			  $extension = pathinfo($_FILES[$filename]['name'], PATHINFO_EXTENSION);
			  if (!in_array(strtolower($extension), array("jpg","jpeg","png"))) {
				  $json['type'] = "error";
				  $json['message'] = $json['message'] = str_replace("[EXT]", $extension, Lang::$word->FM_FILE_ERR5);
				  print json_encode($json);
				  exit;
			  }  

			  if (file_exists($path . $_FILES[$filename]['name'])) {
				  $json['type'] = "error";
				  $json['message'] = Lang::$word->FM_FILE_ERR1;
				  print json_encode($json);
				  exit;
			  }
			  
			  if (!is_writeable($path)) {
				  $json['type'] = "error";
				  $json['message'] = Lang::$word->FM_FILE_ERR2;
				  print json_encode($json);
				  exit;
			  }
			  
			  if ($maxsize < $_FILES[$filename]['size']) {
				  $json['type'] = "error";
				  $json['message'] = str_replace("[LIMIT]", File::getSize($maxsize), Lang::$word->FM_FILE_ERR3);
				  print json_encode($json);
				  exit;
			  }
			  
			  $html = '';
			  $newName = "IMG_" . Utility::randName();
			  $fullname = $path . $newName . "." . strtolower(File::getExtension($_FILES[$filename]['name']));
			  $dbname = $newName . "." . strtolower(File::getExtension($_FILES[$filename]['name']));

			  if (move_uploaded_file($_FILES[$filename]['tmp_name'], $fullname)) {
				  $data = array(
					  'listing_id' => $id,
					  'title' => "-/-",
					  'photo' => $dbname);
					  
				  $last_id = self::$db->insert(self::gTable, $data)->getLastInsertId();

                  try {
                      $img = new Image($path . $data['photo']); 
                      $img->thumbnail(400, 300)->save($path . 'thumbs/' . $data['photo']);
                  }
                  catch (exception $e) {
                      echo 'Error: ' . $e->getMessage();
                  }
				  
				  $html = '
					<tr data-id="' . $last_id . '" class="active">
					  <td class="sorter"><i class="icon reorder"></i></td>
					  <td><a href="' . UPLOADURL . 'listings/pics' . $id . '/' . $data['photo'] . '" data-title="Hello" data-lightbox-gallery="true" data-lightbox="true"><img src="' . UPLOADURL . 'listings/pics' . $id . '/thumbs/' . $data['photo'] . '" alt="" class="wojo grid small image"></a></td>
					  <td data-editable="true" data-set=\'{"type": "gallery", "id": ' . $last_id . ',"key":"name", "path":""}\'>' . $data['title'] . '</td>
					  <td><small class="wojo label">0</small></td>
					  <td><a class="delete" data-set=\'{"title": "' . Lang::$word->GAL_DELETE . '", "parent": "tr", "option": "deleteGalleryImage", "extra": "' . $data['photo'] . '", "id": ' . $last_id . ', "name": "' . $data['title'] . '"}\'><i class="rounded outline icon negative trash link"></i></a></td>
					</tr>';
			
				  
				  $json['type'] = "success";
				  $json['html'] = $html;
				  print json_encode($json);
				  exit;
			  }
		  }
		  
		  $json['type'] = "error";
		  print json_encode($json);
		  exit;

      }
	  

      /**
       * Items::quickMessage()
       * 
       * @return
       */
      public function quickMessage()
      {
          $validate = Validator::instance();
          $validate->addSource($_POST);
          $validate->addRule('msg', 'string', true, 3, 300, Lang::$word->CL_QMSG);
          $validate->run();

          if (empty(Message::$msgs)) {
              $numSent = 0;
              $mailer = Mailer::sendMail();
              $row = self::$db->select(Users::mTable, array('email', 'CONCAT(fname," ",lname) as name'), array('id' => Filter::$id))->result();

              if ($row) {
                  ob_start();
                  require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Quick_Email_From_Admin.tpl.php');
                  $html_message = ob_get_contents();
                  ob_end_clean();

                  $newbody = str_replace(array(
                      '[COMPANY]',
                      '[LOGO]',
                      '[FULLNAME]',
                      '[URL]',
                      '[MSG]',
                      '[DATE]'), array(
                      App::get("Core")->company,
                      Utility::getLogo(),
                      $row->name,
                      SITEURL,
                      $validate->safe->msg,
                      date('Y')), $html_message);

                  $message = Swift_Message::newInstance()
							->setSubject(Lang::$word->EMN_NOTEFROM . ' ' . App::get("Core")->company)
							->setTo(array($row->email => $row->name))->setFrom(array(App::get("Core")
							->site_email => App::get("Core")->company))
							->setBody($newbody, 'text/html');

                  $numSent++;
                  $mailer->send($message);
              }

              if ($numSent) {
                  $json['type'] = 'success';
                  $json['title'] = Lang::$word->SUCCESS;
                  $json['message'] = $numSent . ' ' . Lang::$word->EMN_SENT;
              } else {
                  $json['type'] = 'error';
                  $json['title'] = Lang::$word->ERROR;
                  $json['message'] = Lang::$word->EMN_ALERT;
              }
              print json_encode($json);
          } else {
              Message::msgSingleStatus();
          }
      }


      /**
       * Items::updateHits($id)
       * 
       * @param bool $id
       * @return
       */
      public function updateHits($id)
      {
		  $date = date('Y-m-d');
          if ($row = self::$db->pdoQuery("SELECT * FROM `" . Stats::sTable . "` WHERE DATE(created) = ? AND listing_id = ? LIMIT 1", array($date, $id))->result()) {
			  self::$db->pdoQuery("UPDATE `" . Stats::sTable . "` SET visits = visits+1 WHERE listing_id = {$row->listing_id} AND DATE(created) = '{$date}'");
          } else {
              $data = array(
                  'created' => Db::toDate(),
                  'visits' => 1,
                  'listing_id' => $id,
              );
			  self::$db->insert(Stats::sTable, $data);
          }
		  self::$db->pdoQuery("UPDATE " . self::lTable . " SET hits = hits+1 WHERE id = " . $id);

      }
	  
      /**
       * Webspecials::doTitle()
       * 
       * @return
       */
      public static function doTitle($model_id)
      {
          $sql = "
		  SELECT 
			md.name as mdname, mk.name as mkname 
		  FROM
			`" . Content::mdwsTable . "` AS md 
			LEFT JOIN `" . Content::mkwsTable . "` AS mk 
			  ON mk.id = md.make_id 
		  WHERE md.id = ?;";

          $row = self::$db->pdoQuery($sql, array($model_id))->result();
          return ($row) ? Url::doSeo($row->mkname . '-' . $row->mdname) : 0;
      }

      /**
       * Items::doActivity()
       * 
       * @return
       */
      public static function doActivity($array = array())
      {
		  
		  $data = array(
			'user_id' => $array['user_id'], 
			'type' => $array['type'], 
			'lid' => $array['lid'],
			'title' => $array['title'],
			'username' => $array['username'],
			'fname' => $array['fname'],
			'lname' => $array['lname'],
		   );
		  self::$db->insert(self::acTable, $data);
      }
	  
      /**
       * Items::activityIcons()
       * 
       * @param mixed $type
       * @return
       */
      public static function activityIcons($type)
      {

          switch ($type) {
              case "added":
                  return "car";

              case "like":
                  return "thumbs up";

              case "membership":
                  return "medal";

              case "login":
                  return "lock";
          }
      }


      /**
       * Items::activityTitle()
       * 
       * @param mixed $row
       * @return
       */
      public static function activityTitle($row)
      {
          switch ($row->type) {
              case "like":
                  return Lang::$word->LIKE . " &rsaquo; " . $row->title;

              case "added":
                  return Lang::$word->ADDED . " &rsaquo; " . $row->title;

              case "membership":
                  return Lang::$word->MEMBERSHIP . " &rsaquo; " . $row->title;

              case "login":
                  return Lang::$word->LOGIN;
          }
      }


      /**
       * Items::activityDesc()
       * 
       * @param mixed $row
       * @return
       */
      public static function activityDesc($row)
      {
          switch ($row->type) {
              case "like":
                  return Lang::$word->WSP_AC_LIKED . " &rsaquo; " . $row->title;

              case "added":
                  return Lang::$word->WSP_AC_ADD . " &rsaquo; " . $row->title;

              case "membership":
                  return Lang::$word->WSP_AC_MEM . " &rsaquo; " . $row->title;

              case "login":
                  return Lang::$word->WSP_AC_LOGIN;
          }
      }
  }