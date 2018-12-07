<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

//die("principio.");

define("USE_CURL",	TRUE);

if (isPreProductionVersion_()) // Is Lunix server.
{
	// URL EXAMPLE: http://localhost/uniflip.server.communication/?file=473546384-1415464062.upl&action=send_file
	// URL EXAMPLE: http://81.7.134.38/com.server/?file=1673811335-1204973160.upl&action=send_file
	define("UNIFLIP_FOLDER", "uniflip.com/html/");	
	define("GET_OUT_PATH", "../../");

	define("ABSOLUTE_CUTTER_URL", "http://86.48.36.131/uniflip.server.communication/");
	define("ABSOLUTE_LINUX_URL", "http://81.7.134.38/html/com.server/");
}
elseif (!isCutterVersion_()) // Is in my localhost test server.
{
	define("UNIFLIP_FOLDER", "uniflip4.com/");	
	define("GET_OUT_PATH", "../");

	define("ABSOLUTE_CUTTER_URL", "http://localhost/uniflip.server.communication/");
	define("ABSOLUTE_LINUX_URL", ABSOLUTE_CUTTER_URL);
}
else // Is the cutter server.
{
	define("UNIFLIP_FOLDER", "");	
	define("GET_OUT_PATH", "../");	

	define("ABSOLUTE_CUTTER_URL", "http://86.48.36.131/uniflip.server.communication/");
	define("ABSOLUTE_LINUX_URL", "http://81.7.134.38/html/com.server/");
}

define("EXTRA_DOCUMENT_ROOT", "/".UNIFLIP_FOLDER);
define("SERVER_PATH", GET_OUT_PATH.UNIFLIP_FOLDER);
define("UPLOADS_PATH", SERVER_PATH."member/upload1/uploads/");

include(SERVER_PATH."_conf.php");
include("./linux.php");
include("./cutter.php");

$memberId = getRequest("memberid");
$catalogId = getRequest("catalogid");
$catalogIdcutter = getRequest("catalogidcutter");
$file = getRequest("file");
$server = getRequest("server");
$action = getRequest("action");

switch ($action) {
	// Start example URLs:
	
	// Production: http://81.7.134.38/com.server/?action=start_cutting_process&memberid=86855&catalogid=1090719&file=1673811335-1204973160.upl
	// Localhost : http://localhost/uniflip.server.communication/?action=start_cutting_process&memberid=86855&catalogid=1090723&file=1673811335-1204973160.upl

	//http://81.7.134.38/com.server/?file=1673811335-1204973160.upl&action=send_file
	// STEP 1: LINUX > CUTTER *********************************************************************
	/*case 'start_cutting_process': 
		// We prepare the cutter for receive info.	
		//die("principio start_cutting_process.");
		updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_0);
		updateLog($memberId, $catalogId, $catalogIdcutter, $action, "OK");
		$url = ABSOLUTE_CUTTER_URL;
		$url .= "?catalogid=" . $catalogId;
		$url .= "&memberid=" . $memberId;
		$url .= "&file=" . $file;
		$url .= "&action=sending_first_info".urlTest();
		if (!$urlOutput = fileGetContents($url))
		{
			pinta("File get contents was wrong:" . $url);
		}
		linkIfLocalhost($url);
		pinta($urlOutput);
		die(".");*/

	// STEP 2: CUTTER > LINUX *********************************************************************
	/*case 'sending_first_info': 
		// We ask for hte Linux server about catalog info and uploaded file.		
		//die("principio sending_first_info.");
		$url = ABSOLUTE_LINUX_URL;
		$url .= "?catalogid=" . $catalogId;
		$url .= "&memberid=" . $memberId;
		$url .= "&action=request_catalog_data".urlTest();		
		linkIfLocalhost($url);
		//die("mitad sending_first_info 1.");
		$catalogData= fileGetContents($url);

		pinta($catalogData);
		die("mitad sending_first_info 2.");

		$catalogData = json_decode($catalogData, true); // Now, we have the catalog info, but we need to insert it into the database..

		
		pinta($catalogData);
		die("mitad sending_first_info 3.");		

		if ($cutterCatalogId= createCatalogCutter2($catalogData))
		{
			$url = ABSOLUTE_LINUX_URL;
			$url .= "?file=" . $file;
			$url .= "&catalogid=" . $catalogId;
			$url .= "&catalogidcutter=" . $cutterCatalogId;
			$url .= "&memberid=" . $memberId;
			$url .= "&action=request_file".urlTest();
			if ($catalogFile = fileGetContents($url))
			{
				file_put_contents(UPLOADS_PATH.$file, $catalogFile); // Now, we have the .upl file stored.

				//pinta($cutterCatalogId);
				linkIfLocalhost($url);
				//linkIfLocalhost(UPLOADS_PATH.$file);
				//pinta($catalogFile);

				$url = ABSOLUTE_LINUX_URL;
				$url .= "?catalogid=" . $catalogId;
				$url .= "&memberid=" . $memberId;
				$url .= "&action=cutter_runs_alone".urlTest();
				fileGetContents($url); // Just say the Linux that we can start the cutter process now..
			}

			die(".");
		}
		else
		{
			pinta("Error on createCatalogCutter2.");	
			pinta($catalogData, true);
		}		*/

	// STEP 2.1: LINUX > CUTTER *********************************************************************
	/*case 'request_catalog_data':
		// We get the full catalog info.
		die("principio request_catalog_data.");
		updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_1);		
		if ($catalogInfo= getCatalogInfo($memberId, $catalogId, true))
		{
			updateLog($memberId, $catalogId, $catalogIdcutter, $action, "OK");
			die($catalogInfo);
		}
		else
		{
			updateLog($memberId, $catalogId, $catalogIdcutter, $action, "ERROR!");
			die("0");
		}	
		
		break;*/

	// STEP 2.2: LINUX > CUTTER *********************************************************************
	/*case 'request_file': // Most probably: linux server.
		// We serve the uploaded file.
		updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_2);
		$filePath= UPLOADS_PATH.$file;
		$msg= "0";

		if (!$file)
		{
			$msg= "ERROR! No file.";
			updateLog($memberId, $catalogId, $catalogIdcutter, $action, $msg);
		}
		elseif (!is_file($filePath))
		{
			$msg= "ERROR! File does not exists: ".$filePath;
			updateLog($memberId, $catalogId, $catalogIdcutter, $action, $msg);
		}
		elseif ($file_content= fileGetContents($filePath))
		{
			updateLog($memberId, $catalogId, $catalogIdcutter, $action, "OK");
			$msg= $file_content;
		}
		else
		{
			$msg= "ERROR! fileGetContents: ".$filePath;
			updateLog($memberId, $catalogId, $catalogIdcutter, $action, $msg);			
		}	
		die($msg);
		break;*/

	// STEP 2.2: LINUX > [none] *********************************************************************	
	/*case 'cutter_runs_alone': // Most probably: windows old cutter server.
		// Just update to know that we need to let the cutter working.
		updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_3);
		updateLog($memberId, $catalogId, $catalogIdcutter, $action, "OK");
		die("0");
		break;*/
	
	default:
		die("No action defined.");
		break;
}

