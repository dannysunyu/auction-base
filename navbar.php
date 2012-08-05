
<?php 

function echoActiveClassIfRequestMatches($requestUri)
{
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");
    if ($current_file_name == $requestUri)
        echo 'class="active"';
}

?>

<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">AuctionBase</a>
			<div class="nav-collapse">
					<ul class="nav">
						<li <?=echoActiveClassIfRequestMatches("home")?>><a href="home.php">Search</a></li>
						<li <?=echoActiveClassIfRequestMatches("about")?>><a href="about.php">About</a></li>
					</ul>
			</div>
		</div>
	</div>
</div>