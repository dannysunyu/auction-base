<?php # currtime.php - show current time
  
  include ('./sqlitedb.php');
?>

<html>
<head>
<title>AuctionBase</title>
</head>

<?php 
  include ('./navbar.php');
?>

<center>
<h3> Current Time </h3> 

<?php
  $query = "select currenttime from Time";
  
  try {
    $result = $db->query($query);
    $row = $result->fetch();
    echo "Current time is: ".htmlspecialchars($row["currenttime"]);
  } catch (PDOException $e) {
    echo "Current time query failed: " . $e->getMessage();
  }
  
  $db = null;
?>

</center>
</html>

