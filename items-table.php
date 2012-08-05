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
include ('./sqlitedb.php');	
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
?>

<script type="text/javascript">
function loadModalBody(bidItemID, numBids, isBiddingOpen) {
	$('#bid-' + bidItemID + '-modal-body').load('bid-modal-body.php', { "itemID" : bidItemID, "numBids" : numBids, "user" : <?php echo '"'.$user.'"'?>, "isBiddingOpen": isBiddingOpen, "selectedTime" : <?php echo "'".$selectedTime."'" ?> });
}
</script>


<?php
function drawBidButton($bidItemID, $bidItemName, $numBids, $isBiddingClosed) {
	$buttonTitle = ($isBiddingClosed) ? "History" : "Bid";	
	$buttonClass = ($isBiddingClosed) ? "btn" : "btn btn-primary";
	echo '<div id="wrapper" style="display:table">';
	echo '<div class="button-cell" style="display:table-cell; vertical-align:middle">';
	echo '<a class="'.$buttonClass.'" data-toggle="modal" href="#bid-modal-'.$bidItemID.'" onclick="loadModalBody('.$bidItemID.', '.$numBids.', '.($isBiddingClosed ? 'false' : 'true').')">'.$buttonTitle.'</a>
    <div class="modal fade hide" id="bid-modal-'.$bidItemID.'">
	    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal">×</button>
		    <h3>'.$bidItemName.'</h3>
		</div>
		<div class="modal-body" id="bid-'.$bidItemID.'-modal-body">
		</div>
		<div class="modal-footer">';
	echo '<a href="#" class="btn" data-dismiss="modal">Done</a';
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

 $query = "select distinct itemID, name, currently, buyPrice, ends from Item";
 $isFirstCondition = True;
 $needsAnd = False;
 $conditions = array();
 if (strlen($_POST['itemID']) > 0)
	 $conditions[] = "Item.itemID = " . intval($_POST['itemID']);
 else {
	 if ($selectedCategory != "All Categories") 
		 $conditions[] = "itemID in (select itemID from Category where category='" . $selectedCategory . "')";
	 if ($maxPrice > 0) 
		 $conditions[] = "Item.currently =< " . $maxPrice;
	 if ($minPrice > 0)
		 $conditions[] = "Item.currently >= " . $minPrice;
	 if ($openOrClosed == "open")
		 $conditions[] = 'ends > "' . $selectedTime . '")';
	 else if ($openOrClosed == "closed") 
		 $conditions[] = 'ends <= "' . $selectedTime . '")';
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
				   echo '. Winner was' . $winner;
		   
		   echo "</td><td>" . money_format('$%i', floatval($row["currently"])) . "</td><td>";
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
		   
		   drawBidButton($row["itemID"], $row["name"], $numBids, $closed);
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
      echo "Item query failed: " . $e->getMessage();
 }
?>

<?php
function FormatTime($timestamp, $currentTime)
{
	$timestamp = strtotime($timestamp);
	
	// Get time difference and setup arrays
	$difference = strtotime($currentTime) - $timestamp;
	
	$periods = array("second", "minute", "hour", "day", "week", "month", "years");
	$lengths = array("60","60","24","7","4.35","12");
 
	// Past or present
	if ($difference >= 0) 
	{
		$ending = "ago";
	}
	else
	{
		$difference = -$difference;
		$ending = "to go";
	}
 
	// Figure out difference by looping while less than array length
	// and difference is larger than lengths.
	$arr_len = count($lengths);
	for($j = 0; $j < $arr_len && $difference >= $lengths[$j]; $j++)
	{
		$difference /= $lengths[$j];
	}
 
	// Round up		
	$difference = round($difference);
 
	// Make plural if needed
	if($difference != 1) 
	{
		$periods[$j].= "s";
	}
 
	// Default format
	$text = "$difference $periods[$j] $ending";
 
 
	// over 24 hours
	if($j > 2)
	{
		// future date over a day formate with year
		if($ending == "to go")
		{
			if($j == 3 && $difference == 1)
			{
				$text = "tomorrow at ". date("g:i a", $timestamp);
			}
			else
			{
				$text = date("F j, Y \a\\t g:i a", $timestamp);
			}
			return $text;
		}
 
		if($j == 3 && $difference == 1) // Yesterday
		{
			$text = "yesterday at ". date("g:i a", $timestamp);
		}
		else if($j == 3) // Less than a week display -- Monday at 5:28pm
		{
			$text = date("l \a\\t g:i a", $timestamp);
		}
		else if($j < 6 && !($j == 5 && $difference == 12)) // Less than a year display -- June 25 at 5:23am
		{
			$text = date("F j \a\\t g:i a", $timestamp);
		}
		else // if over a year or the same month one year ago -- June 30, 2010 at 5:34pm
		{
			$text = date("F j, Y \a\\t g:i a", $timestamp);
		}
	}
 
	return $text;
}
	
	
?>

