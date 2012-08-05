<?php
include ("./_header.php");
?>

<div class="span12">
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
	    }
	    echo "<br/>";
	  ?>
	  <h2>Filter your search</h2><br/>
	  <form class="well form-inline" id="filter-form" action="#" method="get">
	                       <?php
	                         include ('./filter_form.php');
	                       ?>
	                       </form>
	<div id="query-info"></div>
	<table class="table table-striped" id="items-table"></table>
	<div id="search-results"></div>
</div>
			
<script type="text/javascript">
	$('#filter-form').submit(function() {
		var data = $(this).serializeArray();
		$('#items-table').load("items-table.php", data);
		return false;
	});

	function filterByCategory() {
		var category = this.childNodes[0].nodeValue();
		// draw items with ajax
		drawItems($selectedtime, $id);
	}
</script>

<?php
include("./_footer.php");
?>