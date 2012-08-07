<?php
include ('sqlitedb.php');	
include ('format-time.php');

try {
	$itemQuery = 'select sellerID, rating, description, Item.location, Item.country, firstBid, started, ends from Item NATURAL JOIN Seller where itemID = '. $_POST["itemID"];	
	$itemResult = $db->query($itemQuery);
	$item = $itemResult->fetch();
} catch (PDOException $e) {
	echo "Bid item query failed: " . $e->getMessage();
}
echo '<div style="width:46%; float:left; padding:15px;">';

	echo '<p><strong>Seller   </strong> <span rel="tooltip" class="tip" style="color: rgb(0, 136, 204); display: inline" title="Rating: '.$item["rating"].'">'.$item["sellerID"].' </span>';
	echo '<p><strong>Description  </strong> '.$item["description"].'</p>';
	echo '<p><strong>Location </strong> '.$item["location"].', '.$item["country"].'</p>';
	echo '<p><strong>Item ID </strong> '.$_POST["itemID"].'</p>';
	echo '<p><strong>Started   </strong>'.FormatTime($item["started"], $_POST["selectedTime"]).'</p>';
	$isBiddingOpen = $_POST["isBiddingOpen"] == "true";
	$endsTitle = ($isBiddingOpen) ? "Ends " : "Ended ";
	echo '<p><strong>'.$endsTitle.'  </strong>'.FormatTime($item["ends"], $_POST["selectedTime"]).'</p>';
	if ($isBiddingOpen)
		echo '<p><strong>Starting Bid </strong>'.money_format('$%i', floatval($item["firstBid"])).'</p>';
	else
		echo '<p>Bidding is closed. </p>';
?>
</div>
<div style="width:47%; float:right; padding:15px;">
	<form class="well form-inline" id="bid-form" action="#" method="post">
		<span class="input-prepend input-append"><span class="add-on">$</span><input name="bid" id="bid" class="span2" type="text" size="160" placeholder="Bid" /><span class="add-on">.00</span>&nbsp;&nbsp;&nbsp;&nbsp;
			<button type="submit" class="btn">Bid Now</button>
		</span>
    </form>

<div id="bid-alert"></div>
<div id="history-table"></div>
<div id="alert-container"></div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#history-table').load('history-table.php', { "itemID" : <?php echo $_POST["itemID"] ?>, "numBids" : <?php echo $_POST["numBids"]?>, "selectedTime" : <?php echo "'".$_POST["selectedTime"]."'" ?>, "user" : <?php echo "'".$_POST["user"]."'" ?> , "firstBid" : (<?php echo intval($_POST["firstBid"]) ?>) });
    });

	$('#bid-form').live('submit', function(e) {
		e.preventDefault();
		var postBidData = { "itemID" : <?php echo $_POST["itemID"] ?>, 
					"numBids" : <?php echo $_POST["numBids"]?>, 
					"user" : <?php echo "'".$_POST["user"]."'" ?>, 
					"bid" : $('#bid').val(), 
					"currentPrice" : <?php echo $_POST["currentPrice"]?>,
					"selectedTime" : <?php echo "'".$_POST["selectedTime"]."'" ?> };
		
		// I would use getJson, but I need an asynchronous request. 						
		$.ajax({ 
			url: "post-bid.php",
			type: "POST",
			data: postBidData,
			success: function(ret) {
				var response = $.parseJSON(ret);				
	           if (response.result != "success")
				   $html = '<div class="alert alert-error">' + response.warnings + '</div>'
			   else
				   $html = "";
				$('#bid-alert').html($html);
			    
			   },
			async: false
		});
		 
		var historyTableData = 	{ "itemID" : <?php echo $_POST["itemID"] ?>, 
					"user" : <?php echo "'".$_POST["user"]."'" ?>, 
					"selectedTime" : <?php echo "'".$_POST["selectedTime"]."'" ?>, 
					"didBid" : "True" };

		$('#history-table').load('history-table.php', historyTableData);
		return false;
	});

	jQuery( function($) {
    	$("a.tip").tooltip();
	});
</script>

