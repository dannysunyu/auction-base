<?php
include ('sqlitedb.php');	
include ('format-time.php');
?>

<?php
try {
	$itemQuery = 'select sellerID, rating, description, Item.location, Item.country, firstBid, started, ends from Item NATURAL JOIN Seller where itemID = '. $_REQUEST["itemID"];	
	$itemResult = $db->query($itemQuery);
	$item = $itemResult->fetch();

	echo '<p><strong>Seller   </strong> <span rel="tooltip" class="tip" style="color: rgb(0, 136, 204); display: inline" title="Rating: '.$item["rating"].'">'.$item["sellerID"].' </span>';
	echo '<p><strong>Description  </strong> '.$item["description"].'</p>';
	echo '<p><strong>Location </strong> '.$item["location"].', '.$item["country"].'</p>';
	echo '<p><strong>Item ID </strong> '.$_REQUEST["itemID"].'</p>';
	echo '<p><strong>Started   </strong>'.FormatTime($item["started"], $_REQUEST["selectedTime"]).'</p>';
	$isBiddingOpen = $_REQUEST["isBiddingOpen"] == "true";
	$endsTitle = ($isBiddingOpen) ? "Ends " : "Ended ";
	echo '<p><strong>'.$endsTitle.'  </strong>'.FormatTime($item["ends"], $_REQUEST["selectedTime"]).'</p>';
	if ($isBiddingOpen) {
		echo '<p><strong>Starting Bid </strong>'.money_format('$%i', floatval($item["firstBid"])).'</p>';
		echo '<form class="well form-inline" id="bid-form" action="#" method="post">
			<span class="input-prepend input-append"><span class="add-on">$</span><input name="bid" id="bid" class="span2" type="text" size="80" placeholder="Your Bid" /><span class="add-on">.00</span>&nbsp;&nbsp;&nbsp;&nbsp;
	    <button type="submit" class="btn">Bid Now</button>
		</span>
	    </form>';
	}
	else {
		echo '<p>Bidding is closed. </p>';
	}
} catch (PDOException $e) {
	echo "Bid item query failed: " . $e->getMessage();
}
?>

<div id="bid-alert"></div>
<div id="history-table"></div>
<div id="alert-container"></div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#history-table').load('history-table.php', { "itemID" : <?php echo $_REQUEST["itemID"] ?>, "numBids" : <?php echo $_REQUEST["numBids"]?>, "selectedTime" : <?php echo "'".$_REQUEST["selectedTime"]."'" ?>, "user" : <?php echo "'".$_REQUEST["user"]."'" ?> , "firstBid" : (<?php echo intval($_REQUEST["firstBid"]) ?>) });
    });

	$('#bid-form').live('submit', function(e) {
		e.preventDefault();
		//alert('The user is ' + <?php echo "'".$_REQUEST["user"]."'" ?>);
		//alert('The bid is ' + $('#bid').val());
		/*
		if (intval(<?php echo $_REQUEST["numBids"]?>) == 0) {
			$minAmount = 
		}
		
		
		if (floatval($('#bid').val()) > 
		*/
			var data = { "itemID" : <?php echo $_REQUEST["itemID"] ?>, "numBids" : <?php echo $_REQUEST["numBids"]?>, "user" : <?php echo "'".$_REQUEST["user"]."'" ?>, "bid" : $('#bid').val(), "selectedTime" : <?php echo "'".$_REQUEST["selectedTime"]."'" ?>, "didBid" : "True" };
		//$(document).ready(function(){
			//$('#alert-container').load("post-bid.php", data, function() { alert('Load was performed.'); });

		    $.getJSON("post-bid.php", data, function(response){
	               if (response["result"] == "success")
					   $('#bid-alert').html('<div class="alert alert-success">You are now the top bidder.</div>');
				   else
					   $('#bid-alert').html('<div class="alert alert-error">An error occurred. The bid was not properly inserted into the database.</div>');
	           });

			$('#history-table').load('history-table.php', data); //{ "itemID" : <?php echo $_REQUEST["itemID"] ?>, "selectedTime" : <?php echo "'".$_REQUEST["selectedTime"]."'" ?> });
		return false;
	});

	jQuery( function($) {
    	$("a.tip").tooltip();
	});
</script>

