<?php
include ('./sqlitedb.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>AuctionBase</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css"/>
</head>
<style>
body {padding-top: 60px;}
</style>
<body>
	<?php 
	  include ('./navbar.html');
	?>
	<div class="container">
		<div class="row-fluid">
			<div class="span3">
				<div class="well" style="padding: 8px 0;">
					<ul class="nav nav-list">
						<li class="nav-header">
							Categories
						</li>
						<?php
						$query = "select distinct category from Category";
						try {
							$result = $db->query($query);
							while ($row = $result->fetch()) {
								echo "<li>" . htmlspecialchars($row["category"]) . "</li>";
							}
						} catch (PDOException $e) {
							echo "Item query failed: " . $e->getMessage();
						}
						?>
					</ul>
				</div>
			</div>
			<div class="span9">
				<h3> Select a Time </h3> 

				  <?php
				    $MM = $_POST["MM"];
				    $dd = $_POST["dd"];
				    $yyyy = $_POST["yyyy"];
				    $HH = $_POST["HH"];
				    $mm = $_POST["mm"];
				    $ss = $_POST["ss"];    
				    $entername = htmlspecialchars($_POST["entername"]);
    
				    if($_POST["MM"]) {
				      $selectedtime = $yyyy."-".$MM."-".$dd." ".$HH.":".$mm.":".$ss;
				      echo "<center> (Hello, ".$entername.". Previously selected time was: ".$selectedtime.".)</center>";
				    }
				    echo "<br/>";
				  ?>
				  <form class="well form-inline" method="POST" action="selecttime.php">
				  <?php 
				    include ('./filter_form.html');
				  ?>
				  </form>
				<table class="table table-striped">
					<thead>
							<tr>
								<th>Name</th>
								<th>Open/Closed</th>
								<th>Winner</th>
								<th>Category</th>
							</tr>
					</thead>
					<?php
					$query = "select name, date(ends) as date_ends from Item";
					try {
						$result = $db->query($query);
						$currenttime = $db->query("select date('currenttime') from Time")->fetch();
						while ($row = $result->fetch()) {
							echo "<tr><td>" . htmlspecialchars($row["name"]) . "</td><td>";
							if ($row["date_ends"] < $currenttime) {
								echo "Closed";
							} else {
								echo "Open";
							}
							echo "</td><td>the winner</td><td>the category</td></tr>";
						}
					} catch (PDOException $e) {
						echo "Item query failed: " . $e->getMessage();
					}
					?>
				</table>
			</div>
		</div>
	</div>
</body>
</html>
