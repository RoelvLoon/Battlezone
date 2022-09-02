<?php
require_once dirname(__FILE__) . '/auth.php';

// In dit bestand worden GET-requests verzonden naar de Microsoft Graph API.
// Dit is een API waarmee je veel gegevens van je Microsoft account kan ophalen (en ook posten!).
// Om alle mogelijkheden te bekijken, kun je naar https://developer.microsoft.com/en-us/graph/graph-explorer gaan.
// Optioneel: klik dan op "Sign in to Graph Explorer" en gebruik je @glr.nl account om de Graph Explorer te gebruiken onder je eigen gegevens.

class modGraph
{
    var $modAuth;

    function __construct()
    {
        $this->modAuth = new modAuth();
    }

    function getProfile()
    {
        $profile = json_decode($this->sendGetRequest('https://graph.microsoft.com/v1.0/me/'));
        return $profile;
    }
    
    function getPhoto($user = null)
    {
        if ($user == null) {

            $profile = json_decode($this->sendGetRequest('https://graph.microsoft.com/v1.0/me/'));

            $photoType = json_decode($this->sendGetRequest('https://graph.microsoft.com/v1.0/me/photo'));

            if ($photoType->error) {

                $photo = $this->sendGetRequest('https://ui-avatars.com/api/?background=random&bold=true&name=' . $profile->displayName);
                return 'data:;base64,' . base64_encode($photo) . '';

            } else {

                $photo = $this->sendGetRequest('https://graph.microsoft.com/v1.0/me/photo/%24value');
                return 'data:' . $photoType->{'@odata.mediaContentType'} . ';base64,' . base64_encode($photo) . '';

            }
        } else {

            $profile = json_decode($this->sendGetRequest('https://graph.microsoft.com/v1.0/users/' . $user));

            $photoType = json_decode($this->sendGetRequest('https://graph.microsoft.com/v1.0/users/' . $user . '/photo'));

            if ($photoType->error) {

                $photo = $this->sendGetRequest('https://ui-avatars.com/api/?background=random&bold=true&name=' . $profile->displayName);
                return 'data:image/jpeg;base64,' . base64_encode($photo) . '';

            } else {

                $photo = $this->sendGetRequest('https://graph.microsoft.com/v1.0/users/' . $user . '/photo/%24value');
                return 'data:' . $photoType->{'@odata.mediaContentType'} . ';base64,' . base64_encode($photo) . '';
                
            }
        }
    }

    function sendGetRequest($URL, $ContentType = 'application/json')
    {
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this
                ->modAuth->Token,
            'Content-Type: ' . $ContentType
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }
}
?>