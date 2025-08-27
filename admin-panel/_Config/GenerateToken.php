<?php
    
    //Generate Token
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => ''.$base_url.'/_Api/_GetToken.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_POSTFIELDS =>'{
            "user_key" : "'.$user_key.'",
            "access_key" : "'.$access_key.'"
        }'
    ));

    $response = curl_exec($curl);
    $response_arry=json_decode($response,true);
    $status=$response_arry['status'];
    $message=$response_arry['message'];
    curl_close($curl);
    if($status=='success'){
        $session_token=$response_arry['session_token'];
        $expired_at=$response_arry['expired_at'];
    }else{
        $session_token="";
        $expired_at="";
    }

    $_SESSION["x-token"] = $session_token;
    $_SESSION["x-expired_at"] = $expired_at;
?>