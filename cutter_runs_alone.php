<?
	include("./header.php");

	$action= "cutter_runs_alone";
	
	// Just update to know that we need to let the cutter working.
	updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_3);
	updateLog($memberId, $catalogId, $catalogIdcutter, $action, "OK");
	die("0");
?>