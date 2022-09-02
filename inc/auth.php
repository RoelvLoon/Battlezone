<?php
require_once dirname(__FILE__) . '/mysql.php';
require_once dirname(__FILE__) . '/oauth.php';

class modAuth
{
    var $modDB;
    var $Token;
    var $userData;
    var $userName;
    var $oAuthVerifier;
    var $oAuthChallenge;
    var $oAuthChallengeMethod;
    var $userRoles;
    var $isLoggedIn;
    var $oAuth;

    function __construct($allowAnonymous = '0')
    {
        $this->modDB = new modDB();
        $this->oAuth = new modOAuth();
        session_start();
        $url = _URL . $_SERVER['REQUEST_URI'];

        // Controleer de "sessionkey" met de database. Als het verlopen is of niet bestaat, word je doorgestuurd naar Microsoft
        if (isset($_SESSION['sessionkey']))
        {
            // Controleren of hij nog geldig is
            $res = $this
                ->modDB
                ->QuerySingle('SELECT * FROM tblAuthSessions WHERE txtSessionKey = \'' . $this
                ->modDB
                ->Escape($_SESSION['sessionkey']) . '\'');
            $this->oAuthVerifier = $res['txtCodeVerifier'];
            $this->oAuthChallenge();
            if (!$res || !$res['txtIDToken'])
            {
                // Niet gevonden in database of een lege idtoken
                unset($_SESSION['sessionkey']);
                session_destroy();
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit;
            }
            if ($_GET['action'] == 'logout')
            {
                // Logout actie ontvangen, verwijder de sessie uit de database en verwijder de sessionkey. Stuur daarna door naar de Microsoft logout pagina.
                $this
                    ->modDB
                    ->Delete('tblAuthSessions', array(
                    'intAuthID' => $res['intAuthID']
                ));
                unset($_SESSION['sessionkey']);
                session_destroy();
                header('Location: ' . _OAUTH_LOGOUT);
                exit;
            }
            if (strtotime($res['dtExpires']) < strtotime('+10 minutes'))
            {
                // Poging om de token te vernieuwen
                if ($res['txtRefreshToken'])
                {
                    $oauthRequest = $this
                        ->oAuth
                        ->generateRequest('grant_type=refresh_token&refresh_token=' . $res['txtRefreshToken'] . '&client_id=' . _OAUTH_CLIENTID . '&scope=' . _OAUTH_SCOPE);
                    $response = $this
                        ->oAuth
                        ->postRequest('token', $oauthRequest);
                    $reply = json_decode($response);
                    if ($reply->error)
                    {
                        if (substr($reply->error_description, 0, 12) == 'AADSTS70008:')
                        {
                            // Refresh-token is verlopen
                            $this
                                ->modDB
                                ->Update('tblAuthSessions', array(
                                'txtRedir' => $url,
                                'txtRefreshToken' => '',
                                'dtExpires' => date('Y-m-d H:i:s', strtotime('+5 minutes'))
                            ) , array(
                                'intAuthID' => $res['intAuthID']
                            ));
                            $oAuthURL = 'https://login.microsoftonline.com/' . _OAUTH_TENANTID . '/oauth2/v2.0/' . 'authorize?response_type=code&client_id=' . _OAUTH_CLIENTID . '&redirect_uri=' . urlencode(_URL . _URLPATH . '/' . _OAUTH_CALLBACK) . '&scope=' . _OAUTH_SCOPE . '&code_challenge=' . $this->oAuthChallenge . '&code_challenge_method=' . $this->oAuthChallengeMethod;
                            header('Location: ' . $oAuthURL);
                            exit;
                        }
                        echo $this
                            ->oAuth
                            ->errorMessage($reply->error_description);
                        exit;
                    }
                    $idToken = base64_decode(explode('.', $reply->id_token) [1]);
                    $this
                        ->modDB
                        ->Update('tblAuthSessions', array(
                        'txtToken' => $reply->access_token,
                        'txtRefreshToken' => $reply->refresh_token,
                        'txtIDToken' => $idToken,
                        'txtRedir' => '',
                        'dtExpires' => date('Y-m-d H:i:s', strtotime('+' . $reply->expires_in . ' seconds'))
                    ) , array(
                        'intAuthID' => $res['intAuthID']
                    ));
                    $res['txtToken'] = $reply->access_token;
                }
            }
            // Vul userData en userName in uit de JWT token die in de database is opgeslagen.
            $this->Token = $res['txtToken'];
            if ($res['txtIDToken'])
            {
                $idToken = json_decode($res['txtIDToken']);
                $this->userName = $idToken->preferred_username;
                $this->userRoles = $idToken->roles;
                if (!$idToken->roles)
                {
                    $this->userRoles = array(
                        'Default Access'
                    );
                }
            }
            $this->isLoggedIn = 1;
        }
        else
        {
            if (!$allowAnonymous || $_GET['login'] == '1')
            {
                // Genereer de codeverificatie en challenge
                $this->oAuthChallenge();
                // Genereer een sessionkey en sla deze op in de sessioncookie, zet het vervolgens in de database
                $sessionKey = $this->uuid();
                $_SESSION['sessionkey'] = $sessionKey;
                $this
                    ->modDB
                    ->Insert('tblAuthSessions', array(
                    'txtSessionKey' => $sessionKey,
                    'txtRedir' => $url,
                    'txtCodeVerifier' => $this->oAuthVerifier,
                    'dtExpires' => date('Y-m-d H:i:s', strtotime('+5 minutes'))
                ));
                // Redirect naar de Microsoft login pagina
                $oAuthURL = 'https://login.microsoftonline.com/' . _OAUTH_TENANTID . '/oauth2/v2.0/' . 'authorize?response_type=code&client_id=' . _OAUTH_CLIENTID . '&redirect_uri=' . urlencode(_URL . _URLPATH . '/' . _OAUTH_CALLBACK) . '&scope=' . _OAUTH_SCOPE . '&code_challenge=' . $this->oAuthChallenge . '&code_challenge_method=' . $this->oAuthChallengeMethod;
                header('Location: ' . $oAuthURL);
                exit;
            }
        }
        // Opschonen database
        // Een refresh-token is standaard geldig voor 72 uur, maar er lijkt geen manier te zijn om te zien wanneer een specifieke token verloopt. Dus we gaan er vanuit dat alles wat de 72 uur voorbijgaat, verlopen is en dan verwijderen we ze uit de database.
        $maxRefresh = strtotime('-72 hour');
        $this
            ->modDB
            ->Query('DELETE FROM tblAuthSessions WHERE dtExpires < \'' . date('Y-m-d H:i:s', $maxRefresh) . '\'');
    }

    function checkUserRole($role)
    {
        // Controleer of de gevraagde rol is toegewezen aan de gebruiker
        if (in_array($role, $this->userRoles))
        {
            return 1;
        }
        return;
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

    function oAuthChallenge()
    {
        // Functie om codeverificatie en code-uitdaging te genereren voor oAuth-aanmelding. Zie RFC7636 voor details.
        $verifier = $this->oAuthVerifier;
        if (!$this->oAuthVerifier)
        {
            $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-._~';
            $charLen = strlen($chars) - 1;
            $verifier = '';
            for ($i = 0;$i < 128;$i++)
            {
                $verifier .= $chars[mt_rand(0, $charLen) ];
            }
            $this->oAuthVerifier = $verifier;
        }
        // Challenge = Base64 Url Encode ( SHA256 ( Verifier ) )
        // Pack (H) to convert 64 char hash into 32 byte hex
        // As there is no B64UrlEncode we use strtr to swap +/ for -_ and then strip off the =
        $this->oAuthChallenge = str_replace('=', '', strtr(base64_encode(pack('H*', hash('sha256', $verifier))) , '+/', '-_'));
        $this->oAuthChallengeMethod = 'S256';
    }
}
?>
