<?

define("STATUS_LINUX_CUTTER_0", "Starting Cutter communication");
define("STATUS_LINUX_CUTTER_1", "Sending info to the Cutter");
define("STATUS_LINUX_CUTTER_2", "Sending file to the Cutter");
define("STATUS_LINUX_CUTTER_3", "Cutter is processing the catalog");

function getCatalogInfo($memberId, $catalogId, $asJson= false)
{
	dbOpen();
	$sql= "SELECT * FROM `catalogs` WHERE `id` = '".$catalogId."' AND `userid`='".$memberId."' \r\n";
	$result = dBQuery($sql) or die("Query failed: " . dBError());
	if ($row = dBFetchArray($result, MYSQL_ASSOC)) {
		//$userDateCreated = $row["date_created"];			
	}

	dBFreeResult($result);

	if ($asJson)
	{
		return json_encode($row);
	}
	else
	{
		return $row;
	}

	dbClose();
}


function updateCatalogPDFInfo($memberId, $catalogId, $pdfInfo, $stopProccessByTest= false)
{
	dbOpen();
	$sql= "UPDATE `catalogs` SET `pdfinfo` = '".$pdfInfo."' WHERE `id` = '".$catalogId."' AND `userid`='".$memberId."' \r\n";
	$result = dBQuery($sql) or die("Query failed: " . dBError());
	dbClose();

	if ($stopProccessByTest)
	{
		die("Process stopped for testing purposes.");
	}

	return $result;
}

?>