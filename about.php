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
		$numSoldItemsQuery = "select count(*) as ct from Item where numberOfBids > 0 and ends < '2001-12-20 00:00:01';";
		$numSoldItemsResult = $db->query($numSoldItemsQuery);
		$numSoldItemsRow = $numSoldItemsResult->fetch();
	} catch (PDOException $e) {
		  echo "Number of sold items query failed: " . $e->getMessage();
	}
	
	$usersCounts = array();
	$users = array(
		array("relation" => "Bidder", "description" => "bidders", "count" => 0),
		array("relation" => "Seller", "description" => "sellers", "count" => 0)
		);
	
	foreach ($users as $u) {
		try {
			$numUsersQuery = "select count(*) as ct from ".$u["relation"].";";
			$numUsersResult = $db->query($numUsersQuery);
			$u["count"] = $numUsersResult->fetch();
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
	
	
	for ($i = 0; $i < 20; $i++) {
		$priceRanges[] = array(10*$i, 10*($i + 1));
	}
	$priceRanges[] = array(200, 100000);
	
//	$priceRanges = array(array(0, 10), array(10, 20), array(20, 30), array(30, 40), array(40, 50), array array(100,9999999));
	$priceRangeCounts = array();
	
	for ($i = 0; $i < count($priceRanges); $i++) {
		try {
			$priceRangeQuery = "select count(*) as ct from Item where numberOfBids > 0 and ends < '2001-12-20 00:00:01' and currently >= ". $priceRanges[$i][0]." and currently < ". $priceRanges[$i][1].";";
			//echo '<p style="color:purple;">Query is '.$priceRangeQuery.'.</p>';
			$priceRangeResult = $db->query($priceRangeQuery);
			$priceRangeRow = $priceRangeResult->fetch();
			$priceRangeCounts[] = $priceRangeRow["ct"];
		} catch (PDOException $e) {
			  echo "Price range query failed: " . $e->getMessage();
		}
	}
	/*
	for ($i = 0; $i < count($priceRangeCounts); $i++) {
		echo '<p style="color: orange">Price range count is: '.$priceRangeCounts[$i].'</p>';
	}
	*/
	
	// the item is sold, so numberOfBids > 0
	// the bidder won the item, so the amount for the bid is equal to the currently for the item
	// group by bidderID and get the sum of the amounts, and return the number of bidders within the range of amounts.
	
    $userTypes = array(
				array("relation" => "Seller", "name" => "Sellers", "title" => "Amount Sellers Earn", "relation fragment" => "", "index" => 0), 
				array("relation" => "Bidder", "name" => "Bidders", "title" => "Amount Bidders Spend", "relation fragment" => "Bid natural join", "index" => 1)
			);
	
	$userSumCounts = array(array(), array());
	
	foreach ($userTypes as $user) {
		$userRanges = array();
		for ($i = 0; $i < 20; $i++) {
			$userRanges[] = array(10*$i, 10*($i + 1));
		}
		$userRanges[] = array(20, 100);

		for ($i = 0; $i < count($userRanges); $i++) {
			try {
				$userSumQuery = '
					
					select count(*) as ct
					from (
					     select '.$user["id name"].', sum(soldPrice) as userSum
					     from (
					          select ' .$user["id name"]. ', MAX(currently) as soldPrice
					          from '.$user["relation fragment"].' Item
					          where numberOfBids > 0 and ends < "2001-12-20 00:00:01"
					          group by itemID
					          )
					     group by '.$user["id name"].'
					) as Sums
					where Sums.userSum >= '.$userRanges[$i][0].' and Sums.userSum < '.$userRanges[$i][1].';';
							  //echo '<p style="color:green;">Query is '.$userSumQuery.'.</p>';
							                     $userSumResult = $db->query($userSumQuery);
							                     $userSumRow = $userSumResult->fetch();
							                     $userSumCounts[$user["index"]][] = $userSumRow["ct"];
								//echo '<p style="color:green;">Sum count is '.$userSumRow["ct"].'.</p>';				 
					
			} catch (PDOException $e) {
				  echo "User sum query failed: " . $e->getMessage();
			}
		}
		/*
		for ($i = 0; $i < count($user["sum counts"]); $i++) {
			echo '<p style="color: orange">Sum count is: '.$user["sum counts"][$i].'</p>';
		}
		*/
	}
	/*
	foreach ($userTypes as $user) {
		for ($i = 0; $i < count($userSumCounts[$user["index"]]); $i++) {
			echo '<p style="color: orange">Sum count is: '.$userSumCounts[$user["index"]][$i].'</p>';
		}
	}
	*/
	

	
?>

	<div class="span2">
		<div class="well">
			<center><a href="https://flavors.me/roseperrone">Rose Perrone</a> made this site for <a href="http://www.stanford.edu/class/cs145/">CS145, Databases</a></center>
		</div>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0;">
			<ul class="nav nav-list">
				<li class="nav-header">
					Statistics
				</li>
				<li>
					<table style="min-width: 100%;">
						<tbody>
								<tr>
									<td><p class="alignleft">Auctions between Nov-Dec 2001</p></td>
									<td><strong class="alignright"><?php echo $numAuctionsRow["ct"]; ?></strong></td>
								</tr>
								<tr>
									<td><p class="alignleft">Items Sold between Nov-Dec 2001</p></td>
									<td><strong class="alignright"><?php echo $numSoldItemsRow["ct"]; ?></strong></td>
								</tr>
								<?php
								foreach ($users as $u) {
								?>
									<tr>
										<td><p class="alignleft"><?php echo $u["name"] ?></p></td>
										<td><strong class="alignright"><?php echo $u["count"]; ?></strong></td>
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
	</div>
	<div class="span2">
		<div class="well" style="padding: 8px 0;">
			<ul class="nav nav-list">
				<li class = "nav-header">
					Sold Items by Price
				</li>
				<li>
					<table style="min-width: 100%;">
						<tbody>
							<?php
								for ($i = 0; $i < count($priceRangeCounts); $i++) {
									echo '<tr>
											<td><p class="alignleft">$'.$priceRanges[$i][0].' - $'. $priceRanges[$i][1] .'</p></td>
											<td><strong class="alignright">'.$priceRangeCounts[$i].'</strong></td>
										</tr>';
								}			
							?>
							<tr>
								<td><p></p></td>
								<td><strong></strong></td>
							</tr>
							<tr>
								<td><p></p></td>
								<td><strong></strong></td>
							</tr>
						</tbody>
					</table>
				</li>
			</ul>
		</div>
	</div>
	
	<?php
		foreach ($userTypes as $user) {
			echo '
			<div class="span2">
				<div class="well" style="padding: 8px 0;">
					<ul class="nav nav-list">
						<li class = "nav-header">'.
							$user["title"]
						.'</li>
						<li>
							<table style="min-width: 100%;">
								<tbody>';
										for ($i = 0; $i < count($userRanges); $i++) {
											echo '<tr>
													<td><p class="alignleft">$'.$userRanges[$i][0].' - $'. $userRanges[$i][1] .'</p></td>
													<td><strong class="alignright">'.$userSumCounts[$user["index"]][$i].'</strong></td>
												</tr>';
										}			
									echo'<tr>
										<td><p></p></td>
										<td><strong></strong></td>
									</tr>
									<tr>
										<td><p></p></td>
										<td><strong></strong></td>
									</tr>
								</tbody>
							</table>
						</li>
					</ul>
				</div>
			</div>';
		}
	?>

	
<?php
include("./_footer.php");
?>
	