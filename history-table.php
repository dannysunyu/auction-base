<?php
include ('./sqlitedb.php');	
?>

<?php
//if ($_REQUEST["numBids"] > 0) {
?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Amount</th>
				<th>Date</th>
				<th>Bidder</th>
			</tr>
		</thead>
<?php
//}
?>

<?php
	try {
		$query = 'select bidderID, date(time) as dateTime, time, amount, rating from Bid NATURAL JOIN Bidder where itemID = '.$_REQUEST["itemID"].' and dateTime <= "'. $_REQUEST["selectedTime"] .'" order by time';
		$result = $db->query($query);
		while ($row = $result->fetch()) {
			echo '<tr><td>'.money_format('$%i', floatval($row['amount'])).'</td><td>'.$row['time'].'</td><td><span style="color:rgb(0, 136, 204)" rel="tooltip" class="tip" title="Rating: '.$row['rating'].'">'.$row['bidderID'].'</span></td>';
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
