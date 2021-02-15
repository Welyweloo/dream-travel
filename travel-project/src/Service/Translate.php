<?php

namespace App\Service;

use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Json;

class Translate
{

    public function translateToFrench($text)
    {
        $url = 'https://www.googleapis.com/language/translate/v2?key=' . $_ENV['API_KEY_TRANSLATE'] . '&q=' . rawurlencode($text) . '&source=en&target=fr';
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);
        $responseDecoded = json_decode($response, true);
        $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);      //Here we fetch the HTTP response code
        curl_close($handle);

        if ($responseCode != 200) {
            return  new JsonResponse(['responseCode' => $responseCode, 'message' => $responseDecoded['error']['errors'][0]['message']]);
        } else {
            return  new JsonResponse(['Source' => $text, 'Translation' => $responseDecoded['data']['translations'][0]['translatedText']]);
        }
    }
}
