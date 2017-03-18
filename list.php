<?php

require_once("Weather.php");
require_once("html_table.class.php"); //One of my favorite libraries. Does not have a composer package though!

$query = json_decode('[{"city": "Vancouver", "region": "BC", "country" : "Canada"},
          {"city": "Honolulu", "region": "HI", "country": "United States"},
          {"city": "San Diego", "region": "CA", "country" : "United States" },
          {"city": "Havana", "region": "CH", "country": "Cuba"} ]');

//API Call
$weather = new Weather;
$response = $weather->weatherList($query);

echo "<h1>Weather List</h1>";

//Format response in a table using the HTML_Table class
$tbl = new HTML_Table('', 'city_table');

$tbl->addRow();
$tbl->addCell('City', '', 'header', ["width"=>"100", "align" => "left"]);
$tbl->addCell('Conditions', '', 'header', ["width"=>"230" , "colspan" => 2, "align" => "left"]);
$tbl->addCell('High', '', 'header', ["width"=>"100", "align" => "left"]);
$tbl->addCell('Low', '', 'header', ["width"=>"100", "align" => "left"]);

foreach (json_decode($response) as $city) {
  $tbl->addRow();
  $link_url = "<a href='view.php?city=".$city->city."&region=".$city->region."'>";
  $tbl->addCell($link_url.$city->city."</a>");
  $tbl->addCell("<img src=http://l.yimg.com/a/i/us/we/52/".$city->code.".gif>");
  $tbl->addCell($city->text);
  $tbl->addCell($city->high);
  $tbl->addCell($city->low);
}


//Display the table
echo $tbl->display();

?>
