<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    protected $token;

    public function __construct(){
        $this->token = env('WHATSAPP_API_TOKEN');
    }

    public function send_otp_message($to,$otp_code){
        $to = "92".$to;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.facebook.com/v22.0/604248952770273/messages',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "messaging_product": "whatsapp",
            "to": "'.$to.'",
            "type": "template",
                "template": {
                    "name": "otp_template",
                    "language": {
                        "code": "en_US"
                    },
                    "components": [
                        {
                            "type": "body",
                            "parameters": [
                                {
                                    "type": "text",
                                    "text": "'.$otp_code.'"
                                }
                            ]
                        },
                        {
                            "type": "button",
                            "sub_type": "url",
                            "index": "0",
                            "parameters": [
                            {
                                "type": "text",
                                "text": "'.$otp_code.'"
                            }
                            ]
                        }
                    ]
                }
            }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function upload_media($media){
        $filePath = $media;  // Get the real path of the uploaded file
        $mimeType = mime_content_type($media); // Get the MIME type of the file

        // Ensure the file type is accepted by WhatsApp
        if (!in_array($mimeType, ['application/pdf', 'image/jpeg', 'image/png', 'video/mp4', 'audio/mp4', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            dd("Unsupported file type");
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v22.0/604248952770273/media',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'messaging_product' => 'whatsapp',
                'file' => new \CURLFile($filePath, $mimeType)  // Pass MIME type explicitly
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            Log::info(curl_error($curl));
        } else {
            Log::info("Upload Media: Else LOG".$response);
            return json_decode($response);
        }
    }

    public function send_prescription($data, $response){
        $to = "92".$data->phone_number;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.facebook.com/v22.0/604248952770273/messages',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "messaging_product": "whatsapp",
            "to": "'.$to.'",
            "type": "template",
            "template": {
                "name": "patient_prescription",
                "language": {
                    "code": "en"
                },
                "components": [
                    {
                        "type": "header",
                        "parameters": [
                        {
                            "type": "document",
                            "document": {
                                "id": "'.$response->id.'",
                                "filename": "Prescription.pdf"
                            }
                        }
                        ]
                    },
                    {
                        "type": "body",
                        "parameters": [
                            {
                                "type": "text",
                                "text": "'.$data->name.'"
                            }
                        ]
                    }
                ]
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$this->token,
            'Content-Type: application/json',
            ': '
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        if ($response === false) {
            Log::info(curl_error($curl));
        } else {
            return json_decode($response);
        }

    }
}
