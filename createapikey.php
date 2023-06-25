<?php
include 'createapiuser.php';
$url = "https://sandbox.momodeveloper.mtn.com/v1_0/apiuser/$reference_id/apikey";
$headers = array(
    'Content-Type: application/json',
    'Ocp-Apim-Subscription-Key: '.$secondary_key
);
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_POSTFIELDS => ''
));
curl_setopt($curl, CURLOPT_USERPWD, $secondary_key . ':');
$response = curl_exec($curl);
if(curl_errno($curl)) {
    $error_msg = curl_error($curl);
    echo "cURL Error: " . $error_msg;
}
curl_close($curl);
$data = json_decode($response);
if($data->apiKey) {
   $apikey = $data->apiKey;
} else {
    echo "Failed to generate API key";
}

