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
	$itemID = intval($_POST["itemID"]);
	$category = $_POST["category"];
	$selectedtime = $yyyy."-".$MM."-".$dd." ".$HH.":".$mm.":".$ss;
?>
<thead>
  <tr>
	 <th>Item ID</th>
     <th>Name</th>
     <th>Open/Closed</th>
	 <th>Current Price</th>
     <th>Winner</th>
     <th>Category</th>
  </tr>
</thead>

<?php
function drawBidButton() {
	echo
	'<a class="btn" data-toggle="modal" href="#myModal" >Bid</a>
    <div class="modal fade hide" id="myModal">
	    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal">×</button>
		    <h3>Modal header</h3>
		</div>
		<div class="modal-body">
		    <p>One fine body…</p>
		</div>
		<div class="modal-footer">
		    <a href="#" class="btn" data-dismiss="modal">Close</a>
		    <a href="#" class="btn btn-primary">Save changes</a>
	    </div>
    </div>';
}

	
?>

<?php
function addCondition(&$oldCondition, &$newConditionFragment, &$needsAnd, &$isFirstCondition)
{
	if ($isFirstCondition)
		$oldCondition = $oldCondition . " where ";
	if ($needsAnd)
		 $oldCondition = $oldCondition . " and ";
	$oldCondition = $oldCondition . $newConditionFragment;
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
	 if ($category != "All Categories") 
		 $conditions[] = "itemID in (select itemID from Category where category=" . $category . ")";
	 if ($maxPrice > 0) 
		 $conditions[] = "Item.currently =< " . $maxPrice;
	 if ($minPrice > 0)
		 $conditions[] = "Item.currently >= " . $minPrice;
	 if ($openOrClosed == "open") {
		 $conditions[] = "date_ends > date('" + $selectedtime + "')";
	 } else if ($openOrClosed == "closed") {
		 $conditions[] = "date_ends > date('" + $selectedtime + "')";
	 }
 }
 
 foreach($conditions as $conditionFragment) {
	 addCondition($condition, $conditionFragment, $needsAnd, $isFirstCondition);
 }
 
 $condition = $condition . ";";
 $query = $query . $condition;
 try {
      $result = $db->query($query);
      $currenttime = $db->query("select date('currenttime') from Time")->fetch();
      while ($row = $result->fetch()) {
          echo "<tr><td>" . $row["itemID"];
           echo "</td><td>" . htmlspecialchars($row["name"]) . "</td><td>";
           $closed = strtotime($row["date_ends"]) < date($selectedtime);
		   echo ($closed ? "Closed" : "Open");
		   echo "</td><td>" . money_format('$%i', floatval($row["currently"])) . "</td><td>";
		   if ($closed) {
			   echo "</td><td>winner</td><td>";
		   } else {
			   drawBidButton();
		   }
           $category_query = "select distinct category from Category where itemID = "
 . $itemID;
           $categories =
 $db->query($category_query);
           while ($category =
 $categories->fetch()) {
                echo " " . $category;
           }
           echo "</td></tr>";
      }
 } catch (PDOException $e) {
      echo "Item query failed: "
 . $e->getMessage();
 }
?>