<?php

require __DIR__ . '/vendor/autoload.php';

use Httpful\Request;

class Weather {

  //All calls to the yahoo weather API happen here!
  private function yahooAPICall($city, $region) {
    $city = str_replace(' ', '%20', $city);
    $region = str_replace(' ', '%20', $region);

    $url = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text%3D%22".$city.",".$region."%E2%80%8B%22)&format=json";
    $response = Request::get($url)->send();
    return $response;
  }

  //Function to convert farenheit to celcius
  private function convertToCelsius($far) {
    return round(($far-32)*(5/9), 1);
  }

  //Function to return JSON of five day forecast of a given city and region
  public function fiveDayInfo($city, $region) {
    $response = $this->yahooAPICall($city, $region);

    //the forecast object contains all the info we need. Grab the first five items (days)
    $fiveDayArr = array_slice($response->body->query->results->channel->item->forecast, 0, 5);

    foreach($fiveDayArr as $day){
      $day->high = $this->convertToCelsius($day->high);
      $day->low = $this->convertToCelsius($day->low);
    }

    return $fiveDayArr;
  }

  //Function to return JSON of weather of all cities in cityArray
  public function weatherList($cityArray) {
    $responseArray = [];

    //Loop through all cities
    foreach ($cityArray as $key => $info) {
      $city = $info->city;
      $region = $info->region;
      $response = $this->yahooAPICall($city, $region);

      //grab all relevant info from the response
      $code = $response->body->query->results->channel->item->condition->code;
      $text = $response->body->query->results->channel->item->condition->text;
      $high = $this->convertToCelsius($response->body->query->results->channel->item->forecast[0]->high);
      $low = $this->convertToCelsius($response->body->query->results->channel->item->forecast[0]->low);

      $responseArray[] = ["city" => $city, "region" => $region, "code" => $code, "text" => $text, "high" => $high, "low" => $low];
    }

    return json_encode($responseArray);
  }
}
?>
