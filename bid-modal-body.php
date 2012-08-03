<?php
include ('./sqlitedb.php');	
?>

<?php
$itemID = $_POST["itemID"];

$itemQuery = 'select sellerID, Seller.rating, description, Item.location, Item.country, started, ends, from Item NATURAL JOIN Seller where itemID ='.$_POST["itemID"];	



$historyQuery = 'select bidderID, time, amount, rating from Bid NATURAL JOIN Bidder where itemID = '.$itemID;
?>