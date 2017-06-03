<?php


$content_type = $_SERVER["CONTENT_TYPE"];
$method = $_SERVER['REQUEST_METHOD'];
$content = null;

if($method == "OPTIONS") {

   header("Access-Control-Request-Headers: authorization,content-type");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: authorization,Access-Control-Allow-Origin,Content-Type,X-Requested-With");
    header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
    die();
}



$passy_request = curl_init("https://app.passy.pw/action.php");
curl_setopt($passy_request, CURLOPT_RETURNTRANSFER, true);
curl_setopt($passy_request, CURLOPT_VERBOSE, 1);
curl_setopt($passy_request, CURLOPT_HEADER, 1);
$access_cookie = "";
if($method == "POST") {
    $request_args = array();

    foreach ($_POST as $key => $value) {
        if($key == "access_token") {
            $access_cookie = $value;
            continue;
        }
        $request_args[$key] = $value;
    }
    curl_setopt($passy_request, CURLOPT_POST, 1);
    curl_setopt($passy_request, CURLOPT_POSTFIELDS,
        $request_args);
}
if($access_cookie != "") {
    curl_setopt($passy_request, CURLOPT_HTTPHEADER, array(
        "cookie: $access_cookie"
    ));
}
$response = curl_exec($passy_request);
$header_size = curl_getinfo($passy_request, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = json_decode(substr($response, $header_size), true);
$tokens = array();
$x = false;
foreach (explode("\n", $header) as $item) {
    if(!$x) {
        $x = true;
        continue;
    }
  if(strpos($item, "SCRUMPLEX") !== false) {
        array_push($tokens, substr(explode(";", $item)[0], 12));
      }

}
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
if(count($tokens) != 0) $body["token"] = $tokens;
die(json_encode($body));
