<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1090797 > PC Ofi, 1090722 > PC Casa
header("Location: http://localhost/uniflip.server.communication/start_cutting_process.php?catalogid=1090722&memberid=86855&file=test2.upl");
die();
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function()
{
  $("button").click(function()
  {
    $.ajax({
    	url: "demo_ajax_load.txt", 
    	async: false, 
    	success: function(result)
    					{
      					$("div").html(result);
    					}
    			});
  });
});
</script>
</head>
<body>

<h2>vaos a trocear el proceso en llamadas ajax mejor, pq se keda pillao y no sabemos q pasa</h2>

<p>This is another paragraph.</p>

<button onclick="">Click to launch the 1st step</button>

</body>
</html>