<?php 
	  include ('./sqlitedb.php');	
?>

<table>
    <tr>
		<td>

<div class="controls">
<span class="input-prepend input-append">
  <span class="add-on">$</span><input id="appendedPrependedInputMax" name="maxPrice" class="span2" type="text" size="80" placeholder="Max Price" /><span class="add-on">.00</span>
</span>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span class="input-prepend input-append">
  <span class="add-on">$</span><input id="appendedPrependedInputMin" name="minPrice" class="span2" type="text" size="80" placeholder="Min Price" /><span class="add-on">.00</span>
</span>
</div>

<br/>
<p>	Category:   
	<select class="input-large" name="category">
		<option value="All Categories">All Categories</option>
	<?php
		$query = "select distinct category from Category";
		try {
			$result = $db->query($query);
			while ($row = $result->fetch()) {
				echo '<option value ="' . htmlspecialchars($row["category"]) . '">' . htmlspecialchars($row["category"]) . '</option>';
			}
		} catch (PDOException $e) {
			echo "Item query failed: " . $e->getMessage();
		}
	?>
	</select>	
</p>	

<div class="controls">
	<label class="radio">Open</label> 
	<input id="optionsRadios1" type="radio" checked="" value="open" name="openOrClosed" /> 
	<br/>
	<label class="radio">Closed</label> 
	<input id="optionsRadios2" type="radio" checked="" value="closed" name="openOrClosed" />
	<br/>
	<label class="radio">Either</label>
	<input id="optionsRadios3" type="radio" checked="" value="either" name="openOrClosed" />
</div>


<br/>
<p><input type="text" name="itemID" placeholder="Item ID" /></p>
<br/>

<p><input type="submit" id="filter-submit-button" class="btn" value="Submit" /></p>
        </td>
    </tr>
</table>
