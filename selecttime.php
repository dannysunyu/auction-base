<!DOCTYPE html>
<html lang="en">
<head>
<title>AuctionBase</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css"/>
</head>
<body>
	<div class="container">
		<div class="row-fluid">
			<div class="span2">
				<h3>hey</h3>
			</div>
			<div class="span10">
				<?php 
				  include ('./navbar.html');
				?>

				<center>
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
				    echo "<center>Please select a new time:</center>";
				  ?>
				  <form method="POST" action="selecttime.php">
				  <?php 
				    include ('./timetable.html');
				  ?>
				  </form>

				</center>
			</div>
		</div>
	</div>
</body>
</html>
