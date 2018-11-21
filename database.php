<?

function getCatalog($memberId, $catalogId, $asJson= false)
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

?>