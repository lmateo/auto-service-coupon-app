<?php

class Api {
	public $errorsArr = array();
	public $apiDataArr = array();
	public $authorized = FALSE;
	public $privateKey = "fjFB9E!4h_HC012";
	public $publicKey = "";
	public $cD1 = "<![CDATA[";
	public $cD2 = "]]>";
	public $retXml = "";
	public $retSpecialsXml = "";
	public $retErrorXml = "";


	function ApiMethod()
	{
		$this->publicKey = "";
		$this->authorized = FALSE;
	}

	function authorizeMe()
	{
		if( $this->publicKey == $this->privateKey )
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
		$this->retXml .= "<api version=\"1.0\">\r\n";
		$this->retXml .= "	<messages>\r\n";
		$this->retXml .= "	  <message>Authorized Api User!</message>\r\n";
		$this->retXml .= "	</messages>\r\n";
		$this->retXml .= "</api>\r\n";
	}
	


	function handleRequest()
	{
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

						case "GETAPI":
							if( $this->authorizeMe() )
							{
								$this->getApiData();
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
			$this->errorsArr[] = "Error [nothing at all]";
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

	function getApiData()
	{
		// uncomment the lines below when running in stand-alone mode:
		require_once("api_connect.php");
		
		$api = new PHP_CRUD_API(array(
		 	'dbengine'=>'MySQL',
		 	'hostname'=>'localhost',
		 	'username'=>'quirkspe_ncsuser',
		 	'password'=>'(OTiJdM%.JUs',
		 	'database'=>'quirkspe_inventory_dev',
		 	'charset'=>'utf8'
		));
		$api->executeCommand();

	}

	function getApiErrorReponse()
	{
		$this->retErrorXml = "";
		$this->retErrorXml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
		$this->retErrorXml .= "<api version=\"1.0\">\r\n";
		$this->retErrorXml .= "	<errors total=\"" . count($this->errorsArr) . "\">\r\n";
		if( $this->errorsArr > 0 )
		{
			foreach($this->errorsArr AS $errorMessage )
			{
				$this->retErrorXml .= "	<error>".$errorMessage."</error>\r\n";
			}
		}
		$this->retErrorXml .= "	</errors>\r\n";
		$this->retErrorXml .= "	</api>\r\n";

	}
}