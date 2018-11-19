<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uniUploadMemberid = getRequest("memberid");
$uniUploadCatalogid = getRequest("catalogid");
$file = getRequest("file");
$server = getRequest("server");
$action = getRequest("action");

if ($action=="send_file") // Most probably: linux server.
{
	// At this point, this scrip is being calling FROM the cutter server and is executed IN the linux server.

	if (isPreProductionVersion())
	{
		// URL EXAMPLE: http://localhost/uniflip.server.communication/?file=473546384-1415464062.upl&action=send_file
		// URL EXAMPLE: http://81.7.134.38/uniflip.server.communication/?file=473546384-1415464062.upl&action=send_file
		define("UNIFLIP_FOLDER", "uniflip.com/html");	
	}
	else
	{
		define("UNIFLIP_FOLDER", "uniflip4.com");	
	}
	
	define("UPLOADS_PATH", "../".UNIFLIP_FOLDER."/member/upload1/uploads/");

	if ($file_content= file_get_contents(UPLOADS_PATH.$file))
	{
		die($file_content);
	}
	else
	{
		die("0");
	}

}
else // Most probably: windows old cutter server.
{
	// At this point, this scrip is executed by the cutter server and will call the linux server.
}

function getRequest($index) {
	$value = "";
	if (isset($_REQUEST[$index]))
		$value = $_REQUEST[$index];
	return $value;
}

function isPreProductionVersion()
{
	return ($_SERVER ['HTTP_HOST'] == "81.7.134.38" || $_SERVER ['HTTP_HOST'] == "uniflip2.com" || $_SERVER ['HTTP_HOST'] == "en.uniflip.com") ;
}
?>