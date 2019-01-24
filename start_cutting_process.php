<?
	include("./header.php");

	$action= "start_cutting_process";
	
	// We prepare the cutter for receive info.	
	//die("principio start_cutting_process.");
	updateCatalogPDFInfo($memberId, $catalogId, STATUS_LINUX_CUTTER_0);
	updateLog($memberId, $catalogId, $catalogIdcutter, $action, "OK");
	$file= zipFileIfPossible($file);

	$url = ABSOLUTE_CUTTER_URL."cutter_requesting.php";
	$url .= "?catalogid=" . $catalogId;
	$url .= "&memberid=" . $memberId;
	$url .= "&file=" . $file.urlTest();
	//pinta($url, true);
	linkIfLocalhost($url);
	//die("before go to the file");
	if (!$urlOutput = fileGetContents($url))
	{
		pinta("File get contents was wrong:" . $url);
	}
	
	pinta($urlOutput);
	die(".");	

	function zipFileIfPossible($file)
	{
		global $memberId;
		global $catalogId;
		global $catalogIdcutter;
		global $action;

		$filePath= UPLOADS_PATH.$file;
		if (!is_file($filePath))
		{
			$msg= "ERROR! File does not exists: ".$filePath;
			updateLog($memberId, $catalogId, $catalogIdcutter, $action, $msg);
			die($msg);
		}
		elseif ($fileZipped= zipFile($file, UPLOADS_PATH))
		{
			$msg= "File zipped: ".$filePath;
			updateLog($memberId, $catalogId, $catalogIdcutter, $action, $msg);
			return $fileZipped; // We continue the process with the file zippped.
		}
		else
		{
			$msg= "WARNING! File cannot be zipped: ".$filePath;
			updateLog($memberId, $catalogId, $catalogIdcutter, $action, $msg);
			return $file; // We continue the process withou zippping the file.
		}
	}
?>