function isPreProductionVersion_()
{
	return ($_SERVER ['HTTP_HOST'] == "81.7.134.38" || $_SERVER ['HTTP_HOST'] == "uniflip2.com" || $_SERVER ['HTTP_HOST'] == "en.uniflip.com") ;
}

function isCutterVersion_()
{
	return ($_SERVER ['HTTP_HOST'] == "86.48.36.131") ;
}

function showAsLink($a)
{
	pinta("<a href='".$a."' target='_blank'>".$a."</a>");
}

function linkIfLocalhost($a, $byCutter= false)
{
	//pinta($_SERVER["HTTP_HOST"]);
	if ($_SERVER["HTTP_HOST"]=="localhost" && $byCutter)
	{
		pinta("Next link [should be manual in locahost testing]: ");
		showAsLink($a);
	}
	else
	{
		pinta("Next link [auto]: ");
		pinta($a);
	}
}

// Here we can only control LINUX operations.
function updateLog($memberId, $catalogIdLinux, $catalogIdCutter, $step, $extra= "")
{
	$testLogId= getRequest("testLogId");
	dbOpen();
	$sep= " ";
	$timeStamp= date("Y-m-d H:i:s");
	$val= $extra . $sep . $timeStamp . $sep . " catalogIdLinux=".$catalogIdLinux . $sep;

	if ($catalogIdCutter)
	{
		$catalogIdCutter= ", `catalogIdCutter`= '".$catalogIdCutter."'";
	}

	switch ($step) {
		case 'start_cutting_process':			
			$sql= "INSERT INTO `cutter_logs` (`memberId`, `catalogIdLinux`, `start_cutting_process`) VALUES ('".$memberId."', '".$catalogIdLinux."', '".$val."') \r\n";
			break;

		default:
			if ($testLogId)
			{
				$sql= "UPDATE `cutter_logs` SET `".$step."`= '".$val."' ".$catalogIdCutter." WHERE `id`='".$testLogId."' \r\n";
			}
			else
			{
				$sql= "UPDATE `cutter_logs` SET `".$step."`= '".$val."' ".$catalogIdCutter." WHERE `catalogIdLinux`='".$catalogIdLinux."' \r\n";
			}

			break;
	}
	
	$result = dBQuery($sql) or die("Query failed: " . dBError());
	dBFreeResult($result);
	dbClose();
}

function urlTest()
{
	if ($testLogId= getRequest("testLogId"))
	{
		return "&testLogId=".$testLogId;
	}

	return "";
}

function fileGetContents($url)
{
	if (USE_CURL)
	{
		echo "*** using curl ***";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
		        //'file' => '@/..../file.jpg',
		         // you'll have to change the name, here, I suppose
		         // some other fields ?
		));
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}
	else
	{
		echo "*** using file_get_contents ***";
		return file_get_contents($url);
	}
	
}
?>