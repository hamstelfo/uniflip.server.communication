<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
$file = getRequest("file");
$server = getRequest("server");
$action = getRequest("action");

switch ($action) {
	// Start example URLs:
	
	// Production: http://81.7.134.38/com.server/?action=start_cutting_process&memberid=86855&catalogid=1090719
	// Localhost : http://localhost/uniflip.server.communication/?action=start_cutting_process&memberid=86855&catalogid=1090719

	//http://81.7.134.38/com.server/?file=1673811335-1204973160.upl&action=send_file
	// STEP 1: LINUX > CUTTER *********************************************************************
	case 'start_cutting_process': 
		// We prepare the cutter for receive info.
		updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_0);
		$url = ABSOLUTE_CUTTER_URL;
		$url .= "?catalogid=" . $catalogId;
		$url .= "&memberid=" . $memberId;
		$url .= "&file=" . $file;
		$url .= "&action=sending_first_info";
		$urlOutput = file_get_contents($url);
		showAsLink($url);
		pinta($urlOutput);
		pinta("fin", true);

	// STEP 2: CUTTER > LINUX *********************************************************************
	case 'sending_first_info': 
		// We ask for hte Linux server about catalog info and uploaded file.
		$url = ABSOLUTE_LINUX_URL;
		$url .= "?catalogid=" . $catalogId;
		$url .= "&memberid=" . $memberId;
		$url .= "&action=request_catalog_data";
		$catalogData = json_decode(file_get_contents($url)); // Now, we have the catalog info, but we need to insert it into the database..

		$url = ABSOLUTE_LINUX_URL;
		$url .= "?file=" . $file;
		$url .= "&action=request_file";
		$catalogFile = file_get_contents($url);
		file_put_contents(UPLOADS_PATH.$file, $catalogFile); // Now, we have the .upl file stored.

		$cutterCatalogId= createCatalogCutter2($catalogData);
		showAsLink($url);
		pinta($urlOutput);
		pinta($catalogData);
		pinta("fin", true);

	// STEP 2.1: LINUX > CUTTER *********************************************************************
	case 'request_catalog_data':
		// We get the full catalog info.
		updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_1);
		$catalogInfo= getCatalog($memberId, $catalogId, true);
		die($catalogInfo);
		break;

	// STEP 2.2: LINUX > CUTTER *********************************************************************
	case 'request_file': // Most probably: linux server.
		// We serve the uploaded file.
		updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_2);
		if ($file_content= file_get_contents(UPLOADS_PATH.$file))
		{
			die($file_content);
		}
		else
		{
			die("0");
		}	
		break;

	case 'receive_file': // Most probably: windows old cutter server.
		// At this point, this scrip is executed by the cutter server and will call the linux server.

		break;
	
	default:
		die("No action defined.");
		break;
}

if ($action=="")
{


}
elseif ($action=="")
{
	
}
/*
function getRequest($index) {
	$value = "";
	if (isset($_REQUEST[$index]))
		$value = $_REQUEST[$index];
	return $value;
}
*/
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
?>