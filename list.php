<?php

require_once("Weather.php");

$query = json_decode('[{"city": "Vancouver", "region": "BC", "country" : "Canada"},
          {"city": "Honolulu", "region": "HI", "country": "United States"},
          {"city": "San Diego", "region": "CA", "country" : "United States" },
          {"city": "Havana", "region": "CH", "country": "Cuba"} ]');

$weather = new Weather;
$response = $weather->weatherList($query);

?>
<h1>Weather List</h1>
<table>
  <col width="100">
  <col width="80">
  <col width="150">
  <col width="100">
  <col width="100">
<tr>
  <th align="left">
    City
  </th>
  <th align="left" colspan="2">
    Conditions
  </th>
  <th align="left">
    High
  </th>
  <th align="left">
    Low
  </th>
</tr>
  <?php
  foreach (json_decode($response) as $city) {
    echo "<tr>";
    $link_url = "<a href='view.php?city=".$city->city."&region=".$city->region."'>";
    echo "<td>".$link_url.$city->city."</a></td>";
    echo "<td><img src=http://l.yimg.com/a/i/us/we/52/".$city->code.".gif></td>";
    echo "<td>".$city->text."</td>";
    echo "<td>".$city->high."</td>";
    echo "<td>".$city->low."</td>";
    echo "</tr>";
  }
  ?>
</tr>
</table>
