<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpWebhook
{
    /**
     * Undocumented function
     *
     * @param [type] $url
     * @param [type] $headers
     * @param [type] $body
     * @return void
     */
    public static function makeRequest($method, $url, $options = [])
    {
        try {
            $client = new Client();
            $options = array_merge($options, [
                'allow_redirects' => false,
                'timeout' => 5,
                'connect_timeout' => 5,
            ]);
            $request = $client->request($method, $url, $options);
            
            $response = [];
            if (preg_match('/2[0-9][0-9]/', $request->getStatusCode())) {
                $response = json_decode($request->getBody()->getContents());
            }
            
            return $response;
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                return null;
            }
        }
    }
}
