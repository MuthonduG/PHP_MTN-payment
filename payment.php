<?php
    include "accesstoken.php";
    $phone = '256772123456';
    $url = "https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay";
    $headers = array(
        'Authorization: Bearer '.$access_token,
        'X-Reference-Id: '. $reference_id,
        'X-Target-Environment: sandbox',
        'Content-Type: application/json',
        'Ocp-Apim-Subscription-Key: '.$secodary_key
    );

    $external_id = rand(10000000, 99999999);

    $body = array(
        'amount' => '5.0',
        'currency' => 'EUR',
        "externalId" => $external_id,
        'payer' => array(
            'partyIdType' => 'MSISDN',
            'partyId' => $phone
        ),
        'payerMessage' => 'Umeskia Softwares MTN Payment',
        'payeeNote' => 'Thank you for using Umeskia Softwares MTN Payment'
    );
    $json_body = json_encode($body);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $json_body
    ));
    $response = curl_exec($curl);
    if(curl_errno($curl)) {
        $error_msg = curl_error($curl);
        echo "cURL Error: " . $error_msg;
    }
    curl_close($curl);
    if (curl_errno($curl)) {
    $error_msg = curl_error($curl);
    echo "cURL Error: " . $error_msg;
    } else {
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($httpcode == 202) {
        echo 'Request successfully, Ref ID : '. $reference_id .' & response status code is : ' . $httpcode;
    } else {
        echo 'Request successfully, Response status code is : ' . $httpcode;
        echo "<br>";
        echo "Error : " . $response;
    }
    }
?>