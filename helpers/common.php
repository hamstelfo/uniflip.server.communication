<?

function showAsLink($a)
{
	pinta("<a href='".$a."' target='_blank'>".$a."</a>");
}

function linkIfLocalhost($a, $byCutter= false)
{
	//pinta($_SERVER);
	if ($_SERVER["HTTP_HOST"]=="localhost" || $byCutter)
	{
		pinta("Next link [should be manual in locahost testing]: ");
		showAsLink($a);
	}
	else
	{
		pinta("Next link [auto]: ");
		pinta($a);
	}
}

// Here we can only control LINUX operations.
function updateLog($memberId, $catalogIdLinux, $catalogIdCutter, $step, $extra= "")
{
	$testLogId= getRequest("testLogId");
	dbOpen();
	$sep= " ";
	$timeStamp= date("Y-m-d H:i:s");
	$val= $extra . $sep . $timeStamp . $sep . " catalogIdLinux=".$catalogIdLinux . $sep;

	if ($catalogIdCutter)
	{
		$catalogIdCutter= ", `catalogIdCutter`= '".$catalogIdCutter."'";
	}

	switch ($step) {
		case 'start_cutting_process':			
			$sql= "INSERT INTO `cutter_logs` (`memberId`, `catalogIdLinux`, `start_cutting_process`) VALUES ('".$memberId."', '".$catalogIdLinux."', '".$val."') \r\n";
			break;

		default:
			if ($testLogId)
			{
				$sql= "UPDATE `cutter_logs` SET `".$step."`= '".$val."' ".$catalogIdCutter." WHERE `id`='".$testLogId."' \r\n";
			}
			else
			{
				$sql= "UPDATE `cutter_logs` SET `".$step."`= '".$val."' ".$catalogIdCutter." WHERE `catalogIdLinux`='".$catalogIdLinux."' \r\n";
			}

			break;
	}
	
	$result = dBQuery($sql) or die("Query failed: " . dBError());
	dBFreeResult($result);
	dbClose();
}

function urlTest()
{
	if ($testLogId= getRequest("testLogId"))
	{
		return "&testLogId=".$testLogId;
	}

	return "";
}

function fileGetContents($url)
{
	if (USE_CURL)
	{
		echo "*** using curl ***";
		return curl_post($url);
	}
	else
	{
		echo "*** using file_get_contents ***";
		return file_get_contents($url);
	}
	
}

function curl_post($url, array $post = array(), array $options = array()) 
{ 
    $defaults = array( 
        CURLOPT_POST => 1, 
        CURLOPT_HEADER => 0, 
        CURLOPT_URL => $url, 
        CURLOPT_FRESH_CONNECT => 1, 
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_FORBID_REUSE => 1, 
        CURLOPT_TIMEOUT => 0, 
        CURLOPT_POSTFIELDS => http_build_query($post) 
    ); 

    $ch = curl_init(); 
    curl_setopt_array($ch, ($options + $defaults)); 
    if( ! $result = curl_exec($ch)) 
    { 
        trigger_error(curl_error($ch)); 
        echo "Error url (post): ".$url;
    } 
    curl_close($ch); 
    return $result; 
} 

function curl_get($url, array $get = array(), array $options = array()) 
{    
    $defaults = array( 
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
        CURLOPT_HEADER => 0, 
        CURLOPT_RETURNTRANSFER => TRUE, 
        CURLOPT_TIMEOUT => 4 
    ); 
    
    $ch = curl_init(); 
    curl_setopt_array($ch, ($options + $defaults)); 
    if( ! $result = curl_exec($ch)) 
    { 
        trigger_error(curl_error($ch)); 
        echo "Error url (get): ".$url;
    } 
    curl_close($ch); 
    return $result; 
} 

function zipFile($file)
{
	$zipName= $file.'.zip';
	$zip = new ZipArchive;
	if ($zip->open($zipName, ZipArchive::CREATE) === TRUE)
	{
	    // Add files to the zip file
	    if (!$zip->addFile($file))
	    {
	    	return false;
	    }

	    // All files are added, so close the zip file.
	    $zip->close();

	    return $zipName;
	}

	return false;
}

function unZipFile($file)
{
	$zipName= $file.'.zip';
	$zip = new ZipArchive;
	if ($zip->open($zipName) === TRUE)
	{
	    // Add files to the zip file
	    if (!$zip->extractTo($file))
	    {
	    	return false;
	    }
	    // All files are added, so close the zip file.
	    $zip->close();

	    return $file;
	}

	return false;
}
?>