<?php

define("CUTTER_EXTERNAL_USER_ID", 	86855);

function createCatalogCutter2($catalog)
{
	//pinta($catalog);

	$catalog["userid"]= CUTTER_EXTERNAL_USER_ID;

	$localCatalogPath = "" . localCatalogPath;
	$remoteCatalogPath = "" . remoteCatalogPath;
	$localCatalogServer = "" . localCatalogServer;
	$remoteCatalogServer = "" . remoteCatalogServer;

	$catalogUserPath = $localCatalogPath . $catalog["userid"] . fs_dirsep;
	$catalogUserTempPath = $catalogUserPath . "_temp" . fs_dirsep;
	//pinta($catalogUserPath);
	//pinta($catalogUserTempPath);
	if (!is_dir($catalogUserPath))
		mkdir($catalogUserPath, 0777);
	if (!is_dir($catalogUserTempPath))
		mkdir($catalogUserTempPath, 0777);


	$oUnicodeReplace = new unicode_replace_entities();
	$rCatalogname = $catalog["catalogname"];
	$rCatalogname = urldecode($rCatalogname);
	//$rCatalogname = js_unescape_to_html($rCatalogname);
	$rCatalogname = $oUnicodeReplace->UTF8entities($rCatalogname);
	$rCatalogfile = $catalog["catalogfile"];
	$rCatalogfileURL = $catalog["catalogfileURL"];
	$rCatalogfilepassword = $catalog["catalogfilepassword"];
	//$rCataloglogofile = $catalog["");
	$rCataloglanguage = $catalog["cataloglanguage"];
	$rCataloglayout = $catalog["cataloglayout"];
	$rCatalogrighttoleft = $catalog["catalogrighttoleft"];
	$rCatalogbackgroundsound = $catalog["catalogbackgroundsound"];
	$rCatalogconvertingmethod = $catalog["catalogconvertingmethod"];
	$rCatalogid = "0";
	if ($rCatalogconvertingmethod == "")
	  $rCatalogconvertingmethod = "1";

	/*
	echo "action: " . $rAction . "<br/>\r\n";
	echo "catalogname: " . $rCatalogname . "<br/>\r\n";
	echo "catalogfile: " . $rCatalogfile . "<br/>\r\n";
	echo "cataloglogofile: " . $rCataloglogofile . "<br/>\r\n";
	echo "cataloglanguage: " . $rCataloglanguage . "<br/>\r\n";
	echo "cataloglayout: " . $rCataloglayout . "<br/>\r\n";
	echo "catalogrighttoleft: " . $rCatalogrighttoleft . "<br/>\r\n";
	echo "catalogbackgroundsound: " . $rCatalogbackgroundsound . "<br/>\r\n";
	echo "files: <br/>\r\n";
	echo "<pre>";
	print_r($_FILES);
	echo "</pre>";
	die("");
	*/

	$pdffilename = $rCatalogfile;
	if ($pdffilename == "")
	{
		$pdffilename = $rCatalogfileURL;
	}
	$pdffilesize = "0";
	$logofilename = "";
	$logofilesize = "0";
	dbOpen();
	
	$removeCopyright = 0;
	$powerText = '';
	$sql = "select removecopyright, copyrighttext ";
	$sql .= "from users ";
	$sql .= "where id = '" . $catalog["userid"] . "'; ";
	//echo "<pre>" . $sql . "</pre>"; flush();
	$result2 = dBQuery($sql) or die("Query failed: " . dBError());
	if ($row2 = dBFetchArray($result2, MYSQL_ASSOC)) 
	{
		$removeCopyright = $row2["removecopyright"];
		//$powerText = $row2["copyrighttext"]; // WE ARE AVOIDING UTF8 PROBLEMS, AS LONG AS THIS FIELD IS NOT USED IN CATALOGS (IT COMES FROM USERS)
	}
	dBFreeResult($result2);
	
	$sql = "\r\n";
	$sql .= "insert into catalogs( \r\n";
	$sql .= "	userid, \r\n";
	$sql .= "	name, \r\n";
	$sql .= "	pdffilename, \r\n";
	$sql .= "	pdffilesize, \r\n";
	$sql .= "	pdfinfo, \r\n";
	$sql .= "	pdfpassword, \r\n";
	$sql .= "	status, \r\n";
	$sql .= "	deleted, \r\n";
	$sql .= "	created, \r\n";
	$sql .= "	changed, \r\n";
	$sql .= "	logofilename, \r\n";
	$sql .= "	logofilesize, \r\n";
	$sql .= "	language, \r\n";
	$sql .= "	layout, \r\n";
	$sql .= "	url, \r\n";
	$sql .= "	introtext, \r\n";
	$sql .= "	freeactivatemail, \r\n";
	$sql .= "	privateuse, \r\n";
	$sql .= "	flashversion, \r\n";
	$sql .= "	fspath, \r\n";
	$sql .= "	wspath, \r\n";
	$sql .= "	fsserver, \r\n";
	$sql .= "	wsserver, \r\n";
	$sql .= "	copyrightremove, \r\n";
	$sql .= "	bgsound, \r\n";
	$sql .= "	righttoleft, \r\n";
	$sql .= "	powertext, \r\n";
	$sql .= "	convertingmethod \r\n";
	$sql .= ") \r\n";
	$sql .= "values(  \r\n";
	$sql .= "	" . $catalog["userid"] .", \r\n";
	$sql .= "	'" . str_replace("'", "''", $rCatalogname) . "', \r\n";
	$sql .= "	'" . str_replace("'", "''", $pdffilename) . "', \r\n";
	$sql .= "	" . $pdffilesize . ", \r\n";
	$sql .= "	'" . str_replace("'", "''", $rCatalogfilepassword) . "', \r\n";
	$sql .= "	'', \r\n";
	$sql .= "	0, \r\n";
	$sql .= "	0, \r\n";
	$sql .= "	NOW(), \r\n";
	$sql .= "	NOW(), \r\n";
	$sql .= "	'" . str_replace("'", "''", $logofilename) . "', \r\n";
	$sql .= "	" . $logofilesize . ", \r\n";
	$sql .= "	'" . $rCataloglanguage . "', \r\n";
	$sql .= "	'" . $rCataloglayout . "', \r\n";
	$sql .= "	'', \r\n";
	$sql .= "	'', \r\n";
	$sql .= "	NOW(), \r\n";
	$sql .= "	0, \r\n";
	$sql .= "	" . floatval(flashCatalogVersion) . ", \r\n";
	//$sql .= "	" . floatval(globalCatalogVersion) . ", \r\n";
	$sql .= "	'" . str_replace("\\", "\\\\", $localCatalogPath) . "', \r\n";
	$sql .= "	'" . $remoteCatalogPath . "', \r\n";
	$sql .= "	'" . $localCatalogServer . "', \r\n";
	$sql .= "	'" . $remoteCatalogServer . "', \r\n";
	$sql .= "	'" . $removeCopyright . "', \r\n";
	$sql .= "	'" . $rCatalogbackgroundsound . "', \r\n";
	$sql .= "	'" . $rCatalogrighttoleft . "', \r\n";
	$sql .= "	'" . $powerText . "', \r\n";
	$sql .= " '" . $rCatalogconvertingmethod . "' \r\n";
	$sql .= "); \r\n";
	$sql .= "\r\n";
	
	//pinta($sql);
	@dBQuery($sql) or print("mysql insert error! $sql");
	$catalogId = dBInsertId();
	$rCatalogid = "" . $catalogId;
	$workingFolder = $catalogUserPath . $rCatalogid . fs_dirsep;
	@mkdir($workingFolder, 0777);
	//echo "is_dir(" . $workingFolder . ") = " . is_dir($workingFolder) . "<br/>\r\n";
	//flush();
	
	
	logCMSChanges("catalogid" . $catalogId, "New publication [external].", $catalog["userid"]);
	logCMSChanges("userid" . $catalog["userid"], "New publication [external] " . $catalogId . ".", $logCMSChangesUniFlipUserId);
	sleep(1);
	
	
	/**
	  *  Activate trial-period !!!
	  */
	
	if (is_dir($workingFolder . "pub"))
		@removeDir($workingFolder . "pub");
	if (is_dir($workingFolder . "tmp"))
		@removeDir($workingFolder . "tmp");
	@mkdir($workingFolder . "pub", 0777);
	@mkdir($workingFolder . "pub".fs_dirsep."images", 0777);
	@mkdir($workingFolder . "pub".fs_dirsep."images".fs_dirsep."big", 0777);
	@mkdir($workingFolder . "pub".fs_dirsep."images".fs_dirsep."small", 0777);
	
	
	
	
	$urlUploadError = false;
	if ($rCatalogfile == "" && $rCatalogfileURL != "")
	{
	  	$AgetHeaders = @get_headers($rCatalogfileURL);
    	if (preg_match("|200|", $AgetHeaders[0])) 
    	{
      		$urlUploadError = false;
    	}
    	else 
    	{
      		$urlUploadError = true;
    	}
  	}
	
			
	
	if (!$urlUploadError) 
	{
		if ($rCatalogfile == "" && $rCatalogfileURL != "")
		{
			$downloadedFile = file_get_contents($rCatalogfileURL, false, null, -1, 134217728);
			//$downloadedFile = file_get_contents($rCatalogfileURL);
			file_put_contents($workingFolder . "pub".fs_dirsep."document.pdf", $downloadedFile);
			$downloadedFileSize = filesize($workingFolder . "pub".fs_dirsep."document.pdf");
			$sql = "
			update catalogs set
			pdffilesize = '" . $downloadedFileSize . "'
			where id = " . $catalogId . ";
			";
			@dBQuery($sql);
		}
		/** Language file copy */
		$dirLanguagepath = fs_rootpath . "thecutter".fs_dirsep."static_files".fs_dirsep;
		$dirLanguagepath .= "pub_languages".fs_dirsep.$rCataloglanguage.fs_dirsep;
		$dirLanguagepath .= "language.xml";
		//echo $dirLanguagepath . "\r\n";
		//echo $workingFolder . "pub".fs_dirsep."language.xml\r\n";
		@copy(
			$dirLanguagepath, 
			$workingFolder . "pub".fs_dirsep."language.xml"
		);
		/** end Language file copy */

  	
	  	$bgsoundfilesize = "0";
	  	$bgsoundfilename = "";
	  	if ($rCatalogbackgroundsound == "1") 
	  	{
	  		$bgsoundfilename = "bgsound.mp3";
	  		$bgsoundfilesize = filesize(fs_rootpath."thecutter\\static_files\\pub_common\\bgsound.mp3");
	  		@copy(
	  			fs_rootpath."thecutter\\static_files\\pub_common\\bgsound.mp3", 
	  			$workingFolder . "pub\\bgsound.mp3"
	  		);
	  	}
	
	    $sql = "\r\n";
	  	$sql .= "update catalogs set \r\n";
	  	$sql .= "	pdffilename = '" . $pdffilename . "', \r\n";
	  	$sql .= "	logofilename = '" . $logofilename . "', \r\n";
	  	$sql .= "	bgsoundfilesize = '" . $bgsoundfilesize . "', \r\n";
	  	$sql .= "	bgsoundfilename = '" . $bgsoundfilename . "', \r\n";
	  	$sql .= "	status = 0 \r\n";
	  	$sql .= "where id = " . $catalogId . "; \r\n";
	  	$sql .= "\r\n";
	  	//echo "<pre>" . $sql . "</pre>"; flush();
	  	@dBQuery($sql);
	  	
	  	if ($rCatalogfile == "" && $rCatalogfileURL != "")
	  	{
			$sql = "
			update catalogs set
			`inque` = '1'
			where id = " . $catalogId . ";
			";
			@dBQuery($sql);
	    }
	}
	else 
	{
		$sql = "
		update catalogs set
		deleted = 1
		where id = " . $catalogId . ";
		";
		@dBQuery($sql);
		//if (is_dir($workingFolder))
		//  @removeDir($workingFolder);

		echo "return:-1\r\n";
		echo $rCatalogid . "\r\n";
		echo "createCatalog\r\n";
		dbClose();
		die("");
	}
	  
	dbClose();
		
	return $rCatalogid;
}
?>