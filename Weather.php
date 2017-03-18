<?php

require __DIR__ . '/vendor/autoload.php';

use Httpful\Request;

class Weather {

  private function yahooAPICall($city, $region) {
    $city = str_replace(' ', '%20', $city);
    $region = str_replace(' ', '%20', $region);

    $url = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text%3D%22".$city.",".$region."%E2%80%8B%22)&format=json";
    $response = Request::get($url)->send();
    return $response;
  }

  public function convertToCelcius($far) {
    return round(($far-32)*(5/9), 1);
  }

  public function fiveDayInfo($city, $region) {
    $response = $this->yahooAPICall($city, $region);
    $fiveDayArr = array_slice($response->body->query->results->channel->item->forecast, 0, 5);

    foreach($fiveDayArr as $day){
      $day->high = $this->convertToCelcius($day->high);
      $day->low = $this->convertToCelcius($day->low);
    }

    return $fiveDayArr;
  }

  public function weatherList($cityArray) {

    $responseArray = [];

    foreach ($cityArray as $key => $info) {
      $city = $info->city;
      $region = $info->region;
      $response = $this->yahooAPICall($city, $region);

      $code = $response->body->query->results->channel->item->condition->code;
      $text = $response->body->query->results->channel->item->condition->text;
      $high = $this->convertToCelcius($response->body->query->results->channel->item->forecast[0]->high);
      $low = $this->convertToCelcius($response->body->query->results->channel->item->forecast[0]->low);

      $responseArray[] = ["city" => $city, "region" => $region, "code" => $code, "text" => $text, "high" => $high, "low" => $low];

    }

    return json_encode($responseArray);
  }
}




?>
