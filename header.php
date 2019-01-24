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
	define("UNIFLIP_URL", "http://81.7.134.38/html/uniflip.com/html/");
}
elseif (!isCutterVersion_()) // Is in my localhost test server.
{
	define("UNIFLIP_FOLDER", "uniflip4.com/");	
	define("GET_OUT_PATH", "../");

	define("ABSOLUTE_CUTTER_URL", "http://localhost/uniflip.server.communication/");
	define("ABSOLUTE_LINUX_URL", ABSOLUTE_CUTTER_URL);
	define("UNIFLIP_URL", "http://uniflip4.com.test/");
}
else // Is the cutter server.
{
	define("UNIFLIP_FOLDER", "");	
	define("GET_OUT_PATH", "../");	

	define("ABSOLUTE_CUTTER_URL", "http://86.48.36.131/uniflip.server.communication/");
	define("ABSOLUTE_LINUX_URL", "http://81.7.134.38/html/com.server/");
	define("UNIFLIP_URL", "http://uniflip.com/");
}

define("EXTRA_DOCUMENT_ROOT", "/".UNIFLIP_FOLDER);
define("SERVER_PATH", GET_OUT_PATH.UNIFLIP_FOLDER);
define("UPLOADS_PATH", SERVER_PATH."member/upload1/uploads/");

include(SERVER_PATH."_conf.php");
include("./helpers/common.php");
include("./helpers/linux.php");
include("./helpers/cutter.php");

$memberId = getRequest("memberid");
$catalogId = getRequest("catalogid");
$catalogIdcutter = getRequest("catalogidcutter");
$file = getRequest("file");
$server = getRequest("server");
$action = getRequest("action");

function isPreProductionVersion_()
{
	return ($_SERVER ['HTTP_HOST'] == "81.7.134.38" || $_SERVER ['HTTP_HOST'] == "uniflip2.com" || $_SERVER ['HTTP_HOST'] == "en.uniflip.com") ;
}

function isCutterVersion_()
{
	return ($_SERVER ['HTTP_HOST'] == "86.48.36.131") ;
}
?>