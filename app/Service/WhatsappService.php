<?php

namespace App\Service;

class WhatsappService
{
    private string $token;
    private string $url;

    public function __construct()
    {
        $this->token = '2jQR5L8UL9hN5qbuEJZY';
        $this->url   = 'https://api.fonnte.com/send';
    }
    
    public function send(string $target, string $message): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target'  => $target,
                'message' => $message
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->token
            ]
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true) ?? [];
    }
}