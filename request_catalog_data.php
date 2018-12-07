<?
	include("./header.php");

	$action= "request_catalog_data";
	
	// We get the full catalog info.
	//die("principio request_catalog_data.");
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
?>