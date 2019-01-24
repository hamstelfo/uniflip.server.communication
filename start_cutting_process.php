<?
	include("./header.php");

	$action= "start_cutting_process";
	
	// We prepare the cutter for receive info.	
	//die("principio start_cutting_process.");
	updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_0);
	updateLog($memberId, $catalogId, $catalogIdcutter, $action, "OK");
	$url = ABSOLUTE_CUTTER_URL."cutter_requesting.php";
	$url .= "?catalogid=" . $catalogId;
	$url .= "&memberid=" . $memberId;
	$url .= "&file=" . $file.urlTest();
	//pinta($url, true);
	if (!$urlOutput = fileGetContents($url))
	{
		pinta("File get contents was wrong:" . $url);
	}
	linkIfLocalhost($url);
	pinta($urlOutput);
	die(".");	
?>