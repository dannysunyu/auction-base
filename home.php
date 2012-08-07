<?php
	include("_header.php");
?>

<style type="text/css">
    #spinner-container {
	  position: fixed;
	  top: 50%;
	  left: 50%;
      color: black;
      width: 50px;
      height: 50px;
    }
</style>

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
	  <div id="padding"></div>
	  <span id="spinner-container"></span>
	  <br/>
	  <br/>
	  
      <form class="well form-search search-form" id="term-search-form">
       	<?php include ('search_form.php'); ?>
      </form>
	  
	  <button class="btn" type="submit" id="advanced-search-btn">
	  		  Advanced Search
  	  </button>
	  <br />
	  <br />
	  <form class="well form-inline search-form" id="filter-form" action="#" method="get">
	       <?php include ('filter_form.php'); ?>
	 </form>
	<table class="table table-bordered table-striped" style="background-color: white" id="items-table"></table>
	<div id="search-results"></div>
</div>
			
<script type="text/javascript">
	
	$('.search-form').submit(function() {
		spinner = new Spinner().spin(document.getElementById('spinner-container'));
		if ($('#filter-form').is(':hidden')) {
			var data = $('#term-search-form').serializeArray();
		} else {
			var data = $('#term-search-form, #filter-form').serializeArray();
		}
		$('#items-table').load("items-table.php", data, function() { spinner.stop(); });
		return false;
	});

	function filterByCategory() {
		var category = this.childNodes[0].nodeValue();
		// draw items with ajax
		drawItems($selectedtime, $id);
	}
	
	$('#advanced-search-btn').on('click', function() {
		$("#filter-form").toggle("slow");
	});
</script>

<?php
include("_footer.php");
?>