<?php
	include ("_header.php");
?>

<style type="text/css">
    #spinner-container2 {
	  position: fixed;
	  top: 50%;
	  left: 50%;
      color: black;
      width: 50px;
      height: 50px;
    }
</style>

<div id="spinner-container2"></div>
<div id="stats"></div>

<script type="text/javascript">
	$('document').ready(function() {
		spinner2 = new Spinner().spin(document.getElementById('spinner-container2'));
		$('#stats').load("stats.php", function() {spinner2.stop();});
	});
</script>

<?php
include("_footer.php");
?>
	