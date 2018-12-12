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

	//pinta($catalogData);
	//die("mitad cutter_requesting 2.");

	$catalogData = json_decode($catalogData, true); // Now, we have the catalog info, but we need to insert it into the database..
	
	//pinta($catalogData);
	//die("mitad cutter_requesting 3.");		

	if ($cutterCatalogId= createCatalogCutter2($catalogData))
	{
		$url = ABSOLUTE_LINUX_URL."request_file.php";
		$url .= "?file=" . $file;
		$url .= "&catalogid=" . $catalogId;
		$url .= "&catalogidcutter=" . $cutterCatalogId;
		$url .= "&memberid=" . $memberId.urlTest();
		linkIfLocalhost($url);
		//die("mitad cutter_requesting 4.");
		if ($catalogFile = fileGetContents($url))
		{
			//pinta($catalogFile);
			//die("mitad cutter_requesting 5.");
			/**
			 * Transfer Files Server to Server using PHP Copy
			 * @link https://shellcreeper.com/?p=1249
			 */
			/* Copy the file from source url to server */
			$copy = copy( $catalogFile, $catalogFile.".new" );
			 
			/* Add notice for success/failure */
			if( !$copy ) {
			    echo "Doh! failed to copy $file...\n";
			}
			else{
			    echo "WOOT! success to copy $file...\n";
			}
			//file_put_contents(UPLOADS_PATH.$file, $catalogFile.".new"); // Now, we have the .upl file stored.

			//pinta($cutterCatalogId);
			linkIfLocalhost($catalogFile);			
			//linkIfLocalhost(UPLOADS_PATH.$file);
			//pinta($catalogFile);

			$url = ABSOLUTE_LINUX_URL."cutter_runs_alone.php";
			$url .= "?catalogid=" . $catalogId;
			$url .= "&memberid=" . $memberId.urlTest();
			$url .= "&action=";
			linkIfLocalhost($url);
			fileGetContents($url); // Just say the Linux that we can start the cutter process now..
		}
		else
		{
			pinta("No file found at fileGetContents:".$url, true);
		}

		die(".");
	}
	else
	{
		pinta("Error on createCatalogCutter2.");	
		pinta($catalogData, true);
	}		
?>