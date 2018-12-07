<?
	include("./header.php");

	$action= "request_catalog_data";
	
	// We prepare the cutter for receive info.	
	//die("principio start_cutting_process.");
	updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_0);
	updateLog($memberId, $catalogId, $catalogIdcutter, $action, "OK");
	$url = ABSOLUTE_CUTTER_URL."cutter_requesting.php";
	$url .= "?catalogid=" . $catalogId;
	$url .= "&memberid=" . $memberId;
	$url .= "&file=" . $file.urlTest();
	if (!$urlOutput = fileGetContents($url))
	{
		pinta("File get contents was wrong:" . $url);
	}
	linkIfLocalhost($url);
	pinta($urlOutput);
	die(".");	
?>