<?php
include ('./sqlitedb.php');	
?>

<?php
	try {
		$query = 'select bidderID, date(time) as dateTime, time, amount, rating from Bid NATURAL JOIN Bidder where itemID = '.$_REQUEST["itemID"].' and dateTime <= "'. $_REQUEST["selectedTime"] .'" order by amount desc';
		$result = $db->query($query);
		$firstTime = True;
		$topBidder = False;
		while ($row = $result->fetch()) {
			if ($firstTime) {
				echo '<table class="table table-striped">
						<thead>
							<tr>
								<th>Amount</th>
								<th>Date</th>
								<th>Bidder</th>
							</tr>
						</thead>';
				$firstTime = False;
				// the top user will be listed in the first row
				
			    echo '<p>user row is: '. $row["bidderID"] . '</p>';
                echo '<p>user request is: "'. $_REQUEST["user"] . '"</p>';
				
				
				if ($row["bidderID"] == $_REQUEST["user"]) {
					$topBidder = True;
				}
			}
			echo '<tr><td>'.money_format('$%i', floatval($row['amount'])).'</td><td>'.$row['time'].'</td><td><span style="color:rgb(0, 136, 204)" rel="tooltip" class="tip" title="Rating: '.$row['rating'].'">'.$row['bidderID'].'</span></td>';


		}	
		if ($firstTime)
			echo '<p>No one bid for this item.</p>';

		if ($topBidder) {
			echo '<div class="alert alert-success">You are the top bidder!</div>';
			if ($_REQUEST["user did bid"] == "True") {
				echo '<script type="text/javascript">
					$(document).ready(function() {
						// make the items table reload when the dismiss button is clicked
						alert("this code runs.");
						
						$(".done-btn").click(function() {
							$("#filter-submit-button").click();
						});
					}); 
					</script>';
			}
		}
	} catch (PDOException $e) {
		echo "Bid history query failed: " . $e->getMessage();
	}
?>

<script type="text/javascript">
jQuery( function($) {
    $(".tip").tooltip();
});
</script>

</table>
