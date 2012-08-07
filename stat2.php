<?php
include('sqlitedb.php');
?>

<?php
	for ($i = 0; $i < 20; $i++) {
		$priceRanges[] = array(10*$i, 10*($i + 1));
	}
	$priceRanges[] = array(200, 100000);
	
	$priceRangeCounts = array();
	
	for ($i = 0; $i < count($priceRanges); $i++) {
		try {
			$priceRangeQuery = "select count(*) as ct from Item where numberOfBids > 0 and ends < '2001-12-20 00:00:01' and currently >= ". $priceRanges[$i][0]." and currently < ". $priceRanges[$i][1].";";
			//echo '<p style="color:purple;">Query is '.$priceRangeQuery.'.</p>';
			$priceRangeResult = $db->query($priceRangeQuery);
			$priceRangeRow = $priceRangeResult->fetch();
			$priceRangeCounts[] = $priceRangeRow["ct"];
		} catch (PDOException $e) {
			  echo "Price range query failed: " . $e->getMessage();
		}
	}
	

	
	/*
	for ($i = 0; $i < count($priceRangeCounts); $i++) {
		echo '<p style="color: orange">Price range count is: '.$priceRangeCounts[$i].'</p>';
	}
	*/
?>
		<div class="well" style="padding: 8px 0;">
			<ul class="nav nav-list">
				<li class = "nav-header">
					Sold Items by Price
				</li>
				<li>
					<table style="min-width: 100%;">
						<tbody>
							<?php
								for ($i = 0; $i < count($priceRangeCounts); $i++) {
									echo '<tr>
											<td><p class="alignleft">$'.$priceRanges[$i][0].' - $'. $priceRanges[$i][1] .'</p></td>
											<td><strong class="alignright">'.$priceRangeCounts[$i].'</strong></td>
										</tr>';
								}			
							?>
							<tr>
								<td><p></p></td>
								<td><strong></strong></td>
							</tr>
							<tr>
								<td><p></p></td>
								<td><strong></strong></td>
							</tr>
						</tbody>
					</table>
				</li>
			</ul>
		</div>	
