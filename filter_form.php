<?php 
	  include ('./sqlitedb.php');	
?>

<table>
    <tr>
        <td>
<p>
	
Month:
<select class="input-small" size="1" name="MM">
    <option selected value="01">Jan</option>
    <option value="02">Feb</option>
    <option value="03">Mar</option>
    <option value="04">Apr</option>
    <option value="05">May</option>
    <option value="06">Jun</option>
    <option value="07">Jul</option>
    <option value="08">Aug</option>
    <option value="09">Sep</option>
    <option value="10">Oct</option>
    <option value="11">Nov</option>
    <option value="12">Dec</option>
</select>
Day:
<select class="input-small" size="1" name="dd">
    <option selected>01</option>
    <option>02</option>
    <option>03</option>
    <option>04</option>
    <option>05</option>
    <option>06</option>
    <option>07</option>
    <option>08</option>
    <option>09</option>
    <option>10</option>
    <option>11</option>
    <option>12</option>
    <option>13</option>
    <option>14</option>
    <option>15</option>
    <option>16</option>
    <option>17</option>
    <option>18</option>
    <option>19</option>
    <option>20</option>
    <option>21</option>
    <option>22</option>
    <option>23</option>
    <option>24</option>
    <option>25</option>
    <option>26</option>
    <option>27</option>
    <option>28</option>
    <option>29</option>
    <option>30</option>
    <option>31</option>
</select>
Year:
<select class="input-small" size="1" name="yyyy">
    <option>1999</option>
    <option>2000</option>
    <option selected>2001</option>
    <option>2002</option>
    <option>2003</option>
    <option>2004</option>
    <option>2005</option>
    <option>2006</option>
</select>
</p>
<p>
Hour: 
<select class="input-small" size="1" name="HH">
    <option>00</option>
    <option>01</option>
    <option>02</option>
    <option>03</option>
    <option>04</option>
    <option>05</option>
    <option>06</option>
    <option>07</option>
    <option>08</option>
    <option>09</option>
    <option>10</option>
    <option>11</option>
    <option selected>12</option>
    <option>13</option>
    <option>14</option>
    <option>15</option>
    <option>16</option>
    <option>17</option>
    <option>18</option>
    <option>19</option>
    <option>20</option>
    <option>21</option>
    <option>22</option>
    <option>23</option>
</select>
Minute:
<select class="input-small" size="1" name="mm">
    <option selected>00</option>
    <option>01</option>
    <option>02</option>
    <option>03</option>
    <option>04</option>
    <option>05</option>
    <option>06</option>
    <option>07</option>
    <option>08</option>
    <option>09</option>
    <option>10</option>
    <option>11</option>
    <option>12</option>
    <option>13</option>
    <option>14</option>
    <option>15</option>
    <option>16</option>
    <option>17</option>
    <option>18</option>
    <option>19</option>
    <option>20</option>
    <option>21</option>
    <option>22</option>
    <option>23</option>
    <option>24</option>
    <option>25</option>
    <option>26</option>
    <option>27</option>
    <option>28</option>
    <option>29</option>
    <option>30</option>
    <option>31</option>
    <option>32</option>
    <option>33</option>
    <option>34</option>
    <option>35</option>
    <option>36</option>
    <option>37</option>
    <option>38</option>
    <option>39</option>
    <option>40</option>
    <option>41</option>
    <option>42</option>
    <option>43</option>
    <option>44</option>
    <option>45</option>
    <option>46</option>
    <option>47</option>
    <option>48</option>
    <option>49</option>
    <option>50</option>
    <option>51</option>
    <option>52</option>
    <option>53</option>
    <option>54</option>
    <option>55</option>
    <option>56</option>
    <option>57</option>
    <option>58</option>
    <option>59</option>
</select>
Second: 
<select class="input-small" size="1" name="ss">
    <option selected>00</option>
    <option>01</option>
    <option>02</option>
    <option>03</option>
    <option>04</option>
    <option>05</option>
    <option>06</option>
    <option>07</option>
    <option>08</option>
    <option>09</option>
    <option>10</option>
    <option>11</option>
    <option>12</option>
    <option>13</option>
    <option>14</option>
    <option>15</option>
    <option>16</option>
    <option>17</option>
    <option>18</option>
    <option>19</option>
    <option>20</option>
    <option>21</option>
    <option>22</option>
    <option>23</option>
    <option>24</option>
    <option>25</option>
    <option>26</option>
    <option>27</option>
    <option>28</option>
    <option>29</option>
    <option>30</option>
    <option>31</option>
    <option>32</option>
    <option>33</option>
    <option>34</option>
    <option>35</option>
    <option>36</option>
    <option>37</option>
    <option>38</option>
    <option>39</option>
    <option>40</option>
    <option>41</option>
    <option>42</option>
    <option>43</option>
    <option>44</option>
    <option>45</option>
    <option>46</option>
    <option>47</option>
    <option>48</option>
    <option>49</option>
    <option>50</option>
    <option>51</option>
    <option>52</option>
    <option>53</option>
    <option>54</option>
    <option>55</option>
    <option>56</option>
    <option>57</option>
    <option>58</option>
    <option>59</option>
</select>
</p>
<p>Please enter your name: <input type="text" name="user" value="Dr. Clock"></p>

<div class="controls">
<span class="input-prepend input-append">
  <span class="add-on">$</span><input id="appendedPrependedInputMax" class="span2" type="text" size="80"
  placeholder="Max Price"></input><span class="add-on">.00</span>
</span>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span class="input-prepend input-append">
  <span class="add-on">$</span><input id="appendedPrependedInputMin" class="span2" type="text" size="80"
  placeholder="Min Price"></input><span class="add-on">.00</span>
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
				echo "<option value ='" . htmlspecialchars($row["category"]) . "'>" . htmlspecialchars($row["category"]) . "</option>";
			}
		} catch (PDOException $e) {
			echo "Item query failed: " . $e->getMessage();
		}
	?>
	</select>	
</p>	

<div class="controls">
<label class="radio">
  <input id="optionsRadios1" type="radio" checked="" value="open"
	 name="openOrClosed">
  Open
  </label>
<br/>
<label class="radio">
  <input id="optionsRadios2" type="radio" checked="" value="closed"
	 name="openOrClosed">
  Closed
</label>
<br/>
<label class="radio">
  <input id="optionsRadios3" type="radio" checked="" value="either"
         name="openOrClosed">
  Either
</label>
</div>



<br/>
<p><input type="text" name="itemID" placeholder="Item ID"></p>
<br/>

<p><input type="submit" class="btn" value="Submit"></p>
        </td>
    </tr>
</table>