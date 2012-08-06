<?php
include ('sqlitedb.php');	
?>

<?php 
	$bid = floatval($_REQUEST["bid"]);
	$result = "failure";
	if ($bid > 0) {		
		/* insert the user into the db if they have never bid on an item before */
		try {
			$db->beginTransaction();			
			$userIsNew = True;
			$userQuery = "select * from Bidder where bidderID = '".$_REQUEST["user"]. "'";
			$userResult = $db->query($userQuery);
			while ($userRow = $userResult->fetch()) {
				$userIsNew = False;
			}
			if ($userIsNew) {
				$bidderInsert = $db->prepare("insert into Bidder (bidderID, rating, location, country) values(?,?,?,?)");
				$userArr = array($_REQUEST["user"], "25", "California", "USA");
				$bidderInsert->execute($userArr);
			}

			$bidInsert = $db->prepare("insert into Bid (itemID, bidderID, time, amount) values(?,?,?,?)");
			$arr = array($_REQUEST["itemID"], $_REQUEST["user"], $_REQUEST["selectedTime"], $_REQUEST["bid"]);

			$bidInsert->execute($arr);
			$itemUpdate = "update Item set currently = ".$_REQUEST["bid"].", numberOfBids = numberOfBids + 1 where itemID = ".$_REQUEST["itemID"];
			$db->exec($itemUpdate);

			$db->commit();
			$result = "success";
		} catch (Exception $e) {
			try {
				$db->rollBack();
			} catch (PDOException $pe) {
				$warnings .= "<p>Couldn't roll back. </p>";
			}
			$warnings .= "<p>Transaction falied: " . $e->getMessage() . "</p>";
		}		
	}
	else {
		$warnings .= '<script type="text/javascript"> alert("Your bid of '.money_format('$%i', $bid).' is invalid.") </script>';
	} 
	echo json_encode(array("result" => $result, "bid" => $bid, "warnings" => $warnings));
?>