<thead>
  <tr>
     <th>Name</th>
     <th>Open/Closed</th>
	 <th>Current Price</th>
	 <th>Buy Price</th>
	 <th>Number of Bids</th>
	 <th>Action</th>
     <th>Category</th>
  </tr>
</thead>

<?php
	include ('sqlitedb.php');	
	include ('format-time.php');
?>

<?php
	$MM = $_POST["MM"];
	$dd = $_POST["dd"];
	$yyyy = $_POST["yyyy"];
	$HH = $_POST["HH"];
	$mm = $_POST["mm"];
	$ss = $_POST["ss"];    
	$user = htmlspecialchars($_POST["user"]);
	$maxPrice = intval($_POST["maxPrice"]);
	$minPrice = intval($_POST["minPrice"]);
	$openOrClosed = $_POST["openOrClosed"];
	$selectedItemID = intval($_POST["itemID"]);
	$selectedCategory = $_POST["category"];
	$selectedTime = $yyyy."-".$MM."-".$dd." ".$HH.":".$mm.":".$ss;
	if (strlen(htmlspecialchars($_POST["searchTerms"])) > 0) 
		$searchTerms = explode(" ", $_POST["searchTerms"]);
	else
		$searchTerms = array();
	echo 'Search terms are ' . implode(", ", $searchTerms);
?>

<script type="text/javascript">
function loadModalBody(bidItemID, numBids, firstBid, isBiddingOpen, currentPrice) {
	$('#bid-' + bidItemID + '-modal-body').load('bid-modal-body.php', { "itemID" : bidItemID, "numBids" : numBids, "firstBid" : firstBid, "user" : <?php echo '"'.$user.'"'?>, "isBiddingOpen": isBiddingOpen, "selectedTime" : <?php echo "'".$selectedTime."'" ?>, "currentPrice" : currentPrice});
}
</script>


<?php
function drawBidButton($bidItemID, $bidItemName, $numBids, $firstBid, $isBiddingClosed, $currentPrice) {
	$buttonTitle = ($isBiddingClosed) ? "History" : "Bid";	
	$buttonClass = ($isBiddingClosed) ? "btn" : "btn btn-primary";
	echo '<div id="wrapper" style="display:table">';
	echo '<div class="button-cell" style="display:table-cell; vertical-align:middle">';
	echo '<a class="'.$buttonClass.'" data-toggle="modal" href="#bid-modal-'.$bidItemID.'" onclick="loadModalBody('.$bidItemID.', '.$numBids.', '.$firstBid.' , '.($isBiddingClosed ? 'false' : 'true').', '.$currentPrice.')">'.$buttonTitle.'</a>
    <div class="modal fade hide" id="bid-modal-'.$bidItemID.'">
	    <div class="modal-header">
		    <button type="button" class="close done-btn" data-dismiss="modal">×</button>
		    <h3>'.$bidItemName.'</h3>
		</div>
		<div class="modal-body" id="bid-'.$bidItemID.'-modal-body">
		</div>
		<div class="modal-footer">';
	echo '<a href="#" class="btn done-btn" data-dismiss="modal">Done</a>';
	echo '</div></div></div></div>';
}
?>

