<?php 
	/** 
	* Configuration

	* @package Wojo Framework
	* @author wojoscripts.com
	* @copyright 2017
	* @version Id: configs.ini.php, v1.00 2018-02-15 03:32:05 gewa Exp $
	*/
 
	 if (!defined("_WOJO")) 
     die('Direct access to this location is not allowed.');
 
	/** 
	* Database Constants - these constants refer to 
	* the database configuration settings. 
	*/
	 define('DBQ_SERVER', 'localhost'); 
	 define('DBQ_USER', 'quirkspe_ncsuser'); 
	 define('DBQ_PASS', '(OTiJdM%.JUs'); 
	 define('DBQ_DATABASE', 'quirkspe_inventory');
	 define('DBQ_DRIVER', 'mysql');
 
	 //define('INSTALL_KEY', '18u1HvEDr5pskzgY'); 
 
	/** 
	* Show Debugger Console. 
	* Display errors in console view. Not recomended for live site. true/false 
	*/
	 define('DEBUG', false);
?>