<?php
	include ('sqlitedb.php');	
?>

<?php 
	$bid = floatval($_POST["bid"]);	
	$currentPrice = $_POST["currentPrice"];

	$result = "failure";
	if ($bid >= $currentPrice + 1) {		
		/* insert the user into the db if they have never bid on an item before */
		try {
			$db->beginTransaction();			
			$userIsNew = True;
			$userQuery = "select * from Bidder where bidderID = '".$_POST["user"]. "'";
			$userResult = $db->query($userQuery);
			while ($userRow = $userResult->fetch()) {
				$userIsNew = False;
			}
			if ($userIsNew) {
				$bidderInsert = $db->prepare("insert into Bidder (bidderID, rating, location, country) values(?,?,?,?)");
				$userArr = array($_POST["user"], "25", "California", "USA");
				$bidderInsert->execute($userArr);
			}

			$bidInsert = $db->prepare("insert into Bid (itemID, bidderID, time, amount) values(?,?,?,?)");
			$arr = array($_POST["itemID"], $_POST["user"], $_POST["selectedTime"], $_POST["bid"]);
			$bidInsert->execute($arr);

			/* I don't need to set currently or numberOfBids, because they're irrelevant when the time is not the most recent time */
			$db->commit();
			$result = "success";
		} catch (Exception $e) {
			try {
				$db->rollBack();
			} catch (PDOException $pe) {
				$warnings .= "Couldn't roll back.";
			}
			$warnings .= "Transaction falied: " . $e->getMessage();
		}		
	}
	else {
		$warnings .= 'You must bid at least '.money_format('$%i', $currentPrice + 1).' .';
	} 
	echo json_encode(array("result" => $result, "warnings" => $warnings));
?>