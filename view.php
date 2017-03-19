<?php

require_once("Weather.php");
require_once("html_table.class.php"); //One of my favorite libraries. Does not have a composer package though!

//Need a city and region
if(empty($_GET['city']) || empty($_GET['region'])) {
  header("Location: list.php");
  die();
}

$city = $_GET['city'];
$region = $_GET['region'];

echo "<h1>".$city."</h1>";

//API Call
$weather = new Weather;
$response = $weather->fiveDayInfo($city, $region);

//If the API call fails
if(empty($response)) {
  echo "Something went wrong! Please try again later.";
  echo "<BR><a href='list.php'>Return to list</a>";
  die;
}

//Format response in a table using the HTML_Table class
$tbl = new HTML_Table('', 'forecast_table');

$tbl->addRow();
$tbl->addCell('Date', '', 'header', ["width"=>"100", "align" => "left"]);
$tbl->addCell('Conditions', '', 'header', ["width"=>"230" , "colspan" => 2, "align" => "left"]);
$tbl->addCell('High', '', 'header', ["width"=>"100", "align" => "left"]);
$tbl->addCell('Low', '', 'header', ["width"=>"100", "align" => "left"]);

foreach ($response as $day) {
  $tbl->addRow();
  $tbl->addCell($day->date);
  $tbl->addCell("<img src=http://l.yimg.com/a/i/us/we/52/".$day->code.".gif>");
  $tbl->addCell($day->text);
  $tbl->addCell($day->high);
  $tbl->addCell($day->low);
}

//Display the table
echo $tbl->display();
echo "<a href='list.php'>Return to list</a>";

?>
