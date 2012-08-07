<?php
include('sqlitedb.php');
?>

<?php
	
	
	// the item is sold, so numberOfBids > 0
	// the bidder won the item, so the amount for the bid is equal to the currently for the item
	// group by bidderID and get the sum of the amounts, and return the number of bidders within the range of amounts.
	
    $userTypes = array(
				array("relation" => "Seller", "name" => "Sellers", "id name" => "sellerID", "title" => "Amount Sellers Earn", "relation fragment" => "", "index" => 0), 
				array("relation" => "Bidder", "name" => "Bidders", "id name" => "bidderID", "title" => "Amount Bidders Spend", "relation fragment" => "Bid natural join", "index" => 1)
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
	try {
		$totalMoneyQuery = "select SUM(soldPrice) as totalMoney 
			                  from (
		                             select MAX(currently) as soldPrice
								     from Item
			                         where numberOfBids > 0 and ends < '2001-12-20 00:00:01'
								     group by itemID
								 );";
		$totalMoneyResult = $db->query($totalMoneyQuery);
		$totalMoneyRow = $totalMoneyResult->fetch();
	} catch (PDOException $e) {
		  echo "Total money query failed: " . $e->getMessage();
	}
?>	
	
<?php
	$leftDiv = True;
	foreach ($userTypes as $user) {
		$style = ($leftDiv) ? 'style="margin-left: 22px;"' : "";
		echo '
		<div class="span2" '.$style.'>
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
		$leftDiv = False;
	}
?>