<?php
function addCondition(&$oldCondition, &$newConditionFragment, &$needsAnd, &$isFirstCondition)
{
	if ($isFirstCondition)
		$oldCondition .= " where ";
	if ($needsAnd)
		 $oldCondition .= " and ";
	$oldCondition .= $newConditionFragment;
	$needsAnd = True;
	$isFirstCondition = False;
}

 $query = "select distinct itemID, name, firstBid, buyPrice, ends from Item";
 $isFirstCondition = True;
 $needsAnd = False;
 $conditions = array();
 if (strlen($_POST['itemID']) > 0)
	 $conditions[] = "Item.itemID = " . intval($_POST['itemID']);
 else {
	 if ($selectedCategory != "All Categories" && strlen($selectedCategory) > 0) 
		 $conditions[] = "itemID in (select itemID from Category where category='" . $selectedCategory . "')";
	 if ($maxPrice > 0) 
		 $conditions[] = "Item.currently =< " . $maxPrice;
	 if ($minPrice > 0)
		 $conditions[] = "Item.currently >= " . $minPrice;
	 if ($openOrClosed == "open")
		 $conditions[] = 'ends > "' . $selectedTime . '")';
	 else if ($openOrClosed == "closed") 
		 $conditions[] = 'ends <= "' . $selectedTime . '")';
	 $searchTermCondition = "";
	 $first = True;
	 foreach ($searchTerms as $searchTerm) {
		 if ($first) {
			 $first = False;
			 $searchTermCondition .= '(';
		 }
		 else {
			 $searchTermCondition .= ' or ';
		 }
		 $searchTermCondition .= 'name like "%'.$searchTerm.'%" or location like "%'.$searchTerm.'%" or country like "%'.$searchTerm.'%" or description like "%'.$searchTerm.'%"';
	 }
	 
	 if (!$first) {
		 $searchTermCondition .= ')';
	 }
	 if (strlen($searchTermCondition) > 0)
		 $conditions[] = $searchTermCondition; 
 }
 
 foreach($conditions as $conditionFragment) {
	 addCondition($condition, $conditionFragment, $needsAnd, $isFirstCondition);
 }	 
 
 $condition .= ";";
 $query .= $condition;
 
 /* Query info */
 try {
      $result = $db->query($query);
      while ($row = $result->fetch()) {
        
	  /* Current price is not the same as the "currently" attribute, because we can travel through time. */
	  	$currentPriceQuery = "select max(amount) as maxAmount from Bid where itemID = ".$row["itemID"]." and time <= '".$selectedTime."'";
	  	$currentPriceResult = $db->query($currentPriceQuery);
	  	$currentPriceRow = $currentPriceResult->fetch();
	  	$currentPrice = $currentPriceRow["maxAmount"];
	  	$currentPrice = floatval($currentPrice);
		   
		   echo "<tr></td><td>" . htmlspecialchars($row["name"]) . "</td><td>";
           $closed = strtotime($row["ends"]) <= strtotime($selectedTime);
		  
		   if ($closed) {
			   $winnerQuery = "select distinct bidderID from Bid NATURAL JOIN Bidder where itemID=" . $row["itemID"] . 
				   " and amount = (select max(amount) from Bid where itemID=".$row["itemID"].");";
			   try {
				   $winnerResult = $db->query($winnerQuery);
				   $winnerRow = $winnerResult->fetch();
				   $winner = $winnerRow["bidderID"];
				   if (strlen($winner) == 0)
					   $winner = "No bidders";
			    } catch (PDOException $e) {
				        echo "Winner query failed: " . $e->getMessage();
				}
			}
		 
		   echo ($closed ? "Closed " : "Will close ") . FormatTime($row["ends"], $selectedTime);
		   if ($closed)
			   if ($winner == "No bidders")
				   echo '. No bidders.';
			   else
				   echo '. Winner was ' . $winner;

		   echo "</td><td>" . money_format('$%i', $currentPrice) . "</td><td>";
		   if (floatval($row["buyPrice"]) > 0)
			   echo money_format('$%i', floatval($row["buyPrice"]));
		   echo "</td><td>";
		   
		   try {
			   $numBidsQuery = "select count(time) as numBids from Bid where itemID =".$row["itemID"]." and time <= '".$selectedTime."';";
			   $numBidsResult = $db->query($numBidsQuery);
			   $numBidsRow = $numBidsResult->fetch();
			   $numBids = $numBidsRow["numBids"];
			   echo "".$numBids."</td><td>";	
		   } catch (PDOException $e) {
		   	   echo "Num Bids query failed: " . $e->getMessage();
		   }
		   
		   drawBidButton($row["itemID"], $row["name"], $numBids, $row["firstBid"], $closed, $currentPrice);
		   echo "</td><td>";
           $categoryQuery = "select distinct category from Category where itemID = " . $row["itemID"];
           $categories = $db->query($categoryQuery);
		   $first = True;
		   $categoriesString = '';
           while ($categoryRow = $categories->fetch()) {
			   if (!$first) 
				   $categoriesString .= ", ";
                $categoriesString .= $categoryRow["category"];
				$first = False;
           }
           echo "" . $categoriesString . "</td></tr>";
      }
 } catch (PDOException $e) {
      echo "Item query or current price query failed: " . $e->getMessage();
 }
?>

