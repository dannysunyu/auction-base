<thead>
  <tr>
	 <th>Item ID</th>
     <th>Name</th>
     <th>Open/Closed</th>
	 <th>Current Price</th>
	 <th>Number of Bids</th>
     <th>Winner</th>
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
	$("#query-info").children().remove()
</script>

<script type="text/javascript">
function loadModalBody(bidItemID, numBids) {
	$('#bid-' + bidItemID + '-modal-body').load('bid-modal-body.php', { "itemID" : bidItemID, "numBids" : numBids, "user" : <?php echo '"'.$user.'"' ?> });
}
</script>

<?php
function drawBidButton($bidItemID, $bidItemName, $numBids) {
	echo
	'<a class="btn" data-toggle="modal" href="#bid-modal-'.$bidItemID.'" onclick="loadModalBody('.$bidItemID.', '.$numBids.')">Bid</a>
    <div class="modal fade hide" id="bid-modal-'.$bidItemID.'">
	    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal">×</button>
		    <h3>'.$bidItemName.'</h3>
		</div>
		<div class="modal-body" id="bid-'.$bidItemID.'-modal-body">
		</div>
		<div class="modal-footer">
		    <a href="#" class="btn" data-dismiss="modal">Cancel</a>
		    <a href="#" class="btn btn-primary">Bid</a>
	    </div>
    </div>';
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

 $query = "select distinct itemID, name, currently, date(ends) as date_ends from Item";
 $isFirstCondition = True;
 $needsAnd = False;
 $conditions = array();
 if (strlen($_POST['itemID']) > 0)
	 $conditions[] = "Item.itemID = " . intval($_POST['itemID']);
 else {
	 if ($selectedCategory != "All Categories") 
		 $conditions[] = "itemID in (select itemID from Category where category=" . $selectedCategory . ")";
	 if ($maxPrice > 0) 
		 $conditions[] = "Item.currently =< " . $maxPrice;
	 if ($minPrice > 0)
		 $conditions[] = "Item.currently >= " . $minPrice;
	 if ($openOrClosed == "open")
		 $conditions[] = 'date_ends > date("' . $selectedTime . '")';
	 else if ($openOrClosed == "closed") 
		 $conditions[] = 'date_ends < date("' . $selectedTime . '")';
 }
 
 foreach($conditions as $conditionFragment) {
	 addCondition($condition, $conditionFragment, $needsAnd, $isFirstCondition);
 }
 
 $condition .= ";";
 $query .= $condition;
 
 /* Query info */
	 
	echo "<script type='text/javascript'> $('#query-info').append('<h3>Query</h3><div class=\"well\">". htmlspecialchars($query) . "</div>')</script>";
 
 	$HTML = '<h3>Parameters</h3><div class="well">';
 	foreach ($_POST as $key => $entry)
 	{
 	     if (is_array($entry)) {
 	        foreach($entry as $value)
    			 $HTML .= $key .": " . $value . ", ";
 	     } else {
 			 $HTML .= $key .": " . $entry . ", ";
 	     }
 	}
	$HTML = substr($HTML, 0, -2);
 	$HTML .= '</div>';
 	echo "<script type='text/javascript'> $('#query-info').append('".$HTML."')</script>";

 try {
      $result = $db->query($query);
      while ($row = $result->fetch()) {
          echo "<tr><td>" . $row["itemID"];
           echo "</td><td>" . htmlspecialchars($row["name"]) . "</td><td>";
           $closed = strtotime($row["date_ends"]) < strtotime(date($selectedTime));
		   echo ($closed ? "Closed on " : "Will close on ") . $row["date_ends"];
		   echo "</td><td>" . money_format('$%i', floatval($row["currently"])) . "</td><td>";
		   try {
			   $numBidsQuery = "select count(time) as numBids from Bid where itemID =".$row["itemID"]." and time < '".$selectedTime."';";
			   $numBidsResult = $db->query($numBidsQuery);
			   $numBidsRow = $numBidsResult->fetch();
			   $numBids = $numBidsRow["numBids"];
			   echo "".$numBids."</td><td>";	
		   } catch (PDOException $e) {
		   	   echo "Num Bids query failed: " . $e->getMessage();
		   }
		   if ($closed) {
			   $winnerQuery = "select distinct bidderID from Bid NATURAL JOIN Bidder where itemID=" . $row["itemID"] . 
				   " and amount = (select max(amount) from Bid where itemID=".$row["itemID"].");";
			   try {
				   $winnerResult = $db->query($winnerQuery);
				   $winnerRow = $winnerResult->fetch();
				   $winner = $winnerRow["bidderID"];
				   if (strlen($winner) > 0)
						echo $winner ."</td><td>";
				   else 
					   echo "No bidders</td><td>";
			    } catch (PDOException $e) {
				        echo "Winner query failed: " . $e->getMessage();
				}
		   } else {
			   $bidArray = array();	
			   drawBidButton($row["itemID"], $row["name"], $numBids);
			   echo "</td><td>";
		   }
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

