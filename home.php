<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
<meta charset="UTF-8">
<title>AuctionBase</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css"/>
</head>
<style>
body {padding-top: 60px;}
</style>
<body>
	<?php 
	  include ('./navbar.html');
	  include ('./sqlitedb.php');	
	?>
	<div class="container">
		<div class="row-fluid">
			<div class="span3">
				<div class="well" style="padding: 8px 0;">
					<ul class="nav nav-list">
						<li class="nav-header">
							I'm a sidebar.
						</li>
						<?php
						for ($i = 0; $i < 30; $i+=1)
							echo ("<li>hey " . $i . "</li>");
						?>
					</ul>
				</div>
			</div>
			<div class="span9">
				  <?php
				    $MM = $_POST["MM"];
				    $dd = $_POST["dd"];
				    $yyyy = $_POST["yyyy"];
				    $HH = $_POST["HH"];
				    $mm = $_POST["mm"];
				    $ss = $_POST["ss"];    
				    $user = htmlspecialchars($_POST["user"]);
    
				    if($_POST["MM"]) {
				      $selectedtime = $yyyy."-".$MM."-".$dd." ".$HH.":".$mm.":".$ss;
				      echo "<center> (Hello, ".$user.". Previously selected time was: ".$selectedtime.".)</center>";
				    }
				    echo "<br/>";
				  ?>
				  <form class="well form-inline" id="filter-form" action="#" method="GET">
				                       <?php
				                         include ('./filter_form.php');
				                       ?>
				                       </form>
				  
				<table class="table table-striped" id="items-table"></table>
			</div>
		</div>
	</div>
</body>
</html>


<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
//	drawItems("2001-12-22 00:00:01", "Rose", "Boats", 900, 300, 738934008);
//	console.log("Done drawing!");
});


$('#filter-form').submit(function() {
	var data = $(this).serializeArray();
	$('#items-table').load("drawer.php", data);
	return false;
});


function filterByCategory() {
	var category = this.childNodes[0].nodeValue();
	// draw items with ajax
	drawItems($selectedtime, $id)
}
</script>