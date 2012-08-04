<?php
include ("./_header.php");
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
		$numUsersQuery = "select count(*) as ct from (select bidderID from Bidder UNION select sellerID from Seller);";
		$numUsersResult = $db->query($numUsersQuery);
		$numUsersRow = $numUsersResult->fetch();
	} catch (PDOException $e) {
		  echo "Number of users query failed: " . $e->getMessage();
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

	<div class="span8">
		<div class="well">
			<center><a href="https://flavors.me/roseperrone">Rose Perrone</a> made this site for <a href="http://www.stanford.edu/class/cs145/">CS145, Databases</a></center>
		</div>
	</div>
	<div class="span4">
		<div class="well" style="padding: 8px 0;">
			<ul class="nav nav-list">
				<li class="nav-header">
					Statistics
				</li>
				<li>
					<table style="min-width: 100%;">
						<tbody>
							<div style="clear: both; "> <!-- Clearing the float. -->
								<tr>
									<td><p class="alignleft">Number of Auctions</p></td>
									<td><strong class="alignright"><?php echo $numAuctionsRow["ct"]; ?></strong></td>
								</tr>
								<tr>
									<td><p class="alignleft">Number of Users</p></td>
									<td><strong class="alignright"><?php echo $numUsersRow["ct"]; ?></strong></td>
								</tr>
								<tr>
									<td><p class="alignleft">Average Seller Rating</p></td>
									<td><strong class="alignright"><?php echo number_format($avgSellerRatingRow["avg"]); ?></strong></td>
								</tr>
								<tr>
									<td><p class="alignleft">Average Bidder Rating</p></td>
									<td><strong class="alignright"><?php echo number_format($avgBidderRatingRow["avg"]); ?></strong></td>
								</tr>
								<tr>
									<td><p></p></td>
									<td><strong></strong></td>
								</tr>
								<tr>
									<td><p></p></td>
									<td><strong></strong></td>
								</tr>
							</div>
						<!--	
					<strong>Number of auctions</strong></li>
				<li><strong>Number of users</strong></li>
				<li><strong>Number of sellers</strong></li>
				<li><strong>Average seller rating</strong>
				-->
						</tbody>
					</table>
				</li>
			</ul>
		</div>
	</div>
	
<?php
include("./_footer.php");
?>
	