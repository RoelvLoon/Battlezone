<?php
class modOAuth
{

    function errorMessage($message)
    {
        return '<b>Oeps, er is iets misgegaan: </b><br>' . $message;
    }

    function generateRequest($data)
    {
        return $data . '&client_secret=' . urlencode(_OAUTH_SECRET);
    }

    function postRequest($endpoint, $data)
    {
        $ch = curl_init('https://login.microsoftonline.com/' . _OAUTH_TENANTID . '/oauth2/v2.0/' . $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if ($cError = curl_error($ch))
        {
            echo $this->errorMessage($cError);
            exit;
        }
        curl_close($ch);
        return $response;

    }

    function base64UrlEncode($toEncode)
    {
        return str_replace('=', '', strtr(base64_encode($toEncode) , '+/', '-_'));
    }

    function uuid()
    {
        //uuid version 4
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff) , mt_rand(0, 0xffff) ,
        // 16 bits for "time_mid"
        mt_rand(0, 0xffff) ,
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,
        // 48 bits for "node"
        mt_rand(0, 0xffff) , mt_rand(0, 0xffff) , mt_rand(0, 0xffff));
    }

}
?>