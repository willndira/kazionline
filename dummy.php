<?php
require_once 'neuro/Data.php';

session_start();

var_dump(Data::get_trade_chart($_SESSION["sess_id"]));
/*
$arr = [];
$arr[] = ["t1"=>"car", "price"=>30];
$arr[] = ["t1"=>"car2", "price"=>100];
$arr[] = ["t1"=>"car3", "price"=>10];
$arr[] = ["t1"=>"car4", "price"=>50];

$prices = [];

foreach($arr as $i=>$elem)
{
    $prices[] = $elem["price"];
}

array_multisort($prices, SORT_ASC, SORT_NUMERIC, $arr);

var_dump($arr);
 * 
 */

?>
<!--
<canvas class="flot-base" width="123" height="250" 
        style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 112px; height: 228px;"></canvas>
<div class="flot-text" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; font-size: smaller; color: rgb(84, 84, 84);">
  <div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
    <div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 20px; text-align: center;">DEC</div>
    <div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 29px; text-align: center;">JAN</div>
    <div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 37px; text-align: center;">FEB</div>
    <div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 42px; text-align: center;">MAR</div>
    <div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 52px; text-align: center;">APR</div>
    <div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 59px; text-align: center;">MAY</div>
    <div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 69px; text-align: center;">JUN</div>
    <div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 78px; text-align: center;">JUL</div>
    <div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 83px; text-align: center;">AUG</div>
    <div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 93px; text-align: center;">SEP</div></div>
    <div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
      <div style="position: absolute; top: 198px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 14px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 19px; text-align: right;">0</div>
      <div style="position: absolute; top: 149px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 14px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 7px; text-align: right;">500</div>
      <div style="position: absolute; top: 101px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 14px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 1px; text-align: right;">1000</div>
      <div style="position: absolute; top: 52px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 14px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 1px; text-align: right;">1500</div>
      <div style="position: absolute; top: 3px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 14px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 1px; text-align: right;">2000</div>
          
    </div></div><canvas class="flot-overlay" width="123" height="250" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 112px; height: 228px;"></canvas></div>
