<?php
include('sqlitedb.php');
?>


<?php
	
try {
	$numAuctionsQuery = "select count(*) as ct from Item;";
	$numAuctionsResult = $db->query($numAuctionsQuery);
	$numAuctionsRow = $numAuctionsResult->fetch();
} catch (PDOException $e) {
	  echo "Number of auctions query failed: " . $e->getMessage();
}
	
try {
	$numSoldItemsQuery = "select count(*) as ct from Item where numberOfBids > 0 and ends < '2001-12-20 00:00:01';";
	$numSoldItemsResult = $db->query($numSoldItemsQuery);
	$numSoldItemsRow = $numSoldItemsResult->fetch();
} catch (PDOException $e) {
	  echo "Number of sold items query failed: " . $e->getMessage();
}
	
$usersCounts = array();
$users = array(
	array("relation" => "Bidder", "description" => "Bidders", "index" => 0),
	array("relation" => "Seller", "description" => "Sellers", "index" => 1)
	);
	
foreach ($users as $u) {
	try {
		$numUsersQuery = "select count(*) as ct from ".$u["relation"].";";
		$numUsersResult = $db->query($numUsersQuery);
		$usersCountsRow = $numUsersResult->fetch();
		$usersCounts[] = $usersCountsRow["ct"];
	} catch (PDOException $e) {
		  echo "Number of ".$u["description"]." query failed: " . $e->getMessage();
	}
}
	
try {
	$avgSellerRatingQuery = "select avg(rating) as avg from Seller;";
	$avgSellerRatingResult = $db->query($avgSellerRatingQuery);
	$avgSellerRatingRow = $avgSellerRatingResult->fetch();
} catch (PDOException $e) {
	  echo "Average seller rating query failed: " . $e->getMessage();
}
	
try {
	$avgBidderRatingQuery = "select avg(rating) as avg from Bidder;";
	$avgBidderRatingResult = $db->query($avgBidderRatingQuery);
	$avgBidderRatingRow = $avgBidderRatingResult->fetch();
} catch (PDOException $e) {
	  echo "Average bidder rating query failed: " . $e->getMessage();
}
?>

		<div class="well" style="padding: 8px 0;">
			<ul class="nav nav-list">
				<li class="nav-header">
					Statistics
				</li>
				<li>
					<table style="min-width: 100%;">
						<tbody>
								<tr>
									<td><p class="alignleft">Auctions</p></td>
									<td><strong class="alignright"><?php echo $numAuctionsRow["ct"]; ?></strong></td>
								</tr>
								<tr>
									<td><p class="alignleft">Items Sold</p></td>
									<td><strong class="alignright"><?php echo $numSoldItemsRow["ct"]; ?></strong></td>
								</tr>
								<tr>
									<td><p class="alignleft">Total Money Transferred</p></td>
									<td><strong class="alignright">$<?php echo number_format($totalMoneyRow["totalMoney"], 0, "", ","); ?></strong></td>
								</tr>
								<?php
								foreach ($users as $u) {
								?>
									<tr>
										<td><p class="alignleft">Number of <?php echo $u ["description"] ?></p></td>
										<td><strong class="alignright"><?php echo $usersCounts[$u["index"]] ?></strong></td>
									</tr>
								<?php
									}
								?>
								<tr>
									<td><p class="alignleft">Average Seller Rating</p></td>
									<td><strong class="alignright"><?php echo number_format($avgSellerRatingRow["avg"]); ?></strong></td>
								</tr>
								<tr>
									<td><p class="alignleft">Average Bidder Rating</p></td>
									<td><strong class="alignright"><?php echo number_format($avgBidderRatingRow["avg"]); ?></strong></td>
								</tr>
						</tbody>
					</table>
				</li>			
			</ul>
		</div>