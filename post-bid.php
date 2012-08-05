<?php
include ('./sqlitedb.php');	
?>

<?php 
	$bid = floatval($_POST["bid"]);
	$result = "failure";
	$bid = "invalid";
	if ($bid > 0) {		
		/* insert the user into the db if they have never bid on an item before */
		/*
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
			$itemUpdate = "update Item set currently = ".$_POST["bid"].", numberOfBids = numberOfBids + 1 where itemID = ".$_POST["itemID"];
			$db->exec($itemUpdate);

			$db->commit();
			//echo '<script type="text/javascript"> $(document).ready(function() {alert("Congratulations! You are the current highest bidder at '.money_format('$%i', $bid).')}); </script>';
			//echo '{"result": "success", "bid" : "$22"}';
			$result = "success";
			$bid = money_format('$%i', $bid);
			//echo '{"result": "success", "bid" : "'.money_format('$%i', $bid).'"}';
		} catch (Exception $e) {
			try {
				$db->rollBack();
			} catch (PDOException $pe) {
				$warnings .= "<p>Couldn't roll back. </p>";
			}
			$warnings .= "<p>Transaction falied: " . $e->getMessage() . "</p>";
		}
*/			
	}
	else {
		$warnings .= '<script type="text/javascript"> alert("Your bid of '.money_format('$%i', $bid).' is invalid.") </script>';
	} 
	echo json_encode(array("result" => $result, "bid" => $bid, "warnings" => $warnings));
?>