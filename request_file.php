<?
	include("./header.php");

	$action= "request_file";
	$serveFile = 1; //getRequest("serveFile"); // If not, then we just return the file URL. If yes, we return the file content.
	
	// We serve the uploaded file.
	updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_2);
	$filePath= COPY_FILES_PATH.$file;
	$filePath2= UPLOADS_PATH.$file;

	$msg= "0";

	if (!$file)
	{
		$msg= "ERROR! No file.";
		updateLog($memberId, $catalogId, $catalogIdcutter, $action, $msg);
	}
	elseif (!$serveFile)
	{
		$msg= COPY_FILES_PATH.$file; // http://uniflip4.com.test/member/upload1/uploads/test2.upl
	}
	elseif (!is_file($filePath2))
	{
		$msg= "ERROR! File does not exists: ".$filePath;
		updateLog($memberId, $catalogId, $catalogIdcutter, $action, $msg);
	}
	elseif (!$filePathZipped= zipFile($filePath2))
	{
		$msg= "ERROR! File cannot be zipped: ".$filePath;
		updateLog($memberId, $catalogId, $catalogIdcutter, $action, $msg);
	}
	//elseif ($file_content= file_get_contents($filePathZipped))
	elseif ($file_content= getFileFromLinuxFTP("tmpfileoncutter.zip", $file))		
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