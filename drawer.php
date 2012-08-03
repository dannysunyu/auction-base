<?php
include ('./sqlitedb.php');	

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
$openOrClosed = $_POST["openOrClosed"];
$itemID = intval($_POST["itemID"]);
$category = $_POST["category"];
$selectedtime = $yyyy."-".$MM."-".$dd." ".$HH.":".$mm.":".$ss;
echo "<center> (Hello, ".$user.". Previously selected time was: ".$selectedtime.".)</center>";

?>

<thead>
  <tr>
	 <th>Item ID</th>
     <th>Name</th>
     <th>Open/Closed</th>
     <th>Winner</th>
     <th>Category</th>
  </tr>
</thead>

 <?php

 echo "<p>hey</p>";
 $query = "select distinct itemID, name, date(ends) as date_ends from Item";
 $firstCondition = True;
 $needsAnd = False;
 if (strlen($_POST['itemID']) > 0) {
	 if ($firstCondition)
		 $condition = $condition . " where ";
	 $itemID = intval($_POST['itemID']);
	 $condition = $condition . "Item.itemID = " . $itemID;
	 $needsAnd = True;
	 $firstCondition = False;
 } else {
	 if ($category != "All Categories") {
		 if ($firstCondition)
	    	  $condition = $condition . " where ";
		 if ($needsAnd)
			 $condition = $condition . " and ";
		 $condition = $condition . " itemID in (select itemID from Category where category=" . $category . " )";
		 $firstCondition = False;
		 $needsAnd = True; 
	 }
	if ($openOrClosed == "open") {
		 if ($firstCondition)
	    	  $condition = $condition . " where ";
		 if ($needsAnd)
			 $condition = $condition . " and ";
		 $condition = $condition . "date_ends > date('" + $selectedtime + "') ";
		 $firstCondition = False;
		 $needsAnd = True;
	 } else if ($openOrClosed == "closed") {
		 if ($firstCondition)
	    	  $condition = $condition . " where ";
		 if ($needsAnd)
			 $condition = $condition . " and ";
		 $condition = $condition . "date_ends > date('" + $selectedtime + "') ";
		 $firstCondition = False;
		 $needsAnd = True;
	 }
 }
 
 $condition = $condition . ";";
 echo "<p>" . $query . $condition . "</p>";
 try {
      $result = $db->query($query);
      $currenttime = $db->query("select date('currenttime') from Time")->fetch();
      while ($row = $result->fetch()) {
          echo "<tr><td>" . $row["itemID"];
           echo "</td><td>" . htmlspecialchars($row["name"]) . "</td><td>";
           if ($row["date_ends"] < $currenttime) {
                echo "Closed";
           } else {
                echo "Open";
           }
           echo "</td><td>winner</td><td>";
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