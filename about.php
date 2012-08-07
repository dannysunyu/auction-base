<?php
	include ("_header.php");
?>

<style type="text/css">
    #spinner-container-t {
	  position: fixed;
	  top: 50%;
	  left: 50%;
      color: black;
      width: 50px;
      height: 50px;
	  background: red;
    }
</style>

<div id="stats"></div>

<script type="text/javascript">
	$('document').ready(function() {
		spinner2 = new Spinner().spin(document.getElementById('spinner-container-t'));
		$('#stats').load("stats.php", function() {spinner2.stop();});
	});
</script>

<?php
include("_footer.php");
?>
	