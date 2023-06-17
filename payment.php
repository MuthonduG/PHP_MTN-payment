<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        define('USER_ID', '');
        define('API_KEY', '');

        
        function get_api_user() {
            $token = get_uuid();
            $api_key = '';

            $post_data = array(
                'providerCallbackHost' => 'https://localhost',
            );

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($curl, CURLOPT_URL, 'https://sandbox.momodeveloper.mtn.com/v1_0/apiuser');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt(
                $curl,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'X-Reference-Id: ' . $token,
                    'Ocp-Apim-Subscription-Key: ' . $api_key
                )
            );

            $result = curl_exec($curl);
            if (!$result) {die("Connection Failure");}
            curl_close($curl);
            echo $result;
        };

        function get_uuid() {
            return sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
        }
        
        function get_access_token() {
            $credentials =base64_encode(USER_ID.':'.API_KEY);
            $ch = curl_init('https://sandbox.momodeveloper.mtn.com/collection/token/');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Authorization: Basic '.$credentials,
                    'Content-Type : application/json',
                    'Ocp-Apim-Subscription-Key: '.COLLECTION_SUBSCRIPTION_KEY
                )
            );
            $response = curl_exec($ch);
            $response = json_decode($response);

            $access_token = $response->access_token;
            if(!$access_tokenaccess){
                throw new Exception("Invalid access token generated");
                return FALSE;
            }
            return $access_token;
        }

        function request_pay(){
            $access_token = get_access_token();
            $endpoint_url = 'https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay';

            $data = array(
                "amount" => "1",
                "currency" => "EUR",
                "externalId" => "123456",

                "payer" => array(
                    "partyIdType" => "MSISDN",
                    "partyId" => "254795107488"
                ),

                "payerMessage"=> "Payment Request",
                "payeeNote"=> "Please confirm payment"
            );

            $data_string = json_encode($data);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $endpoint_url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

            curl_setopt(
                $curl,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$access_token,
                    'X-Reference-Id: '.get_uuid(),
                    'X-Target-Environment: sandbox',
                    'Ocp-Apim-Subscription-Key: '.COLLECTION_SUBSCRIPTION_KEY,
                )
            );

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
        }
    ?>
</body>
</html>