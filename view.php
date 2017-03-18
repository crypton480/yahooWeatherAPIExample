<?php

require_once("Weather.php");

if(!isset($_GET['city']) || !isset($_GET['region'])) {
  header("Location: list.php");
  die();
}

$city = $_GET['city'];
$region = $_GET['region'];

$weather = new Weather;
$response = $weather->fiveDayInfo($city, $region);
?>

<h1><?php echo $city ?></h1>

<table>
  <col width="100">
  <col width="80">
  <col width="150">
  <col width="100">
  <col width="100">
  <tr>
    <th align="left">
      Date
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
foreach ($response as $day) {
  echo "<tr>";
  echo "<td>".$day->date."</td>";
  echo "<td><img src=http://l.yimg.com/a/i/us/we/52/".$day->code.".gif></td>";
  echo "<td>".$day->text."</td>";
  echo "<td>".$day->high."</td>";
  echo "<td>".$day->low."</td>";
  echo "</tr>";

}
?>
</table>

<BR>
<a href='list.php'>Return to list</a>
