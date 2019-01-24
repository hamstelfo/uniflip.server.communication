<?
	include("./header.php");

	$action= "cutter_requesting";
	
	// We ask for hte Linux server about catalog info and uploaded file.		
	//die("principio cutter_requesting.");
	$url = ABSOLUTE_LINUX_URL."request_catalog_data.php";
	$url .= "?catalogid=" . $catalogId;
	$url .= "&memberid=" . $memberId.urlTest();
	linkIfLocalhost($url);
	//die("mitad cutter_requesting 1.");
	$catalogData= fileGetContents($url);

	pinta($catalogData);
	//die("mitad cutter_requesting 2.");

	$catalogData = json_decode($catalogData, true); // Now, we have the catalog info, but we need to insert it into the database..
	
	//pinta($catalogData);
	//die("mitad cutter_requesting 3.");	
	$fileNew= $file.".new";

	if ($bring_file= getFileFromLinuxFTP($fileNew, $file))		
	{
		updateLog($memberId, $catalogId, $catalogIdcutter, "request_file", "OK:".$fileNew);
		//die("here we take the file from the real server.");
		if ($cutterCatalogId= createCatalogCutter2($catalogData))
		{
			$url = ABSOLUTE_LINUX_URL."cutter_runs_alone.php";
			$url .= "?catalogid=" . $catalogId;
			$url .= "&memberid=" . $memberId.urlTest();
			linkIfLocalhost($url);
			pinta("cortamos antes del run alone");
			fileGetContents($url); // Just say the Linux that we can start the cutter process now..

			die(".");
		}
		else
		{
			$msg= "Error on createCatalogCutter2.";
			updateLog($memberId, $catalogId, $catalogIdcutter, "request_file", $msg);
			pinta($msg);	
			pinta($catalogData, true);
		}		
	}
	else
	{
		$msg= "ERROR! getFileFromLinuxFTP: ".$file;
		updateLog($memberId, $catalogId, $catalogIdcutter, "request_file", $msg);			
	}	

	
?>