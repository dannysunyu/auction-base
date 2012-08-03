
<h3>Parameters</h3>
<div class="well">
<?php
	foreach ($_POST as $key => $entry)
	{
	     if (is_array($entry)) {
	        foreach($entry as $value)
	           print $key . ": " . $value . "<br>";
	     } else {
	        print $key . ": " . $entry . "<br>";
	     }
	}

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
	echo "<center> (Hello, ".$user.". Previously selected time was: ".$selectedtime.".)</center>";
?>
</div>

<h3>Query</h3>
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

 $query = "select distinct itemID, name, date(ends) as date_ends from Item";
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

 echo "<div class='well'>" . $query . "</div>";
 ?>