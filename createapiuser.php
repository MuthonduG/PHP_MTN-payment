<?php
function generate_uuid()
{
  return sprintf(
    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff)
  );
}
$reference_id = generate_uuid();
$secondary_key = "0041b35c62984ac293d5b39c582c266c";
$url = 'https://sandbox.momodeveloper.mtn.com/v1_0/apiuser';
$data = array(
  'providerCallbackHost' => 'https://webhook.site/3d0fbc08-256a-4dc3-a66d-0f43ac8f3fa4'
);

$headers = array(
  'Content-Type: application/json',
  'X-Reference-Id: '. $reference_id,
  'Ocp-Apim-Subscription-Key: '. $secondary_key
);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode($data),
  CURLOPT_HTTPHEADER => $headers
));
$response = curl_exec($curl);
if (curl_errno($curl)) {
  $error_msg = curl_error($curl);
  echo "cURL Error: " . $error_msg;
} else {
  $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);
  if ($httpcode == 201) {
    echo 'API user created successfully, Ref ID : '. $reference_id .' & response status code is : ' . $httpcode;
  } else {
    echo 'API user creation failed, Response status code is : ' . $httpcode;
    echo "<br>";
    echo "Error : " . $response;
  }
}