<?php
require_once './inc/mysql.php';
require_once './inc/oauth.php';

session_start();
$modDB = new modDB();
$oAuth = new modOAuth();
if ($_GET['error'])
{
    echo $oAuth->errorMessage($_GET['error_description']);
    exit;
}

// Haal session data op vanuit database
$sessionData = $modDB->QuerySingle('SELECT * FROM tblAuthSessions WHERE txtSessionKey=\'' . $modDB->Escape($_SESSION['sessionkey']) . '\'');

if ($sessionData)
{
    // Vraag token aan van Microsoft
    $oauthRequest = $oAuth->generateRequest('grant_type=authorization_code&client_id=' . _OAUTH_CLIENTID . '&redirect_uri=' . urlencode(_URL . _URLPATH . '/' . _OAUTH_CALLBACK) . '&code=' . $_GET['code'] . '&code_verifier=' . $sessionData['txtCodeVerifier']);

    $response = $oAuth->postRequest('token', $oauthRequest);

    // Decodeer de response van Microsoft. Pak de JWT data uit van de access_token en id_token en update de database.
    if (!$response)
    {
        echo $oAuth->errorMessage('Unknown error acquiring token');
        exit;
    }
    $reply = json_decode($response);
    if ($reply->error)
    {
        echo $oAuth->errorMessage($reply->error_description);
        exit;
    }

    $idToken = base64_decode(explode('.', $reply->id_token) [1]);
    $modDB->Update('tblAuthSessions', array(
        'txtToken' => $reply->access_token,
        'txtRefreshToken' => $reply->refresh_token,
        'txtIDToken' => $idToken,
        'txtRedir' => '',
        'dtExpires' => date('Y-m-d H:i:s', strtotime('+' . $reply->expires_in . ' seconds'))
    ) , array(
        'intAuthID' => $sessionData['intAuthID']
    ));
    // Stuur de gebruiker terug naar waar het vandaan kwam.
    header('Location: ' . $sessionData['txtRedir']);
}
else
{
    header('Location: /');
}
?>