<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Http;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(base_path('firebase_credentials.json'));
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($token, $title, $body)
    {
        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
            'image' => 'https://dummyjson.com/image/150',
        ]);
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification)->withData(['imageUrl' => 'https://dummyjson.com/image/150']);
        try {
            $this->messaging->send($message);
            return ['success' => true, 'message' => 'Notification sent successfully'];
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            return ['success' => false, 'error' => 'Messaging error: ' . $e->getMessage()];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'General error: ' . $e->getMessage()];
        }
    }


    // public function sendNotification($token, $title, $body, $imageUrl = null)
    // {
    //     $credentials = json_decode(file_get_contents(base_path('firebase_credentials.json')), true);
    //     $accessToken = $this->getAccessToken($credentials);

    //     $url = 'https://fcm.googleapis.com/v1/projects/' . $credentials['project_id'] . '/messages:send';
    //     $payload = [
    //         'message' => [
    //             'token' => $token,
    //             'notification' => [
    //                 'title' => $title,
    //                 'body' => $body,
    //                 'image' => $imageUrl,
    //             ],
    //         ],
    //     ];

    //     $response = Http::withToken($accessToken)
    //         ->post($url, $payload);

    //     if ($response->successful()) {
    //         return ['success' => true, 'message' => 'Notification sent successfully'];
    //     }

    //     return ['success' => false, 'error' => $response->json()['error']['message'] ?? 'Failed to send notification'];
    // }

    // private function getAccessToken($credentials)
    // {
    //     $client = new \Google_Client();
    //     $client->setAuthConfig($credentials);
    //     $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    //     return $client->fetchAccessTokenWithAssertion()['access_token'];
    // }
}
