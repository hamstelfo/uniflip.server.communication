<?
	include("./header.php");

	$action= "request_file";
	
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
	elseif ($file_content= file_get_contents($filePath))
	{
		updateLog($memberId, $catalogId, $catalogIdcutter, $action, "OK");
		$msg= $file_content;
	}
	else
	{
		$msg= "ERROR! file_get_contents: ".$filePath;
		updateLog($memberId, $catalogId, $catalogIdcutter, $action, $msg);			
	}	
	die($msg);
?>